<?php

require_once 'BaseController.php';

class WidgetController extends Main_BaseController
{
    public function getLwebDataAction() {
        $lwebOptionID               = $this->params['lwebOptionID'];
        $this->view->lwebOptionData = Widget::getLwebOptionData($lwebOptionID);
    }

    public function newWidgetAction() {
        $this->view->form = $form = new WidgetForm(array('widgetID' => 5));
        // fb($_FILES, 'files');
    }

    public function editLwebDataAction() {
        
    }
}