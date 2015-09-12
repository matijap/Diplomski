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
                }
                // fb('forma je validna');
                // fb($this->params, 'personal settings params');
            }
        }
    }
}