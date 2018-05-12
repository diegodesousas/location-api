<?php

namespace App\Action\Filter;

use App\Action\BaseAction;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

abstract class BasicFilterAction extends BaseAction
{
    /**
     * @var Eloquent
     */
    protected $model;

    /**
     * Filter constructor.
     * @param Eloquent $model
     */
    public function __construct(Eloquent $model)
    {
        parent::__construct();

        $this->model = $model;
    }

    /**
     * Config attributes to filter in model
     *
     * @return array
     */
    protected abstract function attributesToFilter(): array;

    /**
     * Config relations to filter in model
     *
     * @return array
     */
    protected abstract function relationsToFilter(): array;

    /**
     * @param Builder $builder
     * @param string $name
     * @param array $config
     */
    private function addWhereInBuilder(Builder $builder, string $name, array $config) {
        $attr = array_get($config, 'alias', $name);

        if ($value = $this->getAttribute($attr)) {

            $operator = array_get($config, 'operator', '=');

            $value = array_get($config, 'partial', false) ? "%".$value."%" : $value;

            $builder->where($name, $operator, $value);
        }
    }

    /**
     * Run logic action
     */
    protected function getQueryBuilder(): Builder
    {
        $builder = $this->model->where(function (Builder $builder) {

            foreach ($this->attributesToFilter() as $name => $config) {

                $this->addWhereInBuilder($builder, $name, $config);
            }
        });

        foreach ($this->relationsToFilter() as $relation => $relationConfig) {
            $builder->whereHas($relation, function (Builder $builder) use ($relationConfig) {

                foreach (array_get($relationConfig, 'attrs', []) as $name => $config) {
                    $this->addWhereInBuilder($builder, $name, $config);
                }
            });
        }

        return $builder;
    }

    /**
     * Define action attributes and validations
     */
    public function attributes(): void {}
}