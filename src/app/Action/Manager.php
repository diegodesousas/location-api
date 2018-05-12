<?php

namespace App\Action;

use App\Validation\Validator;
use Illuminate\Support\Collection;

class Manager
{
    /**
     * @var array
     */
    private $queue = [];

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Collection
     */
    private $results;

    /**
     * @var Collection
     */
    private $messages;

    /**
     * Manager constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        $this->results = collect();
        $this->messages = collect();
    }

    /**
     * Run all actions
     */
    public function runActions(): void
    {
        while($current = current($this->queue)) {

            $classname = $current[0];
            $data = $current[1];

            /**
             * Instantiate Action with DI Container
             * @var $action BaseAction
             */
            $action = resolve($classname);
            $action->setData($data);
            $action->attributes();

            $this->validator->setData($data);
            $this->validator->setRules($action->rules());

            if ($this->validator->passes()) {
                $action->run();
                $this->results->put($classname, $action->results());
            } else {
                $this->messages->put($classname, $this->validator->messages()->messages());
            }

            next($this->queue);
        }
    }

    /**
     * @param string $classname
     * @param array $data
     * @return Manager
     */
    public function addAction(string $classname, array $data): self
    {
        $this->queue[] = [$classname, $data];

        return $this;
    }

    /**
     * @return Collection
     */
    public function messages(): Collection
    {
        return $this->messages;
    }

    /**
     * @param $classname
     * @return mixed
     */
    public function result($classname)
    {
        return $this->results->get($classname, null);
    }

    /**
     * String property/rule format
     *
     * @param $rule
     * @return bool
     */
    public function thisRuleFailed(string $rule): bool
    {
        return (bool) array_get($this->validator->failed(), $rule, false);
    }
}