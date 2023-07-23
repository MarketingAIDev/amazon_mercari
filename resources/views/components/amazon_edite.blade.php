<!-- extend layouts -->
@extends("layouts.sidebar")
@section('additional_CSS')
<style>
    .change_img {
        min-width: 80px;
        height: 80px;
        width: 80px;
        background-color: #efefef;
        border-color: #efefef;
    }

    .css-b8o04o {
        position: relative;
        top: -83px;
        right: -59px;
        background: transparent;
        border: 0px;
    }

    .css-1600cfd {
        width: 24px;
        height: auto;
        display: inline-block;
        line-height: 1em;
        flex-shrink: 0;
        color: currentcolor;
        vertical-align: middle;
        max-width: 100%;
        fill: var(--chakra-colors-brandGray-400);
        z-index: 3;
    }

    img {
        text-align: center;
        min-width: 70px;
        height: 80px;
        width: 70px;
        cursor: pointer;
    }

    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {
        opacity: 0.7;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        height: auto;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .modal-content,
    #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px) {
        .modal-content {
            width: 100%;
        }
    }
</style>
@endsection
<!-- start this page -->
@section('content')
<div class="page-heading" id="product_info">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last" id="product_title">
                <h3>商品変更</h3>
            </div>
            <!-- <div class="col-12 col-md-6 order-md-2 order-first">
                <a href="{{route('base_data')}}" type="button" class="my-2 btn btn-outline-primary block float-start float-lg-end mx-3 product_information" id="info_back">Amazon商品リストへ</a>
            </div> -->
        </div>
    </div>
    <section class="section">
        <div class="card">
            <!-- <div class="card-header">
                <h4 class="card-title" id="true_title">{{ json_decode($product->product)}}</h4>
            </div> -->
            <div class="card-body">
                <div class="d-flex" style="overflow-x: auto; overflow-y: hidden;" id="imgFrame">
                    <div class="change_img mx-1">
                        <input accept="image/*,.jpeg,.jpg,.png" multiple="" type="file" id="imgSelect" tabindex="-1" style="display: none;" onchange="previewFile(event)">
                        <svg id="imgSvg" style="cursor: pointer;" viewBox="0 0 24 24" focusable="false" class="chakra-icon css-rpm1sx">
                            <path clip-rule="evenodd" d="M20.3 3H6.47a.7.7 0 0 0-.7.7v13.83a.7.7 0 0 0 .7.7H20.3a.7.7 0 0 0 .7-.7V3.7a.7.7 0 0 0-.7-.7Zm-.7 13.83H7.17V4.4H19.6v12.43Zm-7.48-9.01a1.69 1.69 0 1 1-3.38 0 1.69 1.69 0 0 1 3.38 0ZM17 15.1H9.44a.89.89 0 0 1-.63-1.52L10.44 12a.89.89 0 0 1 1.25 0l.52.45 1.69-1.69a1.21 1.21 0 0 1 1.72 0L17.86 13a1.22 1.22 0 0 1-.86 2.1ZM4.4 19.6h13.13a.7.7 0 1 1 0 1.4H3.7a.7.7 0 0 1-.7-.7V6.47a.7.7 0 1 1 1.4 0V19.6Z"></path>
                        </svg>
                    </div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                    <div class="change_img mx-1"></div>
                </div>

                <div class="row m-3">
                    <div class="form-body ">
                        <div class="row">
                            <div class="col-md-3">
                                <label>商品名;</label>
                            </div>
                            <div class="col-md-9 form-group" id="product_name">
                                <input type="text" class="form-control" name="product" value="{{ json_decode($product->product) }}"></input>
                            </div>
                            <div class="col-md-3">
                                <label>ASIN;</label>
                            </div>
                            <div class="col-md-9 form-group" id="ASIN">
                                <input type="text" class="form-control" name="ASIN" value="{{ $product->ASIN }}" readonly></input>
                            </div>
                            <div class="col-md-3">
                                <label>Variation Attributes;</label>
                            </div>
                            <div class="col-md-9 form-group" id="attribute">
                                <input type="text" class="form-control" name="attribute" value="{{ json_decode($product->attribute) }}"> </input>
                            </div>
                            <div class="col-md-3">
                                <label>売れ筋ランキング;</label>
                            </div>
                            <div class="col-md-9 form-group" id="rank">
                                <input type="text" name="rank" class="form-control" value="{{ $product->rank }}"></input>
                            </div>
                            <div class="col-md-3">
                                <label>Amazonカテゴリ Root;</label>
                            </div>
                            <div class="col-md-9 form-group" id="a_c_root">
                                <input type="text" name="a_c_root" class="form-control" value="{{ $product->a_c_root }}" readonly></input>
                            </div>
                            <div class="col-md-3">
                                <label>Amazonカテゴリ Sub;</label>
                            </div>
                            <div class="col-md-9 form-group" id="a_c_sub">
                                <input type="text" name="a_c_sub" class="form-control" value="{{ $product->a_c_sub }}" readonly></input>
                            </div>
                            <div class="col-md-3">
                                <label>Amazonカテゴリ Tree;</label>
                            </div>
                            <div class="col-md-9 form-group" id="a_c_tree">
                                <input type="text" name="a_c_tree" class="form-control" value="{{ $product->a_c_tree }}" readonly></input>
                            </div>
                            <div class="col-md-3">
                                <label>アマゾン価格(¥);</label>
                            </div>
                            <div class="col-md-9 form-group" id="price">
                                <input type="number" name="price" class="form-control" value="{{ $product->price }}"></input>
                            </div>
                            <div class="col-md-3">
                                <label>Package: Length (cm);</label>
                            </div>
                            <div class="col-md-9 form-group" id="p_length">
                                <input type="number" name="p_length" class="form-control" value="{{ $product->p_length }}"></input>
                            </div>
                            <div class="col-md-3">
                                <label>Package: Width (cm);</label>
                            </div>
                            <div class="col-md-9 form-group" id="p_width">
                                <input type="number" name="p_width" class="form-control" value="{{ $product->p_width }}"></input>
                            </div>
                            <div class="col-md-3">
                                <label>Package: Height (cm);</label>
                            </div>
                            <div class="col-md-9 form-group" id="p_height">
                                <input type="number" name="p_height" class="form-control" value="{{ $product->p_height }}"></input>
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
                                <textarea class="form-control" name="feature" rows="8" placeholder="商品の説明を入力してください"><?php echo json_decode($product->feature) ?></textarea>
                            </div>
                            <div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <textarea class="form-control" name="feature_1" rows="8" placeholder="商品の説明を入力してください"><?php echo json_decode($product->feature_1) ?></textarea>

                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <textarea class="form-control" name="feature_2" rows="8" placeholder="商品の説明を入力してください"><?php echo json_decode($product->feature_2) ?></textarea>

                            </div>
                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                <textarea class="form-control" name="feature_3" rows="8" placeholder="商品の説明を入力してください"><?php echo json_decode($product->feature_3) ?></textarea>

                            </div>
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <textarea class="form-control" name="feature_4" rows="8" placeholder="商品の説明を入力してください"><?php echo json_decode($product->feature_4) ?></textarea>

                            </div>
                            <div class="tab-pane fade" id="v-pills-feature" role="tabpanel" aria-labelledby="v-pills-feature-tab">
                                <textarea class="form-control" name="feature_5" rows="8" placeholder="商品の説明を入力してください"><?php echo json_decode($product->feature_5) ?></textarea>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="float-start float-lg-end col-12 col-md-6 order-md-2 order-first">
                    <a href="{{route('base_data')}}" type="button" class="my-2 btn btn-outline-secondary block float-start float-lg-end mx-3 product_information" id="info_back">キャンセル</a>
                    <button class="my-2 btn btn-outline-primary block float-start float-lg-end mx-3" onclick="changeAmazonData({{$product->id}})" type="button">保存</button>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="myModal" class="modal" overflow-auto>
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        let amazonImgs = '{{ $product->image }}';
        let eachImg = amazonImgs.split(';');
        let len = eachImg.length - 1;
        for (let i = 0; i < len; i++) {
            var element = `<img class="medium_img" onclick="description(event)" src="${eachImg[i]}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="1">
            <button type="button" class="css-b8o04o" onclick="removeIMG(event)">
                <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon css-1600cfd"><path clip-rule="evenodd" d="M19.07 4.93a10 10 0 1 0 0 14.14c3.904-3.905 3.904-10.235 0-14.14ZM16.39 15.4a.69.69 0 0 1 0 1 .67.67 0 0 1-.49.21.7.7 0 0 1-.5-.21L12 13l-3.5 3.5a.7.7 0 0 1-.49.2.74.74 0 0 1-.5-.2.71.71 0 0 1 0-1L11 12 7.51 8.5a.707.707 0 0 1 1-1L12 11l3.4-3.4a.707.707 0 1 1 1 1L13 12l3.39 3.4Z"></path></svg>
            </button>`;
            $('.change_img')[i + 1].innerHTML = element;
        }
        if (eachImg[len] != '') {
            var element = `<img class="medium_img" src="${eachImg[len ]}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="1">
            <button type="button" class="css-b8o04o" onclick="removeIMG(event)">
                <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon css-1600cfd"><path clip-rule="evenodd" d="M19.07 4.93a10 10 0 1 0 0 14.14c3.904-3.905 3.904-10.235 0-14.14ZM16.39 15.4a.69.69 0 0 1 0 1 .67.67 0 0 1-.49.21.7.7 0 0 1-.5-.21L12 13l-3.5 3.5a.7.7 0 0 1-.49.2.74.74 0 0 1-.5-.2.71.71 0 0 1 0-1L11 12 7.51 8.5a.707.707 0 0 1 1-1L12 11l3.4-3.4a.707.707 0 1 1 1 1L13 12l3.39 3.4Z"></path></svg>
            </button>`;
            $('.change_img')[len + 1].innerHTML = element;
        }
    });

    $('#imgSvg').on('click', function() {
        $('#imgSelect').click();
    });

    function previewFile(e) {
        if (e.target.files[0] === undefined || e.target.files[0] == null) {
            return;
        }
        let amazonImgs = '{{ $product->image }}';
        let eachImg = amazonImgs.split(';');
        let len = eachImg.length - 1;
        for (let i = 0; i < len; i++) {
            var element = `<img class="medium_img" src="${eachImg[i]}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="1">
            <button type="button" class="css-b8o04o" onclick="removeIMG(event)">
                <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon css-1600cfd"><path clip-rule="evenodd" d="M19.07 4.93a10 10 0 1 0 0 14.14c3.904-3.905 3.904-10.235 0-14.14ZM16.39 15.4a.69.69 0 0 1 0 1 .67.67 0 0 1-.49.21.7.7 0 0 1-.5-.21L12 13l-3.5 3.5a.7.7 0 0 1-.49.2.74.74 0 0 1-.5-.2.71.71 0 0 1 0-1L11 12 7.51 8.5a.707.707 0 0 1 1-1L12 11l3.4-3.4a.707.707 0 1 1 1 1L13 12l3.39 3.4Z"></path></svg>
            </button>`;
            $('.change_img')[i + 2].innerHTML = element;
        }
        if (eachImg[len] != '') {
            var element = `<img class="medium_img" src="${eachImg[len ]}" alt="image" data-bs-target="#Gallerycarousel" data-bs-slide-to="1">
            <button type="button" class="css-b8o04o" onclick="removeIMG(event)">
                <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon css-1600cfd"><path clip-rule="evenodd" d="M19.07 4.93a10 10 0 1 0 0 14.14c3.904-3.905 3.904-10.235 0-14.14ZM16.39 15.4a.69.69 0 0 1 0 1 .67.67 0 0 1-.49.21.7.7 0 0 1-.5-.21L12 13l-3.5 3.5a.7.7 0 0 1-.49.2.74.74 0 0 1-.5-.2.71.71 0 0 1 0-1L11 12 7.51 8.5a.707.707 0 0 1 1-1L12 11l3.4-3.4a.707.707 0 1 1 1 1L13 12l3.39 3.4Z"></path></svg>
            </button>`;
            $('.change_img')[len + 2].innerHTML = element;
        }

        var preview = $('.medium_img')[0];
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            let postData = {
                image: reader.result,
                // target: obj,
            };
            $.ajax({
                url: "{{route('imgSave')}}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'image': JSON.stringify(postData)
                },
                success: function(result, status, xhr) {
                    console.log(result);
                    preview.src = result;
                    var newUrl = 'http://xs021277.xsrv.jp' + result;
                    console.log(newUrl);
                }
            });
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
    const changeAmazonData = (id) => {
        let img = $('.medium_img');
        let len = img.length;
        let img_urls = '';
        for (let i = 0; i < len; i++) {
            img_urls += img[i].src + ';';
            console.log(img[i].src);
        }
        let postData = {
            product: $("input[name = 'product'").val(),
            p_id: id,
            image: img_urls,
            attribute: $("input[name = 'attribute'").val(),
            rank: $("input[name = 'rank'").val(),
            price: $("input[name = 'price'").val(),
            p_length: $("input[name = 'p_length'").val(),
            p_width: $("input[name = 'p_width'").val(),
            p_height: $("input[name = 'p_height'").val(),
            feature: $("textarea[name = 'feature'").val(),
            feature_1: $("textarea[name = 'feature_1'").val(),
            feature_2: $("textarea[name = 'feature_2'").val(),
            feature_3: $("textarea[name = 'feature_3'").val(),
            feature_4: $("textarea[name = 'feature_4'").val(),
            feature_5: $("textarea[name = 'feature_5'").val(),
        }

        console.log(postData)

        $.ajax({
            url: "{{ route('update_base_product') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                change: postData,
            },
            success: function(res) {
                Toastify({
                    text: "データが正常に保存されました。",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
                setInterval(() => {
                    location.href = window.location.href;
                }, 2000);
            }
        })
    }
    const description = (e) => {
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        modal.style.display = "block";
        modalImg.src = e.target.src;

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

    }
    const removeIMG = (para) => {
        // #imgFrame > div: nth - child(3) > button > svg
        console.log(para);
        para.currentTarget.parentElement.remove();
        var newDiv = `<div class="change_img mx-1"></div>`;
        $('#imgFrame').html($('#imgFrame').html() + newDiv);
        $('#imgSvg').on('click', function() {
            $('#imgSelect').click();
        });
    }
</script>
@endpush