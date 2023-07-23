const express = require( "express" );
const router = express.Router();
const amazon = require( "../controllers/amazon.controller.js" );

router.post( '/getInfo', amazon.getInfo );
router.post( '/saveExhibition', amazon.saveExhibition );
router.post( '/saveMercari', amazon.saveMercari );
router.post( '/downloadImages', amazon.downloadImages );
router.post( '/saveAmazon', amazon.saveAmazon );
router.post( '/mercariUpdateExportDelete', amazon.mercariUpdateExportDelete );
router.post( '/mercariDeleteAction', amazon.mercariDeleteAction );
router.post( '/amazonGetProducts', amazon.amazonGetProducts );
router.post( '/userProductMercari', amazon.userProductMercari );
router.post( '/getAllUserD', amazon.getAllUserD );

module.exports = router;