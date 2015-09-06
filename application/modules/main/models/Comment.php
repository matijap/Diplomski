<?php

require_once 'Comment/Row.php';

class Comment extends Comment_Row
{
    const COMMENT_SHOW_LIMIT = 5;

    public static function create($data, $tableName = false) {
        if (!$data['parent_comment_id']) {
            unset($data['parent_comment_id']);
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

    public function loadNextComments() {
        $result = Main::select()
                  ->from(array('CO' => 'comment'), '')
                  ->where('CO.commented_post_id = ?', $this->commented_post_id)
                  ->where('CO.id > ?', $this->id)
                  ->columns(array('CO.id'));
                  fb("$result");
                  $result = $result->query()->fetchAll();

        fb($result);
        return '';
    }
}