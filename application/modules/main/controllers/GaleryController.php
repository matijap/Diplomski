<?php

require_once 'BaseController.php';

class GaleryController extends Main_BaseController
{
    public $galery;
    public $image;

    public function preDispatch() {
        parent::preDispatch();
        $galeryID = Utils::arrayFetch($this->params, 'galeryID', false);
        if ($galeryID) {
            $this->galery         = Main::buildObject('Galery', $this->params['galeryID']);
            $this->view->galeryID = $this->galery->id;
        }
        $imageID = Utils::arrayFetch($this->params, 'imageID', false);
        if ($imageID) {
            $this->image = Main::buildObject('Image', $this->params['imageID']);
            $this->view->imageID = $this->image->id;
        }
    }

    public function indexAction() {
        $watched              = Utils::arrayFetch($this->params, 'watchedID', $this->user->id);
        $watchedUser          = Main::buildObject('User', $watched);
        $this->view->galeries = $watchedUser->getGaleryList();
        $this->view->watched  = $watchedUser;
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
        $this->view->images  = $this->galery->getImageList();
        $this->view->form    = new AddCommentForm(array('isImage' => true));
        $watchedUser         = Main::buildObject('User', $this->galery->user_id);
        $this->view->watched = $watchedUser;
    }

    public function uploadAction() {
        $this->view->fileName = $this->galery->uploadImage();
    }

    public function getImageCommentsAction() {
        $this->view->comments = $this->image->getCommentsForImage($this->user->id);
    }

    public function deleteImageAction() {
        $this->view->form = $form = new Galery_ImageDelete(array('imageID' => $this->params['imageID']));
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $image    = Main::buildObject('Image', $this->params['imageID']);
                $galeryID = $image->galery_id;
                $delete   = $image->delete();
                $msg      = $delete ? $this->translate->_('Image deleted successfully') : $this->translate->_('Problem occured while deleting image');
                $status   = $delete ? Sportalize_Controller_Action::NOTIFICATION_SUCCESS : Sportalize_Controller_Action::NOTIFICATION_ERROR;
                $this->setNotificationMessage($msg, $status);
                $this->_helper->json(array(
                    'status'  => $delete,
                    'url'     => APP_URL . '/galery/galery-image?galeryID=' . $galeryID
                ));
            }
        }
    }
}