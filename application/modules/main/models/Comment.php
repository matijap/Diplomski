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

    public function loadNextComments($loggedUserID) {
        $result = Main::select()
                  ->from(array('CO' => 'comment'), '')
                  ->join(array('US' => 'user'), 'CO.commenter_id = US.id', '')
                  ->joinLeft(array('UL' => 'user_like'), 'CO.id = UL.comment_id AND UL.user_id = ' . $loggedUserID, '')
                  ->where('CO.commented_post_id = ?', $this->commented_post_id)
                  ->where('CO.id < ?', $this->id)
                  ->columns(array('CO.id as comment_id', 'UL.id as user_like',
                                  'CO.text', 'CO.likes', 'CO.forwarded', 'CO.date'))
                  ->order('CO.id DESC')
                  ->limit(Comment::COMMENT_SHOW_LIMIT)
                  ->query()->fetchAll();

        return $result;
    }
}