module.exports = (sequelize, Sequelize) => {
  const categoryList=sequelize.define( "categories", {
    user_id: {
      type: Sequelize.INTEGER
    },
    a_c_root: {
      type: Sequelize.STRING
    },
    a_c_sub: {
      type: Sequelize.STRING
    },
    m_category: {
      type: Sequelize.STRING
    },
  },
    {
      timestamps: false
    } );
    return categoryList;
  };