<?php


if(!function_exists('smarty_block_bs_panel')){
    include_once 'block.bs_panel.php';
}
/**
 * 
 * @param type $inParams
 * @param type $content
 * @param Smarty_Internal_Template $template
 * @param type $repeat
 */
function smarty_block_bs_box($inParams, $content, $template, &$repeat) {
    $inParams = array_merge($inParams, ['component' => 'box']);
    smarty_block_bs_panel($inParams, $content, $template, $repeat);
}
