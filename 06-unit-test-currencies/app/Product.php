<?php

namespace App;
use App\Services\CurrencyService;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price'];

    public function getPriceEurAttribute()
    {
        return (new CurrencyService())->convert($this->price, 'usd', 'eur');
    }
}
