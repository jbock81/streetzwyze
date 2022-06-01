<?php /* Smarty version 3.1.27, created on 2020-03-02 12:52:08
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tx-failed.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2676493585e5cf368ddea24_41195483%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c092c0b17a90d43aefbcc35d68b9e1aa83b4902c' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tx-failed.tpl',
      1 => 1583149499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2676493585e5cf368ddea24_41195483',
  'variables' => 
  array (
    'transaction' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5cf368e15260_04476048',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5cf368e15260_04476048')) {
function content_5e5cf368e15260_04476048 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '2676493585e5cf368ddea24_41195483';
?>
<div class="alert alert-danger reservation-failed">
    <h2>Reservation Failed</h2>
    REASON: <?php echo $_smarty_tpl->tpl_vars['transaction']->value->gateway_status_text;?>

</div>
<br/>




<div class="text-left">
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
</div>
<?php echo $_smarty_tpl->getSubTemplate ('_tx_return.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);

}
}
?>