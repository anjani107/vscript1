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

    public function destroy(Asset $asset)
    {
        if (\Storage::disk('public')->exists($asset->path)) {
            \Storage::disk('public')->delete($asset->path);
        }
        $asset->delete();

        return back()->with('success', 'Asset deleted.');
    }
}
