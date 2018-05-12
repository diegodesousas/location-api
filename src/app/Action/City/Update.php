<?php

namespace App\Action\City;

use App\Action\CRUD\Update as BaseUpdate;
use App\Model\City;
use App\Model\State;
use App\Validation\Exists;
use App\Validation\Filled;
use App\Validation\Required;

class Update extends BaseUpdate
{
    /**
     * Update constructor.
     * @param City $model
     */
    public function __construct(City $model)
    {
        parent::__construct($model);
    }

    /**
     * @return array
     */
    protected function modelData(): array
    {
        return array_get($this->data(), 'city', []);
    }

    /**
     * @return void
     */
    public function attributes(): void
    {
        parent::attributes();

        $this
            ->addAttribute('city')
                ->addRule(new Required())
                ->build()
            ->addAttribute('city.name')
                ->addRule(new Filled())
                ->build()
            ->addAttribute('city.state_id')
                ->addRule(new Filled())
                ->addRule(new Exists(new State()))
                ->build();
    }


}