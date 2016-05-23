<?php


//===============================================
// Configuration
//===============================================

if( class_exists('Config') && method_exists(new Config(),'register')){

	// Register variables
	Config::register("paypal", "key", "0000000");
	Config::register("paypal", "secret", "AAAAAAAAA");

}

?>
