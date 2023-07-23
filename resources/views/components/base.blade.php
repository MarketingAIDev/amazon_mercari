<!-- extend layouts -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<!-- start datatable css -->

<link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">

<link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css')}}">
<link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/pages/datatables.css')}}">
<script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>
<!-- end file css -->
<!-- end additional css -->
@endsection
<!-- start this page -->
@section('content')
<div class="page-heading" id="product_list">
    <div class="page-title">
        <div class="row">
            <div class="col-4 col-md-4 order-md-1 order-last">
                <!-- <h3>Amazon商品リスト</h3> -->
                <h5 style="color:#3550b1" class="mt-3">【データ取得中の商品】:<strong style="color:#3550b1" id="updating"></strong></h5>
            </div>
            <div class="col-4 col-md-4 order-md-1 order-last">
                <h5 style="color: #7c8d21;" class="mt-3">【データ取得完了の商品】:<strong style="color: #7c8d21;" id="complete"></strong></h5>
            </div>
            <div class="col-4 col-md-4 order-md-2 order-first">
                <!-- <a href="#" class="btn btn-outline-primary breadcrumb-header float-start float-lg-end"></a> -->
                <!-- <button type="button" class="my-2 btn btn-outline-primary block float-start float-lg-end mx-3"><i class="bi bi-tools"></i>設定</button> -->
                <button type="button" class="m-2 btn btn-danger btn-icon float-lg-end" id="amazon" onclick="allDataRemove()"><i class="bi bi-trash"></i> 削除する</button>
                <button type="button" class="m-2 btn btn-primary block float-start float-lg-end" data-bs-toggle="modal" data-bs-target="#backdrop">
                    <i class="bi bi-plus-circle"></i> 登録する
                </button>
                <div class="modal fade text-left amazon_mercari_modal" id="backdrop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title" id="myModalLabel4">登録</h4>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <div class="modal-body" style="overflow: hidden;">
                                <input type="file" name="xlsx" id="xlsx" class="form-control" onchange="xlReader(event)" />
                                <!-- <input type="file" class="form-control csv_event" style="cursor: pointer;" placeholder="CSVファイルを選択してください。" id="csvfile" /> -->
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="col-12 col-lg-3 btn btn btn-outline-primary hidden" data-bs-dismiss="modal" id="update_csv">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">登録する</span>
                                </button>
                                <button type="button" class="col-12 col-lg-3 btn btn-light-secondary " onclick="cancelXLSX()" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">キャンセル</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <th>Keepa URL</th>
                            <th>詳細</th>
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
@push('scripts')
<script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/pages/datatables.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script>
    let entry_tracking_history = [];
    let entry_check_condition = {};
    setInterval(() => {
        $.ajax({
            // url: "http://localhost:32768/api/v1/amazon/amazonGetProducts",
            url: "https://xs021277.xsrv.jp/fmproxy/api/v1/amazon/amazonGetProducts",
            type: "post",
            data: {
                user_id: '{{ Auth::id() }}',
            },
            success: function(res) {
                $('#updating').html(`${res.updating}件`);
                $('#complete').html(`${res.complete}件`);
                entry_tracking_history.push(res);
            },
            error: function() {

            }
        });
    }, 1000 * 20);
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
    var update_num = 0;
    var xlRowObjArr = [];
    // xlsx reader
    const xlReader = function(e) {
        var file = e.target.files[0];
        var reader = new FileReader();
        var ext = $('#xlsx').val().split(".").pop().toLowerCase();
        if ($.inArray(ext, ["xlsx"]) === -1) {
            alert("xlsx ファイルを選択してください。");
            return false;
        }

        reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
            var asins = [];
            workbook.SheetNames.forEach(function(sheetName) {
                xlRowObjArr = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            });

            for (const r of xlRowObjArr) {
                if (r.ASIN) {
                    asins.push(r.ASIN);
                }
            }
            console.log(xlRowObjArr);
            // $("#update_csv").attr("disabled", false);
            $("#update_csv").removeClass(' hidden');
            if (xlRowObjArr.length == 0) {
                alert('xlsx ファイルにデータがありません。');
                xlRowObjArr = [];
            } else if (xlRowObjArr[0]['Prime Eligible (Buy Box)'] == undefined && xlRowObjArr[0]['カテゴリ: Tree'] == undefined) {
                alert('選択した xlsx ファイル形式が正しくありません。');
                xlRowObjArr = [];
            } else {

                $('#update_csv').on('click', function() {
                    Toastify({
                        text: "データを保存するのに少し時間が必要です。",
                        duration: 3100,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
                        $("#loader-4").show(); //makes page more lightweight 
                    });
                    $('.progress_loader').css('display', 'block');

                    amazon_send('update', xlRowObjArr.slice(update_num, (update_num + 100)), (update_num + 100 >= xlRowObjArr.length) ? 'end' : 'start');

                });
            }
        };

        reader.onerror = function(ex) {};

        reader.readAsBinaryString(file);
    };

    function refresh_page() {
        location.href = "{{ route('base_data') }}";
    };

    function delete_product(id) {
        if (!window.confirm('データを本当に削除しますか？')) {
            return;
        }
        $.ajax({
            url: '{{ route("select_remove_product") }}',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: id
            },
            success: function() {
                Toastify({
                    text: "データが正常に削除されました。",
                    duration: 2300,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
                $('#tr_' + id).remove();
                setTimeout(() => {
                    location.href = window.location.href;
                }, 1000);
            }
        })
    };

    function allDataRemove() {
        if (!window.confirm('データを本当に削除しますか？')) {
            return;
        }
        $.ajax({
            url: "{{ route('amazon_remove_alldata') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {},
            success: function(res) {
                if (res === 'success') {
                    location.href = window.location.href;
                }
            }
        });
    }

    function amazon_send(condition, parameter, finish) {
        let postData = {
            condition: condition,
            xlRowObjArr: parameter
        };
        $.ajax({
            url: "{{ route('create_amazon_data') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                xlsxData: JSON.stringify(postData)
            },
            success: function(res) {
                if (update_num < xlRowObjArr.length) {
                    update_num += 100;
                    amazon_send('update', xlRowObjArr.slice(update_num, (update_num + 100)), (update_num + 100 >= xlRowObjArr.length) ? 'end' : 'start');

                    if (update_num > 0) {
                        var progress_len = (update_num / xlRowObjArr.length) * 100;
                        $('#progress').val(progress_len);
                    }

                } else {
                    $('#progress').val(100);
                    Toastify({
                        text: "データが正常に保存されました。",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                    setTimeout(() => {
                        location.href = "{{ route('base_data') }}";
                    }, 4000);

                }

                // let asins = [];
                // for (const r of postData.xlRowObjArr) {
                //     if (r.ASIN) {
                //         asins.push(r.ASIN);
                //     }
                // }
                // let priceData = {
                //     user_id: '{{ Auth::user()->id }}',
                //     codes: asins
                // };
                // $.ajax({
                //     // url: "http://localhost:32768/api/v1/amazon/getInfo",
                //     url: "http://xs021277.xsrv.jp/fmproxy/api/v1/amazon/getInfo",
                //     type: "post",
                //     data: {
                //         asin: JSON.stringify(priceData)
                //     },
                //     success: function() {
                //         Toastify({
                //             text: "データが正常に保存されました。",
                //             duration: 2000,
                //             close: true,
                //             gravity: "top",
                //             position: "right",
                //             backgroundColor: "#4fbe87",
                //         }).showToast();
                //     },
                // });
            }
        });
        return 'success';
    }

    $(document).ready(function() {
        $('.datatable').DataTable().ajax.reload();
        $.ajax({
            // url: "http://localhost:32768/api/v1/amazon/amazonGetProducts",
            url: "https://xs021277.xsrv.jp/fmproxy/api/v1/amazon/amazonGetProducts",
            type: "post",
            data: {
                user_id: '{{ Auth::id() }}',
            },
            success: function(res) {
                $('#updating').html(`${res.updating}件`);
                $('#complete').html(`${res.complete}件`);
                entry_tracking_history.push(res);
            },
            error: function() {

            }
        });
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
        ajax: "{{ route('amazon_list') }}",
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
                data: null,
                name: 'jsonstr',
                render: function (data,type,row) {
                    let product_error = row.product_error
                    console.log(product_error);
                    if (product_error != '' ) {
                        return (
                                `<span data-bs-toggle="tooltip" style="text-align:right;" class="badge bg-light-danger">${row.product_error}</span>`
                                )
                    }else {
                        return (
                            `${row.jsonstr}`
                        )
                    }
                }
            },
            {
                data: null,
                name: 'price',
                render: function(data, type, row) {
                    if (row.tracking_condition == 1 && row.price == 0) {
                        return (
                            `<span data-bs-toggle="tooltip" style="text-align: right;" class="badge bg-light-warning">Amazon価格なし</span>`
                        )
                    }
                    if (row.r_price == 0 && row.price == 0) {
                        return (
                            `<span data-bs-toggle="tooltip" style="text-align: right;" class="badge bg-light-warning">取得中</span>`
                        )
                    }
                    if (row.price > row.r_price) {
                        return (
                            `<span data-bs-toggle="tooltip" title="現在価格" style="text-align: right;" class="badge bg-light-success"><i class="bi bi-arrow-up"></i> ¥${row.price}</span>
                            <br/><span data-bs-toggle="tooltip" title="登録価格" class="badge bg-light-secondary">¥${row.r_price}</span>`
                        )
                    } else if (row.price < row.r_price) {
                        return (
                            `<span data-bs-toggle="tooltip" title="現在価格" style="text-align: right;" class="badge bg-light-success"><i class="bi bi-arrow-down"></i> ¥${row.price}</span>
                            <br/><span data-bs-toggle="tooltip" title="登録価格" class="badge bg-light-secondary">¥${row.r_price}</span>`
                        )
                    } else if (row.price == row.r_price)
                        return (
                            `<span data-bs-toggle="tooltip" title="現在価格" class="badge bg-light-success">¥ ${row.price} </span>
                            <br/><span data-bs-toggle="tooltip" title="登録価格" class="badge bg-light-secondary">¥${row.r_price} </span>`
                        );
                }
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
                        `<div class="dropdown">
                            <button class="btn btn-primary rounded-pill dropdown-toggle me-1" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                詳細
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                <a class="dropdown-item _info product_detail" style="cursor:pointer" href="base_product_info/` + data + `">もっと見る</a>
                                <a class="dropdown-item _info product_detail" style="cursor:pointer" href="base/edite/` + data + `">編集</a>
                                <button class="dropdown-item product_del" style="cursor:pointer" id="del_` + data + `" onclick="delete_product(` + data + `)">削除</button>
                            </div>
                        </div>`
                    )
                }
            },
        ]
    });

    function cancelXLSX() {
        location.href = location.href = window.location.href;
    }
</script>
@endpush