<?php /* Smarty version 3.1.27, created on 2020-03-03 01:26:23
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tx-success.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:13863937165e5da42f7ce689_72819383%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89e91cdb1f893c4df5d97235a799e090560092ae' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tx-success.tpl',
      1 => 1583195124,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13863937165e5da42f7ce689_72819383',
  'variables' => 
  array (
    'error' => 0,
    'reservation' => 0,
    'data' => 0,
    'transaction' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5da42f84a138_11772474',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5da42f84a138_11772474')) {
function content_5e5da42f84a138_11772474 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '13863937165e5da42f7ce689_72819383';
?>
<form class="form-horizontal vault-form" method="POST" action="">
    <?php if ($_smarty_tpl->tpl_vars['error']->value) {?>
        <div class="alert alert-danger">
            <?php echo $_smarty_tpl->tpl_vars['error']->value;?>

        </div>
    <?php }?>

    <?php if (!$_smarty_tpl->tpl_vars['reservation']->value->hasCustomerInfo()) {?>
        <p>
            
        </p>
    <?php } else { ?>
        <p style="color: #16a085; font-weight: 700; text-transform: uppercase; font-size: 12px;">
            We noticed payment was with a new card
        </p>
    <?php }?>

    <?php if (!$_smarty_tpl->tpl_vars['reservation']->value->hasCustomerInfo()) {?>
        <div>
            <div class="form-group">
                <div class="col-md-12">
                    <input class="form-control" name="customer[email]" type="email" placeholder="E-Mail Address"
                           required="" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['customer']['email'];?>
"/>
                </div>
            </div>
        </div>
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['transaction']->value->isNewBank()) {?>
        <div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bank']['account'];?>
" name="bank[account]"
                           placeholder="Account Number for reversal"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="form-control-static text-left" style="font-weight: bold; line-height: 50px;">
                        <img src="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
img/logos/<?php echo mb_strtolower($_smarty_tpl->tpl_vars['transaction']->value->bank->qt_code, 'UTF-8');?>
.png"
                             style="max-width: 100%; height: 50px; float: right;">
                        <?php echo $_smarty_tpl->tpl_vars['transaction']->value->bank->getName();?>

                    </div>

                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    <?php }?>
    <div>
        <button class="btn btn-block btn-success btn-lg" name="action" value="complete" type="submit">
           Secure Fund
        </button>
    </div>
</form>

<?php }
}
?>