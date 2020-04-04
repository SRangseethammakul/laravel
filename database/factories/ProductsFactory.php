<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'price' => $faker->biasedNumberBetween(200,5000),
        'category_id' => $faker->biasedNumberBetween(1,4),
        'created_at' => $faker->dateTime($max='now'),
        'updated_at' => $faker->dateTime($max='now')
    ];
});
