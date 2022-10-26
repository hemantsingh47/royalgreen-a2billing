<?php
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

$DBHandle  = DbConnect();
$inst_table  = new Table();
//fetching data in descending order (lastest entry first)
$result = "SELECT * FROM users1 ORDER BY id"; // mysql_query is deprecated
$result_info  = $inst_table -> SQLExec($DBHandle, $result);  
?>

<html>
<head>	
	<title>Homepage</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
function AjaxFunction()
{
var httpxml;
try
  {
  // Firefox, Opera 8.0+, Safari
  httpxml=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
		  try
   			 		{
   				 httpxml=new ActiveXObject("Msxml2.XMLHTTP");
    				}
  			catch (e)
    				{
    			try
      		{
      		httpxml=new ActiveXObject("Microsoft.XMLHTTP");
     		 }
    			catch (e)
      		{
      		alert("Your browser does not support AJAX!");
      		return false;
      		}
    		}
  }
function stateck() 
    {
    if(httpxml.readyState==4)
      {
document.getElementById("msg").innerHTML=httpxml.responseText;
document.getElementById("msg").style.background='#f1f1f1';
      }
    }
	var url="new_monthly_project_report.php";
url=url+"?sid="+Math.random();
httpxml.onreadystatechange=stateck;
httpxml.open("GET",url,true);
httpxml.send(null);
tt=timer_function();
  }

///////////////////////////
function timer_function(){
var refresh=1000; 
mytime=setTimeout('AjaxFunction();',refresh)
}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
$('#timer').countdown({
    until: '<?php echo date("23:00:01"); ?>' 
});
</script>
</head>
<style>
p.exh {
     margin-top: -13px;
}
p.ex1 {
     margin-top: -58px;
}
p.ex {
	padding-top: 1px;
	 
}
a:link {
    color:#000000;
}
div.a {
    width: 164%;
    border: 0px solid black;
}
div.c {
    text-align: center;
} 
ul
{
    font-family: Arial, Verdana;
    font-size: 14px;
    margin: 0;
    padding: 0;
    list-style: none;
}

ul li
{
    display: block;
    position: relative;
    float: left;
}

li ul
{
    display: none;
}

ul li a 
{
    display: block;
    text-decoration: none;
    color: #ffffff;
    border-top: 1px solid #ffffff;
    padding: 5px 15px 5px 15px;
    background: #2C5463;
    margin-left: 1px;
    white-space: nowrap;
}

ul li a:hover 
{
    background: #orange;
}
li:hover ul 
{
    display: block;
    position: absolute;
}

li:hover li
{
    float: none;
    font-size: 11px;
}

li:hover a 
{
    background: #617F8A;
}

li:hover li a:hover 
{
    background: #95A9B1;
}
.div 
{ background-color: green !important }
tr:h
{ background-color: green !important }
tr:i
{ background-color: red !important }
</style>
<body>

<table width='100%' border="0" cellspacing="10" cellpadding="10">
	<tr>
	<td><img src="logo.png" alt="Adore" width="188" height="70"></td>
	<td><h2 style="color:#000000;"><strong>Monthly Project Report</strong></h2></td>
	<!--<td><a href="add.html"><h3 align ="right" style="color:#4d0000;">Add New Projects</h3></a>
	<a href="new_login.php"><h3 align ="right" style="color:#4d0000;">Update Info here</h3></a>
	<a href="https://billing.adoreinfotech.co.in/admin/Public/dashboard.php"><h3 align ="right" style="color:#4d0000;">Dashboard</h3></a>
	</td>-->
	
	
	


	
</tr></table>
	<div align="center">
<table width='100%' border="1" cellspacing="30" cellpadding="0" style="text-align:center;">
	<tr bgcolor=''>
		<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Sr.No.</b></h3></p></th><div>
	    <div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Project Name</b></h3></p></th></div>
		<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Project Description</b></h3></p></th></div>
		<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Project Status</b></h3></p></th></div>
	<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Notes</b></h3></p></th></div>
	<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Starting Date</b></h3></p></th></div>
	<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Ending Date</b></h3></p></th></div>
	<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Time Remaining</b></h3></p></th></div>
	<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Project Duration</b></h3></p></th></div>
	<!--<div><th><p class="ex"><h3 style="color:#000000;" align="center"><b>Update Info</b></h3></p></th></div>-->
	<?php 

	$array = array();
foreach($result_info as $result)
      {
       $id =   $result['id'];
       $project_name =   $result['project_name'];
	   $project_description = $result['project_description'];
       $project_status =   $result['project_status'];
       $starting_date =   $result['starting_date'];
       $ending_date =   $result['ending_date'];  
         $notes = $result['notes']; 
       $dteStart = new DateTime($starting_date); 
       $dteEnd   = new DateTime($ending_date);
        
       $diff = date_diff($dteStart,$dteEnd);
      $remain_days = $diff->format("%R%a days");
      
      $remain_time = $diff->format("%H:%I:%S"); 
	  
	  
$date1 = new DateTime("now");
$date2 = new DateTime($ending_date);
$diff = date_diff($date1,$date2);
   $counter_remain_days = $diff->format("%R%a days");
   $counter_remain_time = $diff->format("%H:%I:%S"); 

/*var_dump($date1 == $date2);
var_dump($date1 < $date2);
var_dump($date1 > $date2);
*/
	  
	  
	    
      $array_value =  array_push($array, array('id' =>$id,'project_name' =>$project_name,'project_description' =>$project_description, 'project_status' => $project_status, 'notes' => $notes,'start_date' =>$starting_date,'end_date' =>$ending_date,'remaining_days' => $counter_remain_days,'remaining_time' => $counter_remain_time,'remain_days' => $remain_days,'remain_time' => $remain_time));     
      }
      
	  
    // print_r($array); 
	 
	//echo "<pre>";
   // echo "Sr.No.\tProject Name\tProject Description\tProject Status\tUpdate Project Info\tStarting Date\tEnding Date\tTime Remaining\tTotal Days";
    foreach ( $array as $var ) {
   // echo "\n", $var['id'],"\t", $var['project_name'],"\t", $var['project_description'],"\t", $var['project_status'],"\t", $var['start_date'],"\t", $var['end_date'],"\t", $var['remaining_time'],"\t", $var['remaining_days'],"\t";
    
	
	$id=$var[id];
switch(true) {
    case ($var[id]=='1'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='1') {
            $bgColor = ' style="background-color:#FF8C00 !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			 echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			
		    echo "<td>";
			
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\">Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	  case ($var[id]=='2'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='2') {
            $bgColor = ' style="background-color:#FF8C00 !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			
			echo "</tr>";
     break;
	 
	 
	 
	 
	  case ($var[id]=='3'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='3') {
            $bgColor = ' style="background-color:#4d9900 !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	  case ($var[id]=='4'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='4') {
            $bgColor = ' style="background-color:#4d9900 !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			  echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	 
	 
	 
	 case ($var[id]=='5'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='5') {
            $bgColor = ' style="background-color:#FF8C00 !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
		/*	echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
            
			
			
			case ($var[id]=='6'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='6') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
		/*	echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	 case ($var[id]=='7'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='7') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	 case ($var[id]=='8'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='8') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	 
	 
	 
	 case ($var[id]=='9'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='9') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
		/*	echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	 
	 
	 
	 case ($var[id]=='10'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='10') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			  echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
		/*	echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
            
           case ($var[id]=='11'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='11') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";*/
			
			echo "</tr>";
     break; 
            
        case ($var[id]=='12'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='12') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			  echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;    
	 
	 case ($var[id]=='13'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='13') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
		/*	echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			echo "</tr>";
     break;
	   
	   
	   
	   case ($var[id]=='14'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='14') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
		/*	echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
	 
	 
	 case ($var[id]=='15'):
	       echo "<tr id= 'h'>";
		    if ($var[id]=='15') {
            $bgColor = ' style="background-color:lightgreen !important;" ';
              }
            echo "<td>";
            echo $var['id'];
            echo "</td>";

            echo "<td>";
            echo $var['project_name'];
            echo "</td>";
			
			echo "<td>";
            echo $var['project_description'];
            echo "</td>";
			
			echo "<td $bgColor>";
            echo $var['project_status'];
            echo "</td>";
			
			
			 echo "<td>";
			 echo $var['notes'];;
           // echo $var['start_date'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a> | <a href=\"delete.php?id=$var[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>";
            echo "</td>";
			*/
			
			 echo "<td>";
            echo $var['start_date'];
            echo "</td>";
			
			 echo "<td>";
            echo $var['end_date'];
            echo "</td>";
			
			echo "<td>";
			echo $var['remaining_days'];
			echo "\n";
            echo $var['remaining_time'];
            echo "</td>";
			
			 echo "<td>";
			echo $var['remain_days'];
			echo "\n";
            echo $var['remain_time'];
            echo "</td>";
			
			/*echo "<td>";
            echo "<a href=\"edit.php?id=$var[id]\" >Edit</a>";
            echo "</td>";
			*/
			
			echo "</tr>";
     break;
}
}
?>
	</table>
	</div>
	
</body>
</html>
