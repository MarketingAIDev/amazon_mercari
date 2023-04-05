const amazonPaapi = require('amazon-paapi');
const { Op } = require("sequelize");
const { products, users, mercaris, exhibitions, postages, prices, mercariUpdates } = require("../models");

exports.updateInfo = async () => {
	const all_postage = await postages.findAll();
	if (all_postage.length != 0) {
		// await users.findAll()
		// 	.then( async ( users ) =>
		// 	{
		// 		for ( const u of users ) {
		// 			amazonTracking( u, all_postage );
		// 		}
		// 	} )
		// 	.catch( err =>
		// 	{
		// 		console.log( "Cannot access user data", err );
		// 	} );
		products.findAll({ where: { user_id: user.id } })
			.then(res => {
				var index = 0;
				var len = res.length;
				var asins = [];
				for (const i of res) {
					asins.push(i.ASIN);

				}
				let checkInterval = setInterval(() => {
					if (index < len) {
						let checkItemInfo = new CheckItemInfo(user, asins.slice(index, (index + 10)), all_postage);
						checkItemInfo.main();
						index += 10;
					} else {
						clearInterval(checkInterval);
						// amazonTracking(user);
						index = 0;
					}
				}, 1000 * 10);
			}).catch(err => {
				console.log(err);
			});

	}
};

const amazonTracking = (user, all_postage) => {
	products.findAll({ where: { user_id: user.id } })
		.then(res => {
			var index = 0;
			var len = res.length;
			var asins = [];
			for (const i of res) {
				asins.push(i.ASIN);

			}
			let checkInterval = setInterval(() => {
				if (index < len) {
					let checkItemInfo = new CheckItemInfo(user, asins.slice(index, (index + 10)), all_postage);
					checkItemInfo.main();
					index += 10;
				} else {
					clearInterval(checkInterval);
					// amazonTracking(user);
					index = 0;
				}
			}, 1000 * 10);
		}).catch(err => {
			console.log(err);
		});
};

class CheckItemInfo {
	constructor(user, code, all_postage) {
		this.user = user;
		this.code = code;
		this.all_postage = all_postage;
	}

	main() {
		const commonParameters = {
			AccessKey: this.user.dataValues.accesskey,
			SecretKey: this.user.dataValues.secretkey,
			PartnerTag: this.user.dataValues.partnertag, // yourtag-20
			PartnerType: 'Associates', // Default value is Associates.
			Marketplace: 'www.amazon.co.jp', // Default value is US. Note: Host and Region are predetermined based on the marketplace value. There is no need for you to add Host and Region as soon as you specify the correct Marketplace value. If your region is not US or .com, please make sure you add the correct Marketplace value.
		};

		let requestParameters = { // this is the parameter to get information with asin from amazon
			ItemIds: this.code,
			ItemIdType: 'ASIN',
			Condition: 'New',
			Resources: [
				"Offers.Listings.DeliveryInfo.IsPrimeEligible",
				'Offers.Listings.Availability.Message',
				"Offers.Summaries.LowestPrice"
			],
		};
		amazonPaapi.GetItems(commonParameters, requestParameters)
			.then(async (amazonData) => { // save data into db

				var items = amazonData.ItemsResult.Items;
				for (const item of items) {
					try {
						let query = {};
						let mercari = {};
						let mercari_update_info = {};
						let exhibition_updata_info = {};
						let amazon_info = {
							postage: 0,
						};
						let condition = {
							user_id: this.user.id,
							ASIN: item.ASIN,
						};
						var user_amazon_info;
						if (item.Offers.Summaries[0].Condition.Value == 'New') {
							query.price = item.Offers.Summaries[0].LowestPrice.Amount;
						} else if (item.Offers.Summaries.length > 1 && item.Offers.Summaries[1].Condition.Value == 'New') {
							query.price = item.Offers.Summaries[1].LowestPrice.Amount;
						}
						await products.findOne({ where: condition })
							.then(async (data) => {
								if (data.r_price == 0) {
									query.r_price = query.price;
								}
								user_amazon_info = data;
								//get postage
								if (data.p_width && data.p_length && data.p_height) {
									for (let i = 0; i < this.all_postage.length; i++) {
										if (this.all_postage[i]['width'] > (data.p_width * 10) && this.all_postage[i]['height'] > (data.p_height * 10) && this.all_postage[i]['length'] > (data.p_length * 10)) {
											amazon_info.postage = this.all_postage[i]['final'];
											break;
										}
									}
								}
								//get profit
								let profit = await prices.findOne({ where: { down: { [Op.lt]: data.price, }, up: { [Op.gt]: data.price, } } });
								amazon_info.profit = (profit != null || profit != undefined) ? profit.profit : 0;
								exhibition_updata_info.price = query.price;
								exhibition_updata_info.e_price = (exhibition_updata_info.price + amazon_info.profit + amazon_info.postage + 100) * 1.1;
								exhibition_updata_info.etc = 100;
							})
							.catch(err => {
								console.log('miss product !', err);
							});
						if (item.Offers !== undefined) {
							if (item.Offers.Listings[0].Availability !== undefined) {
								if (item.Offers.Listings[0].Availability.Message != '在庫あり。') {
									mercari.SKU1_inventory = 0;
									mercari.selling_price = 0;
									exhibition_updata_info.inventory = 0;
									mercari_update_info.SKU1_increase = 2;//inventory increase condition 
									mercari_update_info.SKU1_stock_increase = 0;//inventory
									mercari_update_info.Selling_price = 0;
								}
								else {
									mercari.selling_price = exhibition_updata_info.e_price;
									mercari_update_info.SKU1_increase = 1;
									mercari_update_info.SKU1_stock_increase = 1;
									mercari_update_info.Selling_price = exhibition_updata_info.e_price;
								}
							}
						}
						await exhibitions.update(exhibition_updata_info, { where: condition });
						await products.update(query, { where: condition });
						await mercaris.update(mercari, { where: condition });
						await mercariUpdates.update(mercari_update_info, { where: { [Op.and]: [{ user_id: condition.user_id }, { SKU1_product_management_code: user_amazon_info.m_code }] } });
					} catch (err) {
						console.log('tttttttt tracking error', err);
					}
				}
			}).catch(err => {
				console.log('gggggggg get amazonitem error', err.status);
			});
	}
}
