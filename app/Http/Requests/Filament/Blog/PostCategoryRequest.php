<?php

namespace App\Http\Requests\Filament\Blog;

use App\Http\Requests\BaseRequest;
use App\Models\PostCategory;
use Illuminate\Validation\Rule;

class PostCategoryRequest extends BaseRequest
{
    protected ?PostCategory $record = null;

    public function forRecord(?PostCategory $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function rules(): array
    {
        $slugRule = Rule::unique(PostCategory::class, 'slug');

        if ($this->record) {
            $slugRule->ignore($this->record);
        }

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', $slugRule],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
