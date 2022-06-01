<?php /* Smarty version 3.1.27, created on 2020-03-02 22:34:20
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/gateway.test-payment-success.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:7037768475e5d7bdc036478_94756528%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0d91e7042e49b6b82dcf98776ecb820402fc84c8' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/gateway.test-payment-success.tpl',
      1 => 1583149499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7037768475e5d7bdc036478_94756528',
  'variables' => 
  array (
    'page_title' => 0,
    'reservation' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5d7bdc090ac4_19891231',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5d7bdc090ac4_19891231')) {
function content_5e5d7bdc090ac4_19891231 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '7037768475e5d7bdc036478_94756528';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="IE=9" http-equiv="X-UA-Compatible" />
        <meta content="width=device-width,initial-scale=1,maximum-scale=1" name="viewport" />
        <title><?php if ($_smarty_tpl->tpl_vars['page_title']->value) {
echo $_smarty_tpl->tpl_vars['page_title']->value;?>
-<?php }?>Cashvault - Distribution Payment Service</title>
        <link rel="icon" href="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
favicon.png" type="image/png"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
css/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
css/styles.css"/>
    </head>
    <body class="test-gateway">
        <div id="mainContent" onClick="">
            <section class="payment-form mar-top">
                <div class="container">
                    <div class="row mar-top">
                        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1-5">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                </div>
                            </div>
                            <form id="testPaymentSuccessForm" action="<?php echo SmartyMiscFunctions::smartyFunctionBuildUrl(array('action'=>'test-payment-success'),$_smarty_tpl);?>
?rid=<?php echo $_smarty_tpl->tpl_vars['reservation']->value->id;?>
" method="POST">
                                <div class="form-box mar-top">
                                    <div class="form-body">
                                        <div class="form-group text-center">
                                            <h4 class="green "><i class="fa fa-check" aria-hidden="true"></i></h4>
                                            <h5> Transaction successful</h5>
                                            <p>Returning to Merchant</p>
                                            <div id="countdown">
                                                <div id="countdown-number"></div>
                                                <svg>
                                                <circle r="18" cx="20" cy="20"></circle>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="row btn-row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="action" value="PaymentSuccessRedirect"/>
                                                <button class="pay-bt mar-top" type="button"> Return to Merchant</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    <?php echo '<script'; ?>
 src="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
js/testgateway.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
plugin.min.js"><?php echo '</script'; ?>
>
</div>
</body>
</html><?php }
}
?>