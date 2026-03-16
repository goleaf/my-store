<?php

namespace App\Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Customer;

class CustomerFactory extends BaseFactory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'title' => null,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'company_name' => $this->faker->boolean ? $this->faker->company : null,
            'tax_identifier' => $this->faker->boolean ? Str::random() : null,
            'account_ref' => strtoupper($this->faker->bothify('CUST-####')),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'password' => Hash::make('password'),
            'status' => $this->faker->randomElement(['active', 'unverified']),
            'locale' => 'en',
            'avatar' => null,
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'last_login_at' => now()->subDays($this->faker->numberBetween(0, 30)),
            'attribute_data' => [],
            'meta' => $this->faker->boolean ? ['account_no' => Str::random()] : null,
        ];
    }
}
