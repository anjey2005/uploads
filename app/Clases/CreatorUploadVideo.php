<?php

namespace App\Clases;

use App\Clases\Interface\ObjectUpload;

class CreatorUploadVideo extends CreatorUpload
{
    public function CreateUpload(): ObjectUpload
    {
        return new Video();
    }
}
