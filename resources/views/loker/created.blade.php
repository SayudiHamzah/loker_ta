@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('loker.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            {{--  <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name" required>
                            </div>  --}}

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- select -->
                                        <div class="form-group">
                                            <label>Select user</label>
                                            <select class="form-control" name="user_id">
                                                @foreach ($datas as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- select -->
                                        <div class="form-group">
                                            <label>Status Loker</label>
                                            <select class="form-control" name="status">
                                                {{--  @foreach ($datas as $id => $name)  --}}
                                                    <option value="1">Terbuka</option>
                                                    <option value="0">Tertutup</option>
                                                {{--  @endforeach  --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
