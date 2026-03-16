<?php

namespace App\Support\ActivityLog;

use App\Base\BaseModel;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\ActivityLog\Orders\Address;
use App\Support\ActivityLog\Orders\Capture;
use App\Support\ActivityLog\Orders\EmailNotification;
use App\Support\ActivityLog\Orders\Intent;
use App\Support\ActivityLog\Orders\Refund;
use App\Support\ActivityLog\Orders\StatusUpdate;
use Illuminate\Support\Collection;

class Manifest
{
    public array $events = [];

    public function __construct()
    {
        $this->events = [
            Order::morphName() => [
                Comment::class,
                StatusUpdate::class,
                Capture::class,
                Intent::class,
                Refund::class,
                EmailNotification::class,
                Address::class,
                TagsUpdate::class,
            ],
            Product::morphName() => [
                Comment::class,
            ],
            ProductVariant::morphName() => [
                Comment::class,
            ],
        ];
    }

    /**
     * Add an activity log render.
     */
    public function addRender(string $subject, string $renderer): self
    {
        if (class_exists($subject) && new $subject instanceof BaseModel) {
            $subject = $subject::morphName();
        }

        if (empty($this->events[$subject])) {
            $this->events[$subject] = [];
        }

        $this->events[$subject][] = $renderer;

        return $this;
    }

    /**
     * Return the items from a given subject.
     */
    public function getItems(string $subject): Collection
    {
        if (class_exists($subject) && new $subject instanceof BaseModel) {
            $subject = $subject::morphName();
        }

        return collect($this->events[$subject] ?? [])
            ->merge([
                Update::class,
                Create::class,
            ])->map(function ($subject) {
                $class = new $subject;

                return [
                    'event' => $class->getEvent(),
                    'class' => $class,
                ];
            });
    }
}
