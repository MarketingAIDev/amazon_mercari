module.exports = {
	HOST: "localhost",
	USER: "root",
	PASSWORD: "",
	DB: "amazon_mercari",
	dialect: "mysql",
	pool: {
		max: 150,
		min: 0,
		acquire: 220000,
		idle: 220000
	}
};