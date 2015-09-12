<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_FileUpload extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formFileUpload';

    public function init() {
        parent::init();

        $validator = new Zend_Validate_File_IsImage();
        $validator->setMessage($this->translate->_(vsprintf('Accepted formats are %s, %s and %s', array('JPG', 'PNG', 'GIF'))), Zend_Validate_File_IsImage::FALSE_TYPE);
        $this->addValidator($validator);

        $validator = new Zend_Validate_File_Size(array(
            'min' => '1kb', 'max' => '5kb'
        ));
        $this->addValidator($validator);
    }

    public function isValid($data) {
        $return = true;
        $name   = $this->getName();
        if ($this->isRequired() && !isset($_FILES[$name])) {
            $this->_messages[] = $translate->_('You must upload a file');
            $return = false;
        }
        if (!isset($_FILES[$name])) {
            $return = true;
        } else {
            if ($return && !empty($_FILES[$name]['name'])) {
                foreach ($this->getValidators() as $key => $validator) {
                    if (!$validator->isValid($_FILES[$name]['tmp_name'])) {
                        $msgs = $validator->getMessages();
                        $this->_messages = array_merge($this->_messages, $validator->getMessages());
                        $return = false;
                    }
                }
            }
        }
        
        return $return;
    }

    public function setMaxFileSize($max = '50', $unit = 'kb') {
        $validator = $this->getValidator('Zend_Validate_File_Size');
        $validator->setMax($max . $unit);
    }

    public function setMinFileSize($min = '1', $unit = 'kb') {
        $validator = $this->getValidator('Zend_Validate_File_Size');
        $validator->setMax($min . $unit);
    }
}