<?php
/**
 *  Copyright 2010 BILLMATE AB. All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without modification, are
 *  permitted provided that the following conditions are met:
 *
 *     1. Redistributions of source code must retain the above copyright notice, this list of
 *        conditions and the following disclaimer.
 *
 *     2. Redistributions in binary form must reproduce the above copyright notice, this list
 *        of conditions and the following disclaimer in the documentation and/or other materials
 *        provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY BILLMATE AB "AS IS" AND ANY EXPRESS OR IMPLIED
 *  WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 *  FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL BILLMATE AB OR
 *  CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 *  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 *  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 *  ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *  The views and conclusions contained in the software and documentation are those of the
 *  authors and should not be interpreted as representing official policies, either expressed
 *  or implied, of BILLMATE AB.
 *
 */

class ot_billmate_den_fee {
    var $title, $output;

    function ot_billmate_den_fee() {
        $this->code = 'ot_billmate_den_fee';
        if(strpos($_SERVER['SCRIPT_FILENAME'],'admin')) {
            $this->title = "Billmate - Invoice fee (DK)";
        }
        else {
            $this->title = MODULE_BILLMATE_DEN_FEE_TITLE;
        }
        $this->description = MODULE_BILLMATE_DEN_FEE_DESCRIPTION;
        $this->enabled = MODULE_BILLMATE_DEN_FEE_STATUS;
        $this->sort_order = MODULE_BILLMATE_DEN_FEE_SORT_ORDER;
        $this->tax_class = MODULE_BILLMATE_DEN_FEE_TAX_CLASS;
        $this->output = array();
    }

    function process() {
        global $order, $ot_subtotal, $currencies;

        $od_amount = $this->calculate_credit($this->get_order_total());

        //Disable module when $od_amount is <= 0
        if ($od_amount <= 0)
            $this->enabled = false;

        if ($od_amount != 0) {
            $tax_rate =tep_get_tax_rate(MODULE_BILLMATE_DEN_FEE_TAX_CLASS);

            $this->output[] = array('title' => $this->title . ':',
                    'text' => $currencies->format($od_amount),
                    'value' => $od_amount,
                    'tax_rate' => $tax_rate);
            $order->info['total'] = $order->info['total'] + $od_amount;
        }
    }


    function calculate_credit($amount) {
        global $order, $customer_id, $payment, $sendto, $customer_id,
        $customer_zone_id,$customer_country_id, $cart, $currencies, $currency;

        $od_amount=0;

        if ($payment != "billmate_den")
            return $od_amount;

        if (MODULE_BILLMATE_DEN_FEE_MODE == 'fixed') {
            $od_amount = MODULE_BILLMATE_DEN_FEE_FIXED;
        }
        else {
            $table = preg_split("/[:,]/" , MODULE_BILLMATE_DEN_FEE_TABLE);

            $size = sizeof($table);
            for ($i=0, $n=$size; $i<$n; $i+=2) {
                if ($amount <= $table[$i]) {
                    $od_amount = $table[$i+1];
                    break;
                }
            }
        }

        if ($od_amount == 0)
            return $od_amount;

        if (MODULE_BILLMATE_DEN_FEE_TAX_CLASS > 0) {
            $tod_rate =tep_get_tax_rate(MODULE_BILLMATE_DEN_FEE_TAX_CLASS);
            $tod_amount = $od_amount - $od_amount/($tod_rate/100+1);
            $order->info['tax'] += $tod_amount;
            $tax_desc = tep_get_tax_description(
                    MODULE_BILLMATE_DEN_FEE_TAX_CLASS,
                    $customer_country_id, $customer_zone_id);
            $order->info['tax_groups']["$tax_desc"] += $tod_amount;
        }

        if (DISPLAY_PRICE_WITH_TAX=="true") {
            $od_amount = $od_amount;
        } else {
            $od_amount = $od_amount-$tod_amount;
            $order->info['total'] += $tod_amount;
        }

        return ($od_amount/$currencies->get_value($currency));
    }


    function get_order_total() {
        global  $order, $cart, $currencies, $currency;
        $order_total = $order->info['total'];

// Check if gift voucher is in cart and adjust total
        $products = $cart->get_products();

        for ($i=0; $i<sizeof($products); $i++) {
            $t_prid = tep_get_prid($products[$i]['id']);

            $gv_query = tep_db_query(
                    "select products_price, products_tax_class_id, ".
                    "products_model from " . TABLE_PRODUCTS .
                    " where products_id = '" . $t_prid . "'");

            $gv_result = tep_db_fetch_array($gv_query);

            if (preg_match('/^GIFT/', addslashes($gv_result['products_model']))) {
                $qty = $cart->get_quantity($t_prid);
                $products_tax =
                        tep_get_tax_rate($gv_result['products_tax_class_id']);

                if ($this->include_tax =='false') {
                    $gv_amount = $gv_result['products_price'] * $qty;
                } else {
                    $gv_amount = ($gv_result['products_price'] +
                                    tep_calculate_tax(
                                    $gv_result['products_price'],
                                    $products_tax)) * $qty;
                }
                $order_total=$order_total - $gv_amount;
            }
        }

        if ($this->include_tax == 'false')
            $order_total=$order_total-$order->info['tax'];

        if ($this->include_shipping == 'false')
            $order_total=$order_total-$order->info['shipping_cost'];

        return $order_total*$currencies->get_value($currency);
    }


    function check() {
        if (!isset($this->check)) {
            $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_BILLMATE_DEN_FEE_STATUS'");
            $this->check = tep_db_num_rows($check_query);
        }

        return $this->check;
    }

    function keys() {
        return array('MODULE_BILLMATE_DEN_FEE_STATUS',
                'MODULE_BILLMATE_DEN_FEE_MODE',
                'MODULE_BILLMATE_DEN_FEE_FIXED',
                'MODULE_BILLMATE_DEN_FEE_TABLE',
                'MODULE_BILLMATE_DEN_FEE_TAX_CLASS',
                'MODULE_BILLMATE_DEN_FEE_SORT_ORDER'
        );
    }

    function install() {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_BILLMATE_DEN_FEE_STATUS', 'true', 'Do you want to display the payment charge', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BILLMATE_DEN_FEE_SORT_ORDER', '0', 'Sort order of display.', '6', '2', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fixed invoice charge', 'MODULE_BILLMATE_DEN_FEE_FIXED', '20', 'Fixed invoice charge (inc. VAT) in DKK.', '6', '7', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Charge Table', 'MODULE_BILLMATE_DEN_FEE_TABLE', '200:20,500:10,10000:5', 'The invoice charge is based on the total cost. Example: 200:20.500,10:10000:5,etc.. Up to 200 charge 20, from there to 500 charge 10, etc', '6', '2', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_BILLMATE_DEN_FEE_TAX_CLASS', '0', 'Use the following tax class on the payment charge.', '6', '6', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Charge Type', 'MODULE_BILLMATE_DEN_FEE_MODE', 'fixed', 'Invoice charge is fixed or based  on the order total.', '6', '0', 'tep_cfg_select_option(array(\'fixed\', \'price\'), ', now())");


    }

    function remove() {
        $keys = '';
        $keys_array = $this->keys();
        for ($i=0; $i<sizeof($keys_array); $i++) {
            $keys .= "'" . $keys_array[$i] . "',";
        }
        $keys = substr($keys, 0, -1);

        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }
}
?>