<?php

namespace App\Services;

use App\Clases\CreatorUpload;
use App\Clases\CreatorUploadArchive;
use App\Clases\CreatorUploadDocument;
use App\Clases\CreatorUploadPicture;
use App\Clases\CreatorUploadVideo;
use App\Clases\Interface\ObjectUpload;

class DetectCreatorUpload
{
    /**
     * получить соответсвующий объект фабрики CreatorUpload для указанной категории
     *
     * @param int $category_id
     * @return CreatorUpload|null
     */
    public static function DetectCreatorUpload(int $category_id)
    {
        switch ($category_id) {
            case 1:
                $creatorUpload = new CreatorUploadPicture();
                break;
            case 2:
                $creatorUpload = new CreatorUploadVideo();
                break;
            case 3:
                $creatorUpload = new CreatorUploadArchive();
                break;
            case 4:
                $creatorUpload = new CreatorUploadDocument();
                break;
            default:
                $creatorUpload = null;
        }

        return $creatorUpload;
    }
}
