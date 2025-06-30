@extends('layout.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-body">
                    <h4 class="mb-4">Grafik Penggunaan Loker Terakhir</h4>

                    <div class="row">
                        <!-- Chart Section -->
                        <div class="col-md-12 col-lg-6 mb-4">
                          {!! $chart->container() !!}
                        </div>

                        <!-- Card with Image -->
                        <div class="col-md-12 col-lg-6 mb-4">
                          <div class="card mb-2 h-100">
                            <img class="card-img-top img-fluid" src="../dist/img/photo3.jpg" alt="Dist Photo 3">
                            <div class="card-img-overlay" style="background-color: rgba(0,0,0,0.5);">
                              <h5 class="card-title text-primary">Card Title</h5>
                              <p class="card-text pb-1 pt-1 text-white">
                                E-locker adalah sistem penyimpanan barang pribadi berbasis teknologi digital yang dirancang untuk memberikan keamanan dan kemudahan akses. Sistem ini menggunakan loker yang dilengkapi dengan kunci digital, seperti QR code, sebagai metode autentikasi. Untuk meningkatkan perlindungan data, E-locker menerapkan enkripsi <strong>RC4</strong> dalam proses pengamanan informasi pengguna dan kode autentikasi. Dengan teknologi ini, E-locker menjadi solusi praktis dan aman bagi siapa pun yang membutuhkan tempat penyimpanan sementara yang terpercaya, baik di lingkungan kerja, pendidikan, maupun fasilitas umum.
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>

                </div>
            </div>
            <h4 class="mb-4">Manajemen Hak Akses</h4>
            @php
                $colors = ['bg-info', 'bg-success', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-secondary'];
                $users = \App\Models\User::pluck('name', 'id');
            @endphp

            <div class="row">
                @foreach ($haskAkses as $loker)
                    @php
                        $randomColor = $colors[array_rand($colors)];
                        $userName = $users[$loker['user_id']] ?? 'Tidak diketahui';
                        $status = $loker['user_id'] != '1';
                    @endphp
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon {{ $randomColor }} elevation-1">
                                <i class="fas fa-lock"></i>
                            </span>

                            <div class="info-box-content">
                                <span class="info-box-text"
                                    title="Nama Loker: {{ $loker['name_locker'] }}">{{ $loker['name_locker'] }}</span>

                                <label class="switch-container">
                                    <div class="switch">
                                        <input type="checkbox" id="toggleBtn{{ $loker['id'] }}"
                                            data-id="{{ $loker['id'] }}" data-user-name="{{ $userName }}"
                                            data-toggle-type="cabut" {{ $status ? 'checked' : 'disabled' }} />
                                        <span class="slider"></span>
                                    </div>
                                    <span class="label-text" id="labelText{{ $loker['id'] }}">
                                        @if ($status)
                                        ✅ Diizinkan - <span class="badge badge-success">{{ $userName }}</span>
                                        @else
                                        ❌ Kosong
                                        @endif
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Kontrol Manual -->
            <h4 class="mb-4">Manajemen Kontrol Manual</h4>
            @php
                $colors = ['bg-info', 'bg-danger', 'bg-success', 'bg-warning', 'bg-primary', 'bg-secondary'];
                $icons = ['fas fa-boxes'];
            @endphp

            <div class="row">
                @foreach ($lockers as $loker)
                    @php
                        $randomColor = $colors[array_rand($colors)];
                        $randomIcon = $icons[array_rand($icons)];
                        $status = $loker['status'] === '1';
                        $users = \App\Models\User::pluck('name', 'id');
                    @endphp
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon {{ $status ? 'bg-success' : 'bg-danger' }} elevation-1">
                                <i class="{{ $randomIcon }}"></i>
                            </span>

                            <div class="info-box-content">
                                <span class="info-box-text"
                                    title="Loker dimiliki oleh: {{ $users[$loker['user_id']] ?? 'Tidak diketahui' }}">
                                    {{ $loker['name_locker'] }}
                                </span>
                                <label class="switch-container">
                                    <div class="switch">
                                        <input type="checkbox" id="toggleBtnB{{ $loker['id'] }}" class="status-toggle"
                                            data-id="{{ $loker['id'] }}" data-toggle-type="status"
                                            data-user-id="{{ $loker['user_id'] }}" {{ $status ? 'checked' : '' }} />
                                        <span class="slider"></span>
                                    </div>
                                    <span class="label-text" id="labelText{{ $loker['id'] }}">
                                        {{ $status ? 'BUKA' : 'TUTUP' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>



            <h4 class="mb-4">Status Loker</h4>

            <div class="row justify-content-center">
                <div class="col-4 col-sm-4 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-boxes"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah User</span>
                            <span class="info-box-number">
                                {{ $jumlahUser }}
                                {{--  <small>%</small>  --}}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-4 col-sm-4 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Loker Kosong</span>
                            <span class="info-box-number">{{ $penggunaanLoker }} / 4</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-4 col-sm-4 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Penggunaan Harian</span>
                            {{--  <span class="info-box-number">760</span>  --}}
                            <span class="info-box-number">{{ $penggunaanHarian }}</span>

                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>



            <!-- Load ApexCharts for Larapex -->
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            {!! $chart->script() !!}

        </div>
        <!-- /.row -->



        <script>
            const checkbox = document.getElementById("toggleBtn");
            const labelText = document.getElementById("labelText");

            checkbox.addEventListener("change", function() {
                labelText.textContent = this.checked ? "BUKA" : "TUTUP";
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');

                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        const toggleType = this.dataset.toggleType;
                        const lockerId = this.dataset.id;
                        const userId = this.dataset.userId;
                        const label = document.getElementById('labelText' + lockerId);

                        if (toggleType === 'cabut') {
                            const userName = this.dataset.userName;

                            Swal.fire({
                                title: 'Apakah kamu yakin?',
                                text: "Cabut akses untuk pengguna: " + userName,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, cabut!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch("{{ route('loker.delete.akses') }}", {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                id: lockerId
                                            })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire('Berhasil!', data.message,
                                                        'success')
                                                    .then(() => location.reload());
                                            } else {
                                                Swal.fire('Gagal!', data.message, 'error');
                                            }
                                        });
                                } else {
                                    this.checked = true; // Balik lagi toggle jika batal
                                }
                            });

                        } else if (toggleType === 'status') {
                            const newStatus = this.checked ? 1 : 0;

                            fetch(`/loker/update-dashst/${lockerId}/${userId}`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({
                                        status: newStatus
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (label) {
                                        label.textContent = newStatus === 1 ? 'BUKA' : 'TUTUP';
                                    }
                                })
                                .catch(error => {
                                    console.error('Gagal update status:', error);
                                    alert('Gagal update status. Coba lagi.');
                                    this.checked = !this.checked; // Revert toggle
                                });
                        }
                    });
                });
            });
        </script>

        </div>
    </section>
@endsection
