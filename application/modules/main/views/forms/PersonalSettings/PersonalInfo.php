<?php

class PersonalSettings_PersonalInfo extends PersonalSettings_PersonalSettingsBaseForm {

    public function createElements() {
        parent::createElements();

        $this->addTitleElement($this->translate->_('Personal Information'));

        $userInfo = $this->user->getUserInfoList();

        $this->addElement('text', 'first_name', array(
            'label'     => $this->translate->_('First name'),
            'required'  => true,
            'belongsTo' => 'personal_info',
            'value'     => $userInfo[0]->first_name,
        ));
        $this->addDisplayGroup(array('first_name'), 'first_name_dg');

        $this->addElement('text', 'last_name', array(
            'label'     => $this->translate->_('Last name'),
            'required'  => true,
            'belongsTo' => 'personal_info',
            'value'     => $userInfo[0]->last_name,
        ));
        $this->addDisplayGroup(array('last_name'), 'last_name_dg');

        $email = $this->createElement('text', 'email', array(
            'label'     => $this->translate->_('Email'),
            'required'  => true,
            'belongsTo' => 'personal_info',
            'value'     => $this->user->email,
        ));
        $this->addElement($email);
        $validator = new Zend_Validate_EmailAddress();
        $message   = $this->translate->_('Invalid email format');
        $validator->setMessage($message, 'emailAddressInvalidFormat');
        $validator->setMessage($message, 'emailAddressInvalidHostname');

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

        $dob    = $userInfo[0]->date_of_birth;
        $locale = Zend_Registry::get('Zend_Locale');
        $date   = new Zend_Date($dob, Zend_Date::TIMESTAMP, $locale);
        $list   = Zend_Locale::getTranslationList('Date', $this->loc);
        $this->addElement('text', 'date_of_birth', array(
            'label'     => $this->translate->_('Date Of Birth'),
            'class'     => 'datepicker',
            'belongsTo' => 'personal_info',
            'value'     => $date->get($list['short'])
        ));
        $this->addDisplayGroup(array('date_of_birth'), 'date_of_birth_dg');

        $this->addElement('text', 'phone', array(
            'label'     => $this->translate->_('Phone'),
            'belongsTo' => 'personal_info',
            'value'     => $userInfo[0]->phone,
        ));
        $this->addDisplayGroup(array('phone'), 'phone_dg');

        $this->addElement('text', 'city', array(
            'label'     => $this->translate->_('City'),
            'belongsTo' => 'personal_info',
            'value'     => $userInfo[0]->city,
        ));
        $this->addDisplayGroup(array('city'), 'city_dg');

        $this->addElement('select', 'country_id', array(
            'multioptions' => Country::getMultioptions(),
            'belongsTo'    => 'personal_info',
            'value'        => $userInfo[0]->country_id,
        ));
        $this->addDisplayGroup(array('country_id'), 'country_dg');

        if (!empty($userInfo[0]->avatar)) {
            $fileName = UserInfo::AVATAR_IMAGES_FOLDER . '/' . $userInfo[0]->avatar;
        }
        $avatar = new Sportalize_Form_Element_FileUpload( 'avatar', array(
            'label' => $this->translate->_('Avatar'),
            'file'  => $fileName,
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