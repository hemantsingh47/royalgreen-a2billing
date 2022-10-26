<?php
include_once('../confi.php');
$inst_table = new Table();
 $call_prefix = $inst_table -> SQLExec($DBHandle, "SELECT config_value FROM cc_config WHERE config_key='dynamic_call_prefix' AND config_group_title='global' ");
 
  
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['rates'] && $_POST['username'])
{
    $rates = Security::decrypt(sanitize_data($_POST['rates']), KEY_SECURE);
    $username = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);
    //$rates = sanitize_data($_POST['rates']);
    $rates = str_replace(' ', '-', $rates); // Replaces all spaces with hyphens.
    $rates = preg_replace('/[^A-Za-z0-9\-]/', '', $rates); // Removes special chars.
    $rates = ltrim($rates, '0');
    
     // for xountry code
     $cust_ccode = "SELECT * from cc_card where username = '".$username."'";
     $cust_ccode_value = $inst_table->SQLExec($DBHandle,$cust_ccode);  
   
     $cust_currency = $inst_table->SQLExec($DBHandle , "SELECT currencycode from cc_country where countryprefix = '".$cust_ccode_value[0]['ccode']."'"); 
     $cust_curr_code = $cust_currency[0]['currencycode'];   
     // for live convert currency value 
     
    if($rates == '0' || $rates =='' || empty($rates))
    {
        $result = array('result'=>'failure',"rates"=>'0',"dialprefix"=>'Not found',"callprefix"=>$call_prefix[0]['config_value']);    
    }
    else
    {
        $max_len_prefix = min(strlen($rates), 15); // don't match more than 15 digits (the most I have on my side is 8 digit prefixes)
        $prefixclause = '(';
        while ($max_len_prefix > 0) 
        {
            $prefixclause .= "dialprefix='" . substr($rates, 0, $max_len_prefix) . "' OR ";
            $max_len_prefix--;
        }
        $prefixclause .= "dialprefix='defaultprefix')";

        // match Asterisk/POSIX regex prefixes,  rewrite the Asterisk '_XZN.' characters to
        // POSIX equivalents, and test each of them against the dialed number
        $prefixclause .= " OR (dialprefix LIKE '&_%' ESCAPE '&' AND '$rates' ";
        $prefixclause .= "REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', dialprefix, '$'), ";
        $prefixclause .= "'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', ''))";
        $query_rates = "SELECT dialprefix,rateinitial,cc_prefix.destination FROM cc_ratecard
            LEFT JOIN cc_prefix ON (cc_ratecard.dialprefix=cc_prefix.prefix)
            WHERE ".$prefixclause." ORDER BY dialprefix DESC LIMIT 1";
        $result_rates = $inst_table -> SQLExec($DBHandle, $query_rates);
        if($result_rates)
        {    
          $ratevalue = (array)$result_rates;
           $actual_rates = round($ratevalue[0]['rateinitial'],4);
             
            $result = array( 'result'=>'success', "rates"=>"".$actual_rates."","dialprefix"=>$ratevalue[0]['dialprefix'],"callprefix"=>$call_prefix[0]['config_value']);
        }
        else
        {
            $result = array('result'=>'failure',"rates"=>'0',"dialprefix"=>'Not found',"callprefix"=>$call_prefix[0]['config_value']);
        }    
    }
    
    echo json_encode($result);
    die;
    
}
else
{
    $result = array('result'=>'failure',"rates"=>'0',"dialprefix"=>'Not found',"callprefix"=>$call_prefix[0]['config_value']);
    echo json_encode($result);
    die;
}
?>