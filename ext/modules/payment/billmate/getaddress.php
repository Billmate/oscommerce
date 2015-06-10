<?php
/**
 * Created by PhpStorm.
 * User: jesper
 * Date: 15-04-01
 * Time: 13:28
 */
global $user_billing, $language, $languages_id;
	chdir('../../../../');
	require('includes/application_top.php');
	require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/Billmate.php');
	require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/utf8.php');

	require(DIR_WS_CLASSES . 'order.php');
	// load the language file according to set language.
	include(DIR_WS_LANGUAGES . $language . '/modules/payment/billmate_invoice.php');
	$order = new order;
	$method = $_GET['method'];
	if($method == 'billmate_invoice')
	{
		$method = 'billmate';
		$secret   = MODULE_PAYMENT_BILLMATE_SECRET;
		$eid      = MODULE_PAYMENT_BILLMATE_EID;
		$testmode = ((MODULE_PAYMENT_BILLMATE_TESTMODE == 'True')) ? true : false;
	}
	if($method == 'pcbillmate'){
		$secret   = MODULE_PAYMENT_PCBILLMATE_SECRET;
		$eid      = MODULE_PAYMENT_PCBILLMATE_EID;
		$testmode = ((MODULE_PAYMENT_PCBILLMATE_TESTMODE == 'True')) ? true : false;
	}
	$ssl = true;
	$debug = false;

	$languageCode = tep_db_fetch_array(tep_db_query("select code from languages where languages_id = " . $languages_id));
	if(!defined('BILLMATE_LANGUAGE')) define('BILLMATE_LANGUAGE',$languageCode['code']);

	$billmate = new BillMate($eid,$secret,true,$testmode,false);
	$address = $billmate->getAddress(array('pno' => $_POST[$method.'_pnum']));



	$user_billing[$method.'_pnum'] = $_POST[$method.'_pnum'];
	$user_billing[$method.'_email'] = $_POST[$method.'_email'];
	$user_billing[$method.'_invoice_type'] = $_POST[$method.'_invoice_type'];

	tep_session_register('user_billing');

	if (isset($address['code']) || empty($address) || !is_array($address))
		die(json_encode(array('success' => false, 'content' => utf8_encode($address['message']),'popup' => false)));

	foreach($address as $key => $value)
		$address[$key] = utf8_encode($value);



	$billing = $order->billing;
	$delivery = $order->delivery;

	$fullname = $billing['firstname'].' '.$billing['lastname'] .' '.$billing['company'];
	if( empty ( $address['firstname'] ) ){
		$apiName = $fullname;
	} else {
		$apiName  = $address['firstname'].' '.$address['lastname'];
	}


	$firstArr = explode(' ', $order->billing['firstname']);
	$lastArr  = explode(' ', $order->billing['lastname']);

	if( empty( $address['firstname'] ) ){
		$apifirst = $firstArr;
		$apilast  = $lastArr ;
	}else {
		$apifirst = explode(' ', $address['firstname'] );
		$apilast  = explode(' ', $address['lastname'] );
	}

	$matchedFirst = array_intersect($apifirst, $firstArr );
	$matchedLast  = array_intersect($apilast, $lastArr );
	$apiMatchedName   = !empty($matchedFirst) && !empty($matchedLast);

	$addressNotMatched = !isEqual($address['street'], $billing['street_address'] ) ||
	                     !isEqual($address['zip'], $billing['postcode']) ||
	                     !isEqual($address['city'], $billing['city']) ||
	                     !isEqual($address['country'], $billing['country']['iso_code_2']);

	$shippingAndBilling =  !$apiMatchedName ||
	                       !isEqual($billing['street_address'],  $delivery['street_address'] ) ||
	                       !isEqual($billing['postcode'], $delivery['postcode']) ||
	                       !isEqual($billing['city'], $delivery['city']) ||
	                       !isEqual($billing['country']['iso_code_3'], $delivery['country']['iso_code_3']);

	if( $addressNotMatched || $shippingAndBilling ){

		if(empty($_POST['geturl'])){
			$html = '<p><b>'.MODULE_PAYMENT_BILLMATE_CORRECT_ADDRESS.' </b></p>'.($address['firstname']).' '.$address['lastname'].'<br>'.$address['street'].'<br>'.$address['zip'].' '.$address['city'].'<div style="padding: 17px 0px;"> <i>'.MODULE_PAYMENT_BILLMATE_CORRECT_ADDRESS_OPTION.'</i></div> <input type="button" value="'.MODULE_PAYMENT_BILLMATE_YES.'" onclick="updateAddress();" class="button"/> <input type="button" value="'.MODULE_PAYMENT_BILLMATE_NO.'" onclick="closefunc(this)" class="button" style="float:right" />';
			die(json_encode(array('success' => false, 'content' => utf8_encode($html),'popup' => true)));
		} else {
			if($address->firstname == "") {
				$billmate_fname = $order->billing['firstname'];
				$billmate_lname = $order->billing['lastname'];
				$company_name   = $address['company'];
			}else {
				$billmate_fname = $address['firstname'];
				$billmate_lname = $address['lastname'];
				$company_name   = '';
			}

			$billmate_street = $address['street'];
			$billmate_postno = $address['zip'];
			$billmate_city = $address['city'];

			$order->delivery['firstname'] = $billmate_fname;
			$order->billing['firstname'] = $billmate_fname;
			$order->delivery['lastname'] = $billmate_lname;
			$order->billing['lastname'] = $billmate_lname;
			$order->delivery['company'] = $company_name;
			$order->billing['suburb'] = $order->delivery['suburb'] = '';
			$order->billing['company'] = $company_name;
			$order->delivery['street_address'] = $billmate_street;
			$order->billing['street_address'] = $billmate_street;
			$order->delivery['postcode'] = $billmate_postno;
			$order->billing['postcode'] = $billmate_postno;
			$order->delivery['city'] = $billmate_city;
			$order->billing['city'] = $billmate_city;


			//Set same country information to delivery
			$order->delivery['state'] = $order->billing['state'];
			$order->delivery['zone_id'] = $order->billing['zone_id'];
			$order->delivery['country_id'] = $order->billing['country_id'];
			$order->delivery['country']['id'] = $order->billing['country']['id'];
			$order->delivery['country']['title'] = $order->billing['country']['title'];
			$order->delivery['country']['iso_code_2'] = $order->billing['country']['iso_code_2'];
			$order->delivery['country']['iso_code_3'] = $order->billing['country']['iso_code_3'];
			die(json_encode(array('success' => true)));
		}

	}

