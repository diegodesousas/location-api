<?php

namespace App\Action\State;

use App\Action\Filter\BasicFilterAction;
use App\Model\State;

class Filter extends BasicFilterAction
{
    /**
     * Filter constructor.
     * @param State $model
     */
    public function __construct(State $model)
    {
        parent::__construct($model);
    }

    /**
     * Run logic action
     */
    public function run(): void
    {
        $builder = $this->getQueryBuilder();

        $this->results()->put('states', $builder->get());
    }

    /**
     * Config attributes to filter in model
     *
     * @return array
     */
    protected function attributesToFilter(): array
    {
        return [
            'name' => [
                'operator' => 'like',
                'partial' => true
            ],
            'abbreviation' => [
                'operator' => 'like',
                'partial' => true
            ],
        ];
    }

    /**
     * Config relations to filter in model
     *
     * @return array
     */
    protected function relationsToFilter(): array
    {
        return [];
    }
}