<?php

class SearchForm extends Sportalize_Form_Base {

    public $search = '';

    public function __construct($data = array()) {
        $this->search = $data['search'];
        parent::__construct($data);
    }

    public function init() {
        $this->setAction(APP_URL . '/search/index');
        parent::init();
    }
    public function createElements() {
        parent::createElements();
        $this->addElement('text', 'global_search', array(
            'class'       => 'global-search',
            'placeholder' => $this->translate->_('Search for people of pages'),
            'value'       => $this->search
        ));

        $this->addElement('hidden', 'exclude_id', array(
            'value' => $this->user->id,
        ));


        $header =  new Sportalize_Form_Element_PlainHtml('header', array(
            'value' => '<i class="fa fa-search global-search-icon cursor-pointer"></i>'
        ));
        $this->addElement($header);

    }
}