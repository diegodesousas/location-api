<?php

namespace App\Validation;

use App\Model\City;
use Illuminate\Contracts\Translation\Translator;

class Validator extends \Illuminate\Validation\Validator
{
    public function __construct(Translator $translator)
    {
        parent::__construct($translator, [], [], []);

        $this->setPresenceVerifier(resolve('validation.presence'));
    }

    public function validateNotHaveCity($attribute, $stateId, $parameters, $validator)
    {
        return City::where('state_id', $stateId)->count() == 0;
    }
}