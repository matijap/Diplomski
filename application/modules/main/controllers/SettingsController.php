<?php

require_once 'BaseController.php';

class SettingsController extends Main_BaseController
{
    public function indexAction() {
        $this->view->form = $form = new PersonalSettingsForm();
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $user = Main::buildObject('User', $this->params['userID']);
                if ($user) {
                    $user->updateFavouritesAndDreamTeam($this->params);
                    $user->updatePersonalInfo($this->params['personal_info']);
                    $user->updatePrivacySettings($this->params['privacy_settings']);

                    //hacky solution, because file upload is performed only after validation. so form will
                    //have avatar image set to one before upload occured
                    // also will handle if country is changed, so will display proper date format
                    $loc = $this->setLocParam();
                    $this->setDatePickerFormat($loc);
                    $this->view->form = new PersonalSettingsForm();
                    $this->setNotificationMessage($this->translate->_('Personal settings saved successfully.'));
                }
            }
        }
    }
}