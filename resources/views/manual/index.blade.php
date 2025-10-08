@extends('layout.app')
@section('content')
<section class="content">
    <div class="container-fluid">
        {{-- Status Loker --}}
        <h4 class="mb-4">Status Loker</h4>

        <div class="row justify-content-center">
            <div class="col-4 col-sm-4 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-boxes"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Jumlah User</span>
                        <span class="info-box-number">
                            {{ $jumlahUser }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-4 col-sm-4 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Jumlah Loker Kosong</span>
                        <span class="info-box-number">{{ $penggunaanLoker }} / 4</span>
                    </div>
                </div>
            </div>

            <div class="col-4 col-sm-4 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Penggunaan Harian</span>
                        <span class="info-box-number">{{ $penggunaanHarian }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Prepare mapping id => status agar hak akses bisa tahu status kontrol manual --}}
        @php
            // pastikan $lockers adalah collection/array dari DB yang memiliki id dan status
            $lockerStatus = [];
            foreach ($lockers as $lk) {
                $lockerStatus[$lk['id']] = isset($lk['status']) && $lk['status'] === '1' ? 1 : 0;
            }

            $colors = ['bg-info', 'bg-success', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-secondary'];
            $users = \App\Models\User::pluck('name', 'id');
        @endphp

        {{-- Hak Akses --}}
        <h4 class="mb-4">Hak Akses</h4>
        <div class="row">
            @foreach ($haskAkses as $loker)
                @php
                    $randomColor = $colors[array_rand($colors)];
                    $userName = $users[$loker['user_id']] ?? 'Tidak diketahui';
                    // status apakah ada user (izin) -- tetap asli
                    $hasUser = $loker['user_id'] != '1';
                    // cek status kontrol manual (gunakan mapping)
                    $isOpen = isset($lockerStatus[$loker['id']]) && $lockerStatus[$loker['id']] == 1;
                @endphp

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        {{-- tambahkan data-locker-id agar JS dapat menemukan dan sinkron warna --}}
                        <span class="info-box-icon {{ $isOpen ? 'bg-success' : 'bg-danger' }} elevation-1"
                              data-locker-id="{{ $loker['id'] }}" data-section="hak-akses">
                            <i class="fas fa-lock"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text"
                                  title="Nama Loker: {{ $loker['name_locker'] }}">
                                {{ $loker['name_locker'] }}
                            </span>

                            <label class="switch-container">
                                <div class="switch">
                                    <input
                                        type="checkbox"
                                        id="toggleBtn{{ $loker['id'] }}"
                                        data-id="{{ $loker['id'] }}"
                                        data-user-name="{{ $userName }}"
                                        data-toggle-type="cabut"
                                        {{ $hasUser ? 'checked' : 'disabled' }}
                                    />
                                    <span class="slider"></span>
                                </div>

                                <span class="label-text" id="labelHakAkses{{ $loker['id'] }}">
                                    @if ($hasUser)
                                        ✅ Diizinkan - <span class="badge {{ $isOpen ? 'badge-success' : 'badge-secondary' }}" id="badgeUser{{ $loker['id'] }}">{{ $userName }}</span>
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
        <h4 class="mb-4">Kontrol Manual</h4>

        @php
            $colors2 = ['bg-info', 'bg-danger', 'bg-success', 'bg-warning', 'bg-primary', 'bg-secondary'];
            $icons = ['fas fa-boxes'];
            $users = \App\Models\User::pluck('name', 'id');
        @endphp

        <div class="row">
            @foreach ($lockers as $loker)
                @php
                    $randomIcon = $icons[array_rand($icons)];
                    $status = $loker['status'] === '1';
                @endphp

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        {{-- identifikasi icon kontrol manual juga dengan data-locker-id --}}
                        <span class="info-box-icon {{ $status ? 'bg-success' : 'bg-danger' }} elevation-1"
                              data-locker-id="{{ $loker['id'] }}" data-section="kontrol-manual">
                            <i class="{{ $randomIcon }}"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text"
                                  title="Loker dimiliki oleh: {{ $users[$loker['user_id']] ?? 'Tidak diketahui' }}">
                                {{ $loker['name_locker'] }}
                            </span>

                            <label class="switch-container">
                                <div class="switch">
                                    <input
                                        type="checkbox"
                                        id="toggleBtnB{{ $loker['id'] }}"
                                        class="status-toggle"
                                        data-id="{{ $loker['id'] }}"
                                        data-toggle-type="status"
                                        data-user-id="{{ $loker['user_id'] }}"
                                        {{ $status ? 'checked' : '' }}
                                    />
                                    <span class="slider"></span>
                                </div>

                                <span class="label-text" id="labelKontrol{{ $loker['id'] }}">
                                    {{ $status ? 'BUKA' : 'TUTUP' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- other content... (grafik dll) -->

        <!-- Load ApexCharts for Larapex -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        {!! $chart->script() !!}

    </div>

    <script>
        // Script: sinkron warna antara Hak Akses dan Kontrol Manual saat status berubah
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const toggleType = this.dataset.toggleType;
                    const lockerId = this.dataset.id;
                    const userId = this.dataset.userId;
                    const labelHak = document.getElementById('labelHakAkses' + lockerId);
                    const labelKontrol = document.getElementById('labelKontrol' + lockerId);
                    const badgeUser = document.getElementById('badgeUser' + lockerId);

                    // Helper: ubah warna (bg-success / bg-danger) pada kedua ikon (hak akses & kontrol manual)
                    function updateIconsColor(isOpen) {
                        const iconSpans = document.querySelectorAll('[data-locker-id="' + lockerId + '"]');
                        iconSpans.forEach(span => {
                            // ganti kelas bg-success <-> bg-danger
                            span.classList.remove('bg-success', 'bg-danger');
                            span.classList.add(isOpen ? 'bg-success' : 'bg-danger');
                        });
                    }

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
                                        Swal.fire('Berhasil!', data.message, 'success')
                                            .then(() => location.reload());
                                    } else {
                                        Swal.fire('Gagal!', data.message, 'error');
                                        this.checked = true; // kembalikan
                                    }
                                }).catch(err => {
                                    Swal.fire('Gagal!', 'Terjadi kesalahan jaringan', 'error');
                                    this.checked = true;
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
                            // Update label pada Kontrol Manual
                            if (labelKontrol) {
                                labelKontrol.textContent = newStatus === 1 ? 'BUKA' : 'TUTUP';
                            }

                            // Update warna ikon di kedua bagian
                            updateIconsColor(newStatus === 1);

                            // Update badge di Hak Akses (jika ada user, ubah warna badge agar sinkron)
                            if (badgeUser) {
                                badgeUser.classList.remove('badge-success', 'badge-secondary');
                                badgeUser.classList.add(newStatus === 1 ? 'badge-success' : 'badge-secondary');
                            }

                            // Jika ingin juga mengubah teks di Hak Akses (opsional)
                            if (labelHak) {
                                // jangan ubah teks "Diizinkan" / "Kosong" — hanya ubah warna via badge di atas
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

    <div class="card-body">
        {{--  <h4 class="mb-4">Grafik Penggunaan Loker Terakhir</h4>  --}}
        <div class="row">
            <!-- Chart Section -->
            {{--  <div class="col-md-12 col-lg-6 mb-4">
                {!! $chart->container() !!}
            </div>  --}}

            <!-- Card with Image -->
            {{--  <div class="col-md-12 col-lg-6 mb-4">
                <div class="card mb-2 h-100">
                    <img class="card-img-top img-fluid" src="../dist/img/A2.jpg" alt="Dist Photo 3">
                    <div class="card-img-overlay" style="background-color: rgba(0,0,0,0.5);">
                        <h5 class="card-title text-primary">E-Locker dengan Keamanan Kriptografi Algoritma RC4</h5>
                        <p class="card-text pb-1 pt-1 text-white">
                            E-locker adalah sistem penyimpanan barang pribadi berbasis teknologi digital yang dirancang untuk memberikan keamanan dan kemudahan akses. Sistem ini menggunakan loker yang dilengkapi dengan kunci digital, seperti QR code, sebagai metode autentikasi. Untuk meningkatkan perlindungan data, E-locker menerapkan enkripsi <strong>RC4</strong> dalam proses pengamanan informasi pengguna dan kode autentikasi. Dengan teknologi ini, E-locker menjadi solusi praktis dan aman bagi siapa pun yang membutuhkan tempat penyimpanan sementara yang terpercaya, baik di lingkungan kerja, pendidikan, maupun fasilitas umum.
                        </p>
                    </div>
                </div>
            </div>  --}}

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card mb-2 h-100 text-white">
                    <div class="card-bg"
                         style="
                             /* background-image: url('../dist/img/A2.jpg'); */
                             background-size: cover;
                             background-position: center;
                             color: white;
                             height: 100%;
                             min-height: 300px; /* Sesuaikan tinggi minimal */
                             position: relative;
                         ">
                        <div class="card-img-overlay">
                            <h5 class="card-title text-dark"><b>E-Locker dengan Keamanan Kriptografi Algoritma RC4</b></h5>
                            <p class="card-text pb-1 pt-1 text-dark">
                                E-locker adalah sistem penyimpanan barang pribadi berbasis teknologi digital yang dirancang untuk memberikan keamanan dan kemudahan akses. Sistem ini menggunakan loker yang dilengkapi dengan kunci digital, seperti QR code, sebagai metode autentikasi. Untuk meningkatkan perlindungan data, E-locker menerapkan enkripsi <strong>RC4</strong> dalam proses pengamanan informasi pengguna dan kode autentikasi. Dengan teknologi ini, E-locker menjadi solusi praktis dan aman bagi siapa pun yang membutuhkan tempat penyimpanan sementara yang terpercaya, baik di lingkungan kerja, pendidikan, maupun fasilitas umum.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>
@endsection
