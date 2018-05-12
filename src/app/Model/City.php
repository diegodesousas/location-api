<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class City extends Eloquent
{
    protected $fillable = [
        'name',
        'state_id'
    ];

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return array|static
     */
    public function toArray()
    {
        $default = parent::toArray();

        array_forget($default, 'state_id');

        return collect($default)->merge([
           'state' => $this->state->toArray()
        ]);
    }


}
