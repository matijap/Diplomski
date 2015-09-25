<?php

require_once 'BaseController.php';

class WidgetController extends Main_BaseController
{
    public function getLwebDataAction() {
        $lwebOptionID               = $this->params['lwebOptionID'];
        $this->view->lwebOptionData = Widget::getLwebOptionData($lwebOptionID);
        $lwebOption             = Main::buildObject('WidgetOption', $lwebOptionID);
        $widget                 = Main::buildObject('Widget', $lwebOption->widget_id);
        $this->view->modalTitle = $widget->title;
    }

    public function newWidgetAction() {
        $widgetID = Utils::arrayFetch($this->params, 'widgetID', false);
        $pageID   = Utils::arrayFetch($this->params, 'page_id', false);
        $data     = array();
        if ($widgetID) {
            $data['widgetID'] = $widgetID;
        }
        if ($pageID) {
            $data['pageID'] = $pageID;
        }
        // $data['widgetID'] = 4;
        $this->view->form = $form = new WidgetForm($data);
        $response = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $widget = Widget::factory($this->params);
                $widget->process();
                $this->setNotificationMessage($this->translate->_('Widget processed successfully'));
                $this->_helper->json(array('status'  => 1,
                                           'url'     => APP_URL . '/page/index?pageID=' . $pageID));
            }
        }
    }

    public function editLwebDataAction() {
        $tempData = Utils::arrayFetch($this->params, 'tempData', false);
        if (!empty($tempData)) {
            $tempData = Zend_Json::decode($tempData);
            if (isset($tempData['main'])) {
                foreach ($tempData['main'] as $key => $value) {
                    foreach ($value as $k => $v) {
                        $filter = new Zend_Filter_Digits();
                        unset($tempData['main'][$key][$k]);
                        $k = $filter->filter($k);
                        $tempData['main'][$key][$k] = $v;
                    }
                }
            }
            if (isset($tempData['additional'])) {
                foreach ($tempData['additional'] as $key => $value) {
                    $filter = new Zend_Filter_Digits();
                    unset($tempData['additional'][$key]);
                    $key = $filter->filter($key);
                    $tempData['additional'][$key] = $value;
                }
            }
        }
        $widgetOptionID = $this->params['widgetOptionID'];
        $data = array();
        if ($widgetOptionID != 'new') {
            $widgetOption = Main::buildObject('WidgetOption', $this->params['widgetOptionID']);
            $data         = $widgetOption->getWidgetOptionList();
        }
        $this->view->widgetOptionID = $widgetOptionID;
        $this->view->widgetData = $this->user->getUserWidgetDataList();

        
        $left       = array();
        $right      = array();
        $additional = array();
        foreach ($data as $key => $value) {
            $rand = Utils::getRandomNumber();
            if ($value->placement == Widget::WIDGET_LWEB_PLACEMENT_MAIN) {
                $left[$rand]  = array('label' => $value->title, 'value' => $value->value_1);
                $right[$rand] = array('label' => $value->title, 'value' => $value->value_2);
            } else {
                $additional = array($rand => array('label' => $value->title, 'value' => $value->value_1));
            }
        }
        if (empty($left)) {
            $rand = Utils::getRandomNumber();
            $left  = array($rand => array('label' => $this->translate->_('Name'), 'value' => ''));
            $right = array($rand => array('label' => $this->translate->_('Name'), 'value' => ''));
        }
        
        $this->view->decoded = $arr = array('main' => array('left' => $left,'right' => $right ), 'additional' => $additional);
        if ($tempData) {
            $this->view->decoded = $tempData;
        }
        $this->view->itemID  = $this->params['item'];

    }

    public function saveLwebDataAction() {
        $res = Main::fetchRow(Main::select('UserWidgetData')
                              ->where('user_id = ?', $this->params['userID'])
                              ->where('title = ?', $this->params['data'])
                              );
        if (!$res) {
            UserWidgetData::create(array('user_id' => $this->params['userID'], 'title' => $this->params['data']));
        }
    }

    public function deleteWidgetAction() {
        $this->view->form = $form = new WidgetForms_Delete(array('widgetID' => $this->params['widgetID']));
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $widget = Main::buildObject('Widget', $this->params['widgetID']);
                $widget->delete();
                $this->setNotificationMessage($this->translate->_('Widget deleted successfully'));
                $this->_helper->json(array('status'  => 1,
                                           'url'     => APP_URL . '/page/index?pageID=' . $this->params['pageID']));
            }
        }
    }

    public function deleteTableDataAction() {
        $tableData = Main::buildObject('WidgetTableData', $this->params['id']);
        if (is_null($tableData->user_id)) {
            $status  = 0;
            $message = $this->translate->_('Cannot delete system widget data');
        } else {
            $tableData->delete();
            $status  = 1;
            $message = $this->translate->_('Widget data deleted successfully');
        }
        $this->_helper->json(array('status'  => $status, 'message' => $message));
    }

    public function addTableDataAction() {
        $response = WidgetTableData::checkAvailability($this->params);
        $this->_helper->json(array(
            'status'  => $response['status'],
            'message' => $response['message'],
            'id'      => $response['id'],
            )
        );
    }
}