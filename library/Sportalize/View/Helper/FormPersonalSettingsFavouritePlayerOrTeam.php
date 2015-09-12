<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormPersonalSettingsFavouritePlayerOrTeam extends Zend_View_Helper_FormElement
{
    public function formPersonalSettingsFavouritePlayerOrTeam($name, $value = null, $attribs = null, array $options = null)
    {
        $translate = Zend_Registry::getInstance()->Zend_Translate;

        $info = $this->_getInfo($name, $value, $attribs, $options);
        extract($info); // name, value, attribs, options

        $data             = $attribs['data'];
        $selectedPlayersOrTeams  = $attribs['selectedPlayersOrTeams'];
        $title            = $attribs['title'];
        $playerOrTeam     = $attribs['playerOrTeam'];
        $elementName      = $attribs['elementName'];

        $availablePlayersOrTeams = $elementName == 'players' ? Page::getAvailablePlayers() : Page::getAvailableTeams();

        $html ='<label class="display-inline-block">' . $title . '</label>
                <div class="display-inline-block append-into">';
        if (!empty($data[$elementName])) {
            $count     = 1;
            $dataCount = count($data[$elementName]);
            // > 1 because of template input
            if (($dataCount) > 1) {
                foreach ($data[$elementName] as $key => $value) {
                    $displayNone = $dataCount == 2 ? 'display-none' : '';
                    $html .= '<div class="to-be-removed">
                                <input value="' . $value . '" type="text" class="m-t-5" name="personal_settings[favourites][' . $elementName . '][]">
                                <i class="fa fa-times m-l-5 cursor-pointer remove-item ' . $displayNone . '" data-closest="favorite"></i>
                             </div>';
                    if ($count == ($dataCount - 1)) {
                        break;
                    }
                    $count++;
                }
            }
        } else {
            $html .= '<div class="to-be-removed">
                        <input value="" type="text" class="m-t-5" name="personal_settings[favourites][' . $elementName . '][]">
                        <i class="fa fa-times m-l-5 cursor-pointer remove-item display-none" data-closest="favorite"></i>
                    </div>';
        }
        $html .='</div>
                <div class="display-inline-block m-l-10">
                    <i class="fa fa-plus cursor-pointer add-new-item" data-closest="favorite" data-html-template="favorite-' . $elementName . '-template"></i>
                </div>';
        if (count($availablePlayersOrTeams)) {
            $html .= '<p class="m-t-10 m-b-10">' . $translate->_(vsprintf("%s that have page and can be linked%s", array($playerOrTeam, ':'))) . '</p>
                        <select name="personal_settings[favourites][available_' . $elementName . '][]" multiple="multiple" style="width: 100%;">';
            foreach ($availablePlayersOrTeams as $value) {
                if (is_array($selectedPlayersOrTeams)) {
                    $selected = in_array($value['id'], $selectedPlayersOrTeams) ? 'selected' : '';
                } else {
                    $selected = '';
                }
                
                $html .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['title'] . '</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }
}