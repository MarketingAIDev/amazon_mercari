module.exports = {
	HOST: "localhost",
	USER: "root",
	PASSWORD: "",
	DB: "amazon_mercari",
	dialect: "mysql",
	pool: {
		max: 5,
		min: 0,
		acquire: 90000,
		idle: 10000
	}
};