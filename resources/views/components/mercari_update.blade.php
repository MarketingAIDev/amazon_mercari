<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css')}}">
<style>
</style>
@endsection
<!-- end additional css -->
<!-- start this page -->
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-12 m-2 order-md-1">
                <h3>出品中商品数 : <span style="color:red; font-family:verdana">{{$mercari_updates->count()}}個</span></h3>
                <!-- <a href=" {{ route('mercari_update_allremove') }}" type="button" class="mx-4 btn btn-outline-danger block float-start float-lg-end" onclick="return window.confirm('データベースから永久に削除します。\nデータを本当に削除しますか？')" id="mercari_update_allremove">
                    <i class="bi bi-trash"></i>
                </a> -->
                <a class="mx-4 btn btn-outline-danger block float-lg-end" href="{{ route('export_mercari_update_csv_delete') }}" onclick="return window.confirm('緊急削除を進めますか？');"><i class="bi bi-download"></i> 緊急削除</a>
                <button type="button" class="mx-2 btn btn-outline-primary block float-start float-lg-end mercari_product_data_import" data-bs-toggle="modal" data-bs-target="#mercari_import_csv">
                    <i class="bi bi-filetype-csv"></i> CSVのアップロード
                </button>
                <button type="button" class="btn btn-outline-primary block float-start float-lg-end" data-bs-toggle="modal" data-bs-target="#mercari_setting"><i class="bi bi-tools"></i> 設定</button>
                <a class="mx-2 btn btn-outline-success block float-lg-end" href="{{ route('mercari_update_all') }}"><i class="bi bi-card-checklist"></i> 全て見る</a>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header p-2">
            </div>
            <div class="card-body ">
                @if ($errors->first('info'))
                <div class="alert alert-info alert-dismissible fade show update_csv_upload_success" role="alert">
                    <span class="alert-text text-white "> {{$errors->first('info')}} </span>
                    <button type=" button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif ($errors->first('info'))
                @if ($errors->first('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white"> {{$errors->first('error')}} </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif ($errors->first('error'))
                <div class="table-responsive">
                    <table class="table table-striped mb-0" style="text-align: center;">
                        <tbody id="mercari_update_list">
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade text-left amazon_mercari_modal" id="mercari_import_csv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form action="{{ route('update_mercari_import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="myModalLabel4">アップロード</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body" style="overflow: hidden;">
                    <input type="file" name="file[]" id="file" class="form-control csv_import_input" multiple require />
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="col-12 col-lg-6 btn btn btn-outline-primary csv_import_button" data-bs-dismiss="modal" id="mercari_update_csv">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">アップロード</span>
                    </button>
                    <button type="button" class="col-12 col-lg-6 btn btn-light-secondary " data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">キャンセル</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade text-left amazon_mercari_modal" id="mercari_setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="myModalLabel4">一括設定</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body" style="overflow: hidden;">
                <strong class="mx-2">発送元の地域</strong>
                <div class="input-group mb-3">
                    <select class="form-select" id="region_origin">
                        <option value="jp01">北海道</option>
                        <option value="jp02">青森県</option>
                        <option value="jp03">岩手県</option>
                        <option value="jp04">宮城県</option>
                        <option value="jp05">秋田県</option>
                        <option value="jp06">山形県</option>
                        <option value="jp07">福島県</option>
                        <option value="jp08">茨城県</option>
                        <option value="jp09">栃木県</option>
                        <option value="jp10">群馬県</option>
                        <option value="jp11">埼玉県</option>
                        <option value="jp12">千葉県</option>
                        <option value="jp13">東京都</option>
                        <option value="jp14">神奈川県</option>
                        <option value="jp15">新潟県</option>
                        <option value="jp16">富山県</option>
                        <option value="jp17">石川県</option>
                        <option value="jp18">福井県</option>
                        <option value="jp19">山梨県</option>
                        <option value="jp20">長野県</option>
                        <option value="jp21">岐阜県</option>
                        <option value="jp22">静岡県</option>
                        <option value="jp23">愛知県</option>
                        <option value="jp24">三重県</option>
                        <option value="jp25">滋賀県</option>
                        <option value="jp26">京都府</option>
                        <option value="jp27">大阪府</option>
                        <option value="jp28">兵庫県</option>
                        <option value="jp29">奈良県</option>
                        <option value="jp30">和歌山県</option>
                        <option value="jp31">鳥取県</option>
                        <option value="jp32">島根県</option>
                        <option value="jp33">岡山県</option>
                        <option value="jp34">広島県</option>
                        <option value="jp35">山口県</option>
                        <option value="jp36">徳島県</option>
                        <option value="jp37">香川県</option>
                        <option value="jp38">愛媛県</option>
                        <option value="jp39">高知県</option>
                        <option value="jp40">福岡県</option>
                        <option value="jp41">佐賀県</option>
                        <option value="jp42">長崎県</option>
                        <option value="jp43">熊本県</option>
                        <option value="jp44">大分県</option>
                        <option value="jp45">宮崎県</option>
                        <option value="jp46">鹿児島県</option>
                    </select>
                </div>
                <strong class="mx-2">発送までの日数</strong>
                <div class="input-group mb-3">
                    <select class="form-select" id="day_ship">
                        <option value="1">1~2日で発送</option>
                        <option value="2">2~3日で発送</option>
                        <option value="3">4~7日で発送</option>
                        <option value="4">8日以上または未定</option>
                    </select>
                </div>
                <strong class="mx-2">商品ステータス</strong>
                <div class="input-group mb-3">
                    <select class="form-select" id="product_status">
                        <option value="2">公開</option>
                        <option value="1">非公開</option>
                        <option value="3">削除</option>
                    </select>
                </div>
                <strong class="mx-2">再出品日の設定</strong>
                <div class="input-group mb-3">
                    <input type="number" class="form-control" id="re_entry" value="14">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="col-12 col-lg-3 btn btn btn-outline-primary" data-bs-dismiss="modal" id="update_mercari_setting">
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
@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<!-- start file js -->
<script src="{{ asset('assets/extensions/filepond/filepond.js')}}"></script>
<script src="{{ asset('assets/extensions/toastify-js/src/toastify.js')}}"></script>
<script src="{{ asset('assets/js/pages/filepond.js')}}"></script>
<script>
    $('#re_entry').on('change', function() {
        console.log($('#re_entry').val())
    })

    var mercari_update_data = <?php echo $mercari_updates; ?>;
    var rangeHtml = '';
    if (mercari_update_data.length == 0) {
        rangeHtml += `<tr>
                            <td colspan="5" style="text-align: center;">データがありません。</td>
                        </tr>`;
    }
    let len = mercari_update_data.length + 1;
    let list_condition = mercari_update_data.length % 500;
    for (let i = 1; i < len; i++) {
        if (i % 500 == 0) {
            rangeHtml +=
                `<tr>
                    <td>` + (i - 499) + `~` + i + `</td>
                    <td>
                    <a class="btn btn-outline-primary block mx-1 float-lg-end export_mercari_update_csv" href="/export_mercari_update_csv/` + mercari_update_data[i - 500].id + `/` + mercari_update_data[i - 1].id + `/` + (i - 499) + `/` + i + `"><i class="bi bi-download"></i> CSV</a>
                    <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/mercari_update_list/` + mercari_update_data[i - 500].id + `/` + mercari_update_data[i - 1].id + `/` + (i - 499) + `/` + i + `"><i class="bi bi-card-checklist"></i> 見る</a>
                    </td>
                    </tr>`;
        }
        // <a class="btn btn-outline-danger block mx-1 float-lg-end" id="mercari_update_delete" onclick="return window.confirm('データを本当に削除しますか？')" href="/mercari_update_delete/` + mercari_update_data[i - 500].id + `/` + mercari_update_data[i - 1].id + `/` + (i - 499) + `/` + i + `"><i class="bi bi-trash"></i></a>
    }
    if (list_condition != 0) {
        let startId = mercari_update_data.length - (mercari_update_data.length % 500);
        let endId = mercari_update_data.length - 1
        console.log(startId);
        rangeHtml +=
            `<tr>
            <td>` + (len - mercari_update_data.length % 500) + `~` + (len - 1) + `</td>
            <td>
            <a class="btn btn-outline-primary block mx-1 float-lg-end export_mercari_update_csv" href="/export_mercari_update_csv/` + mercari_update_data[startId].id + `/` + mercari_update_data[endId].id + `/` + (len - mercari_update_data.length % 500) + `/` + (len - 1) + `"><i class="bi bi-download"></i> CSV</a>
            <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/mercari_update_list/` + mercari_update_data[startId].id + `/` + mercari_update_data[endId].id + `/` + (len - mercari_update_data.length % 500) + `/` + (len - 1) + `"><i class="bi bi-card-checklist"></i> 見る</a>
            </td>
        </tr>`;
    }

    $('#mercari_update_list').html(rangeHtml);

    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('mercari_exist_delete') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
            },
            data: {},
            success: function(res) {
                if (res == 'success') {}
            }
        })
        $.ajax({
            url: "{{ route('mercari_exist_setting') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
            },
            data: {},
            success: function(res) {
                $('#region_origin').val(res.region_origin);
                $('#day_ship').val(res.days_ship);
                $('#product_status').val(res.product_status);
                $('#re_entry').val(res.re_entry);
            }
        })
    });
    $('#update_mercari_setting').on('click', function() {
        $.ajax({
            url: '{{ route("mercari_entry_update_setting") }}',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                region_origin: $('#region_origin').val(),
                day_ship: $('#day_ship').val(),
                product_status: $('#product_status').val(),
                re_entry: $('#re_entry').val(),
                user_id: '{{Auth::user()->id}}'
            },
            success: function(res) {
                Toastify({
                    text: "設定が保存されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
                setTimeout(() => {
                    location.href = window.location.href;
                }, 2000);
            }
        });
    });
</script>
<!-- end 
    -->
@endpush
<!-- end additional scripts -->