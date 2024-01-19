@include('Admin.Includes.header')
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
            Create Page
            </h2>
         </div>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">
      <div class="row row-cards">
         <div class="col-12">

         <form method="POST" class="page_form" action="{{route('Admin.general_settings.pages.store')}}"   enctype="multipart/form-data" >
          @csrf
         <div class="row ">
         <div class="form-group col-6">
         <label class="form-label font-weight-bold" >Name</label>
            <input type="text" name="name" class="form-control name">
            </div>
            <div class="form-group col-6">
            <label class="form-label font-weight-bold" >logo</label>
            <input type="file" name="logo" class="form-control">
            </div>
            <div class="form-group col-12">
            <label class="form-label font-weight-bold" >Role</label>
            <select class="form-control role" name="role"  >
               <option value="">Select Role</option>
               @php
               $filter_roles = DB::table('roles')->where('del', '0')->get();
               @endphp
               <option value="0" >All</option>
               @foreach($filter_roles as $filter_role)
               @if($filter_role->id != '1')
               <option value="{{$filter_role->id}}" >{{$filter_role->name}}</option>
               @endif
               @endforeach
            </select>
            </div>
      
            <div class="form-group col-12 mt-3">
               <label class="form-label font-weight-bold" >Description</label>
               <textarea class="form-control editor description" name="description" id="description" ></textarea>
            </div>
            
            <div class="col-12">
               <button type="submit" class="btn btn-primary rounded col-2 mt-4 ml-auto mr-auto  " >Create</button>
            </div>
         </div>
         </form>


         </div>
   </div>
</div>


@include('Admin.Includes.scripts')

<script>
   $(document).ready(function() {

let options = {
    selector: '.editor',
    height: 600,
    menubar: false,
    statusbar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }',
    init_instance_callback: function(editor) {

    }
}
tinymce.init(options);



   $(".page_form").on("submit", function(e) {

      e.preventDefault();

      loader(true);
      $.ajax({
         headers: {
               "Accept": "application/json"
         },
         type: "POST",
         url: "{{route('Admin.general_settings.pages.store')}}",
         data: new FormData(this),
         processData: false,
         contentType: false,
         success: function(response) {
               loader(false);
               toastr.success(response.message);

               window.location.href = "{{url('/general-settings/pages/list')}}";




         },
         error: function(response) {
               loader(false);
               if (response.status == 422) {
                  var errors = response.responseJSON.data;
                  $.each(errors, function(field, messages) {
                     error_msg = messages[0];
                     toastr.error(error_msg);
                  });
               } else if (response.status == 500) {
                  toastr.error("Something went wrong")
               } else {
                  toastr.error(response.responseJSON.message)
               }
         }
      });

   });

});


</script>


@include('Admin.Includes.footer')
