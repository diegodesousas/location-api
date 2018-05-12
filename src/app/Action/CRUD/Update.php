<?php

namespace App\Action\CRUD;

use App\Action\BaseAction;
use App\Validation\Exists;
use App\Validation\Required;
use Jenssegers\Mongodb\Eloquent\Model;

abstract class Update extends BaseAction
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Update constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct();

        $this->model = $model;
    }

    /**
     * Define action attributes and validations
     */
    public function attributes(): void
    {
        $this
            ->addAttribute('id')
                ->addRule(new Required())
                ->addRule(new Exists($this->model))
                ->build();
    }

    /**
     *
     */
    public function run(): void
    {
        $id = array_get($this->data(), 'id');

        $model = $this->model->find($id);

        $model->fill($this->modelData())->save();
    }

    protected abstract function modelData(): array;
}