<?php

namespace App\Clases;

use App\Clases\Interface\ObjectUpload;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Picture implements ObjectUpload
{
    protected array $validated = [];

    protected $path_for_storage;
    protected $path_for_saveAs;
    protected $folder_file;
    protected $folder_preview;
    protected $file_preview_default;
    protected $max_file_size;


    // загружаем необходимые найстройки из конфига
    public function __construct()
    {
        $this->path_for_saveAs = 'public' . DIRECTORY_SEPARATOR;
        $this->path_for_storage =  'app' . DIRECTORY_SEPARATOR . $this->path_for_saveAs;

        $this->folder_file = config('upload.folder_file', 'user_file') . DIRECTORY_SEPARATOR;
        $this->folder_preview = config('upload.folder_preview', 'user_preview') . DIRECTORY_SEPARATOR;
        $this->file_preview_default = config('upload.folder_preview_default', $this->folder_preview) .
            DIRECTORY_SEPARATOR . Str::of(get_class($this))->afterLast('\\')->lower() . '.png';

        $this->max_file_size = config('upload.max_file_size', 4096);
    }

    // получить относительный путь для файла или директории
    private function getPath($file = ''): string
    {
        return $this->path_for_saveAs . $file;
    }

    // получить полный путь для файла или директории
    private function getFullPath($file = ''): string
    {
        return storage_path($this->path_for_storage . $file);
    }

    // правила валидации
    protected function rules(): array
    {
        return [
            'category_id' => 'exists:\App\Models\Category,id',
            'title' => ['required', 'string', 'min:1', 'max:250'],
            'descr' => ['required', 'string', 'min:1'],
            'file' => ['required', 'file', 'max:' . $this->max_file_size, 'mimetypes:image/png,image/gif,image/jpeg'],
            'public' => ['nullable', 'accepted']
        ];
    }

    // генерация уникального имени файла (на случай если файлов будет очень много - раскладываем по папкам)
    private function createFileName()
    {
        do {
            $file = strtolower(Str::random(40)) . '.' . pathinfo($this->validated['file_name'], PATHINFO_EXTENSION);
            $file = $this->folder_file . substr($file, 0, 2) . DIRECTORY_SEPARATOR . $file;
        } while (file_exists($this->getFullPath($file)));
        return $file;
    }


    // генерация уникального имени файла для превью
    private function createPreviewName()
    {
        return str_replace($this->folder_file, $this->folder_preview, $this->validated['file']);
    }

    // удаление указанного файла и если он последний то и папки
    private function deleteF($f)
    {
        $file = $this->getFullPath($f);
        try {
            $ret = (file_exists($file) ? unlink($file) : false);
            $this->deleteDir(pathinfo($file, PATHINFO_DIRNAME));
        } catch (\Exception $e) {
            $ret = false;
        }

        return $ret;
    }

    // удаление указанной папки если она пустая
    private function deleteDir($path)
    {
        try {
            if (count(scandir($path)) == 2) rmdir($path);
        } catch (\Exception $e) {
        }
    }


    // валидация данных из формы и добавление данных из запроса
    public function validate(Request $request)
    {
        $this->validated = $request->validate($this->rules());

        $this->validated['user_id'] = $request->user()['id'];
        $this->validated['public'] = boolval($this->validated['public']);
        $this->validated['file_name'] = $request->file('file')->getClientOriginalName();
    }

    // перенос файла в папку
    public function upload()
    {
        $file = $this->createFileName();

        try {
            if (!is_dir($d = pathinfo($this->getFullPath($file), PATHINFO_DIRNAME))) {
                mkdir($d, 0777, true);
            }
            $this->validated['file']->storeAs($this->path_for_saveAs, $file);
        } catch (\Exception $e) {
            return $e;
        }

        $this->validated['file'] = $file;

        return true;
    }

    // создание записи в базе
    public function save(): Upload
    {
        $upload = Upload::create($this->validated);

        return $upload;
    }

    // инициализация
    public function setValidate(array $validated)
    {
        $this->validated = $validated;
    }

    // создание превью - если не получится будет установлено значение по умолчанию
    public function createPreview(): bool
    {
        $this->validated['file_preview'] = $this->createPreviewName();


        if (!file_exists($f = $this->getFullPath($this->validated['file_preview']))) {
            try {
                if (!is_dir($d = pathinfo($f, PATHINFO_DIRNAME))) mkdir($d, 0777, true);

                $maxWidthLogo = config('upload.maxWidthLogo', 250);
                $maxHeightLogo = config('upload.maxHeightLogo', 300);

                $preview = Image::make($this->getFullPath($this->validated['file']));
                $preview->fit($maxWidthLogo, $maxHeightLogo);
                $preview->save($f);
            } catch (\Exception $e) {
                $this->deleteDir($d);
                $this->validated['file_preview'] = $this->file_preview_default;
            }
        }
        return true;
    }

    // обновление записи в базе
    public function update($status = null)
    {
        $u = Upload::findOrFail($this->validated['id']);

        if($status !== null) $this->validated['status'] = $status;
        $u->update([
            'status' => $this->validated['status'],
            'file_preview' => $this->validated['file_preview'],
        ]);

    }

    // удалить фаил
    public function deleteFile(): bool
    {
        return $this->deleteF($this->validated['file']);
    }

    // удалить фаил превью, если он не по умолчанию
    public function deletePreview(): bool
    {
        if ($this->validated['file_preview'] != $this->file_preview_default) return $this->deleteF($this->validated['file_preview']);
        return true;
    }

}
