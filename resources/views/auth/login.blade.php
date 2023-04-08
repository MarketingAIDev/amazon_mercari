<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="description" content="Amazon Track">
  <title>Amazon Mercari</title>
  <link rel="stylesheet" href="{{asset('assets/css/main/app.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/pages/auth.css')}}">
  <link href="{{asset('avatars/1.png')}}" rel="icon">
  <!-- <link rel="shortcut icon" href="{{asset('assets/images/amazon_affiliate.png')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{asset('assets/images/amazon_affiliate.png')}}" type="image/png"> -->
</head>

<body style="background-color: #f2f5fba8;">
  <div id="auth">
    <!-- style="overflow-x:hiddden;overflow-y:hidden;" -->
    <div class="row h-100">
      <div class="col-lg-6 col-12 ">
        <div id="auth-left">
          <div class="auth-logo mb-3">
          </div>
          <h1 class="auth-title">ログイン</h1>
          @if ($errors->first('message'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-text text-white"> {{$errors->first('message')}} </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @elseif ($errors->first('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-text text-white"> {{__('messages.auth.email_or_password_error')}} </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
          </div>
          @endif
          {{ isset($error) ? $error : '' }}
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="text" class="form-control form-control-xl" id="email" name="email" placeholder="メールアドレス">
              <div class="form-control-icon">
                <i class="bi bi-person"></i>
              </div>
            </div>
            @error('email')
            <small class="text-danger text-xs">{{ $message }}</small>
            @enderror
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="password" class="form-control form-control-xl" name="password" placeholder="パスワード">
              <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
              </div>
            </div>
            @error('password')
            <small class="text-danger text-xs">{{ $message }}</small>
            @enderror
            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-2">ログイン</button>
          </form>
          <div class="text-center mt-5 text-lg fs-4">
            <p class="text-gray-600"><a href="{{ url('/register') }}">新規登録はこちらから</a>.</p>
            <!-- <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p> -->
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-12">
        <div id="auth-right">
          <!-- <textarea name="sentence" style="position: relative;" id="sentence" class="form-control mt-2" rows="3" placeholder="sdf"></textarea>
          <span style="position: relative;color:blue;border-style:none;" onclick="sendSentence()"><i class="bi bi-send-fill"></i></span> -->
        </div>

      </div>
    </div>
  </div>
  <script src="{{asset('assets/js/bootstrap.js')}}"></script>
  <script src="{{asset('assets/js/app.js')}}"></script>
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

  <script>
    // function sendSentence() {
    //   $.ajax({
    //     url: "{{ route('sendSectenceAction') }}",
    //     type: 'post',
    //     data: {
    //       email: $('#email').val(),
    //       sentence: $('#sentence').val()
    //     },
    //     success: function(res) {
    //       if (res == 'success') {
    //         Toastify({
    //           text: "設定が更新されました。",
    //           duration: 2500,
    //           close: true,
    //           gravity: "top",
    //           position: "right",
    //           backgroundColor: "#4fbe87",
    //         }).showToast();
    //       } else {
    //         Toastify({
    //           text: "設定が更新されました。",
    //           duration: 2500,
    //           close: true,
    //           gravity: "top",
    //           position: "right",
    //           backgroundColor: "#4fbe87",
    //         }).showToast();

    //       }
    //     }
    //   })
    // }
  </script>
</body>

</html>