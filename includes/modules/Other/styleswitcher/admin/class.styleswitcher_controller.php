<?php


class StyleSwitcher_Controller extends HBController {
    
    /**
     * @var StyleSwitcher $module
     */
    var $module;

    public function beforeCall($params) {
        $this->template->render(substr(dirname(__FILE__),0,-5).'template'.DS.'default.tpl');
        $this->template->pageTitle = 'Style Switcher';
    }
    public function _default($params) {
        $this->template->assign('styles',$this->module->listStyles());
    }

    function add() {
        $id = $this->module->addStyle();
        if($id) {
            Utilities::redirect('?cmd=styleswitcher&action=edit&id='.$id);
        } else {
            Utilities::redirect('?cmd=styleswitcher');
        }
    }
    public function edit($params) {
        if(!isset($params['id']) || !$theme = $this->module->getStyle($params['id'])) {
            Utilities::redirect('?cmd=styleswitcher');
        }
        if(isset($params['make']) && $params['token_valid']) {
            if(isset($params['save'])) {
                $this->module->saveStyle($params['id'], $params['name'], $params['variables']);
                if(isset($params['enabled'])) {
                    $this->module->setDefault($params['id']);
                } else {
                    $this->module->unsetDefault($params['id']);

                }
            Utilities::redirect('?cmd=styleswitcher&action=edit&id='.$params['id']);

            } elseif(isset($params['delete'])) {
                $this->module->deleteStyle($params['id']);
            Utilities::redirect('?cmd=styleswitcher');
            }
        }
        $this->template->assign('theme',$theme);
        $this->template->assign('regions',$this->module->getAvailableRegions());
        $this->template->assign('styles',$this->module->listStyles());
        $this->template->assign('sansi','@sansFontFamily');
        $this->template->assign('size','@baseFontSize');
        $this->template->assign('bh','@BodyTopHeight');
        
    }

   
}