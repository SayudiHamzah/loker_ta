@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Menampilkan informasi user -->
                <div class="user-info">
                    <h4>Loker Information</h4>
                    {{--  <p><strong>ID:</strong> {{ $datauser->id }}</p>  --}}
                    <p><strong>Name:</strong> {{ $datauser->name }}</p>
                    <p><strong>Name loker:</strong> {{ $data->name_locker }}</p>
                    <p><strong>Status aktifitas:</strong> <span
                            class="badge {{ $data->qrcode->status_activitas == 1 ? 'badge-success' : 'badge-danger' }}">
                            {{ $data->qrcode->status_activitas == 1 ? 'Loker Kosong' : 'Sedang Digunakan' }}
                        </span></p>
                    <p><strong>Email:</strong> {{ $datauser->email }}</p>
                    <p><strong>Status:</strong> {{ $data->status == 1 ? 'Terbuka' : 'Tertutup' }}</p>
                    <p><strong>Created At:</strong> {{ $datauser->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $datauser->updated_at }}</p>
                </div>

                <form action="{{ route('loker.status', $data->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $data->status == 1 ? 0 : 1 }}">
                    <input type="hidden" name="user_id" value="{{ $datauser->id }}">
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    {{--  <p><strong>Email:</strong> {{ $datauser->email }}</p>  --}}


                    <button type="submit"
                        class="btn {{ $data->status == 1 ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-block">
                        {{ $data->status == 1 ? 'Tutup' : 'Buka' }}
                    </button>
                </form>

                <br>

                {{--  <button type="button" class="btn btn-outline-warning btn-block">Riwayat</button>  --}}
                <a href="{{ route('loker.history', $data->id) }}" class="btn btn-outline-warning btn-block">
                    {{--  <a href="{{ route('loker.history', $data->id) }}" class="btn btn-outline-warning btn-block">  --}}
                    Riwayat
                </a>
                <br>
                <a href="{{ route('loker.akses', $data->id) }}" class="btn btn-outline-primary btn-block">
                    {{--  <a href="{{ route('loker.history', $data->id) }}" class="btn btn-outline-warning btn-block">  --}}
                    Hak Akses
                </a>


                {{--  <br>  --}}


                <!-- QR Code Container -->
                <div class="qr-code-container"
                    style="border: 1px solid #ddd; padding: 20px; display: inline-block; margin-top: 20px;">
                    {!! QrCode::size(300)->generate($data->qrcode->qrcode) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
