const amazonPaapi = require('amazon-paapi');
const { Op } = require("sequelize");
const { products, exhibitions, mercariUpdates, users, ng_categories, ng_products, ng_words, settings, postages, prices, categories, categoryIds, mercaris } = require("../models");
const download = require('image-downloader');
const makeDir = require('make-dir');
const archiver = require('archiver');
const fs = require('fs'); // I use fs to read the directories for their contents
const downloader = require('zip-downloader');

class GetProductInfo {
	constructor(user_info, code) {
		this.code = code;
		this.user = user_info; // user_info = dataValues {id: id, family_name:family_name, ....}
	}

	async main() {
		const commonParameters = {
			AccessKey: this.user.dataValues.accesskey,
			SecretKey: this.user.dataValues.secretkey,
			PartnerTag: this.user.dataValues.partnertag,
			PartnerType: 'Associates',
			Marketplace: 'www.amazon.co.jp',
		};

		let requestParameters = {
			ItemIds: this.code,
			ItemIdType: 'ASIN',
			Condition: 'New',
			Resources: [
				"Offers.Listings.DeliveryInfo.IsPrimeEligible",
				'Offers.Listings.Availability.Message',
				"Offers.Summaries.LowestPrice"
			],
		};

		await amazonPaapi.GetItems(commonParameters, requestParameters)
			.then((amazonData) => { // save data into db
				var items = amazonData.ItemsResult.Items;
				for (const item of items) {
					try {
						let query = {};
						let condition = {
							user_id: this.user.dataValues.id,
							ASIN: item.ASIN,
						};
						if (item.Offers.Summaries[0].Condition.Value == 'New') {
							query.price = item.Offers.Summaries[0].LowestPrice.Amount;
							query.r_price = item.Offers.Summaries[0].LowestPrice.Amount;
						} else if (item.Offers.Summaries.length > 1 && item.Offers.Summaries[1].Condition.Value == 'New') {
							query.price = item.Offers.Summaries[1].LowestPrice.Amount;
							query.r_price = item.Offers.Summaries[1].LowestPrice.Amount;
						}
						if (item.Offers !== undefined) {
							if (item.Offers.Listings[0].Availability !== undefined) {
								if (item.Offers.Listings[0].Availability.Message != '在庫あり。') {
									query.inventory = 0;
								}
								else {
									query.inventory = 1;
								}
							}
						}
						products.update(query, { where: condition });
					} catch (err) {
						console.log('forof item error', err.status);
					}
				}
			}).catch(err => {
				console.log('amazonPaapi.GetItems error', err.status);
			});
	}
}

const amazonInfo = async (reqData) => {
	try {
		var index = 0;
		var len = reqData.codes.length;
		let user_info = await users.findOne({ where: { 'id': reqData.user_id } });
		var inputInterval = setInterval(() => {
			if (index < len) {
				let getProductInfo = new GetProductInfo(user_info, reqData.codes.slice(index, (index + 10)));
				getProductInfo.main();
				index += 10;
			} else {
				clearInterval(inputInterval);
			}
		}, 10000);
	} catch (err) {
		console.log('users.findOne error', err);
	}
};

exports.getInfo = (req, res) => {
	let reqData = JSON.parse(req.body.asin);
	amazonInfo(reqData);
	res.send({ msg: "success" });
};

// download images of 1000 target products
const eachIMGdownload = async (data, directoryPath) => {
	await makeDir(directoryPath);
	await data.forEach(async (row) => {
		let image = row.image.split(';');
		// download less than 4 images for every products
		// for ( let i = 0; i < 4; i++ ) {
		for (let i = 0, len = image.length; i < Math.min(4, len); i++) {
			await download.image({
				url: image[i],
				dest: directoryPath + '/' + row.ASIN + '_' + (i + 1) + '.jpg',
			}).then(() => {
				console.log('image download success !');
			}).catch((err) => {
				console.log(i, image[i], err);
			});
		}
	});
};

exports.downloadImages = async (req, res) => {
	let condition = {
		user_id: Number(req.body.user_id),
		exclusion: '',
	};

	let information = {};
	information.images_path = await makeDir('../public/' + condition.user_id);

	// download images of all available target products(1000 once)
	await exhibitions.findAll({ where: condition }, { order: ['id', 'ASC'] })
		.then(async (data) => {
			let len = data.length + 1;
			for (let i = 1000; i < len; i += 1000) {
				eachIMGdownload(data.slice(i - 999, i), information.images_path + '(' + (i - 999) + '-' + i + ')');
			}
			eachIMGdownload(data.slice(len - len % 1000 + 1, len), information.images_path + '(' + (len - len % 1000 + 1) + '-' + (len - 1) + ')');
			console.log('all images saved !');
		})
		.catch(err => {
			console.log('download images', err);
		});
	res.send(information);
}

exports.saveExhibition = async (req, res) => {
	let query_condition = { user_id: Number(req.body.user_id) };

	await exhibitions.destroy({ where: query_condition });

	let amazonData = await products.findAll({ where: query_condition });

	if (amazonData.length != 0) {

		let condition = await settings.findAll({ where: query_condition });
		let ngCategory = await ng_categories.findAll({ where: query_condition });
		let ngWord = await ng_words.findAll({ where: query_condition });
		let ngProduct = await ng_products.findAll({ where: query_condition });
		let setting = await settings.findOne({ where: query_condition });
		let criteria = await postages.findAll();
		let mercari_update = await mercariUpdates.findAll({ where: query_condition });

		let len = criteria.length;
		let patt = [
			"）",
			")",
			"｝",
			"}",
			"］",
			"】",
			"〕",
			"〙",
			"〛",
			"」",
			"』",
			"]",
			"〉",
			"›",
			"》",
			"、",
			"。",
			"»",
			"”",
			" ",
			"～",
		];

		for (let i = 0; i < amazonData.length; i++) {
			if (amazonData[i].flag == 1) {
				let ngCategoriesSearchString = '';
				let exclusion = '';
				let m_category = '';
				let m_category_id = '';
				let product = (amazonData[i].product) ? JSON.parse(amazonData[i].product) : '';
				let feature = (amazonData[i].feature) ? JSON.parse(amazonData[i].feature) : '';
				let feature_1 = (amazonData[i].feature_1) ? JSON.parse(amazonData[i].feature_1) : '';
				let feature_2 = (amazonData[i].feature_2) ? JSON.parse(amazonData[i].feature_2) : '';
				let feature_3 = (amazonData[i].feature_3) ? JSON.parse(amazonData[i].feature_3) : '';
				let feature_4 = (amazonData[i].feature_4) ? JSON.parse(amazonData[i].feature_4) : '';
				let feature_5 = (amazonData[i].feature_4) ? JSON.parse(amazonData[i].feature_4) : '';
				let ngProductSearchString = product + feature + feature_1 + feature_2 + feature_3 + feature_4 + feature_5;
				let profit = await prices.findOne({
					where: {
						down: {
							[Op.lt]: amazonData[i].price,
						},
						up: {
							[Op.gt]: amazonData[i].price,
						}
					}
				});
				let m_category_match = await categories.findOne({ where: { [Op.and]: [{ a_c_root: amazonData[i].a_c_root }, { a_c_sub: amazonData[i].a_c_sub }, { user_id: query_condition.user_id }] } });

				//ng category setting
				ngCategoriesSearchString += amazonData[i].a_c_root + amazonData[i].a_c_sub + amazonData[i].a_c_tree;
				for (let n = 0; n < ngCategory.length; n++) {
					let position = ngCategoriesSearchString.search(ngCategory[n].category)
					if (position) {
						exclusion += '<span class="badge bg-light-warning">NGカテゴリ</span><br />';
					}
				}

				//ng word setting
				for (let m = 0; m < ngProduct.length; m++) {
					let position = ngProductSearchString.search(ngProduct[m].product)
					if (position) {
						exclusion += '<span class="badge bg-light-warning">NGカテゴリ</span><br />';
					}
				}

				//delete word setting in product and feature ...
				for (let a = 0; a < ngWord.length; a++) {
					pattern = "/" + ngWord[a].word + "/i";
					product = product.replace(pattern, '');
					feature = feature.replace(pattern, '');
					feature_1 = feature_1.replace(pattern, '');
					feature_2 = feature_2.replace(pattern, '');
					feature_3 = feature_3.replace(pattern, '');
					feature_4 = feature_4.replace(pattern, '');
					feature_5 = feature_5.replace(pattern, '');
				}
				//setting mercari category
				if (m_category_match == null) {
					exclusion += '<span class="badge bg-light-warning">対象カテゴリーなし</span><br />';
				} else {
					m_category = m_category_match.m_category;
					if (m_category == '削除') {
						exclusion += '<span class="badge bg-light-warning">カテゴリを削除</span><br />';
					}
					match_m_category_id = await categoryIds.findOne({ where: { [Op.and]: [{ all_category: m_category }, { user_id: query_condition.user_id }] } });
					if (match_m_category_id) {
						m_category_id = match_m_category_id.category_id;
					}
				}
				//image chack exist 
				if (amazonData[i].image == '') {
					exclusion += '<span class="badge bg-light-warning">画像なし</span><br />';
				}

				//setting amazon_price
				if (amazonData[i].price == 0) {
					exclusion += '<span class="badge bg-light-warning">Amazon価格が0円です。</span><br />'
				}
				// product_name of option
				let options = (amazonData[i].attribute) ? JSON.parse(amazonData[i].attribute) : null;
				if (options !== null) {
					let a = options.split(';');
					let r = '';
					for (let i = 0; i < a.length; i++) {
						let b = a[i].split(':');
						r += b[1] ? b[1] : "";
					}
					if (r.length < 40) {
						if (condition[0]['mark']) {
							product = '★' + r + '★' + product;
						} else {
							product = r + product;
						}
					}
				}
				if (product.length > 40) {
					let temp = product.slice(0, 40);
					var product_split = 23;
					for (let i = 0; i < patt.length; i++) {
						var temp_1 = temp.lastIndexOf(patt[i]);
						if (temp_1 > product_split) {
							product_split = temp_1;
						}
					}
					product = product.slice(0, product_split);
				}
				let comment = (setting.sentence == null) ? '' : setting.sentence;
				//setting feature
				feature = comment + product + feature + feature_1 + feature_2 + feature_3 + feature_4 + feature_5;
				if (feature.length > 1000) {
					temp = feature.slice(0, 1000);
					let feature_split = 0;
					for (let i = 0; i < patt.length; i++) {
						let temp_1 = temp.lastIndexOf(patt[i]);
						if (temp_1 > feature_split) {
							feature_split = temp_1;
						}
					}
					feature = temp.slice(0, feature_split);
				}

				//setting prime
				if (condition[0]['prime']) {
					if (amazonData[i].prime == 'no') {
						exclusion += '<span class="badge bg-light-warning">非prime</span><br />';
					}
				}

				//setting postage
				let postage = 0;
				if (amazonData[i].p_width && amazonData[i].p_length && amazonData[i].p_height) {
					for (let i = 0; i < len; i++) {
						if (criteria[i]['width'] > (amazonData[i].p_width * 10) && criteria[i]['height'] > (amazonData[i].p_height * 10) && criteria[i]['length'] > (amazonData[i].p_length * 10)) {
							postage = criteria[i]['final'];
							break;
						}
					}
				}
				if (profit != null) {
					if (profit.profit == null && profit.profit == 0) {
						r_profit = 0;
					} else {
						r_profit = profit.profit;
					}
				} else {
					r_profit = 0;
				}

				var query = {};    //start register ===============================================
				await mercariUpdates.findOne({ where: { SKU1_product_management_code: amazonData[i].m_code } })
					.then(data => {
						console.log(data);
						if (data == null || data === undefined) {
							query.condition_n_u = 1;//new product.
						} else {
							if (data.SKU1_current_inventory == 0) {
								query.condition_n_u = 3;// product is already exist mercari, but inventory is 0
							} else {
								query.condition_n_u = 2;// product is already exist mercari.
							}
						}
					})
				query.m_code = amazonData[i].m_code;
				query.amazon_id = amazonData[i].id;
				query.ASIN = amazonData[i].ASIN;
				query.image = amazonData[i].image;
				query.product = JSON.stringify(product);
				query.prime = amazonData[i].prime;
				query.feature = JSON.stringify(feature);
				query.a_category = amazonData[i].a_c_tree;
				query.m_category = m_category;
				query.m_category_id = m_category_id;
				query.price = amazonData[i].price;
				query.e_price = (amazonData[i].price + r_profit + postage + 100) * 1.1;
				query.postage = postage;
				query.etc = 100;
				query.exclusion = exclusion;
				query.user_id = query_condition.user_id;
				query.inventory = 0;
				console.log("saveExhibition >>>>>>>>>>>>", amazonData[i].m_code);
				await exhibitions.create(query);
			}
		}
	}
	res.send({ msg: "success" });
}
exports.saveMercari = async (req, res) => {
	let query_condition = { user_id: Number(req.body.user_id) };
	await mercaris.destroy({ where: query_condition });

	let exhibition_data = await exhibitions.findAll({ where: { [Op.and]: [{ exclusion: '' }, { user_id: query_condition.user_id }] } });

	await exhibition_data.forEach(async (row) => {
		await mercariUpdates.findOne({ where: { SKU1_product_management_code: row.m_code } })
			.then(async (mercari_update_data) => {
				console.log("saveMercari>>>>", mercari_update_data);
				if (mercari_update_data == null || mercari_update_data === undefined) {
					var query = {};
					var image = row.image.split(';');
					for (var i = 0; i < 4; i++) {
						if (image[i] != null || image[i] != undefined) {
							query['image_' + (i + 1)] = row.ASIN + '_' + (i + 1) + '.jpg';
						}
					}
					query.image = image[0];
					query.user_id = row.user_id;
					query.SKU1_management = row.m_code;
					query.SKU1_inventory = 1;
					query.product = row.product;
					query.feature = row.feature;
					query.ASIN = row.ASIN;
					query.selling_price = row.e_price;
					query.category_id = row.m_category_id;
					query.commodity = 1;
					query.shipping_method = 1;
					query.region_origin = 'jp12';
					query.day_ship = 3;
					query.product_status = 1;
					await mercaris.create(query);
				} else {
					let mercari_update_info = {};
					if (row.inventory == 0) {
						mercari_update_info.SKU1_increase = 2;
						mercari_update_info.SKU1_stock_increase = 0;
						mercari_update_info.Selling_price = 0;
					} else {
						mercari_update_info.SKU1_increase = 1;
						mercari_update_info.SKU1_stock_increase = 1;
						mercari_update_info.Selling_price = row.e_price;
					}

					await mercariUpdates.update(mercari_update_info, { where: { SKU1_product_management_code: row.m_code } });
				}
			})

		// dragon change ======================================================================================
		// if (row.condition_n_u == 1) {

		// 	var query = {};
		// 	var image = row.image.split(';');
		// 	for (var i = 0; i < 4; i++) {
		// 		if (image[i] != null || image[i] != undefined) {
		// 			query['image_' + (i + 1)] = row.ASIN + '_' + (i + 1) + '.jpg';
		// 		}
		// 	}
		// 	query.image = image[0];
		// 	query.user_id = row.user_id;
		// 	query.SKU1_management = row.m_code;
		// 	query.SKU1_inventory = 1;
		// 	query.product = row.product;
		// 	query.feature = row.feature;
		// 	query.ASIN = row.ASIN;
		// 	query.selling_price = row.e_price;
		// 	query.category_id = row.m_category_id;
		// 	query.commodity = 1;
		// 	query.shipping_method = 1;
		// 	query.region_origin = 'jp12';
		// 	query.day_ship = 3;
		// 	query.product_status = 1;
		// 	await mercaris.create(query);

		// } else {

		// 	let mercari_update_info = {};
		// 	if (row.intentory == 0) {
		// 		mercari_update_info.SKU1_increase = 2;
		// 		mercari_update_info.SKU1_stock_increase = 0;
		// 		mercari_update_info.Selling_price = 0;
		// 	} else {
		// 		mercari_update_info.SKU1_increase = 1;
		// 		mercari_update_info.SKU1_stock_increase = 1;
		// 		mercari_update_info.Selling_price = row.e_price;
		// 	}
		// 	await mercariUpdates.update(mercari_update_info, { where: { SKU1_product_management_code: row.m_code } });

		// }
	});
}

exports.downloadImageZip = async (req, res) => {
	let temp = {
		path: '../public/Mercari/' + req.body.family_name,
		family_name: req.body.family_name,
		download_path: '../nodejs',
	}
	var output = await fs.createWriteStream(temp.family_name + '.zip');
	var archive = await archiver('zip');

	output.on('close', function () {
		console.log(archive.pointer() + ' total bytes');
		console.log('archiver has been finalized and the output file descriptor has closed.');
	});

	archive.on('error', function (err) {
		throw err;
	});

	await archive.pipe(output);

	// append files from a sub-directory, putting its contents at the root of archive
	await archive.directory(temp.path, false);
	await archive.finalize();
}
exports.saveAmazon = async (req, res) => {
	const reqData = JSON.parse(req.body.xlsxData);
	const xlsxAmazonArr = reqData['xlRowObjArr'];
	await xlsxAmazonArr.forEach(async (row) => {
		var query = {};
		let xlsxAmazon = await products.findOne({ where: { [Op.and]: [{ user_id: reqData['user_id'] }, { ASIN: row.ASIN }] } });
		console.log(xlsxAmazon);
		if (xlsxAmazon == undefined || xlsxAmazon == null) {
			//new amazon data
			query.user_id = reqData['user_id'];
			query.image = row['画像'] ?? '';
			query.ASIN = row['ASIN'];
			query.prime = row['Prime Eligible (Buy Box)'] ?? '';
			query.product = JSON.stringify(row['商品名']);
			query.attribute = (row['Variation Attributes']) ? JSON.stringify(row['Variation Attributes']) : '';
			query.feature_1 = (row['説明 & Features: Feature 1']) ? JSON.stringify(row['説明 & Features: Feature 1']) : '';
			query.feature_2 = (row['説明 & Features: Feature 2']) ? JSON.stringify(row['説明 & Features: Feature 2']) : '';
			query.feature_3 = (row['説明 & Features: Feature 3']) ? JSON.stringify(row['説明 & Features: Feature 3']) : '';
			query.feature_4 = (row['説明 & Features: Feature 4']) ? JSON.stringify(row['説明 & Features: Feature 4']) : '';
			query.feature_5 = (row['説明 & Features: Feature 5']) ? JSON.stringify(row['説明 & Features: Feature 5']) : '';
			query.feature = (row['説明 & Features: 説明']) ? JSON.stringify(row['説明 & Features: 説明']) : '';
			query.price = 0;
			query.r_price = 0;
			query.rank = row['売れ筋ランキング: Subcategory Sales Ranks'] ?? '';
			query.a_c_root = row['カテゴリ: Root'] ?? '';
			query.a_c_sub = row['カテゴリ: Sub'] ?? '';
			query.a_c_tree = row['カテゴリ: Tree'] ?? '';
			query.p_length = row['Package: Length (cm)'] ?? '';
			query.p_width = row['Package: Width (cm)'] ?? '';
			query.p_height = row['Package: Height (cm)'] ?? '';
			query.flag = 1;
			let newModel = await products.create(query);
			let string = '0000000';
			query.m_code = 'MC' + string.substring((Math.floor(Math.log10(newModel.id)) + 1)) + newModel.id;
			await products.update(query, { where: { [Op.and]: [{ user_id: reqData['user_id'] }, { ASIN: row.ASIN }] } });
		} else {
			query.image = row['画像'] ?? '';
			query.ASIN = row['ASIN'];
			query.prime = row['Prime Eligible (Buy Box)'] ?? '';
			query.product = JSON.stringify(row['商品名']);
			query.attribute = (row['Variation Attributes']) ? JSON.stringify(row['Variation Attributes']) : '';
			query.feature_1 = (row['説明 & Features: Feature 1']) ? JSON.stringify(row['説明 & Features: Feature 1']) : '';
			query.feature_2 = (row['説明 & Features: Feature 2']) ? JSON.stringify(row['説明 & Features: Feature 2']) : '';
			query.feature_3 = (row['説明 & Features: Feature 3']) ? JSON.stringify(row['説明 & Features: Feature 3']) : '';
			query.feature_4 = (row['説明 & Features: Feature 4']) ? JSON.stringify(row['説明 & Features: Feature 4']) : '';
			query.feature_5 = (row['説明 & Features: Feature 5']) ? JSON.stringify(row['説明 & Features: Feature 5']) : '';
			query.feature = (row['説明 & Features: 説明']) ? JSON.stringify(row['説明 & Features: 説明']) : '';
			query.price = 0;
			query.r_price = 0;
			query.rank = row['売れ筋ランキング: Subcategory Sales Ranks'] ?? '';
			query.a_c_root = row['カテゴリ: Root'] ?? '';
			query.a_c_sub = row['カテゴリ: Sub'] ?? '';
			query.a_c_tree = row['カテゴリ: Tree'] ?? '';
			query.p_length = row['Package: Length (cm)'] ?? '';
			query.p_width = row['Package: Width (cm)'] ?? '';
			query.p_height = row['Package: Height (cm)'] ?? '';
			query.flag = 1;
			await products.update(query, { where: { [Op.and]: [{ user_id: reqData['user_id'] }, { ASIN: row.ASIN }] } });
		}
	});

	res.send({ msg: 'success' });

}