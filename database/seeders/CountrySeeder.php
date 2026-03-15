<?php

namespace Database\Seeders;

use App\Store\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seedCountries() as $country) {
            Country::updateOrCreate(
                ['iso3' => $country['iso3']],
                $country,
            );
        }
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    private function seedCountries(): array
    {
        return [
            $this->country('GBR', 'GB', 'United Kingdom', '44', 'London', 'GBP'),
            $this->country('USA', 'US', 'United States', '1', 'Washington', 'USD'),
            $this->country('AUT', 'AT', 'Austria', '43', 'Vienna', 'EUR'),
            $this->country('BEL', 'BE', 'Belgium', '32', 'Brussels', 'EUR'),
            $this->country('BGR', 'BG', 'Bulgaria', '359', 'Sofia', 'BGN'),
            $this->country('HRV', 'HR', 'Croatia', '385', 'Zagreb', 'EUR'),
            $this->country('CYP', 'CY', 'Cyprus', '357', 'Nicosia', 'EUR'),
            $this->country('CZE', 'CZ', 'Czechia', '420', 'Prague', 'CZK'),
            $this->country('DNK', 'DK', 'Denmark', '45', 'Copenhagen', 'DKK'),
            $this->country('EST', 'EE', 'Estonia', '372', 'Tallinn', 'EUR'),
            $this->country('FIN', 'FI', 'Finland', '358', 'Helsinki', 'EUR'),
            $this->country('FRA', 'FR', 'France', '33', 'Paris', 'EUR'),
            $this->country('DEU', 'DE', 'Germany', '49', 'Berlin', 'EUR'),
            $this->country('GRC', 'GR', 'Greece', '30', 'Athens', 'EUR'),
            $this->country('HUN', 'HU', 'Hungary', '36', 'Budapest', 'HUF'),
            $this->country('IRL', 'IE', 'Ireland', '353', 'Dublin', 'EUR'),
            $this->country('ITA', 'IT', 'Italy', '39', 'Rome', 'EUR'),
            $this->country('LVA', 'LV', 'Latvia', '371', 'Riga', 'EUR'),
            $this->country('LTU', 'LT', 'Lithuania', '370', 'Vilnius', 'EUR'),
            $this->country('LUX', 'LU', 'Luxembourg', '352', 'Luxembourg', 'EUR'),
            $this->country('MLT', 'MT', 'Malta', '356', 'Valletta', 'EUR'),
            $this->country('NLD', 'NL', 'Netherlands', '31', 'Amsterdam', 'EUR'),
            $this->country('POL', 'PL', 'Poland', '48', 'Warsaw', 'PLN'),
            $this->country('ROU', 'RO', 'Romania', '40', 'Bucharest', 'RON'),
            $this->country('SVK', 'SK', 'Slovakia', '421', 'Bratislava', 'EUR'),
            $this->country('ESP', 'ES', 'Spain', '34', 'Madrid', 'EUR'),
            $this->country('SWE', 'SE', 'Sweden', '46', 'Stockholm', 'SEK'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function country(
        string $iso3,
        string $iso2,
        string $name,
        string $phonecode,
        string $capital,
        string $currency,
    ): array {
        return [
            'name' => $name,
            'iso3' => $iso3,
            'iso2' => $iso2,
            'phonecode' => $phonecode,
            'capital' => $capital,
            'currency' => $currency,
            'native' => $name,
            'emoji' => $iso2,
            'emoji_u' => "U+{$iso2}",
        ];
    }
}
