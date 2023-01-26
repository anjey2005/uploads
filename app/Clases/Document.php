<?php

namespace App\Clases;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class Document extends Archive
{

    protected function rules(): array
    {
        return array_merge(
            parent::rules(),
            ['file' => ['required', 'file', 'max:' . $this->max_file_size, 'mimes:pdf,doc,xls,txt']]
        );
    }


}
