<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
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
                <a href="{{ route('mercari_list') }}" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-reply"></i> 戻る</a>
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
                                <th>画像</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>カテゴリID</th>
                                <th>出荷地</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mercari_products as $m)
                            <tr id="{{$m->id}}">
                                <td>{{$m->SKU1_management}}</td>
                                <td>
                                    <div style="max-width: 4rem;height: 4rem; background-color:white;text-align:center">
                                        <img style="max-width: 100%;max-height: 4rem;" src="{{ $m->image }}" alt="image"><br>
                                    </div>
                                </td>
                                <td data-bs-toggle="tooltip">{{ json_decode($m->product) }}</td>
                                <td style="text-align:end">
                                    <span data-bs-toggle="tooltip" title="出品価格" class="badge bg-light-success">¥{{number_format($m->selling_price)}}</span>
                                </td>
                                <td data-bs-toggle="tooltip">{{ $m->category_id }}</td>
                                <td>
                                    <span data-bs-toggle="tooltip" style="text-align: right;" class="badge bg-light-warning"><?php echo ($m->region_origin == 'jp12') ? '千葉県' : '' ?></span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($mercari_products)) {{ $mercari_products->onEachSide(1)->links('mypage.pagination') }} @endif

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