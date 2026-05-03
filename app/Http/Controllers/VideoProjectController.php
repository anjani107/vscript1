<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\VideoProject;
use App\Models\Script;
use App\Models\Note;
use Illuminate\Http\Request;

class VideoProjectController extends Controller
{
    public function index()
    {
        $projects = VideoProject::where('user_id', auth()->id())
            ->with('client')
            ->withCount(['scripts', 'assets', 'notes'])
            ->latest()
            ->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::where('user_id', auth()->id())->orderBy('name')->get();
        return view('projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'client_id'   => 'nullable|exists:clients,id',
        ]);

        $project = VideoProject::create(array_merge($data, ['user_id' => auth()->id()]));

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function show(VideoProject $project)
    {
        abort_if($project->user_id !== auth()->id(), 403);
        $project->load(['scripts', 'assets', 'notes', 'client']);
        return view('projects.show', compact('project'));
    }

    public function edit(VideoProject $project)
    {
        abort_if($project->user_id !== auth()->id(), 403);
        $clients = Client::where('user_id', auth()->id())->orderBy('name')->get();
        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, VideoProject $project)
    {
        abort_if($project->user_id !== auth()->id(), 403);
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'client_id'   => 'nullable|exists:clients,id',
        ]);

        $project->update($data);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(VideoProject $project)
    {
        abort_if($project->user_id !== auth()->id(), 403);
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

    // Notes
    public function storeNote(Request $request, VideoProject $project)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'color'   => 'required|in:yellow,blue,green,pink,purple',
        ]);

        $project->notes()->create($data);

        return back()->with('success', 'Note added.');
    }

    public function updateNote(Request $request, VideoProject $project, Note $note)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'color'   => 'required|in:yellow,blue,green,pink,purple',
        ]);

        $note->update($data);

        return back()->with('success', 'Note updated.');
    }

    public function destroyNote(VideoProject $project, Note $note)
    {
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }
}
