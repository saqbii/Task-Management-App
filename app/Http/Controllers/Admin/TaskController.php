<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::role('user')->get();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'details' => 'required',
            'file' => 'required',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $data = $request->except('file');

        // Handle file upload
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();
            $path = $uploadedFile->storeAs('task_files', $filename, 'public');
            $data['file'] = $path;
        }

        Task::create($data);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = User::role('user')->get();
        return view('admin.tasks.edit', compact('users', 'task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'details' => 'required',
            'file' => 'nullable',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $data = $request->except('file');

        if ($request->hasFile('file')) {
            if ($task->file) {
                Storage::disk('public')->delete($task->file);
            }

            $uploadedFile = $request->file('file');
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();
            $path = $uploadedFile->storeAs('task_files', $filename, 'public');
            $data['file'] = $path;
        }

        $task->update($data);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }


    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function download(Task $task)
    {
        $filePath = storage_path("app/public/{$task->file}");

        return response()->stream(
            function () use ($filePath) {
                $fileStream = fopen($filePath, 'rb');
                fpassthru($fileStream);
                fclose($fileStream);
            },
            200,
            [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
            ]
        );
    }
}
