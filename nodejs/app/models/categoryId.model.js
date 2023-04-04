module.exports = (sequelize, Sequelize) => {
  const categoryIdList=sequelize.define( "category_ids", {
      user_id: {
        type:Sequelize.INTEGER
      },
      category_id: {
        type: Sequelize.STRING
      },
      category: {
        type: Sequelize.STRING
      },
      all_category: {
        type: Sequelize.STRING
      },
    },
    { 
      timestamps: false
    });
    return categoryIdList;
  };