const express = require( "express" );
const router = express.Router();
const amazon = require( "../controllers/amazon.controller.js" );

router.post( '/getInfo', amazon.getInfo );
router.post( '/downloadImages', amazon.downloadImages );
router.post( '/downloadImageZip', amazon.downloadImageZip );
router.post( '/saveExhibition', amazon.saveExhibition );
router.post( '/saveMercari', amazon.saveMercari );

module.exports = router;