module.exports = (sequelize, Sequelize) => {
  const ngCategoryList=sequelize.define( "ng_categories", {
    user_id: {
      type: Sequelize.INTEGER
    },
    category: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    } );
    return ngCategoryList;
  };