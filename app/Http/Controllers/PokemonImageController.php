<?php

namespace App\Http\Controllers;

use App\Http\Requests\PokemonImageRequest;
use App\Services\PokemonImageService;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

class PokemonImageController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(PokemonImageService $imageService)
    {
        return $imageService->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PokemonImageRequest $request, PokemonImageService $imageService)
    {
        $image = $imageService->create($request);
        return response()->json([
            'status' => 'successful',
            'pokemon_images' => $image
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, PokemonImageService $imageService)
    {
        return $imageService->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, PokemonImageRequest $request, PokemonImageService $imageService)
    {
        $image = $imageService->update($request, $id);
        return response()->json([
            'status' => 'successful',
            'pokemon_images' => $image
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, PokemonImageService $imageService)
    {
        $imageService->delete($id);
        return response()->json(['status' => 'successful']);
    }
}
