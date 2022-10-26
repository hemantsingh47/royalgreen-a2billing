<?php

 

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

//getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'lst_time','trunks'));
       $smarty->display('main.tpl'); 
       $smarty->display('header.tpl');
       
 $DBHandle  = DbConnect();
$instance_table = new Table();

//     Initialization of variables    ///////////////////////////////
  

// #### HEADER SECTION

 $QUERY ="SELECT * FROM cc_credit";
 $result =mysql_query($QUERY);
   
?>

<?php
/*--------------------------------------------------------------------------------------------
|    @desc:        pagination index.php
|    @author:      Mohit Gupta
|    @url:         http://billing.adoreinfotech.co.in/Pagination
|    @date:        17 July 2015
|    @email        mohit.kumar@adoreinfotech.com
|  
---------------------------------------------------------------------------------------------*/
include ('paginate.php'); //include of paginat page

$show_page = 0;
$per_page = 15;         // number of results to show per page
$result = mysql_query("SELECT * FROM cc_credit ORDER BY id");
$total_results = mysql_num_rows($result);
$total_pages = ceil($total_results / $per_page);//total pages we going to have

//-------------if page is setcheck------------------//
if (isset($_GET['page'])) {
    $show_page = $_GET['page'];             //it will telles the current page
    if ($show_page > 0 && $show_page <= $total_pages) {
        $start = ($show_page - 1) * $per_page;
        $end = $start + $per_page;
    } else {
        // error - show first set of results
        $start = 0;              
        $end = $per_page;
    }
} else {
    // if page isn't set, show first set of results
    $start = 0;
    $end = $per_page;
}
// display pagination
$page = intval($_GET['page']);

$tpages=$total_pages;
if ($page <= 0)
    $page = 1;
?>

   
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD>
    <link rel="shortcut icon" href="templates/default/images/adore.ico">
    <title>..:: Billing Solution: CallingCard, CallBack & VOIP Billing Solution ::..</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link href="templates/default/css/main.php" rel="stylesheet" type="text/css">
    <link href="templates/default/css/newcss.css" rel="stylesheet" type="text/css">
    <link href="templates/default/css/invoice.css" rel="stylesheet" type="text/css">
    <link href="templates/default/css/receipt.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">    
        var IMAGE_PATH = "templates/default/images/";
    </script>
    <script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>
    <script type="text/javascript" src="./javascript/jquery/jquery.debug.js"></script>
    <script type="text/javascript" src="./javascript/jquery/ilogger.js"></script>
    <script type="text/javascript" src="./javascript/jquery/handler_jquery.js"></script>
    <script language="javascript" type="text/javascript" src="./javascript/misc.js"></script>
    
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
<form name="theForm" action="<?php $_SERVER['PHP_SELF']?>" method="post"  > 
<div id='main-container'><h2>Balance Transfer Reports</h2> </div>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" type="text/css" href="csspagination/style.css" />
    <style type="text/css">
.logo
{
    text-align: center;
}
.container{

}
</style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="logo">
                
            </div>
</br></br>
        </div>
        <div class="row">
            <div class="span6 offset3">
                <div class="mini-layout">
                 <?php
                    echo "</br>";
                    // display data in table
                    echo "<table class='table table-bordered'>";
                    echo "<thead><tr><th>Id</th><th>Transfer From</th> <th>Transfer To</th><th>Amount</th><th>Date</th><th>Time</th></tr></thead>";
                    // loop through results of database query, displaying them in the table 
                    for ($i = $start; $i < $end; $i++) {
                        // make sure that PHP doesn't try to show results that don't exist
                        if ($i == $total_results) {
                            break;
                        }
                        //$start
                        // echo out the contents of each row into a table
                        
                        
                        echo "<tr " . $cls . ">";
                       
                        echo '<td>' .$i. '</td>';
                        //echo '<td>' . mysql_result($result, $i, 'id') . '</td>';
                        echo '<td>' . mysql_result($result, $i, 'transferFrom') . '</td>';
                        echo '<td>' . mysql_result($result, $i, 'transferTo') . '</td>';
                        echo '<td>' . mysql_result($result, $i, 'Amount') . '</td>';
                        echo '<td>' . mysql_result($result, $i, 'date') . '</td>';
                        echo '<td>' . mysql_result($result, $i, 'time') . '</td>';
    
                        echo "</tr>";
                    }
                    

                    // close table>
                echo "</table>";
            $cls=0;
                    $reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages;
                    echo '<div class="pagination"><ul>';
                    if ($total_pages > 1) {
                        echo paginate($reload, $show_page, $total_pages);
                    }
                    echo "</ul></div>";
                 echo "<b>-List- $total_results Rerords\n</b>";
            // pagination
            ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php

$smarty->display( 'footer.tpl'); 
   
