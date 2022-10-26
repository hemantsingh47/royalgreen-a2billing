<?php

include_once ('../confi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['username'] && $_POST['password'] && $_POST['phonenos']) 
{
    $username = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);
    $password = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);
    $PhoneNos = $_POST['phonenos'];
    
    $q = "SELECT username,uipass FROM cc_card WHERE username='$username' AND uipass = '$password'";
    $inst_table = new Table();
    $res = $inst_table->SQLExec($DBHandle, $q);
	
    if ($res) 
	{
        $q = "SELECT phone FROM cc_card WHERE phone IN ($PhoneNos)";
        $qresult = $inst_table->SQLExec($DBHandle, $q);
		//print_r($q);
		//print_r($qresult);
        $result['result']='success';
        $i=0;
        foreach($qresult as $users)
		{
            $result['phonenos'][$i]=$users['phone'];
            $i++;
        }
        echo(json_encode($result));
    }
}
else
{
    echo 'Invalid Data';
}

?>
