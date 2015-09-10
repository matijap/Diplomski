<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormPersonalSettingsFavouritePlayer extends Zend_View_Helper_FormElement
{
    public function formPersonalSettingsFavouritePlayer($name, $value = null, $attribs = null, array $options = null)
    {
        $translate = Zend_Registry::getInstance()->Zend_Translate;

        $info = $this->_getInfo($name, $value, $attribs, $options);
        extract($info); // name, value, attribs, options

        $data             = $attribs['data'];
        $selectedPlayers  = $attribs['selectedPlayers'];

        $availablePlayers = Page::getAvailablePlayers();

        $html ='<label class="display-inline-block">' . $translate->_("Favourite Player") . '</label>
                <div class="display-inline-block append-into">';
        if (!empty($data['players'])) {
            $count     = 1;
            $dataCount = count($data['players']);
            // > 1 because of template input
            if (($dataCount) > 1) {
                foreach ($data['players'] as $key => $value) {
                    $html .= '<div class="to-be-removed">
                                <input value="' . $value . '" type="text" class="m-t-5" name="personal_settings[favourites][players][]">
                                <i class="fa fa-times m-l-5 cursor-pointer remove-item" data-closest="favorite"></i>
                             </div>';
                    if ($count == ($dataCount - 1)){
                        break;
                    }
                    $count++;
                }
            }
        }
        $html .='</div>
                <div class="display-inline-block m-l-10">
                    <i class="fa fa-plus cursor-pointer add-new-item" data-closest="favorite" data-html-template="favorite-player-template"></i>
                </div>';
        if (count($availablePlayers)) {
            $html .= '<p class="m-t-10 m-b-10">' . $translate->_("Players that have page and can be linked:") . '</p>
                        <select name="personal_settings[favourites][available_players][]" multiple="multiple" style="width: 100%;">';
            foreach ($availablePlayers as $value) {
                $selected = in_array($value['id'], $selectedPlayers) ? 'selected' : '';
                $html .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['title'] . '</option>';
            }
            $html .= '</select>';
        }
        $html .='<div class="favorite-player-template display-none">
                    <div class="to-be-removed">
                        <input type="text" class="m-t-5" name="personal_settings[favourites][players][]">
                        <i class="fa fa-times m-l-5 cursor-pointer remove-item" data-closest="favorite"></i>
                    </div>
                </div>';
        
        return $html;
    }
}