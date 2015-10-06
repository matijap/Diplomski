<?php
class AddFriendIntoListForm extends Sportalize_Form_Base {

    public $lid;

    public function __construct($data = array()) {
        $this->lid = $data['listID'];
        parent::__construct();
    }
    public function init() {
        parent::init();
    }

    public function createElements() {
        parent::createElements();
        $friends = $this->user->getFriendList(false);
        $multioptions = $this->processFriendsMultioptions($friends);
        $this->addElement('select', 'all_friends_list', array(
            'multiple'    => 'multiple',
            'multioptions' => $multioptions,
            'class'       => 'display-inline-block width-200px',
        ));
        $this->addElement('hidden', 'listID', array(
            'value' => $this->lid,
            'class' => 'friend-list-id'
        ));
        $button = new Sportalize_Form_Element_PlainHtml('button', array(
            'value' => '<a class="display-inline-block blue-button add-friends-into-list" href="javascript:void(0)" style="display: inline-block !important;">' . $this->translate->_('Add') . '</a>'
        ));
        $this->addElement($button);
    }

    public function processFriendsMultioptions($friends) {
        $return = array();
        foreach ($friends as $oneFriend) {
            $return[$oneFriend['id']] = Utils::mergeStrings(array($oneFriend['first_name'], $oneFriend['last_name']));
        }
        return $return;
    }
}