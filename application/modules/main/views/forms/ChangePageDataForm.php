<?php
class ChangePageDataForm extends Sportalize_Form_Modal {
    public $pagid;

    public function __construct($data = array()) {
        if (isset($data['pageID'])) {
            $this->pagid = $data['pageID'];
        }
        parent::__construct($data);
    }
    public function init() {
        $this->setModalTitle($this->translate->_('Change page data'));
        parent::init();
        $this->setAction(APP_URL . '/page/change-page-data');
    }

    public function createElements() {
        parent::createElements();
        $page = Main::buildObject('Page', $this->pagid);

        $this->addElement('text', 'title', array(
            'value'    => $page->title,
            'label'    => $this->translate->_('Title'),
            'required' => true
        ));
        $this->addElement('text', 'description', array(
            'value'    => $page->description,
            'label'    => $this->translate->_('Description'),
            'required' => true
        ));
        $this->addElement('hidden', 'user_id', array(
            'value' => $this->user->id
        ));
        $logo = new Sportalize_Form_Element_FileUpload( 'logo', array(
            'label' => $this->translate->_('Logo'),
            'file'  => Page::PAGE_IMAGES_FOLDER . '/' . $page->logo,
        ));
        $logo->setMaxFileSize('20');
        $this->addElement($logo);

        $this->addElement('hidden', 'pageID', array(
            'value' => $this->pagid
        )); 

    }
}