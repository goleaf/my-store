<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum PostStatus: string
{
    use TranslatesEnumLabels;

    case Draft = 'draft';
    case Published = 'published';
    case Scheduled = 'scheduled';

    public function translationKey(): string
    {
        return "admin::global.enums.post_status.{$this->value}";
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
            self::Scheduled => 'warning',
        };
    }
}
