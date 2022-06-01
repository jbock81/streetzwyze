<?php /* Smarty version 3.1.27, created on 2020-03-02 22:33:33
         compiled from "/home2/l3n9m1k1/streetz/widget/vendor/intelworx/ideo-framework/views/error.error404.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:5081562325e5d7badd02d55_88916377%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd531dfe720d2d16a1e39b4495037d20bf2b3d073' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/vendor/intelworx/ideo-framework/views/error.error404.tpl',
      1 => 1583149499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5081562325e5d7badd02d55_88916377',
  'variables' => 
  array (
    'showErrors' => 0,
    'error_type' => 0,
    'request' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5d7badd542a6_36751206',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5d7badd542a6_36751206')) {
function content_5e5d7badd542a6_36751206 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '5081562325e5d7badd02d55_88916377';
?>
<div id="page_content">
    <h2>Page not found</h2>
    <p>
        <i class="icon-info-sign"></i> The page you're looking for was not found. Please try again later.
    </p>
    <?php if ($_smarty_tpl->tpl_vars['showErrors']->value) {?>
        <p align="center"><strong>ERROR: </strong> <?php echo $_smarty_tpl->tpl_vars['error_type']->value;?>
</p>
        <pre>
Params: <?php echo $_smarty_tpl->tpl_vars['request']->value;?>

        </pre>
    <?php }?>
</div><?php }
}
?>