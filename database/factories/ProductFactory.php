<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Product;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => $faker->text($maxNbChars = 20),
        'description' => $faker->sentence(8),
        'price' => $faker->numberBetween(100, 900),
        'stock' => $faker->numberBetween(10, 100),
        'image' => 'product.jpeg'
    ];
});

