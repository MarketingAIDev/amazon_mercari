module.exports = (sequelize, Sequelize) => {
  const ngProductList=sequelize.define( "ng_products", {
    user_id: {
      type: Sequelize.INTEGER
    },
    product: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    } );
    return ngProductList;
  };