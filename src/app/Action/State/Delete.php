<?php

namespace App\Action\State;

use App\Action\CRUD\Delete as BaseDelete;
use App\Model\State;
use App\Validation\Exists;
use App\Validation\NotHaveCity;
use App\Validation\Required;

class Delete extends BaseDelete
{
    public function __construct(State $model)
    {
        parent::__construct($model);
    }

    public function attributes(): void
    {
        parent::attributes();

        $this
            ->addAttribute('id')
            ->addRule(new Required())
            ->addRule(new Exists($this->model))
            ->addRule(new NotHaveCity())
            ->build();
    }
}