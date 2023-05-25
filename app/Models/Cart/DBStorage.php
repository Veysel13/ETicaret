<?php

namespace App\Models\Cart;

use Darryldecode\Cart\CartCollection;
use Illuminate\Database\Eloquent\Model;

class DBStorage extends Model
{
    public function has($key)
    {
        return DatabaseStorageModel::find($key);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return new CartCollection(DatabaseStorageModel::find($key)->cart_data);
        } else {
            return [];
        }
    }

    public function put($key, $value)
    {
        if ($row = DatabaseStorageModel::find($key)) {
            $row->cart_data = $value;
            $row->save();
        } else {
            DatabaseStorageModel::create([
                'id' => $key,
                'cart_data' => $value
            ]);
        }
    }
}
