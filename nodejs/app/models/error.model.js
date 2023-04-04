module.exports = (sequelize, Sequelize) => {
  const errorList=sequelize.define( "errors", {
    code: {
      type: Sequelize.STRING
    },
    machine_id: {
      type: Sequelize.INTEGER
    }
  },
    {
      timestamps: false
    } );
  return errorList;
};