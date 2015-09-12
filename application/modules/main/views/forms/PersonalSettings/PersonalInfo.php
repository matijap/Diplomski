<?php

class PersonalSettings_PersonalInfo extends PersonalSettings_PersonalSettingsBaseForm {

    public function __construct() {
        $this->class   = 'dream-team';
        
        parent::__construct();
    }

    public function createElements() {
        parent::createElements();

        $this->addTitleElement($this->translate->_('Personal Information'));

        $this->addElement('text', 'first_name', array(
            'label'     => $this->translate->_('First name'),
            'required'  => true,
            'belongsTo' => 'personal_info'
        ));
        $this->addDisplayGroup(array('first_name'), 'first_name_dg');

        $this->addElement('text', 'last_name', array(
            'label' => $this->translate->_('Last name'),
            'required' => true,
            'belongsTo' => 'personal_info',
        ));
        $this->addDisplayGroup(array('last_name'), 'last_name_dg');

        $email = $this->createElement('text', 'email', array(
            'label' => $this->translate->_('Email'),
            'required' => true,
            'belongsTo' => 'personal_info'
        ));
        $this->addElement($email);
        $validator = new Zend_Validate_EmailAddress();
        $message = $this->translate->_('Invalid email format');
        $validator->setMessage($message, 'emailAddressInvalidFormat');
        $validator->setMessage($message, 'emailAddressInvalidHostname');
            
        // $email->addValidator($validator, false);
        $email->addValidator('Db_NoRecordExists', true,
            array(
                'table'     => 'user',
                'field'     => 'email',
                'exclude' => array(
                      'field' => 'id',
                      'value' => isset($this->user->id) ? $this->user->id : ''
                    ),
                'messages' => $this->translate->_("User with existing email already exist")
            ));
        $this->addDisplayGroup(array('email'), 'email_dg');

        $this->addElement('text', 'date_of_birth', array(
            'label' => $this->translate->_('Date Of Birth'),
            'class' => 'datepicker',
            'belongsTo' => 'personal_info'
        ));
        $this->addDisplayGroup(array('date_of_birth'), 'date_of_birth_dg');

        $this->addElement('text', 'phone', array(
            'label' => $this->translate->_('Phone'),
            'belongsTo' => 'personal_info'
        ));
        $this->addDisplayGroup(array('phone'), 'phone_dg');

        $this->addElement('text', 'city', array(
            'label' => $this->translate->_('City'),
            'belongsTo' => 'personal_info'
        ));
        $this->addDisplayGroup(array('city'), 'city_dg');

        $this->addElement('select', 'country_id', array(
            'multioptions' => Country::getMultioptions(),
            'belongsTo' => 'personal_info'
        ));
        $this->addDisplayGroup(array('country_id'), 'country_dg');

        $avatar = new Sportalize_Form_Element_FileUpload( 'avatar', array(
            'label' => $this->translate->_('Avatar'),
            
        ));
        $avatar->setMaxFileSize('20');
        $this->addElement($avatar);
        $this->addDisplayGroup(array('avatar'), 'avatar_dg');
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

        $dg = $this->getDisplayGroup('avatar_dg');
        $this->clearDecoratorsAndSetDecorator($dg, array(
                'FormElements',
                array('HtmlTag', array('tag'   =>'div','class'  => 'personal-settings-container avatar-dg'))
            ));
    }
}