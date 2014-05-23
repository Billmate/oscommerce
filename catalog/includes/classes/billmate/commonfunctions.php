<?php
if(!function_exists('getCountryID')){

	function error_report($ip=''){
		if( empty($ip)) return;

		if( $_SERVER['REMOTE_ADDR'] == $ip ){
			ini_set('display_errors', 1 );
			error_reporting(E_ALL);
		}
	}
	function mdie($ip='', $msg){
		if( empty($ip)) return;

		if( $_SERVER['REMOTE_ADDR'] == $ip ){
			die($msg);
		}
	}
	define('BILLPLUGIN_VERSION','1.15');
	define('BILLMATE_VERSION',  "PHP:OsCommerce:".BILLPLUGIN_VERSION );

	function getCountryID(){
		return 209;
		$country = strtoupper(shopp_setting('base_operations'));
		switch($country){
			case 'SE': return 209;
			case 'FI': return 73;
			case 'DK': return 59;
			case 'NO': return 164;
			default :
				return 209;
		}
	}
}