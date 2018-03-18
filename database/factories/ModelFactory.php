<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

function getReferenceId(string $model): int
{
    $class = '\App\Models\\'.$model;
    if($ids = $class::all('id')->toArray()){
        return array_random($ids)['id'];
    }
    return false;
}

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;
    $name = explode(' ', $faker->name);
    return [
        'first_name'     =>$name[0],
        'last_name'      =>$name[1],
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'timeZone'       => 'CET',
        'remember_token' => str_random(10),
        'token'          => str_random(10),
    ];
});

// City
$factory->define(App\Models\City::class, function (Faker\Generator $faker) {
    return [
      'name' => $faker->city
    ];
});

// Country
$factory->define(App\Models\Country::class, function (Faker\Generator $faker) {
    return [
      'name' => $faker->country
    ];
});

// Organisation
$factory->define(App\Models\Organisation::class, function (Faker\Generator $faker) {
    return [
      'name' => $faker->word
    ];
});

// Client
$factory->define(App\Models\Client::class, function (Faker\Generator $faker) {
    $name = explode(' ', $faker->name);
    return [
      'first_name' => $name[0],
      'last_name'  => $name[1],
      'avatar'     => $faker->image(storage_path('images'),  100, 100,'people')
    ];
});

// Product
$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    return [
      'name'         => $faker->word,
      'description'  => $faker->text(100),
      'coast'        => $faker->randomFloat(3,150),
      'image'        => $faker->image(storage_path('images'), 100, 100, 'food')
    ];
});

// Phone
$factory->define(App\Models\Phone::class, function (Faker\Generator $faker) {
    return [
      'name' => $faker->phoneNumber,
    ];
});

// Email
$factory->define(App\Models\Email::class, function (Faker\Generator $faker) {
    return [
      'name' => $faker->email,
    ];
});


// =========== With foreign key ==============


// OrganisationInformation
$factory->define(App\Models\OrganisationInformation::class, function (Faker\Generator $faker) {
    return [
      'description' => $faker->text(100),
      'organisation_type_id' => getReferenceId('TypeOrganisation')
    ];
});

// ClientAddress
$factory->define(App\Models\ClientAdress::class, function (Faker\Generator $faker) {
    return [
      'city_id'     => getReferenceId('City'),
      'country_id'  => getReferenceId('Country'),
      'client_id'   => getReferenceId('Client'),
      'street'      => $faker->streetName
    ];
});

// ClientEmail
$factory->define(App\Models\ClientEmail::class, function (Faker\Generator $faker) {
    return [
      'email_id'  => getReferenceId('Email'),
      'client_id' => getReferenceId('Client'),
    ];
});

// ClientPhone
$factory->define(App\Models\ClientPhone::class, function (Faker\Generator $faker) {
    return [
      'phone_id'  => getReferenceId('Phone'),
      'client_id' => getReferenceId('Client'),
    ];
});

// ProductDiscounts
$factory->define(App\Models\ProductDiscount::class, function (Faker\Generator $faker) {
    return [
      'discount_id' => getReferenceId('Discount'),
      'product_id'  => getReferenceId('Product'),
    ];
});

// UsersPermission
$factory->define(App\Models\UsersPermission::class, function (Faker\Generator $faker) {
    return [
      'permission_id' => getReferenceId('Permission'),
      'user_id'       => getReferenceId('User'),
    ];
});

// UsersRoles
$factory->define(App\Models\UsersRoles::class, function (Faker\Generator $faker) {
    return [
      'role_id' => getReferenceId('Role'),
      'user_id' => getReferenceId('User'),
    ];
});



