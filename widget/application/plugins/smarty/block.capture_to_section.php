<?php

require_once 'CaptureSectionSharedData.php';

/**
 * 
 * @param array $inParams
 * @param array $content
 * @param Smarty_Internal_Template $template
 * @param type $repeat
 */
function smarty_block_capture_to_section($inParams, $content, $template, &$repeat) {

    $params = array_merge(CaptureSectionSharedData::$DEFAULTS, $inParams);
    $parent = $params['global'] ? TemplateEngine::getInstance() : null;

    //retrieve the sections variable object
    $sectionsObj = $template->getVariable(CaptureSectionSharedData::VAR_NAME, $parent, false);

    if (!$sectionsObj || !is_array($sectionsObj->value)) {
        //create the object if not existent
        if ($parent) {
            $parent->assign(CaptureSectionSharedData::VAR_NAME, []);
        } else {
            $template->assign(CaptureSectionSharedData::VAR_NAME, []);
        }

        $sectionsObj = $template->getVariable(CaptureSectionSharedData::VAR_NAME, $parent, false);
    }

    $name = $params['name'];
    if ($repeat) {
        if (!array_key_exists($name, $sectionsObj->value)) {
            $sectionsObj->value[$name] = [];
        }
    } else {
        $sectionsObj->value[$name][] = $content;
    }
}
