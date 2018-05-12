<?php

namespace App\Action\CRUD;

use App\Action\BaseAction;
use Jenssegers\Mongodb\Eloquent\Model;

abstract class Create extends BaseAction
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Create constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct();

        $this->model = $model;
    }

    /**
     * Data to fill model
     *
     * @return array
     */
    protected abstract function modelData(): array;

    /**
     * Run logic action
     */
    public function run(): void
    {
        $this->model->fill($this->modelData())->save();

        $this->results->put('model', $this->model);
    }
}