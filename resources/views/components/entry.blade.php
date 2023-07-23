<!-- extend sidebar -->
@extends("layouts.sidebar")

<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="{{asset('assets/extensions/filepond/filepond.css')}}">
<link rel="stylesheet" href="{{asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">
<link rel="stylesheet" href="{{asset('assets/extensions/toastify-js/src/toastify.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/pages/filepond.css')}}">
<link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/pages/datatables.css')}}">
@endsection
<!-- end additional css -->

<!-- start this page -->
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-3 order-md-1 order-last">
                <h3>出品対象商品</h3>
            </div>
            @if ($errors->first('upload'))
            <div class="alert alert-info alert-dismissible fade show mercari_list_fail" role="alert">
                <span class="alert-text text-white "> {{$errors->first('upload')}} </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif ($errors->first('upload'))
            <div class="col-12 col-md-9 order-md-2 order-first">
                <a id="save_mercari_data" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-window-plus"></i> 出品商品登録</a>
                <a href="{{ route('export_xlsx_entry') }}" id="export_entry_data" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-download"></i> xlsx</a>
                <a href="{{route('entry_data')}}" class="btn btn btn-primary block float-start float-lg-end m-2"><i class="bi bi-file-earmark-font-fill"></i> 出品対象商品一覧</a>
                <a href="{{route('entry_data_not')}}" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-file-earmark-font-fill"></i> 出品不可商品一覧</a>
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
                            <th>SKU1_商品<br />管理コード</th>
                            <th>ASIN</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>メルカリカテゴリー</th>
                            <th>Keepa URL</th>
                            <th>操作</th>
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
<script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/pages/datatables.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }, false);
    $('#save_mercari_data').on('click', function() {
        if (!window.confirm('メルカリに商品を登録しますか？')) {
            return;
        }
        $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
            $("#loader-4").show(); //makes page more lightweight 
        });
        $('.progress_loader').css('display', 'block');

        var progress_index = 1;
        const progress_func = setInterval(() => {
            if (progress_index < 95) {
                $('#progress').val(progress_index);
                progress_index++;
            } else {
                clearInterval(progress_func);
                progress_index = 1;
            }
        }, 1000 * 2);

        $.ajax({
            // url: "http://localhost:32768/api/v1/amazon/saveMercari",
            url: "https://xs021277.xsrv.jp/fmproxy/api/v1/amazon/saveMercari",
            type: "post",
            data: {
                user_id: '{{ Auth::id() }}',
            },
            success: function(res) {
                let res_count = res.msg;
                if (res.msg < 1800)
                    res_count = 1800;
                setTimeout(() => {
                    clearInterval(progress_func);
                    $('#progress').val(100);
                    location.href = '{{ route("entry_data") }}';
                }, 1030 * res_count);

                if (res.msg > 500) {
                    Toastify({
                        text: "出品データが多い場合、\n長い時間がかかることがあります。",
                        duration: 2100,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#56b6f7",
                    }).showToast();
                }
            },
            error: function() {
                clearInterval(progress_func);
                $('#progress').val(100);
                Toastify({
                    text: "5〜10秒後にもう一度クリックしてください。",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "rgb(25 178 203)",
                }).showToast();
                setTimeout(() => {
                    location.href = '{{ route("entry_data") }}';
                }, 1000 * 3);
            }
        });
    });

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
        ajax: "{{ route('entry_list') }}",
        columns: [{
                data: null,
                name: 'image',
                render: function(data, type, row) {
                    url = row.image.split(';')[0];
                    if (row.prime == 'yes') {
                        return (
                            `<div style="width:4rem;height:4rem;background-color:white;text-align:center">
                                <img src="${url}" alt="IMG" style="width: 50px; height: 50px;">
                            </div>
                            <span class="badge bg-primary">prime</span>`
                        );
                    } else {
                        return (
                            `<div style="width:4rem;height:4rem;background-color:white;text-align:center">
                                <img src="${url}" alt="IMG" style="width: 50px; height: 50px;">
                            </div>
                            <span class="badge bg-danger">非prime</span>`
                        );
                    }
                }
            },
            {
                data: 'm_code',
                name: 'm_code',
            },
            {
                data: 'ASIN',
                name: 'ASIN',
            },
            {
                data: 'product',
                name: 'product',
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
                data: 'ASIN',
                name: 'keepaURL',
                render: function(data) {
                    return (
                        `<a href="https://keepa.com/#!product/5-` + data + `" target="_blank"><img style="width: 200px;" title="https://keepa.com/#!product/5-` + data + `" src="https://graph.keepa.com/pricehistory.png?asin=` + data + `&domain=co.jp&salesrank=1" /></a>`
                    )
                }
            },
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return (
                        `<a class="btn btn-round btn-danger btn-sm" style="cursor:pointer" onclick="return window.confirm('データを本当に削除しますか？');" href="/delete_entry_data/` + data + `"><i class="bi bi-trash"></i></a>`
                    )
                }
            },
        ]
    });
</script>
<!-- end -->
@endpush
<!-- end additional scripts -->