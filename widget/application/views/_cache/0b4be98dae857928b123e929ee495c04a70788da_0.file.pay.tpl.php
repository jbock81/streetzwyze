<?php /* Smarty version 3.1.27, created on 2020-03-02 12:50:55
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:6532595965e5cf31f15cbd7_90135701%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0b4be98dae857928b123e929ee495c04a70788da' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/main/pay.tpl',
      1 => 1583149499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6532595965e5cf31f15cbd7_90135701',
  'variables' => 
  array (
    'error' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5cf31f197ea7_13567685',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5cf31f197ea7_13567685')) {
function content_5e5cf31f197ea7_13567685 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '6532595965e5cf31f15cbd7_90135701';
?>
<form class="form-horizontal vault-form" method="POST" action="">
    <?php if ($_smarty_tpl->tpl_vars['error']->value) {?>
        <div class="alert alert-danger">
            <?php echo $_smarty_tpl->tpl_vars['error']->value;?>

        </div>
    <?php }?>

    <div class="form-group">
        <div class="col-md-12">
            <input type="text" pattern="^\d+$" class="form-control" name="mobile_number" placeholder="Mobile Number" required="" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['mobile_number'];?>
"/>
        </div>
    </div>

    <div>
        <button class="btn btn-block btn-success btn-lg" name="action" value="begin">
            Continue
        </button>
    </div>
</form>
<?php echo $_smarty_tpl->getSubTemplate ('_footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);

}
}
?>