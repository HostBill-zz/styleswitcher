<?php

class StyleSwitcher_Controller extends HBController {

    /**
     * @var StyleSwitcher $module
     */
    var $module;

    
    public function getstyle($params) {
        if (isset($params['id'])) {
            $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

            $content = $this->module->getStyle($params['id']);
            $etagFile = md5($params['id'] . $content['modified']);
            $last = gmdate("D, d M Y H:i:s", $content['modified']) . " GMT";
            header("Last-Modified: " . $last);
            header("Etag: " . $etagFile);
            header('Cache-control: public');
            header('Content-type: text/css');
            if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $content['modified'] ) || $etagHeader == $etagFile) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
            die($content['content']);
        }
    }

}