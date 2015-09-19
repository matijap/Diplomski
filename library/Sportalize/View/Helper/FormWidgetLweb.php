<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormWidgetLweb extends Zend_View_Helper_FormElement
{
    public function formWidgetLweb($name, $value = null, $attribs = null, array $options = null)
    {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
                
        $data  = $attribs['data'];
        $wiod  = $attribs['wiod'];

        //small hack. if no data is sent, we simulate array so we can utilize foreach
        if (!$data) {
            $data = array('new' => 'temp');
        }
        if (!$wiod) {
            $wiod = 'new';
        }
        
        $html = '<div class="owls-holder widget-marker sortable-initialize width-95-percent display-inline-block append-into" data-template="list-option-template">';
        $count = count($data);
        $style = $count > 1  ? '' : ' display: none;';
        foreach ($data as $key => $value) {
            $arrayToBeEncoded = array();
            if (isset($value['data'])) {
                foreach ($value['data'] as $placement => $datas) {
                    foreach ($datas as $k => $oneData) {
                        if ($placement == Widget::WIDGET_LWEB_PLACEMENT_MAIN) {
                            $arrayToBeEncoded[strtolower($placement)]['left'][$k]['label'] = $oneData['title'];
                            $arrayToBeEncoded[strtolower($placement)]['left'][$k]['value'] = $oneData['value_1'];
                            $arrayToBeEncoded[strtolower($placement)]['right'][$k]['label'] = $oneData['title'];
                            $arrayToBeEncoded[strtolower($placement)]['right'][$k]['value'] = $oneData['value_2'];
                        } else {
                            $arrayToBeEncoded[strtolower($placement)][$k]['label'] = $oneData['title'];
                            $arrayToBeEncoded[strtolower($placement)][$k]['value'] = $oneData['value_1'];
                        }
                    }
                }
            }
            $arrayToBeEncoded = Zend_Json::encode($arrayToBeEncoded);
            $rand1 = Utils::getRandomNumber();
            $rand2 = Utils::getRandomNumber();
            $rand3  = Utils::getRandomNumber();
            $valueIsTemp = $value == 'temp';
            $name  = 'images';
            $imgClass    = $valueIsTemp ? '' : 'height-50px';
            $html .='<div class="modal-element to-be-removed">
                        <label class="main-label">' . $translate->_("Option Avatars") . '</label>
                        <div class="main-div">
                            <input data-trigger="ap_' . $rand1 . '" name="lweb[' . $wiod . '][' . $rand3 . '][' . $name . '][1]" id="' . $rand1 . '" type="file" class="list-option-avatar bb pull-left upload-change-it" />
                            <input data-trigger="ap_' . $rand2 . '" name="lweb[' . $wiod . '][' . $rand3 . '][' . $name . '][2]" id="' . $rand2 . '" type="file" class="list-option-avatar bb pull-left upload-change-it m-l-5" />
                            <div class="' . $imgClass . ' list-with-button-upload upload-ap_' . $rand1 . '">';
                            if (!$valueIsTemp) {
                                $path  = APP_URL . '/' . Widget::WIDGET_IMAGES_FOLDER . '/' . $value['image_1'];
                                $html .= '<img src="' . $path . '">';
                                $html .= '<input type="hidden" name="lweb[' . $wiod . '][' . $rand3 . '][temp_images][1]" value="' . $value['image_1'] . '" >';
                            }
            $html .=       '</div>
                            <div class="' . $imgClass . ' list-with-button-upload upload-ap_' . $rand2 . '">';
                            if (!$valueIsTemp) {
                                $path  = APP_URL . '/' . Widget::WIDGET_IMAGES_FOLDER . '/' . $value['image_2'];
                                $html .= '<img src="' . $path . '">';
                                $html .= '<input type="hidden" name="lweb[' . $wiod . '][' . $rand3 . '][temp_images][2]" value="' . $value['image_2'] . '" >';
                            }
            $html .=       '</div>
                            <i data-closest="widget-marker" style="vertical-align: top;' . $style . '" class="fa fa-times remove-item m-l-5 remove-list-with-button-option"></i>
                            <i data-wiod="' . $key . '" data-item="' . $rand3 . '" class="fa fa-cog customize-list-with-edit-button-options"></i>
                            <i class="fa fa-spinner fa-spin customize-list-with-edit-button-options-spinner" style="display:none"></i>
                            <input name="lweb[data][' . $rand3 . ']" class="hidden-json" type="hidden" value="' . htmlentities($arrayToBeEncoded) . '">
                            <div class="clear"></div>
                        </div>
                    </div>';
        }

        $html .= '</div>';

        return $html;
    }
}