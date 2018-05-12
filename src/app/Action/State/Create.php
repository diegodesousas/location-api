<?php

namespace App\Action\State;

use App\Action\CRUD\Create as BaseCreate;
use App\Model\State;
use App\Validation\Required;

class Create extends BaseCreate
{
    /**
     * Create constructor.
     *
     * @param State $model
     */
    public function __construct(State $model)
    {
        parent::__construct($model);
    }

    /**
     * Define action attributes and validations
     */
    public function attributes(): void
    {
        $this
            ->addAttribute('state.name')
                ->addRule(new Required())
                ->build()
            ->addAttribute('state.abbreviation')
                ->addRule(new Required())
                ->build();
    }

    /**
     * Data to fill model
     *
     * @return array
     */
    protected function modelData(): array
    {
        return array_get($this->data(), 'state', []);
    }
}