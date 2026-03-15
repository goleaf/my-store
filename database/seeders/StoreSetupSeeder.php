<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Store\FieldTypes\TranslatedText;
use App\Store\Models\Attribute;
use App\Store\Models\AttributeGroup;
use App\Store\Models\Channel;
use App\Store\Models\Collection;
use App\Store\Models\CollectionGroup;
use App\Store\Models\Country;
use App\Store\Models\Currency;
use App\Store\Models\CustomerGroup;
use App\Store\Models\Language;
use App\Store\Models\Product;
use App\Store\Models\ProductType;
use App\Store\Models\TaxClass;
use App\Store\Models\TaxZone;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Throwable;

class StoreSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedAdminUser();
            $this->seedCountries();
            $this->seedChannel();
            $this->seedLanguage();
            $this->seedCurrency();
            $this->seedCustomerGroup();
            $this->seedCollectionGroup();
            $this->seedTaxClass();
            $this->seedTaxZone();
            $this->seedAttributes();
            $this->seedProductType();
        });
    }

    private function seedCountries(): void
    {
        if (Country::count()) {
            return;
        }

        if (app()->environment('testing')) {
            $this->call(CountrySeeder::class);

            return;
        }

        try {
            $exitCode = $this->callImportAddressData();
        } catch (Throwable) {
            $exitCode = 1;
        }

        if ($exitCode === 0 && Country::count()) {
            return;
        }

        $this->call(CountrySeeder::class);
    }

    private function seedAdminUser(): void
    {
        if (! class_exists(Staff::class) || Staff::whereAdmin(true)->exists()) {
            return;
        }

        $credentials = $this->resolveAdminCredentials();

        if (! $credentials) {
            return;
        }

        $this->callCreateAdmin($credentials);
    }

    /**
     * @return array<string, string>|null
     */
    private function resolveAdminCredentials(): ?array
    {
        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            return null;
        }

        $raw = File::get($envPath);
        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        $values = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, "\"'");

            if ($key !== '') {
                $values[$key] = $value;
            }
        }

        $firstName = $values['ADMIN_FIRSTNAME'] ?? null;
        $lastName = $values['ADMIN_LASTNAME'] ?? null;
        $email = $values['ADMIN_EMAIL'] ?? null;
        $password = $values['ADMIN_PASSWORD'] ?? null;

        if (! $firstName || ! $lastName || ! $email || ! $password) {
            return null;
        }

        if (strlen($password) < 8) {
            return null;
        }

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
        ];
    }

    /**
     * @param  array<string, string>  $credentials
     */
    private function callCreateAdmin(array $credentials): void
    {
        $payload = [
            '--firstname' => $credentials['first_name'],
            '--lastname' => $credentials['last_name'],
            '--email' => $credentials['email'],
            '--password' => $credentials['password'],
            '--no-interaction' => true,
        ];

        foreach (['lunar:create-admin', 'admin:create-admin'] as $command) {
            try {
                Artisan::call($command, $payload);

                return;
            } catch (Throwable) {
                continue;
            }
        }
    }

    private function callImportAddressData(): int
    {
        foreach (['lunar:import:address-data', 'store:import:address-data'] as $command) {
            try {
                return Artisan::call($command);
            } catch (Throwable) {
                continue;
            }
        }

        return 1;
    }

    private function seedChannel(): void
    {
        if (Channel::whereDefault(true)->exists()) {
            return;
        }

        Channel::create([
            'name' => 'Webstore',
            'handle' => 'webstore',
            'default' => true,
            'url' => 'http://localhost',
        ]);
    }

    private function seedLanguage(): void
    {
        if (Language::count()) {
            return;
        }

        Language::create([
            'code' => 'en',
            'name' => 'English',
            'default' => true,
        ]);
    }

    private function seedCurrency(): void
    {
        if (Currency::whereDefault(true)->exists()) {
            return;
        }

        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'exchange_rate' => 1,
            'decimal_places' => 2,
            'default' => true,
            'enabled' => true,
        ]);
    }

    private function seedCustomerGroup(): void
    {
        if (CustomerGroup::whereDefault(true)->exists()) {
            return;
        }

        CustomerGroup::create([
            'name' => 'Retail',
            'handle' => 'retail',
            'default' => true,
        ]);
    }

    private function seedCollectionGroup(): void
    {
        if (CollectionGroup::count()) {
            return;
        }

        CollectionGroup::create([
            'name' => 'Main',
            'handle' => 'main',
        ]);
    }

    private function seedTaxClass(): void
    {
        if (TaxClass::count()) {
            return;
        }

        TaxClass::create([
            'name' => 'Default Tax Class',
            'default' => true,
        ]);
    }

    private function seedTaxZone(): void
    {
        if (TaxZone::count()) {
            return;
        }

        $taxZone = TaxZone::create([
            'name' => 'Default Tax Zone',
            'zone_type' => 'country',
            'price_display' => 'tax_exclusive',
            'default' => true,
            'active' => true,
        ]);

        $taxZone->countries()->createMany(
            Country::get()->map(fn ($country) => [
                'country_id' => $country->id,
            ])->toArray(),
        );
    }

    private function seedAttributes(): void
    {
        if (Attribute::count()) {
            return;
        }

        $productGroup = AttributeGroup::create([
            'attributable_type' => Product::morphName(),
            'name' => collect([
                'en' => 'Details',
            ]),
            'handle' => 'details',
            'position' => 1,
        ]);

        $collectionGroup = AttributeGroup::create([
            'attributable_type' => Collection::morphName(),
            'name' => collect([
                'en' => 'Details',
            ]),
            'handle' => 'collection_details',
            'position' => 1,
        ]);

        Attribute::create([
            'attribute_type' => 'product',
            'attribute_group_id' => $productGroup->id,
            'position' => 1,
            'name' => [
                'en' => 'Name',
            ],
            'handle' => 'name',
            'section' => 'main',
            'type' => TranslatedText::class,
            'required' => true,
            'default_value' => null,
            'configuration' => [
                'richtext' => false,
            ],
            'system' => true,
            'description' => [
                'en' => '',
            ],
        ]);

        Attribute::create([
            'attribute_type' => 'collection',
            'attribute_group_id' => $collectionGroup->id,
            'position' => 1,
            'name' => [
                'en' => 'Name',
            ],
            'handle' => 'name',
            'section' => 'main',
            'type' => TranslatedText::class,
            'required' => true,
            'default_value' => null,
            'configuration' => [
                'richtext' => false,
            ],
            'system' => true,
            'description' => [
                'en' => '',
            ],
        ]);

        Attribute::create([
            'attribute_type' => 'product',
            'attribute_group_id' => $productGroup->id,
            'position' => 2,
            'name' => [
                'en' => 'Description',
            ],
            'handle' => 'description',
            'section' => 'main',
            'type' => TranslatedText::class,
            'required' => false,
            'default_value' => null,
            'configuration' => [
                'richtext' => true,
            ],
            'system' => false,
            'description' => [
                'en' => '',
            ],
        ]);

        Attribute::create([
            'attribute_type' => 'collection',
            'attribute_group_id' => $collectionGroup->id,
            'position' => 2,
            'name' => [
                'en' => 'Description',
            ],
            'handle' => 'description',
            'section' => 'main',
            'type' => TranslatedText::class,
            'required' => false,
            'default_value' => null,
            'configuration' => [
                'richtext' => true,
            ],
            'system' => false,
            'description' => [
                'en' => '',
            ],
        ]);
    }

    private function seedProductType(): void
    {
        if (ProductType::count()) {
            return;
        }

        $type = ProductType::create([
            'name' => 'Stock',
        ]);

        $type->mappedAttributes()->attach(
            Attribute::whereAttributeType(
                Product::morphName()
            )->get()->pluck('id')
        );
    }
}
