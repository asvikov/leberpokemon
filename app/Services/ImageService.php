<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageService {

    protected $storage_disc = 'public';

    protected $path_origin_img = 'images/';

    protected $path_resize_img = 'images/resized/';

    protected $fillable = [
        'image'
    ];

    protected $resizeable = [];

    public function __construct()
    {
        $this->setImageAttribute();
    }

    /**
     * get all files in $path_resize_img
     * @return array
     */
    public function all() {

        $file_list = Storage::disk($this->storage_disc)->files($this->path_resize_img);
        $file_list_names = str_replace($this->path_resize_img, '', $file_list);
        $result = [];

        foreach ($file_list_names as $name) {
            $result[] = [
                'id' => $name,
                'url' => Storage::disk($this->storage_disc)->url($this->path_resize_img . $name)
            ];
        }
        return $result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function findOrFail(string $id) {

        if(Storage::disk($this->storage_disc)->exists($this->path_resize_img . $id)) {
            return [
                'id' => $id,
                'url' => Storage::disk($this->storage_disc)->url($this->path_resize_img . $id)
            ];
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param string $name
     * @param Request $request
     * @return array
     */
    public function create(Request $request, $name = '') {

        if($request->hasFile($this->fillable[0])) {
            $path_origin_img = rtrim($this->path_origin_img, '/');

            if($name) {
                $url = $request->file($this->fillable[0])->storeAs($path_origin_img, $name, $this->storage_disc);
            } else {
                $url = $request->file($this->fillable[0])->store($path_origin_img, $this->storage_disc);
            }
            $path = Storage::disk($this->storage_disc)->path($url);
            $this->url_resize_img = [];

            foreach ($this->resizeable as $to_resize) {
                $this->resize($path, $to_resize['with'], $to_resize['height'], $to_resize['postfix']);
            }
            if(!$this->url_resize_img) {
                $this->url_resize_img[] = [
                    'name' => $this->getNameFromPath($url),
                    'url' => $url
                ];
            }
            return $this->url_resize_img;
        }
    }

    /**
     * @param Request $request
     * @param string $id
     * @return array
     */
    public function update(Request $request, string $id) {

        $this->findOrFail($id);
        $this->delete($id);
        $name = $this->getNameWithoutPostfix($id);
        return $this->create($request, $name);
    }

    /**
     * @param string $id
     */
    public function delete(string $id) {

        $name = $this->getNameWithoutPostfix($id);
        $path = $this->path_origin_img . $name;
        $this->deleteImage($path);
        $postfixes = $this->getPostfixes();

        foreach ($postfixes as $postfix) {

            $full_name = $this->getNameWithPostfix($name, $postfix);
            $this->deleteImage($this->path_resize_img . $full_name);
        }
    }

    /**
     * @param string $path
     */
    protected function deleteImage(string $path) {

        Storage::disk($this->storage_disc)->delete($path);
    }

    /**
     * call setResizeable to set variations image size
     */
    public function setImageAttribute() {}

    /**
     * @param int $with
     * @param int $height
     * @param string $postfix
     */
    protected function setResizeable(int $with, int $height, string $postfix) {

        $this->resizeable[] = [
            'with' => $with,
            'height' => $height,
            'postfix' => $postfix
        ];
    }

    /**
     * @param string $path
     * @param $with
     * @param $height
     * @param string $postfix
     */
    public function resize(string $path, $with, $height, $postfix = '') {

        $path_resize = Storage::disk($this->storage_disc)->path($this->path_resize_img);
        $name = $this->getNameFromPath($path);

        if($postfix) {
            $name = $this->getNameWithPostfix($name, $postfix);
        }
        $image_path = $path_resize . $name;
        $image = Image::read($path);
        $image->resize($with, $height);
        $image->save($image_path);
        $this->url_resize_img[] = [
            'name' => $name,
            'url' => Storage::disk($this->storage_disc)->url($this->path_resize_img . $name)
        ];
    }

    /**
     * @param string $url
     * @return string
     */
    public function getRelativeUrl(string $url) {

        $server_url = Storage::disk($this->storage_disc)->url('');
        return str_replace($server_url, '', $url);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getFullUrl(string $path) {

        return Storage::disk($this->storage_disc)->url($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getNameFromPath(string $path) {

        $expl_path = explode('/', $path);
        $name = array_pop($expl_path);
        return $name;
    }

    /**
     * @return array
     */
    protected function getPostfixes() {

        $postfixes = [];

        foreach ($this->resizeable as $to_resize) {
            $postfixes[] = $to_resize['postfix'];
        }
        return $postfixes;
    }

    /**
     * @param string $name
     * @return string|null
     */
    protected function getNameWithoutPostfix(string $name) {

        $postfixes = $this->getPostfixes();
        $pat_post = implode('|', $postfixes);
        $pattern = '/('.$pat_post.')\.(\w+)$/';
        return preg_replace($pattern, '.$2', $name);
    }

    /**
     * @param string $name
     * @param string $postfix
     * @return string
     */
    protected function getNameWithPostfix(string $name, string $postfix) {

        $expl_name = explode('.', $name);
        $extension = array_pop($expl_name);
        $name_without_ext = implode('.', $expl_name);
        $name_posf = $name_without_ext . $postfix . '.' . $extension;
        return $name_posf;
    }
}
