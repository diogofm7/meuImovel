<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\RealState;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $user = auth('api')->user();
        $realStates = $user->realStates()->with(['photos' => function ($q){
                                                $q->whereIsThumb(true);
                                             }
                                            , 'categories'])->paginate(10);

        return response()->json($realStates, 200);
    }

    public function show($id)
    {
        try{
            $user = auth('api')->user();
            $realState = $user->realStates()->with(['photos', 'categories'])
                                            ->findOrFail($id)
                                            ->makeHidden('thumb');

            return response()->json([
                'data' => $realState
            ], 200);

        }catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        try{

            $user = auth('api')->user();
            $realState = $user->realStates()->create($data);

            if (!empty($data['categories']))
                $realState->categories()->sync($data['categories']);

            if ($images) {
                $imagesUploaded = [];
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo'     => $path,
                        'is_thumb'  => false
                    ];
                }
                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel cadastrado com sucesso!'
                ]
            ], 200);

        }catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update($id, RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        try{

            $user = auth('api')->user();
            $realState = $user->realStates()->findOrFail($id);
            $realState->update($data);

            if (!empty($data['categories']))
                $realState->categories()->sync($data['categories']);

            if ($images) {
                $imagesUploaded = [];
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo'     => $path,
                        'is_thumb'  => false
                    ];
                }
                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel atualizado com sucesso!'
                ]
            ], 200);

        }catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try{

            $user = auth('api')->user();
            $realState = $user->realStates()->findOrFail($id);
            $realState->categories()->detach();
            $realState->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel deletado com sucesso!'
                ]
            ], 200);

        }catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

}
