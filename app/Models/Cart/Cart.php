<?php

namespace App\Models\Cart;

use Darryldecode\Cart\ItemCollection;
use Illuminate\Database\Eloquent\Model;

class Cart extends \Darryldecode\Cart\Cart
{
    private $addressKey;
    private $restaurantKey;

    public function __construct($session, $events, $instanceName, $session_key, $config)
    {
        $this->addressKey = $session_key . '_address';
        $this->restaurantKey = $session_key . '_restaurant';

        parent::__construct($session, $events, $instanceName, $session_key, $config);
    }

    public function addAddress($value)
    {
        if ($row = DatabaseStorageModel::find($this->addressKey)) {
            // update
            $row->cart_data = $value;
            $row->save();
        } else {
            DatabaseStorageModel::create([
                'id' => $this->addressKey,
                'cart_data' => $value
            ]);
        }
        return true;
    }

    public function getAddress()
    {
        $row = DatabaseStorageModel::find($this->addressKey);
        if ($row) {
            return $row->cart_data;
        }
        return null;
    }

    public function removeAddress()
    {
        return DatabaseStorageModel::where('id', $this->addressKey)->delete();
    }

    public function addRestaurant($value)
    {
        if ($row = DatabaseStorageModel::find($this->restaurantKey)) {
            // update
            $row->cart_data = $value;
            $row->save();
        } else {
            DatabaseStorageModel::create([
                'id' => $this->restaurantKey,
                'cart_data' => $value
            ]);
        }
        return true;
    }

    public function getRestaurant()
    {
        $row = DatabaseStorageModel::find($this->restaurantKey);
        if ($row) {
            return $row->cart_data;
        }
        return null;
    }

    public function removeRestaurant()
    {
        return DatabaseStorageModel::where('id', $this->restaurantKey)->delete();
    }
}
