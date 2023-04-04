module.exports = ( sequelize, Sequelize ) =>
{
  const productList = sequelize.define( "amazon_products", {
    user_id: {
      type: Sequelize.INTEGER
    },
    inventory: {
      type: Sequelize.INTEGER
    },
    image: {
      type: Sequelize.STRING
    },
    ASIN: {
      type: Sequelize.STRING
    },
    product: {
      type: Sequelize.STRING
    },
    prime: {
      type: Sequelize.STRING
    },
    price: {
      type: Sequelize.INTEGER
    },
    r_price: {
      type: Sequelize.INTEGER
    },
    attribute: {
      type: Sequelize.STRING
    },
    feature_1: {
      type: Sequelize.STRING
    },
    feature_2: {
      type: Sequelize.STRING
    },
    feature_3: {
      type: Sequelize.STRING
    },
    feature_4: {
      type: Sequelize.STRING
    },
    feature_5: {
      type: Sequelize.STRING
    },
    feature: {
      type: Sequelize.STRING
    },
    rank: {
      type: Sequelize.STRING
    },
    p_length: {
      type: Sequelize.STRING
    },
    p_width: {
      type: Sequelize.STRING
    },
    p_height: {
      type: Sequelize.STRING
    },
    a_c_root: {
      type: Sequelize.STRING
    },
    a_c_sub: {
      type: Sequelize.STRING
    },
    a_c_tree: {
      type: Sequelize.STRING
    },
    m_code: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    } );
  return productList;
};