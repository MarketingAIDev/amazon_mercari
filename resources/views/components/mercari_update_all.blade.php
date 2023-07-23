<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">
<link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css')}}">
<link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/pages/datatables.css')}}">
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
                <h3>更新商品一覧</h3>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-first">
                <a href="{{ route('mercari_update') }}" class="btn btn-outline-primary block float-start float-lg-end m-2"><i class="bi bi-reply"></i> 戻る</a>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover datatable" style="min-width:980px">
                    <thead>
                        <tr>
                            <th>SKU1</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>発送元の地域</th>
                            <th>商品ステータス</th>
                            <th>商品登録日時</th>
                            <th>最終更新日時</th>
                            <th>削除</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<!-- start file js -->
<script src="{{ asset('assets/extensions/filepond/filepond.js')}}"></script>
<script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/pages/datatables.min.js')}}"></script>
<script>
    const region = {
        jp01: '北海道',
        jp02: '青森県',
        jp03: '岩手県',
        jp04: '宮城県',
        jp05: '秋田県',
        jp06: '山形県',
        jp07: '福島県',
        jp08: '茨城県',
        jp09: '栃木県',
        jp10: '群馬県',
        jp11: '埼玉県',
        jp12: '千葉県',
        jp13: '東京都',
        jp14: '神奈川県',
        jp15: '新潟県',
        jp16: '富山県',
        jp17: '石川県',
        jp18: '福井県',
        jp19: '山梨県',
        jp20: '長野県',
        jp21: '岐阜県',
        jp22: '静岡県',
        jp23: '愛知県',
        jp24: '三重県',
        jp25: '滋賀県',
        jp26: '京都府',
        jp27: '大阪府',
        jp28: '兵庫県',
        jp29: '奈良県',
        jp30: '和歌山県',
        jp31: '鳥取県',
        jp32: '島根県',
        jp33: '岡山県',
        jp34: '広島県',
        jp35: '山口県',
        jp36: '徳島県',
        jp37: '香川県',
        jp38: '愛媛県',
        jp39: '高知県',
        jp40: '福岡県',
        jp41: '佐賀県',
        jp42: '長崎県',
        jp43: '熊本県',
        jp44: '大分県',
        jp45: '宮崎県',
        jp46: '鹿児島県',
        jp47: '沖縄県'
    }

    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);


    const deleteMercariUpdate = (id) => {
        if (!window.confirm('データを本当に削除しますか？')) {
            return;
        }
        $.ajax({
            url: "{{ route('delete_mercari_update_data') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            },
            success: function() {
                Toastify({
                    text: "データが正常に削除されました。",
                    duration: 2500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
                setTimeout(() => {
                    location.href = window.location.href;
                }, 1000);
            }
        });

    }
    var datatable = $('.datatable').DataTable({
        columnDefs: [{
            targets: 3,
            className: 'dt-body-right'
        }],
        processing: true,
        serverSide: true,
        autoConfig: true,
        pageLength: 10,
        ajax: '{{ route("mercari_update_view") }}',
        columns: [{
                data: 'SKU1_product_management_code',
                name: 'SKU1_product_management_code',
            },
            {
                data: 'jsonstr',
                name: 'jsonstr',
            },
            {
                data: 'Selling_price',
                name: 'Selling_price',
                render: function(data, type, row) {
                    return (
                        `¥${row.Selling_price}`
                    )
                }
            },
            {
                data: null,
                name: 'region_origin',
                render: function(data, type, row) {
                    let result_region_origin = region[row.region_origin];
                    console.log(result_region_origin)
                    return (
                        `${result_region_origin}`
                    )
                }
            }, {
                data: null,
                name: 'product_status',
                render: function(data, type, row) {
                    if (row.product_status == 1) {
                        return (
                            `<span class="badge bg-info">非公開</span>`
                        );
                    } else if (row.product_status == 2) {
                        return (
                            `<span class="badge bg-success">公開</span>`
                        )
                    } else {
                        return (`<span class="badge bg-danger">削除中</span>`)
                    }
                }
            },

            {
                data: 'product_registration_time',
                name: 'product_registration_time'
            },
            {
                data: 'last_modified',
                name: 'last_modified',
            },
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return (
                        `<a class="btn btn-round btn-danger btn-sm" style="cursor:pointer" onclick="deleteMercariUpdate(${data})"><i class="bi bi-trash"></i></a>`
                    )
                }
            },
        ]
    });

    $(document).ready(function() {
        $('.datatable').DataTable().ajax.reload();
    });
</script>
@endpush