<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Gate;
use Storage;
use App\TList;

class ImageController extends Controller
{
    public function get(TList $list)
    {
        if (Gate::denies('view', $list)) {
            abort(403);
        }
        if (config('filesystems.default') == 'local') {
            try {
                return response()->file(storage_path('app/' . $list->image->path));
            } catch (FileNotFoundException $e) {
                abort(404);
            }
        } elseif (config('filesystems.default') == 's3') {
            return redirect(Storage::temporaryUrl($list->image->path, now()->addMinutes(5)));
        } else {
            abort(404);
        }
    }
}
