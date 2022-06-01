<?php /* Smarty version 3.1.27, created on 2020-03-02 12:52:08
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/_tx_return.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:7491921215e5cf368e1b144_14250286%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a86f56c2d111b0e88e9abb1ce4f8098f30601145' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/_tx_return.tpl',
      1 => 1583149499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7491921215e5cf368e1b144_14250286',
  'variables' => 
  array (
    'transaction' => 0,
    'reservation' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5cf368e3bec0_24565183',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5cf368e3bec0_24565183')) {
function content_5e5cf368e3bec0_24565183 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '7491921215e5cf368e1b144_14250286';
if ($_smarty_tpl->tpl_vars['transaction']->value) {?>
    <?php $_smarty_tpl->tpl_vars['reservation'] = new Smarty_Variable($_smarty_tpl->tpl_vars['transaction']->value->reservation, null, 0);?>
    <div class="row">
        <!--<div class="col-sm-10 col-sm-offset-1">-->
        <div class="col-xs-12 ">
            <button type="button" class="btn btn-success btn-lg btn-block" id="returnBtn">
                Close
            </button>
        </div>
    </div>
    <?php echo '<script'; ?>
>
        (function () {
            var transactionData = {
                ReservationID: "<?php echo $_smarty_tpl->tpl_vars['transaction']->value->reservation_id;?>
",
                ResponseCode: "<?php echo $_smarty_tpl->tpl_vars['transaction']->value->gateway_status_code;?>
",
                ResponseDescription: "<?php echo $_smarty_tpl->tpl_vars['transaction']->value->gateway_status_text;?>
",
            };

            var returnUrl = '<?php if ($_smarty_tpl->tpl_vars['reservation']->value->site_return_url) {
echo $_smarty_tpl->tpl_vars['reservation']->value->site_return_url;
} else {
echo SmartyMiscFunctions::smartyFunctionBuildUrl(array('controller'=>'pay','action'=>'default'),$_smarty_tpl);?>
?rid=<?php echo $_smarty_tpl->tpl_vars['reservation']->value->id;
}?>';

            document.getElementById('returnBtn').addEventListener('click', function () {
                var form = document.createElement('form');
                form.setAttribute('target', '_top');
                form.setAttribute('method', 'POST');
                form.setAttribute('name', 'merchantAlert');
                form.setAttribute('action', returnUrl);
                for (var i in transactionData) {
                    if (transactionData.hasOwnProperty(i)) {
                        form.innerHTML += '<input type="hidden" name="' + i + '" value="' + transactionData[i] + '"/>';
                    }
                }
                document.body.appendChild(form);
                form.submit();
            });
        })();

    <?php echo '</script'; ?>
>
<?php } else { ?>
    <div class="alert alert-danger">
        Transaction was not found, you should not be seeing this, please contact support.
    </div>
<?php }
}
}
?>