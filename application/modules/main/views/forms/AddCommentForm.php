<?php

class AddCommentForm extends Sportalize_Form_Base {
    
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

        $this->addElement('hidden', 'commented_post_id', array('value' => 0, 'class' => 'form_postID'));
        $this->addElement('hidden', 'parent_comment_id', array('value' => 0, 'class' => 'form_commentID'));
        $this->addElement('hidden', 'commenter_id', array('value' => $this->user->id, 'class' => 'form_commenterID'));
    }

    public function redecorate() {
        parent::redecorate();

        foreach ($this->getElements() as $element) {
            $this->clearDecoratorsAndSetViewHelperOnly($element);
        }
    }
}