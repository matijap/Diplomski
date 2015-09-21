<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormWidgetList extends Zend_View_Helper_FormElement
{
    public function formWidgetList($name, $value = null, $attribs = null, array $options = null)
    {
        $data      = $attribs['data'];
        $optionID  = $attribs['optionID'];
        $sectionID = $attribs['sectionID'];
        $html  = '<input name="list[' . $sectionID . '][' . $optionID . '][value_1]" type="text" data-id="' . $sectionID . '" class="widget-option-and-value bb pull-left" value="' . $data['value_1'] . '">';
        $html .= '<input name="list[' . $sectionID . '][' . $optionID . '][value_2]" type="text" data-id="' . $sectionID . '" class="widget-option-and-value bb pull-left" value="' . $data['value_2'] . '">';
        $style = '';
        if ($attribs['printRemove']) {
            $style = 'style="display:block"';
        }
        $html .= '<i data-closest="widget-marker" class="fa fa-times remove-item m-l-5 display-none" ' . $style . '></i>';

        $html .= '<div class="clear"></div>';
        
        return $html;
    }
}