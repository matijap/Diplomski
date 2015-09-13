<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormPersonalSettingsPrivacy extends Zend_View_Helper_FormElement
{
    public function formPersonalSettingsPrivacy($name, $value = null, $attribs = null, array $options = null)
    {   
        $multioptions = PrivacySetting::getPrivacySettingsMultioptions();
        $user         = Zend_Registry::get('logged_user');
        $friends      = $user->getFriendList(false);
        $groups       = $user->getFriendGroups();

        $friendsMultioptions = array();
        foreach ($friends as $key => $value) {
            $friendsMultioptions[$value['id']] = $value['first_name'] . ' ' . $value['last_name'];
        }
        
        $html = ' <label>' . $attribs['title'] . '</label>
                  <select name="privacy_settings[' . $name . '][visibility]" class="' . $attribs['visibility'] .'-visibility">';
        foreach ($multioptions as $key => $value) {
            $html .= '<option value="' . $key . '">' . $value . '</option>';
        }
        $html .= '</select>
                  <div class="width-100-percent m-t-10 privacy-friends-list" style="display: none;">
                    <div class="width-200px float-right">
                        <select name="privacy_settings[' . $name . '][friend_list][]" multiple="multiple" class="width-100-percent">';
        foreach ($groups as $key => $value) {
            $html .= '<option value="' . $key . '">' . $value . '</option>';
        }
        $html .= '      </select>
                    </div>
                  </div>
                  <div class="width-100-percent m-t-10 privacy-friends" style="display: none;">
                    <div class="width-200px float-right">
                        <select name="privacy_settings[' . $name . '][specific_friends][]" multiple="multiple" class="width-100-percent">';
        foreach ($friendsMultioptions as $key => $value) {
            $html .= '<option value="' . $key. '">' . $value . '</option>';
        }
        $html .= '      </select>
                    </div>
                  </div>';
      
        return $html;
    }
}