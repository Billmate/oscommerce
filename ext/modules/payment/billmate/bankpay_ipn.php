<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

chdir('../../../../');
require('includes/application_top.php');
  

  if (!defined('MODULE_PAYMENT_BILLMATEBANK_STATUS') || (MODULE_PAYMENT_BILLMATEBANK_STATUS  != 'True')) {
    exit;
  }

  @include_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmate_lang.php');
  require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmateutils.php');	
if(!class_exists('Encoding',false)){
    require_once DIR_FS_CATALOG . DIR_WS_CLASSES.'billmate/utf8.php';
    require_once DIR_FS_CATALOG . DIR_WS_CLASSES.'billmate/commonfunctions.php';
}

	$response = file_get_contents("php://input");
	$input = json_decode($response, true);
	$_DATA = $input['data'];
	$_DATA['order_id'] = $_DATA['orderid'];

	if(isset($_DATA['status']) || $_DATA['status'] == 'Paid'){
		if (isset($_DATA['order_id']) && ($_DATA['order_id'] > 0)) {

			$secret = MODULE_PAYMENT_BILLMATEBANK_SECRET;
			$eid = MODULE_PAYMENT_BILLMATEBANK_EID;
			$ssl = true;
			$debug = false;
			$testmode = ((MODULE_PAYMENT_BILLMATEBANK_TESTMODE == 'True')) ? true : false;

			if( (MODULE_PAYMENT_BILLMATEBANK_AUTHENTICATION_MODE != 'sale') ) {
				$k = new BillMate($eid,$secret,$ssl, $testmode,$debug);
				$result1 = (object)$k->UpdatePayment( array('PaymentData'=> array("number"=>$_DATA['number'], "orderid"=>(string)$_DATA['order_id'])));
			} else {
				$result1 = (object)$_DATA;
			}
			if(is_string($result1) || (isset($result1->message) && is_object($result1))){
			} else {

				$find_st_optional_field_query =
						tep_db_query("show columns from " . TABLE_ORDERS);
		
				$has_billmatecardpay_ref = false;
		
				while($fields = tep_db_fetch_array($find_st_optional_field_query)) {
					if ( $fields['Field'] == "billmateref" )
						$has_billmatecardpay_ref = true;
				}
		
				if ($has_billmatecardpay_ref) {
					tep_db_query("update " . TABLE_ORDERS . " set billmateref='" .
							$result1->number . "' " . " where orders_id = '" .
							$_DATA['order_id'] . "'");
				}
		
				// Insert transaction # into history file
		
				$sql_data_array = array('orders_id' => $_DATA['order_id'],
										'orders_status_id' => MODULE_PAYMENT_BILLMATEBANK_ORDER_STATUS_ID,
										'date_added' => 'now()',
										'customer_notified' => 0,
										'comments' => ('Billmate_IPN')
									);
				tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

				$sql_data_array = array('orders_id' => $_DATA['order_id'],
										'orders_status_id' => MODULE_PAYMENT_BILLMATEBANK_ORDER_STATUS_ID,
										'date_added' => 'now()',
										'customer_notified' => 0,
										'comments' => ('Accepted by Billmate ' . date("Y-m-d G:i:s") .' Invoice #: ' . $result1->number)
									);
				tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
				
				tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . (MODULE_PAYMENT_BILLMATEBANK_ORDER_STATUS_ID ) . "', last_modified = now() where orders_id = '" . (int)$_DATA['order_id'] . "'");

			}
		} 
	} else {
		$email_body = '$_DATA:' . "\n\n";
		reset($_DATA);
		while (list($key, $value) = each($_DATA)) {
			$email_body .= $key . '=' . $value . "\n";
		}
	}
	exit;
	
	// -------------------------------------------------------------------------------------- //

  require('includes/application_bottom.php');
?>
