<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Base\BaseModel;
use App\Base\Casts\AsAttributeData;
use App\Base\Enums\Concerns\ProvidesProductAssociationType;
use App\Base\HasThumbnailImage;
use App\Base\Traits\HasChannels;
use App\Base\Traits\HasCustomerGroups;
use App\Base\Traits\HasMacros;
use App\Base\Traits\HasMedia;
use App\Base\Traits\HasTags;
use App\Base\Traits\HasTranslations;
use App\Base\Traits\HasUrls;
use App\Base\Traits\LogsActivity;
use App\Base\Traits\Searchable;
use App\Database\Factories\ProductFactory;
use App\Jobs\Products\Associations\Associate;
use App\Jobs\Products\Associations\Dissociate;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property int $id
 * @property ?int $brand_id
 * @property int $product_type_id
 * @property string $status
 * @property ?\Illuminate\Support\Collection $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Product extends BaseModel implements Contracts\Product, HasThumbnailImage, SpatieHasMedia
{
    use HasChannels;
    use HasCustomerGroups;
    use HasFactory;
    use HasMacros;
    use HasMedia;
    use HasTags;
    use HasTranslations;
    use HasUrls;
    use LogsActivity;
    use Searchable;
    use SoftDeletes;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    /**
     * Define which attributes should be
     * fillable during mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_data',
        'product_type_id',
        'status',
        'brand_id',
        'store_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'original_price',
        'cost_price',
        'stock',
        'low_stock_threshold',
        'weight_grams',
        'badge',
        'badge_custom',
        'availability_note',
        'shipping_info',
        'product_type',
        'product_code',
        'seller_name',
        'units_per_pack',
        'disclaimer',
        'is_active',
        'is_popular',
        'is_featured',
        'is_daily_best',
        'sort_order',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    /**
     * Record's title
     */
    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->translateAttribute('name'),
        );
    }

    public function mappedAttributes(): Collection
    {
        return $this->productType->mappedAttributes;
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::modelClass());
    }

    public function images(): MorphMany
    {
        return $this->media()->where('collection_name', config('store.media.collection'));
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::modelClass());
    }

    public function variant(): HasOne
    {
        return $this->hasOne(ProductVariant::modelClass());
    }

    protected function hasVariants(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->variants()->count() > 1,
        );
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Collection::modelClass(),
            config('store.database.table_prefix').'collection_product'
        )->withPivot(['position'])->orderByPivot('position')->withTimestamps();
    }

    public function associations(): HasMany
    {
        return $this->hasMany(ProductAssociation::modelClass(), 'product_parent_id');
    }

    public function inverseAssociations(): HasMany
    {
        return $this->hasMany(ProductAssociation::modelClass(), 'product_target_id');
    }

    public function associate(mixed $product, ProvidesProductAssociationType|string $type): void
    {
        Associate::dispatch($this, $product, $type);
    }

    /**
     * Dissociate a product to another with a type.
     */
    public function dissociate(mixed $product, ProvidesProductAssociationType|string|null $type = null): void
    {
        Dissociate::dispatch($this, $product, $type);
    }

    public function customerGroups(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::modelClass(),
            "{$prefix}customer_group_product"
        )->withPivot([
            'purchasable',
            'visible',
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    }

    public static function getExtraCustomerGroupPivotValues(CustomerGroup $customerGroup): array
    {
        return [
            'purchasable' => $customerGroup->default,
        ];
    }

    /**
     * Return the brand relationship.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::modelClass());
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->whereStatus($status);
    }

    public function prices(): HasManyThrough
    {
        return $this->hasManyThrough(
            Price::modelClass(),
            ProductVariant::modelClass(),
            'product_id',
            'priceable_id'
        )->wherePriceableType('product_variant');
    }

    public function productOptions(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            ProductOption::modelClass(),
            "{$prefix}product_product_option"
        )->withPivot(['position'])->orderByPivot('position');
    }

    public function getThumbnailImage(): string
    {
        return $this->thumbnail?->getUrl('small') ?? '';
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'category_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProductDetail::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('is_approved', true);
    }
}
