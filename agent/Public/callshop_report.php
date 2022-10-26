 <?php                
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php'; 
$DBHandle  = DbConnect();
$instance_table = new Table();

// #### HEADER SECTION
 if(isset($_REQUEST['custid']) && isset($_REQUEST['callid']) && isset($_REQUEST['custref']))
 {
     $customer_id=$_REQUEST['custid'];
     $callshop_id=$_REQUEST['callid'];
     $cust_username=$_REQUEST['custref'];
 }
 else
 {
     header("Location: billing_entity_callshop.php");
     die;
 }
 
 $QUERY ="SELECT * FROM `callshop_cc_call` WHERE `card_id`='".$customer_id."'";
 $callshop = $instance_table -> SQLExec($DBHandle, $QUERY);
//DISPLAY HEADER--
$smarty->display('main.tpl');
?>
<script type="text/javascript">
function delete_booth(delval)
         {
            var postData="custid="+delval+'&'+$.param({ 'ask-delone': 'delval' });
             if (confirm("Are You Sure? Process cannot be Undone!")== true) {
                /*alert(postData);*/
                $.ajax({
                type: "POST",
                url: "billing_callshop_delete.php",
                data:postData,
                success: function(response) {
                    
                    alert(response);
                    window.location.href="billing_entity_callshop.php";
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                    
                }

                });            
                   
            } else {
                alert("You pressed Cancel!") ;
            }
            
       }        
</script>
<h2><?php echo gettext("CallShop Booth Entries: ").$cust_username;?></h2>
<div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-overflow-container uk-margin-bottom">
                        <table class="table table-bordered table-striped" id="ts_issues">
                            <thead>
                                <tr>
                                    <th class="uk-text-center"><?php echo gettext("Sr. No.");?></th>
                                    <th><?php echo gettext("Start Time");?></th>
                                    <th><?php echo gettext("Stop Time");?></th>
                                    <th><?php echo gettext("Duration(sec.)");?></th>
                                    <th><?php echo gettext("Destination");?></th>
                                    <th><?php echo gettext("Charge");?></th>
                                    
                                </tr>
                            </thead>
                           
                             <tbody>
                             <?php 
                             
                                for($i=0;$i<count($callshop);$i++)
                                {
                                  ?>
                                    
                                <tr>
                                    <td class="uk-text-center"><span class="uk-text-small uk-text-muted uk-text-nowrap">CALL-<?php echo ($i+1); ?></span></td>
                                    <td><?php echo $callshop[$i]['starttime']; ?></td>
                                    <td><?php echo $callshop[$i]['stoptime']; ?></td>
                                    <td><span ><?php echo $callshop[$i]['sessiontime']; ?></span></td>
                                    <td class="uk-text-small"><?php echo $callshop[$i]['calledstation']; ?></td>
                                    <td class="uk-text-small"><?php echo -0+$callshop[$i]['sessionbill']; ?></td>
                                    
                                </tr>
                              <?php   
                                       
                                }
                            ?> 
                            <tr>
                                <td colspan="6" style="text-align: right;">
                                <a  name="download_reciept" class="btn btn-success" href="callshop_invoice.php?custid=<?php echo $customer_id?>&callid=<?php echo $callshop_id?>&custref=<?php echo $cust_username?>" target="_blank" ><?php echo ("Download Reciept")?></a>
                                <a  name="clear_reciept" class="btn btn-success" href="#" onclick="delete_booth('<?php echo $customer_id?>')" ><?php echo ("Clear Booth")?></a>
                                </td>
                            </tr> 
                             </tbody>
                        </table>
                    </div>
               </div>
            </div>
 </div>
<?php

//$query = "SELECT logo_data,logo_mime_type FROM cc_admin_maincss WHERE user_agent='$admin'"; 
 $query = "SELECT logo_data,mime_type FROM cc_image WHERE agent_id=". $_SESSION['agent_id']." ";
 
if(isset($_POST["submit"]))
{  echo "</br>"; 
echo "<div id='up' style='position: relative;
    top: -280px;
    left: 400px;'>";
echo " &nbsp &nbsp <a href='$invref.pdf'>Download your Receipt</a>";

 echo "</br>";
  echo "</br>";
echo "<a href=\"clearcallbooth.php?id={$id}&ref={$ref}&charge={$finalchar}\" target='_blank'>Clear Booth</a>";
echo "</div>";
}
?>


 <?php 
    $smarty->display('footer.tpl'); 
 
?>
 
   
