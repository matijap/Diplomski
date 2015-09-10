<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_PersonalSettingsFavouritePlayer extends Zend_Form_Element_Xhtml
{
    public $helper = 'formPersonalSettingsFavouritePlayer';

    public function isValid($data) {
        $this->_messages = array();
        $translate       = Zend_Registry::getInstance()->Zend_Translate;
        $return          = true;
        $request         = Zend_Controller_Front::getInstance()->getRequest();
        $params          = $request->getParams();

        $data      = Utils::arrayFetch($params, 'personal_settings.favourites', false);
        $dataCount = count($data['players']);
        if ($dataCount > 1) {
            $count = 1;
            foreach ($data['players'] as $key => $value) {
                if (empty($value)) {
                    $return = false;
                    $this->_messages[] = $translate->_('You must fill all inputs, or remove empty');
                    break;
                }
                if ($count == ($dataCount - 1)) {
                    break;
                }
            }
        }
        return $return;
    }
}