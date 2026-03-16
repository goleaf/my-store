<?php

namespace Database\Factories;

use App\Base\Enums\PostStatus;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title($this->faker->unique()->words(5, true));
        $status = $this->faker->randomElement([
            PostStatus::Published,
            PostStatus::Draft,
            PostStatus::Scheduled,
        ]);

        return [
            'author_id' => Staff::query()->orderBy('id')->value('id') ?? Staff::factory(),
            'category_id' => PostCategory::query()->orderBy('id')->value('id') ?? PostCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->sentence(18),
            'content' => collect($this->faker->paragraphs(4))
                ->map(fn (string $paragraph): string => "<p>{$paragraph}</p>")
                ->implode(''),
            'featured_image' => null,
            'tags' => $this->faker->words(3),
            'status' => $status,
            'published_at' => match ($status) {
                PostStatus::Published => now()->subDays($this->faker->numberBetween(1, 30)),
                PostStatus::Scheduled => now()->addDays($this->faker->numberBetween(1, 14)),
                default => null,
            },
            'views_count' => $this->faker->numberBetween(0, 500),
            'read_time_minutes' => $this->faker->numberBetween(2, 10),
            'meta_title' => $title,
            'meta_description' => $this->faker->sentence(12),
        ];
    }
}
