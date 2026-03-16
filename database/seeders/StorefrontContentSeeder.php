<?php

namespace Database\Seeders;

use App\Base\Enums\DeliverySlotDayType;
use App\Base\Enums\HomeBannerType;
use App\Base\Enums\HomeSectionType;
use App\Base\Enums\PostStatus;
use App\Base\Enums\SavedPaymentMethodType;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\DeliverySlot;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\PromoBlock;
use App\Models\ReviewHelpfulVote;
use App\Models\SavedPaymentMethod;
use App\Models\SiteSetting;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Store\Models\Announcement;
use App\Models\Store\Models\DeliveryZone;
use App\Models\FeaturedCategory;
use App\Models\HomeBanner;
use App\Models\HomeSection;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StorefrontContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedAnnouncements();
        $this->seedDeliveryExperience();
        $this->seedHomePageContent();
        $this->seedPromoBlocks();
        $this->seedSiteSettings();
        $this->seedStores();
        $this->seedBlogContent();
        $this->seedCustomerExperience();
    }

    private function seedAnnouncements(): void
    {
        if (Announcement::query()->exists()) {
            return;
        }

        collect([
            [
                'message' => 'Fresh groceries delivered in as little as 30 minutes.',
                'bg_color' => '#16a34a',
                'text_color' => '#ffffff',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(14),
            ],
            [
                'message' => 'Free delivery on baskets over $75 this week only.',
                'bg_color' => '#0f766e',
                'text_color' => '#ffffff',
                'starts_at' => now()->subHours(12),
                'ends_at' => now()->addDays(7),
            ],
        ])->each(fn (array $announcement): Announcement => Announcement::query()->create([
            ...$announcement,
            'is_active' => true,
        ]));
    }

    private function seedDeliveryExperience(): void
    {
        if (! DeliveryZone::query()->exists()) {
            collect([
                ['name' => 'Downtown', 'min_order' => 20, 'delivery_fee' => 4.99],
                ['name' => 'Suburbs', 'min_order' => 35, 'delivery_fee' => 7.99],
                ['name' => 'Regional', 'min_order' => 50, 'delivery_fee' => 11.99],
            ])->each(fn (array $zone): DeliveryZone => DeliveryZone::query()->create([
                ...$zone,
                'is_active' => true,
            ]));
        }

        if (DeliverySlot::query()->exists()) {
            return;
        }

        $zones = DeliveryZone::query()
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get();

        foreach ($zones as $zoneIndex => $zone) {
            $baseDay = $zoneIndex % 7;

            collect([
                [
                    'day_type' => DeliverySlotDayType::Recurring->value,
                    'day_of_week' => $baseDay,
                    'specific_date' => null,
                    'label' => 'Morning delivery',
                    'start_time' => '09:00:00',
                    'end_time' => '11:00:00',
                    'cutoff_hours' => 2,
                    'fee' => 0,
                    'capacity' => 40,
                ],
                [
                    'day_type' => DeliverySlotDayType::Recurring->value,
                    'day_of_week' => ($baseDay + 2) % 7,
                    'specific_date' => null,
                    'label' => 'Evening delivery',
                    'start_time' => '17:00:00',
                    'end_time' => '20:00:00',
                    'cutoff_hours' => 3,
                    'fee' => 2.50,
                    'capacity' => 35,
                ],
                [
                    'day_type' => DeliverySlotDayType::Specific->value,
                    'day_of_week' => null,
                    'specific_date' => now()->addDays(1 + $zoneIndex)->toDateString(),
                    'label' => 'Tomorrow express',
                    'start_time' => '12:00:00',
                    'end_time' => '14:00:00',
                    'cutoff_hours' => 1,
                    'fee' => 4.50,
                    'capacity' => 20,
                ],
            ])->each(fn (array $slot): DeliverySlot => DeliverySlot::query()->create([
                ...$slot,
                'zone_id' => $zone->id,
                'booked_count' => 0,
                'is_active' => true,
            ]));
        }
    }

    private function seedHomePageContent(): void
    {
        $collections = Collection::query()
            ->with(['defaultUrl', 'products'])
            ->whereHas('defaultUrl')
            ->whereHas('products')
            ->orderBy('id')
            ->limit(6)
            ->get();

        if ($collections->isEmpty()) {
            return;
        }

        if (! FeaturedCategory::query()->exists()) {
            $collections->take(6)->values()->each(function (Collection $collection, int $index): void {
                FeaturedCategory::query()->create([
                    'collection_id' => $collection->id,
                    'title' => $collection->translateAttribute('name'),
                    'image' => null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            });
        }

        if (! HomeBanner::query()->exists()) {
            collect([
                [
                    'title' => 'Weekly produce picks',
                    'subtitle' => 'Seasonal fruit and veg',
                    'link' => '/shop',
                    'type' => HomeBannerType::Top->value,
                    'sort_order' => 1,
                ],
                [
                    'title' => 'Dinner in under 20 minutes',
                    'subtitle' => 'Pantry shortcuts and fresh ingredients',
                    'link' => '/shop',
                    'type' => HomeBannerType::Top->value,
                    'sort_order' => 2,
                ],
                [
                    'title' => 'Healthy lunch essentials',
                    'subtitle' => 'Ready-to-mix salads and bowls',
                    'link' => '/shop',
                    'type' => HomeBannerType::Middle->value,
                    'sort_order' => 3,
                ],
                [
                    'title' => 'Stock up and save',
                    'subtitle' => 'Everyday value on family staples',
                    'link' => '/shop',
                    'type' => HomeBannerType::Middle->value,
                    'sort_order' => 4,
                ],
            ])->each(fn (array $banner): HomeBanner => HomeBanner::query()->create([
                ...$banner,
                'image' => null,
                'is_active' => true,
            ]));
        }

        if (! HomeSection::query()->exists()) {
            $sectionBlueprints = [
                ['title' => 'Trending this week', 'subtitle' => 'Popular baskets customers keep reordering', 'type' => HomeSectionType::ProductGrid->value],
                ['title' => 'Best for busy evenings', 'subtitle' => 'Quick picks for easy meal planning', 'type' => HomeSectionType::SidebarGrid->value],
                ['title' => 'Top pantry restocks', 'subtitle' => 'Shelf staples, snacks, and breakfast essentials', 'type' => HomeSectionType::ProductGrid->value],
            ];

            foreach ($collections->take(3)->values() as $index => $collection) {
                HomeSection::query()->create([
                    'title' => $sectionBlueprints[$index]['title'],
                    'subtitle' => $sectionBlueprints[$index]['subtitle'],
                    'type' => $sectionBlueprints[$index]['type'],
                    'collection_id' => $collection->id,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seedPromoBlocks(): void
    {
        if (PromoBlock::query()->exists()) {
            return;
        }

        collect([
            [
                'title' => 'Weekend basket deals',
                'subtitle' => 'Buy more, save more on shared favorites',
                'badge_text' => 'Up to 25% off',
                'bg_color' => '#d1fae5',
                'position' => 'middle',
                'cta_text' => 'Browse offers',
                'cta_url' => '/shop',
                'sort_order' => 1,
            ],
            [
                'title' => 'Daily best sellers',
                'subtitle' => 'Freshly restocked essentials picked by our team',
                'badge_text' => 'Staff picks',
                'bg_color' => '#fee2e2',
                'position' => 'daily_best_promo',
                'cta_text' => 'Shop best sellers',
                'cta_url' => '/shop',
                'sort_order' => 2,
            ],
        ])->each(fn (array $promoBlock): PromoBlock => PromoBlock::query()->create([
            ...$promoBlock,
            'image' => null,
            'is_active' => true,
        ]));
    }

    private function seedSiteSettings(): void
    {
        collect([
            ['key' => 'site_name', 'value' => config('app.name'), 'group' => 'general', 'type' => 'text', 'label' => 'Site Name'],
            ['key' => 'homepage_variant', 'value' => 'default', 'group' => 'homepage', 'type' => 'text', 'label' => 'Homepage Variant'],
            ['key' => 'hero_autoplay_ms', 'value' => '5000', 'group' => 'homepage', 'type' => 'text', 'label' => 'Hero Autoplay'],
            ['key' => 'default_delivery_zone', 'value' => 'Downtown', 'group' => 'checkout', 'type' => 'text', 'label' => 'Default Delivery Zone'],
            ['key' => 'support_email', 'value' => 'support@example.com', 'group' => 'general', 'type' => 'text', 'label' => 'Support Email'],
            ['key' => 'social_links', 'value' => json_encode(['instagram' => 'https://example.com/instagram', 'facebook' => 'https://example.com/facebook'], JSON_THROW_ON_ERROR), 'group' => 'social', 'type' => 'json', 'label' => 'Social Links'],
        ])->each(function (array $setting): void {
            SiteSetting::query()->updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'group' => $setting['group'],
                    'type' => $setting['type'],
                    'label' => $setting['label'],
                    'description' => $setting['label'],
                ],
            );
        });
    }

    private function seedStores(): void
    {
        if (Store::query()->exists()) {
            return;
        }

        $owners = Customer::query()
            ->select(['id', 'first_name', 'last_name', 'email', 'phone'])
            ->orderBy('id')
            ->limit(3)
            ->get();

        $storeBlueprints = [
            ['name' => 'Northside Fresh Market', 'city' => 'Chicago', 'state' => 'Illinois', 'commission_rate' => 8.50],
            ['name' => 'Harbor Pantry Co', 'city' => 'Seattle', 'state' => 'Washington', 'commission_rate' => 10.00],
            ['name' => 'Sunrise Family Grocer', 'city' => 'Austin', 'state' => 'Texas', 'commission_rate' => 9.25],
        ];

        foreach ($owners->values() as $index => $owner) {
            $blueprint = $storeBlueprints[$index] ?? [
                'name' => "{$owner->first_name} {$owner->last_name} Market",
                'city' => 'New York',
                'state' => 'New York',
                'commission_rate' => 9.50,
            ];

            Store::query()->create([
                'owner_id' => $owner->id,
                'name' => $blueprint['name'],
                'slug' => Str::slug($blueprint['name']),
                'logo' => null,
                'banner' => null,
                'description' => 'A customer-first neighborhood grocery storefront with fresh produce, pantry staples, and weekly specials.',
                'email' => $owner->email,
                'phone' => $owner->phone,
                'address_line_1' => '100 Market Street',
                'city' => $blueprint['city'],
                'state' => $blueprint['state'],
                'country' => 'United States',
                'opening_hours' => [
                    'mon' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                    'tue' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                    'wed' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                    'thu' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                    'fri' => ['open' => '08:00', 'close' => '21:00', 'closed' => false],
                    'sat' => ['open' => '09:00', 'close' => '21:00', 'closed' => false],
                    'sun' => ['open' => '10:00', 'close' => '18:00', 'closed' => false],
                ],
                'commission_rate' => $blueprint['commission_rate'],
                'rating_avg' => 4.70,
                'total_reviews' => 32 + ($index * 9),
                'is_verified' => true,
                'is_active' => true,
                'meta_title' => $blueprint['name'],
                'meta_description' => 'Fresh groceries and household essentials from a trusted local marketplace store.',
            ]);
        }
    }

    private function seedBlogContent(): void
    {
        if (! PostCategory::query()->exists()) {
            collect([
                ['name' => 'Fresh Picks', 'description' => 'Seasonal ingredients, produce highlights, and what to add to your weekly cart.', 'sort_order' => 1],
                ['name' => 'Meal Planning', 'description' => 'Time-saving recipes, prep ideas, and family shopping lists.', 'sort_order' => 2],
                ['name' => 'Savings & Offers', 'description' => 'Bundle deals, promotions, and ways to stretch your grocery budget.', 'sort_order' => 3],
            ])->each(function (array $category): void {
                PostCategory::query()->create([
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'image' => null,
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]);
            });
        }

        if (! Post::query()->exists()) {
            $authorId = Staff::query()->orderBy('id')->value('id');
            $categories = PostCategory::query()->select(['id', 'name'])->orderBy('sort_order')->get();

            if ($authorId && $categories->isNotEmpty()) {
                collect([
                    ['title' => 'How to build a balanced weekly grocery basket', 'status' => PostStatus::Published->value, 'offset' => 10],
                    ['title' => 'Five breakfast staples to keep on repeat', 'status' => PostStatus::Published->value, 'offset' => 7],
                    ['title' => 'What to freeze, refrigerate, and store in the pantry', 'status' => PostStatus::Published->value, 'offset' => 4],
                    ['title' => 'This weekend’s family meal deals', 'status' => PostStatus::Scheduled->value, 'offset' => -2],
                    ['title' => 'Behind the scenes of our produce quality checks', 'status' => PostStatus::Draft->value, 'offset' => null],
                ])->values()->each(function (array $post, int $index) use ($authorId, $categories): void {
                    $title = $post['title'];
                    $status = $post['status'];
                    $publishedAt = match ($status) {
                        PostStatus::Published->value => now()->subDays((int) $post['offset']),
                        PostStatus::Scheduled->value => now()->addDays(abs((int) $post['offset'])),
                        default => null,
                    };

                    Post::query()->create([
                        'author_id' => $authorId,
                        'category_id' => $categories[$index % $categories->count()]->id,
                        'title' => $title,
                        'slug' => Str::slug($title),
                        'excerpt' => "{$title} with actionable tips for faster grocery planning.",
                        'content' => collect([
                            'Plan around the meals you will actually make this week.',
                            'Start with versatile produce, proteins, and pantry staples.',
                            'Add a few easy extras so quick lunches and snacks are covered.',
                        ])->map(fn (string $paragraph): string => "<p>{$paragraph}</p>")->implode(''),
                        'featured_image' => null,
                        'tags' => ['grocery', 'freshcart', 'meal-planning'],
                        'status' => $status,
                        'published_at' => $publishedAt,
                        'views_count' => 40 + ($index * 13),
                        'read_time_minutes' => 4 + $index,
                        'meta_title' => $title,
                        'meta_description' => "Read {$title} on our grocery journal.",
                    ]);
                });
            }
        }

        if (PostComment::query()->exists()) {
            return;
        }

        $posts = Post::query()->select(['id'])->orderBy('id')->limit(3)->get();
        $customers = Customer::query()->select(['id', 'first_name'])->orderBy('id')->limit(3)->get();

        foreach ($posts->values() as $index => $post) {
            $customer = $customers[$index % max($customers->count(), 1)] ?? null;

            PostComment::query()->create([
                'post_id' => $post->id,
                'parent_id' => null,
                'customer_id' => $customer?->id,
                'guest_name' => $customer ? null : 'Guest Shopper',
                'guest_email' => $customer ? null : 'guest@example.com',
                'body' => 'This is exactly the kind of quick grocery guidance I needed.',
                'is_approved' => true,
                'is_flagged' => false,
            ]);
        }
    }

    private function seedCustomerExperience(): void
    {
        $customers = Customer::query()
            ->select(['id', 'email'])
            ->orderBy('id')
            ->limit(12)
            ->get();

        $products = Product::query()
            ->select(['id', 'attribute_data'])
            ->with(['variants:id,product_id'])
            ->orderBy('id')
            ->limit(12)
            ->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        if (! SavedPaymentMethod::query()->exists()) {
            foreach ($customers->take(6)->values() as $index => $customer) {
                SavedPaymentMethod::query()->create([
                    'customer_id' => $customer->id,
                    'type' => $index < 4 ? SavedPaymentMethodType::Card->value : SavedPaymentMethodType::Paypal->value,
                    'stripe_customer_id' => $index < 4 ? "cus_demo_{$customer->id}" : null,
                    'stripe_payment_method_id' => $index < 4 ? "pm_demo_{$customer->id}" : null,
                    'last_four' => $index < 4 ? str_pad((string) (4242 + $index), 4, '0', STR_PAD_LEFT) : null,
                    'brand' => $index < 4 ? 'Visa' : null,
                    'expiry_month' => $index < 4 ? 12 : null,
                    'expiry_year' => $index < 4 ? now()->addYears(2)->year : null,
                    'paypal_email' => $index >= 4 ? $customer->email : null,
                    'payoneer_account_id' => null,
                    'is_default' => $index === 0,
                ]);
            }
        }

        if (! Wishlist::query()->exists()) {
            foreach ($customers->take(8)->values() as $index => $customer) {
                $product = $products[$index % $products->count()];
                $variantId = $product->variants->first()?->id;

                Wishlist::query()->create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'variant_id' => $variantId,
                ]);
            }
        }

        if (ProductReview::query()->exists()) {
            return;
        }

        $createdReviews = collect();

        foreach ($customers->take(6)->values() as $index => $customer) {
            $product = $products[$index % $products->count()];

            $createdReviews->push(ProductReview::query()->create([
                'product_id' => $product->id,
                'customer_id' => $customer->id,
                'rating' => 5 - ($index % 2),
                'rating_flavor' => 5,
                'rating_value' => 4,
                'rating_scent' => 4,
                'title' => 'Reliable quality and fast delivery',
                'body' => 'The items arrived fresh, well-packed, and matched what I expected from the listing.',
                'images' => [],
                'helpful_count' => 0,
                'is_verified_purchase' => true,
                'is_approved' => true,
                'is_flagged' => false,
                'admin_reply' => null,
                'admin_replied_at' => null,
            ]));
        }

        $reviewVoters = $customers->slice(6)->values();

        foreach ($createdReviews->values() as $index => $review) {
            $voter = $reviewVoters[$index % max($reviewVoters->count(), 1)] ?? null;

            if (! $voter || $voter->id === $review->customer_id) {
                continue;
            }

            ReviewHelpfulVote::query()->create([
                'review_id' => $review->id,
                'customer_id' => $voter->id,
            ]);

            $review->update(['helpful_count' => $review->helpful_count + 1]);
        }
    }
}
