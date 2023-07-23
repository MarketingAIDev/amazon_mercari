<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Amazon Track">
    <title>Amazon Mercari</title>
    <link rel="stylesheet" href="assets/css/main/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <link href="{{asset('avatars/1.png')}}" rel="icon">
    <!-- <link rel="shortcut icon" href="{{asset('assets/images/amazon_affiliate.png')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{asset('assets/images/amazon_affiliate.png')}}" type="image/png"> -->
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-6 col-12">
                <div id="auth-left" class="py-2 mt-5">
                    <h3 class="auth-title">サインアップ</h3>
                    @if ($errors->first('message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white"> {{$errors->first('message')}} </span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}" role="form" novalidate>
                        @csrf
                        @error('family_name')
                        <small class="text-danger text-xs">{{ $message }}</small>
                        @enderror
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="text" name="family_name" class="form-control form-control-xl" placeholder="お名前" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        @error('email')
                        <small class="text-danger text-xs">{{ $message }}</small>
                        @enderror
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="email" name="email" class="form-control form-control-xl" placeholder="メールアドレス" required>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>

                            </div>
                            <div class="invalid-feedback">有効なメールアドレスを入力してください。!</div>
                        </div>
                        @error('password')
                        <small class="text-danger text-xs">{{ $message }}</small>
                        @enderror
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="password" name="password" class="form-control form-control-xl" placeholder="パスワード" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        @error('password_confirmation')
                        <small class="text-danger text-xs">{{ $message }}</small>
                        @enderror
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="password" name="password_confirmation" class="form-control form-control-xl" placeholder="パスワード確認" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-3">新規登録</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class='text-gray-600'><a href="{{ route('login') }}">アカウントをお持ちですか？</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="auth-right">
                    <!-- <img src="{{asset('avatars/login.png')}}" alt=""> -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>