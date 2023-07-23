<!-- extend sidebar -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<!-- end file css -->
@endsection
<!-- end additional css -->
<!-- start this page -->
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-12 m-2 order-md-1">
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header p-2">
            </div>
            <div class="card-body ">
                <div class="col-12">
                    <div class="alert alert-light-info color-info"><i class="bi bi-star"></i> メルカリショップから削除した商品のSKU1商品管理コードを入力してください。（完全一致）
                    </div>
                </div>
                <div class="col-12">
                    <form action="" method="">
                        <div class="form-group col-12">
                            <textarea class="col-12 form-control" id="delete_SKU1" cols="100" rows="10" placeholder="複数のキーワードで絞り込みたい場合は改行で区切って入力してください
例）
MC0000000001
MC0000000002
MC0000000003
MC0000000004
MC0000000005"></textarea>
                        </div>
                        <button type="button" onclick="deletedProductAction()" class="btn btn-primary">削除</button>
                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
@endsection
<!-- end this page -->
<!-- start additional scripts -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
    const deletedProductAction = () => {
        let textarea = $('#delete_SKU1').val().split('\n');
        if (textarea == '' || textarea[0].length < 12) {
            Toastify({
                text: "SKU1 製品管理コードが正しくありません。正確に入力してください。",
                duration: 2500,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "rgb(213 45 45 / 72%)",
            }).showToast();
        } else {
            console.log(textarea);
            $.ajax({
                url: "{{ route('deleted_SKU1') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    deleted_product: textarea,
                },
                success: function() {
                    Toastify({
                        text: "操作が成功しました。",
                        duration: 2500,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                }
            })

        }
    }
</script>


@endpush