<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_PersonalSettingsFavouritePlayerOrTeam extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formPersonalSettingsFavouritePlayerOrTeam';

    public function isValid($data) {
        $name      = $this->getName();
        $key       = $name == 'favourite_player' ? 'players' : 'teams';


        $data      = Utils::arrayFetch($this->params, 'personal_settings.favourites', false);
        $dataCount = count($data[$key]);
        if ($dataCount > 1) {
            $count = 1;
            foreach ($data[$key] as $value) {
                if (empty($value)) {
                    $this->return = false;
                    $this->_messages[] = $this->translate->_('You must fill all inputs, or remove empty');
                    break;
                }
                if ($count == ($dataCount - 1)) {
                    break;
                }
            }
        }
        return $this->return;
    }
}