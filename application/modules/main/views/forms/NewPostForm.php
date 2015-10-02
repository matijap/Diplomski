<?php

class NewPostForm extends Sportalize_Form_Modal {

    public function init() {
        $this->setAction(APP_URL . '/post/new-post');
        $this->setModalTitle($this->translate->_('Create New Post'));
        parent::init();
    }

    public function createElements() {
        parent::createElements();

        $param = Utils::arrayFetch($this->params, 'post_type', false);

        $multioptions = Post::getPostTypeMultioptions();
        $this->addElement('select', 'post_type', array(
            'label'        => $this->translate->_('Type'),
            'multioptions' => $multioptions,
            'class'        => 'width-100px'
        ));

        $userPages = $this->user->getUserPages();
        if(!empty($userPages)) {
            $multioptions = array();
            $multioptions['_E_'] = $this->translate->_('Pick page');
            foreach ($userPages as $key => $value) {
                $multioptions[$key] = $value;
            }
            $this->addElement('select', 'page_id', array(
                'label'        => $this->translate->_('Or post as a page'),
                'multioptions' => $multioptions,
                'class'        => 'width-100px'
            ));
        }

        $this->addElement('text', 'title', array(
            'label' => $this->translate->_('Title')
        ));

        $this->addElement('textarea', 'text', array(
            'label'    => $this->translate->_('Text'),
            'rows'     => 5,
            'required' => true,
        ));
        
        $class = $param == Post::POST_TYPE_IMAGE ? '' : 'hide-modal-parent';
        $this->addElement('text', 'image_url', array(
            'label' => $this->translate->_('Image URL'),
            'class' => $class,
        ));

        $fileUpload = new Sportalize_Form_Element_FileUpload( 'image_upload', array(
            'label' => $this->translate->_('Upload Image'),
            'class' => $class,
        ));
        $fileUpload->setMaxFileSize();
        $this->addElement($fileUpload);

        $class = $param == Post::POST_TYPE_VIDEO ? '' : 'hide-modal-parent';
        $this->addElement('text', 'video', array(
            'label' => $this->translate->_('YouTube URL'),
            'class' => $class,
        ));

        $this->getUserIDElement();
    }

    public function isValid($data) {
        $ok = parent::isValid($data);
        if (!isset($_FILES['image_upload']) && empty($data['image_url']) && $data['post_type'] == Post::POST_TYPE_IMAGE) {
            $this->getElement('image_upload')->addError($this->translate->_('You must eiter upload image or enter url'));
            $ok = false;
        }
        if ($data['post_type'] == Post::POST_TYPE_VIDEO) {
            $id = Utils::getYoutubeIdFromUrl($data['video']);
            if (!$id) {
                $this->getElement('video')->addError($this->translate->_('Invalid URL'));
                $ok = false;
            }
        }
        return $ok;
    }
}