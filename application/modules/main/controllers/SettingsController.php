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
                    $user->updateOtherData($this->params['other']);

                    $this->setNotificationMessage($this->translate->_('Personal settings saved successfully.'));
                    $this->_redirect(APP_URL . '/settings/index');
                }
            }
        }
    }
}