<?php
namespace App\Services;

use App\Models\Region;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

class RegionService {

    protected $parentage = [];

    protected $children_ids = [];

    /**
     * @param int|string $id
     * @return Region
     */
    public function getRegion($id) {

        $region = Region::select([
            'id',
            'name',
            'parent_id',
            DB::raw('ST_AsText(geometry) as geometry'),
            'created_at',
            'updated_at'
        ])
            ->where('id', $id)
            ->first();
        return $region;
    }

    /**
     * @param Request $request
     * @return Region
     */
    public function setRegion(Request $request) {

        $region = Region::create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'geometry' => DB::raw('ST_GeometryFromText("'.$request->input('geometry').'", '.config('database.srid').')')
        ]);
        return $region;
    }

    /**
     * @param string|int $id
     * @return array with models Region
     */
    public function getParentage(string|int $id) {

        $region = $this->getRegion($id);
        $this->buildParentage($region);
        return $this->parentage;
    }

    /**
     * @param Region $region
     */
    protected function buildParentage(Region $region) {

        $this->parentage[] = $region;

        if($region->parent_id) {
            $parent = $this->getParent($region);
            $this->buildParentage($parent);
        }
    }

    /**
     * @param Region $region
     * @return Region
     */
    public function getParent(Region $region) {

        return $this->getRegion($region->parent_id);
    }

    /**
     * @param int|string $id
     * @return array
     */
    public function getChildrenIds($id) {

        $this->getChildren($id);
        return $this->children_ids;
    }

    /**
     * @param $id
     * @return array
     */
    public function getChildren($id) {

        $children = Region::where('parent_id', $id)->get(['id', 'name']);

        $children->map(function ($child) {
            $this->children_ids[] = $child->id;
            $deep_ch = $this->getChildren($child->id);
            $child->setAttribute('region_children', $deep_ch);
            return $child;
        });
        return $children->toArray();
    }
}
