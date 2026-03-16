<?php

namespace App\Store\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Store\Base\Addressable;
use App\Store\Base\BaseModel;
use App\Store\Base\Traits\CachesProperties;
use App\Store\Base\Traits\HasMacros;
use App\Store\Base\Traits\LogsActivity;
use App\Store\Base\ValueObjects\Cart\TaxBreakdown;
use App\Store\Database\Factories\CartAddressFactory;
use App\Store\DataTypes\Price;
use App\Store\DataTypes\ShippingOption;

/**
 * @property int $id
 * @property int $cart_id
 * @property ?int $country_id
 * @property ?string $title
 * @property ?string $first_name
 * @property ?string $last_name
 * @property ?string $company_name
 * @property ?string $tax_identifier
 * @property ?string $line_one
 * @property ?string $line_two
 * @property ?string $line_three
 * @property ?string $city
 * @property ?string $state
 * @property ?string $postcode
 * @property ?string $delivery_instructions
 * @property ?string $contact_email
 * @property ?string $contact_phone
 * @property string $type
 * @property ?string $shipping_option
 * @property array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class CartAddress extends BaseModel implements Addressable, Contracts\CartAddress
{
    use CachesProperties;
    use HasFactory;
    use HasMacros;
    use LogsActivity;

    /**
     * Array of cachable class properties.
     *
     * @var array
     */
    public $cachableProperties = [
        'shippingOption',
        'shippingSubTotal',
        'shippingTaxTotal',
        'shippingTotal',
        'taxBreakdown',
    ];

    /**
     * The applied shipping option.
     */
    public ?ShippingOption $shippingOption = null;

    /**
     * The shipping sub total.
     */
    public ?Price $shippingSubTotal = null;

    /**
     * The shipping tax total.
     */
    public ?Price $shippingTaxTotal = null;

    /**
     * The shipping total.
     */
    public ?Price $shippingTotal = null;

    /**
     * The tax breakdown.
     */
    public ?TaxBreakdown $taxBreakdown = null;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CartAddressFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'country_id',
        'title',
        'first_name',
        'last_name',
        'company_name',
        'tax_identifier',
        'line_one',
        'line_two',
        'line_three',
        'city',
        'state',
        'postcode',
        'delivery_instructions',
        'contact_email',
        'contact_phone',
        'meta',
        'type',
        'shipping_option',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta' => AsArrayObject::class,
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::modelClass());
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::modelClass());
    }
}
