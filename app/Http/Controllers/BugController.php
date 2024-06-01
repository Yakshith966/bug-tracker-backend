<?php
namespace App\Http\Controllers;

use App\Events\BugStatusChanged;
use Illuminate\Http\Request;
use App\Models\Bug;
use Illuminate\Support\Facades\Storage;

class BugController extends Controller
{
    public function index()
    {
        return Bug::with('developers','project')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tester_id' => 'required|exists:users,id',
            'bug_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'priority' => 'required|string',
            // 'status' => 'required|string',
            // 'assigned_date' => 'required|date_format:Y-m-d',
            'project_id' => 'required|exists:projects,id',
        ]);

        $bugImages = [];
        if ($request->hasFile('bug_images')) {
            foreach ($request->file('bug_images') as $image) {
                $path = $image->store('bug_images', 'public');
                $bugImages[] = $path;
            }
        }

        $bug = Bug::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'tester_id' => $request->input('tester_id'),
            'bug_images' => $bugImages,
            'priority' => $request->input('priority'),
            'status' => $request->input('status'),
            'assigned_date' => $request->input('assigned_date'),
            'project_id' => $request->input('project_id'),
        ]);

        return response()->json($bug->load('developers'), 201);
    }
    public function show($id)
    {
        return Bug::with( 'project', 'developers')->findOrFail($id);
    }

    public function assignDevelopers(Request $request, $id)
    {
        $this->validate($request, [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $bug = Bug::findOrFail($id);
        $bug->developers()->sync($request->user_ids);

        return response()->json($bug->load('developers'), 200);
    }
    public function updateBug(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bug_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $bug = Bug::findOrFail($id);

        // Update bug details
        $bug->name = $request->input('name');
        $bug->description = $request->input('description');

        // Handle bug images
        if ($request->hasFile('bug_images')) {
            $bugImages = [];
            foreach ($request->file('bug_images') as $image) {
                $path = $image->store('bug_images', 'public');
                $bugImages[] = $path;
            }
            $bug->bug_images = $bugImages;
        }

        $bug->save();

        return response()->json($bug->load('developers'), 200);
    }
    public function updateStatus(Request $request, $id)
{
    $bug = Bug::findOrFail($id);
    $previousStatus = $bug->status;

    // $bug->name = $request->input('name');
    // $bug->description = $request->input('description');
    $bug->status = $request->input('status');
    // other updates...

    $bug->save();

    if ($previousStatus !== $bug->status && in_array($bug->status, ['working', 'closed'])) {
        event(new BugStatusChanged($bug));
    }

    return response()->json($bug->load('developers'), 200);
}

    public function destroy($id)
    {
        $bug = Bug::findOrFail($id);
        $bug->delete();

        return response()->json(['message' => 'Bug deleted successfully'], 200);
    }
    public function getBugsForDeveloper($developerId)
    {
        $data= Bug::with('developers', 'project')
            ->whereHas('developers', function($query) use ($developerId) {
                $query->where('developer_id', $developerId);
            })
            ->get();
         return response()->json($data);   
    }
}
