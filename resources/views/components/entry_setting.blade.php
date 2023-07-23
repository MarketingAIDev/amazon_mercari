<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
<!-- start file choose css -->
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/simple-datatables.css')}}">
<style>
    strong {
        font-size: 1.3rem;
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
                        <h3>NG 設定 , Prime</h3>
                    </div>
                    <div class="card-body">
                        <div class="row my-3">
                            <div class="col-md-6 mb-4">
                                <strong>NGカテゴリー選別</strong><span>（文字のみ可能です。）</span>
                                <div class="form-group">
                                    <textarea class="form-control" id="ngCategory" rows="5" style="height: 100px;" placeholder="Amazonカテゴリーでリストにあるカテゴリーの商品は出品不可商品シートへ。"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <strong>出品不可商品用NGワード</strong><span>（文字のみ可能です。）</span>
                                <div class="form-group">
                                    <textarea class="form-control" id="ngProduct" rows="5" placeholder="商品名や説明文内にリストにあるワードが含まれる商品は出品不可商品シートへ。" style="height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-md-6 mb-4">
                                <strong>テキスト削除用ワード</strong>
                                <div class="form-group">
                                    <textarea class="form-control" id="ngword" rows="5" style="height: 100px;" placeholder="リスト内の単語は商品名と説明文から削除します。"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <strong>定型文の入力</strong>
                                <div class="form-group">
                                    <textarea class="form-control" id="sentence" rows="5" style="height: 100px;" placeholder="定型文を入力します。"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-md-4 mb-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">その他の費用</span>
                                    </div>
                                    <input type="number" aria-label="" class="form-control" placeholder="" value="{{$setting[0]->etc}}" onchange="otherPrice(event)" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">値下げ設定（日数）</span>
                                    </div>
                                    <input type="number" aria-label="" class="form-control" placeholder="" value="{{$setting[0]->price_cut_date}}" onchange="price_cut_date(event)" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">値下げ</span>
                                    </div>
                                    <input type="number" aria-label="" class="form-control" placeholder="" value="{{$setting[0]->price_reduction}}" onchange="price_reduction(event)" />
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4 form-check form-switch">
                                        <input class="form-check-input mx-1 bs-cyan" onclick="settingPorductMark()" type="checkbox" id="prime" @if ($setting[0]->prime == 1) checked
                                        @endif />
                                        <label class="form-check-label" for="prime">
                                            <h6>Prime選別</h6>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4 form-check form-switch" style="padding-left: 0px !important;">
                                        <input class="form-check-input  mx-1 bs-cyan" onclick="settingPorductMark()" type="checkbox" id="mark" @if ($setting[0]->mark == 1) checked
                                        @endif />
                                        <label class="form-check-label" for="mark">
                                            <h6>★マーク設定（商品名）★</h6>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4 form-check form-switch" style="padding-left: 0px !important;">
                                        <input class="form-check-input  mx-1 bs-cyan" onclick="settingPorductMark()" type="checkbox" id="price_cut" @if ($setting[0]->price_cut == 1) checked
                                        @endif />
                                        <label class="form-check-label" for="price_cut">
                                            <h6>値下げ設定</h6>
                                        </label>
                                    </div>
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
    window.onload = function loadTextarea() {
        // ================= ng category value. ==================
        var objCategory = <?php echo $Ngcategories ?>;
        var str = '';
        for (const o of objCategory) {
            str += o.category + '\n';
        }
        $('#ngCategory').val(str);
        // ==================== ng product value. ================
        var objProduct = <?php echo $Ngproducts ?>;
        var str = '';
        for (const o of objProduct) {
            str += o.product + '\n';
        }
        $('#ngProduct').val(str);
        // ==================== ng word value. ================
        var objWord = <?php echo $Ngwords ?>;
        var str = '';
        for (const o of objWord) {
            str += o.word + '\n';
        }
        $('#ngword').val(str);
        // ====================sentence value. ================
        var objSentence = <?php echo $setting; ?>;
        $('#sentence').val(objSentence[0].sentence);
    }

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
        }, 750 * 1);

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

    function sleep(ms) {
        return new Promise((resolve) => {
            setTimeout(resolve, ms);
        });
    }
    const otherPrice = (e) => {
        console.log(event.target.value);
        $.ajax({
            url: "{{ route('change_setting_price') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                etc: event.target.value
            },
            success: function() {}
        })
    }
    const price_cut_date = (e) => {
        console.log(event.target.value);
        $.ajax({
            url: "{{ route('change_setting_price_date') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                price_cut_date: event.target.value
            },
            success: function() {}
        })
    }
    const price_reduction = (e) => {
        console.log(event.target.value);
        $.ajax({
            url: "{{ route('change_price_reduction') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                price_reduction: event.target.value
            },
            success: function() {}
        })
    }
    const refresh_page = () => {
        location.href = "{{ route('entry_data') }}";
    };

    function settingPorductMark() {
        let postData = {
            mark: $('#mark')[0].checked,
            prime: $('#prime')[0].checked,
            price_cut: $('#price_cut')[0].checked
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
        console.log($('#sentence').val());
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
        var ngcategorys = $('#ngCategory').val().split('\n');
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
        var ngProducts = $('#ngProduct').val().split('\n');
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
        var ngwords = $('#ngword').val().split('\n');
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