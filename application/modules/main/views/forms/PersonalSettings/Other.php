<?php

class PersonalSettings_Other extends PersonalSettings_PersonalSettingsBaseForm {
    
    public function __construct() {
        $this->class = '';
        parent::__construct();
    }
    public function createElements() {
        parent::createElements();

        $this->addTitleElement($this->translate->_('Other'));
        $userInfo = $this->user->getUserInfo();
        $this->addElement('select', 'language', array(
            'multioptions' => Language::getMultiOptions(),
            'label'        => $this->translate->_('Language'),
            'class'        => 'width-200px',
            'belongsTo'    => 'other',
            'value'        => $userInfo->language_id
        ));

        $this->addDisplayGroup(array('language'), 'language_dg');
    }

    public function redecorate() {
        parent::redecorate();

        $decorator = array(
            'ViewHelper',
            'Description',
            'Errors',
            'Label',
        );
        
        foreach ($this->getElements() as $key => $element) {
            $this->clearDecoratorsAndSetDecorator($element, $decorator);
        }
    }
}