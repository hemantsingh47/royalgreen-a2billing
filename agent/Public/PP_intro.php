<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
**/

include '../lib/agent.defines.php';
include_once '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';

if (!$ACXACCESS) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$smarty->display('main.tpl');
$DBHandle = DbConnect();
$table_message = new Table("cc_message_agent", "*");
$clause_message = "id_agent = ".$_SESSION['agent_id'];
$messages = $table_message -> Get_list($DBHandle, $clause_message, 'order_display', 'ASC');
$cnts = new Constants();
$message_types = $cnts->getMsgTypeList();
?>

<?php
if (is_array($messages)&& sizeof($messages)>0) {
    foreach ($messages as $message) {
    ?>

        <div id="msg" class="<?php echo $message_types[$message['type']][2];?>" style="margin-top:0px;position:relative;<?php if($message['logo']==0)echo 'background-image:none;padding-left:10px;'; ?>" >
        <?php echo stripslashes($message['message']); ?>
        </div>
    <?php }
} else {
?>

<center>
<table align="center" width="90%" bgcolor="white" cellpadding="15" cellspacing="15" style="border: solid 1px">
<div class="col-md-9 no-padding lib-item" data-category="view">
                <div class="lib-panel">
                    <div class="row box-shadow">
                        <div class="col-md-12">
                            <img class="lib-img-show" src="images/logo/a2billing.jpg">
                        </div>
                        <div class="col-md-10">
                            <!--<div class="lib-row lib-header">
                                EXAMPLE
                                <div class="lib-header-seperator"></div>
                            </div>-->
                            <div class="lib-row lib-header" align="left">
                            <ul>
                                <li>Authentication,Authorization,Accounting and real time <b>VOIP</b>.</li>
                                <li>Customer Management System.</li>
                                <li>Powerfull <b>Rate-Engine</b>.</li>
                                <li>Recurring Service Over The Card.</li>
                                <li>Auto creation card option for new callerID.</li>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </table>
</center>

<style>
body {
    padding: 20px;
    font-family: 'Open Sans', sans-serif;
    background-color: #f7f7f7;
}

.lib-panel {
    margin-bottom: 20Px;
}
.lib-panel img {
    width: 100%;
    background-color: transparent;
}

.lib-panel .row,
.lib-panel .col-md-6 {
    padding: 0;
    background-color: #FFFFFF;
}


.lib-panel .lib-row {
    padding: 0 20px 0 20px;
}

.lib-panel .lib-row.lib-header {
    background-color: #FFFFFF;
    font-size: 20px;
    padding: 10px 20px 0 20px;
}

.lib-panel .lib-row.lib-header .lib-header-seperator {
    height: 2px;
    width: 26px;
    background-color: #d9d9d9;
    margin: 7px 0 7px 0;
}

.lib-panel .lib-row.lib-desc {
    position: relative;
    height: 100%;
    display: block;
    font-size: 13px;
}
.lib-panel .lib-row.lib-desc a{
    position: absolute;
    width: 100%;
    bottom: 10px;
    left: 20px;
}

.row-margin-bottom {
    margin-bottom: 20px;
}

.box-shadow {
    -webkit-box-shadow: 0 0 10px 0 rgba(0,0,0,.10);
    box-shadow: 0 0 10px 0 rgba(0,0,0,.10);
}

.no-padding {
    padding: 0;
}
</style>
            
<?php
}
$smarty->display('footer.tpl');
