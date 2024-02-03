@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row mt-5">
                <h2>Edit Task</h2>
                @csrf
                @method('PUT')
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Task Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $task->name }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="file" class="form-label">File</label>
                    <input type="file" name="file" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                </div>


                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">Assign Task To</label>
                    <select name="user_id" class="form-control">
                        <option value="">Select</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @if($user->id == $task->user_id) selected @endif>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="details" class="form-label">Details</label>
                    <textarea name="details" class="form-control">{{ $task->details }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-25">Update</button>
            </div>
        </form>
    </div>
@endsection
