<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_PersonalSettingsDreamTeam extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formPersonalSettingsDreamTeam';

    public function isValid($data) {
        $name     = $this->getName();
        $exploded = explode('_', $name);
        $count    = count($exploded);
        $id       = $exploded[($count - 1)];

        $dreamTeams = Utils::arrayFetch($this->params, 'personal_settings.dream_team.' . $id, false);
        if ($dreamTeams) {
            foreach ($dreamTeams['data'] as $key => $value) {
                if (empty($value)) {
                    $this->_messages[] = $this->translate->_('You must fill all inputs');
                    $this->return      = false;
                    break;
                }
            }
        } else {
            $this->_messages[] = $this->translate->_('You must fill all inputs');
            $this->return      = false;
        }
        return $this->return;
    }
}