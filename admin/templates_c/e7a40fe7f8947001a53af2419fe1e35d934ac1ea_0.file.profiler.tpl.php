<?php
/* Smarty version 3.1.33, created on 2019-08-28 06:37:31
  from '/var/www/html/crm/admin/Public/templates/default/profiler.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d66212bb6dff7_02972503',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e7a40fe7f8947001a53af2419fe1e35d934ac1ea' => 
    array (
      0 => '/var/www/html/crm/admin/Public/templates/default/profiler.tpl',
      1 => 1562392626,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d66212bb6dff7_02972503 (Smarty_Internal_Template $_smarty_tpl) {
?>	
<?php  
        global $profiler;
        global $G_instance_Query_trace;

        if ($profiler->installed && $profiler->modedebug)
                $profiler->display($G_instance_Query_trace);
?>

<?php }
}
