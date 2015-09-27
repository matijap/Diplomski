<?php

require_once 'Galery/Row.php';

class Galery extends Galery_Row
{
    const GALERY_IMAGES_FOLDER   = 'user_images/galery';
    const EMPTY_GALERY_THUMBNAIL = 'galery_empty.jpg';

    public static function getThumbnail($galeryID) {
        $galery = Main::select()
                  ->from(array('IM' => 'image'), '')
                  ->where('IM.galery_id = ?', $galeryID )
                  ->columns(array('IM.id', 'IM.url'))
                  ->limit(1)
                  ->order('IM.id DESC')
                  ->query()->fetch();
        
        $return = empty($galery) ? self::EMPTY_GALERY_THUMBNAIL : $galery['url'];

        return self::GALERY_IMAGES_FOLDER . '/' . $return;
    }

    public static function getThumbnailImg() {
        return Galery::getThumbnail($this->id);
    }

    public function uploadImage() {
        $fileName = Utils::uploadMultiFiles('0', self::GALERY_IMAGES_FOLDER, 'files', $this->id);
        Image::create(array(
            'galery_id' => $this->id,
            'url'       => $fileName[1],
        ));
    }
}