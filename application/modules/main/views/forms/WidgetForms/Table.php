<?php

class WidgetForms_Table extends WidgetForms_WidgetSettingsBase {

    public function __construct($data = array()) {
        $this->containerClass = 'position-relative';
        $this->dgClass        = 'widget-table';
        $this->widgetBuilt    = Widget::WIDGET_TYPE_TABLE;
        
        parent::__construct($data);
    }

    public function createElements() {
        $tableOptionsTitle = new Sportalize_Form_Element_PlainHtml( 'table_options_title', array(
            'value' => '<h3>' . $this->translate->_('Table Options') . '</h3>',
        ));
        $this->addElement($tableOptionsTitle);

        $createdElements = array();
        $pickedValues    = '';
        foreach ($this->data as $id => $oneTableData) {
            $rand = Utils::getRandomNumber();
            $option = new Sportalize_Form_Element_WidgetTableOption('table_' . $id, array(
                'value_1'     => $oneTableData['value_1'],
                'value_2'     => $oneTableData['value_2'],
                'label'       => $this->translate->_('Option'),
                'label_class' => 'cursor-pointer',
                'rand'        => $rand,
            ));
            $this->addElement($option);
            $createdElements[] = $option;
            $pickedValues    = $oneTableData['value_2'];
        }
        
        $this->addDisplayGroup($createdElements, 'table_options_dg');

        $plus = new Sportalize_Form_Element_PlainHtml( 'plus', array(
            'value' => '<i class="fa fa-plus m-r-5 add-new-item pull-right add-section" data-html-template="table-option-template" data-closest="div.widget-table"></i>'
        ));
        $this->addElement($plus);

        $tableDataTitle = new Sportalize_Form_Element_PlainHtml( 'table_data_title', array(
            'value' => '<h3>' . $this->translate->_('Table Data') . '</h3>',
        ));
        $this->addElement($tableDataTitle);
        $data = new Sportalize_Form_Element_WidgetTableData('table_data', array(
            'multioptions' => WidgetTableData::getMultioptions($this->user->id),
            'label'        => $this->translate->_('Available data'),
            'separator'    => '',
            'pickedValues' => $pickedValues
        ));
        $this->addElement($data);
        $this->addDisplayGroup(array('table_data'), 'table_data_dg');
    }

    public function redecorate() {
        parent::redecorate();

        $decorator   = $this->getDefaultModalDecorators('cursor-move', '', 'to-be-removed');
        foreach ($this->getElements() as $key => $oneElement) {
            if ($oneElement->getType() == 'Sportalize_Form_Element_WidgetTableOption') {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
        }
        $decorator = array(
               'FormElements',
                array('HtmlTag', array('tag'   =>'div','class'  => 'append-into sortable-initialize'))
            );
        $tableOptionDisplayGroup = $this->getDisplayGroup('table_options_dg');
        $this->clearDecoratorsAndSetDecorator($tableOptionDisplayGroup, $decorator);

        $decorator = array(
               'FormElements',
                array('HtmlTag', array('tag'   =>'div','id'  => 'widget-table-data'))
            );
        $tableDataDisplayGroup = $this->getDisplayGroup('table_data_dg');
        $this->clearDecoratorsAndSetDecorator($tableDataDisplayGroup, $decorator);
        
    }
}