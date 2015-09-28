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

        $fileName = Utils::uploadFile('logo', Page::PAGE_IMAGES_FOLDER, $page->id);
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
                            'CO1.likes', 'UI.first_name', 'UI.last_name'));

        
        $posts = Main::select()
                    ->from(array('PO' => 'post'), '')
                    ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
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
                ->columns(array('WI.id', 'WI.title', 'UW.placement'))
                ->group('WI.id')
                ->query()->fetchAll();
        return $widgets;
    }
    
}