module.exports = (sequelize, Sequelize) => {
  const mercariList = sequelize.define("mercari_products", {
    user_id: {
      type: Sequelize.INTEGER
    },
    ASIN: {
      type: Sequelize.STRING
    },
    image_1: {
      type: Sequelize.STRING
    },
    image_2: {
      type: Sequelize.STRING
    },
    image_3: {
      type: Sequelize.STRING
    },
    image_4: {
      type: Sequelize.STRING
    },
    image_5: {
      type: Sequelize.STRING
    },
    image_6: {
      type: Sequelize.STRING
    },
    image_7: {
      type: Sequelize.STRING
    },
    image_8: {
      type: Sequelize.STRING
    },
    image_9: {
      type: Sequelize.STRING
    },
    image_10: {
      type: Sequelize.STRING
    },
    image: {
      type: Sequelize.STRING
    },
    product: {
      type: Sequelize.STRING
    },
    feature: {
      type: Sequelize.STRING
    },
    SKU1_type: {
      type: Sequelize.STRING
    },
    SKU1_inventory: {
      type: Sequelize.STRING
    },
    SKU1_management: {
      type: Sequelize.STRING
    },
    SKU1_jan_code: {
      type: Sequelize.STRING
    },
    SKU2_type: {
      type: Sequelize.STRING
    },
    SKU2_inventory: {
      type: Sequelize.INTEGER
    },
    SKU2_managenment: {
      type: Sequelize.STRING
    },
    SKU2_jan_code: {
      type: Sequelize.STRING
    },
    SKU3_type: {
      type: Sequelize.STRING
    },
    SKU3_inventory: {
      type: Sequelize.INTEGER
    },
    SKU3_managenment: {
      type: Sequelize.STRING
    },
    SKU3_jan_code: {
      type: Sequelize.STRING
    },
    SKU4_type: {
      type: Sequelize.STRING
    },
    SKU4_inventory: {
      type: Sequelize.INTEGER
    },
    SKU4_managenment: {
      type: Sequelize.STRING
    },
    SKU4_jan_code: {
      type: Sequelize.STRING
    },
    SKU5_type: {
      type: Sequelize.STRING
    },
    SKU5_inventory: {
      type: Sequelize.INTEGER
    },
    SKU5_managenment: {
      type: Sequelize.STRING
    },
    SKU5_jan_code: {
      type: Sequelize.STRING
    },
    SKU6_type: {
      type: Sequelize.STRING
    },
    SKU6_inventory: {
      type: Sequelize.INTEGER
    },
    SKU6_managenment: {
      type: Sequelize.STRING
    },
    SKU6_jan_code: {
      type: Sequelize.STRING
    },
    SKU7_type: {
      type: Sequelize.STRING
    },
    SKU7_inventory: {
      type: Sequelize.INTEGER
    },
    SKU7_managenment: {
      type: Sequelize.STRING
    },
    SKU7_jan_code: {
      type: Sequelize.STRING
    },
    SKU8_type: {
      type: Sequelize.STRING
    },
    SKU8_inventory: {
      type: Sequelize.INTEGER
    },
    SKU8_managenment: {
      type: Sequelize.STRING
    },
    SKU8_jan_code: {
      type: Sequelize.STRING
    },
    SKU9_type: {
      type: Sequelize.STRING
    },
    SKU9_inventory: {
      type: Sequelize.INTEGER
    },
    SKU9_managenment: {
      type: Sequelize.STRING
    },
    SKU9_jan_code: {
      type: Sequelize.STRING
    },
    SKU10_type: {
      type: Sequelize.STRING
    },
    SKU10_inventory: {
      type: Sequelize.INTEGER
    },
    SKU10_managenment: {
      type: Sequelize.STRING
    },
    SKU10_jan_code: {
      type: Sequelize.STRING
    },
    brand_id: {
      type: Sequelize.INTEGER
    },
    selling_price: {
      type: Sequelize.INTEGER
    },
    category_id: {
      type: Sequelize.STRING
    },
    commodity: {
      type: Sequelize.INTEGER
    },
    shipping_method: {
      type: Sequelize.INTEGER
    },
    region_origin: {
      type: Sequelize.STRING
    },
    day_ship: {
      type: Sequelize.INTEGER
    },
    product_status: {
      type: Sequelize.INTEGER
    },
    img_url_1: {
      type: Sequelize.STRING
    },
    img_url_2: {
      type: Sequelize.STRING
    },
    img_url_3: {
      type: Sequelize.STRING
    },
    img_url_4: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    });
  return mercariList;
};