module.exports = (sequelize, Sequelize) => {
  const ngWordList=sequelize.define( "ng_words", {
    user_id: {
      type: Sequelize.INTEGER
    },
    word: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    } );
    return ngWordList;
  };