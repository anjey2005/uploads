<?php

namespace App\Clases;


class Video extends Archive
{

    protected function rules(): array
    {
        return array_merge(
            parent::rules(),
            ['file' => ['required', 'file', 'max:' . $this->max_file_size, 'mimes:avi,mpg,mp4,mov,mkv']]
        );
    }


}
