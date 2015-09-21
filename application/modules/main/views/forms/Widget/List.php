<?php

class Widget_List extends Widget_WidgetSettingsBase {

    public function __construct($data = array()) {
        $this->containerClass = 'append-into sortable-initialize';
        $this->dgClass        = 'widget-list sortable-initialize';
        $this->widgetBuilt    = Widget::WIDGET_TYPE_LIST;
        
        parent::__construct($data);
    }

    public function createElements() {
        $data = array();
        if ($this->widgetID) {
            $widget = Main::buildObject('Widget', $this->widgetID);
            if ($widget->type == $this->widgetBuilt) {
                $data   = $widget->getDataForListWidget();
            }
        }
        if (!count($data)) {
            $rand                   = Utils::getRandomNumber();
            $rand2                  = Utils::getRandomNumber();
            $data[$rand]['title']   = '';
            $data[$rand]['image']   = '';
            $data[$rand]['options'] = array($rand2 => array('value_1' => '',
                                                            'value_2' => ''));
        }
        $createdElements = array();
        $count           = count($data);
        foreach ($data as $sectionID => $oneListSection) {
            $title = $this->createElement('text', 'title_' . $sectionID, array(
                'label'     => $this->translate->_('List section title'),
                'value'     => $oneListSection['title'],
                'belongsTo' => 'list[' . $sectionID . ']',
                'data-id'   => $sectionID,
            ));
            $this->addElement($title);

            $createdElements[$sectionID][] = $title;

            $fileUpload = new Sportalize_Form_Element_FileUpload( 'image', array(
                'label'     => $this->translate->_('List section avatar'),
                'file'      => $oneListSection['image'],
                'belongsTo' => 'list[' . $sectionID . ']',
            ));
            $fileUpload->setMaxFileSize();
            $this->addElement($fileUpload);
            $createdElements[$sectionID][] = $fileUpload;

            foreach ($oneListSection['options'] as $optionID => $oneOption) {
                $options = new Sportalize_Form_Element_WidgetList('options_' . $sectionID . '_' . $optionID, array(
                    'data'        => $oneOption,
                    'label'       => $this->translate->_('List Option'),
                    'optionID'    => $optionID,
                    'sectionID'   => $sectionID,
                    'printRemove' => $count > 1
                ));
                $this->addElement($options);
                $createdElements[$sectionID][] = $options;
            }
            
            $plus = new Sportalize_Form_Element_PlainHtml( 'plus_' . $sectionID, array(
            'value' => '<i class="fa fa-plus m-r-5 add-new-item" style="vertical-align: top;" data-html-template="list-option-template" data-closest="div.one-widget-list-section"></i>'
            ));
            $this->addElement($plus);
            $style = $count > 1  ? '' : 'style="display: none;"';
            $remove = new Sportalize_Form_Element_PlainHtml( 'remove_' . $sectionID, array(
                'value' => '<i data-closest="div.widget-list" class="fa fa-times remove-item remove-list-section" ' . $style . '></i>'
            ));
            $this->addElement($remove);
            $createdElements[$sectionID][] = $plus;
            $createdElements[$sectionID][] = $remove;
            
            $this->addElement($options);
        }
        foreach ($createdElements as $key => $value) {
            $this->addDisplayGroup($value, 'dg_' . $key);
        }
    }

    public function redecorate() {
        parent::redecorate();

        $decorator   = $this->getDefaultModalDecorators('', '', 'to-be-removed');
        $decorator[] = array(array('b' => 'HtmlTag'), array('tag' => 'div', 'class' => 'owls-holder widget-marker sortable-initialize width-95-percent display-inline-block append-into'));
        foreach ($this->getElements() as $key => $oneElement) {
            if ($oneElement->getType() == 'Sportalize_Form_Element_WidgetList') {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
        }

        $this->redecorateFileUpload();
    }
}