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
            <div class="col-12 col-md-4 order-md-1 order-last">
                <h3>一括登録</h3>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-first">
                <a href="{{ route('mercari_update') }}" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-reply"></i> 戻る</a>
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
                                <th>SKU1</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>カテゴリID</th>
                                <th>出荷地</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mercari_update_data as $m)

                            @endforeach
                        </tbody>
                    </table>
                    @if (count($mercari_update_data)) {{ $mercari_update_data->onEachSide(1)->links('mypage.pagination') }} @endif

                </div>
            </div>
        </div>
    </section>
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
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
    $(document).ready(function() {
        if ($('#error').val() == 'no') {
            alert('ご迷惑をおかけして申し訳ございません。\nダウンロードする画像はありません。\n画像をダウンロードする前に、 出品データを再度ダウンロードしてください。');
        }
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
    // $('#export_entry_data').on('click', function() {
    //     $.ajax({
    //         url: "{{route('export_mercari')}}",
    //         type: "post",
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         data: {},
    //         beforeSend: function(data) {},
    //         success: function(res) {}
    //     });
    // });
</script>
<!-- end -->
@endpush
<!-- end additional scripts -->