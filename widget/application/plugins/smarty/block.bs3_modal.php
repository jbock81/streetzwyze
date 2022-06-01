<?php

/**
 * 
 * @param array $inParams
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return type
 */
function smarty_block_bs3_modal($inParams, $content, $template, &$repeat) {
    $defaults = array(
        'title' => "",
        'footer' => "",
        'displayed' => false,
        'id' => "modal",
        'size' => "md",
        'animate' => true,
    );
    $params = array_merge($defaults, (array) $inParams);
    $fade = $params['animate'] ? 'fade' : '';
    if (!$repeat) {
        //$hide = $params['displayed'] ? : 'hide';
        $return = <<<BS
<div class="modal {$fade}" id="{$params['id']}">
  <div class="modal-dialog modal-{$params['size']}" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class='modal-title'>{$params['title']}</h3>
      </div>
      <div class="modal-body">
        {$content}
      </div>
      <div class="modal-footer">
        {$params['footer']}
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
BS;
        if ($params['assign']) {
            $template->assign($params['assign'], $return);
        } else {
            return $return;
        }
    }
}
