<?php

require_once 'Image/Row.php';

class Image extends Image_Row
{
    public function getCommentsForImage($loggedUserID) {
        $comments = Main::select()
                    ->from(array('CO' => 'comment'), '')
                    ->joinLeft(array('UL' => 'user_like'), 'CO.id = UL.comment_id AND UL.user_id = ' . $loggedUserID, '')
                    ->joinLeft(array('UF' => 'user_favorite'), 'CO.id = UF.comment_id AND UF.user_id = ' . $loggedUserID, '')
                    ->where('commented_image_id = ?', $this->id)
                    ->columns(array('CO.text', 'CO.forwarded', 'CO.likes', 'CO.date', 'CO.id as comment_id', 'UL.id AS user_like', 'UF.id as comment_favorite'))
                    ->limit(5)
                    ->order('CO.date DESC')
                    ->query()->fetchAll();
        return $comments;
    }

    public function delete() {
        $comments = Main::select()
                    ->from('comment')
                    ->where('commented_image_id = ?', $this->id)
                    ->columns(array('id'))
                    ->query()->fetchAll(PDO::FETCH_COLUMN);

        Main::execQuery("DELETE FROM user_like WHERE comment_id IN (?)", array(implode(',', $comments)));
        Main::execQuery("DELETE FROM comment WHERE id IN (?)", array(implode(',', $comments)));
        $url = $this->url;
        $unlink = parent::delete();
        if ($unlink) {
            shell_exec('chmod -R 0777 ' . WEB_ROOT_PATH . '/' . Galery::GALERY_IMAGES_FOLDER . '/' . $url);
            $unlink = unlink(WEB_ROOT_PATH . '/' . Galery::GALERY_IMAGES_FOLDER . '/' . $url);    
        }
        return $unlink;
    }
}