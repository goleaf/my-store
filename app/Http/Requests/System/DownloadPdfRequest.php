<?php

namespace App\Http\Requests\System;

use App\Http\Requests\BaseRequest;

class DownloadPdfRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'record' => ['required'],
            'record_type' => ['required'],
            'view' => ['required'],
        ];
    }
}
