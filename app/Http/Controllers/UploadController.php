<?php

namespace App\Http\Controllers;

use App\Clases\CreatorUpload;
use App\Events\UploadEvent;
use App\Models\Category;
use App\Models\Upload;
use App\Services\DetectCreatorUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    /**
     * полный вывод по объекту
     *
     * @param \App\Models\Upload $upload
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Upload $upload)
    {
        return view('pages.upload_show', compact('upload'));
    }

    /**
     * отдать изображение или превью объекта в браузер, для изображения - только если юзер смотрит его с текущего сайта
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Upload $upload
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function view(Request $request, Upload $upload)
    {
        if ($request->get('type', 'preview') == 'preview') {
            $f = config('public_path', public_path('storage')) . DIRECTORY_SEPARATOR . $upload->file_preview;
        } elseif ($request->cookie('XSRF-TOKEN', 'cookie') == $request->get('token', 'query')) {
            $f = config('public_path', public_path('storage')) . DIRECTORY_SEPARATOR . $upload->file;
        } else {
            $f = config('blocked_file', public_path('image' . DIRECTORY_SEPARATOR . 'blocked.png'));
        }
        return response()->file($f);
    }

    /**
     * LIKE объекта
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Upload $upload
     * @return array
     */
    public function like(Request $request, Upload $upload)
    {
        if ($upload->user()->isNot($request->user())) {
            // увеличение счетчика лайков
            $upload->increment('likes');
            $upload->save();

            // оповещение автора файла о лайке
            $msg = str_replace('FILE', $upload->file_name, __('You file "FILE" has been a new like'));
            broadcast(new UploadEvent($upload, 'info', $msg));

            return ['out' => 'ok', 'msg' => __('Thanks for your like')];
        }
        return ['out' => 'error', 'msg' => __('You can not like self files')];
    }

    /**
     * скачивание объекта
     *
     * @param \App\Models\Upload $upload
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Upload $upload)
    {
        // увеличение счетчика скачиваний
        $upload->increment('uploads');
        $upload->save();

        return response()->download(config('public_path', public_path('storage')) . DIRECTORY_SEPARATOR . $upload->file,
            $upload->file_name);
    }


    /**
     * форма загрузки объектов
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function form()
    {
        $categories = Category::orderBy('name')->get();

        return view('pages.upload', compact('categories'));
    }

    /**
     * загрузка и сохранение объекта
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // выбор фабрики
        $creatorUpload = DetectCreatorUpload::DetectCreatorUpload($request->get('category_id', 0));
        if (!($creatorUpload instanceof CreatorUpload)) {
            return redirect()
                ->route('upload')
                ->withErrors(Validator::make([], [])
                    ->errors()
                    ->add('category_id', __('Not found object for prepare data. Please select other category')))
                ->withInput();
        }

        // сохранение объекта
        $creatorUpload->CreateObjectUpload($request);

        return redirect()->route('my_uploads')->with('status', __('You file is saved and sent to control. After approved his will be published'));
    }

    /**
     * переключение поля "public" объекта
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Upload $upload
     * @return array
     */
    public function public(Request $request, Upload $upload)
    {
        // действие допустимо только для автора
        if ($upload->user()->is($request->user())) {
            $upload->public = ('true' == $request->get('public', 'false'));
            $upload->save();
            return ['msg' => __('Public status for file "' . $upload->file_name . '" has been changed')];
        }
        return ['msg' => __('Access denied')];
    }

    /**
     * DELETE объекта
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Upload $upload
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, Upload $upload)
    {
        // действие допустимо только для автора
        if ($upload->user()->is($request->user())) {
            $upload->delete();
            return redirect()->back()->with('status', __('You file is deleted'));
        }
        return redirect()->back()->with('status', __('Access denied'));
    }
}
