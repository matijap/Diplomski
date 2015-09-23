<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormWidgetTableOption extends Zend_View_Helper_FormElement
{
    public function formWidgetTableOption($name, $value = null, $attribs = null, array $options = null)
    {
        return '<input type="text" class="widget-option-and-value bb pull-left" name="list_option_title_1"
                        id="list-option-title-1" style="width: 85%;" />
                
                <i class="fa fa-cog cursor-pointer"></i>
                <i class="fa fa-spin fa-spinner" style="display:none;"></i>
                <i class="fa fa-times remove-section remove-list-option"></i>
                <div class="clear"></div>';
    }
}