<?php

use App\Base\Enums\DeliverySlotDayType;
use App\Http\Requests\Filament\Blog\PostCategoryRequest;
use App\Http\Requests\Filament\Blog\PostRequest;
use App\Http\Requests\Filament\Catalog\ProductReviewRequest;
use App\Http\Requests\Filament\Shipping\DeliverySlotRequest;
use App\Http\Requests\Filament\Store\StoreRequest;
use App\Http\Requests\Filament\Support\ContactSubmissionRequest;
use App\Http\Requests\Filament\System\SiteSettingRequest;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SiteSetting;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

test('contact submission request exposes email and boolean rules', function () {
    $request = new ContactSubmissionRequest;

    expect($request->fieldRules('email'))->toBe([
        'required',
        'email',
        'max:255',
    ])->and($request->fieldRules('is_read'))->toBe([
        'required',
        'boolean',
    ]);
});

test('delivery slot request moves conditional requirements into the request', function () {
    $specificRequest = (new DeliverySlotRequest)->forDayType(DeliverySlotDayType::Specific->value);
    $recurringRequest = (new DeliverySlotRequest)->forDayType(DeliverySlotDayType::Recurring->value);

    expect($specificRequest->fieldRules('specific_date')[0])->toBe('required')
        ->and($specificRequest->fieldRules('day_of_week')[0])->toBe('nullable')
        ->and($recurringRequest->fieldRules('specific_date')[0])->toBe('nullable')
        ->and($recurringRequest->fieldRules('day_of_week')[0])->toBe('required');
});

test('record aware filament requests ignore the current record for unique fields', function (string $requestClass, string $modelClass, string $field) {
    $record = makeExistingModelFromClass($modelClass, 42);
    $request = (new $requestClass)->forRecord($record);
    $rules = collect($request->fieldRules($field));
    $uniqueRule = $rules->first(fn (mixed $rule): bool => $rule instanceof Unique);

    expect($request->fieldHasRule($field, 'required'))->toBeTrue()
        ->and($uniqueRule)->toBeInstanceOf(Unique::class)
        ->and((string) $uniqueRule)->toContain('"42"');
})->with([
    [PostCategoryRequest::class, PostCategory::class, 'slug'],
    [PostRequest::class, Post::class, 'slug'],
    [SiteSettingRequest::class, SiteSetting::class, 'key'],
    [StoreRequest::class, Store::class, 'slug'],
]);

test('product review request keeps score fields numeric and bounded', function () {
    $request = new ProductReviewRequest;

    expect($request->fieldRules('rating'))->toBe([
        'required',
        'integer',
        'min:1',
        'max:5',
    ])->and($request->fieldRules('helpful_count'))->toBe([
        'required',
        'integer',
        'min:0',
    ]);
});

function makeExistingModelFromClass(string $modelClass, int $id): Model
{
    /** @var Model $model */
    $model = new $modelClass;
    $model->forceFill(['id' => $id]);
    $model->exists = true;
    $model->syncOriginal();

    return $model;
}
