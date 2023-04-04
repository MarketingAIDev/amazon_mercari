<!DOCTYPE html>
<html class="no-js " lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Amazon Track">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MERCARI</title>

    <link rel="stylesheet" href="{{asset('assets/css/main/app.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main/app-dark.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/preloader/css/loader-4.css') }}" />
    <link rel="stylesheet" href="{{asset('assets/css/shared/iconly.css')}}">
    <link rel="stylesheet" href="{{asset('assets/extensions/toastify-js/src/toastify.css')}}">
    <style>
        #backdrop,
        #category_model,
        #backdrop_2,
        #mercari_setting,
        #region_origin {
            background: rgb(13 17 70 / 17%);
        }

        .hidden {
            display: none;
        }
    </style>
    <!-- Favicons -->
    <link href="{{asset('avatars/1.png')}}" rel="icon">
    <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
    @yield('additional_CSS')
</head>

<body class="">
    <div id="app">
        <div class="container">
            <div class="row mt-5">
                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h1>プランを選択してください。</h1>
                                <h5 class="text-subtitle text-muted">メルカリショップに商品を登録しましょう。</h5>
                            </div>
                        </div>
                    </div>    
                </div>
                <section class="section">
                    <div class="row">
                        <div class="col-12 col-md-8 offset-md-2">
                            <div class="pricing">
                                <div class="row align-items-center">
                                    @foreach($plans as $p)
                                    <div class="col-lg-3 col-md-6 mt-4 px-0 position-relative z-1" id="{{ $p->id }}">
                                        <div class="card">
                                            <div class="card-header text-center bg-primary">
                                                <h1 class="card-title text-white" style="font-size: 24px !important;">{{ $p->name }}</h1>
                                            </div>
                                            <div class="text-center m-2">
                                                <h5 class="price">円 {{ number_format($p->price) }} / 月</h5>
                                            </div>
                                            <ul>
                                                <li><i class="bi bi-check-circle"></i>{{ $p->limit }}商品まで登録</li>
                                            </ul>
                                            <div class="card-footer">
                                                <button class="btn btn-primary btn-block" onclick="pay({{ $p->id }}, {{ $p->price }})">選択</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{asset('assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/extensions/toastify-js/src/toastify.js')}}"></script>

    <!-- Stripe -->
    <script src="https://js.stripe.com/v2/"></script>
    <script src="https://js.stripe.com/v3/"></script>
    
    <script type="text/javascript">
        var totalPrice = 0;
        var planId = 1;
        var userEmail = '{{ Auth::user()->email }}';
        
        //Stripe api key
        var stripe = Stripe(
            'pk_test_51Lg5KXHttMGhiRVpBcJf9zESHELiLWfty1vOI28vNpAcODHszakmiJGWAvUYX5yBne9U9VZF8dw8xHTd3OaNBZct00hsTPAQl3'
        );
        
        var headerParams = {
            'Authorization': 'bearer sk_test_51Lg5KXHttMGhiRVpCz6TKTdxHlUM4CeIcdGyGRDM3iDGvhppuqUlplyNyHAiEU51Ih5oxfDDATlcVMoxTuGIT6xG00s585N5nV '
        };
        
        // Stripe-Pay
        const pay = (id, price) => {
            if (!window.confirm('本当にお支払いを開始しますか？')) {
                return window.location.href = '{{ route("register") }}';
            }

            totalPrice = price;
            planId = id;
        
            $.ajax({
                type: "GET",
                headers: headerParams,
                url: "https://api.stripe.com/v1/customers",
                data: {
                    email: userEmail
                },
                beforeSend: function () {
                    console.log(headerParams);
                    // toastr.info('カスタマー情報を確認中です。しばらくお待ちください。');
                },
                success: function (response) {
                    console.log(response);
                    if (response.data.length == 0) {
                        $.ajax({
                            type: "POST",
                            headers: headerParams,
                            url: "https://api.stripe.com/v1/customers",
                            data: {
                                email: userEmail
                            },
                            success: function (response) {
                                console.log("update Customer>>>>>>>>0", response);
                                customer = response;
                                stripeResponseHandler();
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            headers: headerParams,
                            url: "https://api.stripe.com/v1/customers/" + response.data[0].id,
                            data: {
                                email: userEmail
                            },
                            success: function (response) {
                                console.log("update customer>>>>>>>>1", response);
                                // toastr.success('カスタマー情報を確認しました。');
                                customer = response;
                                
                                stripeResponseHandler();
                            }
                        });
                    }
                }
            });
        };
        
        const stripeResponseHandler = () => {
            if (userEmail == '') {
                window.location.href = '{{ route("register") }}'
            }

            var data = {
                "payment_method_types[]": "card",
                line_items: [{
                    price_data: {
                        currency: "JPY",
                        unit_amount: Math.ceil(totalPrice * 1.1),
                        product_data: {
                            name: "test product",
                        },
                    },
                    quantity: 1,
                }],
                "customer": customer.id,
        
                mode: 'payment',
                allow_promotion_codes: false,
                billing_address_collection: "required",
                success_url: '{{ url("plan/select/") }}' + planId,
                cancel_url: '{{ route("register") }}',
            }
            $.ajax({
                type: "POST",
                headers: headerParams,
                url: "https://api.stripe.com/v1/checkout/sessions",
                data: data,
                success: function (session) {
                    // toastr.success('決済ページへ移動します。');
                    console.log("create session>>>>>>>>>", session);
                    return stripe.redirectToCheckout({
                        sessionId: session.id,
                    });
                },
                error: (err) => {
                    console.log(err);
                }
            });
        }
    </script>
</body>

</html>