<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormWidgetTableNewData extends Zend_View_Helper_FormElement
{
    public function formWidgetTableNewData($name, $value = null, $attribs = null, array $options = null)
    {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return '<input placeholder="' . $translate->_('Official data name') . '" type="text" class="bb pull-left" id="widget-table-data-full" 
                                style="width: 70%;" />
                <input placeholder="' . $translate->_('Short data name') . '" type="text" class="bb pull-left" id="widget-table-data-short" 
                style="width: 20%;" />
                <i class="fa fa-plus m-l-5 cursor-pointer add-new-widget-table-data"></i>
                <div class="clear"></div>';
    }
}