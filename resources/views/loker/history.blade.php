@extends('layout.app')
@section('content')
    <section class="content">
        <div class="container-fluid">

            @php
                $groupedLogs = $datalog->groupBy(function ($log) {
                    return $log->created_at->format('Y-m-d');
                });
            @endphp

            <div class="timeline">
                @foreach ($groupedLogs as $date => $logs)
                    <!-- Timeline time label sekali per tanggal -->
                    <div class="time-label">
                        <span class="bg-primary">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                    </div>

                    @foreach ($logs as $log)
                        <div>
                            <i class="fas fa-box bg-blue"></i>
                            <div class="timeline-item">
                                {{--  <span class="time"><i class="fas fa-clock"></i>
                                    {{ $log->created_at->format('H:i:s') }}</span>  --}}
                                <h3 class="timeline-header">
                                    Penggunaan QRCode oleh
                                    <span
                                        class="badge {{ $log->user->status == 'user' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $log->user->status }}
                                    </span>

                                    {{--  {{ $log->user->status }}  --}}
                                </h3>

                                <div class="timeline-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td style="color: black">
                                                <p style="color: black"> Nama Loker</p>
                                               </td>
                                            <td>:</td>
                                            <td>
                                                {{ $log->loker->name_locker }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Aktivitas</td>
                                            <td>:</td>
                                            <td>
                                                <span
                                                    class="badge {{ $log->qrcode->status_activitas == 1 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $log->qrcode->status_activitas == 1 ? 'Loker Kosong' : 'Sedang Digunakan' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama Pengguna</td>
                                            <td>:</td>
                                            <td>{{ $log->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>:</td>
                                            <td>{{ $log->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Penggunaan</td>
                                            <td>:</td>
                                            <td>{{ $log->created_at->format('H:i:s') }}</td>
                                        </tr>
                                    </table>


                                    {{--  QR Code: <strong>{{ $log->qrcode->qrcode ?? 'Tidak tersedia' }}</strong><br>  --}}
                                    <div style="display: flex; justify-content: center; margin-top: 20px;">
                                        <div class="qr-code-container"
                                            style="border: 1px solid #ddd; padding: 20px; display: inline-block;">
                                            {!! QrCode::size(200)->generate($log->qrcode->qrcode) !!}
                                        </div>
                                    </div>

                                    {{--  Waktu Penggunaan: {{ $log->waktu_penggunaan }}  --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach

                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>
            </div>


        </div>
        <!-- /.timeline -->

    </section>
@endsection
