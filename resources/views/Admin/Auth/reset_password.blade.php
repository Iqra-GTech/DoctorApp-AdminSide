<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Forgot Password - {{env('ADMIN_APP_TITLE')}}</title>
    <script defer data-api="/stats/api/event" data-domain="preview.tabler.io" src="/stats/js/script.js"></script>
    <meta name="msapplication-TileColor" content=""/>
    <meta name="theme-color" content=""/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <link rel="icon" href="{{url('/')}}/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{url('/')}}/favicon.ico" type="image/x-icon"/>
    <meta name="description" content="Tabler comes with tons of well-designed components and features. Start your adventure with Tabler and make your dashboard great again. For free!"/>
    <meta name="canonical" content="https://preview.tabler.io/forgot-password.html">
    <meta name="twitter:image:src" content="https://preview.tabler.io/static/og.png">
    <meta name="twitter:site" content="@tabler_ui">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Tabler: Premium and Open Source dashboard template with responsive and high quality UI.">
    <meta name="twitter:description" content="Tabler comes with tons of well-designed components and features. Start your adventure with Tabler and make your dashboard great again. For free!">
    <meta property="og:image" content="https://preview.tabler.io/static/og.png">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="640">
    <meta property="og:site_name" content="Tabler">
    <meta property="og:type" content="object">
    <meta property="og:title" content="Tabler: Premium and Open Source dashboard template with responsive and high quality UI.">
    <meta property="og:url" content="https://preview.tabler.io/static/og.png">
    <meta property="og:description" content="Tabler comes with tons of well-designed components and features. Start your adventure with Tabler and make your dashboard great again. For free!">
    <!-- CSS files -->
    <link href="{{url('/')}}/AdminAssets/dist/css/tabler.min.css?1685973381" rel="stylesheet"/>
    <link href="{{url('/')}}/AdminAssets/dist/css/tabler-flags.min.css?1685973381" rel="stylesheet"/>
    <link href="{{url('/')}}/AdminAssets/dist/css/tabler-payments.min.css?1685973381" rel="stylesheet"/>
    <link href="{{url('/')}}/AdminAssets/dist/css/tabler-vendors.min.css?1685973381" rel="stylesheet"/>
    <link href="{{url('/')}}/AdminAssets/dist/css/demo.min.css?1685973381" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
      .full_loader
      {
            background: url('/loader.gif');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size: 50px;
            z-index: 1000;
            position: absolute;
            min-width: 100vw;
            min-height: 100vh;
            overflow: hidden;
            max-width: 100vw;
            max-height: 100vh;
            background-color: white;
      }
      
      .navbar-brand-image
      {
          height: 4rem;
      }
      
    </style>
  </head>
  
  <body  class=" d-flex flex-column">
  <div class="full_loader" >
    </div>
    <script src="{{url('/')}}/AdminAssets/dist/js/demo-theme.min.js?1685973381"></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark">
            <img src="{{url('/logo.png')}}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
          </a>
        </div>
        <form class="card card-md" action="./" id="reset_password_form" method="get" autocomplete="off" novalidate>
        <input type="hidden" name="token" value="{{ request('token') }}">  
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Reset Password</h2>
            <p class="text-secondary mb-4">Please check your email. Enter otp and new password.</p>
            <div class="mb-3">
              <label class="form-label">OTP Code</label>
              <input type="text" class="form-control code" name="code" placeholder="OTP Code">
            </div>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" class="form-control" placeholder="Enter your new password" name="password">
            </div>
            <div class="form-footer">
              <button type="submit"  class="btn btn-primary w-100 submit">Reset Password</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{{url('/')}}/AdminAssets/dist/js/tabler.min.js?1685973381" defer></script>
    <script src="{{url('/')}}/AdminAssets/dist/js/demo.min.js?1685973381" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>
    
    <script>

function loader(show) {
    
  
          if(show == true)
        {
          $('.full_loader').removeClass('d-none');
        }
        else
        {
          $('.full_loader').addClass('d-none');
        }


  }


  $(document).ready(function() {

    $('.full_loader').addClass('d-none');

$('#reset_password_form').on('submit', function(e) {
    e.preventDefault();


  
    loader(true);
    
    $.ajax({
                headers: {
                    "Accept": "application/json"
                },
                type: "POST",
                url: "{{url('api/forget-password-change')}}",
                data: $(this).serialize(),
                success: function(response) {
                  loader(false);
                    toastr.success(response.message);
                    window.location.href = "/";
                },
                error: function(response) {
                  loader(false);
                  if (response.status == 422) {
                  var errors = response.responseJSON.data;                    
                  $.each(errors, function(field, messages) {
                        error_msg = messages[0]; 
                        toastr.error(error_msg);
                  });
                  }
          else  if (response.status == 500) {
              toastr.error("Something went wrong")
            }
            else
            {
              toastr.error(response.responseJSON.message)
            }
                                          }
            });
});


  });
</script>

  </body>
</html>
