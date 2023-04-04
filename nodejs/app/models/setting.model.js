module.exports = (sequelize, Sequelize) => {
  const settingList=sequelize.define( "settings", {
    user_id: {
      type: Sequelize.INTEGER
    },
    prime: {
      type: Sequelize.INTEGER
    },
    mark: {
      type: Sequelize.INTEGER
    },
    sentence: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    } );
    return settingList;
  };