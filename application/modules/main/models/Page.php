<?php

require_once 'Page/Row.php';

class Page extends Page_Row
{
    const PAGE_TYPE_PLAYER = 'PLAYER';
    const PAGE_TYPE_TEAM   = 'TEAM';
    const PAGE_TYPE_SPORT  = 'SPORT';
    const PAGE_TYPE_OTHER  = 'OTHER';
    const PAGE_TYPE_LEAGUE = 'LEAGUE';

    const PAGE_IMAGES_FOLDER = 'user_images/page_images';

    const SPORTALIZE_PAGE_ID = 1;
    const DEFAULT_LOGO       = 'ball.jpg';

    public static function getAvailablePlayers() {
        return Page::getAvailablePages(Page::PAGE_TYPE_PLAYER);
    }
    public static function getAvailableTeams() {
        return Page::getAvailablePages(Page::PAGE_TYPE_TEAM);
    }

    public static function getAvailablePages($type) {
        return Main::select()
        ->from(array('PA' => 'page'), '')
        ->where('PA.type = ?', $type)
        ->columns(array('PA.id', 'PA.title'))
        ->query()->fetchAll();
    }

    public static function getPageMultioptions() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return array(self::PAGE_TYPE_PLAYER => $translate->_('Player'),
                     self::PAGE_TYPE_TEAM   => $translate->_('Team'),
                     self::PAGE_TYPE_LEAGUE => $translate->_('League'),
                     self::PAGE_TYPE_SPORT  => $translate->_('Sport'),
                     self::PAGE_TYPE_OTHER  => $translate->_('Other'),
                    );
    }

    public static function create($params, $tableName = false) {
        $page = parent::create($params); 
        if (isset($_FILES['logo'])) {
            $fileName = Utils::uploadFile('logo', Page::PAGE_IMAGES_FOLDER, $page->id);
        } else {
            $fileName = self::DEFAULT_LOGO;
        }
        if ($fileName) {
            $page->edit(array('logo' => $fileName));
        }

        return $page;
    }

    public function getPostsByPage($page = 1, $userWatching) {

        $commentColumnsToBeFetched = User::getColumnsToBeFetched();
        $subQuery = Main::select()
                    ->from(array('CO2' => 'comment'), '')
                    ->where('CO2.commented_post_id = PO.id AND CO1.id > CO2.id')
                    ->columns(array('COUNT(*)'));

        $subQueryForCount = Main::select()
                            ->from(array('CO3' => 'comment'), 'COUNT(*)')
                            ->join(array('PO3' => 'post'), 'CO3.commented_post_id = PO3.id', '')
                            ->where("PO3.page_id = ?", $this->id)
                            ->where('CO3.commented_post_id = PO.id');

        $commentSelect = Main::select()
            ->from(array('CO1' => 'comment'), '')
            ->join(array('PO' => 'post'), 'CO1.commented_post_id = PO.id', '')
            ->joinLeft(array('UL' => 'user_like'), 'UL.comment_id = CO1.id AND UL.user_id = ' .  $userWatching, '')
            ->joinLeft(array('UF' => 'user_favorite'), 'UF.comment_id = CO1.id AND UF.user_id = ' .  $userWatching, '')
            ->joinLeft(array('US' => 'user'), 'CO1.commenter_id = US.id', '')
            ->joinLeft(array('UI' => 'user_info'), 'UI.user_id = US.id', '')
            ->where("
                (($subQueryForCount 
                ) - " . (Comment::COMMENT_SHOW_LIMIT + 1) . ")
             < ($subQuery)")
            ->where('PO.page_id = ?', $this->id)
            ->group(array('CO1.commented_post_id', 'CO1.id'))
            ->order(array('CO1.commented_post_id', 'CO1.date DESC', 'CO1.id DESC', ))
            ->columns(array('CO1.id', 'CO1.text', 'CO1.commented_post_id', 'CO1.date',
                            'UL.id as ulid', 'CO1.parent_comment_id', 'CO1.forwarded',
                            'CO1.likes', 'UI.first_name', 'UI.last_name', 'UF.id as comment_favorite'));

        
        $posts = Main::select()
                    ->from(array('PO' => 'post'), '')
                    ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
                    ->joinLeft(array('UL' => 'user_like'), 'UL.post_id = PO.id', '')
                    ->joinLeft(array('UF' => 'user_favorite'), 'UF.post_id = PO.id', '')
                    ->where('PO.page_id = ?', $this->id)
                    ->columns($commentColumnsToBeFetched)
                    ->query()->fetchAll();

        $return = array();
        foreach ($posts as $key => $onePost) {
            // fb($onePost);
            $return[$onePost['post_id']]['post_id'] = $onePost['post_id'];
            $return[$onePost['post_id']]['title']   = $onePost['post_title'];
            $return[$onePost['post_id']]['text']    = $onePost['post_text'];
            $return[$onePost['post_id']]['date']    = $onePost['post_date'];
            $return[$onePost['post_id']]['type']    = $onePost['post_type'];
            $return[$onePost['post_id']]['video']   = $onePost['post_video'];
            $return[$onePost['post_id']]['image']   = $onePost['post_image'];
            $return[$onePost['post_id']]['user_id'] = $onePost['user_id'];
            $return[$onePost['post_id']]['page_id'] = $onePost['page_id'];
            if (!empty($onePost['comment_id'])) {
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['comment_id']        = $onePost['comment_id'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['text']              = $onePost['comment_text'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['parent_comment_id'] = $onePost['parent_comment_id'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['forwarded']         = $onePost['forwarded'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['likes']             = $onePost['likes'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['date']              = $onePost['comment_date'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['user_like']         = $onePost['user_like'];
            }
        }        

        // fb($return);
        $paginator = Zend_Paginator::factory($return);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(User::INDEX_PAGE_POST_LIMIT);
        // return false;
        return $paginator;
    }

    public function gatherWidgetsForPage() {
        $widgets = Main::select()
                ->from(array('WI' => 'widget'), '')
                ->joinLeft(array('UW' => 'user_widget'), 'WI.id = UW.widget_id', '')
                ->where('WI.page_id = ?', $this->id)
                ->columns(array('WI.id', 'WI.title', 'UW.placement', 'WI.is_system'))
                ->group('WI.id')
                ->query()->fetchAll();
        return $widgets;
    }

    public function likeOrUnlike($userID) {
        $ids = $this->getPageWidgetIDs();
        $return   = array();
        if ($this->id != self::SPORTALIZE_PAGE_ID) {
            $userPage = Main::fetchRow(Main::select('UserPage')->where('user_id = ?', $userID)->where('page_id = ?', $this->id));
            if ($userPage) {
                $userPage->delete();
                ExFavoritePages::create(array('user_id' => $userID, 'page_id' => $this->id));
                $return['status']  = 'success';
                $return['message'] = self::$translate->_('Page unfavorited successfully');
                $return['html'] =  '<tr>
                                        <td>
                                            <a href="' . APP_URL . '/page/index/pageID/' . $this->id . '">' . $this->title . '</a>
                                        </td>
                                        <td>
                                            <a data-id="' . $this->id . '" class="blue-button favourite-page-back" href="#"><i class="fa fa-heart"></i></a>
                                        </td>
                                    </tr>';
                Main::execQuery('DELETE FROM user_widget WHERE widget_id IN (?)', array(implode(',', $ids)));
            } else {
                UserPage::create(array('user_id' => $userID, 'page_id' => $this->id));
                $exFavorite = Main::fetchRow(Main::select('ExFavoritePages')->where('user_id = ?', $userID)->where('page_id = ?', $this->id));
                if ($exFavorite) {
                    $exFavorite->delete();
                }
                $return['status']  = 'success';
                $return['message'] = self::$translate->_('Page favorited successfully');
                $return['html'] =  '<tr>
                                        <td>
                                            <a href="' . APP_URL . '/page/index/pageID/' . $this->id . '">
                                                ' . $this->title . '
                                            </a>
                                        </td>
                                        <td>
                                            <a data-url="/widget/configure-widget-for-user/pageID/' . $this->id . '" class="blue-button modal-open" href="javascript:void(0)">
                                                <i class="fa fa-cog"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="blue-button unfavorite-page" data-id="' . $this->id . '" href="javascript:void(0)">
                                                <i class="fa fa-heartbeat"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <input data-id="' . $this->id . '" checked="checked" class="show-in-widget-favorite cursor-pointer" type="checkbox">
                                        </td>
                                    </tr>';
                foreach ($ids as $oneID) {
                    UserWidget::create(array(
                        'user_id'   => $userID,
                        'widget_id' => $oneID,
                        'placement' => Widget::WIDGET_PLACEMENT_RIGHT,
                    ));
                }
            }
        } else {
            $return['status']  = 'error';
            $return['message'] = self::$translate->_('Cannot unlike system page');
        }
        return $return;
    }

    public function showOrHideInFavoritePagesWidget($userID) {
        $userPage = Main::fetchRow(Main::select('UserPage')->where('user_id = ?', $userID)->where('page_id = ?', $this->id));
        $newValue = $userPage->show_in_favorite_pages_widget == 1 ? 0 : 1;
        $userPage->edit(array('show_in_favorite_pages_widget' => $newValue));
        $message  = $newValue ? self::$translate->_('Page will appear in favorite pages widget') : self::$translate->_('Page will no longer appear in favorite pages widget');
        return $message;
    }

    public function getUser() {
        return Main::fetchRow(Main::select('User')->where('id = ?', $this->user_id));
    }

    public function getLikesCount() {
        return Main::select()
                ->from('user_page', '')
                ->where('page_id = ?', $this->id)
                ->columns('COUNT(id)')
                ->query()->fetchColumn();
    }

    public function isLikedByUser($userID) {
        return Main::select()
                ->from('user_page', '')
                ->where('page_id = ?', $this->id)
                ->where('user_id = ?', $userID)
                ->columns('COUNT(id)')
                ->query()->fetchColumn();
    }

    public function getPageWidgetIDs() {
        return Main::select()
                ->from('widget', '')
                ->where('page_id = ?', $this->id)
                ->columns('id')
                ->query()->fetchAll(PDO::FETCH_COLUMN);
    }
}