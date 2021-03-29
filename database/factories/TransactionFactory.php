<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Transaction;
use App\Models\User;
use Faker\Generator as Faker;


$factory->define(Transaction::class, function (Faker $faker) {
    $users = User::pluck('id')->toArray();

    return [
        'user_id' => $faker->randomElement($users),
        'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'fl_operation' => $faker->numberBetween($min = 0, $max = 1),
        'fl_reversal' => 0,
        'value' => $faker->randomFloat($nbMaxDecimals = 2, $min = 10.00, $max = 1000.00),
        'transaction_id' => null,
        'date_transaction' => $faker->dateTime
    ];
});
