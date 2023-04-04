<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="assets/extensions/choices.js/public/assets/styles/choices.css">
<!-- start file choose css -->
<link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
<link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
<link rel="stylesheet" href="assets/extensions/toastify-js/src/toastify.css">
<link rel="stylesheet" href="assets/css/pages/filepond.css">
<link rel="stylesheet" href="assets/extensions/choices.js/public/assets/styles/choices.css">
<link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
<link rel="stylesheet" href="assets/css/pages/simple-datatables.css">
<!-- end file css -->
@endsection
<!-- end additional css -->
<!-- start this page -->
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-12 m-2 order-md-1">
                <!-- <h3>出品データファイルの設定</h3> -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry_setting_ng")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('entry_setting')}}">基本設定</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry_setting_category")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_category')}}">カテゴリ</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry_setting_id")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_category_id')}}">カテゴリーマスター</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry_setting_price")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_price')}}">利益設定表</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry_setting_postage")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_postage')}}">送料設定表</a>
                    </li> -->
                    <li class="nav-item">
                        <a style="cursor:pointer" type="button" onclick="save_exhibition()" class="btn btn-primary"><i class="bi bi-send-check-fill"></i> 出品する</a>
                    </li>
                    <a href="{{ route('entry_data') }}" class="btn btn-primary block float-lg-end mx-2"><i class="bi bi-reply"></i> 戻る</a>

                </ul>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>送料設定表</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- <button type="button" class="btn btn-outline-primary float-start" data-bs-toggle="modal" data-bs-target="#postage_model"><i class="bi bi-plus-circle"></i>配送設定</button> -->
                            <button type="button" class="mx-3 col-2 btn btn-outline-primary block float-start float-lg-end m-2" data-bs-toggle="modal" data-bs-target="#category_model"><i class="bi bi-plus-circle"></i> XLSX</button>
                            <button class="col-1 btn btn-danger btn-icon float-lg-end m-2" type="button" onclick="allCategoryRemove()"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>サイズ</th>
                                            <th>横</th>
                                            <th>縦</th>
                                            <th>高さ</th>
                                            <th>最終</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($postage_get as $post)
                                        <tr>
                                            <td>{{$post->size}}</td>
                                            <td>{{$post->width}}</td>
                                            <td>{{$post->vertical}}</td>
                                            <td>{{$post->height}}</td>
                                            <td>{{$post->final}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade text-left amazon_mercari_modal" id="category_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="myModalLabel4">登録</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body" style="overflow: hidden;">
                    <input type="file" name="xlsx" id="xlsx" class="form-control" onchange="xlReader(event);" />
                    <!-- <input type="file" class="form-control csv_event" style="cursor: pointer;" placeholder="CSVファイルを選択してください。" id="csvFileCategory" /> -->
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="col-12 col-lg-3 btn btn btn-outline-primary" data-bs-dismiss="modal" id="create_category_csv">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">追加</span>
                    </button>
                    <button type="button" class="col-12 col-lg-3 btn btn-light-secondary " data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">キャンセル</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="postage_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="myModalLabel4">登録</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body" style="overflow: hidden;">
                    <input type="file" class="form-control csv_event" style="cursor: pointer;" placeholder="CSVファイルを選択してください。" id="csvFilePostage" />
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="col-12 col-lg-3 btn btn btn-outline-primary" data-bs-dismiss="modal" id="update_postage_csv">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">追加</span>
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
@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="assets/js/pages/simple-datatables.js"></script>
<!-- start file js -->
<script>
    function save_exhibition() {
        if (!window.confirm('出品データをインポートしますか？')) {
            return;
        }

        $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
            $("#loader-4").show(); //makes page more lightweight 
        });
        $('.progress_loader').css('display', 'block');
        var progress_index = 1;
        const progress_func = setInterval(() => {
            if (progress_index < 100) {
                $('#progress').val(progress_index);
                progress_index += 1;
            } else {
                clearInterval(progress_func);
                progress_index = 1;
            }
        }, 1000 * 1);

        Toastify({
            text: "出品データが多い場合、\n長い時間がかかることがあります。",
            duration: 2100,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#4fbe87",
        }).showToast();
        $.ajax({
            // url: "http://localhost:32768/api/v1/amazon/saveExhibition",
            url: "http://xs021277.xsrv.jp/fmproxy/api/v1/amazon/saveExhibition",
            type: "post",
            data: {
                user_id: '{{ Auth::id() }}',
            },
            success: function(res) {
                console.log(res);
                Toastify({
                    text: "データが正常に保存されました。",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
                setTimeout(() => {
                    $('#progress').val(100);
                    location.href = '{{route("entry_data")}}';
                }, 1000 * 100);
            },
        });
    }
    const refresh_page = () => {
        location.href = "{{ route('entry_data') }}";
    };
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

            workbook.SheetNames.forEach(function(sheetName) {
                xlRowObjArr = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            });
            console.log(xlRowObjArr);

            $('#create_category_csv').on('click', function() {
                // let postData = {
                //     user_id : '{{Auth::id()}}',
                //     xlsx : xlRowObjArr
                // }
                // $.ajax({
                // url: "http://localhost:32768/api/v1/xlsx/saveXlsx",
                // url: "http://xs021277.xsrv.jp/fmproxy/api/v1/amazon/saveXlsx",
                //     type: "post",
                //     data: {
                //         xlsx: JSON.stringify(postData)
                //     },
                // });
                // for (let i = 0; i < xlRowObjArr.length; i += 500) {
                //     let chunk = xlRowObjArr.slice(i, i + 500);
                $.ajax({
                    url: "{{ route('postage_xlsx') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        xlsxCategory: JSON.stringify(xlRowObjArr)
                    },
                    beforeSend: function() {
                        Toastify({
                            text: "しばらくお待ちください。",
                            duration: 2500,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#56b6f7",
                        }).showToast();
                    },
                    success: function(res) {
                        Toastify({
                            text: "データが正常に保存されました。",
                            duration: 2500,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#4fbe87",
                        }).showToast();
                        setTimeout(refresh_page, 1000);
                    }
                });
                // }
            });
        };

        reader.onerror = function(ex) {
            console.log(ex);
        };

        reader.readAsBinaryString(file);
    };

    function allCategoryRemove() {
        if (!window.confirm('データを本当に削除しますか？')) {
            return;
        }
        $.ajax({
            url: "{{ route('postage_remove') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {

            },
            success: function(res) {
                setTimeout(refresh_page, 1500);
            }
        });
    }
</script>
<!-- end -->
@endpush