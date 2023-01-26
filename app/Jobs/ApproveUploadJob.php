<?php

namespace App\Jobs;

use App\Clases\CreatorUpload;
use App\Events\UploadEvent;
use App\Models\Upload;
use App\Services\DetectCreatorUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApproveUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $upload;

    //Удалить задание, если модели больше не существуют
    public $deleteWhenMissingModels = true;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Upload $upload)
    {
        // отношения не нужны
        $this->upload = $upload->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // получить соответсвующий объект CreatorUpload для модели
        $creatorUpload = DetectCreatorUpload::DetectCreatorUpload($this->upload->category_id);
        if($creatorUpload instanceof CreatorUpload) {
            // проверяем объект
            $status = $creatorUpload->ApproveObjectUpload($this->upload);

            // оповещение владельца о результате проверки
            $msg = str_replace('FILE', $this->upload->file_name, __('You file "FILE" has been ' . $status));
            broadcast(new UploadEvent($this->upload, 'info', $msg));
        }
    }
}
