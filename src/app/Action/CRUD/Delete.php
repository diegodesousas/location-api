<?php

namespace App\Action\CRUD;

use App\Action\BaseAction;
use App\Validation\Exists;
use App\Validation\Required;
use Jenssegers\Mongodb\Eloquent\Model;

class Delete extends BaseAction
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Delete constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct();

        $this->model = $model;
    }

    /**
     * Run logic action
     */
    public function run(): void
    {
        $id = array_get($this->data(), 'id');

        $this->model->find($id)->delete();
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
}