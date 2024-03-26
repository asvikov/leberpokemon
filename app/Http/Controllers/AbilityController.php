<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbilityRequest;
use App\Models\Ability;
use App\Services\AbilityImageService;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Ability::all(['id', 'name', 'name_lang_ru', 'image']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AbilityImageService $imageService)
    {
        $input = $request->only([
            'name',
            'name_lang_ru',
            'image'
        ]);

        if($request->hasFile('image_file')) {
            $urls = $imageService->create($request);
            $relative_url = $imageService->getRelativeUrl($urls[0]['url']);
            $input['image'] = $relative_url;
        }
        $ability = Ability::create($input);
        $ability = $ability->toArray();
        $ability['image'] = $imageService->getFullUrl($ability['image']);
        $response = [
            'status' => 'created',
            'ability' => $ability
        ];
        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Ability::findOrFail($id, ['id', 'name', 'name_lang_ru', 'image']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AbilityRequest $request, string $id, AbilityImageService $imageService)
    {
        $ability = Ability::findOrFail($id);
        $input = $request->only([
            'name',
            'name_lang_ru',
            'image'
        ]);

        if($request->hasFile('image_file')) {
            $image_id = $imageService->getNameFromPath($ability->image);
            $imageService->update($request, $image_id);
        }
        $ability->update($input);
        $ability = $ability->toArray();
        $ability['image'] = $imageService->getFullUrl($ability['image']);
        $response = [
            'status' => 'updated',
            'pokemon' => $ability
        ];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, AbilityImageService $imageService)
    {
        $ability = Ability::findOrFail($id);
        $ability->delete();
        $image_name = $imageService->getNameFromPath($ability->image);
        $imageService->delete($image_name);
        return response()->json('ability id:'.$id.' has been deleted');
    }
}
