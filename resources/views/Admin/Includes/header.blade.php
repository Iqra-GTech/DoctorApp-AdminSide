<!doctype html>
<html lang="en">

@include('Admin.Includes.links')

  <body >
  <div class="full_loader" ></div>
    <script>


        $(document).ready(function() {
        $('.full_loader').addClass('d-none');
        });
    </script>
    <script src="{{url('/')}}/Admin/dist/js/demo-theme.min.js?1685973381"></script>
    <div class="page">

    @include('Admin.Includes.navbar')

