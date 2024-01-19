<!doctype html>
<html lang="en">

<?php echo $__env->make('Admin.Includes.links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <body >
  <div class="full_loader" ></div>
    <script>


        $(document).ready(function() {
        $('.full_loader').addClass('d-none');
        });
    </script>
    <script src="<?php echo e(url('/')); ?>/Admin/dist/js/demo-theme.min.js?1685973381"></script>
    <div class="page">

    <?php echo $__env->make('Admin.Includes.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php /**PATH D:\Gtech\admin side\TheDoctorApp\resources\views/Admin/Includes/header.blade.php ENDPATH**/ ?>