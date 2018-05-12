<?php

namespace App\Action\State;

use App\Action\CRUD\Read as BaseRead;
use App\Model\State;

class Read extends BaseRead
{
    public function __construct(State $model)
    {
        parent::__construct($model);
    }
}