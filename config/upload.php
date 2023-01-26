<?php

return [

    // все папки будут созданы по пути "папка_проекта/storage/app/public"

    // путь для хранения файлов
    'folder_file' => 'user_file',
    // путь для хранения превью
    'folder_preview' => 'user_preview',
    // папка с файлами превью по умолчанию, именна файлов должны быть как именама классов в нижнем регистре с раширением "png"
    'file_preview_default' => 'user_preview',


    // публичная папка откуда видны файлы, относительно "папка_проекта/public/storage"
    'public_path' => 'storage',

    // максимальный размер файла в кБ
    'max_file_size' => 4096,

    // максимальные ширина и высота для превью
    'maxWidthLogo' => 250,
    'maxHeightLogo' => 300,

    // путь и фаил заглушка для закрытия доступа
    'blocked_file' => public_path('image') . DIRECTORY_SEPARATOR . 'blocked.png',
];
