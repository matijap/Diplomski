<?php
class FavoritesSearchForm extends Sportalize_Form_Base {

    public $formType;
    public $searchParams;

    public function __construct($data = array()) {
        $this->formType     = $data['type'];
        $this->searchParams = $data['searchParams'];
        parent::__construct($data);
    }
    public function init() {
        parent::init();
        $this->setAction(APP_URL . '/favorites/index');
    }

    public function createElements() {
        parent::createElements();
        $action       = 'get' . ucfirst($this->formType) . 'Multioptions';
        $searchType   = Utils::arrayFetch($this->searchParams, 'search_type', false);
        $multioptions = $this->$action();

        $select = $this->createElement('select', 'sort', array(
            'multioptions' => $multioptions,
            'class'        => 'width-200px add-dream-team-sport'
        ));
        if ($searchType == $this->formType) {
            $sort   = Utils::arrayFetch($this->searchParams, 'sort', false);
            if ($sort) {
                $select->setValue($sort);
            }
        }
        $this->addElement($select);


        $placeholder = $this->formType == 'post' ? $this->translate->_('Search post content, title or author') : $this->translate->_('Search comment text or author');
        $text = $this->createElement('text', 'filter', array(
            'class'       => 'search-favourite-content',
            'placeholder' => $placeholder,
        ));
        if ($searchType == $this->formType) {
            $filter = Utils::arrayFetch($this->searchParams, 'filter', false);
            if ($filter) {
                $text->setValue($filter);
            }
        }
        $this->addElement($text);

        $this->addElement('submit', 'submit', array(
            'class' => 'blue-button',
            'label' => $this->translate->_('Search')
        ));
        $this->addElement('hidden', 'search_type', array(
            'value' => $this->formType
        ));
    }

    public function getPostMultiOptions() {
        $ar = $this->getCommentMultiOptions();
        $ar['title asc']  = $this->translate->_('Title (ascending)');
        $ar['title desc'] = $this->translate->_('Title (descending)');
        return $ar;
    }

    public function getCommentMultiOptions() {
        return array(
            'date asc'        => $this->translate->_('Date (ascending)'),
            'date desc'       => $this->translate->_('Date (descending)'),
            'first_name asc'  => $this->translate->_('Author First Name (ascending)'),
            'last_name asc'   => $this->translate->_('Author Last Name (ascending)'),
            'first_name desc' => $this->translate->_('Author First Name (descending)'),
            'last_name desc'  => $this->translate->_('Author Last Name (descending)'),
        );
    }

    public function redecorate() {
        parent::redecorate();

        foreach ($this->getElements() as $oneElement) {
            $this->clearDecoratorsAndSetViewHelperOnly($oneElement);
        }
    }
}
