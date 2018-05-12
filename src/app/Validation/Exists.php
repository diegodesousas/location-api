<?php

namespace App\Validation;

use Jenssegers\Mongodb\Eloquent\Model;

class Exists extends Rule
{
    /**
     * @var Model
     */
    private $model;

    /**
     * Exists constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /**
     * Build validation laravel format
     *
     * @return string
     */
    function build(): string
    {
        return sprintf('exists:%s,_id', $this->model->getTable()) ;
    }
}