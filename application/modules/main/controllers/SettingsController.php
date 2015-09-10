<?php

require_once 'BaseController.php';

class SettingsController extends Main_BaseController
{
    public function indexAction() {
        $this->view->form = $form = new PersonalSettingsForm();
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
            }
        }
        // fb($this->params, 'personal settings params');
    }
}