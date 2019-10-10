<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    $created =$faker->dateTimeBetween('-1 year', '-11 days');
    $updated = clone $created;
    $updated->add(DateInterval::createFromDateString('+' . $faker->numberBetween(0, 10) . 'days'));
    return [
        'title' => $faker->sentence(6, true),
        'excerpt' => $faker->sentences(3, true),
        'body' => $faker->paragraphs(3, true),
        'created_at' => $created,
        'updated_at' => $updated
    ];
});
