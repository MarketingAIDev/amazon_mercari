<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
<link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
<link rel="stylesheet" href="assets/extensions/toastify-js/src/toastify.css">
<link rel="stylesheet" href="assets/css/pages/filepond.css">
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
                <!-- <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "mercari_list")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('mercari_list')}}">一括登録</a>
                    </li>
                    <li class="nav-item">
                        <a <?php if (strpos(url()->current(), "mercari_update")) echo 'class="nav-link active"';
                            else echo 'class="nav-link"'; ?> href="{{route('mercari_update')}}">一括更新</a>
                    </li>
                </ul> -->
            </div>
        </div>
    </div>
    <section class="section">
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
                        <option value="jp01" @if ($exhibition_data[0]['region_origin']=='jp01' ) selected @endif>北海道</option>
                        <option value="jp02" @if ($exhibition_data[0]['region_origin']=='jp02' ) selected @endif>青森県</option>
                        <option value="jp03" @if ($exhibition_data[0]['region_origin']=='jp03' ) selected @endif>岩手県</option>
                        <option value="jp04" @if ($exhibition_data[0]['region_origin']=='jp04' ) selected @endif>宮城県</option>
                        <option value="jp05" @if ($exhibition_data[0]['region_origin']=='jp05' ) selected @endif>秋田県</option>
                        <option value="jp06" @if ($exhibition_data[0]['region_origin']=='jp06' ) selected @endif>山形県</option>
                        <option value="jp07" @if ($exhibition_data[0]['region_origin']=='jp07' ) selected @endif>福島県</option>
                        <option value="jp08" @if ($exhibition_data[0]['region_origin']=='jp08' ) selected @endif>茨城県</option>
                        <option value="jp09" @if ($exhibition_data[0]['region_origin']=='jp09' ) selected @endif>栃木県</option>
                        <option value="jp10" @if ($exhibition_data[0]['region_origin']=='jp10' ) selected @endif>群馬県</option>
                        <option value="jp11" @if ($exhibition_data[0]['region_origin']=='jp11' ) selected @endif>埼玉県</option>
                        <option value="jp12" @if ($exhibition_data[0]['region_origin']=='jp12' ) selected @endif>千葉県</option>
                        <option value="jp13" @if ($exhibition_data[0]['region_origin']=='jp13' ) selected @endif>東京都</option>
                        <option value="jp14" @if ($exhibition_data[0]['region_origin']=='jp14' ) selected @endif>神奈川県</option>
                        <option value="jp15" @if ($exhibition_data[0]['region_origin']=='jp15' ) selected @endif>新潟県</option>
                        <option value="jp16" @if ($exhibition_data[0]['region_origin']=='jp16' ) selected @endif>富山県</option>
                        <option value="jp17" @if ($exhibition_data[0]['region_origin']=='jp17' ) selected @endif>石川県</option>
                        <option value="jp18" @if ($exhibition_data[0]['region_origin']=='jp18' ) selected @endif>福井県</option>
                        <option value="jp19" @if ($exhibition_data[0]['region_origin']=='jp19' ) selected @endif>山梨県</option>
                        <option value="jp20" @if ($exhibition_data[0]['region_origin']=='jp20' ) selected @endif>長野県</option>
                        <option value="jp21" @if ($exhibition_data[0]['region_origin']=='jp21' ) selected @endif>岐阜県</option>
                        <option value="jp22" @if ($exhibition_data[0]['region_origin']=='jp22' ) selected @endif>静岡県</option>
                        <option value="jp23" @if ($exhibition_data[0]['region_origin']=='jp23' ) selected @endif>愛知県</option>
                        <option value="jp24" @if ($exhibition_data[0]['region_origin']=='jp24' ) selected @endif>三重県</option>
                        <option value="jp25" @if ($exhibition_data[0]['region_origin']=='jp25' ) selected @endif>滋賀県</option>
                        <option value="jp26" @if ($exhibition_data[0]['region_origin']=='jp26' ) selected @endif>京都府</option>
                        <option value="jp27" @if ($exhibition_data[0]['region_origin']=='jp27' ) selected @endif>大阪府</option>
                        <option value="jp28" @if ($exhibition_data[0]['region_origin']=='jp28' ) selected @endif>兵庫県</option>
                        <option value="jp29" @if ($exhibition_data[0]['region_origin']=='jp29' ) selected @endif>奈良県</option>
                        <option value="jp30" @if ($exhibition_data[0]['region_origin']=='jp30' ) selected @endif>和歌山県</option>
                        <option value="jp31" @if ($exhibition_data[0]['region_origin']=='jp31' ) selected @endif>鳥取県</option>
                        <option value="jp32" @if ($exhibition_data[0]['region_origin']=='jp32' ) selected @endif>島根県</option>
                        <option value="jp33" @if ($exhibition_data[0]['region_origin']=='jp33' ) selected @endif>岡山県</option>
                        <option value="jp34" @if ($exhibition_data[0]['region_origin']=='jp34' ) selected @endif>広島県</option>
                        <option value="jp35" @if ($exhibition_data[0]['region_origin']=='jp35' ) selected @endif>山口県</option>
                        <option value="jp36" @if ($exhibition_data[0]['region_origin']=='jp36' ) selected @endif>徳島県</option>
                        <option value="jp37" @if ($exhibition_data[0]['region_origin']=='jp37' ) selected @endif>香川県</option>
                        <option value="jp38" @if ($exhibition_data[0]['region_origin']=='jp38' ) selected @endif>愛媛県</option>
                        <option value="jp39" @if ($exhibition_data[0]['region_origin']=='jp39' ) selected @endif>高知県</option>
                        <option value="jp40" @if ($exhibition_data[0]['region_origin']=='jp40' ) selected @endif>福岡県</option>
                        <option value="jp41" @if ($exhibition_data[0]['region_origin']=='jp41' ) selected @endif>佐賀県</option>
                        <option value="jp42" @if ($exhibition_data[0]['region_origin']=='jp42' ) selected @endif>長崎県</option>
                        <option value="jp43" @if ($exhibition_data[0]['region_origin']=='jp43' ) selected @endif>熊本県</option>
                        <option value="jp44" @if ($exhibition_data[0]['region_origin']=='jp44' ) selected @endif>大分県</option>
                        <option value="jp45" @if ($exhibition_data[0]['region_origin']=='jp45' ) selected @endif>宮崎県</option>
                        <option value="jp46" @if ($exhibition_data[0]['region_origin']=='jp46' ) selected @endif>鹿児島県</option>
                    </select>
                </div>
                <strong class="mx-2">発送までの日数</strong>
                <div class="input-group mb-3">
                    <select class="form-select" id="day_ship">
                        <option value="1" @if ($exhibition_data[0]['day_ship']==1) selected @endif>1~2日で発送</option>
                        <option value="2" @if ($exhibition_data[0]['day_ship']==2) selected @endif>2~3日で発送</option>
                        <option value="3" @if ($exhibition_data[0]['day_ship']==3) selected @endif>4~7日で発送</option>
                        <option value="4" @if ($exhibition_data[0]['day_ship']==4) selected @endif>8日以上または未定</option>
                    </select>
                </div>
                <strong class="mx-2">商品ステータス</strong>
                <div class="input-group mb-3">
                    <select class="form-select" id="product_status">
                        <option value="1" @if ($exhibition_data[0]['product_status']==1) selected @endif>非公開</option>
                        <option value="2" @if ($exhibition_data[0]['product_status']==2) selected @endif>公開</option>
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
<script src="assets/extensions/filepond/filepond.js"></script>
<script src="assets/extensions/toastify-js/src/toastify.js"></script>
<script src="assets/js/pages/filepond.js"></script>
<script>
    var all_exhibition_data = <?php echo $exhibition_data; ?>;
    var rangeHtml = '';
    let len = all_exhibition_data.length + 1;
    for (let i = 1; i < len; i++) {
        if (i % 1000 == 0) {
            rangeHtml += `<tr>
                            <td>` + (i - 999) + `~` + i + `</td>
                            <td>
                                <a href="/downloadIMG/` + all_exhibition_data[i - 1000].id + `/` + all_exhibition_data[i - 1].id + `/` + (i - 999) + `/` + i + `" type="button" class="btn btn-outline-primary block mx-1 float-lg-end" id="download_image_zip"><i class="bi bi-download"></i> IMG</a>
                                <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/export_mercari_csv/` + all_exhibition_data[i - 1000].id + `/` + all_exhibition_data[i - 1].id + `/` + (i - 999) + `/` + i + `"><i class="bi bi-download"></i> csv作成</a>
                                <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/mercari_register_products/` + all_exhibition_data[i - 1000].id + `/` + all_exhibition_data[i - 1].id + `/` + (i - 999) + `/` + i + `"><i class="bi bi-card-checklist"></i> リスト</a>
                            </td>
                        </tr>`;
        }
    }
    rangeHtml += `<tr>
                    <td>` + (len - len % 1000 + 1) + `~` + (len - 1) + `</td>
                    <td>
                        <a href="/downloadIMG/` + all_exhibition_data[len - len % 1000].id + `/` + all_exhibition_data[len - 2].id + `/` + (len - len % 1000 + 1) + `/` + (len - 1) + `" type="button" class="btn btn-outline-primary block mx-1 float-lg-end" id="download_image_zip"><i class="bi bi-download"></i> IMG</a>
                        <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/export_mercari_csv/` + all_exhibition_data[len - len % 1000].id + `/` + all_exhibition_data[len - 2].id + `/` + (len - len % 1000 + 1) + `/` + (len - 1) + `"><i class="bi bi-download"></i> csv作成</a>
                        <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/mercari_register_products/` + all_exhibition_data[len - len % 1000].id + `/` + all_exhibition_data[len - 2].id + `/` + (len - len % 1000 + 1) + `/` + (len - 1) + `"><i class="bi bi-card-checklist"></i> リスト</a>
                    </td>
                </tr>`;

    $('#mercari_list').html(rangeHtml);

    $(document).ready(function() {
        if ($('#error').val() == 'no') {
            alert('ご迷惑をおかけして申し訳ございません。\nダウンロードする画像はありません。\n画像をダウンロードする前に、 出品データを再度ダウンロードしてください。');
        }
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
    // $('#download_image_zip').on('click', function() {
    //     $.ajax({
    //         url: "http://localhost:32768/api/v1/amazon/downloadImageZip",
    //         // url: "http://xs021277.xsrv.jp/fmproxy/api/v1/amazon/downloadImageZip",
    //         type: "post",
    //         data: {
    //             family_name: '{{Auth::user()->family_name}}'
    //         },
    //         beforeSend: function(data) {
    //             // console.log('{{Auth::user()->id}}');
    //         },
    //         success: function(res) {
    //         }
    //     });
    // });
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