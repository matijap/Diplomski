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
        $post = parent::create($data);
        if ($data['post_type'] == self::POST_TYPE_IMAGE) {
            unset($data['video']);
            if (isset($_FILES["image_upload"])) {
                $file      = $_FILES["image_upload"];
                if ($file['tmp_name'] != '') {
                    if (is_uploaded_file($file['tmp_name'])) {
                        $fileName = Utils::fileExist(WEB_ROOT_PATH . "/" . Post::POST_IMAGES_FOLDER . "/" , $post->id . '_' . $file['name']);
                        if (!move_uploaded_file($file['tmp_name'], WEB_ROOT_PATH . "/" . Post::POST_IMAGES_FOLDER . "/" . $fileName)) {
                            throw new Exception($translate->_("Could not upload files or images."));
                        } else {
                            $post->image = $fileName;
                            $post->save();
                        }
                    }
                }
            } else {
                $post->image = $data['image_url'];
                $post->save();
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
}