<?php

namespace Database\Factories;

use App\Base\Enums\SavedPaymentMethodType;
use App\Models\Customer;
use App\Models\SavedPaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavedPaymentMethod>
 */
class SavedPaymentMethodFactory extends Factory
{
    protected $model = SavedPaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(SavedPaymentMethodType::cases());

        return [
            'customer_id' => Customer::query()->orderBy('id')->value('id') ?? Customer::factory(),
            'type' => $type,
            'stripe_customer_id' => $type === SavedPaymentMethodType::Card ? $this->faker->uuid() : null,
            'stripe_payment_method_id' => $type === SavedPaymentMethodType::Card ? $this->faker->uuid() : null,
            'last_four' => $type === SavedPaymentMethodType::Card ? $this->faker->numerify('####') : null,
            'brand' => $type === SavedPaymentMethodType::Card ? $this->faker->randomElement(['Visa', 'Mastercard']) : null,
            'expiry_month' => $type === SavedPaymentMethodType::Card ? $this->faker->numberBetween(1, 12) : null,
            'expiry_year' => $type === SavedPaymentMethodType::Card ? now()->addYears(2)->year : null,
            'paypal_email' => $type === SavedPaymentMethodType::Paypal ? $this->faker->safeEmail() : null,
            'payoneer_account_id' => $type === SavedPaymentMethodType::Payoneer ? strtoupper($this->faker->bothify('PAY-########')) : null,
            'is_default' => false,
        ];
    }
}
