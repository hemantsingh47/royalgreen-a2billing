<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_signup_agent.inc';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_SIGNUP)) {
       Header ("HTTP/1.0 401 Unauthorized");
       Header ("Location: PP_error.php?c=accessdenied");
       die();
}

?>
<style type="text/css">
     #elegantBG { display: none; position: absolute; background: #000; opacity: 0.5; -moz-opacity: 0.5; -khtml-opacity: 0.5; filter: alpha(opacity=40); width: 100%; height: 100%; top: 0; left: 0; z-index: 99; }
    #elegantBOX { padding-top:15px;display: none; position: absolute; background: #FCFCFC; color: #333; font-size: 14px; text-align: center; border: 1px solid #111; top: 35%; left:40%; z-index: 100; }
    .elegantX { margin-top:15px; font-size: 12px; color: #636D61; padding: 4px 0; border-top: 1px solid #636D61; background: #BDE5F8; }
</style>
<script type="text/javascript">
var msg = '<?php echo gettext('click outside box to close'); ?>';
function getId(v) { return(document.getElementById(v)); }
function style(v) { return(getId(v).style); }
function agent(v) { return(Math.max(navigator.userAgent.toLowerCase().indexOf(v),0)); }
function isset(v) { return((typeof(v)=='undefined' || v.length==0)?false:true); }
function XYwin(v) { var z=Array($('#page-wrap').innerHeight()-2,$('#page-wrap').width()); return(isset(v)?z[v]:z); }

function elegantTOG() { document.onclick=function(){ style('elegantBG').display='none'; style('elegantBOX').display='none'; document.onclick=function(){}; }; }
function elegantBOX(v,b) { setTimeout("elegantTOG()",100); style('elegantBG').height=XYwin(0)+'px'; style('elegantBG').display='block'; getId('elegantBOX').innerHTML=v+'<div class="elegantX">('+msg+')'+"<\/div>"; style('elegantBOX').left=Math.round((XYwin(1)-b)/2)+'px'; style('elegantBOX').width=b+'px'; style('elegantBOX').display='block'; }
</script>
<?php
$HD_Form -> setDBHandler (DbConnect());

$HD_Form -> init();

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl'); ?>

 



<div id="elegantBG"></div>
<div id="elegantBOX" onmousedown="document.onclick=function(){};" onmouseup="setTimeout('elegantTOG()',1);"></div>

       <!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
            Signup URLs Details                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            User                       </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer SignUp                       </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>        
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Signup URL List                       </a></div>
                    
        </div>
       
    </div>
</div>
<?php

 



// #### HELP SECTION
echo $CC_help_signup_agent;

 

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;



// #### FOOTER SECTION
$smarty->display('footer.tpl');
