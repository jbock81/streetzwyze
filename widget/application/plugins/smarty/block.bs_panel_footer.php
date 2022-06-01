<?php

/**
 * 
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return type
 */
function smarty_block_bs_panel_footer($params, $content, $template, &$repeat) {

//    $defaults = array();
//    $params = array_merge($defaults, (array) $inParams);

    if (!$repeat):
        $bsPanelFooters = &$template->getVariable('bsPanelFooters')->value;
        $end = count($bsPanelFooters) - 1;
        $bsPanelFooters[$end] = $content;
    else:
        
        if (!$template->getVariable('bsPanelLevel') || $template->getVariable('bsPanelLevel')->value < 1) {
            throw new SmartyException('bs_panel_footer should only be called within a bs_panel tag');
        }
    endif;
}
