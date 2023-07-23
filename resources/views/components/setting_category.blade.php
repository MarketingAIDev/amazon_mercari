<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css')}}">
<!-- start file choose css -->
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css')}}">
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
                        <h3>カテゴリ</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <button type="button" class="mx-3 col-2 btn btn-outline-primary block float-start float-lg-end m-2" id="" data-bs-toggle="modal" data-bs-target="#backdrop_2"><i class="bi bi-plus-circle"></i> カテゴリを追加</button>
                            <button type="button" class="mx-3 col-2 btn btn-outline-primary block float-start float-lg-end m-2" data-bs-toggle="modal" data-bs-target="#category_model"><i class="bi bi-plus-circle"></i> XLSX</button>
                            <button class="col-1 btn btn-danger btn-icon float-lg-end m-2" type="button" onclick="allCategoryRemove()"><i class="bi bi-trash"></i></button>
                            <!-- <button class="mx-3 col-2 btn btn btn-outline-primary block float-start float-lg-end m-2" id="add_category">カテゴリを追加</button> -->
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>A - カテゴリ: Root</th>
                                            <th>A - カテゴリ: Sub</th>
                                            <th>メルカリカテゴリー</th>
                                            <th>編集</th>
                                            <th>削除</th>
                                        </tr>
                                    </thead>
                                    <tbody id="category">
                                        @foreach($category_get as $c)
                                        <tr id="category_{{$c->id}}">
                                            <td class="td_input" style="width:25%">{{$c->a_c_root}}</td>
                                            <td class="td_input" style="width:35%">{{$c->a_c_sub}}</td>
                                            <td class="td_input" style="width:30%">{{$c->m_category}}</td>
                                            <td style="width:5%"><a class="edit" data-category="{{$c}}" data-bs-toggle="modal" style="cursor:pointer" data-bs-target="#backdrop_2" id="{{$c->id}}"><i class="bi bi-pencil-square"></i></a></td>
                                            <td style="width:5%"><a id="{{$c->id}}" class="del"><i class="bi bi-trash" style="color:red;cursor:pointer"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($category_get)) {{ $category_get->onEachSide(1)->links('mypage.pagination') }} @endif
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
    <div class="modal fade text-left" id="backdrop_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="myModalLabel4">カテゴリーの保存</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control my-2" id="a_c_root" placeholder="カテゴリ: Root" />
                    <input type="text" class="form-control my-2" id="a_c_sub" placeholder="カテゴリ: Sub" />
                    <input type="text" class="form-control my-2" id="m_category" placeholder="メルカリカテゴリー" />
                    <!-- <input type="text" class="form-control my-2" id="m_category_name" placeholder="メルカリカテゴリー" />
                    <input type="text" class="form-control my-2" id="category_id" placeholder="メルカリカテゴリID" /> -->
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="col-12 col-lg-3 btn btn btn-outline-primary" data-bs-dismiss="modal" id="save_category">
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
<!-- <script src="assets/js/pages/sweetalert2.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script>
    var update_num = 0;
    var xlRowObjArr = [];
    var count_ajax= 1000;
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
    const refresh_page = () => {
        location.href = "{{ route('setting_category') }}";
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
            workbook.SheetNames.forEach(function(sheetName) {
                xlRowObjArr = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            });
            console.log(xlRowObjArr);
            if (xlRowObjArr[0]['カテゴリ: Root'] === undefined && xlRowObjArr[0]['メルカリカテゴリー'] === undefined) {
                alert('選択した xlsx ファイル形式が正しくありません。');
            } else {
                
                
                $('#create_category_csv').on('click', function() {

                    $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
                        $("#loader-4").show(); //makes page more lightweight 
                    });
                    $('.progress_loader').css('display', 'block');
                    category_send( xlRowObjArr.slice(update_num, (update_num + count_ajax)), (update_num + count_ajax >= xlRowObjArr.length) ? 'end' : 'start');
                    // let postData = {
                    //     user_id: '{{Auth::id()}}',
                    //     xlsx: xlRowObjArr
                    // }
                    // $.ajax({
                    //     url: "{{ route('create_matching_categories') }}",
                    //     type: 'post',
                    //     headers: {
                    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //     },
                    //     data: {
                    //         xlsxCategory: JSON.stringify(xlRowObjArr)
                    //     },
                    //     beforeSend: function() {
                    //         Toastify({
                    //             text: "しばらくお待ちください。",
                    //             duration: 2500,
                    //             close: true,
                    //             gravity: "top",
                    //             position: "right",
                    //             backgroundColor: "#56b6f7",
                    //         }).showToast();
                    //     },
                    //     success: function(res) {
                    //         Toastify({
                    //             text: "データが正常に保存されました。",
                    //             duration: 2500,
                    //             close: true,
                    //             gravity: "top",
                    //             position: "right",
                    //             backgroundColor: "#4fbe87",
                    //         }).showToast();
                    //         setTimeout(refresh_page, 1000);
                    //     }
                    // });
                });
            }
        };

        reader.onerror = function(ex) {
            console.log(ex);
        };

        reader.readAsBinaryString(file);
    };

    function category_send( xlsxData, finish) {
        $.ajax({
            url: "{{ route('create_matching_categories') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                xlsxCategory: JSON.stringify(xlsxData)
            },
            // beforeSend: function() {
            //     Toastify({
            //         text: "しばらくお待ちください。",
            //         duration: 2500,
            //         close: true,
            //         gravity: "top",
            //         position: "right",
            //         backgroundColor: "#56b6f7",
            //     }).showToast();
            // },
            success: function(res) {
            if (update_num < xlRowObjArr.length) {
                update_num += count_ajax;
                category_send(xlRowObjArr.slice(update_num, (update_num + count_ajax)), (update_num + count_ajax >= xlRowObjArr.length) ? 'end' : 'start');
                if (update_num > 0) {
                    var progress_len = (update_num / xlRowObjArr.length) * 100;
                    $('#progress').val(progress_len);
                }

            }
            else {
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
                    location.href = "{{ route('setting_category') }}";
                }, 3000);

            }
            }

        });
    }

    function allCategoryRemove() {
        if (!window.confirm('データを本当に削除しますか？')) {
            return;
        }
        $.ajax({
            url: "{{ route('all_category_remove') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {

            },
            success: function(res) {
                Toastify({
                    text: "すべてのデータが削除されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(refresh_page, 1500);
            }
        });
    }

    function sleep(ms) {
        return new Promise((resolve) => {
            setTimeout(resolve, ms);
        });
    }
    $('#backdrop_2').on('shown.bs.modal', function(e) {
        if (e.relatedTarget.dataset.category !== undefined) {
            let categoryData = JSON.parse(e.relatedTarget.dataset.category);
            $('#a_c_root').val(categoryData.a_c_root);
            $('#a_c_sub').val(categoryData.a_c_sub);
            $('#m_category').val(categoryData.m_category);
            $('#m_category_name').val(categoryData.m_category_name);
            $('#category_id').val(categoryData.category_id);
        } else {
            $('#a_c_root').val('');
            $('#a_c_sub').val('');
            $('#m_category').val('');
            $('#m_category_name').val('');
            $('#category_id').val('');
        }
    }).on('hidden.bs.modal', function(e) {
        $('#a_c_root').val('');
        $('#a_c_sub').val('');
        $('#m_category').val('');
        $('#m_category_name').val('');
        $('#category_id').val('');
    });

    var id = 0;
    $('.edit').on('click', function() {
        id = $(this).attr('id');
    });
    $('#save_category').on('click', function() {

        let postData = {
            id: id,
            a_c_root: $('#a_c_root').val(),
            a_c_sub: $('#a_c_sub').val(),
            m_category: $('#m_category').val(),
            // m_category_name: $('#m_category_name').val(),
            // category_id : $('#category_id').val()
        };
        if (id > 0) {
            console.log(postData)
            $.ajax({
                url: "{{ route('update_category') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    exData: JSON.stringify(postData)
                },
                success: function() {
                    Toastify({
                        text: "データが正常に保存されました。",
                        duration: 2500,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                    id = 0;
                    setTimeout(() => {
                        location.href = window.location.href;
                    }, 1000);
                }
            });
        } else {
            $.ajax({
                url: "{{ route('save_category') }}",
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

    $('.del').on('click', function() {
        if (!window.confirm('データを本当に削除しますか？')) {
            return;
        }
        let id = $(this).attr('id')
        $.ajax({
            url: "{{ route('del_category') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            },
            success: function() {
                Toastify({
                    text: "データが正常に削除されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
                $('#category_' + id).remove();
                setTimeout(() => {
                    location.href = window.location.href;
                }, 1000);
            }
        });
    });
</script>

@endpush