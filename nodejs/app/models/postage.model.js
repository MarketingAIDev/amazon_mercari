module.exports = (sequelize, Sequelize) => {
  const postageList=sequelize.define( "postages", {
    user_id: {
      type: Sequelize.INTEGER
    },
    width: {
      type: Sequelize.INTEGER
    },
    length: {
      type: Sequelize.INTEGER
    },
    height: {
      type: Sequelize.INTEGER
    },
    final: {
      type: Sequelize.INTEGER
    },
    size: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    } );
    return postageList;
  };