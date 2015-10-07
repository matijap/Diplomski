<?php

require_once 'BaseController.php';

class PageController extends Main_BaseController
{
    public function newPageAction() {
        $this->view->form = $form = new NewPageForm();

        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $page = Page::create($this->params);
                $this->setNotificationMessage($this->translate->_('Page created successfully'));
                $this->_helper->json(array('status'  => 1,
                                           'url'     => APP_URL . '/page/index?pageID=' . $page->id));
                
            }
        }
    }

    public function indexAction() {
        if (!isset($this->params['pageID'])) {
            $this->setNotificationMessage($this->translate->_('Page id must be set'), Sportalize_Controller_Action::NOTIFICATION_ERROR);
            $this->_redirect('/');
        }
        $page                           = Main::buildObject('Page', $this->params['pageID']);
        $this->view->posts              = $page->getPostsByPage(1, $this->user->id);
        $this->view->form               = new AddCommentForm(array('pageID' => $page->id));
        $this->view->page               = $page;
        $this->view->widgets            = $page->getWidgetList();
        $this->view->watcher            = $this->user;
        $this->view->pageOwner          = $page->getUser();
        $this->view->watcherSameAsOwner = $this->user->id == $this->view->pageOwner->id;
        $this->view->bigLogoChangeForm  = new BigLogoChangeForm(array('pageID' => $page->id));
    }

    public function changePageDataAction() {
        $this->view->form = $form = new ChangePageDataForm(array('pageID' => $this->params['pageID']));
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $page = Main::buildObject('Page', $this->params['pageID']);
                if (isset($_FILES['logo'])) {
                    $fileName             = Utils::uploadFile('logo', Page::PAGE_IMAGES_FOLDER, $page->id);
                    $this->params['logo'] = $fileName;
                }
                $page->edit($this->params);
                $this->setNotificationMessage($this->translate->_('Page edited successfully'));
                $this->_helper->json(array('status'  => 1,
                                           'url'     => APP_URL . '/page/index?pageID=' . $page->id));
            }
        }
    }
}