const amazonPaapi = require('amazon-paapi');
const { Op } = require("sequelize");
const { products, exhibitions, mercariUpdates, users, ng_categories, ng_products, ng_words, settings, postages, prices, categories, categoryIds, mercaris } = require("../models");
const download = require('image-downloader');
const makeDir = require('make-dir');
const fs = require('fs');
const shell = require("shelljs");
const { size } = require('lodash');
const userModel = require('../models/user.model');

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

const getInfo = (req, res) => {
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

const saveExhibition = async (req, res) => {
	let query_condition = { user_id: Number(req.body.user_id) };
	await exhibitions.destroy({ where: query_condition });
	let amazonData = await products.findAll({ where: { [Op.and]: [{ user_id: query_condition.user_id }, { tracking_condition: 1 }, { flag: 1 }] } });

	if (amazonData.length != 0) {

		let condition = await settings.findAll({ where: query_condition });
		let ngCategory = await ng_categories.findAll({ where: query_condition });
		let ngWord = await ng_words.findAll({ where: query_condition });
		let ngProduct = await ng_products.findAll({ where: query_condition });
		let setting = await settings.findOne({ where: query_condition });
		let criterias = await postages.findAll({ where: query_condition });
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
		res.send({ msg: amazonData.length });
		for await (const eachAmazonData of amazonData) {
			// for ( let i = 0; i < amazonData.length; i++ ) {
			let ngCategoriesSearchString = '';
			let exclusion = '';
			let m_category = '';
			let m_category_id = '';
			let product = (eachAmazonData.product) ? JSON.parse(eachAmazonData.product) : '';
			let feature = (eachAmazonData.feature) ? JSON.parse(eachAmazonData.feature) : '';
			let feature_1 = (eachAmazonData.feature_1) ? JSON.parse(eachAmazonData.feature_1) : '';
			let feature_2 = (eachAmazonData.feature_2) ? JSON.parse(eachAmazonData.feature_2) : '';
			let feature_3 = (eachAmazonData.feature_3) ? JSON.parse(eachAmazonData.feature_3) : '';
			let feature_4 = (eachAmazonData.feature_4) ? JSON.parse(eachAmazonData.feature_4) : '';
			let feature_5 = (eachAmazonData.feature_4) ? JSON.parse(eachAmazonData.feature_4) : '';
			feature = (feature === null) ? '' : feature;
			feature_1 = (feature_1 === null) ? '' : feature;
			feature_2 = (feature_2 === null) ? '' : feature;
			feature_3 = (feature_3 === null) ? '' : feature;
			feature_4 = (feature_4 === null) ? '' : feature;
			feature_5 = (feature_5 === null) ? '' : feature;
			let ngProductSearchString = product + feature + feature_1 + feature_2 + feature_3 + feature_4 + feature_5;
			let profit = await prices.findOne({ where: { [Op.and]: [{ user_id: query_condition.user_id }, { down: { [Op.lte]: eachAmazonData.price, }, up: { [Op.gte]: eachAmazonData.price, } }] } });
			let m_category_match = await categories.findOne({ where: { [Op.and]: [{ user_id: query_condition.user_id }, { a_c_root: eachAmazonData.a_c_root }, { a_c_sub: eachAmazonData.a_c_sub },] } });

			//ng category setting
			// ngCategoriesSearchString += eachAmazonData.a_c_root + eachAmazonData.a_c_sub + eachAmazonData.a_c_tree;
			ngCategoriesSearchString = eachAmazonData.a_c_tree;
			for (let n = 0; n < ngCategory.length; n++) {
				let position = ngCategoriesSearchString.search(ngCategory[n].category)
				if (position != -1) {
					exclusion += 'AmazonカテゴリにNGカテゴリが含まれています。⇒' + ngCategory[n].category + '\n';
					break;
				}
			}

			//ng word setting
			for (let m = 0; m < ngProduct.length; m++) {
				let position = await ngProductSearchString.search(ngProduct[m].product)
				if (position != -1) {
					exclusion += '商品説明文にNGワードが含まれている⇒' + ngProduct[m].product + '\n';
					break;
				}

			}
			const deleteWord = (allNgWords) => {
				for (const ng of allNgWords) {
					let ngWordPattern = `${ng_words.word}`;
					product = product.replace(ngWordPattern, '');
					feature = feature.replaceAll(ngWordPattern, '');
					feature_1 = feature_1.replaceAll(ngWordPattern, '');
					feature_2 = feature_2.replaceAll(ngWordPattern, '');
					feature_3 = feature_3.replaceAll(ngWordPattern, '');
					feature_4 = feature_4.replaceAll(ngWordPattern, '');
					feature_5 = feature_5.replaceAll(ngWordPattern, '');
				}
			}
			// fs.appendFileSync( `${__dirname}/tmp.log`, `========================\n` );
			//delete word setting in product and feature ...
			for await (const ng_words of ngWord) {
				// fs.appendFileSync( `${__dirname}/tmp.log`, `pppppp-----: ${product}\n` );
				// let ngWordPattern = new RegExp( `\\b${ng_words.word}\\b`, 'gi' );
				let ngWordPattern = `${ng_words.word}`;
				// fs.appendFileSync( `${__dirname}/tmp.log`, `tttttt------------------------------------------------------------------------: ${ngWordPattern}\n` );
				product = product.replace(ngWordPattern, '');
				// fs.appendFileSync( `${__dirname}/tmp.log`, `cccccc-------------------------------: ${product}\n` );
				feature = feature.replaceAll(ngWordPattern, '');
				feature_1 = feature_1.replaceAll(ngWordPattern, '');
				feature_2 = feature_2.replaceAll(ngWordPattern, '');
				feature_3 = feature_3.replaceAll(ngWordPattern, '');
				feature_4 = feature_4.replaceAll(ngWordPattern, '');
				feature_5 = feature_5.replaceAll(ngWordPattern, '');
			}
			deleteWord(ngWord);

			//setting mercari category
			if (m_category_match == null) {
				exclusion += 'をメルカリカテゴリーなし\n';
			} else {
				m_category = m_category_match.m_category;
				if (m_category == '削除') {
					exclusion += 'メルカリカテゴリを削除しました\n';
				}
				let match_m_category_id = await categoryIds.findOne({ where: { [Op.and]: [{ all_category: m_category }, { user_id: query_condition.user_id }] } });
				if (match_m_category_id != null) {
					m_category_id = match_m_category_id.category_id;
				} else {
					exclusion += 'メルカリカテゴリIDはありません。\n';
				}
			}
			//image chack exist 
			if (eachAmazonData.image == '' || eachAmazonData.image == null) {
				exclusion += 'AMAZON画像なし。\n';
			}
			//setting amazon inventory
			var query = {};
			if (eachAmazonData.inventory == 0) {
				exclusion += 'AMAZON在庫なし。\n'
				query.inventory = 0;
			} else {
				query.inventory = 1;
			}

			//setting amazon_price
			if (eachAmazonData.price == 0) {
				exclusion += 'Amazon価格が0円です。\n'
			}
			// product_name of option
			let options = (eachAmazonData.attribute) ? JSON.parse(eachAmazonData.attribute) : null;
			if (options !== null) {
				let a = options.split(';');
				let r = '';
				for (let i = 0; i < a.length; i++) {
					let b = a[i].split(':');
					r += b[1] ? b[1] : "";
				}
				if (r.length < 100) {
					if (condition[0]['mark']) {
						product = '★' + r + '★' + product;
					} else {
						product = r + product;
					}
				}
			}
			if (product.length > 50) {
				let temp = product.slice(0, 50);
				var product_split = 40;
				for (let i = 0; i < patt.length; i++) {
					var temp_1 = temp.lastIndexOf(patt[i]);
					if (temp_1 > product_split) {
						product_split = temp_1;
					}
				}
				product = product.slice(0, product_split);
			}
			product = await product.replaceAll('★★', '');
			let comment = (setting.sentence == null) ? '' : setting.sentence;
			//setting feature
			feature = (comment ? '●' + comment : '') + '●' + product + (feature ? '●' + feature : '') + (feature_1 ? '●' + feature_1 : '') + (feature_2 ? '●' + feature_2 : '') + (feature_3 ? '●' + feature_3 : '') + (feature_4 ? '●' + feature_4 : '') + (feature_5 ? '●' + feature_5 : '');
			if (feature.length > 2700) {
				temp = feature.slice(0, 2700);
				let feature_split = 0;
				for (let i = 0; i < patt.length; i++) {
					let temp_1 = temp.lastIndexOf(patt[i]);
					if (temp_1 > feature_split) {
						feature_split = temp_1;
					}
				}
				feature = temp.slice(0, feature_split);
			}
			feature = await feature.replaceAll('&#10', "");
			feature = await feature.replaceAll('�', "");
			feature = await feature.replaceAll('●', '\n●');
			feature = await feature.replaceAll('●★', '★');
			feature = await feature.replaceAll('?', '');
			feature = await feature.replaceAll('\n●\n●', '\n●');
			feature = await feature.replaceAll('\n●\n●\n●', '\n●');
			feature = await feature.replaceAll('\n●\n●\n●\n●', '\n●');
			feature = await feature.replaceAll('\n●\n●\n●\n●\n●', '\n●');
			feature = await feature.replaceAll('※', '\n\t※');
			feature = await feature.replaceAll(';;', ';');
			feature = await feature.replaceAll('<br>', '');
			feature = await feature.replaceAll('★★', '');

			//setting prime
			if (condition[0]['prime']) {
				if (eachAmazonData.prime == 'no') {
					exclusion += 'プライム商品でない。\n';
				}
			}

			// setting postage
			let postage = 100000;
			if (eachAmazonData.p_width && eachAmazonData.p_length && eachAmazonData.p_height) {
				for (const criteria of criterias) {
					if (criteria['width'] >= (eachAmazonData.p_width * 10) && criteria['height'] >= (eachAmazonData.p_height * 10) && criteria['length'] >= (eachAmazonData.p_length * 10)) {
						if (postage > criteria['final']) {
							postage = criteria['final'];
						}
					}
				}
			}
			if (postage == 100000) {
				exclusion += '送料が設定されていません⇒サイズオーバー。\n';
				postage = 0;
			}
			// setting profit 
			let r_profit;
			if (profit != null) {
				if (profit.profit == null && profit.profit == 0) {
					r_profit = 0;
				} else {
					r_profit = profit.profit;
				}
			} else {
				r_profit = 0;
			}

			if (amazonData.price < 200) {
				exclusion += '価格エラー（最低価格が399円より大きくなければなりません。）\n';
			}
			if (r_profit == 0) {
				exclusion += '利益額が設定されていません。\n';
			}

			await mercariUpdates.findOne({ where: { SKU1_product_management_code: eachAmazonData.m_code } })
				.then(data => {
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
			let entry_price = (eachAmazonData.price + r_profit + postage + setting.etc) * 1.1;
			if (eachAmazonData.price == 0 || r_profit == 0 || postage == 0) {
				entry_price = 999999;
			}
			//start register ===============================================
			query.m_code = eachAmazonData.m_code;
			query.amazon_id = eachAmazonData.id;
			query.ASIN = eachAmazonData.ASIN;
			query.image = eachAmazonData.image;
			query.product = JSON.stringify(product);
			query.prime = eachAmazonData.prime;
			query.feature = JSON.stringify(feature);
			query.a_category = eachAmazonData.a_c_tree;
			query.m_category = m_category;
			query.m_category_id = m_category_id;
			query.price = eachAmazonData.price;
			query.e_price = (eachAmazonData.price == 0) ? 0 : entry_price;
			query.postage = (eachAmazonData.price == 0) ? 0 : postage;
			query.profit = r_profit;
			query.etc = (eachAmazonData.price == 0) ? 0 : setting.etc;
			query.exclusion = exclusion;
			query.user_id = query_condition.user_id;
			console.log("saveExhibition >>>>>>>>>>>>", eachAmazonData.m_code);
			await exhibitions.create(query);
		}
	}
}

const getAllUserD = async (req, res) => {
	let allUserD = await users.findAll();
	res.send(allUserD);
}

const userProductMercari = async (req, res) => {
	console.log(req.body);
	const user_d_id = req.body.user_d_id;
	await users.destroy({ where: { id: user_d_id } })
		.then(data => {
			res.status(200).json({ msg: `deleted user_id is ${user_d_id}.` })
			products.destroy({ where: { user_id: user_d_id } });
			mercaris.destroy({ where: { user_id: user_d_id } });
			mercariUpdates.destroy({ where: { user_id: user_d_id } });
		})
		.catch(err => {
			res.status(500).json({ error: err })
		})
}

const saveMercari = async (req, res) => {
	let query_condition = { user_id: Number(req.body.user_id) };
	await mercaris.destroy({ where: query_condition });
	let exhibition_data = await exhibitions.findAll({ where: { [Op.and]: [{ exclusion: '' }, { user_id: query_condition.user_id }] } });
	var mercari_updates = await mercariUpdates.findAll({ where: { [Op.and]: [{ user_id: query_condition.user_id }, { product_status: 1 }, { product_status: 2 }] } });
	console.log(mercari_updates.length);
	var img_length_condition = 1;
	var userDir = await makeDir('../public/' + query_condition.user_id);
	var exLen = exhibition_data.length - mercari_updates.length;
	res.send({ msg: exLen });
	// delete directory
	console.log('image delete')
	console.log(exLen);
	shell.rm('-rf', `${userDir}`);
	// make directory
	var dir = [];
	await (async () => {
		for (let i = 0; i < Math.ceil(exLen / 1000); i++) {
			let eachDir = await makeDir(userDir + '/' + (i + 1));
			dir.push(eachDir);
		}
	})();
	console.log(dir);
	// ++++++++++++++++++++++for delay
	await sleep(1000 * 10)
	function sleep(ms) {
		return new Promise((resolve) => {
			setTimeout(resolve, ms);
		});
	}

	for await (const row of exhibition_data) {
		try {
			let mercari_exist_data = await mercaris.findOne({ where: { SKU1_management: row.m_code } });
			if (mercari_exist_data == null) {
				let mercari_update_data = await mercariUpdates.findOne({ where: { SKU1_product_management_code: row.m_code } });
				console.log('>>>>>>>>>>>>>>>>>>>>>>>>>')
				if (mercari_update_data == null || mercari_update_data.product_status == 3) {
					console.log("saveMercari>>>>", mercari_update_data);
					console.log(img_length_condition);
					var query = {};
					var image = row.image.split(';');
					for (let i = 0, len = image.length; i < Math.min(10, len); i++) {
						try {
							await download.image({
								url: image[i],
								dest: dir[Math.ceil(img_length_condition / 1000) - 1] + '/' + row.ASIN + '_' + (i + 1) + '.jpg',
							});
							query['image_' + (i + 1)] = row.ASIN + '_' + (i + 1) + '.jpg';
							query['img_url_' + (i + 1)] = image[i];
							console.log('image download success:', image[i]);
						} catch (error) {
							console.log("iiiiiiiiiiiiiiiiiiiiiiiiiiiiiii image download error:", image[i], error);
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
					query.product_status = 2;
					img_length_condition++;
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
					mercari_update_info.product_name = row.product;
					mercari_update_info.feature = row.feature;
					await mercariUpdates.update(mercari_update_info, { where: { SKU1_product_management_code: row.m_code } });
				}
			} else {
				continue;
			}
		} catch (error) {

		}
	}
}

const mercariUpdateExportDelete = async (req, res) => {
	console.log(req.body)
	const user = await users.findOne({ where: { email: req.body.email } })
	await mercariUpdates.destroy({ where: { [Op.and]: [{ user_id: user.id }, { product_status: 3 }] } });
	res.send({ 'reEntryProduct': 'success' });
}

const amazonGetProducts = async (req, res) => {
	const resultProduct = {};
	const allProduct = await products.findAll({ where: { [Op.and]: [{ user_id: req.body.user_id }, { flag: 1 }] } });
	const countProducts = await products.findAll({ where: { [Op.and]: [{ user_id: req.body.user_id }, { tracking_condition: 0 }, { flag: 1 }] } });
	resultProduct.all = allProduct.length;
	resultProduct.updating = countProducts.length;
	resultProduct.complete = allProduct.length - countProducts.length;
	res.send(resultProduct);
}

const saveAmazon = async (req, res) => {
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

const downloadImages = async (req, res) => {
	let condition = {
		user_id: Number(req.body.user_id),
		exclusion: '',
	};

	let information = {};
	information.images_path = '../public/' + condition.user_id;

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

const mercariDeleteAction = async (req, res) => {
	let SKU1_code = req.body.SKU1_code;
	if (SKU1_code === undefined) {
		res.status(500).json({ 'error': 'incorrected SKU1_code' });
		return;
	} else {
		let existMercariUpdate = await mercariUpdates.findOne({ where: { SKU1_product_management_code: SKU1_code } });
		if (existMercariUpdate != null) {
			await mercariUpdates.destroy({ where: { SKU1_product_management_code: SKU1_code } });
		}
		let existMercariProduct = await mercaris.findOne({ where: { SKU1_management: SKU1_code } });
		if (existMercariProduct != null) {
			await mercaris.destroy({ where: { SKU1_management: SKU1_code } });
		}
		console.log(SKU1_code);
		res.status(200).json({ status: SKU1_code + ' of product is deleted' });

	}
}
module.exports = {
	getInfo,
	amazonGetProducts,
	mercariUpdateExportDelete,
	saveMercari,
	saveExhibition,
	saveAmazon,
	downloadImages,
	mercariDeleteAction,
	userProductMercari,
	getAllUserD,
}
