<?php

namespace App\Clases;

use App\Clases\Interface\ObjectUpload;

class CreatorUploadPicture extends CreatorUpload
{
    public function CreateUpload(): ObjectUpload
    {
        return new Picture();
    }

}
