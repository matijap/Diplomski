<?php

class PersonalSettings_PersonalSettingsBaseForm extends Sportalize_Form_Base {
    
    public $class = '';

    public function init() {
        parent::init();

        $this->setDisableLoadDefaultDecorators(true);
        
        $decorator = array(
            'FormElements',
            array('HtmlTag', array('tag'  => 'div', 'class' => 'main-background-color one-personal-settings-section ' . $this->class)),
        );

        $this->clearDecorators()->setDecorators($decorator);
    }

    public function redecorate() {
        parent::redecorate();
        foreach ($this->getDisplayGroups() as $key => $displayGroup) {
            $this->clearDecoratorsAndSetDecorator($displayGroup, array(
                'FormElements',
                array('HtmlTag', array('tag'   =>'div','class'  => 'personal-settings-container favorite'))
            ));
        }
        foreach ($this->getElements() as $key => $element) {
            $this->clearDecoratorsAndSetDecorator($element, array($this->getViewHelperDecorator(), 'Errors'));
        }
    }

    public function addTitleElement($titleMessage, $class = '') {
        $title = new Sportalize_Form_Element_PlainHtml('title', array(
            'value' => '<h2 class="widget-header-color ' . $class . '">' . $titleMessage . '</h2>'
        ));
        $this->addElement($title);
    }
}