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
        if ($userLike) {
            $userLike->delete();
        } else {
            UserLike::create(array('comment_id' => $this->id, 'user_id' => $userID));
        }
        return $this->edit(array('likes' => $likes));
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
}