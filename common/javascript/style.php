
<?php

 header("Content-Type: text/css; charset=utf-8");
include_once("config.php");
$result = mysqli_query($mysqli, "SELECT * FROM users ORDER BY id =1"); 
while($res = mysqli_fetch_array($result)) { 		
		
	 $newh = $res['textColor'];
	 $backgroundColor = 'lightgrey';

}



?>
 
/** CSS begins **/
body{
    background-color: <?= $backgroundColor; ?>;
    color: <?= $newh; ?>;
}
