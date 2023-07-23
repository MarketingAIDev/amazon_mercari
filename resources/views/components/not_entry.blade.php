<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<!-- start datatable css -->
<!-- <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
<link rel="stylesheet" href="assets/css/pages/simple-datatables.css"> -->
<!-- end datatable css -->
<!-- start file choose css -->
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css')}}">
<link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/pages/datatables.css')}}">
<style>
    table td,
    table th {
        min-width: 3rem ! important;
    }
</style>
<!-- end file css -->
@endsection
<!-- end additional css -->
<!-- start this page -->
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-4 order-md-1 order-last">
                <h3>出品不可商品</h3>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-first">
                <a href="{{ route('export_xlsx_not_entry') }}" id="export_not_entry_data" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-download"></i> xlsx</a>
                <a href="{{route('entry_data')}}" class="btn btn  btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-file-earmark-font-fill"></i> 出品対象商品</a>
                <a href="{{route('entry_data_not')}}" class="btn btn-primary block float-start float-lg-end m-2"><i class="bi bi-file-earmark-font-fill"></i> 出品不可商品</a>
                <a href="{{route('entry_setting')}}" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-pencil"></i>出品設定</a>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover datatable" style="min-width:980px">
                    <thead>
                        <tr>
                            <th>画像</th>
                            <th>ASIN</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>メルカリカテゴリー</th>
                            <th>除外理由</th>
                            <!-- <th>除外理由</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<!-- start file js -->
<script src="{{ asset('assets/extensions/filepond/filepond.js')}}"></script>
<script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/pages/datatables.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);


    const refresh_page = () => {
        location.href = "{{ route('entry_data') }}";
    };

    $(document).ready(function() {
        $('.datatable').DataTable().ajax.reload();

    });

    var datatable = $('.datatable').DataTable({
        columnDefs: [{
            targets: 3,
            className: 'dt-body-right'
        }],
        processing: true,
        serverSide: true,
        autoConfig: true,
        pageLength: 10,
        ajax: "{{ route('not_entry_list') }}",
        columns: [{
                data: null,
                name: 'image',
                render: function(data, type, row) {
                    url = row.image.split(';')[0];
                    if (row.prime == 'yes') {
                        return (
                            `<div style="width:4rem;height:4rem;background-color:white;text-align:center">
                                <img src="${url}" alt="IMG 取得中" style="width: 50px; height: 50px;">
                            </div>
                            <span class="badge bg-primary">prime</span>`
                        );
                    } else {
                        return (
                            `<div style="width:4rem;height:4rem;background-color:white;text-align:center">
                                <img src="${url}" alt="IMG 取得中" style="width: 50px; height: 50px;">
                            </div>
                            <span class="badge bg-danger">非prime</span>`
                        );
                    }
                }
            },
            {
                data: 'ASIN',
                name: 'ASIN'
            },
            {
                data: 'jsonstr',
                name: 'jsonstr',
            },
            {
                data: null,
                name: 'e_price',
                render: function(data, type, row) {
                    return (
                        '<span data-bs-toggle="tooltip" title="出品価格" class="badge bg-light-success">¥' + row['e_price'] + '</span>' +
                        '<br/><span data-bs-toggle="tooltip" title="アマゾン価格" class="badge bg-light-secondary">¥' + row['price'] + '</span>' +
                        '<br/><span data-bs-toggle="tooltip" title="送料" class="badge bg-light-secondary">¥' + row['postage'] + '</span>' +
                        '<br/><span data-bs-toggle="tooltip" title="その他費用" class="badge bg-light-secondary">¥' + row['etc'] + '</span>'
                    );
                }
            },
            {
                data: 'm_category',
                name: 'm_category'
            },
            {
                data: 'exclusion',
                name: 'exclusion',
            },
        ]
    });
</script>
<!-- end 
    -->
@endpush
<!-- end additional scripts -->