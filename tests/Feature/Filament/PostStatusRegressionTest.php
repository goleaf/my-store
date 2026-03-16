<?php

use App\Base\Enums\PostStatus;
use App\Filament\Resources\PostResource;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post resource exposes scheduled status and model casts it correctly', function () {
    $staff = Staff::factory()->create(['admin' => true]);
    $category = PostCategory::query()->create([
        'name' => 'News',
        'slug' => 'news',
        'is_active' => true,
    ]);

    $this->actingAs($staff, 'staff')
        ->get(PostResource::getUrl('create'))
        ->assertSuccessful()
        ->assertSee(PostStatus::Scheduled->label())
        ->assertDontSee('Archived');

    $post = Post::query()->create([
        'author_id' => $staff->id,
        'category_id' => $category->id,
        'title' => 'Scheduled Update',
        'slug' => 'scheduled-update',
        'excerpt' => 'Scheduled excerpt',
        'content' => 'Scheduled content',
        'status' => PostStatus::Scheduled->value,
    ]);

    expect($post->refresh()->status)->toBe(PostStatus::Scheduled);
});
