<?php

require_once 'Image/Row.php';

class Image extends Image_Row
{
    public function getCommentsForImage($loggedUserID) {
        $comments = Main::select()
                    ->from(array('CO' => 'comment'), '')
                    ->joinLeft(array('UL' => 'user_like'), 'CO.id = UL.comment_id AND UL.user_id = ' . $loggedUserID, '')
                    ->where('commented_image_id = ?', $this->id)
                    ->columns(array('CO.text', 'CO.forwarded', 'CO.likes', 'CO.date', 'CO.id as comment_id', 'UL.id AS user_like'))
                    ->limit(5)
                    ->order('CO.date DESC')
                    ->query()->fetchAll();
        return $comments;
    }
}