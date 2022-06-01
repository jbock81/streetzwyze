<?php

/**
 * 
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return type
 */
function smarty_block_bs3_wrap_control($params, $content, $template, &$repeat) {
    $label = $params['label'];
    if (!$repeat) {
        $attrs = $params;
        unset($attrs['label'], $attrs['label_size'], $attrs['input_size'], $attrs['required']);
        $attributes = array();
        foreach ($attrs as $name => $value) {
            $attributes[] = "{$name}=\"" . str_replace('"', '\"', $value) . "\"";
        }
        $attrStr = join(' ', $attributes);
        $inputSize = $params['input_size'] ? : 5;
        $labelSize = $params['label_size'] ? : 3;
        $required = isset($params['required']) && $params['required'] ? "*" : "";

        $return = <<<BS
    <div class="form-group" {$attrStr}>
        <label class='control-label col-sm-{$labelSize}' >
            {$label}{$required}
        </label>
        <div class="col-sm-{$inputSize} {$params['controls_class']}">{$content}</div>
    </div>
BS;
        if ($params['assign']) {
            $template->assign($params['assign'], $return);
        } else {
            return $return;
        }
    }
}
