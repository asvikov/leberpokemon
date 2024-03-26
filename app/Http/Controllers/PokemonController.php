<?php

namespace App\Http\Controllers;

use App\Http\Requests\PokemonRequest;
use App\Models\Ability;
use App\Models\Pokemon;
use App\Models\Shape;
use App\Services\AbilityImageService;
use App\Services\PokemonImageService;
use App\Services\RegionService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param RegionService $regionService
     * @return string
     */
    public function index(Request $request, RegionService $regionService)
    {
        if($request->get('region')) {
            $where_in_ids = $regionService->getChildrenIds($request->get('region'));
            $where_in_ids[] = $request->get('region');
            $pokemones = Pokemon::with(['shapes', 'abilities'])->whereIn('region_id', $where_in_ids)->get(['id', 'name', 'image', 'region_id']);
        } else {
            $pokemones = Pokemon::with(['shapes', 'abilities'])->get(['id', 'name', 'image', 'region_id']);
        }
        $pokemones->map(function ($pokemon) {
            $pokemon->region = $pokemon->region()->first()->name;
            return $pokemon;
        });
        return $pokemones->toJson();
    }

    /**
     * Store a newly created resource in storage.
     * @param PokemonRequest $request
     * @param PokemonImageService $imageService
     */
    public function store(PokemonRequest $request, PokemonImageService $imageService)
    {
        $input = $request->only([
            'name',
            'image',
            'region_id'
        ]);

        if($request->hasFile('image_file')) {
            $urls = $imageService->create($request);
            $relative_url = $imageService->getRelativeUrl($urls[0]['url']);
            $input['image'] = $relative_url;
        }
        $pokemon = Pokemon::create($input);
        $shape_sync = $this->shapesSync($pokemon, $request->get('shapes_id'));
        $ability_sync = $this->abilitiesSync($pokemon, $request->get('abilities_id'));
        $pokemon = $pokemon->toArray();
        $pokemon['image'] = $imageService->getFullUrl($pokemon['image']);
        $pokemon['shapes'] = Shape::find($shape_sync['attached'], ['id', 'name']);
        $pokemon['abilities'] = Ability::find($ability_sync['attached'], ['id', 'name', 'name_lang_ru']);
        $response = [
            'status' => 'created',
            'pokemon' => $pokemon
        ];
        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @param RegionService $regionService
     * @param PokemonImageService $imageService
     * @param AbilityImageService $abilityImageService
     * @return string
     */
    public function show(string $id, RegionService $regionService, PokemonImageService $imageService, AbilityImageService $abilityImageService)
    {
        $pokemon = Pokemon::findOrFail($id, ['id', 'name', 'image', 'region_id']);
        $pokemon->region_name = $regionService
            ->getRegion($pokemon->region_id)
            ->name;
        $pokemon->region_parentage = $regionService
            ->getParentage($pokemon->region_id);
        $pokemon->image = $imageService->getFullUrl($pokemon->image);
        $pokemon->shapes = $pokemon->shapes()->get(['name']);
        $abilities = $pokemon->abilities()->get(['name', 'name_lang_ru', 'image']);

        $abilities->map(function ($ability) use ($abilityImageService) {

            $ability->image =  $abilityImageService->getFullUrl($ability->image);
            return $ability;
        });
        $pokemon->abilities = $abilities;
        return $pokemon->toJson();
    }

    /**
     * Update the specified resource in storage.
     * @param string $id
     * @param PokemonRequest $request
     * @param PokemonImageService $imageService
     */
    public function update(string $id, PokemonRequest $request, PokemonImageService $imageService)
    {
        $pokemon = Pokemon::findOrFail($id);
        $input = $request->only([
            'name',
            'image',
            'region_id'
        ]);

        if($request->hasFile('image_file')) {
            $image_id = $imageService->getNameFromPath($pokemon->image);
            $imageService->update($request, $image_id);
        }

        $pokemon->update($input);
        $this->shapesSync($pokemon, $request->get('shapes_id'));
        $pokemon->shapes = $pokemon->shapes()->get(['shapes.id', 'name']);
        $this->abilitiesSync($pokemon, $request->get('abilities_id'));
        $pokemon->abilities = $pokemon->abilities()->get(['abilities.id', 'name', 'name_lang_ru']);
        $pokemon = $pokemon->toArray();
        $pokemon['image'] = $imageService->getFullUrl($pokemon['image']);
        $response = [
            'status' => 'updated',
            'pokemon' => $pokemon
        ];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @param PokemonImageService $imageService
     */
    public function destroy(string $id, PokemonImageService $imageService)
    {
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->delete();
        $image_name = $imageService->getNameFromPath($pokemon->image);
        $imageService->delete($image_name);
        $this->shapesDestroy($pokemon);
        $this->abilitiesDestroy($pokemon);
        return response()->json('pokemon id:'.$id.' has been deleted');
    }

    /**
     * @param Pokemon $pokemon
     * @param string $shapes_id
     * @return array[]
     */
    protected function shapesSync(Pokemon $pokemon, string $shapes_id) {

        return $this->relativeSync($pokemon->shapes(), $shapes_id);
    }

    /**
     * @param Pokemon $pokemon
     */
    protected function shapesDestroy(Pokemon $pokemon) {

        $pokemon->shapes()->detach();
    }

    /**
     * @param Pokemon $pokemon
     * @param string $ability_id
     * @return array[]
     */
    protected function abilitiesSync(Pokemon $pokemon, string $ability_id) {

        return $this->relativeSync($pokemon->abilities(), $ability_id);
    }

    /**
     * @param Pokemon $pokemon
     */
    protected function abilitiesDestroy(Pokemon $pokemon) {

        $pokemon->abilities()->detach();
    }

    /**
     * @param BelongsToMany $relative
     * @param string $ids
     * @return array[]
     */
    protected function relativeSync($relative, string $ids) {

        $ids = json_decode($ids);
        $result = [
            'attached' => [], 'detached' => [], 'updated' => []
        ];

        if($ids && is_array($ids)) {
            $result = $relative->sync($ids);
        }
        return $result;
    }
}
