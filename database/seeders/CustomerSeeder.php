<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use App\Store\Models\Address;
use App\Store\Models\Country;
use App\Store\Models\Customer;

class CustomerSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run(): void
    {
        DB::transaction(function () {
            $faker = Factory::create();
            $countryId = $this->resolveCountryId();
            $customers = Customer::factory(100)->create();

            foreach ($customers as $customer) {
                for ($i = 0; $i < $faker->numberBetween(1, 10); $i++) {
                    $user = User::factory()->create();

                    $customer->users()->attach($user);
                }

                Address::factory()->create([
                    'shipping_default' => true,
                    'country_id' => $countryId,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'country_id' => $countryId,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'billing_default' => true,
                    'country_id' => $countryId,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'billing_default' => false,
                    'country_id' => $countryId,
                    'customer_id' => $customer->id,
                ]);
            }
        });
    }

    private function resolveCountryId(): int
    {
        $countryId = Country::where('iso3', 'USA')->value('id');

        if ($countryId) {
            return $countryId;
        }

        $country = Country::updateOrCreate(
            ['iso3' => 'USA'],
            [
                'name' => 'United States',
                'iso2' => 'US',
                'phonecode' => '1',
                'capital' => 'Washington',
                'currency' => 'USD',
                'native' => 'United States',
                'emoji' => 'US',
                'emoji_u' => 'U+US',
            ],
        );

        return $country->id;
    }
}
