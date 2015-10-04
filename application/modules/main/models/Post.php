<?php

require_once 'Post/Row.php';

class Post extends Post_Row
{
    const POST_TYPE_TEXT     = 'TEXT';
    const POST_TYPE_IMAGE    = 'IMAGE';
    const POST_TYPE_VIDEO    = 'VIDEO';

    const POST_IMAGES_FOLDER = 'user_images/post_images';

    public static function getPostTypeMultioptions() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return array(
            self::POST_TYPE_TEXT  => $translate->_('Text'),
            self::POST_TYPE_IMAGE => $translate->_('Image'),
            self::POST_TYPE_VIDEO => $translate->_('Video'),
        );
    }

    public static function create($data, $tableName = false) {
        if ($data['post_type'] == self::POST_TYPE_VIDEO) {
            $id            = Utils::getYoutubeIdFromUrl($data['video']);
            $data['video'] = $id[0];
            unset($data['image_url']);
            unset($_FILES["image_upload"]);
        }
        $pageID = Utils::arrayFetch($data, 'page_id', false);
        if ($pageID == '_E_' || !$pageID) {
            unset($data['page_id']);
        } else {
            unset($data['user_id']);
        }
        $post = parent::create($data);
        if ($data['post_type'] == self::POST_TYPE_IMAGE) {
            unset($data['video']);
            if (isset($_FILES["image_upload"])) {
                $fileName = Utils::uploadFile('image_upload', Post::POST_IMAGES_FOLDER, $post->id);
                if ($fileName) {
                    $post = $post->edit(array('image' => $fileName));
                }
            } else {
                $post = $post->edit(array('image' => $data['image_url']));
            }
        }
        return $post;
    }

    public static function getImageUrl($url) {
        $matchHttp = preg_match('/http/', $url);
        $matchWWW  = preg_match('/www/', $url);

        $toReturn  = '';
        if ($matchHttp || $matchWWW) {
            $toReturn =  $url;
        } else {
            $toReturn = APP_URL . '/' . Post::POST_IMAGES_FOLDER . '/' . $url;
        }
        return $toReturn;
    }

    public function likeOrUnlikePost($userID) {
        $return   = array();
        $userLike = Main::fetchRow(Main::select('UserLike')->where('user_id = ?', $userID)->where('post_id = ?', $this->id));
        $user     = Main::buildObject('User', $userID);
        $userInfo = $user->getUserInfo();
        if ($userLike) {
            $userLike->delete();
            $return['message'] = self::$translate->_('Post unliked');
            $return['action']  = 'unlike';
        } else {
            UserLike::create(array(
                'user_id' => $userID,
                'post_id' => $this->id,
            ));
            $return['message']         = self::$translate->_('Post liked');
            $return['messageToAuthor'] = $userInfo->getFullName() . ' ' . self::$translate->_('likes your post');
            if (!empty($this->user_id)) {
                $postAuthor = $this->user_id;
            } else {
                $page       = Main::buildObject('Page', $this->page_id);
                $postAuthor = $page->user_id;
                $return['messageToAuthor'] .= ' ' . self::$translate->_('for') . ' ' . $page->title;
            }
            $return['postAuthor'] = $postAuthor;
            $return['action']     = 'like';
        }
        return $return;
    }

    public function favoriteOrUnfavoritePost($userID) {
        $userFavorite = Main::fetchRow(Main::select('UserFavorite')->where('user_id = ?', $userID)->where('post_id = ?', $this->id));
        if ($userFavorite) {
            $userFavorite->delete();
            $message = self::$translate->_('Post unfavorited');
        } else {
            UserFavorite::create(array(
                'user_id' => $userID,
                'post_id' => $this->id,
            ));
            $message = self::$translate->_('Post favorited');
        }
        return $message;
    }

    public function getLikesCount() {
        return Post::getPostLikesCount($this->id);
    }
    public static function getPostLikesCount($postID) {
        return Main::select()
            ->from(array('PO' => 'post'), '')
            ->join(array('UL' => 'user_like'), 'PO.id = UL.post_id', '')
            ->where('PO.id = ?', $postID)
            ->columns(array('COUNT(UL.id)'))
            ->query()->fetchColumn();
    }
    public function getFavoritesCount() {
        return Main::select()
            ->from(array('PO' => 'post'), '')
            ->join(array('UF' => 'user_favorite'), 'PO.id = UF.post_id', '')
            ->where('PO.id = ?', $this->id)
            ->columns(array('COUNT(UF.id)'))
            ->query()->fetchColumn();
    }

    public function getPostAuthor() {
        return Post::getAuthorForPost($postID, $post);
    }

    public static function getAuthorForPost($postID, $post = NULL) {
        $post = is_null($post) ? Main::buildObject('Post', $postID) : $post;
        if (!empty($post->user_id)) {
            $return = Main::select()
                ->from(array('PO' => 'post'), '')
                ->join(array('UI' => 'user_info'), 'UI.user_id = PO.user_id', '')
                ->columns(array('CONCAT(UI.first_name, " ", UI.last_name) as post_author'))
                ->where('PO.id = ?', $post->id)
                ->query()->fetchColumn();
        } else {
            $page   = Main::buildObject('Page', $post->page_id);
            $return = $page->title;
        }
        return $return;
    }

    public static function determineIfForwardShouldBeVisible($watcherID, $userID = null, $pageID = null) {
        $return;
        if (!empty($userID)) {
            $return = $userID != $watcherID;
        } else {
            $page = Main::buildObject('Page', $pageID);
            $return = $page->user_id != $watcherID;
        }
        return $return;
    }

    public function forward($userID) {
        $originalUserID = empty($this->original_user_id) ? $this->user_id : $this->original_user_id;
        $originalPageID = empty($this->original_page_id) ? $this->page_id : $this->original_page_id;
        $data = array(
            'user_id'          => $userID,
            'page_id'          => NULL,
            'original_user_id' => $originalUserID,
            'title'            => $this->title,
            'text'             => $this->text,
            'original_page_id' => $originalPageID,
            'image'            => $this->image,
            'date'             => time(),
            'video'            => $this->video,
            'post_type'        => $this->post_type,
        );
        $newPost = Main::createNewObject('Post', $data);
        $newPost->save();
    }
}