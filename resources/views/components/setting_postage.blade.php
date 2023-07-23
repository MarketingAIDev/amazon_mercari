<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css')}}">
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
                        <a <?php if (strpos(url()->current(), "entry/setting_ng")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('entry_setting')}}">基本設定</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry/setting_category")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_category')}}">カテゴリ</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry/setting_id")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_category_id')}}">カテゴリーマスター</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry/setting_price")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_price')}}">利益設定表</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry/setting_postage")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('setting_postage')}}">送料</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "entry/data")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('entry_data')}}">出品リスト</a>
                    </li>

                    <a style="cursor:pointer" type="button" onclick="save_exhibition()" class="btn btn-primary"><i class="bi bi-send-check-fill"></i> メルカリ用データに変換する</a>

                </ul>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>送料</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- <button type="button" class="btn btn-outline-primary float-start" data-bs-toggle="modal" data-bs-target="#postage_model"><i class="bi bi-plus-circle"></i>配送設定</button> -->
                            <!-- <button type="button" class="mx-3 col-2 btn btn-outline-primary block float-start float-lg-end m-2" data-bs-toggle="modal" data-bs-target="#category_model"><i class="bi bi-plus-circle"></i> XLSX</button> -->
                            <button type="button" class="mx-3 col-2 btn btn-outline-primary block float-start float-lg-end m-2" data-bs-toggle="modal" data-bs-target="#backdrop_2"><i class="bi bi-plus-circle"></i> 追加</button>
                            <button class="col-1 btn btn-danger btn-icon float-lg-end m-2" type="button" onclick="allCategoryRemove()"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>サイズ</th>
                                            <th>縦</th>
                                            <th>横</th>
                                            <th>高さ</th>
                                            <th>最終</th>
                                            <th>編集</th>
                                            <th>削除</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($postage_get as $post)
                                        <tr>
                                            <td>{{$post->size}}</td>
                                            <td>{{$post->length}}</td>
                                            <td>{{$post->width}}</td>
                                            <td>{{$post->height}}</td>
                                            <td>{{$post->final}}</td>
                                            <td><a class="edit" data-postage="{{$post}}" data-bs-toggle="modal" style="cursor:pointer" data-bs-target="#backdrop_2" id="{{$post->id}}"><i class="bi bi-pencil-square"></i></a></td>
                                            <td><a id="{{$post->id}}" href="./postage/del/{{$post->id}}" onclick="return window.confirm('データを本当に削除しますか？' ) " class="del"><i class="bi bi-trash" style="color:red;cursor:pointer"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($postage_get)) {{ $postage_get->onEachSide(1)->links('mypage.pagination') }} @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    <div class="modal fade text-left" id="backdrop_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="myModalLabel4">送料設定</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control my-2" id="size" placeholder="サイズ	" />
                    <input type="number" class="form-control my-2" id="length" placeholder="横" />
                    <input type="number" class="form-control my-2" id="width" placeholder="縦" />
                    <input type="number" class="form-control my-2" id="height" placeholder="高さ	" />
                    <input type="number" class="form-control my-2" id="final" placeholder="最終" />
                    <!-- <input type="text" class="form-control my-2" id="m_category_name" placeholder="メルカリカテゴリー" />
                    <input type="text" class="form-control my-2" id="category_id" placeholder="メルカリカテゴリID" /> -->
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="col-12 col-lg-3 btn btn btn-outline-primary" data-bs-dismiss="modal" id="save_postage">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">保存</span>
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
<script>
    $('#backdrop_2').on('shown.bs.modal', function(e) {
        if (e.relatedTarget.dataset.postage !== undefined) {
            let postageData = JSON.parse(e.relatedTarget.dataset.postage);
            $('#size').val(postageData.size);
            $('#length').val(postageData.length);
            $('#width').val(postageData.width);
            $('#height').val(postageData.height);
            $('#final').val(postageData.final);
        } else {
            $('#size').val('');
            $('#length').val('');
            $('#width').val('');
            $('#hegiht').val('');
            $('#final').val('');
        }
    }).on('hidden.bs.modal', function(e) {
        $('#size').val('');
        $('#length').val('');
        $('#width').val('');
        $('#hegiht').val('');
        $('#final').val('');
    });
    var id = 0;
    $('.edit').on('click', function() {
        id = $(this).attr('id');
    });

    $('#save_postage').on('click', function() {
        let postData = {
            id: id,
            size: $('#size').val(),
            length: $('#length').val(),
            width: $('#width').val(),
            height: $('#height').val(),
            final: $('#final').val(),
        }
        if (postData.size == '' || postData.width == '' || postData.length == '' || postData.height == '' || postData.final == '') {
            alert('データが正しくありません。');
        } else {
            $.ajax({
                url: "{{ route('save_postage') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    exData: JSON.stringify(postData)
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
                    setTimeout(() => {
                        location.href = window.location.href;
                    }, 1000);
                }
            });
        }
    });

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
            if (progress_index < 96) {
                $('#progress').val(progress_index);
                progress_index += 1;
            } else {
                clearInterval(progress_func);
                progress_index = 1;
            }
        }, 650 * 1);

        $.ajax({
            // url: "http://localhost:32768/api/v1/amazon/saveExhibition",
            url: "https://xs021277.xsrv.jp/fmproxy/api/v1/amazon/saveExhibition",
            type: "post",
            data: {
                user_id: '{{ Auth::id() }}',
            },
            success: function(res) {
                let r_count = res.msg;
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
                    clearInterval(progress_func);
                    $('#progress').val(100);
                    sleep(1000 * 3).then(() => {
                        location.href = '{{route("entry_data")}}';
                    })
                }, 30 * r_count);
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
                    location.href = '{{ route("entry_setting") }}';
                }, 1000 * 3);
            }
        });
    }
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
                        setTimeout(() => {
                            location.href = 'route("setting_postage")'
                        }, 2000);
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

    function sleep(ms) {
        return new Promise((resolve) => {
            setTimeout(resolve, ms);
        });
    }

    function allCategoryRemove() {
        if (!window.confirm('すべてのデータを本当に削除しますか？')) {
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
                Toastify({
                    text: "データが正常に保存されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
                setTimeout(refresh_page, 2000);
            }
        });
    }
</script>
<!-- end -->
@endpush