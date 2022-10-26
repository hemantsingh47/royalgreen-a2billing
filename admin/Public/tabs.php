<?php
 
include '../lib/admin.defines.php';
include_once '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
if (!has_rights(ACX_DASHBOARD)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}  
 
$smarty->display('main.tpl');
 
 $date = date('Y-m-d');
 //print_r($date); 
 //die();
// SELECT *  FROM `cc_card` WHERE `creationdate` LIKE '%2017-02-23%' '%".$country."%'    
$inst_table = new Table();
$query_card = "SELECT count(username) as username from cc_card";
$total_customer = $inst_table -> SQLExec($DBHandle, $query_card); 
//print_r($total_customer); 

$query_card_new = "SELECT count(username) as  username  FROM `cc_card` WHERE `creationdate` LIKE  '%".$date."%'";  
//SELECT count(username) as  username  FROM `cc_card` WHERE `creationdate` LIKE '%2017-12-01%'
$new_customer = $inst_table -> SQLExec($DBHandle, $query_card_new); 
//print_r($new_customer); 
//die();
$query_payment = "SELECT count(id) as id from `cc_payments`";
$total_payment = $inst_table -> SQLExec($DBHandle, $query_payment); 
//print_r($total_payment); 
 
$query_voucher = "SELECT count(voucher) as  voucher  FROM `cc_voucher` WHERE `used` = 1;";
$total_voucher_used = $inst_table -> SQLExec($DBHandle, $query_voucher); 
//print_r($total_customer);  
 
$left = array();
$center = array();
$right = array();
function put_dislay($position, $title, $links)
{
    global $left;
    global $center;
    global $right;
    if ($position=="LEFT") {
        $idx = count($left);
        $left[$idx] = array();
        $left[$idx]["title"] = $title;
        $left[$idx]["links"] = $links;
    } elseif ($position=="CENTER") {
        $idx = count($center);
        $center[$idx] = array();
        $center[$idx]["title"] = $title;
        $center[$idx]["links"] = $links;
    } elseif ($position=="RIGHT") {
        $idx = count($right);
        $right[$idx] = array();
        $right[$idx]["title"] = $title;
        $right[$idx]["links"] = $links;
    }
}
if ( !empty($A2B->config["dashboard"]["customer_info_enabled"]) && $A2B->config["dashboard"]["customer_info_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["customer_info_enabled"],gettext("ACCOUNTS INFO"),array("./modules/customers_numbers.php","./modules/customers_lastmonth.php"));
}
if ( !empty($A2B->config["dashboard"]["refill_info_enabled"]) && $A2B->config["dashboard"]["refill_info_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["refill_info_enabled"],gettext("REFILLS INFO"),array("./modules/refills_lastmonth.php"));
}

 

if ( !empty($A2B->config["dashboard"]["call_info_enabled"]) && $A2B->config["dashboard"]["call_info_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["call_info_enabled"],gettext("CALLS INFO TODAY"),array("./modules/calls_counts.php","./modules/calls_lastmonth.php"));
    
} 

?>
<script type="text/javascript">
 $(document).ready(function(){
  setInterval(function() {
      $.ajax({
                type: "GET",
                url: "live-call-total_data_live.php",
                data:"section=6&totalcall=totalcall",
                success: function(response) {
                   //alert(response);
                    $("#mycall").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }                    
        });
  
   }, 1000);
 });
</script>            
<script type="text/javascript">
$(function () {
    // we use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [], totalPoints = 300;
    calls = 0;
    function getRandomData() {
        if (data.length > 0)
            data = data.slice(1);
            
            $.ajax({
                type: "GET",
               url: "live-call-total_data_live.php",
                data:"section=6&totalcall=all",
                success: function(response) {
                   
                    calls = parseInt(response);
                    //alert(calls);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    //calls = 0;
                    //alert("failed"+errorThrown+textStatus);
                   
                }

        });
        //alert(calls);
        // do a random walk
        while (data.length < totalPoints) {
            var prev = data.length > 0 ? data[data.length - 1] : 5;
            //var y = prev + Math.random() * 8 - 5;
            var y =  (calls);
            $('#mydiv').html(y+" : "+calls);
            if (y < 0)
                y = 0;
            if (y > 100)
                y = 100;
            data.push(y);
        }

        // zip the generated y values with the x values
        var res = [];
        for (var i = 0; i < data.length; ++i)
            res.push([i, data[i]])
        return res;
    }

    // setup control widget
    var updateInterval = 30;
    $("#updateInterval").val(updateInterval).change(function () {
        var v = $(this).val();
        if (v && !isNaN(+v)) {
            updateInterval = +v;
            if (updateInterval < 1)
                updateInterval = 1;
            if (updateInterval > 2000)
                updateInterval = 2000;
            $(this).val("" + updateInterval);
        }
    });

   

    function update() {
        
         // setup plot
          numbery = 5;
         if(calls >1)
         {
             numbery = calls*5;
         }
    var options = {
        series: { shadowSize: 3 }, // drawing is faster without shadows
        yaxis: { min: 0, max: (numbery) },
        xaxis: { show: true }
    };
    
    var plot = $.plot($("#placeholder2"), [ getRandomData() ], options);
        plot.setData([ getRandomData() ]);
        // since the axes don't change, we don't need to call plot.setupGrid()
        plot.draw();
        
        setTimeout(update, updateInterval);
    }

    update();
});
</script> 
<div class="row">
	<div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
		<div class="card">
			<?php for ($i_left=0;$i_left<count($left);$i_left++) 
				{ 
			?>
			 
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label" style="color: #000; font-weight: bold;"><span class="icon"></span>
						<h3 class="kt-portlet__head-title"><?php echo $left[$i_left]["title"]; ?></h3>
					</div>
				</div>
				<?php for ($j_left=0;$j_left<count($left[$i_left]["links"]);$j_left++) 
				{
					include ($left[$i_left]["links"][$j_left]);
				?>
				<br/>
				<?php 
				} 
				?>
			
			<?php } ?>
		</div>
	</div>
			  
	<div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
		<div class="card">
			<?php for ($i_center=0;$i_center<count($center);$i_center++) 
				{ 
			?>
				
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label" style="color: #000; font-weight: bold;"><span class="icon"></span>
						<h3 class="kt-portlet__head-title"><?php echo $center[$i_center]["title"]; ?></h3>
					</div>
				</div>
				<?php 
					for ($j_center=0;$j_center<count($center[$i_center]["links"]);$j_center++) 
					{
						include ($center[$i_center]["links"][$j_center]);
				?>
				<br/>
				<?php 
					} 
				?>
			
			<?php } ?>
		</div>
	</div>
	<div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
		<div class="card">
			<?php for ($i_right=0;$i_right<count($right);$i_right++) 
				{ 
			?>
				
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label" style="color: #000; font-weight: bold;"><span class="icon"></span>
						<h3 class="kt-portlet__head-title"><?php echo $right[$i_right]["title"]; ?></h3>
					</div>
				</div>
				<?php 
					for ($j_right=0;$j_right<count($right[$i_right]["links"]);$j_right++)
					{
						include ($right[$i_right]["links"][$j_right]);
				?>
				<br/>
				<?php 
					} 
				?>
			
			<?php } ?>
		</div>
	</div>			
</div>					
				
	
