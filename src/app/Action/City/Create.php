<?php

namespace App\Action\City;

use App\Action\CRUD\Create as BaseCreate;
use App\Model\City;
use App\Model\State;
use App\Validation\Exists;
use App\Validation\Present;
use App\Validation\Required;
use App\Validation\Sometimes;

/**
 * Class Create
 *
 * @package App\Action\CIty
 */
class Create extends BaseCreate
{
    /**
     * Create constructor.
     *
     * @param City $model
     */
    public function __construct(City $model)
    {
        parent::__construct($model);
    }

    /**
     * Data to fill model
     *
     * @return array
     */
    protected function modelData(): array
    {
        return array_get($this->data(), 'city', []);
    }

    /**
     * Define action attributes and validations
     */
    public function attributes(): void
    {
        $this
            ->addAttribute('city')
                ->addRule(new Required())
                ->build()
            ->addAttribute('city.name')
                ->addRule(new Required())
                ->build()
            ->addAttribute('city.state_id')
                ->addRule(new Required())
                ->addRule(new Exists(new State()))
                ->build();
    }
}