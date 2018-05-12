<?php

namespace App\Action;

use App\Validation\Rule;

class Attribute
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var BaseAction
     */
    private $action;

    /**
     * @var string
     */
    private $rules = [];

    /**
     * Attribute constructor.
     * @param string $name
     * @param BaseAction $action
     */
    public function __construct(string $name, BaseAction $action)
    {
        $this->name = $name;

        $this->action = $action;
    }

    /**
     * @param Rule $rule
     * @return Attribute
     */
    public function addRule(Rule $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return BaseAction
     */
    public function build(): BaseAction
    {
        return $this->action;
    }

    /**
     * Get property rules in laravel format
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = collect($this->rules)
            ->map(function(Rule $rule) {
                return $rule->build();
            })
            ->toArray();

        return [$this->name => $rules];
    }
}