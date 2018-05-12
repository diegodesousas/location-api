<?php

namespace App\Action\City;

use App\Action\CRUD\Delete as BaseDelete;
use App\Model\City;

class Delete extends BaseDelete
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}