@extends('layouts.app')

@section('title', 'Sales')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Sales</li>
    </ol>
@endsection



@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Bắt đầu<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Bắt đầu"
                                               value="{{$start_date}}"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Kết thúc<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="end_date" id="end_date" placeholder="Kết thúc"
                                               value="{{$end_date }}"/>
                                    </div>
                                </div>
                                <div class="ml-3 form-group mb-0 d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary">
                                        Tìm kiếm
                                    </button>
                                </div>
                            </div>

                        </form>
                        <hr>

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    {!! $dataTable->scripts() !!}
@endpush
