<!-- extend layouts -->
@extends("layouts.sidebar")
<!-- start additional css  -->
@section('additional_CSS')
<!-- start datatable css -->
@endsection
<!-- start this page -->
@section('content')
<div class="page-heading" id="product_info">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last" id="product_title">
                <h3>{{ $product->a_c_sub}}</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <a href="{{route('base_data')}}" type="button" class="my-2 btn btn-outline-primary block float-start float-lg-end mx-3 product_information" id="info_back">Amazon商品リストへ</a>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" id="true_title">{{ json_decode($product->product)}}</h4>
            </div>
            <div class="card-body">
                <div class="row gallery" data-bs-toggle="modal" data-bs-target="#galleryModal">
                    @php
                    $images = explode( ';', $product->image);
                    if (count($images) > 4) {
                    $image_1 = $images[0] ?? '';
                    $image_2 = $images[1] ?? '';
                    $image_3 = $images[2] ?? '';
                    $image_4 = $images[3] ?? '';
                    }else {
                    $image_1 = $images[0] ?? '';
                    $image_2 = '';
                    $image_3 ='';
                    $image_4 = '';
                    }
                    @endphp
                    <div class="col-6 col-sm-6 col-lg-3 mt-2 mt-md-0 mb-md-0 mb-2">
                        <a href="">
                            <img class="w-100 active" style="height:100%" id="small_img" src="{{ $image_1 }}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                        </a>
                    </div>
                    <div class="col-6 col-sm-6 col-lg-3 mt-2 mt-md-0 mb-md-0 mb-2">
                        <a href="">
                            <img class="w-100" style="height:100%" id="medium_img" src="{{ $image_2 }}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="1">
                        </a>
                    </div>
                    <div class="col-6 col-sm-6 col-lg-3 mt-2 mt-md-0 mb-md-0 mb-2">
                        <a href="">
                            <img class="w-100" style="height:100%" id="large_img" src="{{ $image_3 }}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="2">
                        </a>
                    </div>
                    <div class="col-6 col-sm-6 col-lg-3 mt-2 mt-md-0 mb-md-0 mb-2">
                        <a href="">
                            <img class="w-100" style="height:100%" id="large_img" src="{{ $image_4 }}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="3">
                        </a>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="form-body ">
                        <div class="row">
                            <div class="col-md-3">
                                <label>商品名;</label>
                            </div>
                            <div class="col-md-9 form-group" id="product_name">
                                <span>{{ json_decode($product->product) }}</span>
                            </div>
                            <div class="col-md-3">
                                <label>ASIN;</label>
                            </div>
                            <div class="col-md-9 form-group" id="ASIN">
                                <span>{{ $product->ASIN }}</span>
                            </div>
                            <div class="col-md-3">
                                <label>Variation Attributes;</label>
                            </div>
                            <div class="col-md-9 form-group" id="attribute">
                                <span>{{ json_decode($product->attribute) }}</span>
                            </div>
                            <div class="col-md-3">
                                <label>売れ筋ランキング;</label>
                            </div>
                            <div class="col-md-9 form-group" id="rank">
                                <span>{{ $product->rank }}</span>
                            </div>
                            <div class="col-md-3">
                                <label>Amazonカテゴリ Root;</label>
                            </div>
                            <div class="col-md-9 form-group" id="a_c_root">
                                <span>{{ $product->a_c_root }}</span>
                            </div>
                            <div class="col-md-3">
                                <label>Amazonカテゴリ Sub;</label>
                            </div>
                            <div class="col-md-9 form-group" id="a_c_sub">
                                <span>{{ $product->a_c_sub }}</span>
                            </div>
                            <div class="col-md-3">
                                <label>Amazonカテゴリ Tree;</label>
                            </div>
                            <div class="col-md-9 form-group" id="a_c_tree">
                                <span>{{ $product->a_c_tree }}</span>
                            </div>
                            <div class="col-md-3">
                                <label>アマゾン価格;</label>
                            </div>
                            <div class="col-md-9 form-group" id="a_c_tree">
                                <span>¥{{ $product->price }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-2">
                    <div class="col-sm-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active px-2" id="v-pills-first-tab" data-bs-toggle="pill" href="#v-pills-first" role="tab" aria-controls="v-pills-first" aria-selected="true">説明 & Features </a>
                            <a class="nav-link px-2" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">説明 & Features 1</a>
                            <a class="nav-link px-2" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false" tabindex="-1">説明 & Features 2</a>
                            <a class="nav-link px-2" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" tabindex="-1">説明 & Features 3</a>
                            <a class="nav-link px-2" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false" tabindex="-1">説明 & Features 4</a>
                            <a class="nav-link px-2" id="v-pills-feature-tab" data-bs-toggle="pill" href="#v-pills-feature" role="tab" aria-controls="v-pills-feature" aria-selected="false" tabindex="-1">説明 & Features 5</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade active show" id="v-pills-first" role="tabpanel" aria-labelledby="v-pills-first-tab">
                                <?php echo json_decode($product->feature) ?>
                            </div>
                            <div class="tab-pane fade active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <?php echo json_decode($product->feature_1) ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <?php echo json_decode($product->feature_2) ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                <?php echo json_decode($product->feature_3) ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <?php echo json_decode($product->feature_4) ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-feature" role="tabpanel" aria-labelledby="v-pills-feature-tab">
                                <?php echo json_decode($product->feature_5) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="Gallerycarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="{{ $image_1 }}">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ $image_2 }}">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ $image_3 }}">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ $image_4 }}">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#Gallerycarousel" role="button" type="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </a>
                    <a class="carousel-control-next" href="#Gallerycarousel" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush