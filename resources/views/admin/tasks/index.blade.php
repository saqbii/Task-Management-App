<!-- resources/views/tasks/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tasks</h2>

        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary mb-3">Create Task</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <!-- Table header goes here -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Assigned To</th>
                    <th>File</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table body goes here -->
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->details }}</td>
                        <td>{{ $task->user->name }}</td>
                        <td> 
                            <a href="{{ route('admin.tasks.download', $task->id) }}" class="btn btn-dark">Download</a>
                        </td>
                        <td>{{ $task->due_date->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $tasks->links() }}
    </div>
@endsection
