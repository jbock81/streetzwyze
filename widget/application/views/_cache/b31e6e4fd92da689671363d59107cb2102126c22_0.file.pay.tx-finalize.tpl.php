<?php /* Smarty version 3.1.27, created on 2020-03-03 01:27:19
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tx-finalize.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:14789914225e5da46744eb94_02347793%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b31e6e4fd92da689671363d59107cb2102126c22' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tx-finalize.tpl',
      1 => 1583190723,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14789914225e5da46744eb94_02347793',
  'variables' => 
  array (
    'transaction' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5da46748c134_73926420',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5da46748c134_73926420')) {
function content_5e5da46748c134_73926420 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_delivery_duration')) require_once '/home2/l3n9m1k1/streetz/widget/application/plugins/smarty/modifier.delivery_duration.php';

$_smarty_tpl->properties['nocache_hash'] = '14789914225e5da46744eb94_02347793';
?>
<div class="alert alert-success">
    <h2 style="font-weight: bold; font-size: 18px; margin: 4px">Secured</h2>
</div>

<p class="text-center">
    <?php $_smarty_tpl->tpl_vars['dur'] = new Smarty_Variable(smarty_modifier_delivery_duration($_smarty_tpl->tpl_vars['transaction']->value->reservation->delivery_duration), null, 0);?>
    
    Delivery duration is<strong> <?php echo smarty_modifier_delivery_duration($_smarty_tpl->tpl_vars['transaction']->value->reservation->delivery_duration);?>
</strong> and code to confirm purchase receipt on its way
</p>

<br/>

<?php echo $_smarty_tpl->getSubTemplate ('_tx_return.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);

}
}
?>