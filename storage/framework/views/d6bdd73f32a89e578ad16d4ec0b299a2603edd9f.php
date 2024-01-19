<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Sign In - <?php echo e(env('ADMIN_APP_TITLE')); ?></title>
    <meta name="msapplication-TileColor" content=""/>
    <meta name="theme-color" content=""/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <link rel="icon" href="<?php echo e(url('/')); ?>/AdminAssets/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="<?php echo e(url('/')); ?>/AdminAssets/favicon.ico" type="image/x-icon"/>
    <meta name="description" content="Tabler comes with tons of well-designed components and features. Start your adventure with Tabler and make your dashboard great again. For free!"/>
    <meta name="canonical" content="https://preview.tabler.io/sign-in.html">
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
    <link href="<?php echo e(url('/')); ?>/AdminAssets/dist/css/tabler.min.css?1685973381" rel="stylesheet"/>
    <link href="<?php echo e(url('/')); ?>/AdminAssets/dist/css/tabler-flags.min.css?1685973381" rel="stylesheet"/>
    <link href="<?php echo e(url('/')); ?>/AdminAssets/dist/css/tabler-payments.min.css?1685973381" rel="stylesheet"/>
    <link href="<?php echo e(url('/')); ?>/AdminAssets/dist/css/tabler-vendors.min.css?1685973381" rel="stylesheet"/>
    <link href="<?php echo e(url('/')); ?>/AdminAssets/dist/css/demo.min.css?1685973381" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>
      @import  url('https://rsms.me/inter/inter.css');
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
  <div class="full_loader" ></div>
    <script src="<?php echo e(url('/')); ?>/AdminAssets/dist/js/demo-theme.min.js?1685973381"></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark">
            <img src="<?php echo e(url('/logo.png')); ?>" width="110" height="32" alt="Tabler" class="navbar-brand-image">
          </a>
        </div>
        <div class="card card-md">
          <div class="card-body">
            <h2 class="h2 text-center mb-4">Login to your account</h2>
            <form action="" method="post">
              <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control email" placeholder="your@email.com" autocomplete="off">
              </div>

              <div class="mb-2">
                <label class="form-label">
                  Password
                  <span class="form-label-description">
                    <a href="<?php echo e(url('/')); ?>/forgot-password">I forgot password</a>
                  </span>
                </label>
                <div class="input-group input-group-flat">
                  <input type="password" class="form-control password"  placeholder="Your password"  autocomplete="off">
                  <span class="input-group-text">
                    <a href="#" class="link-secondary" title="Show" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </a>
                  </span>
                </div>
              </div>
              
              <div class="d-flex mb-2">
                  
                <?php
                      
                      if(isset($_GET['admin']) == 'admin')
                      {
                          echo '<div class="form-check form-check-inline mt-3">
                            <input class="form-check-input" type="radio" checked name="role_id" id="role_id" value="1">
                            <label class="form-check-label" for="role_id">Admin</label>
                            </div>';
                      }
                      else
                      {
                        $roles = DB::table('roles')
                        ->where('del','0')
                        ->where('id','!=','1')
                        ->where('id','!=','2')
                        ->where('id','!=','3')
                        ->get();
                        
                        $i = 0;
                        
                        foreach($roles as $role)
                        {
                            if($i == 0)
                            {
                                $checked='checked';
                            }
                            else
                            {
                                $checked='';
                            }
                            
                            echo '<div class="form-check form-check-inline mt-3">
                            <input class="form-check-input" '.$checked.' type="radio"  name="role_id" id="role_id" value="'.$role->id.'">
                            <label class="form-check-label" for="role_id">'.$role->name.'</label>
                            </div>';
                            $i++;
                        }
                    
                      }
                ?>
    
    
              </div>

              <div class="form-footer">
                <button type="button" class="btn btn-primary w-100 sign-in">Sign in</button>
              </div>
            </form>
          </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="<?php echo e(url('/')); ?>/AdminAssets/dist/js/tabler.min.js?1685973381" defer></script>
    <script src="<?php echo e(url('/')); ?>/AdminAssets/dist/js/demo.min.js?1685973381" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>

    <script>
      
   

    
                $(document).ready(function() {

                  $('.full_loader').addClass('d-none');

                  $(".sign-in").on("click", function() {
              
                    loader(true);
                      $.ajax({
                          headers: {
                              "Accept": "application/json"
                          },
                          type: "POST",
                          url: "<?php echo e(url('api/login')); ?>",
                          data: {
                              "email": $('.email').val(),
                              "password": $('.password').val(),
                              "role_id": $('input[name="role_id"]:checked').val(),
                          },
                          success: function(response) {
                            loader(false);
                            console.log(response);
                              toastr.success(response.message);

                              if(response.data.user.verified == 0)
                              {
                                localStorage.setItem("email", response.data.user.email);
                                localStorage.setItem("role_id", response.data.user.role.id);
                                window.location.href = "<?php echo e(route('Admin.accountVerification')); ?>";
                              }
                              else if(response.data.user.has_profile == false)
                              {
                                window.location.href = "/add_user_profile";
                              } 
                              else
                              {
                                window.location.href = "<?php echo e(route('Admin.dashboard')); ?>";
                              }

                            


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

    </script>
  </body>
</html><?php /**PATH D:\Gtech\admin side\TheDoctorApp\resources\views/Admin/Auth/sign-in.blade.php ENDPATH**/ ?>