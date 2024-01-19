@include('Admin.Includes.header')
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
            General Settings
            </h2>
         </div>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">
      <div class="row row-cards">
         <div class="col-12">

         <form method="POST" action="{{route('Admin.general_settings.pages.update')}}" >
            @csrf
         <div class="row ">
            <div class="form-group col-6">
               <label class="form-label font-weight-bold" >About</label>
               <textarea class="form-control editor about" name="about" id="about" ></textarea>
            </div>

            <div class="form-group col-6">
               <label class="form-label font-weight-bold" >Privacy Policy</label>
               <textarea class="form-control editor privacy_policy" name="privacy_policy" id="privacy_policy" ></textarea>
            </div>


            <div class="form-group col-6 mt-3">
               <label class="form-label font-weight-bold" >Terms And Conditions</label>
               <textarea class="form-control editor terms_and_conditions" name="terms_and_conditions" id="terms_and_conditions" ></textarea>
            </div>
            
            <div class="col-12">
               <button type="submit" class="btn btn-primary rounded col-2 mt-4 ml-auto mr-auto  " >Update</button>
            </div>
         </div>
         </form>


         </div>
   </div>
</div>


@include('Admin.Includes.scripts')

<script>

$(document).ready(function() {

get_data();
});

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
          init_instance_callback : function(editor) {
            get_data()
          }
        }
        tinymce.init(options);   
      });

   function get_data()
   {
        loader(true);

        $.ajax({
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
                    },
                    type: "POST",
                    url: "{{url('api/get-general-settings')}}",
                    success: function(response) {                    
                    loader(false);
                    tinymce.get("about").setContent(response.data.general_settings.about);
                    tinymce.get("terms_and_conditions").setContent(response.data.general_settings.terms_and_conditions);
                    tinymce.get("privacy_policy").setContent(response.data.general_settings.privacy_policy);
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

   }



 


   </script>


@include('Admin.Includes.footer')
