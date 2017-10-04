<?php

namespace App\Transformers;


class AccountTransformer extends BaseTransformer
{
    /**
     * Transformer for the account.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The user model.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item) : array
    {

        return [
            'id' => (int) $item->id,
            'first_name' => (string) $item->first_name,
            'last_name' => (string) $item->last_name,
            'timeZone' => (string) $item->timeZone,
            'email' => (string) $item->email,
        ];
    }
}
