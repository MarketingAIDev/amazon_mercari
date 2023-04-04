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
</head>

<body>
    <div id="auth" style="overflow-x: hidden;overflow-y:hidden;">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left" class="py-2">
                    <div class="auth-logo mb-3">
                    </div>
                    <h3 style="font-size: 3rem;">サインアップ</h3>
                    <form method="POST" action="{{ route('register') }}" role="form" novalidate>
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="text" name="family_name" class="form-control form-control-xl" placeholder="お名前" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="email" name="email" id="email" class="form-control form-control-xl" placeholder="メールアドレス" required>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>

                            </div>
                            <div class="invalid-feedback">有効なメールアドレスを入力してください。!</div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="password" name="password" class="form-control form-control-xl" placeholder="パスワード" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
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
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    
                </div>
            </div>
        </div>
    </div>

</body>

</html>