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
                <h3>出品中商品数 : <span style="color:red; font-family:verdana">{{$mercari_updates->count()}}個</span></h3>
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
                <a href="{{ route('mercari_update_allremove') }}" type="button" class="mx-4 btn btn-outline-danger block float-start float-lg-end" onclick="return window.confirm('データを本当に削除しますか？')" id="mercari_update_allremove">
                    <i class="bi bi-trash"></i> 削除する
                </a>
                <button type="button" class="mx-4 btn btn-outline-primary block float-start float-lg-end" data-bs-toggle="modal" data-bs-target="#mercari_import_csv">
                    <i class="bi bi-filetype-csv"></i> CSVのアップロード
                </button>
            </div>
            <div class="card-body ">
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
<div class="modal fade text-left" id="mercari_import_csv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
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
                    <input type="file" name="file" id="file" class="form-control" />
                    <!-- <input type="file" class="form-control csv_event" style="cursor: pointer;" placeholder="CSVファイルを選択してください。" id="csvfile" /> -->
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="col-12 col-lg-6 btn btn btn-outline-primary" data-bs-dismiss="modal" id="update_csv">
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
@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<!-- start file js -->
<script src="assets/extensions/filepond/filepond.js"></script>
<script src="assets/extensions/toastify-js/src/toastify.js"></script>
<script src="assets/js/pages/filepond.js"></script>
<script>
    var mercari_update_data = <?php echo $mercari_updates; ?>;
    var rangeHtml = '';
    let len = mercari_update_data.length + 1;
    for (let i = 1; i < len; i++) {
        if (i % 1000 == 0) {
            rangeHtml +=
                `<tr>
                    <td>` + (i - 999) + `~` + i + `</td>
                    <td>
                        <a class="btn btn-outline-danger block mx-1 float-lg-end" id="mercari_update_delete" onclick="return window.confirm('データを本当に削除しますか？')" href="/mercari_update_delete/` + mercari_update_data[i - 1000].id + `/` + mercari_update_data[i - 1].id + `/` + (i - 999) + `/` + i + `"><i class="bi bi-trash"></i></a>
                        <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/export_mercari_update_csv/` + mercari_update_data[i - 1000].id + `/` + mercari_update_data[i - 1].id + `/` + (i - 999) + `/` + i + `"><i class="bi bi-download"></i> CSV</a>
                    </td>
                </tr>`;
        }
    }
    rangeHtml +=
        `<tr>
            <td>` + (len - len % 1000 + 1) + `~` + (len - 1) + `</td>
            <td>
                <a class="btn btn-outline-danger block mx-1 float-lg-end" id="mercari_update_delete" onclick="return window.confirm('データを本当に削除しますか？')" href="/mercari_update_delete/` + mercari_update_data[len - len % 1000].id + `/` + mercari_update_data[len - 2].id + `/` + (len - len % 1000 + 1) + `/` + (len - 1) + `"><i class="bi bi-trash"></i></a>
                <a class="btn btn-outline-primary block mx-1 float-lg-end" href="/export_mercari_update_csv/` + mercari_update_data[len - len % 1000].id + `/` + mercari_update_data[len - 2].id + `/` + (len - len % 1000 + 1) + `/` + (len - 1) + `"><i class="bi bi-download"></i> CSV</a>
            </td>
        </tr>`;

    $('#mercari_update_list').html(rangeHtml);

    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
    function mercari_update_allremove() {
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
    

</script>
<!-- end -->
@endpush
<!-- end additional scripts -->