module.exports = ( sequelize, Sequelize ) =>
{
  const settingList = sequelize.define( "settings", {
    user_id: {
      type: Sequelize.INTEGER
    },
    prime: {
      type: Sequelize.INTEGER
    },
    mark: {
      type: Sequelize.INTEGER
    },
    price_cut: {
      type: Sequelize.INTEGER
    },
    price_reduction: {
      type: Sequelize.INTEGER
    },
    price_cut_date: {
      type: Sequelize.INTEGER
    },
    etc: {
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