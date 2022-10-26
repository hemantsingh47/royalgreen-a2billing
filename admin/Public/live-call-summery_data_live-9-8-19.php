<?php

include_once ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include_once ("../lib/regular_express.inc");
include_once ("../lib/phpagi/phpagi-asmanager.php");
include ("../lib/admin.smarty.php");
$DBHandle  = DbConnect();
$instance_table = new Table();
//QUERY FOR GETTING MAX LENGTH OF PREFIX FROM cc_country table
$QUERY ="SELECT MAX( LENGTH( countryprefix ) ) FROM  `cc_country`";
$prefix_max_length = $instance_table -> SQLExec($DBHandle, $QUERY);
$astman = new AGI_AsteriskManager();
$res = $astman->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);
//$value= array("Active Channel(s)" => "core show channels concise");

//buildAsteriskInfo();

function convertActiveChannel($sipChannel, $channel = NULL){
    if($channel == NULL){
        print_r($sipChannel);
        exit();
        $sipChannel_arr = explode(' ', $sipChannel[1]);
        if($sipChannel_arr[0] == 0){
            return 0;
        }else{
            return count($sipChannel_arr[0]);
        }
    }elseif($channel == 'IAX2'){
        $iaxChannel = $sipChannel;
    }    
}

function getActiveChannel($channel_arr, $channelType = NULL){
    if(count($channel_arr) > 1){
        if($channelType == NULL || $channelType == 'SIP'){
            $sipChannel_arr = $channel_arr;
            $sipChannel_arrCount = count($sipChannel_arr);
            $sipChannel_string = $sipChannel_arr[$sipChannel_arrCount - 2];
            $sipChannel = explode(' ', $sipChannel_string);
            return $sipChannel[0];
        }elseif($channelType == 'IAX2'){
            $iax2Channel_arr = $channel_arr;
            $iax2Channel_arrCount = count($iax2Channel_arr);
            $iax2Channel_string = $iax2Channel_arr[$iax2Channel_arrCount - 2];
            $iax2Channel = explode(' ', $iax2Channel_string);
            return $iax2Channel[0];
        }     
    }
}

function getRegistration($registration, $channelType = 'SIP'){
    if($channelType == NULL || $channelType == 'SIP'){
        $sipRegistration_arr = $registration;
        $sipRegistration_count = count($sipRegistration_arr);
        return $sipRegistration_count-3;
        
    }elseif($channelType == 'IAX2'){
        $iax2Registration_arr = $registration;
        $iax2Registration_count = count($iax2Registration_arr);
        return $iax2Registration_count-3;
    }      
}

function getPeer($peer, $channelType = NULL){
    global $astver_major, $astver_minor;
    global $astver;
    if(count($peer) > 1){    
        if($channelType == NULL || $channelType == 'SIP'){
            
           
            $sipPeer = $peer;
            $sipPeer_count = count($sipPeer);
              
            $sipPeerInfo_arr['sipPeer_count'] = $sipPeer_count -3;
            
            $sipPeerInfo_string = $sipPeer[$sipPeer_count -2];
            $sipPeerInfo_arr2 = explode('[',$sipPeerInfo_string);
            $sipPeerInfo_arr3 = explode(' ',$sipPeerInfo_arr2[1]);
            if (version_compare($astver, '1.4', 'ge')) { 
                $sipPeerInfo_arr['online'] = $sipPeerInfo_arr3[1] + $sipPeerInfo_arr3[6];
                $sipPeerInfo_arr['offline'] = $sipPeerInfo_arr3[3] + $sipPeerInfo_arr3[8];
            }else{
                $sipPeerInfo_arr['online'] = $sipPeerInfo_arr3[0];
                $sipPeerInfo_arr['offline'] = $sipPeerInfo_arr3[3];
            }
            return $sipPeerInfo_arr;
            
        }elseif($channelType == 'IAX2'){
            $iax2Peer = $peer;
            $iax2Peer_count = count($iax2Peer);
            $iax2PeerInfo_arr['iax2Peer_count'] = $iax2Peer_count -3;
            $iax2PeerInfo_string = $iax2Peer[$iax2Peer_count -2];
            $iax2PeerInfo_arr2 = explode('[',$iax2PeerInfo_string);
            $iax2PeerInfo_arr3 = explode(' ',$iax2PeerInfo_arr2[1]);
            $iax2PeerInfo_arr['online'] = $iax2PeerInfo_arr3[0];
            $iax2PeerInfo_arr['offline'] = $iax2PeerInfo_arr3[2];
            $iax2PeerInfo_arr['unmonitored'] = $iax2PeerInfo_arr3[4];
            return $iax2PeerInfo_arr;
        } 
    }
}

function buildAsteriskInfo(){
    global $astman;
    global $astver;
    
    $arr = array(
        "Uptime" => "core show uptime",
        "Active SIP Channel(s)" => "sip show channels concise",
        "Active IAX2 Channel(s)" => "iax2 show channels",
        "Sip Registry" => "sip show registry",
        "IAX2 Registry" => "iax2 show registry",
        "Sip Peers" => "sip show peers",    
        "IAX2 Peers" => "iax2 show peers",     
    );
    
    if (ASTERISK_VERSION == '1_4'|| ASTERISK_VERSION == '1_6') {
        $arr['Uptime'] = 'core show uptime';
    }
    
    $htmlOutput = '<div style="color:#000000;font-size:12px;margin:10px;">';
    $htmlOutput .= '<table border="1" cellpadding="10">';

    foreach ($arr as $key => $value) {
        $response = $astman->send_request('Command',array('Command'=>$value));
        $astout = explode("\n",$response['data']);
        switch ($key) {
            case 'Uptime':
                $uptime = $astout;
                $htmlOutput .= '<tr><td colspan="2">'.$uptime[1]."<br />".$uptime[2]."<br /></td>";
                
                $htmlOutput .= '</tr>';
            break;
            case 'Active SIP Channel(s)':
                $activeSipChannel = $astout;
                $activeSipChannel_count = getActiveChannel($activeSipChannel, $channelType = 'SIP');
                $htmlOutput .= '<tr>';
                $htmlOutput .= "<td>Active Sip Channels: </td>"."<td>".$activeSipChannel_count."</td>";
            break;
            case 'Active IAX2 Channel(s)':
                $activeIAX2Channel = $astout;
                $activeIAX2Channel_count = getActiveChannel($activeIAX2Channel, $channelType = 'IAX2');
                $htmlOutput .= "<td>Active IAX2 Channels: ".$activeIAX2Channel_count."</td>";
                $htmlOutput .= '</tr>';
            break; 
            break; 
            case 'Sip Registry':
                $sipRegistration = $astout;
                $sipRegistration_count = getRegistration($sipRegistration, $channelType = 'SIP');
                $htmlOutput .= '<tr>';
                $htmlOutput .= "<td>SIP Registrations: "."<td>".$sipRegistration_count."</td>";
            break;  
            case 'IAX2 Registry':
                $iax2Registration = $astout;
                $iax2Registration_count = getRegistration($iax2Registration, $channelType = 'IAX2');
                $htmlOutput .= "<td>IAX2 Registrations: ".$iax2Registration_count."</td>";
                $htmlOutput .= '</tr>';
            break;              
            case 'Sip Peers':
                $sipPeer = $astout;
                $sipPeer_arr = getPeer($sipPeer, $channelType = 'SIP');
                if($sipPeer_arr['offline'] != 0){
                    $sipPeerColor = 'red';
                }else{
                    $sipPeerColor = '#000000';
                }
                $htmlOutput .= '<tr>';
                $htmlOutput .= "<td>SIP Peers &nbsp;&nbsp;&nbsp;&nbsp;"."</td>";
                //$htmlOutput .= '</tr>';
                $htmlOutput .= '<tr>';
                $htmlOutput .= "<td>Online: </td>"."<td>".$sipPeer_arr['online']."<br />&nbsp;&nbsp;&nbsp;&nbsp;Offline: <span style=\"color:".$sipPeerColor.";font-weight:bold;\">".$sipPeer_arr['offline']."</span></td>";
            break;
            case 'IAX2 Peers':
                $iax2Peer = $astout;
                $iax2Peer_arr = getPeer($iax2Peer, $channelType = 'IAX2');
                if($iax2Peer_arr['offline'] != 0){
                    $iax2PeerColor = 'red';
                }else{
                    $iax2PeerColor = '#000000';
                }
                $htmlOutput .= "<td>IAX2 Peers<br />&nbsp;&nbsp;&nbsp;&nbsp;Online: ".$iax2Peer_arr['online']."<br />&nbsp;&nbsp;&nbsp;&nbsp;Offline: <span style=\"color:".$iax2PeerColor.";font-weight:bold;\">".$iax2Peer_arr['offline']."</span><br />&nbsp;&nbsp;&nbsp;&nbsp;Unmonitored: ".$iax2Peer_arr['unmonitored']."</td>";
                $htmlOutput .= '</tr>';
            break;     
            default:
            }
        }
    $htmlOutput .= '</table>';
   // print_r();
    return $htmlOutput."</div>";
    
    
 //  echo $sipPeerInfo_arr['hello']; 
 
   
}
 $arr = array(
 
        "Uptime" => "core show uptime",
        "Active SIP Channel(s)" => "sip show channels concise",
        "Active IAX2 Channel(s)" => "iax2 show channels",
        "Sip Registry" => "sip show registry",
        "IAX2 Registry" => "iax2 show registry",
        "Sip Peers" => "sip show peers",    
        "IAX2 Peers" => "iax2 show peers",     
        "summary" => "core show channels",     
    );
foreach ($arr as $key => $value) {
        //$response = $astman->send_request('Command',array('Command'=>$value));
        //$astout = explode("\n",$response['data']);
       // print_r($response);
       // echo "<br>";
}


$response = $astman->send_request('Command',array( "Command" => "core show channels concise"));
if(isset($_REQUEST['countryinfo']))
{
       $card_table = new Table('cc_country','*');
                                    $data["calls"]=array();
                                    $kk=0;
                                    foreach(explode("\n", $response['data']) as $line)
                                    {
                                        if (preg_match("/Up/i", $line) && preg_match("/!Dial!/i", $line)) 
                                        {
                                                
                                                 $pieces = explode("!", $line);
                                                 //destinations
                                                 $destinations = ltrim($pieces[2], '0');
                                                 
                                                 $dest_sub=substr($destinations,0,$prefix_max_length[0][0]);
                                                
                                                 for($k=0;$k<strlen($dest_sub);$k++)
                                                 {
                                                     $tofind=substr($dest_sub,0,(strlen($dest_sub)-$k));
                                                     
                                                     //QUERY FOR SEARCHING PREFIX FROM cc_country TABLE
                                                     $QUERY ="SELECT countryprefix,countryname FROM  `cc_country` WHERE countryprefix = '".$tofind."'";
                                                     $found_prefix = $instance_table -> SQLExec($DBHandle, $QUERY);
                                                     $card_clause = "countryprefix = '".$tofind."'";
                                                     $found_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
                                                     
                                                     if($found_result)
                                                     {
                                                         $data["calls"]["code"][$kk] = $found_result[0]['countryprefix'];  
                                                         $data["calls"]["country"][$kk] = $found_result[0]['countryname'];
                                                         $kk++ ;
                                                         
                                                           
                                                     }
                                                     else
                                                     {
                                                        
                                                     } 
                                                 }
                                                
                                                 
                                          
                                        }
                                    }
                                    for($cnt=0;$cnt<count($data["calls"]["code"]);$cnt++)
                                    {
                                        if( ! in_array($found_result[0]['countryprefix'],$data["calls"]["code"]))
                                             {
                                                 $data["calls"]["code"][$kk] = $found_result[0]['countryprefix'];  
                                                 $data["calls"]["country"][$kk] = $found_result[0]['countryname'];
                                                 $kk++;
                                             }   
                                    }
                                    $call_num=((array_count_values($data["calls"]["code"])));
                                    $codes = (array_keys(array_count_values($data["calls"]["code"])));
                                    $countries = (array_keys(array_count_values($data["calls"]["country"])));
                                   
                                  for($jj=0;$jj<count($codes);$jj++)
                                    {
                                      $key=$codes[$jj];   
                                ?>
                                    <tr style="text-align: center;">
                                        
                                        <td>
                                            <?php
                                              echo ($jj+1);  
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                              echo $codes[$jj];  
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                              echo $countries[$jj];  
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                              echo  $call_num[$key];  
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                     
                                    }
}
                                
if(isset($_REQUEST['callinfo']))
{
  //for call

                          
                          
                             $i=1;                                
                                
                                foreach(explode("\n", $response['data']) as $line)
                                {
                                    if (preg_match("/Up/i", $line) && preg_match("/!Dial!/i", $line))
                                    {
                                        $pieces = explode("!", $line);
                                        $trunk=explode("/",$pieces[6]);
                                    ?>
                                    
                                <tr style="text-align: center;">
                                    <td class="uk-text-center"><span class="uk-text-small uk-text-muted uk-text-nowrap">CALL-<?php echo $i; ?></span></td>
                                    <td>
                                         <?php echo gmdate("H:i:s", $pieces[11]); ?>
                                    </td>
                                    <td><a href="#"><?php echo $pieces[8]; ?></a></td>
                                    <td><span ><?php echo $pieces[2]; ?></span></td>
                                    <td class="uk-text-small"><?php echo $trunk[1]; ?></td>
                                    <td class="uk-text-small"><?php echo $pieces[4]; ?></td>
                                    <td><span class="uk-badge uk-badge-outline uk-text-upper"><?php 
                                    echo "<a href='#' onclick='call_terminate(\"".$pieces[0]."\")' title='Terminate the call' ><i  class=\"icon-trash\"></i></a>";
                                    ?></span></td>
                                </tr>
                              <?php    $i++; 
                                    }
                                       
                                }
                            ?> 
                            <tr>
                                <td colspan="7">
                                <form name="asteriskinfo" action="<?php  $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="hidden" name="display" value="asteriskinfo"/>
                                <input type="hidden" name="action" value="asteriskinfo"/>
                                
                                <button type="submit" name="reset" class="btn btn-warning btn-small"  ><i class="icon-reset"></i><?php echo _("Refresh")?></button>
                                </form>
                                </td>
                            </tr>
                            <?php  
}
                                       
                                
                                
?>                                
<style type="text/css">
.uk-text-center,uk-text-small
{
    text-align: center;
}
</style>
<script type="text/javascript">
function call_terminate(sipval)
         {
             var postData = "sipnum="+sipval;
             /*alert(postData);*/
             $.ajax({
                type: "POST",
                url: "live-call-termination.php",
                data:postData,
                success: function(response) {
                    
                    alert(response);
                    //window.location.href="live-call-summery_live.php?section=6&type=tool&display=live_call&extdisplay=all";
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        }); 
       }
         
</script>
 
