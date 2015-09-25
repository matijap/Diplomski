<?php

require_once 'BaseController.php';

class GaleryController extends Main_BaseController
{
    public function indexAction() {
        $this->view->galeries = $this->user->getGaleryList();
    }

    public function deleteGaleryAction() {
        $this->view->form = $form = new Galery_Delete(array('galeryID' => $this->params['galeryID']));
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $galery = Main::buildObject('Galery', $this->params['galeryID']);
                $galery->delete();
                $this->setNotificationMessage($this->translate->_('Galery deleted successfully'));
                $this->_helper->json(array(
                    'status'  => 1,
                    'url'     => APP_URL . '/galery/index'
                ));
            }
        }
    }

    public function createGaleryAction() {
        $this->view->form = $form = new Galery_Create();
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                Galery::create($this->params);
                $this->setNotificationMessage($this->translate->_('Galery created successfully'));
                $this->_helper->json(array(
                    'status'  => 1,
                    'url'     => APP_URL . '/galery/index'
                ));
            }
        }
    }
    public function galeryImageAction() {
    }

    public function uploadAction() {
        Galery::uploadImage();
    }
}