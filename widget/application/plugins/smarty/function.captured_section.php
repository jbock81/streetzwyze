<?php

require_once 'CaptureSectionSharedData.php';

/**
 * 
 * @param array $inParams
 * @param Smarty_Internal_Template $smarty
 */
function smarty_function_captured_section($inParams, $smarty) {
    $params = array_merge(CaptureSectionSharedData::$DEFAULTS, $inParams);
    $parent = $params['global'] ? TemplateEngine::getInstance() : null;
    
    $sectionObj = $smarty->getVariable(CaptureSectionSharedData::VAR_NAME, $parent, false);

    $result = null;
    $name = $params['name'];
    if ($sectionObj && is_array($sectionObj->value) && isset($sectionObj->value[$name])) {
        $result = join(PHP_EOL, $sectionObj->value[$name]);
    }

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $result);
    } else {
        return $result;
    }
}
