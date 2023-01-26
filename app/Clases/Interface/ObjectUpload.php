<?php

namespace App\Clases\Interface;

use App\Models\Upload;
use Illuminate\Http\Request;

interface ObjectUpload
{
    public function validate(Request $request);
    public function upload();
    public function save(): Upload;

    public function setValidate(array $validated);

    public function createPreview(): bool;
    public function update($status = null);

    public function deleteFile():bool;
    public function deletePreview():bool;
}
