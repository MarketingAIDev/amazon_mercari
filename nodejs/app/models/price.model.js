module.exports = (sequelize, Sequelize) => {
  const priceList=sequelize.define( "prices", {
    user_id: {
      type: Sequelize.INTEGER
    },
    up: {
      type: Sequelize.INTEGER
    },
    down: {
      type: Sequelize.INTEGER
    },
    profit: {
      type: Sequelize.INTEGER
    },
    
  },
    {
      timestamps: false
    } );
    return priceList;
  };