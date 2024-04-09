<?php

namespace App\Exports\Common;

use App\Exceptions\ImageNotFound;
use App\Exceptions\NotDetectionModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;


abstract class BaseExport
{

    protected Collection $condition;
    protected array $imageFields = [];
    private $instance = null;
    protected array $paths = [];

    public function __construct()
    {
        $this->setCondition();
        $this->setData();
        $this->setPath();
    }

    public function setCondition()
    {
        $this->condition = collect(app('request')->all());
    }

    abstract protected function setData();

    protected function setInstance($instance)
    {
        throw_if(!($instance instanceof Model), NotDetectionModel::class);

        return $this->instance = $instance;
    }

    public function getInstance()
    {
        return $this->instance;
    }

    protected function setPath()
    {
        foreach($this->imageFields as $field)
        {
            $path = $this->getImagePath($field);
            array_push($this->paths, $path);
            $this->instance->$field = $this->isLocalImage($path)
                                        ? $path
                                        : $this->saveImageIntoLocal($path);
        }
    }

    protected function isRelation(string $field)
    {
        if ($this->instance->getAttribute($field)) {
            return false;
        }

        $image = $this->getRelation($field);

        throw_if(is_null($image), ImageNotFound::class);

        return true;
    }

    protected function getImagePath(string $field)
    {
        return $this->isRelation($field) ? $this->getRelation($field)->path : $this->instance->$field;
    }

    protected function getRelation(string $field)
    {
        return $this->instance->getRelation('images')
                        ->where('field', $field)
                        ->orderBy('created_at')
                        ->first();
    }

    protected function isLocalImage(string $path)
    {
        return file_exists($path);
    }

    protected function saveImageIntoLocal(string $path)
    {
        $dirSep = Config::get('filesystems.dir_sep');
        $driver = Config::get('filesystems.driver_temp_dir');

        if(filter_var($path, FILTER_VALIDATE_URL)){
            $url = $path;
            $path = parse_url($path)['path'];
        }else{
            $url = Storage::disk($this->driver)->url($path);
        }

        $fileContent = file_get_contents($url);
        $newPath = Config::get('filesystems.temp_dir') . $dirSep  . Carbon::now()->format('YmdH') . $path;
        Storage::disk($driver)->put($newPath, $fileContent);

        $path = $driver == 'public'
                ? $newPath
                : Storage::disk(Config::get('filesystems.driver_temp_dir'))->url($newPath);

        return $path;
    }
}
