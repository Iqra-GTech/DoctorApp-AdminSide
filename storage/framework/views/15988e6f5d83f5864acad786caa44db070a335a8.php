<?php echo $__env->make('Admin.Includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
   .disabled_style{
      border-color: transparent;background: transparent;
   }
   </style>
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
            Modules List
            </h2>
         </div>

         <div class="col-1">
            <h2 class="page-title">
               <?php if(in_array('Create module',Session::get('permission_list'))): ?>
            <a class="btn btn-primary rounded" style="margin-left:20px !important" href="<?php echo e(route('Admin.modules.create')); ?>" >Create</a>
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
               <th>ID</th>
               <th>Name</th>
               <th>Table Name</th>
               <th>Status</th>
               <th>Role</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

<!--uploadCsvModal-->
<div class="modal fade" id="uploadCsvModal" tabindex="-1" role="dialog" aria-labelledby="uploadCsvModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadCsvModalLabel">Upload CSV</h5>
      </div>
      <div class="modal-body">
      <form id="csv_form">
         <div class="form-group mt-2 col-12">
            <label class="mb-1" style="text-transform:capitalize;">Upload CSV</label>
            <input type="file" class="form-control" id="upload_csv_field" onchange="csv_file_store();">
            <input type="hidden" class="form-control" id="table_name_field">
            <input type="hidden" class="form-control" id="module_id_field">
            <input type="hidden" class="form-control" id="role_id_field">
         </div>
      </form>
      <div id="table_container"></div>




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#uploadCsvModal').modal('hide');" >Close</button>
      </div>
    </div>
  </div>
</div>
<!--end uploadCsvModal-->


<!--createModal-->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label class="form-label">Email <span class="text-primary">*</span></label>
                           <input type="email" class="form-control" name="email" placeholder="Email" value="">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label class="form-label">Password <span class="text-primary">*</span></label>
                           <input type="text" class="form-control" name="password" placeholder="Password" value="">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label class="form-label">Password Confirmation <span class="text-primary">*</span></label>
                           <input type="text" class="form-control" name="password_confirmation" placeholder="Password Confirmation" value="">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label class="form-label">Phone Number <span class="text-primary">*</span></label>
                           <input type="text" class="form-control" placeholder="Phone Number" name="phone_number" value="">
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label class="form-label">Role <span class="text-primary">*</span></label>
                           <select class="form-control form-select role_id" name="role_id" value="">
                           </select>
                        </div>
                     </div>
                  </div>
               </div>
               <input type="hidden"  name="created_by"  value="1">
               <input type="checkbox" class="d-none" checked name="term_and_conditions" value="1">


            </form>
         </div>
      </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#createModal').modal('hide');" >Close</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="store();">Save</button>
      </div>
    </div>
  </div>
</div>
<!--end createModal-->


<!--editModal-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit</h5>
      </div>
      <div class="modal-body">
      <div class="row">
         <div class="col-12">
            <form id="edit_form">
               <div class="card-body">
                  <div class="row append_fields">


                  </div>
               </div>
               <input type="hidden"  name="role_id"  class="role_id" value="">
               <input type="hidden"  name="user_id"  class="user_id" value="">

            </form>
         </div>
      </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#editModal').modal('hide');" >Close</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="$('#edit_form').submit();">Save</button>
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

   function create_field_html(field)
   {
     var  html_field = ``;

      if(field[3] == 'text')
                  {
                     html_field += `
                     <input type="text" class="form-control" id="${field[2]}" name="${field[2]}"  value="${field[6]}"   placeholder="${field[1]}">
                     `;
                  }
                  if(field[3] == 'number')
                  {
                     html_field += `
                     <input type="number" class="form-control" id="${field[2]}" name="${field[2]}"  value="${field[6]}"   placeholder="${field[1]}">
                     `;
                  }
                  if(field[3] == 'email')
                  {
                     html_field += `
                     <input type="email" class="form-control" id="${field[2]}" name="${field[2]}"  value="${field[6]}"   placeholder="${field[1]}">
                     `;
                  }
                  else
                  if(field[3] == 'image')
                  {
                     html_field += `
                     <input type="file" class="form-control" id="${field[2]}" name="${field[2]}"   value="${field[6]}"  placeholder="${field[1]}">
                     `;
                  }
                  else
                  if(field[3] == 'datepicker')
                  {
                     html_field += `
                     <input type="date" class="form-control" id="${field[2]}" name="${field[2]}"   value="${field[6]}"  placeholder="${field[1]}">
                     `;
                  }
                  else
                  if(field[3] == 'radio')
                  {
                     value_list = field[5]

                     for(var i=0; i < value_list.length; i++)
                     {

                        html_field += `
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="${field[2]}"   value="${value_list[i].trim()}" ${ field[6] == value_list[i].trim() ? 'checked' : i=='0' ? 'checked' : '' }  >
                                 <label class="form-check-label" style="text-transform: capitalize;" >${value_list[i].trim()}</label>
                              </div>
                        `;
                     }
                  }
                  else
                  if(field[3] == 'checkbox')
                  {
                     value_list = field[5]

                     for(var i=0; i < value_list.length; i++)
                     {

                     html_field += `
                                 <div class="form-check">
                                 <input class="form-check-input" type="checkbox"  name="${field[2]}[]"  value="${value_list[i].trim()}" ${ field[6] == value_list[i].trim() ? 'checked' : '' }>
                                 <label class="form-check-label" style="text-transform: capitalize;" >${value_list[i].trim()}</label>
                                 </div>
                     `;

                     }
                  }
                  else
                  if(field[3] == 'dropdown')
                  {
                     value_list = field[5]

                     html_field += `<select class="form-control" style="text-transform: capitalize;" id="${field[2]}" name="${field[2]}"    >`;

                     for(var i=0; i < value_list.length; i++)
                     {
                        html_field +=`<option value="${value_list[i].trim()}" ${ field[6] == value_list[i].trim() ? 'selected' : '' } >${value_list[i].trim()}</option>`;
                     }

                     html_field +=`</select>`;
                  }
                  else
                  if(field[3] == 'textarea')
                  {
                     html_field += `
                     <textarea class="form-control"  id="${field[2]}" name="${field[2]}"    placeholder="${field[1]}">${field[6]}</textarea>
                     `;
                  }

                  return html_field;
   }

   function _edit(module_id)
   {
        loader(true);

        $.ajax({
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                    },
                    type: "POST",
                    url: "<?php echo e(url('/api/get-profile-fields')); ?>",
                    data: {module_id: module_id},
                    success: function(response) {
                       var  fields = response.data.fields;

                      var html_field = ``;

                      for(i=0;i< fields.length;i++)
                      {
                        html_field += `<div class="form-group mt-2 col-6" >`
                        html_field += `<label class="mb-1">${fields[i][1]}</label>`;
                        html_field +=  create_field_html(fields[i]);
                        html_field += `</div>`;
                      }


                     $('.append_fields').html(html_field);

                     $('#edit_form .role_id').val(role_id);
                     $('#edit_form .user_id').val(user_id);

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

   $("#edit_form").on("submit", function(e) {
      e.preventDefault();
      loader(true);
            $.ajax({
               headers: {
                  "Accept": "application/json",
                  "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
            url: "<?php echo e(url('/api/store-profile-fields')); ?>",
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
               toastr.success(response.message);
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

   });

   function store()
   {
        loader(true);

        $.ajax({
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                    },
                    type: "POST",
                    url: "<?php echo e(url('api/register')); ?>",
                    data: $("#create_form").serialize(),
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

   }

   function create()
   {




      $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
               type: "GET",
               url: "<?php echo e(url('/')); ?>"+"/api/roles",
               success: function(response) {
                  var roles = response.data.role;

                  var html = `<option value="">Select Role</option>`;
                  for(var i = 0; i<roles.length;i++){

                  html += `<option value="${roles[i]['id']}">${roles[i]['name']}</option>`;

                  }

                  $('#create_form .role_id').html(html);
                  $('#createModal').modal('show');


               },
               error: function(response) {
                     if (response.status == 500) {
                        toastr.error("Something went wrong")
                     } else {
                        toastr.error(response.responseJSON.message)
                     }
               }
            });


   }

   function fetch_all_data() {
    $(document).ready(function() {


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
                "url": "<?php echo e(url('api/module-managers')); ?>",
                "dataSrc": "data.module_manager",
                "data": function(d) {
                    var filter = [
                        {
                            key: 'id',
                            value: $('#filter_id').val() ? $('#filter_id').val() : ""
                        },
                        {
                            key: 'name',
                            value: $('#filter_name').val() ? $('#filter_name').val() : ""
                        },
                        {
                            key: 'table_name',
                            value: $('#filter_table_name').val() ? $('#filter_table_name').val() : ""
                        },
                        {
                            key: 'active',
                            value: $('#filter_status').val() ? $('#filter_status').val() : ""
                        },
                        {
                            key: 'role_id',
                            value: $('#filter_role').val() ? $('#filter_role').val() : ""
                        },
                    ];
                    d.filter = filter
                }
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        var pageInfo = $('#table_id').DataTable().page.info();
                        var rowNumber = pageInfo.start + meta.row + 1;
                        return rowNumber;
                    }
                },
                {
                    "data": "id"
                },
                {
                    "data": "name"
                },
                {
                    "data": "table_name"
                },
                {
                    "data": "active",
                    "render": function(data, type, row) {
                        return `
                        <?php if(in_array('change module status',Session::get('permission_list'))): ?>
                     <select class="form-control" id="" module_manager_id="${row.id}" onchange="change_status(this);" >
                        <option value="0"  ${row.active == '0' ? 'selected' : ''}  >Inactive</option>
                        <option value="1"  ${row.active == '1' ? 'selected' : ''}  >Active</option>
                     </select>
                     <?php endif; ?>`;
                    }
                },
                {
                    "data": "role.name",
                    "render": function(data, type, row) {
                        return data || "N/A";
                    }
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

                              <a class="dropdown-item  ${row.role.id == '1' ? '': 'd-none' }" role="button" onclick="_upload_csv('${row.id}','${row.table_name}','0','');" >Upload CSV</a>

                              <?php if(in_array('Edit module',Session::get('permission_list'))): ?>
                              <a class="dropdown-item" role="button" href="/modules/edit/${row.id}" >Edit</a>
                              <?php endif; ?>

                              <?php if(in_array('Delete module',Session::get('permission_list'))): ?>
                              <a class="dropdown-item" role="button" onclick="_delete('${row.id}');"  >Delete</a>
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


   function _upload_csv(module_id,table_name,from,filter)
   {
      $('#module_id_field').val(module_id);
      $('#table_name_field').val(table_name);

      $.ajax({
               headers: {
                  "Accept": "application/json",
                  "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
               type: "POST",
               url: "<?php echo e(url('/api/module-managers/upload-csv-get')); ?>",
               data: {module_id : module_id, table_name : table_name, from : from , filter : filter},
               success: function(html) {
                  $('#table_container').html(html);
                  $('#uploadCsvModal').modal('show');
               },
               error: function(response) {
                     if (response.status == 500) {
                        toastr.error("Something went wrong")
                     }
               }
            });

   }

   function change_status(_this)
   {

        var value = $(_this).val();
        var module_manager =  $(_this).attr('module_manager_id');

            $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
               type: "PUT",
               url: "<?php echo e(url('/')); ?>"+"/api/module-managers/"+module_manager+"/update-status?active="+value,
               success: function(response) {


                 toastr.success(response.message)


               },
               error: function(response) {
                     if (response.status == 500) {
                        toastr.error("Something went wrong")
                     } else {
                        toastr.error(response.responseJSON.message)
                     }
               }
            });

   }

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
                                    url: "<?php echo e(url('/api/module-managers')); ?>/"+id,
                                    data: {id : id},
                                    success: function(response) {
                                    toastr.success(response.message);
                                    _DataTable.ajax.reload();
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

   $(document).ready(function () {


      fetch_all_data();


         $('#table_id thead th').each(function() {

         var title = $(this).text();

         if (title != 'Action' && title != 'Sr.' && title != 'Role' && title != 'Status') {
            $(this).append('<br><input class="form-control form-control-sm filter_inputs" type="text" id="filter_' + title.toLowerCase().replace(/ /g, "_") + '" placeholder="Search ' + title + '" />');
         }

         if (title == 'Action') {
            $(this).append('<br><button id="filter_button" class="btn btn-secondary btn-sm">Search</button> <button id="filter_input_clear" class="btn btn-secondary btn-sm">X</button>');
         }

         if (title == 'Role') {
            $(this).append(
               `<br>
            <select class="form-control form-control-sm" id="filter_${title.toLowerCase().replace(/ /g,"_")}"   >
            <option value="">Select ${title}</option>
            <?php
            $filter_roles = DB::table('roles')->where('del', '0')->get();
            ?>
            <?php $__currentLoopData = $filter_roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter_role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($filter_role->id); ?>" ><?php echo e($filter_role->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            `
            );
         }

         if (title == 'Status') {
            $(this).append(
               `<br>
            <select class="form-control form-control-sm" id="filter_${title.toLowerCase().replace(/ /g,"_")}"   >
            <option value="">Select ${title}</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
            </select>
            `
            );
         }

         if (title == 'Sr.') {
            $(this).append('<br><button style="visibility: hidden;" >hidden</button>');
         }

         });

   });

   function csv_file_store()
   {
      var formData = new FormData();

            var allowedTypes = ['csv'];
      var file = $('#upload_csv_field')[0].files[0];
      var fileName = file.name;
      var fileType = fileName.split('.').pop().toLowerCase();

        if (allowedTypes.indexOf(fileType) === -1) {
         toastr.error("File Type must be csv")
            $('#upload_csv_field').val('');
           return 0;
        }


      formData.append('upload_csv_field', file);
      formData.append('table_name_field', $('#table_name_field').val());
      formData.append('module_id_field', $('#module_id_field').val());

      loader(true);

         $.ajax({
                     headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                     },
                     type: "POST",
                     url: "<?php echo e(url('/api/module-managers/upload-csv-store')); ?>",
                     data: formData,
                     processData: false,
                     contentType: false,
                     success: function(response) {

                        _upload_csv($('#module_id_field').val(),$('#table_name_field').val(),0,[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}]);
                        toastr.success(response.message);
                        $('#upload_csv_field').val('');
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


   function _edit_csv_data(_this)
   {
      $(_this).closest('tr').find('.edit_field').removeClass('disabled_style');
      $(_this).closest('tr').find('.edit_field').prop("disabled", false);
      $(_this).closest('tr').find('.edit_box').removeClass('d-none');
   }

   function edit_section_data(_this)
   {

      loader(true);
      var length = $(_this).closest('tr').find('.edit_field').length;
      var arr = [];
      arr[0] = ['id', $(_this).attr('data_id')];
      arr[1]= ['table_name', $(_this).attr('data_table')];
      for(var i=0; i < length;i++)
      {
         var ele = $(_this).closest('tr').find('.edit_field')[i];
         name  = ele.getAttribute('name');
         value =  ele.value;
         arr[i+2] = [name,value];

      }



      $.ajax({
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                    },
                    type: "POST",
                    url: "<?php echo e(url('/api/section-data/update')); ?>",
                    data: Object.fromEntries(arr),
                    success: function(response) {
                        $(_this).closest('tr').find('.edit_field').addClass('disabled_style');
                        $(_this).closest('tr').find('.edit_field').prop("disabled", true);
                        $(_this).closest('tr').find('.edit_box').addClass('d-none');
                        loader(false);
                        toastr.success(response.message);


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


   function  _delete_csv_data(id,table_name,module_id)
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
                                    type: "POST",
                                    url: "<?php echo e(url('/api/section-data/delete')); ?>",
                                    data: {id : id, table_name : table_name},
                                    success: function(response) {
                                    toastr.success(response.message);
                                    _upload_csv(module_id,table_name,0,[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}]);
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






   </script>


<?php echo $__env->make('Admin.Includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH D:\Gtech\admin side\TheDoctorApp\resources\views/Admin/Modules/list.blade.php ENDPATH**/ ?>