<?php

namespace App\Action\City;

use App\Action\CRUD\Read as BaseRead;
use App\Model\City;

class Read extends BaseRead
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}