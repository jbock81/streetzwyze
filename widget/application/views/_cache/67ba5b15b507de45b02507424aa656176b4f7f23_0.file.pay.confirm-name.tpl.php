<?php /* Smarty version 3.1.27, created on 2020-03-02 23:57:21
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/pay.confirm-name.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:617752605e5d8f519b4919_50305881%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67ba5b15b507de45b02507424aa656176b4f7f23' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/pay.confirm-name.tpl',
      1 => 1583186113,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '617752605e5d8f519b4919_50305881',
  'variables' => 
  array (
    'reservation' => 0,
    'error' => 0,
    'customer' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5d8f51a18842_91080192',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5d8f51a18842_91080192')) {
function content_5e5d8f51a18842_91080192 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_currency')) require_once '/home2/l3n9m1k1/streetz/widget/application/plugins/smarty/modifier.currency.php';
if (!is_callable('smarty_block_capture_to_section')) require_once '/home2/l3n9m1k1/streetz/widget/application/plugins/smarty/block.capture_to_section.php';

$_smarty_tpl->properties['nocache_hash'] = '617752605e5d8f519b4919_50305881';
?>
<form class="form-horizontal vault-form" method="POST" action="<?php echo SmartyMiscFunctions::smartyFunctionBuildUrl(array('action'=>'confirm-name'),$_smarty_tpl);?>
?rid=<?php echo $_smarty_tpl->tpl_vars['reservation']->value->id;?>
"
      id="confirmForm">
    <?php if ($_smarty_tpl->tpl_vars['error']->value) {?>
        <div class="alert alert-danger">
            <?php echo $_smarty_tpl->tpl_vars['error']->value;?>

        </div>
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['reservation']->value->hasCancelCharge()) {?>
        <div class="error" style="font-weight: 700; text-transform: uppercase; font-size: 12px;">
            Non refundable fee of <?php echo smarty_modifier_currency($_smarty_tpl->tpl_vars['reservation']->value->delivery_fee);?>

        </div>
        <br/>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['customer']->value) {?>
        <?php if ($_smarty_tpl->tpl_vars['customer']->value->CustomerLastName) {?>
            <div class="well well-sm">
                <?php echo $_smarty_tpl->tpl_vars['customer']->value->CustomerFirstName;?>
 <?php echo $_smarty_tpl->tpl_vars['customer']->value->CustomerLastName;?>

            </div>
        <?php } else { ?>
            <div class="well well-sm">
                Welcome back!
            </div>
        <?php }?>
    <?php } else { ?>
        <div class="well well-sm">
            Welcome!
        </div>
    <?php }?>

    <div>
        <button class="btn btn-block btn-success btn-lg" name="action" value="confirmName" id="proceedToPayBtn">
            Proceed
        </button>
        <div class="help-block">
            <br/>
            <small>Clicking on proceed you confirm acceptance of the <a href="/legal.php" target="_blank">terms and conditions</a></small>
        </div>
    </div>
</form>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('capture_to_section', array('name'=>'footerScripts','global'=>true)); $_block_repeat=true; echo smarty_block_capture_to_section(array('name'=>'footerScripts','global'=>true), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

<?php echo '<script'; ?>
>
    $(function () {
        $('#proceedToPayBtn').on('click', function (e) {
            //e.preventDefault();
            //console.log('Posting size');
            window.parent.postMessage({
                type: 'size',
                height: 660,
                width: 530
            }, '*');
            //$('#confirmForm')[0].submit();
        });

    });

<?php echo '</script'; ?>
>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_capture_to_section(array('name'=>'footerScripts','global'=>true), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php echo $_smarty_tpl->getSubTemplate ('_footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);

}
}
?>