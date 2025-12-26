@extends('layouts.app', ['title' => 'Automation'])

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Otomasi</h1>

    <div class="content-body">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="mb-4">
                <a href="{{ route('automation.create') }}" class="btn btn-primary">Tambah Automation</a>
            </div>

            @if($automations->isEmpty())
                <p class="text-gray-500">Belum ada automations.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Waktu</th>
                                <th>Pin</th>
                                <th>Ulangi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($automations as $automation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $automation->name }}</td>
                                    <td>{{ $automation->time }}</td>
                                    <td>{{ $automation->pin }}</td>
                                    <td>{{ $automation->is_repeat ? 'Ya' : 'Tidak' }}</td>
                                    <td>
                                        <a href="{{ route('automation.edit', $automation->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('automation.destroy', $automation->id) }}" method="POST"
                                            class="d-inline-block" onsubmit="return confirm('Yakin ingin hapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection