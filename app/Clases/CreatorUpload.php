<?php

namespace App\Clases;

use App\Clases\Interface\ObjectUpload;
use App\Models\Upload;
use Illuminate\Http\Request;

abstract class CreatorUpload
{
    // фабричный метод
    abstract public function CreateUpload(): ObjectUpload;

    // создание объекта
    public function CreateObjectUpload(Request $request): Upload
    {
        $objectUpload = $this->CreateUpload();
        $objectUpload->validate($request);
        $ret = $objectUpload->upload();
        if ($ret instanceof \Exception) throw $ret;
        return $objectUpload->save();
    }

    // проверка объекта
    public function ApproveObjectUpload(Upload $upload): string
    {
        $objectUpload = $this->CreateUpload();
        $objectUpload->setValidate($upload->toArray());

        // при желании сделать провеку на контент

        if ($objectUpload->createPreview()) {
            $status = 'accepted';
            $objectUpload->update($status);
        } else {
            $status = 'declined';
        }
        return $status;
    }

    // удаление объекта
    public function DeleteObjectUpload(Upload $upload): void
    {
        $objectUpload = $this->CreateUpload();
        $objectUpload->setValidate($upload->toArray());
        $objectUpload->DeleteFile();
        $objectUpload->DeletePreview();
    }

}
