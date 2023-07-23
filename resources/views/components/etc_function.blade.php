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
<link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css')}}">
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
                                <th>商品管理コード</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>商品ステータス</th>
                                <th>商品登録日時</th>
                                <th>最終更新日時</th>
                            </tr>
                        </thead>
                        <tbody id="mercari_update_limit">
                            @if (count($mercari_updates_limit) == 0)
                            <tr>
                                <td colspan="6" style="text-align: center;">データがありません。</td>
                            </tr>
                            @endif
                            @foreach($mercari_updates_limit as $row)
                            <tr id="{{ $row->id }}">
                                <td>{{ $row->SKU1_product_management_code }}</td>
                                <td style="max-width: 20rem;"><?php echo json_decode($row->product_name) ?></td>
                                <td style="text-align: right;">¥{{ number_format($row->Selling_price) }}</td>
                                <td>
                                    @if ( $row->product_status == 1) <span class="badge bg-info">非公開</span>
                                    @elseif ( $row->product_status == 2) <span class="badge bg-success">公開</span>
                                    @else{
                                    <span class="badge bg-danger">削除中</span>
                                    }
                                    @endif
                                </td>
                                <td>{{ $row->product_registration_time }}</td>
                                <td>{{ $row->last_modified }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($mercari_updates_limit)) {{ $mercari_updates_limit->onEachSide(1)->links('mypage.pagination') }} @endif
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