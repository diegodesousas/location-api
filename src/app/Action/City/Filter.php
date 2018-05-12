<?php

namespace App\Action\City;

use App\Action\Filter\BasicFilterAction;
use App\Model\City;
use App\Validation\Filled;

class Filter extends BasicFilterAction
{
    /**
     * Filter constructor.
     * @param City $model
     */
    public function __construct(City $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    protected function attributesToFilter(): array
    {
        return [
            'name' => [
                'operator' => 'like',
                'partial' => true,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function relationsToFilter(): array
    {
        return [
            'state' => [
                'attrs' => [
                    'name' => [
                        'operator' => 'like',
                        'partial' => true,
                        'alias' => 'state_name'
                    ]
                ]
            ]
        ];
    }

    /**
     * Define action attributes and validations
     */
    public function attributes(): void
    {
        $this
            ->addAttribute('state_name')
                ->addRule(new Filled())
                ->build()
            ->addAttribute('name')
                ->addRule(new Filled())
                ->build();
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
        $queryBuilder = $this->getQueryBuilder();

        $this->results()->put('cities', $queryBuilder->get());
    }
}