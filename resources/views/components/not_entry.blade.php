<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<!-- start datatable css -->
<!-- <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
<link rel="stylesheet" href="assets/css/pages/simple-datatables.css"> -->
<!-- end datatable css -->
<!-- start file choose css -->
<link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
<link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
<link rel="stylesheet" href="assets/extensions/toastify-js/src/toastify.css">
<link rel="stylesheet" href="assets/css/pages/filepond.css">
<style>
    table td,
    table th {
        min-width: 3rem ! important;
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
            <div class="col-12 col-md-4 order-md-1 order-last">
                <h3>出品不可商品</h3>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-first">
                <a href="{{route('entry_data')}}" class="btn btn  btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-file-earmark-font-fill"></i> 出品対象商品</a>
                <a href="{{route('entry_data_not')}}" class="btn btn-primary block float-start float-lg-end m-2"><i class="bi bi-file-earmark-font-fill"></i> 出品不可商品</a>
                <a href="{{route('entry_setting')}}" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-pencil"></i>出品設定</a>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <!-- <th>SKU1_商品管理コード</th> -->
                            <th>画像</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>メルカリカテゴリー</th>
                            <th>除外理由</th>
                            <!-- <th>登録価格</th>
                            <th>出品価格</th> -->
                            <!-- <th>送料</th> -->
                            <!-- <th>その他費用</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($exhibitions) == 0)
                        <tr>
                            <td colspan="5" style="text-align: center;">データがありません。</td>
                        </tr>
                        @endif
                        @foreach($exhibitions as $e)
                        @php
                        $image = explode(';',$e->image);
                        @endphp
                        <tr id="{{$e->id}}">
                            <!-- <td>{{$e->m_code}}</td> -->
                            <td>
                                <div style="max-width: 4rem;height: 4rem; background-color:white;text-align:center">
                                    <img style="max-width: 100%;max-height: 4rem;" src="{{ $image[0] }}" alt="image"><br>
                                </div>
                                @if ($e->prime == 'yes')
                                <span class="badge bg-primary">prime</span>
                                @else
                                <span class="badge bg-danger">非prime</span>
                                @endif
                                <!-- <span class="badge bg-danger">{{ ($e->prime == 'yes')? "" : "prime" }}</span> -->
                            </td>
                            <td data-bs-toggle="tooltip">{{ json_decode($e->product) }}</td>
                            <td style="text-align:end">
                                <span data-bs-toggle="tooltip" title="出品価格" class="badge bg-light-success">¥{{number_format($e->e_price)}}</span>
                                <br><span data-bs-toggle="tooltip" title="アマゾン価格" class="badge bg-light-secondary">¥{{ number_format($e->price) }}</span>
                                <br><span data-bs-toggle="tooltip" title="送料" class="badge bg-light-secondary">¥{{ number_format($e->postage) }}</span>
                                <br><span data-bs-toggle="tooltip" title="その他費用" class="badge bg-light-secondary">¥{{ number_format($e->etc) }}</span>
                            </td>
                            <td data-bs-toggle="tooltip" title="{{$e->m_category_id}}">{{ $e->m_category }}</td>
                            <td><?php echo $e->exclusion ?></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (count($exhibitions)) {{ $exhibitions->onEachSide(1)->links('mypage.pagination') }} @endif
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


    const refresh_page = () => {
        location.href = "{{ route('entry_data') }}";
    };
</script>
<!-- end -->
@endpush
<!-- end additional scripts -->