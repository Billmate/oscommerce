<?php
#ini_set('display_errors', 1);
#error_reporting(E_ALL);
@include_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmate_lang.php');
if(!class_exists('Encoding',false)){
    require_once DIR_FS_CATALOG . DIR_WS_CLASSES.'billmate/utf8.php';
    require_once DIR_FS_CATALOG . DIR_WS_CLASSES.'billmate/commonfunctions.php';
}

class billmatebank {
    var $code, $title, $description, $enabled, $billmatebank_livemode, $billmatebank_testmode, $jQuery, $form_action_url;

    // class constructor
    function billmatebank() {
        global $order, $currency, $currencies, $customer_id, $customer_country_id, $billmatebank_livemode, $billmatebank_testmode;
        $this->jQuery = true;
        $this->code = 'billmatebank';

        if(strpos($_SERVER['SCRIPT_FILENAME'],'admin')) {
            $this->title = MODULE_PAYMENT_BILLMATEBANK_TEXT_TITLE;
        }
        else {
            $this->title = MODULE_PAYMENT_BILLMATEBANK_TEXT_TITLE;
        }

        $this->billmatebank_testmode = false;
        if ((MODULE_PAYMENT_BILLMATEBANK_TESTMODE == 'True')) {
            $this->title .= ' '.MODULE_PAYMENT_BILLMATEBANK_LANG_TESTMODE;
            $this->billmatebank_testmode = true;
        }

        if (MODULE_PAYMENT_BILLMATEBANK_TESTMODE == 'True') {
            $this->form_action_url = 'https://cardpay.billmate.se/pay/test';
        } else {
            $this->form_action_url = 'https://cardpay.billmate.se/pay';
        }
		if (!tep_session_is_registered('admin')) {
			if( $order->billing == null ){
				$billing = $_SESSION['billmate_billing'];
			}else{
				$billing = $_SESSION['billmate_billing'] = $order->billing;
			}


			(MODULE_PAYMENT_BILLMATEBANK_TESTMODE != 'True') ? $this->billmatebank_livemode = true : $this->billmatebank_livemode = false;

			$this->description = MODULE_PAYMENT_BILLMATEBANK_TEXT_DESCRIPTION . "<br />Version: 1.50";
			$this->enabled = ((MODULE_PAYMENT_BILLMATEBANK_STATUS == 'True') ?
					true : false);

			$currencyValid = array('SE','SEK','EU', 'EUR','NOK','NO', 'SE','sek','eu', 'eur','nok','no' );
			$countryValid  = array('SE', 'DK', 'FI', 'NO','se', 'dk', 'fi', 'no');
			$disabled_countries = explode(',',
									trim( 
										strtolower(MODULE_PAYMENT_BILLMATEBANK_DISABLED_COUNTRYIES),
										','
									).','.
									trim( 
										strtoupper(MODULE_PAYMENT_BILLMATEBANK_DISABLED_COUNTRYIES),
										','
									 )
								  );

			/*if (!in_array($currency,$currencyValid)) {
				$this->enabled = false;
			}
			else {*/
				if(is_array($billing)) {
					if(in_array($billing['country']['iso_code_2'],$disabled_countries)) {
						$this->enabled = false;
					}
				}
				else {
					$query = tep_db_query("SELECT countries_iso_code_2 FROM countries WHERE countries_id = " . (int)$_SESSION['customer_country_id']);
					$result = tep_db_fetch_array($query);
			
					if(is_array($result)) {
						if(in_array($result['countries_iso_code_2'],$disabled_countries)) {
							$this->enabled = false;
						}
						$this->enabled = $this->enabled && !in_array($result['countries_iso_code_2'],$disabled_countries);
					}
					else {
						$this->enabled = false;
					}
				}
			//}
			
		
			if(is_object($currencies)) {
				$er = $currencies->get_value($currency);
			}
			else {
				$er = 1;
			}

			if ($order->info['total']*$er > MODULE_PAYMENT_BILLMATEBANK_ORDER_LIMIT)
				$this->enabled = false;

			if ((int)MODULE_PAYMENT_BILLMATEBANK_ORDER_STATUS_ID > 0)
				$this->order_status = MODULE_PAYMENT_BILLMATEBANK_ORDER_STATUS_ID;

			if (is_object($order))
				$this->update_status();
		}
		$this->sort_order = MODULE_PAYMENT_BILLMATEBANK_SORT_ORDER;
    }

    // class methods
    function update_status() {
        global $order;

        if ($this->enabled == true && (int)MODULE_PAYMENT_BILLMATEBANK_ZONE > 0) {
            $check_flag = false;
            $check_query = tep_db_query("select zone_id from " .
                    TABLE_ZONES_TO_GEO_ZONES .
                    " where geo_zone_id = '" .
                    MODULE_PAYMENT_BILLMATEBANK_ZONE .
                    "' and zone_country_id = '" .
                    $order->billing['country']['id'] .
                    "' order by zone_id");

            while ($check = tep_db_fetch_array($check_query)) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                }
                elseif ($check['zone_id'] == $order->billing['zone_id']) {
                    $check_flag = true;
                    break;
                }
            }

            if ($check_flag == false)
                $this->enabled = false;
        }
    }

    function javascript_validation() {
        return false;
    }

    function selection() {

        global $order, $customer_id, $currencies, $currency, $user_billing;

        require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmateutils.php');

        $find_personnummer_field_query =
                tep_db_query("show columns from " . TABLE_CUSTOMERS);

        $has_personnummer = false;
        $has_dob = false;

        while($fields = tep_db_fetch_array($find_personnummer_field_query)) {
            if ($fields['Field'] == "customers_personnummer")
                $has_personnummer = true;
            if ($fields['Field'] == "customers_dob")
                $has_dob = true;
        }

        if ($has_personnummer) {
            $customer_query = tep_db_query("select customers_personnummer from " .
                    TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id."'");
            $customer = tep_db_fetch_array($customer_query);

            $personnummer = $customer['customers_personnummer'];
        }
        else if ($has_dob) {
            $customer_query = tep_db_query("select DATE_FORMAT(customers_dob, '%Y%m%d') AS customers_dob from " .
                    TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id."'");
            $customer = tep_db_fetch_array($customer_query);
            $personnummer = $customer['customers_dob'];
        }
        else {
            $personnummer = "";
        }

        $personnummer = "";

        $er = $currencies->get_value($currency);
        $user_billing = $_SESSION['user_billing'];

        //Fade in/fade out code for the module
        $js = ($this->jQuery) ? BillmateUtils::get_display_jQuery($this->code) : "";
        $popup = '';

        $fields[] = array('title' => BILLMATE_LANG_SE_IMGBANK, 'field' => '');

        return array('id' => $this->code,
                'module' => $this->title,
                'fields' => $fields);
    }

    function pre_confirmation_check() {
        global $billmatebank_testmode, $billmatebank_livemode, $order, $GA_OLD, $KRED_SE_PNO, $user_billing;
        //Store values into Session
        tep_session_register('user_billing');

        $eid = MODULE_PAYMENT_BILLMATEBANK_EID;
        $secret = MODULE_PAYMENT_BILLMATEBANK_SECRET;
    }

    function confirmation() {
        return array('title' => MODULE_PAYMENT_BILLMATEBANK_TEXT_CONFIRM_DESCRIPTION);
    }

    function process_button() {
        global $order, $order_total_modules, $billmatebank_ot, $shipping, $languages_id, $language_id, $language, $currency ;;

        $counter = 1;
        $process_button_string= '';
    
        $eid = MODULE_PAYMENT_BILLMATEBANK_EID;
        $secret = substr( MODULE_PAYMENT_BILLMATEBANK_SECRET, 0, 12 );
		$languages_query = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id = '{$languages_id}'");
		$languages = tep_db_fetch_array($languages_query);
		
		$languageCode = strtoupper( $languages['code'] );
		
		$languageCode = $languageCode == 'DA' ? 'DK' : $languageCode;
		$languageCode = $languageCode == 'SV' ? 'SE' : $languageCode;
		$languageCode = $languageCode == 'EN' ? 'GB' : $languageCode;
    
        $_ = array();
		$_['merchant_id']   = $eid;
		$_['currency']      = $order->info['currency'];
		$_['order_id']      = time();
		$_['callback_url'] = 'http://api.billmate.se/callback.php';
        $_['amount']        = round($order->info['total'], 2)*100;
		$_['accept_url']    = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
		$_['language']		= $languageCode;
		$_['cancel_url']    = tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
		$_['pay_method']    = 'BANK';
		$_['return_method'] = 'GET';
		$_['capture_now']   = 'YES';
        $mac_str = $_['accept_url'] . $_['amount'] . $_['callback_url'] . $_['cancel_url'] . $_['capture_now'] . $_['currency'] . $_['do_3d_secure'] . $_['language'] . $_['merchant_id'] . $_['order_id'] . $_['pay_method'] . $_['prompt_name_entry'] . $_['return_method'] . $secret;
        
		$this->doInvoice();
		billmate_log_data($_, $eid, 'Bank Redirect hidden form');
		
        $mac = hash ( "sha256", $mac_str );

		$_['mac']					= $mac;
        foreach($_ as $key => $col ){
            $process_button_string.=tep_draw_hidden_field($key,$col);
        }
        $order_totals = $order_total_modules->modules;

        if (is_array($order_totals)) {
            reset($order_totals);
            $j = 0;
            $table = preg_split("/[,]/", MODULE_PAYMENT_BILLMATE_ORDER_TOTAL_IGNORE);

            while (list(, $value) = each($order_totals)) {
                $class = substr($value, 0, strrpos($value, '.'));

                if (!$GLOBALS[$class]->enabled) {
                    continue;
                }
                $code = $GLOBALS[$class]->code;
                $ignore=false;


                for ($i=0 ; $i<sizeof($table) && $ignore == false ; $i++) {
                    if ($table[$i] == $code) {
                        $ignore = true;
                    }
                }

                $size = sizeof($GLOBALS[$class]->output);

                if ($ignore == false && $size > 0) {
                    $billmatebank_ot['code_size_'.$j] = $size;
                    for ($i=0; $i<$size; $i++) {
                        $billmatebank_ot['title_'.$j.'_'.$i] = html_entity_decode($GLOBALS[$class]->output[$i]['title']);

                        $billmatebank_ot['text_'.$j.'_'.$i] = $GLOBALS[$class]->output[$i]['text'];
                        if (is_numeric($GLOBALS[$class]->deduction) &&
                                $GLOBALS[$class]->deduction > 0) {
                            $billmatebank_ot['value_'.$j.'_'.$i] = -$GLOBALS[$class]->deduction;
                        }
                        else {
                            $billmatebank_ot['value_'.$j.'_'.$i] = $GLOBALS[$class]->output[$i]['value'];

                            // Add tax rate for shipping address and invoice fee
                            if ($class == 'ot_shipping') {
                                //Set Shipping VAT
                                $shipping_id = @explode('_', $shipping['id']);
                                $tax_class = @$GLOBALS[$shipping_id[0]]->tax_class;
                                $tax_rate = 0;
                                if($tax_class > 0) {
                                    $tax_rate = tep_get_tax_rate($tax_class, $order->billing['country']['id'], ($order->billing['zone_id'] > 0) ? $order->billing['zone_id'] : null);
                                }
                                $billmatebank_ot['tax_rate_'.$j.'_'.$i] = $tax_rate;
                            } else {
                                $billmatebank_ot['tax_rate_'.$j.'_'.$i] = $GLOBALS[$class]->output[$i]['tax_rate'];
                            }
                        }

                        $billmatebank_ot['code_'.$j.'_'.$i] = $GLOBALS[$class]->code;
                    }
                    $j += 1;
                }
            }
            $billmatebank_ot['code_entries'] = $j;
        }

        tep_session_register('billmatebank_ot');
        return $process_button_string;
    }

	function doInvoice($add_order = false ){
	
		 global $order, $customer_id, $currency, $currencies, $sendto, $billto,
				   $billmatebank_ot, $billmatebank_livemode, $billmatebank_testmode,$insert_id;

        $livemode = $this->billmatebank_livemode;

		require (DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmate_api.php');
		require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmateutils.php');
		
		if( empty($_POST ) ) $_POST = $_GET;
		
        //Set the right Host and Port

        $estoreUser = $customer_id;
        $goodsList = array();
        $n = sizeof($order->products);

        // First all the ordinary items
        for ($i = 0 ; $i < $n ; $i++) {
            //    $price_without_tax = ($order->products[$i]['final_price'] * 100/
            //				  (1+$order->products[$i]['tax']/100));
            
            //Rounding off error fix starts
            // Products price with tax
            $price_with_tax = $currencies->get_value($currency) *
                    $order->products[$i]['final_price'] * (1 + $order->products[$i]['tax'] / 100) * 100;
            // Products price without tax
            $price_without_tax = $currencies->get_value($currency) *
                    $order->products[$i]['final_price'] * 100;
            $attributes = "";



            if(isset($order->products[$i]['attributes'])) {
                foreach($order->products[$i]['attributes'] as $attr) {
                    $attributes = $attributes . ", " . $attr['option'] . ": " .
                            $attr['value'];
                }
            }

            if (MODULE_PAYMENT_BILLMATEBANK_ARTNO == 'id' ||
                    MODULE_PAYMENT_BILLMATEBANK_ARTNO == '') {
                $goodsList[] =
                        mk_goods_flags($order->products[$i]['qty'],
                        $order->products[$i]['id'],
                        $order->products[$i]['name'] . $attributes,
                        $price_without_tax,
                        $order->products[$i]['tax'],
                        0,
                        0); //incl VAT
            } else {
                $goodsList[] =
                        mk_goods_flags($order->products[$i]['qty'],
                        $order->products[$i][MODULE_PAYMENT_BILLMATEBANK_ARTNO],
                        $order->products[$i]['name'] . $attributes,
                        $price_without_tax,
                        $order->products[$i]['tax'],
                        0,
                        0); //incl VAT
            }
        }

        // Then the extra charnges like shipping and invoicefee and
        // discount.

        $extra = $billmatebank_ot['code_entries'];
        //end hack

        for ($j=0 ; $j<$extra ; $j++) {
            $size = $billmatebank_ot["code_size_".$j];
            for ($i=0 ; $i<$size ; $i++) {
                $value = $billmatebank_ot["value_".$j."_".$i];
                $name = $billmatebank_ot["title_".$j."_".$i];
                $tax = $billmatebank_ot["tax_rate_".$j."_".$i];
                $name = rtrim($name, ":");
                $code = $billmatebank_ot["code_".$j."_".$i];
                $flags = 0; //INC VAT
                if($code == 'ot_shipping') {
                    $flags += 8; //IS_SHIPMENT
                }
                else if($code == 'ot_'.$this->code.'_fee') {
                    $flags += 16; //IS_HANDLING
                }

/*                if(DISPLAY_PRICE_WITH_TAX == 'true') {
                } else {
                    $price_with_tax = $currencies->get_value($currency) * $value * 100*(($tax/100)+1);
                }*/

				$price_with_tax = $currencies->get_value($currency) * $value * 100;

                if ($value != "" && $value != 0) {
                    $goodsList[] = mk_goods_flags(1, "", BillmateUtils::convertData($name), $price_with_tax, $tax, 0, $flags);
                }

            }
        }

        $secret = (float)MODULE_PAYMENT_BILLMATEBANK_SECRET;
        $eid = (int)MODULE_PAYMENT_BILLMATEBANK_EID;

		$pclass = -1;
		$ship_address = $bill_address = array();

        //$countryData = BillmateCountry::getCountryData($order->billing['country']['iso_code_3']);
		$countryData = BillmateCountry::getSwedenData();
		
	    $ship_address = array(
		    'email'           => $order->customer['email_address'],
		    'telno'           => $order->customer['telephone'],
		    'cellno'          => '',
		    'fname'           => $order->delivery['firstname'],
		    'lname'           => $order->delivery['lastname'],
		    'company'         => $order->delivery['company'],
		    'careof'          => '',
		    'street'          => $order->delivery['street_address'],
		    'zip'             => $order->delivery['postcode'],
		    'city'            => $order->delivery['city'],
		    'country'         => $order->delivery['country']['title'],
	    );
	    $bill_address = array(
		    'email'           => $order->customer['email_address'],
		    'telno'           => $order->customer['telephone'],
		    'cellno'          => '',
		    'fname'           => $order->billing['firstname'],
		    'lname'           => $order->billing['lastname'],
		    'company'         => $order->billing['company'],
		    'careof'          => '',
		    'street'          => $order->billing['street_address'],
		    'house_number'    => '',
		    'house_extension' => '',
		    'zip'             => $order->billing['postcode'],
		    'city'            => $order->billing['city'],
		    'country'         => $order->billing['country']['title'],
	    );

       foreach($ship_address as $key => $col ){
            if(is_numeric($col) ) continue;
            $ship_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
        }
       foreach($bill_address as $key => $col ){
            if(is_numeric($col) ) continue;
            $bill_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
        }
   
        //extract($countryData);
		

		$transaction = array(
			"order1"=>(string)time(),
			"comment"=>(string)"",
			"flags"=>0,
			'gender'=>1,
			'order2'=>'',
			"reference"=>"",
			"reference_code"=>"",
			"currency"=>$countryData['currency'],
			"country"=>209,
			"language"=>$countryData['language'],
			"pclass"=>$pclass,
			"shipInfo"=>array("delay_adjust"=>"1"),
			"travelInfo"=>array(),
			"incomeInfo"=>array(),
			"bankInfo"=>array(),
			"sid"=>array("time"=>microtime(true)),
			"extraInfo"=>array(array("cust_no"=>(string)$customer_id,"creditcard_data"=>$_POST))
		);
		
		$transaction["extraInfo"][0]["status"] = 'Paid';
		
		$ssl = true;
		$debug = false;
		
		$k = new BillMateAPI($eid,$secret,$ssl,$debug);
		$result1 = $k->AddOrder('',$bill_address,$ship_address,$goodsList,$transaction);
	}	
	
    function before_process() {

     global $order, $customer_id, $currency, $currencies, $sendto, $billto,
               $billmatebank_ot, $billmatebank_livemode, $billmatebank_testmode,$insert_id;

    require (DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmate_api.php');
    require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmateutils.php');
	
	if( empty($_POST ) ) $_POST = $_GET;
	
        if(!isset($_POST['status']) || $_POST['status'] != 0){
            tep_redirect(BillmateUtils::error_link(FILENAME_CHECKOUT_PAYMENT,
                    'payment_error=billmatebank&error=' . $_POST['error_message'],
                    'SSL', true, false));
            return;
        } 
        
        //Set the right Host and Port
        $livemode = $this->billmatebank_livemode;

        $estoreUser = $customer_id;
        $goodsList = array();
        $n = sizeof($order->products);

        // First all the ordinary items
        for ($i = 0 ; $i < $n ; $i++) {
            //    $price_without_tax = ($order->products[$i]['final_price'] * 100/
            //				  (1+$order->products[$i]['tax']/100));
            
            //Rounding off error fix starts
            // Products price with tax
            $price_with_tax = $currencies->get_value($currency) *
                    $order->products[$i]['final_price'] * (1 + $order->products[$i]['tax'] / 100) * 100;
            // Products price without tax
            $price_without_tax = $currencies->get_value($currency) *
                    $order->products[$i]['final_price'] * 100;
            $attributes = "";



            if(isset($order->products[$i]['attributes'])) {
                foreach($order->products[$i]['attributes'] as $attr) {
                    $attributes = $attributes . ", " . $attr['option'] . ": " .
                            $attr['value'];
                }
            }

            if (MODULE_PAYMENT_BILLMATEBANK_ARTNO == 'id' ||
                    MODULE_PAYMENT_BILLMATEBANK_ARTNO == '') {
                $goodsList[] =
                        mk_goods_flags($order->products[$i]['qty'],
                        $order->products[$i]['id'],
                        $order->products[$i]['name'] . $attributes,
                        $price_without_tax,
                        $order->products[$i]['tax'],
                        0,
                        0); //incl VAT
            } else {
                $goodsList[] =
                        mk_goods_flags($order->products[$i]['qty'],
                        $order->products[$i][MODULE_PAYMENT_BILLMATEBANK_ARTNO],
                        $order->products[$i]['name'] . $attributes,
                        $price_without_tax,
                        $order->products[$i]['tax'],
                        0,
                        0); //incl VAT
            }
        }

        // Then the extra charnges like shipping and invoicefee and
        // discount.

        $extra = $billmatebank_ot['code_entries'];
        //end hack

        for ($j=0 ; $j<$extra ; $j++) {
            $size = $billmatebank_ot["code_size_".$j];
            for ($i=0 ; $i<$size ; $i++) {
                $value = $billmatebank_ot["value_".$j."_".$i];
                $name = $billmatebank_ot["title_".$j."_".$i];
                $tax = $billmatebank_ot["tax_rate_".$j."_".$i];
                $name = rtrim($name, ":");
                $code = $billmatebank_ot["code_".$j."_".$i];
                $flags = 0; //INC VAT
                if($code == 'ot_shipping') {
                    $flags += 8; //IS_SHIPMENT
                }
                else if($code == 'ot_'.$this->code.'_fee') {
                    $flags += 16; //IS_HANDLING
                }

/*                if(DISPLAY_PRICE_WITH_TAX == 'true') {
                } else {
                    $price_with_tax = $currencies->get_value($currency) * $value * 100*(($tax/100)+1);
                }*/

				$price_with_tax = $currencies->get_value($currency) * $value * 100;

                if ($value != "" && $value != 0) {
                    $goodsList[] = mk_goods_flags(1, "", BillmateUtils::convertData($name), $price_with_tax, $tax, 0, $flags);
                }

            }
        }

        $secret = (float)MODULE_PAYMENT_BILLMATEBANK_SECRET;
        $eid = (int)MODULE_PAYMENT_BILLMATEBANK_EID;

		$pclass = -1;
		$ship_address = $bill_address = array();

        //$countryData = BillmateCountry::getCountryData($order->billing['country']['iso_code_3']);
		$countryData = BillmateCountry::getSwedenData();
		
	    $ship_address = array(
		    'email'           => $order->customer['email_address'],
		    'telno'           => $order->customer['telephone'],
		    'cellno'          => '',
		    'fname'           => $order->delivery['firstname'],
		    'lname'           => $order->delivery['lastname'],
		    'company'         => $order->delivery['company'],
		    'careof'          => '',
		    'street'          => $order->delivery['street_address'],
		    'zip'             => $order->delivery['postcode'],
		    'city'            => $order->delivery['city'],
		    'country'         => $order->delivery['country']['title'],
	    );
	    $bill_address = array(
		    'email'           => $order->customer['email_address'],
		    'telno'           => $order->customer['telephone'],
		    'cellno'          => '',
		    'fname'           => $order->billing['firstname'],
		    'lname'           => $order->billing['lastname'],
		    'company'         => $order->billing['company'],
		    'careof'          => '',
		    'street'          => $order->billing['street_address'],
		    'house_number'    => '',
		    'house_extension' => '',
		    'zip'             => $order->billing['postcode'],
		    'city'            => $order->billing['city'],
		    'country'         => $order->billing['country']['title'],
	    );

       foreach($ship_address as $key => $col ){
            if(is_numeric($col) ) continue;
            $ship_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
        }
       foreach($bill_address as $key => $col ){
            if(is_numeric($col) ) continue;
            $bill_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
        }
   
        //extract($countryData);
		

		$transaction = array(
			"order1"=>(string)time(),
			"comment"=>(string)"",
			"flags"=>0,
			"reference"=>"",
			"reference_code"=>"",
			"currency"=>$countryData['currency'],
			"country"=>209,
			"language"=>$countryData['language'],
			"pclass"=>$pclass,
			"shipInfo"=>array("delay_adjust"=>"1"),
			"travelInfo"=>array(),
			"incomeInfo"=>array(),
			"bankInfo"=>array(),
			"sid"=>array("time"=>microtime(true)),
			"extraInfo"=>array(array("cust_no"=>(string)$customer_id,"creditcard_data"=>$_POST))
		);
		
		
		$transaction["extraInfo"][0]["status"] = 'Paid';


 		require_once DIR_FS_CATALOG . DIR_WS_CLASSES.'/billmate/BillMate.php';
		include_once(DIR_FS_CATALOG . DIR_WS_CLASSES."/billmate/lib/xmlrpc.inc");
		include_once(DIR_FS_CATALOG . DIR_WS_CLASSES."/billmate/lib/xmlrpcs.inc");
		
		$ssl = true;
		$debug = false;
		
		$k = new BillMateAPI($eid,$secret,$ssl,$debug);
		$result1 = $k->AddInvoice('',$bill_address,$ship_address,$goodsList,$transaction);

        if (empty($result1['error'])) {

            // insert address in address book to get correct address in
            // confirmation mail (or fetch correct address from address book
            // if it exists)

            $q = "select countries_id from " . TABLE_COUNTRIES .
                    " where countries_iso_code_2 = 'SE'";

            $check_country_query = tep_db_query($q);
            $check_country = tep_db_fetch_array($check_country_query);

            $cid = $check_country['countries_id'];

            $q = "select address_book_id from " . TABLE_ADDRESS_BOOK .
                    " where customers_id = '" . (int)$customer_id .
                    "' and entry_firstname = '" . $order->delivery['firstname'] .
                    "' and entry_lastname = '" . $order->delivery['lastname'] .
                    "' and entry_street_address = '" . $order->delivery['street_address'] .
                    "' and entry_postcode = '" . $order->delivery['postcode'] .
                    "' and entry_city = '" . $order->delivery['city'] .
                    "' and entry_company = '" . $order->delivery['company'] . "'";
            $check_address_query = tep_db_query($q);
            $check_address = tep_db_fetch_array($check_address_query);
            if(is_array($check_address) && count($check_address) > 0) {
                $sendto = $billto = $check_address['address_book_id'];
            }else {
                $sql_data_array =
                        array('customers_id' => $customer_id,
                        'entry_firstname' => $order->delivery['firstname'],
                        'entry_lastname' => $order->delivery['lastname'],
                        'entry_company' => $order->delivery['company'],
                        'entry_street_address' => $order->delivery['street_address'],
                        'entry_postcode' => $order->delivery['postcode'],
                        'entry_city' => $order->delivery['city'],
                        'entry_country_id' => $cid);

                tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
                $sendto = $billto = tep_db_insert_id();
            }

            $order->billmateref=$result1[1];
            $payment['tan']=$result1[1];
            tep_session_unregister('billmatebank_ot');
            return false;
        } else {
            tep_redirect(BillmateUtils::error_link(FILENAME_CHECKOUT_PAYMENT,
                    'payment_error=billmatebank&error=' .
                    BillmateUtils::error_params($result . " ({$result1['error']})"),
                    'SSL', true, false));
        }
    }

    function after_process() {

        global $insert_id, $order;

        $find_st_optional_field_query =
                tep_db_query("show columns from " . TABLE_ORDERS);

        $has_billmatebank_ref = false;

        while($fields = tep_db_fetch_array($find_st_optional_field_query)) {
            if ( $fields['Field'] == "billmateref" )
                $has_billmatebank_ref = true;
        }

        if ($has_billmatebank_ref) {
            tep_db_query("update " . TABLE_ORDERS . " set billmateref='" .
                    $order->billmateref . "' " . " where orders_id = '" .
                    $insert_id . "'");
        }

        // Insert transaction # into history file

        $sql_data_array = array('orders_id' => $insert_id,
                'orders_status_id' =>
                ($order->info['order_status']),
                'date_added' => 'now()',
                'customer_notified' => 0,
                'comments' => ('Accepted by Billmate ' .
                        date("Y-m-d G:i:s") .
                        ' Invoice #: ' .
                        $order->billmateref));

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        $secret = MODULE_PAYMENT_BILLMATEBANK_SECRET;
        $eid = MODULE_PAYMENT_BILLMATEBANK_EID;
        $invno = $order->billmateref;
        update_orderno($eid, $invno, $secret, utf8_decode($insert_id), $result);

        //Delete Session with user details
        tep_session_unregister('user_billing');

        return false;
    }


    function get_error() {
    
       if (isset($_GET['message']) && strlen($_GET['message']) > 0) {
            $error = stripslashes(urldecode($_GET['message']));
        } else {
            $error = $_GET['error'];
        }
        return array('title' => html_entity_decode(MODULE_PAYMENT_BILLMATE_ERRORINVOICE),
                     'error' => $error);

    }

    function check() {
        if (!isset($this->_check)) {
            $check_query = tep_db_query("select configuration_value from " .
                    TABLE_CONFIGURATION .
                    " where configuration_key = " .
                    "'MODULE_PAYMENT_BILLMATEBANK_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        }
        return $this->_check;
    }

    function install() {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Billmate Module', 'MODULE_PAYMENT_BILLMATEBANK_STATUS', 'True', 'Do you want to accept Billmate payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_BILLMATEBANK_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_BILLMATEBANK_EID', '0', 'Merchant ID (estore id) to use for the Billmate service (provided by Billmate)', '6', '0', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shared secret', 'MODULE_PAYMENT_BILLMATEBANK_SECRET', '', 'Shared secret to use with the Billmate service (provided by Billmate)', '6', '0', now())");


        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Product artno attribute (id or model)', 'MODULE_PAYMENT_BILLMATEBANK_ARTNO', 'id', 'Use the following product attribute for ArtNo.', '6', '2', 'tep_cfg_select_option(array(\'id\', \'model\'),', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Ignore table', 'MODULE_PAYMENT_BILLMATEBANK_ORDER_TOTAL_IGNORE', 'ot_tax,ot_total,ot_subtotal', 'Ignore these entries from order total list when compiling the invoice data', '6', '2', now())");


        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Credit limit', 'MODULE_PAYMENT_BILLMATEBANK_ORDER_LIMIT', '50000', 'Only show this payment alternative for orders less than the value below.', '6', '2', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_BILLMATEBANK_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_BILLMATEBANK_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Testmode', 'MODULE_PAYMENT_BILLMATEBANK_TESTMODE', 'False', 'Do you want to activate the Testmode? We will not pay for the invoices created with the test persons nor companies and we will not collect any fees as well.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Disabled countries', 'MODULE_PAYMENT_BILLMATEBANK_DISABLED_COUNTRYIES', 'se,fi,dk,no', 'Disable in these countries<br/>Enter country ISO Code of two characters <br/>se = Sweden<br/>fi = Finland<br/>dk = Denmark<br/>no = Norway', '10', '0', now())");

    }

    function remove() {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
        return array('MODULE_PAYMENT_BILLMATEBANK_STATUS',
                'MODULE_PAYMENT_BILLMATEBANK_ORDER_STATUS_ID',
                'MODULE_PAYMENT_BILLMATEBANK_EID',
                'MODULE_PAYMENT_BILLMATEBANK_SECRET',
				'MODULE_PAYMENT_BILLMATEBANK_ARTNO',
                'MODULE_PAYMENT_BILLMATEBANK_DISABLED_COUNTRYIES',
                'MODULE_PAYMENT_BILLMATEBANK_ORDER_LIMIT',
                'MODULE_PAYMENT_BILLMATEBANK_ORDER_TOTAL_IGNORE',
                'MODULE_PAYMENT_BILLMATEBANK_TESTMODE',
                'MODULE_PAYMENT_BILLMATEBANK_ZONE',
                'MODULE_PAYMENT_BILLMATEBANK_SORT_ORDER');
    }

}

