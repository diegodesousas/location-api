<?php

namespace App\Action\State;

use App\Action\CRUD\Update as BaseUpdate;
use App\Model\State;
use App\Validation\Filled;

class Update extends BaseUpdate
{
    public function __construct(State $model)
    {
        parent::__construct($model);
    }

    public function attributes(): void
    {
        parent::attributes();

        $this
            ->addAttribute('state.name')
                ->addRule(new Filled())
                ->build()
            ->addAttribute('state.abbreviation')
                ->addRule(new Filled())
                ->build();
    }

    protected function modelData(): array
    {
        return array_get($this->data(), 'state', []);
    }
}