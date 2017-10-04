<?php

namespace App\Transformers;

use Illuminate\Support\Str;

class UserTransformer extends BaseTransformer
{
    /**
     * Transformer for the clients.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The client model.
     *
     * @return string[] The valid output, displayed in the API.
     */

    public function transform($item) : array
    {
        return [
            'id' => (int) $item->id,
            'first_name' => (string) $item->first_name,
            'last_name' => (string) $item->last_name,
            'email' => (string) $item->email,
            'timeZone' => (string) $item->timeZone,
        ];
    }
}