<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\RealStatePhoto;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{

    /**
     * @var RealStatePhoto
     */
    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId)
    {
        try {
            $photo = $this->realStatePhoto
                                ->whereRealStateId($realStateId)
                                ->whereIsThumb(true)->first();

            if ($photo){
                $photo->update([
                    'is_thumb' => false
                ]);
            }

            $photo = $this->realStatePhoto->findOrFail($photoId);
            $photo->update([
                'is_thumb' => true
            ]);

            return response()->json([
                'data' => [
                    'msg' => 'Thumb atualizada com sucesso!'
                ]
            ], 200);

        }catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($photoId)
    {
        try {
            $photo = $this->realStatePhoto->findOrFail($photoId);

            if ($photo->is_thumb) {
                $message = new ApiMessages('Não é possivel remover foto de thumb!');
                return response()->json($message->getMessage(), 401);
            }

            if ($photo) {
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json([
                'data' => [
                    'msg' => 'Foto deletada com sucesso!'
                ]
            ], 200);

        }catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
