<?php /* Smarty version 3.1.27, created on 2020-03-02 23:30:59
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/pay.error.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:9433206015e5d8923748349_60315511%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e7f05ef6d1b56382b1407e09c5bbb3a18a1c7c4b' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/pay.error.tpl',
      1 => 1583149499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9433206015e5d8923748349_60315511',
  'variables' => 
  array (
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5d8923795614_70877292',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5d8923795614_70877292')) {
function content_5e5d8923795614_70877292 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '9433206015e5d8923748349_60315511';
?>
<p class="alert alert-danger">

    <?php echo $_smarty_tpl->tpl_vars['message']->value;?>

</p><?php }
}
?>