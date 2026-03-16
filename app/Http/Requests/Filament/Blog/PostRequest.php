<?php

namespace App\Http\Requests\Filament\Blog;

use App\Base\Enums\PostStatus;
use App\Http\Requests\BaseRequest;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Staff;
use Illuminate\Validation\Rule;

class PostRequest extends BaseRequest
{
    protected ?Post $record = null;

    public function forRecord(?Post $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function rules(): array
    {
        $slugRule = Rule::unique(Post::class, 'slug');

        if ($this->record) {
            $slugRule->ignore($this->record);
        }

        return [
            'author_id' => ['required', Rule::exists((new Staff)->getTable(), 'id')],
            'category_id' => ['nullable', Rule::exists((new PostCategory)->getTable(), 'id')],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', $slugRule],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image'],
            'tags' => ['nullable', 'array'],
            'status' => ['required', 'string', Rule::in(array_map(static fn (PostStatus $status): string => $status->value, PostStatus::cases()))],
            'published_at' => ['nullable', 'date'],
            'read_time_minutes' => ['nullable', 'integer', 'min:0', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
