<?php

namespace App\Action;

use Illuminate\Support\Collection;

abstract class BaseAction
{
    /**
     * @var Collection
     */
    protected $results;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * BaseAction constructor.
     */
    public function __construct()
    {
        $this->results = collect();
    }

    /**
     * @return Collection
     */
    public function results(): Collection
    {
        return $this->results;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return collect($this->attributes)
            ->flatMap(function (Attribute $attribute) {
                return $attribute->rules();
            })
            ->toArray();
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return $this->data;
    }

    /**
     * @param $attributeName
     * @return mixed
     */
    public function getAttribute($attributeName)
    {
        return array_get($this->data, $attributeName, null);
    }

    /**
     * Run logic action
     */
    public abstract function run(): void;

    /**
     * @param string $name
     * @return Attribute
     */
    public function addAttribute(string $name): Attribute
    {
        $attr = new Attribute($name, $this);

        $this->attributes[$name] = $attr;

        return $attr;
    }

    /**
     * Define action attributes and validations
     */
    public abstract function attributes(): void;
}