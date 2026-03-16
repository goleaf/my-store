<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PostComment>
 */
class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customerId = Customer::query()->inRandomOrder()->value('id');
        $isGuest = $customerId === null ? true : $this->faker->boolean(25);

        return [
            'post_id' => Post::query()->orderBy('id')->value('id') ?? Post::factory(),
            'parent_id' => null,
            'customer_id' => $isGuest ? null : $customerId,
            'guest_name' => $isGuest ? $this->faker->name() : null,
            'guest_email' => $isGuest ? $this->faker->safeEmail() : null,
            'body' => $this->faker->paragraph(),
            'is_approved' => true,
            'is_flagged' => false,
        ];
    }
}
