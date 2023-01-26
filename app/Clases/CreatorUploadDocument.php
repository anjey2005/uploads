<?php

namespace App\Clases;

use App\Clases\Interface\ObjectUpload;

class CreatorUploadDocument extends CreatorUpload
{
    public function CreateUpload(): ObjectUpload
    {
        return new Document();
    }
}
