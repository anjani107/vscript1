<?php

namespace App\Http\Controllers;

use App\Models\VideoProject;
use App\Models\Script;
use Illuminate\Http\Request;

class VideoProjectController extends Controller
{
    public function index()
    {
        $projects = VideoProject::withCount(['scripts', 'assets'])->latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $project = VideoProject::create($data);

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function show(VideoProject $project)
    {
        $project->load(['scripts', 'assets']);
        return view('projects.show', compact('project'));
    }

    public function edit(VideoProject $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, VideoProject $project)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $project->update($data);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(VideoProject $project)
    {
        foreach ($project->assets as $asset) {
            if (\Storage::disk('public')->exists($asset->path)) {
                \Storage::disk('public')->delete($asset->path);
            }
        }
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }

    public function updateStatus(Request $request, VideoProject $project)
    {
        $data = $request->validate([
            'status' => 'required|in:draft,in_progress,completed',
        ]);

        $project->update($data);

        return back()->with('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $data['status'])) . '.');
    }

    // Scripts
    public function storeScript(Request $request, VideoProject $project)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $project->scripts()->create($data);

        return back()->with('success', 'Script added.');
    }

    public function updateScript(Request $request, VideoProject $project, Script $script)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $script->update($data);

        return back()->with('success', 'Script saved.');
    }

    public function destroyScript(VideoProject $project, Script $script)
    {
        $script->delete();
        return back()->with('success', 'Script deleted.');
    }
}
