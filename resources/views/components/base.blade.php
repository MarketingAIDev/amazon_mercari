<!-- extend layouts -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<!-- start datatable css -->

<link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
<link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">

<link rel="stylesheet" href="assets/css/pages/filepond.css">
<script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>
<!-- end file css -->
<!-- end additional css -->
@endsection
<!-- start this page -->
@section('content')
<div class="page-heading" id="product_list">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Amazon商品リスト</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <!-- <a href="#" class="btn btn-outline-primary breadcrumb-header float-start float-lg-end"></a> -->
                <!-- <button type="button" class="my-2 btn btn-outline-primary block float-start float-lg-end mx-3"><i class="bi bi-tools"></i>設定</button> -->
                <button type="button" class="m-2 btn btn-danger btn-icon float-lg-end" id="amazon" onclick="allDataRemove()"><i class="bi bi-trash"></i> 削除する</button>
                <button type="button" class="m-2 btn btn-outline-primary block float-start float-lg-end" data-bs-toggle="modal" data-bs-target="#backdrop">
                    <i class="bi bi-plus-circle"></i> 登録する
                </button>
                <div class="modal fade text-left" id="backdrop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
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
                                <!-- <button type="button" class="col-12 col-lg-3 btn btn-outline-primary" data-bs-dismiss="modal" id='new_csv'>
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">登録する</span>
                                </button> -->
                                <button type="button" class="col-12 col-lg-3 btn btn btn-outline-primary" data-bs-dismiss="modal" id="update_csv">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">登録する</span>
                                </button>
                                <button type="button" class="col-12 col-lg-3 btn btn-light-secondary " data-bs-dismiss="modal">
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
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>画像</th>
                                <th>ASIN</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>詳細</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($products) == 0)
                            <tr>
                                <td colspan="5" style="text-align: center;">データがありません。</td>
                            </tr>
                            @endif
                            @foreach($products as $p)
                            <tr id="tr_{{$p->id}}">
                                @php
                                $image = explode(';',$p->image);

                                @endphp
                                <td><a data-bs-toggle="tooltip" title="Keepa" href="{{ 'https://keepa.com/#!product/5-' . $p->ASIN }}" target="_blank">
                                        <div style="width:4rem;height:4rem;background-color:white;text-align:center">
                                            <img style="max-width: 100%;max-height: 4rem;" src="{{$image[0]}}" alt="image">
                                        </div>
                                        @if ($p->prime == 'yes')
                                        <span class="badge bg-primary">prime</span>
                                        @else
                                        <span class="badge bg-danger">非prime</span>
                                        @endif
                                    </a>
                                </td>
                                <td>{{$p->ASIN}}</td>
                                <td>{{ json_decode($p->product) }}</td>
                                <td style="text-align: right;">
                                    
                                    @if($p->price == 0)
                                        取得中
                                    @else
                                        <span data-bs-toggle="tooltip" title="登録価格" id="qqq" class="badge bg-light-secondary">¥{{number_format($p->r_price)}}</span>
                                        @if($p->r_price < $p->price)
                                            <br /><span data-bs-toggle="tooltip" title="現在価格" style="text-align: right;" class="badge bg-light-success"><i class="bi bi-arrow-up"></i> ¥{{number_format($p->price)}}</span>
                                        @elseif ($p->r_price == $p->price)
                                            <br /><span data-bs-toggle="tooltip" title="現在価格" style="text-align: right;" class="badge bg-light-info">¥{{number_format($p->price)}}</span>
                                        @else
                                            <br /><span data-bs-toggle="tooltip" title="現在価格" style="text-align: right;" class="badge bg-light-danger"><i class="bi bi-arrow-down"></i> ¥{{number_format($p->price)}}</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary rounded-pill dropdown-toggle me-1" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            詳細
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                            <a class="dropdown-item _info product_detail" style="cursor:pointer" href="{{url('base_product_info/' . $p->id )}}">もっと見る</a>
                                            <!-- <button class="dropdown-item _edit product_detail" id="edit_{{$p->id}}">編集</button> -->
                                            <button class="dropdown-item product_del" style="cursor:pointer" id="del_{{$p->id}}" onclick="delete_product({{$p->id}})">削除</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($products)) {{ $products->onEachSide(1)->links('mypage.pagination') }} @endif
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);

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

            var xlRowObjArr = [];
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
            if (xlRowObjArr.length == 0) {
                alert('xlsx ファイルにデータがありません。');
                xlRowObjArr = [];
            } else if (xlRowObjArr[0]['Prime Eligible (Buy Box)'] == undefined && xlRowObjArr[0]['カテゴリ: Tree'] == undefined) {
                alert('選択した xlsx ファイル形式が正しくありません。');
                xlRowObjArr = [];
            } else {
                $('#new_csv').on('click', function() {
                    Toastify({
                        text: "多くのデータを保存するのに少し時間がかかることがあります。 ",
                        duration: 3500,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
                        $("#loader-4").show(); //makes page more lightweight 
                    });
                    $('.progress_loader').css('display', 'block');

                    var start_num = 0;
                    //
                    const createInterval = setInterval(() => {
                        if (start_num < xlRowObjArr.length) {
                            const create_func = amazon_send(xlRowObjArr.slice(start_num, (start_num + 1000)), (start_num == 0) ? 'new' : 'update', (start_num + 1000 >= xlRowObjArr.length) ? 'end' : 'start');
                            start_num += 1000;
                            if (create_func == 'success') {
                                if (start_num > 0) {
                                    var progress_len = (start_num / xlRowObjArr.length) * 100;
                                    $('#progress').val(progress_len);
                                }
                            }
                        } else {
                            clearInterval(createInterval);
                            start_num = 0;
                        }
                    }, 23000);
                });
                
                $('#update_csv').on('click', function() {
                    Toastify({
                        text: "データを保存するのに少し時間が必要です。",
                        duration: 3500,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
                        $("#loader-4").show(); //makes page more lightweight 
                    });
                    $('.progress_loader').css('display', 'block');

                    var update_num = 0;
                    //dely 20S
                    const updateInterval = setInterval(() => {
                        if (update_num < xlRowObjArr.length) {
                            const each_func = amazon_send(update_num, xlRowObjArr.slice(update_num, (update_num + 1000)), 'update', (update_num + 1000 >= xlRowObjArr.length) ? 'end' : 'start');
                            update_num += 1000;
                            if (each_func == 'success') {
                                if (update_num > 0) {
                                    var progress_len = (update_num / xlRowObjArr.length) * 100;
                                    $('#progress').val(progress_len);
                                }
                            }
                        } else {
                            clearInterval(updateInterval);
                            update_num = 0;
                        }
                    }, 20000);
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
            url: '{{ route("remove_product") }}',
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
            url: "{{ route('remove_data') }}",
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

    function amazon_send(update_num, parameter, condition, finish) {
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
                if(res != "success"){
                    if (finish == 'end') {
                        alert(update_num + "から" + xlRowObjArr.length + "番までのデータ登録に失敗しました。");
                    }else{
                        alert(update_num + "から" + 1000 + "番までのデータ登録に失敗しました。");
                    }
                }
                if (finish == 'end') {
                    Toastify({
                        text: "データが正常に保存されました。",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                    setTimeout(refresh_page, 4000);
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
                //         if(finish == "end"){
                //             Toastify({
                //                 text: "データが正常に保存されました。",
                //                 duration: 2000,
                //                 close: true,
                //                 gravity: "top",
                //                 position: "right",
                //                 backgroundColor: "#4fbe87",
                //             }).showToast();
                //         }
                //     },
                // });
            }
        });
        return 'success';
    }
</script>
@endpush