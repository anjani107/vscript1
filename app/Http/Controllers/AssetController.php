<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\VideoProject;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function store(Request $request, VideoProject $project)
    {
        $request->validate([
            'file' => 'required|file|max:204800', // 200MB
            'type' => 'required|in:photo,audio,video',
        ]);

        $file = $request->file('file');
        $type = $request->input('type');

        $directory = "assets/{$project->id}/{$type}s";
        $path = $file->store($directory, 'public');

        $project->assets()->create([
            'type'          => $type,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'size'          => $file->getSize(),
        ]);

        return back()->with('success', ucfirst($type) . ' uploaded successfully.');
    }

    public function download(Asset $asset)
    {
        $fullPath = \Storage::disk('public')->path($asset->path);

        if (! \Storage::disk('public')->exists($asset->path)) {
            abort(404, 'File not found.');
        }

        return response()->download($fullPath, $asset->original_name);
    }

    public function destroy(Asset $asset)
    {
        if (\Storage::disk('public')->exists($asset->path)) {
            \Storage::disk('public')->delete($asset->path);
        }
        $asset->delete();

        return back()->with('success', 'Asset deleted.');
    }
}
