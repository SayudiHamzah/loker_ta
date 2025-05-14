@extends('layout.app')



@section('content')
    <a href="{{ url('/loker/create') }}" class="btn btn-sm btn-primary ms-auto">Create Data</a>
    {{--  <a href="{{ route('user.create') }}">Create User</a>  --}}
    <br>
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Table Loker</h3>
            <div class="card-tools">
                <ul class="pagination pagination-sm float-right">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Nama Loker</th>
                        {{--  <th>Email</th>  --}}
                        <th>Status</th>
                        <th style="width: 120px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <a href="{{ route('loker.show', $user->id) }}">{{ $user->name_locker }} </a>
                            </td>
                            {{--  <td>{{ $user->email }}</td>  --}}
                            <td>
                                @if ($user->status == '1')
                                    <span class="badge bg-danger">Terbuka</span>
                                @else
                                    <span class="badge bg-info">Tertutup</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('loker.edit', $user->id) }} " class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('loker.destroy', $user->id) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE') <!-- Spoofing method DELETE -->
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
    </div>
@endsection
