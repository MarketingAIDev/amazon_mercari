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
  <!-- begin loading spinner -->
  <!-- <div class="loader-wrapper" id="loader-1" style="width: 100%; height: 100%; background: rgb(3 4 20); position:fixed; top: 0; left: 0; z-index: 10000;">
    <div id="loader"></div>
  </div> -->
  <div class="loader-wrapper" style="width: 100%;height: 100%;background: rgb(91 94 100 / 88%);position:fixed;top: 0;left: 0;z-index: 10000;" id="loader-4">
    <div id="loader">お</div>
    <div id="loader">待</div>
    <div id="loader">ち</div>
    <div id="loader">く</div>
    <div id="loader">だ</div>
    <div id="loader">さ</div>
    <div id="loader">い</div>
    <div id="loader">。</div>
    <div id="loader" class="progress_loader" style="top: 50%;left: 24%;font-size: 1.5rem;display: none;">
      <!-- <label for="file">Downloading progress:</label> -->
      <progress id="progress" value="0" max="100"> </progress>
    </div>
  </div>
  <!-- end loading spinner -->
  <div id="app">
    <div id="sidebar" class="active">
      <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
          <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
              <a href="{{route('base_data')}}"><img src="{{asset('avatars/234.png')}}" style="height:auto" alt="Logo" srcset=""></a>
            </div>
            <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                  <g transform="translate(-210 -1)">
                    <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                    <circle cx="220.5" cy="11.5" r="4"></circle>
                    <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                  </g>
                </g>
              </svg>
              <div class="form-check form-switch fs-6">
                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                <label class="form-check-label"></label>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
              </svg>
            </div>
            <div class="sidebar-toggler  x">
              <a class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
          </div>
        </div>
        <div class="sidebar-menu">
          <ul class="menu">
            <li <?php if (strpos(url()->current(), "base_")) echo 'class="sidebar-item active"';
                else echo 'class="sidebar-item"'; ?>>
              <a href="{{route('base_data')}}" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Amazon商品の管理</span>
              </a>
            </li>
            <li <?php if (strpos(url()->current(), "entry")) echo 'class="sidebar-item active"';
                else echo 'class="sidebar-item"'; ?>>
              <a href="{{route('entry_condition')}}" class='sidebar-link'>
                <i class="bi bi-life-preserver"></i>
                <span>出品データの管理</span>
              </a>
            </li>
            <li <?php if (strpos(url()->current(), "mercari_update")) echo 'class="sidebar-item active"';
                else echo 'class="sidebar-item"'; ?>>
              <a href="{{route('mercari_update')}}" class='sidebar-link'>
                <i class="bi bi-download"></i>
                <span>出品中の商品リスト</span>
              </a>
            </li>
            <li <?php if (strpos(url()->current(), "mercari_list")) echo 'class="sidebar-item active"';
                else echo 'class="sidebar-item"'; ?>>
              <a href="{{route('mercari_list')}}" class='sidebar-link'>
                <i class="bi bi-download"></i>
                <span>未出品商品リスト</span>
              </a>
            </li>
            <li <?php if (strpos(url()->current(), "etc")) echo 'class="sidebar-item active"';
                else echo 'class="sidebar-item"'; ?>>
              <a href="{{route('etc_function')}}" class='sidebar-link'>
                <i class="bi bi-download"></i>
                <span>在庫切れ商品リスト</span>
              </a>
            </li>
            <li <?php if (strpos(url()->current(), "users")) echo 'class="sidebar-item active has-sub"';
                else echo 'class="sidebar-item has-sub"'; ?>>
              <a href="" class='sidebar-link'>
                <i class="bi bi-person-bounding-box"></i>
                <span>{{Auth::user()->family_name}}</span>
              </a>
              <ul class="submenu ">
                @if ( Auth::user()->role == 'admin')
                <li class="submenu-item ">
                  <a href="{{route('users_admin_page')}}" class='sidebar-link'>
                    <i class="bi bi-person-circle"></i>
                    <span>管理者ページ</span>
                  </a>
                </li>
                @endif
                <li class="submenu-item ">
                  <a href="{{route('users_profile')}}" class='sidebar-link'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                      <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                    </svg>
                    <span>プロフィール</span>
                  </a>
                </li>
                <li class="submenu-item ">
                  <a href="{{route('logout')}}" class='sidebar-link'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                      <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z" />
                      <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708Z" />
                    </svg>
                    <span>ログアウト</span>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div id="main">
      <header class="col-2 mb-3">
        <a class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
      </header>
      @yield('content')
      <!-- <footer>
          <div class="footer clearfix mb-0 text-muted">
            <div class="float-start">
                <p>2023 &copy; MERCARI</p>
            </div>
            <div class="float-end">
                <p>寄稿者 : <span class="text-danger"><i class="bi bi-heart"></i></span> <a href="#">MERCARI</a></p>
            </div>
          </div>
        </footer> -->
    </div>
  </div>

  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{asset('assets/js/bootstrap.js')}}"></script>
  <script src="{{asset('assets/js/app.js')}}"></script>
  <script src="{{asset('assets/extensions/toastify-js/src/toastify.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      setTimeout(removeSpinner, 1100); //wait for page load PLUS time.
    });

    function removeSpinner() {
      $("#loader-4").fadeOut(50, function() { // fadeOut complete. Remove the loadingSpinner
        $("#loader-4").hide(); //makes page more lightweight 
      });
    }
  </script>
  @stack('scripts')
</body>

</html>