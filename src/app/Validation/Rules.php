<?php

namespace App\Validation;

/**
 * Class Rules
 *
 * @package App\Validation
 */
class Rules
{
    /**
     * @var array
     */
    private $rules = [];

    /**
     * Rules constructor.
     */
    public function __construct()
    {
        $this->rules = collect();
    }


    /**
     * @param Rule $rule
     * @return Rules
     */
    public function add(Rule $rule): self
    {
        $this->rules->push($rule);

        return $this;
    }

    /**
     * Build rules to array format
     *
     * @return array
     */
    public function build(): array
    {
        return $this->rules
            ->map(function (Rule $rule) {
                return $rule->build();
            })
            ->toArray();
    }
}