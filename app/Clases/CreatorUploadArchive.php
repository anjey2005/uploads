<?php

namespace App\Clases;

use App\Clases\Interface\ObjectUpload;

class CreatorUploadArchive extends CreatorUpload
{
    public function CreateUpload(): ObjectUpload
    {
        return new Archive();
    }
}
