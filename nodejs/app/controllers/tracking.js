const amazonPaapi = require( 'amazon-paapi' );
const download = require( 'image-downloader' );
const { products, mercaris, exhibitions, postages, prices, mercariUpdates, settings, users } = require( "../models" );
const makeDir = require( 'make-dir' );
const { Op } = require( "sequelize" );
const shell = require( "shelljs" );


const updateInfo = async () =>
{
	setInterval( async () =>
	{
		const product_s_date1 = new Date();
		const allupdate = await mercariUpdates.findAll();
		for await ( const eachproduct of allupdate ) {
			// ++++++++++++++++++++++++++++++++++++++++++++++  change product status of mercari_update DB
			let product_s_date2 = eachproduct.product_registration_time;
			let diffTime = Math.abs( product_s_date2 - product_s_date1 );
			let diffDays = Math.ceil( diffTime / ( 1000 * 60 * 60 * 24 ) );
			let query = {
				product_status: 3,
				// product_registration_price: product_s_date1.toDateString(),
			}
			if ( diffDays >= eachproduct.re_entry ) {
				await mercariUpdates.update( query, { where: { product_id: eachproduct.product_id } } );
				console.log( product_s_date1, product_s_date2, diffDays )
			}

			// ++++++++++++++++++++++++++++++++++++++++++++++  change price cut
			// await sleep( 1000 * 60 * 1 )
			const eachUserSettings = await settings.findOne( { where: { user_id: eachproduct.user_id } } );
			let change_price_date_1 = eachproduct.product_registration_price;
			let change_price_date_2 = product_s_date1;
			// const change_price_date_1 = eachproduct.product_registration_time;
			// let change_price_date_2 = eachproduct.product_registration_price;
			let change_price_diffTime = Math.abs( change_price_date_2 - change_price_date_1 );
			let change_price_diffDays = Math.ceil( change_price_diffTime / ( 1000 * 60 * 60 * 24 ) );
			console.log( `========== ${change_price_diffTime} ==== change price diff: ${change_price_diffDays} =========== setting date : ${eachUserSettings.price_cut_date}` );
			if ( eachUserSettings.price_cut ) {
				if ( change_price_diffDays >= eachUserSettings.price_cut_date ) {
					console.log( eachUserSettings.price_cut )
					var newPrice = eachproduct.Selling_price - eachUserSettings.price_reduction;
					let query = {
						Selling_price: newPrice,
						product_registration_price: new Date().toDateString()
					}
					await mercariUpdates.update( query, { where: { product_id: eachproduct.product_id } } );
					console.log( `>>>>>>>>>> change price is ${newPrice}` )
					console.log( `>>>>>>>>>>>>>>> ${query}` )
				}
			}
		}

		// ++++++++++++++++++++++++amazon tracking get user.id when add user.
		await sleep( 1000 * 60 * 60 * 1 );
		const allUser = await users.findAll();
		for ( const re_entry_user of allUser ) {
			const re_allupdate = await mercariUpdates.findAll( { where: { user_id: re_entry_user.id } } );
			for ( const re_eachproduct of re_allupdate ) {
				let product_s_date3 = re_eachproduct.product_registration_time;
				let diffTime_1 = Math.abs( product_s_date3 - product_s_date1 );
				let diffDays_1 = Math.ceil( diffTime_1 / ( 1000 * 60 * 60 * 24 ) );
				if ( diffDays_1 > re_eachproduct.re_entry ) {
					reEntryProductAction( re_entry_user.id );
					break;
				}
			}
		}
		// }, 1000 * 30 );
	}, 1000 * 60 * 60 * 12 );
};
const allAmazonProduct = async ( user ) =>
{

	let user_info = user;
	var product = await products.findAll( { where: { [Op.and]: [{ user_id: user.id }, { tracking_condition: 0 }, { flag: 1 }] } } );

	var index = 0;
	var len = product.length;
	console.log( `============== can't tracking product =============== ${len} =============== ${user.id} ==============${product}` )
	if ( len == 0 ) {
		product = await products.findAll( { where: { [Op.and]: [{ user_id: user_info.id }, { tracking_condition: 1 }, { flag: 1 }] } } );
		len = product.length;
		console.log( `================ after ${product.length}` )
	}
	var asins = [];
	for ( const i of product ) {
		asins.push( i.ASIN );
	}
	let checkInterval = setInterval( () =>
	{
		if ( index < len ) {
			let checkItemInfo = new CheckItemInfo( asins.slice( index, ( index + 10 ) ), user_info );
			checkItemInfo.main( 1, null );
			index += 10;
			console.log( '>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', index, 'user_id =', user_info.id );
		} else {
			clearInterval( checkInterval );
			allAmazonProduct( user_info );
			index = 0;
		}
	}, 1000 * 15 );
}

class CheckItemInfo
{
	constructor( code, user )
	{
		this.code = code;
		this.user = user;
	}

	main( change, customParameters )
	{
		const commonParameters = {
			AccessKey: this.user.accesskey,
			SecretKey: this.user.secretkey,
			PartnerTag: this.user.partnertag, // yourtag-20
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
				"Offers.Listings.Price",
				"Offers.Summaries.LowestPrice",
				"Images.Primary.Small",
				"Images.Primary.Medium",
				"Images.Primary.Large",
			],
		};
		amazonPaapi.GetItems( ( change == 2 ) ? customParameters : commonParameters, requestParameters )
			.then( async ( amazonData ) =>
			{ // save data into db
				if (amazonData.Errors !== undefined && amazonData.Errors.length > 0) {
					var error = amazonData.Errors;
					for (const e of error) {
						var ASIN_error = {
							tracking_condition:1,
							product_error: '無効な ASIN コード'
						};
						var condition = {
							user_id: this.user.id,
							asin: e.Message.substr(11, 10),
						};
						products.update(ASIN_error, { where: condition });
					}
				}
				var items = amazonData.ItemsResult.Items;

				for ( const item of items ) {
					try {
						let query = {};
						let mercari = {};
						let mercari_update_info = {};
						let exhibition_updata_info = {};
						let amazon_info = {
							postage: 100000,
						};

						let condition = {
							ASIN: item.ASIN,
						};
						if ( item.Offers !== undefined ) {
							if ( item.Offers.Listings[0] !== undefined ) {
								query.price = item.Offers.Listings[0].Price?.Amount;
							}
						}

						query.price = undefined ?? 0;
						if (query.price == 0) {
							if ( item.Offers != undefined ) {
								if ( item.Offers.Listings != undefined ) {
									if ( item.Offers.Listings[0].DeliveryInfo != undefined ) {
										query.prime = ( item.Offers.Listings[0].DeliveryInfo.IsPrimeEligible == true ) ? 'yes' : 'no';
									}
								}
								if ( item.Offers.Summaries != undefined ) {
									if ( item.Offers.Summaries[0].Condition.Value == 'New' ) {
										query.price = item.Offers.Summaries[0].LowestPrice.Amount;
									} else if ( item.Offers.Summaries.length > 1 && item.Offers.Summaries[1].Condition.Value == 'New' ) {
										query.price = item.Offers.Summaries[1].LowestPrice.Amount;
									}
								}
							}
						}
						query.tracking_condition = 1;
						await products.findOne( { where: condition } )
							.then( async ( data ) =>
							{
								try {
									let eachcondition = {
										[Op.and]: [{ ASIN: data.ASIN }, { user_id: data.user_id }]
									}
									query.r_price = ( data.r_price == 0 ) ? query.price : data.r_price;

									// get postage
									let all_postage = await postages.findAll( { where: { user_id: data.user_id } } );
									if ( data.p_width && data.p_length && data.p_height ) {
										for ( let i = 0; i < all_postage.length; i++ ) {
											if ( all_postage[i]['width'] >= ( data.p_width * 10 ) && all_postage[i]['height'] >= ( data.p_height * 10 ) && all_postage[i]['length'] >= ( data.p_length * 10 ) ) {
												if ( amazon_info.postage > all_postage[i]['final'] ) {
													amazon_info.postage = all_postage[i]['final']
												}
											}
										}
									}
									if ( amazon_info.postage == 100000 ) {
										amazon_info.postage = 0;
									}

									let etc = await settings.findOne( { where: { user_id: data.user_id } } );
									let etc_price = ( etc.etc == null ) ? 100 : etc.etc;
									//get profit
									let profit = await prices.findOne( { where: { [Op.and]: [{ user_id: data.user_id }, { down: { [Op.lte]: data.price, }, up: { [Op.gte]: data.price, } }] } } );
									amazon_info.profit = ( profit != null || profit != undefined ) ? profit.profit : 0;

									exhibition_updata_info.price = query.price;
									exhibition_updata_info.e_price = ( exhibition_updata_info.price + amazon_info.profit + amazon_info.postage + etc_price ) * 1.1;
									if (amazon_info.postage == 0 || query.price == 0 || amazon_info.profit == 0 ) {
										exhibition_updata_info.e_price = 999999;
									}
									console.log( `==========amazonPrice:${exhibition_updata_info.price} =============== profit:${amazon_info.profit},=========== postage:${amazon_info.postage}` )

									// get image
									if ( data.image == 'null' || data.image == '' ) {
										query.image = item.Images.Primary.Small.URL + ";" + item.Images.Primary.Medium.URL + ";" + item.Images.Primary.Large.URL;
										exhibition_updata_info.image = item.Images.Primary.Small.URL + ";" + item.Images.Primary.Medium.URL + ";" + item.Images.Primary.Large.URL;
									}

									if ( item.Offers !== undefined ) {
										if ( item.Offers.Listings[0].Availability !== undefined ) {
											if ( item.Offers.Listings[0].Availability.Message != '在庫あり。' ) {
												query.inventory = 0;
												exhibition_updata_info.inventory = 0;
												mercari.SKU1_inventory = 0;
												mercari.selling_price = ( exhibition_updata_info.e_price < 399 ) ? 999999 : exhibition_updata_info.e_price;
												mercari_update_info.SKU1_increase = 2;//inventory increase condition minus
												mercari_update_info.SKU1_stock_increase = 404;//inventory is zero
												mercari_update_info.Selling_price = mercari.selling_price;//min price is 399 

											}
											else {
												mercari.selling_price = ( exhibition_updata_info.e_price < 399 ) ? 999999 : exhibition_updata_info.e_price;
												mercari_update_info.SKU1_increase = 1; //inventory plus
												mercari_update_info.SKU1_stock_increase = 1;
												mercari_update_info.Selling_price = mercari.selling_price;
											}
										}
									}

									await products.update( query, { where: eachcondition } );
									await exhibitions.update( exhibition_updata_info, { where: eachcondition } );
									await mercaris.update( mercari, { where: eachcondition } );
									await mercariUpdates.update( mercari_update_info, { where: { SKU1_product_management_code: data.m_code } } );
								} catch ( error ) {
									console.log( 'each user update error !!', error );
								}
							} )
							.catch( err =>
							{
								console.log( 'product update error !!!', err )
							} );
					} catch ( err ) {
						console.log( `tttttttt tracking error ========${item.ASIN}`, err );
					}
				}
			} ).catch( async ( err ) =>
			{
				console.log(`>>>>>>>>>>>>>>>>>> ${err.status}`)
				if ( change == 2 )
					change = 1
				else
					change = 2;
				const customParameters = {
					AccessKey: 'AKIAIGP4LM462DYLXEAA',
					SecretKey: '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',
					PartnerTag: 'kazuya1984007-22',
					PartnerType: 'Associates',
					Marketplace: 'www.amazon.co.jp',
				};
				await sleep( 1000 * 5 );
				this.main( change, customParameters );
			} );
	}
}

const reEntryProductAction = async ( u_id ) =>
{
	let query_condition = { user_id: u_id };
	let exhibition_data = await exhibitions.findAll( { where: { [Op.and]: [{ exclusion: '' }, { user_id: query_condition.user_id }] } } );
	var mercari_updates = await mercariUpdates.count( { group: 'SKU1_product_management_code' }, { where: { [Op.and]: [{ user_id: query_condition.user_id }, { product_status: { [Op.not]: 3 } }] } } );
	console.log( `mercari_updates_length >>>> ${mercari_updates.length}` );
	var img_length_condition = 1;
	var userDir = await makeDir( '../public/' + query_condition.user_id );
	var exLen = exhibition_data.length - mercari_updates.length;
	// delete directory
	console.log( 'image delete' )
	shell.rm( '-rf', `${userDir}` );
	// make directory
	var dir = [];
	await ( async () =>
	{
		for ( let i = 0; i < Math.ceil( exLen / 1000 ); i++ ) {
			let eachDir = await makeDir( userDir + '/' + ( i + 1 ) );
			dir.push( eachDir );
		}
	} )();

	// ++++++++++++++++++++++for delay
	await sleep( 1000 * 20 )
	function sleep( ms )
	{
		return new Promise( ( resolve ) =>
		{
			setTimeout( resolve, ms );
		} );
	}

	for await ( const row of exhibition_data ) {
		try {
			let mercari_exist_data = await mercaris.findOne( { where: { SKU1_management: row.m_code } } );
			if ( mercari_exist_data == null ) {
				let mercari_update_data = await mercariUpdates.findOne( { where: { SKU1_product_management_code: row.m_code } } );
				console.log( `>>>>>>>>>>>>>>>>>>>>>>>>>${mercari_exist_data}` )
				if ( mercari_update_data == null || mercari_update_data.product_status == 3 ) {
					console.log( "saveMercari>>>>", mercari_update_data );
					console.log( `img_length_condition >>>>>>> ${img_length_condition}` );
					var query = {};
					var image = row.image.split( ';' );
					for ( let i = 0, len = image.length; i < Math.min( 10, len ); i++ ) {
						try {
							await download.image( {
								url: image[i],
								dest: dir[Math.ceil( img_length_condition / 1000 ) - 1] + '/' + row.ASIN + '_' + ( i + 1 ) + '.jpg',
							} );
							query['image_' + ( i + 1 )] = row.ASIN + '_' + ( i + 1 ) + '.jpg';
							query['img_url_' + ( i + 1 )] = image[i];
							console.log( 'image download success:', image[i] );
						} catch ( error ) {
							console.log( "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiii image download error:", image[i], error );
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
					await mercaris.create( query );
				} else {
					let mercari_update_info = {};
					if ( row.inventory == 0 ) {
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
					await mercariUpdates.update( mercari_update_info, { where: { SKU1_product_management_code: row.m_code } } );
				}
			} else {
				continue;
			}
		} catch ( error ) {
			console.log( error );
		}
	}
}

const sleep = ( ms ) =>
{
	return new Promise( ( resolve ) =>
	{
		setTimeout( resolve, ms );
	} );
}
const trackingEachUser = async () =>
{
	const allUser = await users.findAll();
	for ( const eachUser of allUser ) {
		allAmazonProduct( eachUser );
		await users.update( { tracking: 1 }, { where: { id: eachUser.id } } );
	}
	setInterval( addUserTracking, 1000 * 60 * 30 );
}
const addUserTracking = async () =>
{
	const lostUsers = await users.findAll();
	for ( const lostUser of lostUsers ) {
		if ( lostUser.tracking == 0 ) {
			allAmazonProduct( lostUser );
			await users.update( { tracking: 1 }, { where: { id: lostUser.id } } );
		}
		// if ( lostUser.is_permitted == 3 ) {
		// 	allAmazonProduct( lostUser, 0 )
		// 	await users.destroy( { where: { id: lostUser.id } } );
		// }
	}
}

module.exports = {
	trackingEachUser,
	updateInfo,
}