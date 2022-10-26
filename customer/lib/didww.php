<?php
/*
* @copyright   Copyright (C) 2005-2012 - DIDWW
* @author      Igor Gonchar <igor.g@didww.com>
* @package     Didww2Billing
*/

// class for work with DIDWW-API
define("DIDWW_API_WSDL_TEST", 'https://sandbox-api.didww.com/api/index.php?wsdl');
define("DIDWW_API_WSDL", 'https://api.didww.com/api2/?wsdl&api_ver=157'); // live mode

class DidwwApi 
{
	static $_errorCodes = 
		array(
			"100" => "Access denied",
			"150" => "Server error when validating an API client request",
			"151" => "Array has invalid data",
			"200" => "Server error when processing an API client request",
			"300" => "Type not valid",
			"301" => "Protocol not valid",
			"302" => "Unsupported format for this type",
			"303" => "PSTN prefix not supported",
			"400" => "API Order ID not found or invalid",
			"401" => "API Order ID not in valid status",
			"405" => "Transaction refused",
			"410" => "Transaction out of balance",
			"411" => "Account balance is disabled/suspened/has not enough amount for purchases",
			"430" => "Customer: Prepaid Balance disabled or not exist",
			"500" => "Region(s) not found or invalid",
			"501" => "City not found",
			"505" => "DIDs not available for this region",
			"600" => "DID Number not found or invalid",
			"601" => "DID Number not found in Reserved Pool",
			"602" => "DID Number expired. Please renew"
		);

    private $_client;
    private $_errorString;
    private $_errorCode;
    private $_authstr;


    function getClient()
    {
        return $this->_client;
    }

    function getErrorCode()
	{
    return $this->_errorCode;
}
    
    function getErrorString()
	{
    return $this->_errorString;
}
    
    function getError()
    {
    	if($this->_errorCode){
    		return "Error: (code: {$this->_errorCode}, message: {$this->_errorString})";
    	}
    	return NULL;
    }


    function setCredentials($user,$pass,$test = false)
    {
 				$this->_client = new SoapClient($test ? DIDWW_API_WSDL_TEST : DIDWW_API_WSDL);
        $this->_authstr = sha1($user . $pass  .  ($test ? 'sandbox'  : ''));
    }

    function __construct($user, $pass, $test  = false) 
	{
   $this->setCredentials($user, $pass,$test);
}

    function getAvailableMethods()
	{
			$soapFunctions = $this->_client->__getFunctions();
    for ($i = 0; $i < count($soapFunctions); $i++){
        preg_match("/[\s\S]*?(didww_[\s\S]*?)\([\s\S]*?/", $soapFunctions[$i], $matche);
        $soapFunctions[$i] = $matche[1];
    }
    return $soapFunctions;
}

    private function _handleQuery( $method,$params=array())
	{
			$params = array_merge(array('auth_string' => $this->_authstr), $params);
    try
			{
				$method='didww_'.$method;
				$result = $this->_client->__soapCall($method, $params);
    }
	catch(SoapFault $e)
	{
         $this->_errorCode = $e->faultcode;
         $this->_errorString = $e->faultstring;
         $result = null;
    }
    catch(Exception $e)
	{
        $this->_errorCode = $e->getCode();
        $this->_errorString = $e->getMessage();
        $result = null;
    }
    // If result contains error field trying to resolve error text by error code
    if(isset($result->error) && $result->error>0)
	{
        $result->error = isset(self::$_errorCodes[$result->error]) ? self::$_errorCodes[$result->error] : 'Unknown error with code : ' . $result->error ;
	}
    return $result;
}

    function getDetails()
    {
        return $this->_handleQuery('getdidwwapidetails');
    }
    
    function getRegions($iso = 0 , $city_prefix = 0, $last_request_gmt = 0)
	{
		return $this->_handleQuery(
        	'getdidwwregions',
        	array(
        		'country_iso' => $iso,
        		'city_prefix' => $city_prefix,
        		'last_request_gmt' => $last_request_gmt
        	)
        );
    }

    function  orderautorenew($user_id,$did_number,$period,$order_id)
    {
        return $this->_handleQuery(
			'orderautorenew',
			array(
				'customer_id' => $user_id,
				'did_number'=>$did_number,
				'period'=>$period,
        'uniq_hash'=> md5(gmdate("Y-m-d") . '.' . $order_id)
			)
		);
    }

    function createOrder($user_id, $iso, $city_prefix, $period, $map_data, $order_id, $city_id)
    {
		$map_data = $this->convert_map_data($map_data);
    	return $this->_handleQuery(
			'ordercreate',
			array(
				'customer_id' => $user_id,
				'country_iso_code' => $iso,
				'city_prefix' => $city_prefix,
				'period' => $period,
				'map_data' => $map_data,
				'prepaid_funds' => null,
				'uniq_hash' => md5($order_id),
				'city_id' => $city_id,
				'autorenew_enable' => 1 // true by default
			)
		);
    }

	function updateMapping($user_id, $did_number, $map_data)
	{
		$map_data = $this->convert_map_data($map_data);

		return $this->_handleQuery('updatemapping', array(
									'customer_id'=> $user_id,
									'did_number' => $did_number,
									'map_data' => $map_data
		));
	}

	private function convert_map_data($map_detail)
	{
		return array(
			'map_type' => 'URI',
			'map_proto' => 'SIP',
			'map_detail' => $map_detail,
			'map_pref_server' => 0,
			'map_itsp_id' => ''
		);
	}

    function getDidwwApiDetails()
    {
        return $this->_handleQuery('getdidwwapidetails');
    }

	function cancelOrder($user_id, $did_number)
	{
		return $this->_handleQuery('ordercancel',
									array(	'customer_id' => $user_id,
											'did_number' => $did_number
									));
	}
	
	function did_restore($customer_id, $did_number)
	{
		return $this->_handleQuery('didrestore',
									array(	'customer_id' => $user_id,
											'did_number' => $did_number
									));
	}

}

if (!function_exists('json_encode')) {
    function json_encode($data) {
        switch ($type = gettype($data)) {
            case 'NULL':
                return 'null';
            case 'boolean':
                return ($data ? 'true' : 'false');
            case 'integer':
            case 'double':
            case 'float':
                return $data;
            case 'string':
                return '"' . addslashes($data) . '"';
            case 'object':
                $data = get_object_vars($data);
            case 'array':
                $output_index_count = 0;
                $output_indexed = array();
                $output_associative = array();
                foreach ($data as $key => $value) {
                    $output_indexed[] = json_encode($value);
                    $output_associative[] = json_encode($key) . ':' . json_encode($value);
                    if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                        $output_index_count = NULL;
                    }
                }
                if ($output_index_count !== NULL) {
                    return '[' . implode(',', $output_indexed) . ']';
                } else {
                    return '{' . implode(',', $output_associative) . '}';
                }
            default:
                return ''; // Not supported
        }
    }
}
