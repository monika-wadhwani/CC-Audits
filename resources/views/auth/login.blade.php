<!DOCTYPE html>
<html lang="en">

<head>
    <title>login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/design/css/style.css">

    <link rel="shortcut icon" href="{{url('')}}" />
    <style>
        .loader {
            content:'';
    display:block;
    position:absolute;
    left:48%;top:40%;
    width:40px;height:40px;
    border-style:solid;
    border-color:black;
    border-top-color:transparent;
    border-width: 4px;
    border-radius:50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
    }
#loaderBox{
    align-items: center;
}
    @keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    } 
    </style>
</head>

<body>
    <section class="loginSec">
        <div>
            {{-- <img src="" class="qaViewslogo" alt="logoqaviews" width="100"> --}}
        </div>
        <div class="loginFormBox w-100 bg-white">
            {{-- <img src="assets/design/img/porter-logo.png" height="25px" class="mx-auto d-block mb-4"> --}}
            <h1 class="hdngMain d-inline-block text-decoration-underline">Login</h1>
            <p class="subHdng mb-4">
                
            </p>
            <form  action="" method="post" autocomplete="off">
                @csrf
                <div class=" w-100 position-relative f-box">
                    <input class="form-control w-100" type="email" placeholder="Email" id="user_email"  name="email" autocomplete="off">
                    <span class="position-absolute">@</span>
                </div>
                <div class=" w-100 position-relative f-box">
                    <input class="form-control w-100" type="password" id="user_password" placeholder="Password" name="password">
                    <span class="position-absolute"><i class="fa fa-eye showPassword"></i></span>
                </div>
                <button class="mainBtnm w-100" type="button" onclick="loader()" id="kt_login_signin_submit">Login</button>
                {{-- <p>Powered By</p>
                <img src="assets/design/img/QD-logo.png" height="13px" class="mx-auto d-block"> --}}
            </form>
            <div class="loaderBox" id="loader_Box" style="display: none;">
                {{-- <lottie-player src="assets/design/img/loader.json" background="transparent" speed="1"
                    style="width: 200px;height: 200px;" loop autoplay></lottie-player> --}}
            </div>
        </div>

    </section>

    <script type="text/javascript">
         function loader() {
                $('#loader_Box').show().css("display", "flex");
                }
    </script>
    <script>
	
        var KTAppOptions = {
        "colors": {
        "state": {
        "brand": "#5d78ff",
        "dark": "#282a3c",
        "light": "#ffffff",
        "primary": "#5867dd",
        "success": "#34bfa3",
        "info": "#36a3f7",
        "warning": "#ffb822",
        "danger": "#fd3995"
        },
        "base": {
        "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
        "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
        }
        }
        };
        </script>
        
        <!-- end::Global Config -->
        
        <!--begin::Global Theme Bundle(used by all pages) -->
        <script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
        <script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <!--end::Global Theme Bundle -->
        
        <!--begin::Page Scripts(used by this page) -->
        <script src="assets/app/custom/login/login-general.js" type="text/javascript"></script>
        
        <!--end::Page Scripts -->
        
        <!--begin::Global App Bundle(used by all pages) -->
        <script src="assets/app/bundle/app.bundle.js" type="text/javascript"></script>
        
        <!--end::Global App Bundle -->
        </body>
        <script type="text/javascript">
            $("#forget_div").hide();
            function getDiv() {
                //alert("jdsfj");
                $('#forget_div').show();
            }
        </script>
</body>

</html>