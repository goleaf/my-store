<?php

use App\Base\Enums\PostStatus;
use App\Base\Enums\SavedPaymentMethodType;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\PostResource;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use App\Models\SavedPaymentMethod;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('staff can open customer view page with payment methods relation manager', function () {
    Currency::factory()->create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'exchange_rate' => 1,
        'decimal_places' => 2,
        'default' => true,
        'enabled' => true,
    ]);

    $staff = Staff::factory()->create(['admin' => true]);

    Permission::findOrCreate('sales:manage-customers', 'staff');

    $staff->givePermissionTo('sales:manage-customers');

    $customer = Customer::factory()->create();

    SavedPaymentMethod::query()->create([
        'customer_id' => $customer->id,
        'type' => SavedPaymentMethodType::Card->value,
        'brand' => 'Visa',
        'last_four' => '4242',
        'expiry_month' => 12,
        'expiry_year' => now()->year + 1,
        'is_default' => true,
    ]);

    $this->actingAs($staff, 'staff')
        ->get(CustomerResource::getUrl('edit', ['record' => $customer]))
        ->assertSuccessful()
        ->assertSee('Payment Methods');
});

test('staff can open post edit page with comments relation manager', function () {
    $staff = Staff::factory()->create(['admin' => true]);

    $category = PostCategory::query()->create([
        'name' => 'News',
        'slug' => 'news',
        'is_active' => true,
    ]);

    $post = Post::query()->create([
        'author_id' => $staff->id,
        'category_id' => $category->id,
        'title' => 'Fresh Groceries Launch',
        'slug' => 'fresh-groceries-launch',
        'excerpt' => 'Launch update',
        'content' => 'Launch content',
        'status' => PostStatus::Draft->value,
    ]);

    PostComment::query()->create([
        'post_id' => $post->id,
        'guest_name' => 'Guest Reader',
        'guest_email' => 'guest@example.com',
        'body' => 'Please keep this post updated.',
        'is_approved' => false,
        'is_flagged' => false,
    ]);

    $this->actingAs($staff, 'staff')
        ->get(PostResource::getUrl('edit', ['record' => $post]))
        ->assertSuccessful()
        ->assertSee('Comments');
});
