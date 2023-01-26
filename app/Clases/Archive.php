<?php

namespace App\Clases;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class Archive extends Picture
{

    protected function rules(): array
    {
        return array_merge(
            parent::rules(),
            ['file' => ['required', 'file', 'max:' . $this->max_file_size, 'mimes:zip,rar,7z,bzip,gzip']]
        );
    }

    public function createPreview(): bool
    {
        $this->validated['file_preview'] = $this->file_preview_default;
        return true;
    }

    public function deletePreview(): bool
    {
        return true;
    }

}
