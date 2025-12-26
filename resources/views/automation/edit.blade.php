@extends('layouts.app', ['title' => 'Edit Automation'])

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Edit Automation</h1>

    <div class="content-body">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('automation.update', $automation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Automation</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $automation->name) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Waktu</label>
                    <input type="time" name="time" class="form-control" value="{{ old('time', $automation->time) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pin</label>
                    <input type="text" name="pin" class="form-control" value="{{ old('pin', $automation->pin) }}" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_repeat" class="form-check-input" id="is_repeat" {{ old('is_repeat', $automation->is_repeat) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_repeat">Ulangi</label>
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('automation.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection