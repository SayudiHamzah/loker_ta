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
                                <th>#</th>
                                <th>Nama User</th>
                                <th>Nama Loker</th>
                                <th>Key</th>
                                <th>UUID</th>
                                <th>Hasil KSA</th>
                                <th>Hasil PGRA</th>
                                <th>Hasil Desimal</th>
                                <th>Hasil Enkripsi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($encryptions as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ $item->name_locker ?? '-' }}</td>
                                    <td>{{ $item->key ?? '-' }}</td>
                                    <td>{{ $item->uuid ?? '-' }}</td>
                                    <td><code>{{ $item->hasil_ksa }}</code></td>
                                    <td><code>{{ $item->hasil_pgra }}</code></td>
                                    <td><code>{{ $item->hasil_desimal }}</code></td>
                                    <td>{{ $item->hasil_enkripsi }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
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
                                <th>#</th>
                                <th>Nama User</th>
                                <th>Nama Loker</th>
                                <th>Key</th>
                                <th>UUID</th>
                                <th>Hasil KSA</th>
                                <th>Hasil PGRA</th>
                                <th>Hasil Desimal</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($decryptions as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ $item->name_locker ?? '-' }}</td>
                                    <td>{{ $item->key ?? '-' }}</td>
                                    <td>{{ $item->uuid ?? '-' }}</td>
                                    <td><code>{{ $item->hasil_ksa }}</code></td>
                                    <td><code>{{ $item->hasil_pgra }}</code></td>
                                    <td><code>{{ $item->hasil_desimal }}</code></td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
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
</section>
@endsection
