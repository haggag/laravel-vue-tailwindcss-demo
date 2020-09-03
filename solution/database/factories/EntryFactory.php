<?php

/** @var Factory $factory */

use App\Entry;
use App\Model;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Entry::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'label' => rtrim($faker->sentence(4), '.'),
        'amount_cents' => $faker->numberBetween(-1000, 100),
        'date_time' => $faker->dateTimeBetween('-5 days')
    ];
});
