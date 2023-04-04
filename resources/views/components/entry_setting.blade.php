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
                        <h3>NG 設定 , Prime</h3>
                    </div>
                    <div class="card-body">
                        <div class="row my-3">
                            <div class="col-md-6 mb-4">
                                <h6>NGカテゴリー選別</h6>
                                <p>Amazonカテゴリーでリストにあるカテゴリーの商品は出品不可商品シートへ。</p>
                                <div class="form-group">
                                    <textarea class="form-control" id="ngCategory" rows="3" style="height: 100px;"><?php foreach ($Ngcategories as $c) {
                                                                                                                        echo $c['category'] . ',';
                                                                                                                    } ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6>NGワード商品選別</h6>
                                <p>商品名や説明文内にリストにあるワードが含まれる商品は出品不可商品シートへ。</p>
                                <div class="form-group">
                                    <textarea class="form-control" id="ngProduct" rows="3" style="height: 100px;"><?php foreach ($Ngproducts as $p) {
                                                                                                                        echo $p['product'] . ',';
                                                                                                                    } ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-md-6 mb-4">
                                <h6>NG単語を選択</h6>
                                <p>リスト内の単語は商品名と説明文から削除します。</p>
                                <div class="form-group">
                                    <textarea class="form-control" id="ngword" rows="3" style="height: 100px;"><?php foreach ($Ngwords as $w) {
                                                                                                                    echo $w['word'] . ',';
                                                                                                                } ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 form-check form-switch">
                                <input class="form-check-input mx-1 bs-cyan" onclick="settingPorductMark()" type="checkbox" id="prime" @if ($setting[0]->prime == 1) checked
                                @endif />
                                <label class="form-check-label" for="prime">
                                    <h6>Prime選別</h6>
                                </label><br>
                                <input class="form-check-input  mx-1 bs-cyan" onclick="settingPorductMark()" type="checkbox" id="mark" @if ($setting[0]->mark == 1) checked
                                @endif />
                                <label class="form-check-label" for="mark">
                                    <h6>★マーク設定（商品名）★</h6>
                                </label>
                                <p class="mb-1">定型文を入力してください。</p>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="定型文を入力してください。" id="sentence" rows="2" style="max-height: 100px;">{{$setting[0]->sentence}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<script>
    function save_exhibition() {
        if (!window.confirm('出品データをインポートしますか？')) {
            return;
        }

        $("#loader-4").fadeIn(50, function() { // fadeOut complete. Remove the loadingSpinner
            $("#loader-4").show(); //makes page more lightweight 
        });
        $('.progress_loader').css('display', 'block');
        var progress_index = 2;
        const progress_func = setInterval(() => {
            if (progress_index < 100) {
                $('#progress').val(progress_index);
                progress_index += 1;
            } else {
                clearInterval(progress_func);
                progress_index = 1;
            }
        }, 1000 * 7);

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
                }, 1000 * 60 * 15);
            },
        });
    }
    const refresh_page = () => {
        location.href = "{{ route('entry_data') }}";
    };

    function settingPorductMark() {
        let postData = {
            mark: $('#mark')[0].checked,
            prime: $('#prime')[0].checked,
        }
        console.log(postData);
        $.ajax({
            url: "{{ route('change_setting') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: postData,
            success: function() {
                Toastify({
                    text: "設定が更新されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
        })
    }

    $("#sentence").focusout(function() {
        var ngcategorys = $('#ngCategory').val().split(',');
        console.log(ngcategorys);
        $.ajax({
            url: "{{ route('change_setting_sentence') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                sentence: $('#sentence').val()
            },
            success: function() {
                Toastify({
                    text: "データが正常に更新されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
        });
    });
    // NgTextarea script
    $("#ngCategory").focusout(function() {
        var ngcategorys = $('#ngCategory').val().split(',');
        console.log(ngcategorys);
        $.ajax({
            url: "{{ route('change_ng_categories') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: '{{ Auth::user()->id }}',
                ng: ngcategorys
            },
            success: function() {
                Toastify({
                    text: "データが正常に更新されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
        });
    });

    $("#ngProduct").focusout(function() {
        var ngProducts = $('#ngProduct').val().split(',');
        console.log(ngProducts);
        $.ajax({
            url: "{{ route('change_ng_product') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: '{{ Auth::user()->id }}',
                ng: ngProducts
            },
            success: function() {
                Toastify({
                    text: "データが正常に更新されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
        });
    });

    $("#ngword").focusout(function() {
        var ngwords = $('#ngword').val().split(',');
        console.log(ngwords);
        $.ajax({
            url: "{{ route('change_ng_word') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: '{{ Auth::user()->id }}',
                ng: ngwords
            },
            success: function() {
                Toastify({
                    text: "データが正常に更新されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
        });
    });
</script>
<!-- end -->
@endpush