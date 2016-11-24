<?php
/**
 * Created by PhpStorm.
 * User: Boxedsolutions
 * Date: 2016-11-24
 * Time: 09:11
 */
ini_set('display_errors',1);

chdir('../../../../');
require_once('includes/application_top.php');
require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/Billmate.php');
require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/utf8.php');

function partpay($id){
    global $customer_id, $currency, $currencies, $sendto, $billto,
           $pcbillmate,$insert_id, $languages_id, $language_id, $language, $currency, $cart_billmate_card_ID,$billmate_pno,$pclass;

    $pcbillmate = $_SESSION['pcbillmate_ot'];

    require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmateutils.php');
    include_once(DIR_WS_LANGUAGES . $language . '/modules/payment/pcbillmate.php');


    $order = getOrder($id);

    if( empty($_POST ) ) $_POST = $_GET;
    //Set the right Host and Port

    $goodsList = array();
    $n = sizeof($order->products);
    $totalValue = 0;
    $taxValue = 0;
    $codes = array();
    $prepareDiscounts = array();
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

        if (MODULE_PAYMENT_PCBILLMATE_ARTNO == 'id' ||
            MODULE_PAYMENT_PCBILLMATE_ARTNO == '') {
            $temp =
                mk_goods_flags($order->products[$i]['qty'],
                    $order->products[$i]['id'],
                    $order->products[$i]['name'] . $attributes,
                    $price_without_tax,
                    $order->products[$i]['tax'],
                    0,
                    0); //incl VAT
            $totalValue += $temp['withouttax'];
            $taxValue += $temp['tax'];
            $tax1 = (int)$order->products[$i]['tax'];
            if(isset($prepareDiscounts[$tax1])){

                $prepareDiscounts[$tax1] += $temp['withouttax'];
            } else {
                $prepareDiscounts[$tax1] = $temp['withouttax'];
            }

            $goodsList[] = $temp;
        } else {
            $temp =
                mk_goods_flags($order->products[$i]['qty'],
                    $order->products[$i][MODULE_PAYMENT_PCBILLMATE_ARTNO],
                    $order->products[$i]['name'] . $attributes,
                    $price_without_tax,
                    $order->products[$i]['tax'],
                    0,
                    0); //incl VAT
            $totalValue += $temp['withouttax'];
            $taxValue += $temp['tax'];
            $tax1 = (int)$order->products[$i]['tax'];
            if(isset($prepareDiscounts[$tax1])){

                $prepareDiscounts[$tax1] += $temp['withouttax'];
            } else {
                $prepareDiscounts[$tax1] = $temp['withouttax'];
            }
            $goodsList[] = $temp;
        }
    }

    // Then the extra charnges like shipping and invoicefee and
    // discount.

    $extra = $pcbillmate['code_entries'];

    //end hack
    for ($j=0 ; $j<$extra ; $j++) {
        $size = $pcbillmate["code_size_".$j];
        for ($i=0 ; $i<$size ; $i++) {
            $value = $pcbillmate["value_".$j."_".$i];
            $name = $pcbillmate["title_".$j."_".$i];
            $tax = $pcbillmate["tax_rate_".$j."_".$i];
            $name = rtrim($name, ":");
            $code = $pcbillmate["code_".$j."_".$i];

            $price_without_tax = $currencies->get_value($currency) * $value * 100;
            if(DISPLAY_PRICE_WITH_TAX == 'true') {
                $price_without_tax = $price_without_tax/(($tax+100)/100);
            }

            $codes[] = $code;
            if( $code == 'ot_discount' ) { $price_without_tax = 0 - $price_without_tax; }
            if( $code == 'ot_shipping' ){ $shippingPrice = $price_without_tax; $shippingTaxRate = $tax; continue; }

            if ($value != "" && $value != 0) {
                $totals = $totalValue;
                foreach($prepareDiscounts as $tax => $value)
                {
                    $percent = $value / $totals;
                    $price_without_tax_out = $price_without_tax * $percent;
                    $temp = mk_goods_flags(1, "", ($name).' '.(int)$tax.'% '.MODULE_PAYMENT_PCBILLMATE_VAT, $price_without_tax_out, $tax, 0, 0);
                    $totalValue += $temp['withouttax'];
                    $taxValue += $temp['tax'];
                    $goodsList[] = $temp;
                }
            }

        }
    }

    $secret = MODULE_PAYMENT_PCBILLMATE_SECRET;
    $eid = MODULE_PAYMENT_PCBILLMATE_EID;

    $ship_address = $bill_address = array();
    $countryData = BillmateCountry::getSwedenData();
    error_log('sendto'.print_r($sendto,true));
    $ship_address = array(
        "firstname" => $order->delivery['firstname'],
        "lastname" 	=> $order->delivery['lastname'],
        "company" 	=> $order->delivery['company'],
        "street" 	=> $order->delivery['street_address'],
        "street2" 	=> "",
        "zip" 		=> $order->delivery['postcode'],
        "city" 		=> $order->delivery['city'],
        "country" 	=> $order->delivery['country']['iso_code_2'],
        "phone" 	=> $order->customer['telephone'],
    );

    $bill_address = array(
        "firstname" => $order->billing['firstname'],
        "lastname" 	=> $order->billing['lastname'],
        "company" 	=> $order->billing['company'],
        "street" 	=> $order->billing['street_address'],
        "street2" 	=> "",
        "zip" 		=> $order->billing['postcode'],
        "city" 		=> $order->billing['city'],
        "country" 	=> $order->billing['country']['iso_code_2'],
        "phone" 	=> $order->customer['telephone'],
        "email" 	=> $order->customer['email_address'],
    );

    /*foreach($ship_address as $key => $col ){
         if(is_numeric($col) ) continue;
         $ship_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
     }
    foreach($bill_address as $key => $col ){
         if(is_numeric($col) ) continue;
         $bill_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
     }*/

    $ssl = true;
    $debug = false;
    $languageCode = tep_db_fetch_array(tep_db_query("select code from languages where languages_id = " . $languages_id));
    $languageCode['code']  = (strtolower($languageCode['code']) == 'se') ? 'sv' : $languageCode['code'];

    $testmode = false;
    if ((MODULE_PAYMENT_PCBILLMATE_TESTMODE == 'True')) {
        $testmode = true;
    }
    if(!defined('BILLMATE_LANGUAGE')) define('BILLMATE_LANGUAGE',$languageCode['code']);
    if(!defined('BILLMATE_SERVER')) define('BILLMATE_SERVER','2.1.7');
    if(tep_session_is_registered('billmate_pno')){
        $pno = $billmate_pno;
    }

    $k = new BillMate($eid,$secret,$ssl,$testmode,$debug,$codes);
    $invoiceValues = array();
    $lang = $languageCode['code'] == 'se' ? 'sv' : $languageCode['code'];
    $invoiceValues['PaymentData'] = array(	"method" => "4",		//1=Factoring, 2=Service, 4=PartPayment, 8=Card, 16=Bank, 24=Card/bank and 32=Cash.
        "currency" => $currency, //"SEK",
        "paymentplanid" => $pclass,
        "language" => $lang,
        "country" => "SE",
        "orderid" => (string)$cart_billmate_card_ID,
        "bankid" => true,
        "returnmethod" => "GET",
        "accepturl" => tep_href_link(FILENAME_CHECKOUT_PROCESS,'', 'SSL'),
        "cancelurl" => tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'),
        "callbackurl" => tep_href_link('ext/modules/payment/billmate/common_ipn.php', '', 'SSL')
    );
    $invoiceValues['PaymentInfo'] = array( 	"paymentdate" => date('Y-m-d'),
        "yourreference" => "",
        "ourreference" => "",
        "projectname" => "",
        "delivery" => "Post",
        "deliveryterms" => "FOB",
    );

    $invoiceValues['Customer'] = array(
        'customernr'=> (string)$customer_id,
        'pno'=>$pno,
        'Billing'=> $bill_address,
        'Shipping'=> $ship_address
    );
    $invoiceValues['Articles'] = $goodsList;
    $totalValue += $shippingPrice;
    $taxValue += $shippingPrice * ($shippingTaxRate/100);
    $totaltax = round($taxValue,0);
    $totalwithtax = round($order->info['total']*100,0);
    //$totalwithtax += $shippingPrice * ($shippingTaxRate/100);
    $totalwithouttax = $totalValue;
    $rounding = $totalwithtax - ($totalwithouttax+$totaltax);

    $invoiceValues['Cart'] = array(
        "Handling" => array(
            "withouttax" => 0,
            "taxrate" => 0
        ),
        "Shipping" => array(
            "withouttax" => ($shippingPrice)?round($shippingPrice,0):0,
            "taxrate" => ($shippingTaxRate)?$shippingTaxRate:0
        ),
        "Total" => array(
            "withouttax" => $totalwithouttax,
            "tax" => $totaltax,
            "rounding" => $rounding,
            "withtax" => $totalwithtax,
        )
    );
    $result1 = (object)$k->AddPayment($invoiceValues);
    $result1->raw_response = $k->raw_response;


    if(isset($result1->code)){
        billmate_remove_order($cart_billmate_card_ID,true);
        tep_session_unregister('cart_Billmate_card_ID');
        tep_redirect(BillmateUtils::error_link(FILENAME_CHECKOUT_PAYMENT,
            'payment_error=billmate_partpay&error=' . utf8_encode($result1->message)));
        exit;
    } else {
        if($result1->status == 'WaitingForBankIDIdentification' || $result1->status == 'WaitingForBankIDIdentificationForAddressCheck'){
            tep_redirect($result1->url);
            exit;
        } else {
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, rawurlencode('credentials=' . json_encode($result1->raw_response['credentials']) . '&data=' . json_encode($result1->raw_response['data'])), 'SSL'));
            exit;
        }
    }
    
}

function invoice($order_id){
    global $customer_id, $currency, $currencies, $sendto, $billto,
           $billmate_ot,$insert_id, $languages_id, $language_id, $language, $currency, $cart_billmate_card_ID,$billmate_pno,$billmate_billing;

    $billmate_ot = $_SESSION['billmate_ot'];

    require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'billmate/billmateutils.php');
    include_once(DIR_WS_LANGUAGES . $language . '/modules/payment/billmate_invoice.php');
    //require(DIR_WS_CLASSES . 'order.php');




    $order = getOrder($order_id);
    //$order = $order->query($id);
    error_log('order.after'.print_r($order->delivery,true));
    error_log('order.after.cart_billmate..'.print_r($cart_billmate_card_ID,true));
    error_log('order.after.order_id.'.print_r($order_id,true));
    error_log('order.after'.print_r($order->billing,true));

    if( empty($_POST ) ) $_POST = $_GET;
    //Set the right Host and Port

    $goodsList = array();
    $n = sizeof($order->products);
    $totalValue = 0;
    $taxValue = 0;
    $codes = array();
    $prepareDiscounts = array();
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

        if (MODULE_PAYMENT_BILLMATE_ARTNO == 'id' ||
            MODULE_PAYMENT_BILLMATE_ARTNO == '') {
            $temp =
                mk_goods_flags($order->products[$i]['qty'],
                    $order->products[$i]['id'],
                    $order->products[$i]['name'] . $attributes,
                    $price_without_tax,
                    $order->products[$i]['tax'],
                    0,
                    0); //incl VAT
            $totalValue += $temp['withouttax'];
            $taxValue += $temp['tax'];
            $tax1 = (int)$order->products[$i]['tax'];
            if(isset($prepareDiscounts[$tax1])){

                $prepareDiscounts[$tax1] += $temp['withouttax'];
            } else {
                $prepareDiscounts[$tax1] = $temp['withouttax'];
            }

            $goodsList[] = $temp;
        } else {
            $temp =
                mk_goods_flags($order->products[$i]['qty'],
                    $order->products[$i][MODULE_PAYMENT_BILLMATE_ARTNO],
                    $order->products[$i]['name'] . $attributes,
                    $price_without_tax,
                    $order->products[$i]['tax'],
                    0,
                    0); //incl VAT
            $totalValue += $temp['withouttax'];
            $taxValue += $temp['tax'];
            $tax1 = (int)$order->products[$i]['tax'];
            if(isset($prepareDiscounts[$tax1])){

                $prepareDiscounts[$tax1] += $temp['withouttax'];
            } else {
                $prepareDiscounts[$tax1] = $temp['withouttax'];
            }
            $goodsList[] = $temp;
        }
    }

    // Then the extra charnges like shipping and invoicefee and
    // discount.

    $extra = $billmate_ot['code_entries'];

    //end hack
    for ($j=0 ; $j<$extra ; $j++) {
        $size = $billmate_ot["code_size_".$j];
        for ($i=0 ; $i<$size ; $i++) {
            $value = $billmate_ot["value_".$j."_".$i];
            $name = $billmate_ot["title_".$j."_".$i];
            $tax = $billmate_ot["tax_rate_".$j."_".$i];
            $name = rtrim($name, ":");
            $code = $billmate_ot["code_".$j."_".$i];

            $price_without_tax = $currencies->get_value($currency) * $value * 100;
            if(DISPLAY_PRICE_WITH_TAX == 'true') {
                $price_without_tax = $price_without_tax/(($tax+100)/100);
            }

            $codes[] = $code;
            if( $code == 'ot_discount' ) { $price_without_tax = 0 - $price_without_tax; }
            if( $code == 'ot_shipping' ){ $shippingPrice = $price_without_tax; $shippingTaxRate = $tax; continue; }

            if ($value != "" && $value != 0) {
                $totals = $totalValue;
                foreach($prepareDiscounts as $tax => $value)
                {
                    $percent = $value / $totals;
                    $price_without_tax_out = $price_without_tax * $percent;

                    if($code = 'ot_billmate_fee'){
                        $temp = mk_goods_flags(1, "", ($name).' '.(int)$tax.'% '.MODULE_PAYMENT_BILLMATE_VAT, $price_without_tax_out, $tax, 0, true);
                    } else {
                        $temp = mk_goods_flags(1, "", ($name).' '.(int)$tax.'% '.MODULE_PAYMENT_BILLMATE_VAT, $price_without_tax_out, $tax, 0, false);

                    }
                    $totalValue += $temp['withouttax'];
                    $taxValue += $temp['tax'];
                    $goodsList[] = $temp;
                }
            }

        }
    }

    $secret = MODULE_PAYMENT_BILLMATE_SECRET;
    $eid = MODULE_PAYMENT_BILLMATE_EID;

    $ship_address = $bill_address = array();
    $countryData = BillmateCountry::getSwedenData();
    error_log('sendto'.print_r($sendto,true));
    $order->delivery = $order->billing = $billmate_billing;
    $ship_address = array(
        "firstname" => $order->delivery['firstname'],
        "lastname" 	=> $order->delivery['lastname'],
        "company" 	=> $order->delivery['company'],
        "street" 	=> $order->delivery['street_address'],
        "street2" 	=> "",
        "zip" 		=> $order->delivery['postcode'],
        "city" 		=> $order->delivery['city'],
        "country" 	=> $order->delivery['country']['iso_code_2'],
        "phone" 	=> $order->customer['telephone'],
    );

    $bill_address = array(
        "firstname" => $order->billing['firstname'],
        "lastname" 	=> $order->billing['lastname'],
        "company" 	=> $order->billing['company'],
        "street" 	=> $order->billing['street_address'],
        "street2" 	=> "",
        "zip" 		=> $order->billing['postcode'],
        "city" 		=> $order->billing['city'],
        "country" 	=> $order->billing['country']['iso_code_2'],
        "phone" 	=> $order->customer['telephone'],
        "email" 	=> $order->customer['email_address'],
    );
    error_log('address'.print_r($bill_address,true));
    /*foreach($ship_address as $key => $col ){
         if(is_numeric($col) ) continue;
         $ship_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
     }
    foreach($bill_address as $key => $col ){
         if(is_numeric($col) ) continue;
         $bill_address[$key] = utf8_decode(Encoding::fixUTF8( $col ));
     }*/

    $ssl = true;
    $debug = false;
    $testmode =
    $languageCode = tep_db_fetch_array(tep_db_query("select code from languages where languages_id = " . $languages_id));
    $languageCode['code']  = (strtolower($languageCode['code']) == 'se') ? 'sv' : $languageCode['code'];

    if(!defined('BILLMATE_LANGUAGE')) define('BILLMATE_LANGUAGE',$languageCode['code']);
    if(!defined('BILLMATE_SERVER')) define('BILLMATE_SERVER','2.1.7');
    if(tep_session_is_registered('billmate_pno')){
        $pno = $billmate_pno;
    }
    $testmode = false;
    if ((MODULE_PAYMENT_BILLMATE_TESTMODE == 'True')) {
        $testmode = true;
    }
    $k = new BillMate($eid,$secret,$ssl,$testmode,$debug,$codes);
    $invoiceValues = array();
    $lang = $languageCode['code'] == 'se' ? 'sv' : $languageCode['code'];
    $invoiceValues['PaymentData'] = array(	"method" => "1",		//1=Factoring, 2=Service, 4=PartPayment, 8=Card, 16=Bank, 24=Card/bank and 32=Cash.
        "currency" => $currency, //"SEK",
        "language" => $lang,
        "country" => "SE",
        "orderid" => (string)$cart_billmate_card_ID,
        "bankid" => true,
        "returnmethod" => "GET",
        "accepturl" => tep_href_link(FILENAME_CHECKOUT_PROCESS,'', 'SSL'),
        "cancelurl" => tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'),
        "callbackurl" => tep_href_link('ext/modules/payment/billmate/common_ipn.php', '', 'SSL')
    );
    $invoiceValues['PaymentInfo'] = array( 	"paymentdate" => date('Y-m-d'),
        "yourreference" => "",
        "ourreference" => "",
        "projectname" => "",
        "delivery" => "Post",
        "deliveryterms" => "FOB",
    );

    $invoiceValues['Customer'] = array(
        'customernr'=> (string)$customer_id,
        'pno'=>$pno,
        'Billing'=> $bill_address,
        'Shipping'=> $ship_address
    );
    $invoiceValues['Articles'] = $goodsList;
    $totalValue += $shippingPrice;
    $taxValue += $shippingPrice * ($shippingTaxRate/100);
    $totaltax = round($taxValue,0);
    $totalwithtax = round($order->info['total']*100,0);
    //$totalwithtax += $shippingPrice * ($shippingTaxRate/100);
    $totalwithouttax = $totalValue;
    $rounding = $totalwithtax - ($totalwithouttax+$totaltax);

    $invoiceValues['Cart'] = array(
        "Handling" => array(
            "withouttax" => 0,
            "taxrate" => 0
        ),
        "Shipping" => array(
            "withouttax" => ($shippingPrice)?round($shippingPrice,0):0,
            "taxrate" => ($shippingTaxRate)?$shippingTaxRate:0
        ),
        "Total" => array(
            "withouttax" => $totalwithouttax,
            "tax" => $totaltax,
            "rounding" => $rounding,
            "withtax" => $totalwithtax,
        )
    );
    $result1 = (object)$k->AddPayment($invoiceValues);
    $result1->raw_response = $k->raw_response;
    if(isset($result1->code)){

        tep_redirect(BillmateUtils::error_link(FILENAME_CHECKOUT_PAYMENT,
            'payment_error=billmate_partpay&error=' . utf8_encode($result1->message)));
        exit;
    } else {
        if($result1->status == 'WaitingForBankIDIdentification' || $result1->status == 'WaitingForBankIDIdentificationForAddressCheck'){
            tep_redirect($result1->url);
            exit;
        } else {
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, rawurlencode('credentials=' . json_encode($result1->raw_response['credentials']) . '&data=' . json_encode($result1->raw_response['data'])), 'SSL'));
            exit;
        }
    }
}

switch($_GET['method']){
    case 'invoice':
        error_log(print_r($_GET,true));
        invoice($_GET['order_id']);
        break;
    case 'partpay':
        partpay($_GET['order_id']);
        break;
}

function getOrder($order_id){
    global $languages_id;
    $toReturn = new stdClass();
    $order_query = tep_db_query("select customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
    $order = tep_db_fetch_array($order_query);

    error_log('getOrder.query'."select customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");

    error_log('getOrder'.print_r($order,true));
    $totals_query = tep_db_query("select title, text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
    while ($totals = tep_db_fetch_array($totals_query)) {
        $toReturn->totals[] = array('title' => $totals['title'],
            'text' => $totals['text']);
    }

    $order_total_query = tep_db_query("select text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'");
    $order_total = tep_db_fetch_array($order_total_query);

    $shipping_method_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_shipping'");
    $shipping_method = tep_db_fetch_array($shipping_method_query);

    $order_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order['orders_status'] . "' and language_id = '" . (int)$languages_id . "'");
    $order_status = tep_db_fetch_array($order_status_query);

    $toReturn->info = array('currency' => $order['currency'],
        'currency_value' => $order['currency_value'],
        'payment_method' => $order['payment_method'],
        'cc_type' => $order['cc_type'],
        'cc_owner' => $order['cc_owner'],
        'cc_number' => $order['cc_number'],
        'cc_expires' => $order['cc_expires'],
        'date_purchased' => $order['date_purchased'],
        'orders_status' => $order_status['orders_status_name'],
        'last_modified' => $order['last_modified'],
        'total' => strip_tags($order_total['text']),
        'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])));

    $toReturn->customer = array('id' => $order['customers_id'],
        'name' => $order['customers_name'],
        'company' => $order['customers_company'],
        'street_address' => $order['customers_street_address'],
        'suburb' => $order['customers_suburb'],
        'city' => $order['customers_city'],
        'postcode' => $order['customers_postcode'],
        'state' => $order['customers_state'],
        'country' => array('title' => $order['customers_country']),
        'format_id' => $order['customers_address_format_id'],
        'telephone' => $order['customers_telephone'],
        'email_address' => $order['customers_email_address']);

    $toReturn->delivery = array('name' => trim($order['delivery_name']),
        'company' => $order['delivery_company'],
        'street_address' => $order['delivery_street_address'],
        'suburb' => $order['delivery_suburb'],
        'city' => $order['delivery_city'],
        'postcode' => $order['delivery_postcode'],
        'state' => $order['delivery_state'],
        'country' => array('title' => $order['delivery_country']),
        'format_id' => $order['delivery_address_format_id']);

    if (empty($toReturn->delivery['name']) && empty($toReturn->delivery['street_address'])) {
        $toReturn->delivery = false;
    }

    $toReturn->billing = array('name' => $order['billing_name'],
        'company' => $order['billing_company'],
        'street_address' => $order['billing_street_address'],
        'suburb' => $order['billing_suburb'],
        'city' => $order['billing_city'],
        'postcode' => $order['billing_postcode'],
        'state' => $order['billing_state'],
        'country' => array('title' => $order['billing_country']),
        'format_id' => $order['billing_address_format_id']);
    return $toReturn;
}