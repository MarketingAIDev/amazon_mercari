module.exports = ( sequelize, Sequelize ) =>
{
  const exhibitionList = sequelize.define( "exhibitions", {
    user_id: {
      type: Sequelize.INTEGER
    },
    amazon_id: {
      type: Sequelize.INTEGER
    },
    m_code: {
      type: Sequelize.STRING
    },
    ASIN: {
      type: Sequelize.STRING
    },
    image: {
      type: Sequelize.STRING
    },
    product: {
      type: Sequelize.STRING
    },
    prime: {
      type: Sequelize.STRING
    },
    feature: {
      type: Sequelize.STRING
    },
    price: {
      type: Sequelize.INTEGER
    },
    e_price: {
      type: Sequelize.INTEGER
    },
    a_category: {
      type: Sequelize.STRING
    },
    m_category: {
      type: Sequelize.STRING
    },
    m_category_id: {
      type: Sequelize.STRING
    },
    postage: {
      type: Sequelize.INTEGER
    },
    profit: {
      type: Sequelize.INTEGER
    },
    etc: {
      type: Sequelize.INTEGER
    },
    exclusion: {
      type: Sequelize.STRING
    },
    condition_n_u: {
      type: Sequelize.INTEGER
    },
    inventory: {
      type: Sequelize.INTEGER
    },
  },
    {
      timestamps: false
    } );
  return exhibitionList;
};