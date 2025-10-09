{{-- resources/views/data-proses/show.blade.php --}}
@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('data-proses') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Alert untuk notifikasi copy -->
                    <div class="alert alert-success alert-dismissible fade show" id="copyAlert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
                        <span id="copyMessage">Teks berhasil disalin ke clipboard!</span>
                        <button type="button" class="close" onclick="hideAlert()">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="row">
                        <!-- Informasi Utama -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title mb-0">Informasi Utama</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">User</th>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Locker</th>
                                            <td>{{ $item->name_locker ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Key</th>
                                            <td><code>{{ $item->key ?? '-' }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>UUID</th>
                                            <td><code>{{ $item->uuid ?? '-' }}</code></td>
                                        </tr>
                                            @if ($title == "Detail Enkripsi")
                                        <tr>
                                            <th>UUID Encode</th>
                                            <td><code>{{ $encodeVal ?? '-' }}</code></td>
                                        </tr>
                                            @endif

                                        <tr>
                                            <th>Tanggal Dibuat</th>
                                            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terakhir Diupdate</th>
                                            <td>{{ $item->updated_at->format('d-m-Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Proses -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h4 class="card-title mb-0">Hasil Proses</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label><strong>Hasil KSA:</strong></label>
                                        <div class="code-block" id="codeKsa">
                                            <code>{{ $item->hasil_ksa ?? '-' }}</code>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><strong>Hasil PGRA:</strong></label>
                                        <div class="code-block" id="codePgra">
                                            <code>{{ $item->hasil_pgra ?? '-' }}</code>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><strong>Hasil Desimal:</strong></label>
                                        <div class="code-block" id="codeDesimal">
                                            <code>{{ $item->hasil_desimal ?? '-' }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    {{--  <button onclick="window.print()" class="btn btn-outline-primary">
                                        <i class="fas fa-print"></i> Print
                                    </button>  --}}
                                    <button onclick="copyToClipboard('hasil_ksa')" class="btn btn-outline-secondary">
                                        <i class="fas fa-copy"></i> Copy KSA
                                    </button>
                                    <button onclick="copyToClipboard('hasil_pgra')" class="btn btn-outline-secondary">
                                        <i class="fas fa-copy"></i> Copy PGRA
                                    </button>
                                    <button onclick="copyToClipboard('hasil_desimal')" class="btn btn-outline-secondary">
                                        <i class="fas fa-copy"></i> Copy Desimal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .code-block {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 12px;
        max-height: 200px;
        overflow-y: auto;
        word-break: break-all;
        white-space: pre-wrap;
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
    }

    .code-block code {
        background: none;
        padding: 0;
        color: #e83e8c;
    }

    .table th {
        background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            margin-bottom: 1rem;
        }

        .btn {
            margin-bottom: 5px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
// Fungsi untuk menampilkan alert
function showAlert(message, type = 'success') {
    const alert = document.getElementById('copyAlert');
    const messageSpan = document.getElementById('copyMessage');

    if (alert && messageSpan) {
        // Set class alert berdasarkan type
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        messageSpan.textContent = message;
        alert.style.display = 'block';

        // Auto hide setelah 3 detik
        setTimeout(hideAlert, 3000);
    }
}

// Fungsi untuk menyembunyikan alert
function hideAlert() {
    const alert = document.getElementById('copyAlert');
    if (alert) {
        alert.style.display = 'none';
    }
}

// Fungsi copy to clipboard yang diperbaiki
function copyToClipboard(field) {
    // Dapatkan teks berdasarkan field
    let text;
    switch(field) {
        case 'hasil_ksa':
            text = `{{ $item->hasil_ksa ?? '' }}`;
            break;
        case 'hasil_pgra':
            text = `{{ $item->hasil_pgra ?? '' }}`;
            break;
        case 'hasil_desimal':
            text = `{{ $item->hasil_desimal ?? '' }}`;
            break;
        default:
            text = '';
    }

    // Hilangkan tanda kutip jika ada
    text = text.trim();

    if (!text || text === '-') {
        showAlert('Tidak ada data untuk disalin!', 'warning');
        return;
    }

    // Method 1: Modern Clipboard API
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text)
            .then(function() {
                showAlert('Teks berhasil disalin ke clipboard!');
            })
            .catch(function(err) {
                // Fallback ke method lama jika modern method gagal
                fallbackCopyText(text);
            });
    } else {
        // Fallback untuk browser lama atau HTTP
        fallbackCopyText(text);
    }
}

// Fallback method untuk copy
function fallbackCopyText(text) {
    try {
        // Buat textarea sementara
        const textArea = document.createElement('textarea');
        textArea.value = text;

        // Buat textarea tidak terlihat
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);

        // Select dan copy
        textArea.focus();
        textArea.select();

        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);

        if (successful) {
            showAlert('Teks berhasil disalin ke clipboard!');
        } else {
            showAlert('Gagal menyalin teks!', 'error');
        }
    } catch (err) {
        console.error('Fallback copy error:', err);
        showAlert('Browser tidak mendukung copy to clipboard!', 'error');
    }
}

// Handler untuk error global (menangani error dari dashboard2.js)
window.addEventListener('error', function(e) {
    // Jika error berasal dari dashboard2.js dan terkait getContext, ignore
    if (e.filename && e.filename.includes('dashboard2.js') && e.message.includes('getContext')) {
        e.preventDefault();
        console.warn('Error dari dashboard2.js diabaikan');
        return true;
    }
});

// Alternative: Menggunakan try-catch block saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Coba inisialisasi komponen yang mungkin error
    try {
        // Code untuk inisialisasi chart atau komponen lain
        if (typeof Chart !== 'undefined') {
            // Inisialisasi chart jika diperlukan
        }
    } catch (error) {
        console.warn('Error inisialisasi komponen:', error.message);
    }
});
</script>
@endsection
