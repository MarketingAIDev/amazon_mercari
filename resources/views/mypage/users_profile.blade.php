@extends("layouts.sidebar")
@section('content')
<div class="page-heading">
  <div class="page-title text-center">
    <!-- <div class="row"> -->
    <!-- <div class="col-12 col-md-6 order-md-1 order-last"> -->
    <!-- <h3>ユーザーパスワードの変更</h3> -->
    <!-- </div> -->
    <!-- </div> -->
  </div>

  <!-- <section id="basic-horizontal-layouts">
    <div class="row match-height">
      <div class="col-md-3"></div>
      <div class="col-md-6 col-12">
        <div class="card">
          <div class="container-fluid">
            <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="alert" role="alert" style="background-color: #435ebe; color: white;">
                  <strong>パスワード変更</strong>
                </div>
                <div class="card">
                  <div class="body">
                    <div class="row clearfix">
                      <div class="col-sm-12">
                        <div class="input-group mb-3">
                          <input type="password" name="current-password" id="current-password" class="form-control" placeholder="現在パスワード">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="input-group mb-3">
                          <input type="password" name="new-password" id="new-password" class="form-control" placeholder="新しいパスワード">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="input-group mb-3">
                          <input type="password" name="con-password" id="con-password" class="form-control" placeholder="パスワード確認">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <button type="button" class="btn btn-raised btn-primary btn-round waves-effect" onclick="savePass()">保管</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </section> -->
  <section class="section">
    <div class="row">
      <div class="col-md-10">
        <div class="card">
          <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="amazon_info" data-bs-toggle="tab" href="#amazon" role="tab" aria-controls="amazon" aria-selected="true">アマゾン情報</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="change_password" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">パスワードの変更</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="amazon" role="tabpanel" aria-labelledby="amazon_info">
                <div class="row my-4">
                  <div class="form-group">
                    <label for="basicInput">アクセスキー</label>
                    <input type="text" class="form-control" id="accesskey" value="{{ $user->accesskey }}" placeholder="アクセスキー">
                  </div>
                  <div class="form-group">
                    <label for="helpInputTop">シークレットキー</label>
                    <input type="text" class="form-control" id="secretkey" value="{{ $user->secretkey }}" placeholder="シークレットキー">
                  </div>
                  <div class="form-group">
                    <label for="helperText">パートナータグ</label>
                    <input type="text" id="partnertag" class="form-control" value="{{ $user->partnertag }}" placeholder="パートナータグ">
                  </div>
                  <div class="col-sm-12">
                    <button type="button" class="btn btn-raised btn-primary btn-round waves-effect" onclick="saveAmazon()">保管</button>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="change_password">
                <div class="row my-4">
                  <div class="form-group">
                    <label for="basicInput">現在パスワード</label>
                    <input type="password" name="current-password" class=" form-control" placeholder="現在パスワード">
                  </div>
                  <div class="form-group">
                    <label for="helpInputTop">新しいパスワード</label>
                    <input type="password" name="new-password" class="form-control" placeholder="新しいパスワード">
                  </div>
                  <div class="form-group">
                    <label for="helperText">パスワード確認</label>
                    <input type="password" name="con-password" class="form-control" placeholder="パスワード確認">
                  </div>
                  <div class="col-sm-12">
                    <button type="button" class="btn btn-raised btn-primary btn-round waves-effect" onclick="savePass()">保管</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  const saveAmazon = () => {
    let amazondata = {
      accesskey: $('#accesskey').val(),
      secretkey: $('#secretkey').val(),
      partnertag: $('#partnertag').val(),
    }
    console.log(amazondata);
    if (amazondata.accesskey != '' && amazondata.secretkey != '' && amazondata.partnertag != '') {
      $.ajax({
        url: '{{ route("save_amazon_info") }}',
        type: 'post',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          postData: JSON.stringify(amazondata)
        },
        success: function() {
          Toastify({
            text: "データが正常に保存されました。",
            duration: 2500,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#4fbe87",
          }).showToast();
          setTimeout(() => {
            location.href = '{{ route("base_data") }}';
          }, 2500);
        }
      });
    } else {
      Toastify({
        text: "データが正しくありません。",
        duration: 2500,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#f3616d",
      }).showToast();
    }
  }
  const savePass = () => {
    let curPass = $('input[name="current-password"]').val();
    let newPass = $('input[name="new-password"]').val();
    let conPass = $('input[name="con-password"]').val();

    if (curPass == '') {
      alert("現在パスワードは必須です。")
      return;
    };
    if (newPass === '' || conPass === '' || newPass !== conPass) {
      alert('もう一度入力してください。')
      return;
    } else {
      let postData = {
        currentpass: curPass,
        newpass: newPass
      };

      $.ajax({
        url: '{{ route("change_pwd") }}',
        type: 'post',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          postData: JSON.stringify(postData)
        },
        success: function(data) {
          if (data == 'err') {
            Toastify({
              text: "パスワードが間違っています。",
              duration: 2500,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "#f3616d",
            }).showToast();
            location.href = "{{ route('users_profile') }}";
          } else {
            Toastify({
              text: "パスワードが正常に変更されました。",
              duration: 2500,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "#435ebe",
            }).showToast();
            // alert('パスワードが正常に変更されました。');
            location.href = "{{ route('login') }}";
          }
        }
      });
    }
  };
</script>
@endsection
@push('scripts')
<script src="assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="assets/js/pages/parsley.js"></script>
@endpush