<?php

require_once 'Comment/Row.php';

class Comment extends Comment_Row
{
    const COMMENT_SHOW_LIMIT  = 5;
    const COMMENT_TYPE_POST   = 'POST';
    const COMMENT_TYPE_GALERY = 'GALERY';

    public static function create($data, $tableName = false) {
        if (!$data['parent_comment_id']) {
            unset($data['parent_comment_id']);
        }
        if (isset($data['commented_image_id'])) {
          $data['type'] = self::COMMENT_TYPE_GALERY;
        }
        $data['date'] = time();
        $comment      = parent::create($data);
        return $comment;
    }

    public function likeOrUnlike($userID) {
        $userLike = Main::fetchRow(Main::select('UserLike')->where('user_id = ?', $userID)->where('comment_id = ?', $this->id));
        $likes    = $userLike ? $this->likes - 1 : $this->likes + 1;
        $return   = array();
        if ($userLike) {
            $userLike->delete();
            $return['action']        = 'unlike';
            $return['commentAuthor'] = $this->commenter_id;
        } else {
            UserLike::create(array('comment_id' => $this->id, 'user_id' => $userID));
            $return['action'] = 'like';
        }
        $this->edit(array('likes' => $likes));
        $return['comment'] = $this;
        return $return;
    }

    public function loadNextComments($loggedUserID, $imageID = false) {
        $result = Main::select()
                  ->from(array('CO' => 'comment'), '')
                  ->join(array('US' => 'user'), 'CO.commenter_id = US.id', '')
                  ->joinLeft(array('UL' => 'user_like'), 'CO.id = UL.comment_id AND UL.user_id = ' . $loggedUserID, '');
                  if (!is_null($this->commented_post_id)) {
                    $result->where('CO.commented_post_id = ?', $this->commented_post_id);
                  }
                  if (!is_null($this->commented_image_id)) {
                    $result->where('CO.commented_image_id = ?', $this->commented_image_id);
                  }
                  $result = $result->where('CO.id < ?', $this->id)
                  ->columns(array('CO.id as comment_id', 'UL.id as user_like',
                                  'CO.text', 'CO.likes', 'CO.forwarded', 'CO.date'))
                  ->order('CO.id DESC')
                  ->limit(Comment::COMMENT_SHOW_LIMIT)
                  ->query()->fetchAll();

        return $result;
    }

    public function favoriteOrUnfavoriteComment($userID) {
        $userFavorite = Main::fetchRow(Main::select('UserFavorite')->where('user_id = ?', $userID)->where('comment_id = ?', $this->id));
        if ($userFavorite) {
            $userFavorite->delete();
            $message = self::$translate->_('Comment unfavorited');
        } else {
            UserFavorite::create(array(
                'user_id'    => $userID,
                'comment_id' => $this->id,
            ));
            $message = self::$translate->_('Comment favorited');
        }
        return $message;
    }

    public function getLikesCount() {
        return $this->likes;
    }
    public function getFavoritesCount() {
        return Main::select()
            ->from(array('CO' => 'comment'), '')
            ->join(array('UF' => 'user_favorite'), 'CO.id = UF.comment_id', '')
            ->where('CO.id = ?', $this->id)
            ->columns(array('COUNT(UF.id)'))
            ->query()->fetchColumn();
    }

    public function getPostAuthor() {
        return Main::select()
            ->from(array('CO' => 'comment'), '')
            ->join(array('UI' => 'user_info'), 'UI.user_id = CO.commenter_id', '')
            ->where('CO.id = ?', $this->id)
            ->columns(array('CONCAT(UI.first_name, " ", UI.last_name) as post_author'))
            ->query()->fetchColumn();
    }

    public function forward($userID) {
        $data = array(
            'user_id'          => $userID,
            'page_id'          => NULL,
            'original_user_id' => $this->commenter_id,
            'title'            => '',
            'text'             => $this->text,
            'original_page_id' => NULL,
            'image'            => NULL,
            'date'             => time(),
            'video'            => NULL,
            'post_type'        => Post::POST_TYPE_TEXT,
            'date'             => time()
        );
        $newPost = Main::createNewObject('Post', $data);
        $newPost->save();
    }
}