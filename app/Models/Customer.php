<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use App\Base\Casts\AsAttributeData;
use App\Base\Traits\HasAttributes;
use App\Base\Traits\HasMacros;
use App\Base\Traits\HasModelExtending;
use App\Base\Traits\HasPersonalDetails;
use App\Base\Traits\HasTranslations;
use App\Base\Traits\LogsActivity;
use App\Base\Traits\Searchable;
use App\Database\Factories\CustomerFactory;

/**
 * @property int $id
 * @property ?string $title
 * @property string $first_name
 * @property string $last_name
 * @property ?string $company_name
 * @property ?string $tax_identifier
 * @property ?string $account_ref
 * @property ?array $attribute_data
 * @property ?array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Customer extends User implements Contracts\Customer
{
    use HasAttributes;
    use HasFactory;
    use HasPersonalDetails;
    use HasTranslations;
    use LogsActivity;
    use Notifiable;
    use Searchable;
    use HasMacros, HasModelExtending {
        HasModelExtending::__callStatic insteadof HasMacros;
    }

    protected $table = 'customers';

    /**
     * Define the guarded attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'first_name',
        'last_name',
        'company_name',
        'tax_identifier',
        'account_ref',
        'email',
        'phone',
        'password',
        'status',
        'locale',
        'avatar',
        'remember_token',
        'email_verified_at',
        'last_login_at',
        'attribute_data',
        'meta',
    ];

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'attribute_data' => AsAttributeData::class,
            'meta' => AsArrayObject::class,
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CustomerFactory::new();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('store.database.table_prefix').$this->getTable());

        if ($connection = config('store.database.connection')) {
            $this->setConnection($connection);
        }
    }

    public function getForeignKey(): string
    {
        return 'customer_id';
    }

    public function customerGroups(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::modelClass(),
            "{$prefix}customer_customer_group"
        )->withTimestamps();
    }

    public function discounts(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            Discount::modelClass(),
            "{$prefix}customer_discount"
        )->withTimestamps();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::modelClass());
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::modelClass());
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::modelClass());
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(SavedPaymentMethod::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function reviewHelpfulVotes(): HasMany
    {
        return $this->hasMany(ReviewHelpfulVote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'owner_id');
    }

    public function mappedAttributes(): MorphToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->morphToMany(
            Attribute::modelClass(),
            'attributable',
            "{$prefix}attributables"
        )->withTimestamps();
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (): string => trim(
                preg_replace('/\s+/', ' ', "{$this->first_name} {$this->last_name}")
            ),
            set: function (?string $value): array {
                $value = trim((string) $value);

                if ($value === '') {
                    return [];
                }

                $parts = preg_split('/\s+/', $value, 2) ?: [];

                return [
                    'first_name' => $parts[0] ?? '',
                    'last_name' => $parts[1] ?? '',
                ];
            },
        );
    }
}
