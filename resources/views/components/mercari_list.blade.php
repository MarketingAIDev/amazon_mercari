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
                <h3>未出品商品数 : <span style="color:red; font-family:verdana">{{$exhibition_data->count()}}個</span></h3>
            </div>
        </div>
    </div>
    <section class="section">
        <!-- <div class="alert alert-light-info color-info">
            <span>まず、Pythonアプリケーションを実行します。</span>
            <p>あと、画像をダウンロードし、csvファイルをダウンロードします。</p>
        </div> -->
        <div class="card">
            <div class="card-header p-2">
                <button type="button" class="mx-4 btn btn-outline-primary block float-start float-lg-end" data-bs-toggle="modal" data-bs-target="#mercari_setting"><i class="bi bi-tools"></i> 設定</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" style="text-align: center;">
                        <tbody id="mercari_list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade text-left amazon_mercari_modal" id="mercari_setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="myModalLabel4">設定</h4>
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
                        <option value="1">非公開</option>
                        <option value="2">公開</option>
                    </select>
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
    var all_exhibition_data = <?php echo $exhibition_data; ?>;
    var rangeHtml = '';
    let len = all_exhibition_data.length + 1;
    for (let i = 1; i < len; i++) {
        if (i % 1000 == 0) {
            rangeHtml += `<tr>
                            <td>` + (i - 999) + `~` + i + `</td>
                            <td>
                                <a href="/downloadIMG/` + all_exhibition_data[i - 1000].id + `/` + all_exhibition_data[i - 1].id + `/` + (i - 999) + `/` + i + `" type="button" class="btn btn-outline-primary block mx-1 float-lg-end download_image_zip"><i class="bi bi-download"></i> IMG</a>
                                <a class="btn btn-outline-primary block mx-1 float-lg-end download_new_csv" href="/export_mercari_csv/` + all_exhibition_data[i - 1000].id + `/` + all_exhibition_data[i - 1].id + `/` + (i - 999) + `/` + i + `"><i class="bi bi-download"></i> csv作成</a>
                                <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/mercari_list_products/` + all_exhibition_data[i - 1000].id + `/` + all_exhibition_data[i - 1].id + `/` + (i - 999) + `/` + i + `"><i class="bi bi-card-checklist"></i> リスト</a>
                            </td>
                        </tr>`;
        }
    }
    if (all_exhibition_data.length % 1000 != 0) {
        rangeHtml += `<tr>
                    <td>` + (len - all_exhibition_data.length % 1000) + `~` + (len - 1) + `</td>
                    <td>
                        <a href="/downloadIMG/` + all_exhibition_data[all_exhibition_data.length - all_exhibition_data.length % 1000].id + `/` + all_exhibition_data[all_exhibition_data.length - 1].id + `/` + (len - all_exhibition_data.length % 1000) + `/` + (len - 1) + `" type="button" class="btn btn-outline-primary block mx-1 float-lg-end download_image_zip"><i class="bi bi-download"></i> IMG</a>
                        <a class="btn btn-outline-primary block mx-1 float-lg-end download_new_csv" href="/export_mercari_csv/` + all_exhibition_data[all_exhibition_data.length - all_exhibition_data.length % 1000].id + `/` + all_exhibition_data[all_exhibition_data.length - 1].id + `/` + (len - all_exhibition_data.length % 1000) + `/` + (len - 1) + `"><i class="bi bi-download"></i> csv作成</a>
                        <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/mercari_list_products/` + all_exhibition_data[all_exhibition_data.length - all_exhibition_data.length % 1000].id + `/` + all_exhibition_data[all_exhibition_data.length - 1].id + `/` + (len - all_exhibition_data.length % 1000) + `/` + (len - 1) + `"><i class="bi bi-card-checklist"></i> リスト</a>
                    </td>
                </tr>`;
    }
    $('#mercari_list').html(rangeHtml);

    $(document).ready(function() {
        $.ajax({
            url: "{{ route('mercari_product_setting_exist') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
            },
            data: {},
            success: function(res) {
                $('#region_origin').val(res.region_origin);
                $('#day_ship').val(res.day_ship);
                $('#product_status').val(res.product_status);
            }
        })
    });
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);

    $('#update_mercari_setting').on('click', function() {
        $.ajax({
            url: '{{ route("mercari_update_setting") }}',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                region_origin: $('#region_origin').val(),
                day_ship: $('#day_ship').val(),
                product_status: $('#product_status').val(),
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
    $('#export_entry_data').on('click', function() {
        $.ajax({
            url: "{{route('export_mercari')}}",
            type: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {},
            beforeSend: function(data) {},
            success: function(res) {}
        });
    });
</script>
<!-- end -->
@endpush
<!-- end additional scripts -->