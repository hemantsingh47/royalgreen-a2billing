<?php
$url = $_SERVER['REQUEST_URI'];
header("Refresh:20;URL=\"". $url."\""); //redirect in 20 seconds
?>

<?php
include ("/var/www/html/crm/common/lib/admin.defines.php");
include ("/var/www/html/crm/common/lib/admin.module.access.php");
include ("/var/www/html/crm/common/lib/regular_express.inc");
include ("/var/www/html/crm/common/lib/phpagi/phpagi-asmanager.php");
include ("/var/www/html/crm/common/lib/admin.smarty.php");


if (! has_rights (ACX_MAINTENANCE)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

check_demo_mode_intro();
            
// #### HEADER SECTION
$smarty->display('main.tpl');

?> 
 <h2><?php echo gettext("Live Call Reports");?></h2>
<div class="row-fluid">
    <div class="widget-box">
      

<center style="margin-right:370px;">
     
<?php

$astman = new AGI_AsteriskManager();
$res = $astman->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);
print_r($res);
echo "hello";
echo $res[0];
                                                
$dispnum = 'live_call'; //used for switch on config.php

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$extdisplay = isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:'summary';

$modes = array(
	//"summary" => "Summary",
	//"registries" => "Registries",
	//"channels" => "Channels",
	//"peers" => "Peers",
	//"sip" => "Sip Info",
	//"iax" => "IAX Info",
	//"conferences" => "Conferences",
	//"subscriptions" => "Subscriptions",
	//"voicemail" => "Voicemail Users",
	/*"codecs" => "Codecs",*/
    //"all" => "Full Report"
);  
$arr_all = array(
	//"Version" => "core show version",
	//"Uptime" => "core show uptime",
	"Active Channel(s)" => "core show channels concise",
     //"Sip Channel(s)" => "sip show channels concise",
	//"IAX2 Channel(s)" => "iax2 show channels",
	//"Sip Registry" => "sip show registry",
	//"Sip Peers" => "sip show peers",                  
	//"IAX2 Registry" => "iax2 show registry",
	//"IAX2 Peers" => "iax2 show peers",
	//"Codecs" => "core show translation",
	//"Subscribe/Notify" => "core show hints",
   // "Zaptel driver info" => "zap show channels",
	//"Conference Info" => "meetme list",
	//"Voicemail users" => "voicemail show users", 
);   
/*$arr_registries = array(
	//"Sip Registry" => "sip show registry",
	//"IAX2 Registry" => "iax2 show registry",
); 
$arr_channels = array(
	"Live Call Reports" => "core show channels",
	"Sip Channel(s)" => "sip show channels concise",
	//"IAX2 Channel(s)" => "iax2 show channels",
);
$arr_codecs = array(
        "Codecs" => "show translation",
); 
$arr_peers = array(
	"Sip Peers" => "sip show peers",
	"IAX2 Peers" => "iax2 show peers",
);  
$arr_sip = array(
	"Sip Registry" => "sip show registry",
	"Sip Peers" => "sip show peers",
);
$arr_iax = array(
	"IAX2 Registry" => "iax2 show registry",
	"IAX2 Peers" => "iax2 show peers",
);
$arr_conferences = array(
	"Conference Info" => "meetme list",
);
$arr_subscriptions = array(
	"Subscribe/Notify" => "core show hints"
);
$arr_voicemail = array(
	"Voicemail users" => "voicemail show users",
);                                                */


if (ASTERISK_VERSION == '1_4'|| ASTERISK_VERSION == '1_6') {
	//$arr_all["Uptime"]="core show uptime";
	/*$arr_all["Active Live Call"]="core show channels";
	$arr_all["Subscribe/Notify"]="core show hints";
	$arr_all["Voicemail users"]="voicemail show users";
	$arr_all["Codecs"]="core show translation";
	$arr_codecs["Codecs"]="core show translation";*/
	/*$arr_channels["Active Channel(s)"]="core show channels concise";*/
	/*$arr_subscriptions["Subscribe/Notify"]="core show hints";
	$arr_voicemail["Voicemail users"]="voicemail show users";  */ 
}

?>

 
<div class="rnav"><ul>
 
<?php 
$i=0;
foreach ($modes as $mode => $value) {
	$i++;
	if ($i > 1) echo " | ";
	/*echo "<li><a id=\"".($extdisplay==$mode)."\" href=\"".$_SERVER['PHP_SELF']."?&section=".$section."type=".urlencode("tool")."&display=".urlencode($dispnum)."&extdisplay=".urlencode($mode)."\">"._($value)."</a></li>"; */
	  echo "<a id=\"".($extdisplay==$mode)."\" href=\"".$_SERVER['PHP_SELF']."?section=".$section."&type=".urlencode("tool")."&display=".urlencode($dispnum)."&extdisplay=".urlencode($mode)."\">"._($value)."</a>";
}
?>
</ul></div>

<div class="content">
<!--<h2><span class="headerHostInfo"><?php echo "Live Reports ".$modes[$extdisplay]; ?></span></h2>-->

<form name="asteriskinfo" action="<?php  $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="display" value="asteriskinfo"/>
<input type="hidden" name="action" value="asteriskinfo"/>



<?php
if (!$astman) {
?>   <table class="jtable">
	<tr class="boxheader">
		<td colspan="2" align="center"><h5><?php echo _("ASTERISK MANAGER ERROR")?></h5></td>
	</tr>
		<tr class="boxbody" align="left">
			<td>
			<table border="1" >
				<tr>
					<td >
							<?php 
							echo "<br>The module was unable to connect to the asterisk manager.<br>Make sure Asterisk is running and your manager.conf settings are proper.<br><br>";
							?>
					</td>
				</tr>
			</table>
			</td>
		</tr>
        </table>
<?php
} else {
	if ($extdisplay != "summary") {
		$arr="arr_".$extdisplay;
		foreach ($$arr as $key => $value) {
?>
                            
			                <div class="jtable-main-container">
                            <table class="jtable" border="0" cellpadding="2" cellspacing="2" width="100%" align="center" >
                            <thead>
                            <tr >
                            <td style="width:30%" align="center">Duration</td>
                            <td style="width:20%" align="center">Account/Pin</td>
                            <td style="width:20%" align="center">Destination</td>
                            <td style="width:50%" align="center">Trunk</td>
                            <td style="width:20%" align="center">Status</td>
                            </tr  >
                            </thead>
                            <tbody>
                            
								<?php                                 
								$response = $astman->send_request('Command',array('Command'=>$value));
                                 
								$new_value = $response['data']; 
                            
                                //echo ltrim($new_value,'Privilege: Command');
                                //echo $new_value;
                                
                                foreach(explode("\n", $response['data']) as $line)
                                if (preg_match("/Up/i", $line) && preg_match("/!Dial!/i", $line)) 
                                {
                                  {
                                         $pieces = explode("!", $line);
                                         
                                         $regex = "~".preg_quote($pieces[12],"~")."!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!~";
                                         preg_match($regex,$response['data'],$to);
            
                                        
            
                                         echo "<tr bgcolor='#F2F2EE' onmouseover='bgColor='#A4C8DE'' onmouseout='bgColor='#F2F2EE''>";
                                         echo "<td align='center'>"; 
                                         echo gmdate("H:i:s", $pieces[11]);
                                           
                                         echo "</td>";
                                         
                                         echo "<td align='center'>";
                                         echo $pieces[8];
                                         //echo $to[8];
                                         echo "</td>";
                                         
                                         echo "<td align='center'>";
                                         echo $pieces[2];
                                         echo "</td>";
                                         
                                         $trdata = explode(",",$pieces[6]);
                                         //echo $trdata[0]."<br>";
                                         $trdata1 = explode("/",$trdata[0]);
                                         //echo $trdata1[1]."<br>";
                                         
                                         echo "<td align='center'>";
                                         //echo $pieces[6];
                                         echo $trdata1[1];
                                         echo "</td>";
                                         
                                         echo "<td align='center'>";
                                         echo $pieces[4];
                                         echo "</td>";
                                         echo "</tr>";
                                         
                                  }
                                }
                                                               
								?>  
                                </tbody>
                                </table>
			                  </div>
		<?php
			}
                
		} else {
	?>
			<tr class="boxheader">
				<td colspan="2" align="center"><h2><?php echo _("Summary")?></h2></td>
			</tr>
			<tr class="boxbody" align="left">
				<td>
				<table border="1">
					<tr>
						<td>
							<?php echo buildAsteriskInfo(); ?>       
						</td>
					</tr>
				</table>
			</td>
		</tr>
<?php
	}
}
?>   
	</table>
<tr>
	<td colspan="2"><h3><input name="Submit" class="form_input_button" type="submit" value="<?php echo _("Refresh")?>"></h3></td>
</tr>
</table>

<script language="javascript">
<!--
var theForm = document.asteriskinfo;
//-->
</script>
</form>
 
   <?php

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
		"Active SIP Channel(s)" => "sip show channels",
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
	return $htmlOutput."</div>";
    
    
 //  echo $sipPeerInfo_arr['hello']; 
 
   
}
?>
</center>
</div>
</div> 
<?php
       
// #### FOOTER SECTION
$smarty->display('footer.tpl');


