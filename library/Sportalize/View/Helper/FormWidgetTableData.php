<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormWidgetTableData extends Zend_View_Helper_FormElement
{
    public function formWidgetTableData($name, $value = null, $attribs = null, array $options = null)
    {
        $pickedValues    = Zend_Json::decode($attribs['pickedValues']);
        $html            = '';
        $loggedUser      = Zend_Registry::get('logged_user');
        $userCreatedData = WidgetTableData::getUserCreatedData($loggedUser->id);

        foreach ($attribs['multioptions'] as $short => $long) {
            $translatedShort = WidgetTableData::translateShort($short);
            $checked         = '';
            if (is_array($pickedValues)) {
                $checked         = array_key_exists($short, $pickedValues) ? 'checked="checked"' : '';    
            }
            $style           = 'style="opacity: 0"';
            $id              = '';
            foreach ($userCreatedData as $key => $value) {
                if ($value['short']  == $short && $value['long'] == $long) {
                    $style           = 'style="opacity: 1"';
                    $id              = $value['id'];
                }
            }
            $html .='<label>
                        ' . $long . ' (' . $translatedShort . ')
                       <i data-id="' . $id . '" class="fa fa-times cursor-pointer remove-table-data" ' . $style . '></i>
                       <input type="checkbox" ' . $checked . ' data-placeholder="' . $translatedShort . '" data-original-short="' . $short . '">
                    </label>';
        }
        return $html;
    }
}