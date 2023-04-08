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
                <h3>在庫切れ商品数 : <span style="color:red; font-family:verdana"><?php echo count($mercari_updates_limit); ?>個</span></h3>
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
                                <th>画像</th>
                                <th>商品管理コード</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>商品ステータス</th>
                                <th>最終更新日時</th>
                            </tr>
                        </thead>
                        <tbody id="mercari_update_limit">
                            @foreach($mercari_updates_limit as $row)
                            <tr id="{{ $row[0] }}">
                                <td><img src="{{ $row[1] }}" style="width:50px;heigth:50px;" /></td>
                                <td>{{ $row[2] }}</td>
                                <td style="max-width: 20rem;"><?php echo json_decode($row[3]) ?></td>
                                <td style="text-align: right;">¥{{ number_format($row[4]) }}</td>
                                <td>
                                    @if ( $row[5] == 1) 非公開
                                    @elseif ( $row[5] == 2) 公開
                                    @else{
                                    削除
                                    }
                                    @endif
                                </td>
                                <td>{{ $row[6] }}</td>
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
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
</script>


@endpush