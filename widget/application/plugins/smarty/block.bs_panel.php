<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * @param type $inParams
 * @param type $content
 * @param Smarty_Internal_Template $template
 * @param type $repeat
 */
function smarty_block_bs_panel($inParams, $content, $template, &$repeat) {

    $defaults = array(
        'title' => "",
        'footer' => "",
        'type' => 'default',
        'id' => "panel",
        'has_body' => true,
        'component' => 'panel',
        'header_append' => '',
        'class' => '',
    );

    $params = array_merge($defaults, (array) $inParams);
    $params['type'] = $params['type'] ? : 'default';
    $component = $params['component'] ? : 'panel';

    if (!$repeat):
        $footerStored = array_pop($template->getVariable('bsPanelFooters')->value);
        if (!trim($params['footer'])) {
            $params['footer'] = $footerStored;
        }

        $attributes = [];
        foreach ($params as $name => $value) {
            if(!array_key_exists($name, $defaults)){
                $attributes[] = " {$name}=\"" . str_replace('"', '\"', $value) . "\"";
            }
        }
        $attrStr = join('', $attributes);
        ?>
        <div class="<?= $component ?> <?= $component ?>-<?= $params['type'] ?> <?= $params['class'] ?>" id="<?= $params['id'] ?>"<?= $attrStr?>>
            <?php if ($params['title']) : ?>
                <div class="<?= $component ?>-heading <?= $component ?>-header">
                    <h3 class="<?= $component ?>-title"><?= $params['title'] ?></h3>
                    <?php if (trim($params['header_append'])): ?>
                        <?= $params['header_append'] ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($params['has_body']): ?>
                <div class="<?= $component ?>-body">
                <?php endif; ?>
                <?= $content; ?>
                <?php if ($params['has_body']): ?>
                </div>
            <?php endif; ?>
            <?php if ($params['footer']): ?>
                <div class="<?= $component ?>-footer">
                    <?= $params['footer'] ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        --$template->getVariable('bsPanelLevel')->value;
    else:
        if ($template->getVariable('bsPanelLevel')->value) {
            ++$template->getVariable('bsPanelLevel')->value;
        } else {
            $template->assign('bsPanelLevel', 1);
        }


        if (!$template->getVariable('bsPanelFooters')->value) {
            $template->assign('bsPanelFooters', []);
        }

        $template->getVariable('bsPanelFooters')->value[] = '';
    endif;
}
