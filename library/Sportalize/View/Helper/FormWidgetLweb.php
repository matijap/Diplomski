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
            $data = array('temp');
        }
        if (!$wiod) {
            $wiod = 'new';
        }
        // fb($data, 'data');
        $html = '<div class="owls-holder widget-marker sortable-initialize width-95-percent display-inline-block append-into" data-template="list-option-template">';
        $count = count($data);
        $style = $count > 1  ? '' : ' display: none;';
        foreach ($data as $key => $value) {
            $rand1 = Utils::getRandomNumber();
            $rand2 = Utils::getRandomNumber();
            $name  = Utils::getRandomNumber();
            $valueIsTemp = $value == 'temp';

            $imgClass    = $valueIsTemp ? '' : 'height-50px';
            $html .='<div class="modal-element to-be-removed">
                        <label class="main-label">' . $translate->_("Option Avatars") . '</label>
                        <div class="main-div">
                            <input data-trigger="ap_' . $rand1 . '" name="lweb[' . $name . '][]" id="' . $rand1 . '" type="file" class="list-option-avatar bb pull-left upload-change-it" />
                            <input data-trigger="ap_' . $rand2 . '" name="lweb[' . $name . '][]" id="' . $rand2 . '" type="file" class="list-option-avatar bb pull-left upload-change-it m-l-5" />
                            <div class="' . $imgClass . ' list-with-button-upload upload-ap_' . $rand1 . '">';
                            if (!$valueIsTemp) {
                                $html .= '<img src="' . APP_URL . '/' . Widget::WIDGET_IMAGES_FOLDER . '/' . $value['image_1'] . '">';
                            }
            $html .=       '</div>
                            <div class="' . $imgClass . ' list-with-button-upload upload-ap_' . $rand2 . '">';
                            if (!$valueIsTemp) {
                                $html .= '<img src="' . APP_URL . '/' . Widget::WIDGET_IMAGES_FOLDER . '/' . $value['image_2'] . '">';
                            }
            $html .=       '</div>
                            <i data-closest="widget-marker" style="vertical-align: top;' . $style . '" class="fa fa-times remove-item m-l-5 remove-list-with-button-option"></i>
                            <i data-wiod="' . $wiod . '" data-item="456789" class="fa fa-cog customize-list-with-edit-button-options"></i>
                            <i class="fa fa-spinner fa-spin customize-list-with-edit-button-options-spinner" style="display:none"></i>
                            <div class="clear"></div>
                        </div>
                    </div>';
        }

        $html .= '</div>';

        return $html;
    }
}