<?php

require_once 'BaseController.php';

class WidgetController extends Main_BaseController
{
    public function getLwebDataAction() {
        $lwebOptionID               = $this->params['lwebOptionID'];
        $this->view->lwebOptionData = Widget::getLwebOptionData($lwebOptionID);
    }
}