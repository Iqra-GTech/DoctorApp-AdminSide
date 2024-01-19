<?php echo $__env->make('Admin.Includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
            Sponsers List
            </h2>
         </div>
         <div class="col-1">
            <h2 class="page-title">
               <?php if(in_array('Create sponsers',Session::get('permission_list'))): ?>
            <button type="button" class="btn btn-primary rounded" style="margin-left:20px !important"onclick="create();">Create</button>
            <?php endif; ?>
         </h2>
         </div>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">
      <div class="row row-cards">
      <div class="col-12">

    <table id="table_id" class="display table table-striped table-bordered">
        <thead>
            <tr>
               <th>Sr.</th>
               <th>Logo</th>
               <th>Name</th>
               <th>Priority</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


<!--createModal-->
<div class="modal fade" id="createModal" tabindex="-1"  sponser="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog"  sponser="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Create</h5>
      </div>
      <div class="modal-body">
      <div class="row">
         <div class="col-12">
             <form id="create_form">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Name <span class="text-primary">*</span></label>
                           <input type="text" class="form-control create_fields" name="name" placeholder="Name" value="">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Logo <span class="text-primary">*</span></label>
                           <input type="file" class="form-control create_fields" name="logo" placeholder="Logo" value="">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Priority <span class="text-primary">*</span></label>
                           <input type="number" class="form-control create_fields" name="priority" placeholder="Priority" value="">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Link <span class="text-primary">*</span></label>
                           <input type="text" class="form-control create_fields" name="link" placeholder="Link" value="">
                        </div>
                     </div>
                  </div>
               </div>


            </form>
         </div>
      </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#createModal').modal('hide');" >Close</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="$('#create_form').submit();" >Create</button>
      </div>
    </div>
  </div>
</div>
<!--end createModal-->


<!--editModal-->
<div class="modal fade" id="editModal" tabindex="-1"  sponser="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog"  sponser="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Create</h5>
      </div>
      <div class="modal-body">
      <div class="row">
         <div class="col-12">
            <form id="edit_form">
            <?php echo method_field('PUT'); ?>
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Name <span class="text-primary">*</span></label>
                           <input type="text" class="form-control edit_name_field" name="name" placeholder="Name" value="">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Priority <span class="text-primary">*</span></label>
                           <input type="number" class="form-control edit_priority_field" name="priority" placeholder="Priority" value="" >
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Link <span class="text-primary">*</span></label>
                           <input type="text" class="form-control edit_link" name="link" placeholder="Link" value="" >
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Logo <span class="text-primary">*</span></label>
                           <input type="file" class="form-control edit_logo_field" name="logo" placeholder="Logo" value="">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <img src="" width="100" height="100"  class="edit_logo_pic">
                           </div>
                           </div>
                           </div>
               </div>
               <input type="hidden"  name="edit_id"  class="edit_id" value="">

            </form>
         </div>
      </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#editModal').modal('hide');" >Close</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="update();">Edit</button>
      </div>
    </div>
  </div>
</div>
<!--end editModal-->


      </div>
   </div>


</div>


<?php echo $__env->make('Admin.Includes.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>

   var _DataTable = "";

   function create()
   {

    $('#createModal').modal('show');

   }

      $("#create_form").on("submit", function(e) {

         e.preventDefault();

         loader(true);

         $.ajax({
         headers: {
         "Accept": "application/json",
         "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
         },
         url: "<?php echo e(url('api/sponsers')); ?>",
         type: 'POST',
         data: new FormData(this),
         processData: false,
         contentType: false,
         success: function(response) {
         toastr.success(response.message);
         fetch_all_data();
         loader(false);
         $('#createModal').modal('hide');

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


   // function fetch_all_data()
   // {

   //       loader(true);
   //       let _DataTable = new DataTable('#table_id');
   //       _DataTable.clear().draw();

   //          $.ajax({
   //             headers: {
   //                   "Accept": "application/json",
   //                   "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
   //             },
   //             type: "GET",
   //             url: "<?php echo e(url('api/sponsers')); ?>",
   //             success: function(response) {
   //                var  sponsers = response.data.sponsers;

   //                   if(sponsers.length > 0){

   //                      for(var i = 0; i< sponsers.length; i++){

   //                         _DataTable.row.add([
   //                               i+1,
   //                               `<img src="${sponsers[i]['logo']}" style="width:40px;height:40px; border:1px solid transparent;border-radius:50%;">`,
   //                               sponsers[i]['name'],
   //                               sponsers[i]['priority'],
   //                               `<div class=" text-dark dropdown">
   //                                  <a class="text-dark" href="#navbar-users" data-bs-toggle="dropdown" data-bs-auto-close="outside"  sponser="button" aria-expanded="false" >
   //                                     <span class="d-inline-block">
   //                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
   //                                           <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
   //                                        </svg>
   //                                     </span>
   //                                  </a>
   //                                  <div class="dropdown-menu">
   //                                     <a class="dropdown-item"  sponser="button" onclick="_edit('${sponsers[i]['id']}');" >Edit</a>
   //                                     <a class="dropdown-item"  sponser="button" onclick="_delete('${sponsers[i]['id']}');" >Delete</a>
   //                                  </div>
   //                               </div>`
   //                               ] ).draw();
   //                      }


   //                }

   //                loader(false);

   //             },
   //             error: function(response) {
   //              loader(false);
   //                   if (response.status == 500) {
   //                      toastr.error("Something went wrong")
   //                   } else {
   //                      toastr.error(response.responseJSON.message)
   //                   }
   //             }
   //          });

   // }


   function fetch_all_data() {

   $(document).ready(function() {

   if(_DataTable != "" ){
   _DataTable.clear().destroy();
   }

   _DataTable = $('#table_id').DataTable({
         "serverSide": true,
         "ordering": false,
         "searching": false,

         "ajax": {
            headers: {
               "Accept": "application/json",
               "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
            },
            "type": "GET",
            "url": "<?php echo e(url('api/sponsers')); ?>",
            "dataSrc": "data.sponsers",
            "data": function(d) {
               var filter = [
                     {
                        key: 'name',
                        value: $('#filter_name').val() ? $('#filter_name').val() : ""
                     },
                     {
                        key: 'priority',
                        value: $('#filter_priority').val() ? $('#filter_priority').val() : ""
                     },
               ];
               d.filter = filter
            }
         },
         "columns": [
            {
               "data": null,
               "render": function(data, type, row, meta) {
                     var pageInfo = $('#table_id').DataTable().page.info();
                     var rowNumber = pageInfo.start + meta.row + 1;
                     return rowNumber;
               }
            },
            {
               "data": "logo",
               "render": function(data, type, row) {

                return  `<img src="${row.logo}" style="width:40px;height:40px; border:1px solid transparent;border-radius:50%;">`;

               }
            },
            {
               "data": "name"
            },
            {
               "data": "priority"
            },
            {
               "data": "null",
               "render": function(data, type, row) {
                     return `
                  <div class="text-dark dropdown">
                        <a class="text-dark" href="#navbar-users" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                           <span class="d-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                              </svg>
                           </span>
                        </a>
                        <div class="dropdown-menu">
                            <?php if(in_array('Edit sponsers',Session::get('permission_list'))): ?>
                           <a class="dropdown-item" role="button" onclick="_edit('${row.id}');">Edit</a>
                           <?php endif; ?>

                           <?php if(in_array('Delete sponsers',Session::get('permission_list'))): ?>
                           <a class="dropdown-item" role="button" onclick="_delete('${row.id}');">Delete</a>
                           <?php endif; ?>
                        </div>
                  </div>`;
               }
            }

         ]
   });

   $('#filter_button').on('click', function() {
         _DataTable.ajax.reload();
   });

   $('#filter_input_clear').on('click', function() {
         $('thead th input').val('');
         $('thead th select').val('');
         _DataTable.ajax.reload();
   });

});

}


   $(document).ready(function () {

    fetch_all_data();

      $('#table_id thead th').each(function() {

         var title = $(this).text();

         if (title != 'Action' && title != 'Sr.' && title != 'Logo') {
            $(this).append('<br><input class="form-control form-control-sm filter_inputs" type="text" id="filter_' + title.toLowerCase().replace(/ /g, "_") + '" placeholder="Search ' + title + '" />');
         }

         if (title == 'Action') {
            $(this).append('<br><button id="filter_button" class="btn btn-secondary btn-sm">Search</button> <button id="filter_input_clear" class="btn btn-secondary btn-sm">X</button>');
         }

         if (title == 'Sr.' || title == 'Logo') {
            $(this).append('<br><button style="visibility: hidden;" >hidden</button>');
         }

      });



   });

   function  _delete(id)
   {

        swal({
                title: "Error",
                text: "Are you sure you want to delete this?",
                icon: "error",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    loader(true);
                    $.ajax({
                                    headers: {
                                        "Accept": "application/json",
                                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                                    },
                                    type: "DELETE",
                                    url: "<?php echo e(url('/api/sponsers')); ?>/"+id,
                                    data: {id : id},
                                    success: function(response) {
                                    toastr.success(response.message);
                                    fetch_all_data();
                                    loader(false);


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

        });


   }

   function update()
   {

    $.ajax({
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                    },
                    url: "<?php echo e(url('api/sponsers')); ?>/"+$('.edit_id').val(),
                    type: "POST",
                     data: new FormData($('#edit_form')[0]),
                     processData: false,
                     contentType: false,
                    success: function(response) {
                      toastr.success(response.message);
                      fetch_all_data();
                      loader(false);
                      $('#editModal').modal('hide');

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

   function _edit(id)
   {
        loader(true);

        $.ajax({
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                    },
                    type: "GET",
                    url: "<?php echo e(url('/api/sponsers')); ?>/"+id,
                    success: function(response) {

                      var   sponser = response.data.sponser;
                      $('.edit_name_field').val(sponser.name);
                      $('.edit_priority_field').val(sponser.priority);
                      $('.edit_link').val(sponser.link);
                      $('.edit_logo_field').val('');
                      $('.edit_logo_pic').attr('src', sponser.logo)
                      $('.edit_id').val(sponser.id);
                      console.log( sponser);
                      loader(false);
                      $('#editModal').modal('show');

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


<?php echo $__env->make('Admin.Includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH D:\Gtech\admin side\TheDoctorApp\resources\views/Admin/Sponsers/list.blade.php ENDPATH**/ ?>