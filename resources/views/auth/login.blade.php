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
  <div id="auth" style="overflow-x:hiddden;overflow-y:hidden;">

    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left">
          <div class="auth-logo mb-3">
          </div>
          <h3 style="font-size: 3rem;">ログイン</h3>
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="text" class="form-control form-control-xl" name="email" placeholder="メールアドレス">
              <div class="form-control-icon">
                <i class="bi bi-person"></i>
              </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="password" class="form-control form-control-xl" name="password" placeholder="パスワード">
              <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">ログイン</button>
          </form>
          <div class="text-center mt-5 text-lg fs-4">
            <p class="text-gray-600"><a href="{{ route('register') }}">新規登録はこちらから</a>.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">
          <!-- <img src="{{asset('assets/images/index.png')}}" class="text-center" alt=""> -->
        </div>
      </div>
    </div>

  </div>
</body>

</html>