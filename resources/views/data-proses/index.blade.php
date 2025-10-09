@extends('layout.app')

@section('content')
<section class="content">
    <div class="container-fluid">
        <h2 class="text-center display-4">Data Enkripsi & Dekripsi (KSA & PGRA)</h2>

        {{-- ========================== --}}
        {{-- FORM PENCARIAN --}}
        {{-- ========================== --}}
        <form action="{{ route('data-proses') }}" method="GET">
            <div class="row mb-4">
                <div class="col-md-4 offset-md-2">
                    <div class="form-group">
                        <input type="search" name="search_user" class="form-control form-control-lg"
                            placeholder="Nama user..." value="{{ request('search_user') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <input type="search" name="search_locker" class="form-control form-control-lg"
                            placeholder="Nama loker..." value="{{ request('search_locker') }}">
                    </div>
                </div>

                {{--  <div class="col-md-2 d-flex gap-2">

                </div>  --}}
                <div class="container">
                    <button type="submit" class="btn btn-lg btn-primary w-100">
                        Cari
                    </button>
                </div>


            </div>
        </form>

        {{-- ========================== --}}
        {{-- TABEL ENKRIPSI --}}
        {{-- ========================== --}}
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">Hasil Enkripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Nama Locker</th>
                                {{--  <th>Key</th>
                                <th>UUID</th>
                                <th>Hasil KSA</th>
                                <th>Hasil PGRA</th>
                                <th>Hasil Desimal</th>  --}}
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($encryptions as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ $item->name_locker ?? '-' }}</td>
                                {{--  <td>{{ $item->key ?? '-' }}</td>
                                <td>{{ $item->uuid ?? '-' }}</td>
                                <td class="text-truncate" style="max-width: 150px;">
                                    <code title="{{ $item->hasil_ksa }}">{{ Str::limit($item->hasil_ksa, 50) }}</code>
                                </td>
                                <td class="text-truncate" style="max-width: 150px;">
                                    <code title="{{ $item->hasil_pgra }}">{{ Str::limit($item->hasil_pgra, 50) }}</code>
                                </td>
                                <td class="text-truncate" style="max-width: 150px;">
                                    <code title="{{ $item->hasil_desimal }}">{{ Str::limit($item->hasil_desimal, 50) }}</code>
                                </td>  --}}
                                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('data-proses.show', ['encryption', $item->id]) }}"
                                       class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data enkripsi ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- TABEL DEKRIPSI --}}
        {{-- ========================== --}}
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="card-title mb-0">Hasil Dekripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Nama Locker</th>
                                {{--  <th>Key</th>
                                <th>UUID</th>
                                <th>Hasil KSA</th>
                                <th>Hasil PGRA</th>
                                <th>Hasil Desimal</th>  --}}
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($decryptions as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ $item->name_locker ?? '-' }}</td>
                                    {{--  <td>{{ $item->key ?? '-' }}</td>
                                    <td>{{ $item->uuid ?? '-' }}</td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        <code title="{{ $item->hasil_ksa }}">{{ Str::limit($item->hasil_ksa, 50) }}</code>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        <code title="{{ $item->hasil_pgra }}">{{ Str::limit($item->hasil_pgra, 50) }}</code>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        <code title="{{ $item->hasil_desimal }}">{{ Str::limit($item->hasil_desimal, 50) }}</code>
                                    </td>  --}}
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('data-proses.show', ['decryption', $item->id]) }}"
                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data dekripsi ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        .text-truncate {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .table code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.875em;
            cursor: help;
        }

        .nav-tabs .nav-link.active {
            font-weight: bold;
        }
    </style>
</section>
@endsection
