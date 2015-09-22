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
        foreach ($data as $sectionID => $oneListSection) {
            $count           = count($oneListSection['options']);
            $title = $this->createElement('text', 'title_' . $sectionID, array(
                'label'     => $this->translate->_('List section title'),
                'value'     => $oneListSection['title'],
                'belongsTo' => 'list[' . $sectionID . ']',
                'data-id'   => $sectionID,
            ));
            $this->addElement($title);

            $createdElements[$sectionID][] = $title;

            $fileUpload = new Sportalize_Form_Element_FileUpload( 'image', array(
                'label'      => $this->translate->_('List section avatar'),
                'file'       => $oneListSection['image'],
                'belongsTo'  => 'list[' . $sectionID . ']',
                'customID'   => $sectionID
            ));
            $fileUpload->setMaxFileSize();

            $this->addElement($fileUpload);
            $createdElements[$sectionID][] = $fileUpload;

            if (empty($oneListSection['options'])) {
                $oneListSection['options'] = array(Utils::getRandomNumber() => array('value_1' => '',
                                                                                     'value_2' => ''));
            }
            $tag1 = new Sportalize_Form_Element_PlainHtml( 'tag_1_' . $sectionID, array(
                'value' => '<div class="owls-holder widget-marker sortable-initialize width-95-percent display-inline-block append-into">'
            ));
            $this->addElement($tag1);
            $createdElements[$sectionID][] = $tag1;

            foreach ($oneListSection['options'] as $optionID => $oneOption) {
                $options = new Sportalize_Form_Element_WidgetList('options_' . $sectionID . '_' . $optionID, array(
                    'data'        => $oneOption,
                    'label'       => $this->translate->_('List Option'),
                    'optionID'    => $optionID,
                    'sectionID'   => $sectionID,
                    'printRemove' => $count > 1
                ));
                $createdElements[$sectionID][] = $options;
            }

            $tag2 = new Sportalize_Form_Element_PlainHtml( 'tag_2_' . $sectionID, array(
                'value' => '</div>'
            ));
            $this->addElement($tag2);
            $createdElements[$sectionID][] = $tag2;
            
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
        }
        foreach ($createdElements as $key => $value) {
            $this->addDisplayGroup($value, 'dg_' . $key);
        }
    }

    public function redecorate() {
        parent::redecorate();

        $decorator   = $this->getDefaultModalDecorators('', '', 'to-be-removed');
        foreach ($this->getElements() as $key => $oneElement) {
            if ($oneElement->getType() == 'Sportalize_Form_Element_WidgetList') {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
        }
        foreach ($this->getSubforms() as $key => $oneSubform) {
            $decorator = array(
                'FormElements',
                array(array('b' => 'HtmlTag'), array('tag'  => 'div', 'class' => '')),
            );
            $this->clearDecoratorsAndSetDecorator($oneSubform, $decorator);
            foreach ($oneSubform->getElements() as $oneElement) {
                if ($oneElement->getType() == 'Sportalize_Form_Element_WidgetList') {
                    $this->clearDecoratorsAndSetDecorator($oneElement, $this->getDefaultModalDecorators('', '', 'to-be-removed'));
                }
            }
        }
        $this->redecorateFileUpload();
    }
}