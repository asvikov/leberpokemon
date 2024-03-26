<?php
namespace App\Services;

use App\Services\ImageService;

class PokemonImageService extends ImageService {

    protected $path_origin_img = 'images/pokemon_portrait/';

    protected $path_resize_img = 'images/pokemon_portrait/resized/';

    protected $fillable = [
        'image_file'
    ];

    /**
     * call setResizeable to set variations image size
     */
    public function setImageAttribute()
    {
        $this->setResizeable(100, 100, '_sm_100x100');
        $this->setResizeable(80, 80, '_sm_80x80');
        $this->setResizeable(50, 50, '_sm_50x50');
    }
}
