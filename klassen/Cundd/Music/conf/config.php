<?php
$config = array();
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Cundd_Music_Model_Adapter_Cundd
$config['database_name'] = 'Music_Release';
$config['image_base_url'] = 'http://ec1.images-amazon.com/images/P/';



//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Flash-Settings
$config['flash_maxCols'] = 5;
$config['flash_maxRows'] = NULL;
$config['flash_spaceBetweenAlbums'] = 8;
$config['flash_albumWidth'] = 200;
$config['flash_albumHeight'] = 200;
$config['flash_objectType'] = 'Cundd.GraphicObject.UrlLoading.RectLazy';



//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Filter
$config['filter_mapping'] = array("color" => "mainColor"); // array("GET-Parameter-Filter-String" => "Filter-Name")




//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Cundd_Music_Model_Entity_Color
$config['Color__scanRows'] = 10;
$config['Color__scanColumns'] = 10;
$config['Color__gray_treshold'] = 20;
$config['Color__colorFields'] = array(
	'color01' => array( 'start' => array(0,0) , 'end' => array(100,15) ),
	'color02' => array( 'minH' => 0 , 'minL' => 85 , 'maxH' => 100 , 'maxL' => 100 ),
	'color03' => array( 0 , 15 , 5 , 85 ),
	'color04' => array( 5 , 15 , 18 , 85 ),
	'color05' => array( 18 , 15 , 39 , 85 ),
	'color06' => array( 39 , 15 , 58 , 85 ),
	'color07' => array( 58 , 15 , 69 , 85 ),
	'color08' => array( 69 , 15 , 77 , 85 ),
	'color09' => array( 77 , 15 , 90 , 85 ),
	'color10' => array( 90 , 15 , 100 , 85 ),
	'color11' => array( 'gray' , 15 , 85 ), // gray
);


?>