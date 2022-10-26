<?php

include(dirname(__FILE__).'/../includes/methods/ebspayment.php');

class ebspayment
{
    public $code, $title, $description, $enabled;
    public $ebs_allowed_currencies = array('CAD', 'EUR', 'GBP', 'JPY', 'USD', 'MXN', 'AUD', 'NZD', 'BRL', 'INR');

    // class constructorform_action_url
    public function ebspayment()
    {
        global $order;

        $this->code = 'ebspayment';
        $this->title = MODULE_PAYMENT_EBS_TEXT_TITLE;
        $this->description = MODULE_PAYMENT_EBS_TEXT_DESCRIPTION;
        $this->sort_order = 1;
        $this->enabled = ((MODULE_PAYMENT_EBS_STATUS == 'True') ? true : false);
        $this->form_action_url = EBS_PAYMENT_URL;
		$this-> ebsmode = EBS_PAYMENT_MODE;
		$this-> ebsaccountid = EBS_MODULE_ACCOUNT_ID;
		$this-> ebssecretkey = MODULE_PAYMENT_EBS_ID;
    }

    // class methods
    public function update_status()
    {
        global $order;

        if ( ($this->enabled == true) && ((int) MODULE_PAYMENT_EBS_ZONE > 0) ) {
            $check_flag = false;
            $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_EBS_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
            while ($check = tep_db_fetch_array($check_query)) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check['zone_id'] == $order->billing['zone_id']) {
                    $check_flag = true;
                    break;
                }
            }

            if ($check_flag == false) {
                $this->enabled = false;
            }
        }
    }

    public function javascript_validation()
    {
        return false;
    }

    public function selection()
    {
        return array('id' => $this->code, 'module' => $this->title);
    }

    public function pre_confirmation_check()
    {
        return false;
    }

    public function confirmation()
    {
        return false;
    }

    public function process_button($transactionID = 0, $key= "")
    {
        global $order, $currencies, $currency;
		
        $my_currency = strtoupper($GLOBALS['A2B']->config['global']['base_currency']);

        if (!in_array($my_currency, $this->ebspayment_allowed_currencies)) {
            $my_currency = 'USD';
        }
		$currencyObject = new currencies();
		$data_array = array(
		"channel"=>"0",
		//"account_id"=>"5880",
		"account_id"=>$this-> ebsaccountid ,
		"reference_no"=>$transactionID,
		"amount"=>number_format($order->info['total'], $currencyObject->get_decimal_places($my_currency)),
		"currency"=>"INR",
		"description"=>STORE_NAME,
		"return_url"=>tep_href_link("checkout_process_ebs.php?transactionID=".$transactionID."&sess_id=".session_id()."&key=".$key."&card_number=".'XYZ', '', 'SSL'),
		"mode"=>$this->ebsmode,
		"payment_mode"=>'',
		"name"=>$this->title,
		"address"=>session_id(),
		"city"=>$order->customer['city'],
		"postal_code"=>$order->customer['postcode'],
		"country"=>$order->customer['country'],
		"email"=>$order->customer['email_address'],
		"phone"=>$order->customer['telephone']
		
		);
		
		$hashData = $this-> ebssecretkey;
		ksort($data_array);
		foreach ($data_array as $key => $value)
		{
			if (strlen($value) > 0) 
			{
				$hashData .= '|'.$value;
			}
		}
		
if (strlen($hashData) > 0) {
	$secure_hash = strtoupper(hash("sha512",$hashData));//for SHA512
	//$secure_hash = strtoupper(hash("sha1",$hashData));//for SHA1
	//$secure_hash = strtoupper(md5($hashData));//for MD5
}

$data_array['secure_hash'] = $secure_hash; 
$process_button_string ='';
	foreach ($data_array as $key => $value)
		{
			$process_button_string.= tep_draw_hidden_field($key, $value);
		}		
		
								   

        return $process_button_string;
    }
    public function get_CurrentCurrency()
    {
        $my_currency = MODULE_PAYMENT_EBS_CURRENCY;
        $base_currency = strtoupper($GLOBALS['A2B']->config['global']['base_currency']);
        if ($my_currency =='Selected Currency' && in_array($base_currency, $this->ebspayment_allowed_currencies) ) {
            $my_currency = $base_currency;
        } elseif (!in_array($my_currency, $this->ebspayment_allowed_currencies)) {
            $my_currency = 'USD';
        }

        return $my_currency;
    }
    public function before_process()
    {
        return false;
    }

    public function get_OrderStatus()
    {
        if ($_POST['payment_status']=="") {
            return -2;
        }
        switch ($_POST['payment_status']) {
            case "Failed":
                return -2;
            break;
            case "Denied":
                return -1;
            break;
            case "Pending":
                return -0;
            break;
            case "In-Progress":
                return 1;
            break;
            case "Completed":
                return 2;
            break;
            case "Processed":
                return 3;
            break;
            case "Refunded":
                return 4;
            break;
            default:
              return 5;
        }
    }
    public function after_process()
    {
        return false;
    }

    public function output_error()
    {
        return false;
    }

    public function keys()
    {
        return array('MODULE_PAYMENT_EBS_STATUS','EBS_PAYMENT_MODE','EBS_MODULE_ACCOUNT_ID', 'MODULE_PAYMENT_EBS_ID');
    }
}
