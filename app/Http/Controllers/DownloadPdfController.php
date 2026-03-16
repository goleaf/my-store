<?php

namespace App\Http\Controllers;

use App\Http\Requests\System\DownloadPdfRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\Controller;

class DownloadPdfController extends Controller
{
    public function __invoke(DownloadPdfRequest $request)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $validated = $request->validated();

        $recordType = Relation::getMorphedModel($validated['record_type']);
        $view = $validated['view'];
        $record = $validated['record'];

        $model = $recordType::find($record);

        return Pdf::loadView($view, [
            'record' => $model,
        ])->stream();
    }
}
