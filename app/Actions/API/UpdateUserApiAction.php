<?php

namespace App\Actions\API;

use App\Models\User;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class UpdateUserApiAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    private function isImage(object $file)
    {
        return $file instanceof File;
    }

    public function handle()
    {
        $user = User::findOrFail($this->attributes['user_id']);

        if(isset($this->attributes['image']) && $this->attributes['image']){

            $oldFile = public_path('image/'.$user->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = url('image/' . $file['name']);

        } else {
            unset($this->attributes['image']);
        }


        $user->update($this->attributes);

        return $user;
    }
}
