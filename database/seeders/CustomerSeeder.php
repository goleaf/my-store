<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Country;
use App\Models\Customer;

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
                Address::factory()->create([
                    'shipping_default' => true,
                    'billing_default' => true,
                    'contact_email' => $customer->email,
                    'contact_phone' => $customer->phone,
                    'country_id' => $countryId,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'billing_default' => false,
                    'contact_email' => $customer->email,
                    'contact_phone' => $customer->phone,
                    'country_id' => $countryId,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'billing_default' => true,
                    'contact_email' => $customer->email,
                    'contact_phone' => $customer->phone,
                    'country_id' => $countryId,
                    'customer_id' => $customer->id,
                ]);

                Address::factory()->create([
                    'shipping_default' => false,
                    'billing_default' => false,
                    'contact_email' => $customer->email,
                    'contact_phone' => $customer->phone,
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
