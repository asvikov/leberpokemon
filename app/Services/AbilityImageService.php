<?php
namespace App\Services;

use App\Services\ImageService;

class AbilityImageService extends ImageService {

    protected $path_origin_img = 'images/pokemon_ability/';

    protected $path_resize_img = 'images/pokemon_ability/resized/';

    protected $fillable = [
        'image_file'
    ];

    /**
     * call setResizeable to set variations image size
     */
    public function setImageAttribute()
    {
        $this->setResizeable(100, 100, '_sm_100x100');
    }
}
