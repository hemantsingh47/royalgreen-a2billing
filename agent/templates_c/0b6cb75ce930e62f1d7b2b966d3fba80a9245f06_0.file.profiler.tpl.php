<?php
/* Smarty version 3.1.33, created on 2019-09-23 10:09:35
  from '/var/www/html/crm/agent/Public/templates/default/profiler.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d8899dfe7f976_68926811',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b6cb75ce930e62f1d7b2b966d3fba80a9245f06' => 
    array (
      0 => '/var/www/html/crm/agent/Public/templates/default/profiler.tpl',
      1 => 1558972820,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d8899dfe7f976_68926811 (Smarty_Internal_Template $_smarty_tpl) {
?>	
<?php  
        global $profiler;
        global $G_instance_Query_trace;

        if ($profiler->installed && $profiler->modedebug)
                $profiler->display($G_instance_Query_trace);
?>

<?php }
}
