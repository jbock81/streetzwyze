<?php /* Smarty version 3.1.27, created on 2020-03-02 23:29:53
         compiled from "/home2/l3n9m1k1/streetz/widget/application/views/layout.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1479713425e5d88e1d21793_90508448%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a60b2b99bbf562a9cfdf1a18531e67fa5c2db83c' => 
    array (
      0 => '/home2/l3n9m1k1/streetz/widget/application/views/layout.tpl',
      1 => 1583185913,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1479713425e5d88e1d21793_90508448',
  'variables' => 
  array (
    'page_title' => 0,
    'MAIN_CONTENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5e5d88e1d6da20_86278393',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5e5d88e1d6da20_86278393')) {
function content_5e5d88e1d6da20_86278393 ($_smarty_tpl) {
if (!is_callable('smarty_function_captured_section')) require_once '/home2/l3n9m1k1/streetz/widget/application/plugins/smarty/function.captured_section.php';

$_smarty_tpl->properties['nocache_hash'] = '1479713425e5d88e1d21793_90508448';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php if ($_smarty_tpl->tpl_vars['page_title']->value) {
echo $_smarty_tpl->tpl_vars['page_title']->value;?>
-<?php }?>Streetzwyze | Secure exchange of goods and services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" href="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
css/styles.css"/>
    <link rel="icon" href="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
favicon.png" type="image/png"/>
</head>
<body>

<div class="container payportal-body">
    <div class="row">
        <div class="col-lg-offset-3 col-md-offset-2 col-lg-6 col-md-8 col-sm-offset-2 col-sm-8">
            <div class="themed contentWrap vault-text">
                <div id="mainContent">
                    <div class="logo text-center">
                        <img src="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
img/logo.png"/>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <?php echo $_smarty_tpl->tpl_vars['MAIN_CONTENT']->value;?>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
 src="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
js/jquery.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo SmartyMiscFunctions::smartyFunctionAssetsUrl(array(),$_smarty_tpl);?>
js/jquery.material.form.min.js"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
>
        $(function () {
            $('form.vault-form')
                    .addClass('material')
                    .materialForm();

            //send message.
            function heightWatch() {
                window.parent.postMessage({
                    type: 'size',
                    height: $(document.body).height() + 10
                }, '*');
            }

            heightWatch();
            document.body.addEventListener('resize', function(e){
                heightWatch();
            });
        });
    <?php echo '</script'; ?>
>

<?php echo smarty_function_captured_section(array('name'=>'footerScripts','global'=>true),$_smarty_tpl);?>

</body>
</html>
<?php }
}
?>