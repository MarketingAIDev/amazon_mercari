module.exports = {
	HOST: "localhost",
	USER: "root",
	PASSWORD: "",
	DB: "amazon_mercari",
	dialect: "mysql",
	pool: {
		max: 5,
		min: 0,
		acquire: 120000,
		idle: 120000
	},
	dialectOptions: {
		connectTimeout: 90000
	}
};