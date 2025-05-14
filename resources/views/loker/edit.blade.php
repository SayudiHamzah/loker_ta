@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('loker.update', $datas->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $datas->id }}">
                        <div class="card-body">

                            <!-- Select User -->
                            <div class="form-group">
                                <label for="user_id">Select User</label>
                                <select class="form-control" name="user_id" id="user_id">
                                    @foreach ($dataA as $id => $name)
                                        <option value="{{ $id }}" {{ $datas->user_id == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Status -->
                            <div class="form-group">
                                <label for="status">Status Loker</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1" {{ $datas->status == 1 ? 'selected' : '' }}>Terbuka</option>
                                    <option value="0" {{ $datas->status == 0 ? 'selected' : '' }}>Tertutup</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_aja')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const originalUserId = "{{ $datas->user_id }}";
        const originalStatus = "{{ $datas->status }}";

        const userSelect = document.getElementById('user_id');
        const statusSelect = document.getElementById('status');
        const submitBtn = document.getElementById('submitBtn');

        function checkForChanges() {
            const isChanged = userSelect.value !== originalUserId || statusSelect.value !== originalStatus;
            submitBtn.disabled = !isChanged;
        }

        // Event listener
        userSelect.addEventListener('change', checkForChanges);
        statusSelect.addEventListener('change', checkForChanges);

        // Inisialisasi
        checkForChanges();
    });
</script>
@endsection
