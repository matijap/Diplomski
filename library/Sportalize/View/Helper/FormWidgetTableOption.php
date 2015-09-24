<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormWidgetTableOption extends Zend_View_Helper_FormElement
{
    public function formWidgetTableOption($name, $value = null, $attribs = null, array $options = null)
    {
        $rand = $attribs['rand'];
        return '<input data-id="' . $rand . '" type="text"
                class="widget-option-and-value bb pull-left" name="table[' . $rand . '][value_1]"
                style="width: 85%;" value="' . $attribs['value_1'] . '" />
                <input type="hidden" name="table[' . $rand . '][value_2]" value="' . htmlentities($attribs['value_2']) . '" >
                <i class="fa fa-cog cursor-pointer"></i>
                <i class="fa fa-spin fa-spinner" style="display:none;"></i>
                <i class="fa fa-times remove-item m-l-10" style="font-size: 15px; vertical-align: middle;"></i>
                <div class="clear"></div>';
    }
}