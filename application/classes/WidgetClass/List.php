<?php


class WidgetClass_List extends WidgetClass_Abstract {
    public function createWidget() {
        // fb($this->params);
        // fb($_FILES);
        foreach ($this->params['list'] as $key => $oneSection) {
            $title = $oneSection['title_' . $key];
            $section = WidgetOption::create(array('type'      => Widget::WIDGET_OPTION_TYPE_LIST,
                                                  'title'     => $title,
                                                  'widget_id' => $this->widget->id,
                                                ));
            if (isset($_FILES['list']['name'][$key])) {
              $fileName = Utils::uploadMultiFiles($key . '.image', Widget::WIDGET_IMAGES_FOLDER, 'list', $this->widget->id . '_' . $section->id);
              fb($fileName);
              // $section->image_1 = $fileName;
              $section->edit(array('image_1' => $fileName[1]));
            }
            foreach ($oneSection as $item => $oneOption) {
                if ($item != 'title_' . $key && $item != 'image') {
                    $option = WidgetOption::create(array('type'                    => Widget::WIDGET_OPTION_TYPE_LIST_OPTION,
                                                         'value_1'                 => $oneOption['value_1'],
                                                         'value_2'                 => $oneOption['value_2'],
                                                         'widget_id'               => $this->widget->id,
                                                         'parent_widget_option_id' => $section->id,
                                                    ));
                }
                
            }
        }
    }
}