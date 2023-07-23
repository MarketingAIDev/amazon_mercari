module.exports = ( sequelize, Sequelize ) =>
{
  const userList = sequelize.define( "users", {
    id: {
      type: Sequelize.INTEGER,
      primaryKey: true
    },
    email: {
      type: Sequelize.STRING
    },
    _token: {
      type: Sequelize.STRING
    },
    password: {
      type: Sequelize.STRING
    },
    role: {
      type: Sequelize.STRING
    },
    family_name: {
      type: Sequelize.STRING
    },
    partnertag: {
      type: Sequelize.STRING
    },
    accesskey: {
      type: Sequelize.STRING
    },
    secretkey: {
      type: Sequelize.STRING
    },
    is_permitted: {
      type: Sequelize.INTEGER
    },
    tracking: {
      type: Sequelize.INTEGER
    },
  },
    {
      timestamps: false
    } );
  return userList;
};