<!-- extend sidebar -->
@extends("layouts.sidebar")

<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
<link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
<link rel="stylesheet" href="assets/extensions/toastify-js/src/toastify.css">
<link rel="stylesheet" href="assets/css/pages/filepond.css">
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="assets/css/pages/datatables.css">
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
                <!-- <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>SKU1_商品<br />管理コード</th>
                                <th>画像</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>メルカリカテゴリー</th>
                                <th>Keepa URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($exhibitions) == 0)
                            <tr>
                                <td colspan="6" style="text-align: center;">出品するデータはありません。</td>
                            </tr>
                            @endif
                            @foreach($exhibitions as $e)
                            @php
                            $image = explode(';',$e->image);
                            @endphp
                            <tr id="{{$e->id}}">
                                <td>{{$e->m_code}}</td>
                                <td>
                                    <div style="max-width: 4rem;height: 4rem; background-color:white;text-align:center">
                                        <img style="max-width: 100%;max-height: 4rem;" src="{{ $image[0] }}" alt="image"><br>
                                    </div>
                                    @if ($e->prime == 'yes')
                                    <span class="badge bg-primary">prime</span>
                                    @else
                                    <span class="badge bg-danger">非prime</span>
                                    @endif
                                </td>
                                <td data-bs-toggle="tooltip">{{ json_decode($e->product) }}</td>
                                <td style="text-align:end">
                                    <span data-bs-toggle="tooltip" title="出品価格" class="badge bg-light-success">¥{{number_format($e->e_price)}}</span>
                                    <br><span data-bs-toggle="tooltip" title="アマゾン価格" class="badge bg-light-secondary">¥{{ number_format($e->price) }}</span>
                                    <br><span data-bs-toggle="tooltip" title="送料" class="badge bg-light-secondary">¥{{ number_format($e->postage) }}</span>
                                    <br><span data-bs-toggle="tooltip" title="その他費用" class="badge bg-light-secondary">¥{{ number_format($e->etc) }}</span>
                                </td>
                                <td data-bs-toggle="tooltip" title="{{$e->m_category_id}}">{{ $e->m_category }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ 'https://keepa.com/#!product/5-' . $e->ASIN }}" target="_blank"><img style="width: 200px;" title="{{ 'https://keepa.com/#!product/5-' .$e->ASIN }}" src={{ 'https://graph.keepa.com/pricehistory.png?asin=' . $e->ASIN . '&domain=co.jp&salesrank=1' }} /></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($exhibitions)) {{ $exhibitions->onEachSide(1)->links('mypage.pagination') }} @endif
                </div> -->
                <table class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th>SKU1_商品<br />管理コード</th>
                            <th>画像</th>
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
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/js/pages/datatables.min.js"></script>
<script src="assets/extensions/toastify-js/src/toastify.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }, false);

    $('#save_mercari_data').on('click', function() {
        $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
            $("#loader-4").show(); //makes page more lightweight 
        });
        $('.progress_loader').css('display', 'block');

        var progress_index = 1;
        const progress_func = setInterval(() => {
            if (progress_index < 100) {
                $('#progress').val(progress_index);
                progress_index++;
            } else {
                clearInterval(progress_func);
                progress_index = 1;
            }
        }, 1000 * 1);

        $.ajax({
            url: "http://localhost:32768/api/v1/amazon/saveMercari",
            //url: "http://xs021277.xsrv.jp/fmproxy/api/v1/amazon/saveMercari",
            type: "post",
            data: {
                user_id: '{{ Auth::id() }}',
            },
            success: function(res) {
                console.log(res);
                setTimeout(() => {
                    $('#progress').val(100);
                    location.href = '{{ route("entry_data") }}';
                }, 1000 * 100);
                if ($('#progress').val() == 99) {
                    Toastify({
                        text: "データが正常に保存されました。",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                }
            },
        });
        // $.ajax({
        //     url: "http://localhost:32768/api/v1/amazon/downloadImages",
        //     //url: "http://xs021277.xsrv.jp/fmproxy/api/v1/amazon/downloadImages",
        //     type: "post",
        //     data: {
        //         user_id: '{{Auth::user()->id}}'
        //     },
        //     beforeSend: function(data) {
        //         console.log(data);
        //     },
        //     success: function(res) {
        //         console.log(res);
        //         Toastify({
        //             text: "お待ちください。",
        //             duration: 2500,
        //             close: true,
        //             gravity: "top",
        //             position: "right",
        //             backgroundColor: "#4fbe87",
        //         }).showToast();
        //     }
        // });
    });

    $(document).ready(function() {
        $('.datatable').DataTable().ajax.reload();
    });

    const delete_data = function (id) {
        console.log(id);
        return;
    };

    var datatable = $('.datatable').DataTable({
        columnDefs: [
            {
                targets: 3,
                className: 'dt-body-right'
            }
        ],
        processing: true,
        serverSide: true,
        autoConfig: true,
        pageLength: 10,
        ajax: "{{ route('entry.list') }}",
        columns: [
            {
                data: 'm_code',
                name: 'm_code'
            },
            {
                data: 'image',
                name: 'image',
                render: function(data) {
                    url = data.split(';')[0];
                    return (
                        '<img src="' + url + '" alt="image" style="width: 50px; height: 50px;">'
                    );
                }
            },
            {
                data: 'product',
                name: 'product'
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
                data: 'm_category_id',
                name: 'm_category_id'
            },
            {
                data: 'ASIN',
                name: 'keepaURL',
                render: function (data) {
                    return (
                        `<a href="https://keepa.com/#!product/5-` + data + `" target="_blank"><img style="width: 200px;" title="https://keepa.com/#!product/5-` + data + `" src="https://graph.keepa.com/pricehistory.png?asin=` + data +  `&domain=co.jp&salesrank=1" /></a>`
                    )
                }
            },
            {
                data: 'id',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return (
                        `<btn class="btn btn-round btn-danger btn-sm" style="cursor:pointer" onclick="delete_data(` + data + `)"><i class="bi bi-trash"></i></btn>`
                    )
                }
            },
        ]
    });
</script>
<!-- end -->
@endpush
<!-- end additional scripts -->