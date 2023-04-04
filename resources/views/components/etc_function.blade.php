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
<link rel="stylesheet" href="assets/extensions/sweetalert2/sweetalert2.min.css">
<!-- end file css -->
@endsection
<!-- end additional css -->
<!-- start this page -->
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-12 m-2 order-md-1">
                <h3>在庫切れ商品数 : <span style="color:red; font-family:verdana">{{$mercari_updates_limit->count()}}個</span></h3>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header p-2">
            </div>
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" style="text-align: center;">
                        <thead>
                            <tr>
                                <th>商品管理コード</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>商品ステータス</th>
                                <th>最終更新日時</th>
                            </tr>
                        </thead>
                        <tbody id="mercari_update_limit">
                            @foreach($mercari_updates_limit as $row)
                            <tr id="{{ $row->id }}">
                                <td>{{ $row->SKU1_product_management_code }}</td>
                                <td><?php echo json_decode($row->product_name) ?></td>
                                <td>{{ $row->Selling_price }}</td>
                                <td>{{ $row->product_status }}</td>
                                <td>{{ $row->last_modified }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script>
    // var mercari_update_limit = <?php echo $mercari_updates_limit; ?>;
    // var rangeHtml = '';
    // var delete_ids = [];
    // console.log(mercari_update_limit);
    // let len = mercari_update_limit.length;
    // for (let i = 1; i < len; i++) {
    //     if (i % 1000 == 0) {
    //         rangeHtml += `<tr>
    //     <td>` + (i - 999) + `~` + i + `</td>
    //     <td>
    //         <a class="btn btn-outline-danger block mx-1 float-lg-end" id="mercari_update_delete" onclick="return window.confirm('データを本当に削除しますか？')" href="/mercari_update_delete/` + mercari_update_limit[i - 1000].id + `/` + mercari_update_limit[i - 1].id + `/` + (i - 999) + `/` + i + `"><i class="bi bi-trash"></i></a>
    //     </td>
    //     </tr>`;
    //     }
    // }
    // rangeHtml +=
    //     `<tr>
    //         <td>` + (len - len % 1000 + 1) + `~` + (len - 1) + `</td>
    //         <td>
    //             <a class="btn btn-outline-danger block mx-1 float-lg-end" id="mercari_update_delete" onclick="return window.confirm('データを本当に削除しますか？')" href="/mercari_update_delete/` + mercari_update_limit[len - len % 1000].id + `/` + mercari_update_limit[len - 2].id + `/` + (len - len % 1000 + 1) + `/` + (len - 1) + `"><i class="bi bi-trash"></i></a>
    //         </td>
    //     </tr>`;

    // $('#mercari_update_limit').html(rangeHtml);

    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
</script>


@endpush