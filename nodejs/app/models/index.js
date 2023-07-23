const dbConfig = require( "../config/db.config.js" );
const Sequelize = require( "sequelize" );
const sequelize = new Sequelize( dbConfig.DB, dbConfig.USER, dbConfig.PASSWORD, {
  host: dbConfig.HOST,
  dialect: dbConfig.dialect,
  // operatorsAliases: false,
  operatorsAliases: 0,
  pool: {
    max: dbConfig.pool.max,
    min: dbConfig.pool.min,
    acquire: dbConfig.pool.acquire,
    idle: dbConfig.pool.idle
  }
} );
const db = {};
db.Sequelize = Sequelize;
db.sequelize = sequelize;

db.categories = require( "./category.model.js" )( sequelize, Sequelize );
db.categoryIds = require( "./categoryId.model.js" )( sequelize, Sequelize );
db.products = require( "./product.model.js" )( sequelize, Sequelize );
db.exhibitions = require( "./exhibition.model.js" )( sequelize, Sequelize );
db.mercaris = require( "./mercari.model.js" )( sequelize, Sequelize );
db.mercariUpdates = require( "./mercari_update.model.js" )( sequelize, Sequelize );
db.users = require( "./user.model.js" )( sequelize, Sequelize );
db.errors = require( "./error.model.js" )( sequelize, Sequelize );
db.ng_words = require( "./ng_word.model.js" )( sequelize, Sequelize );
db.ng_categories = require( "./ng_category.model.js" )( sequelize, Sequelize );
db.ng_products = require( "./ng_product.model.js" )( sequelize, Sequelize );
db.postages = require( "./postage.model.js" )( sequelize, Sequelize );
db.prices = require( "./price.model.js" )( sequelize, Sequelize );
db.settings = require( "./setting.model.js" )( sequelize, Sequelize );
module.exports = db;