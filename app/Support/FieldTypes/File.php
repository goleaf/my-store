<?php

namespace App\Support\FieldTypes;

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;
use App\Models\Attribute;
use App\Support\Synthesizers\FileSynth;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class File extends BaseFieldType
{
    protected static string $synthesizer = FileSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $file_types = $attribute->configuration->get('file_types');
        $multiple = (bool) $attribute->configuration->get('multiple');
        $min_files = $attribute->configuration->get('min_files');
        $max_files = $attribute->configuration->get('max_files');
        $request = (new ConfiguredFieldRequest)
            ->forField($attribute->handle)
            ->required((bool) $attribute->required)
            ->withRules($attribute->validation_rules);

        $input = FileUpload::make($attribute->handle)
            ->rules($request->fieldRules($attribute->handle))
            ->required($request->fieldHasRule($attribute->handle, 'required'))
            ->helperText($attribute->translate('description'));

        if (! blank($file_types) && is_array($file_types)) {
            $input->acceptedFileTypes($file_types);
        }

        if ($multiple) {
            $input->multiple();
        }

        if ($min_files) {
            $input->minFiles($min_files);
        }

        if ($max_files) {
            $input->maxFiles($max_files);
        }

        return $input;
    }

    public static function getConfigurationFields(): array
    {
        return [
            TagsInput::make('file_types')
                ->label(
                    __('admin::fieldtypes.file.form.file_types.label')
                )->suggestions([
                    'image/*',
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'audio/*',
                    'audio/mpeg',
                    'audio/aac',
                    'audio/wav',
                    'video/*',
                    'video/mp4',
                    'video/mpeg',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/rtf',
                    'application/pdf',
                ])
                ->placeholder(__('admin::fieldtypes.file.form.file_types.placeholder'))
                ->reorderable(),
            Toggle::make('multiple')->label(
                __('admin::fieldtypes.file.form.multiple.label')
            ),
            TextInput::make('min_files')
                ->label(
                    __('admin::fieldtypes.file.form.min_files.label')
                )->nullable()->numeric(),
            TextInput::make('max_files')->label(
                __('admin::fieldtypes.file.form.max_files.label')
            )->nullable()->numeric(),
        ];
    }
}
