<?php /* Smarty version 3.1.27, created on 2020-03-05 22:16:41
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/pay.info.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:16980867655e616c39718417_59685228%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a8ef4c6e6aa9096eaa743c506751f6a9ffd3bd65' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/pay.info.tpl',
      1 => 1583149499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16980867655e616c39718417_59685228',
  'variables' => 
  array (
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e616c39890d36_03108915',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e616c39890d36_03108915')) {
function content_5e616c39890d36_03108915 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '16980867655e616c39718417_59685228';
?>
<div class="alert alert-info">
    <p>
        <?php echo $_smarty_tpl->tpl_vars['message']->value;?>

    </p>
</div><?php }
}
?>