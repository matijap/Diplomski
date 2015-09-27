<?php

class AddCommentForm extends Sportalize_Form_Base {
    
    public $pageID   = false;
    public $isImage  = false;

    public function __construct($data = array()) {
        if (isset($data['pageID'])) {
            $this->pageID = $data['pageID'];
        }

        $this->isImage = isset($data['isImage']);
        parent::__construct($data);
    }
    public function init() {
        parent::init();
        $this->setAction(APP_URL . '/comment/submit-comment');
    }

    public function createElements() {
        parent::createElements();

        $commentText = $this->createElement('textarea', 'text', array(
            'required'    => 'required',
            'placeholder' => $this->translate->_('Click to enter comment'),
            'class'       => 'post-comment-textarea bb',
        ));
        $commentText->setRequired(true);
        $this->addElement($commentText);

        $this->addElement('submit', 'submit', array(
            'value' => $this->translate->_('Submit'),
            'class' => 'submit-comment'
        ));
        
        $this->addElement('hidden', 'commenter_id', array('value' => $this->user->id, 'class' => 'form_commenterID'));
        if ($this->pageID) {
            $this->addElement('hidden', 'page_id', array('value' => 7));
        }
        if ($this->isImage) {
            $this->addElement('hidden', 'commented_image_id', array('value' => 0, 'class' => 'form_imageID'));
        } else {
            $this->addElement('hidden', 'commented_post_id', array('value' => 0, 'class' => 'form_postID'));
            $this->addElement('hidden', 'parent_comment_id', array('value' => 0, 'class' => 'form_commentID'));    
        }
    }

    public function redecorate() {
        parent::redecorate();

        foreach ($this->getElements() as $element) {
            $this->clearDecoratorsAndSetViewHelperOnly($element);
        }
    }
}