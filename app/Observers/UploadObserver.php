<?php

namespace App\Observers;

use App\Clases\CreatorUpload;
use App\Jobs\ApproveUploadJob;
use App\Models\Upload;
use App\Services\DetectCreatorUpload;

class UploadObserver
{
    /**
     * Handle the Upload "created" event.
     *
     * @param \App\Models\Upload $upload
     * @return void
     */
    public function created(Upload $upload)
    {
        // создаем задание на проверку объекта - задержка 3 секунды это что бы страница успела перезагрузится :)
        ApproveUploadJob::dispatch($upload)->delay(now()->addSecond(3));
    }

    /**
     * Handle the Upload "updated" event.
     *
     * @param \App\Models\Upload $upload
     * @return void
     */
    public function updated(Upload $upload)
    {
        //
    }

    /**
     * Handle the Upload "deleted" event.
     *
     * @param \App\Models\Upload $upload
     * @return void
     */
    public function deleted(Upload $upload)
    {
        // после удаления модели удалем данныее объекта
        $creatorUpload = DetectCreatorUpload::DetectCreatorUpload($upload->category_id);
        if ($creatorUpload instanceof CreatorUpload) $creatorUpload->DeleteObjectUpload($upload);
    }

    /**
     * Handle the Upload "restored" event.
     *
     * @param \App\Models\Upload $upload
     * @return void
     */
    public function restored(Upload $upload)
    {
        //
    }

    /**
     * Handle the Upload "force deleted" event.
     *
     * @param \App\Models\Upload $upload
     * @return void
     */
    public function forceDeleted(Upload $upload)
    {
        //
    }
}
