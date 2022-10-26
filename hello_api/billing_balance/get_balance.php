<?php
include('../confi.php');
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'] && $_POST['cust_pass'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);
        $password = Security::decrypt(sanitize_data($_POST['cust_pass']), KEY_SECURE);
        $QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, " .
        "freetimetocall, label, packagetype, billingtype, startday, id_cc_package_offer, cc_card.id, currency,cc_card.useralias,UNIX_TIMESTAMP(cc_card.creationdate) creationdate  FROM cc_card " .
        "LEFT JOIN cc_tariffgroup ON cc_tariffgroup.id=cc_card.tariff LEFT JOIN cc_package_offer ON cc_package_offer.id=cc_tariffgroup.id_cc_package_offer " .
        "LEFT JOIN cc_card_group ON cc_card_group.id=cc_card.id_group WHERE username = '" .$username. "' AND uipass = '" . $password . "'";
        $customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);
		
		$customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);
        $call_prefix = $inst_table -> SQLExec($DBHandle, "SELECT config_value FROM cc_config WHERE config_key='dynamic_call_prefix' AND config_group_title='global' ");   
        //echo $customer_res[0]['currency'];
		
        //echo $customer_res[0]['currency'];
        $currencies_list = get_currencies();
        //print_r($currencies_list[strtoupper($customer_res['currency'])][2]);die;
        $two_currency = false;
        if (!isset ($currencies_list[strtoupper($customer_res[0]['currency'])][2]) || !is_numeric($currencies_list[strtoupper($customer_res[0]['currency'])][2])) 
        {
            $mycur = 1;
        } 
        else 
        {
            $mycur = $currencies_list[strtoupper($customer_res[0]['currency'])][2];
            $display_currency = strtoupper($customer_res[0]['currency']);
            if (strtoupper($customer_res[0]['currency']) != strtoupper(BASE_CURRENCY))
            $two_currency = true;
        }
        $credit_cur = $customer_res[0]['credit'] / $mycur;
        $credit_cur = round($credit_cur, 3);
        $json = array('credit'=>"".$credit_cur."",'currency'=>strtoupper(BASE_CURRENCY),"callprefix"=>$call_prefix[0]['config_value']);
		 
        echo json_encode($json);
        die;
}
?>