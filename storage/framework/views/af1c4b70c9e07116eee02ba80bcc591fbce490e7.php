<?php echo $__env->make('Admin.Includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
              Users List
            </h2>
         </div>
         <div class="col-1">
            <h2 class="page-title">
               <?php if(in_array('create users',Session::get('permission_list'))): ?>
            <button type="button" class="btn btn-primary rounded" style="margin-left:20px !important" onclick="create();">Create</button>
            <?php endif; ?>
            </h2>
         </div>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">
      <div class="row row-cards">
      <div class="col-12  table-container">

    <table id="table_id" class="display table table-striped table-bordered">
        <thead>
            <tr>
               <th>Sr.</th>
               <th style="width:74px;">ID</th>
               <th>Email</th>
               <th>Phone Number</th>
               <th>Status</th>
               <th>Role</th>
               <th>Verified</th>
               <th>Created By</th>
               <th>Created Date</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

<!--detailsModal-->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Detail</h5>
      </div>
      <div class="modal-body">

      <table id="detail_table" class="" style="width:100%;">
        <tbody>
        </tbody>
    </table>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#detailsModal').modal('hide');" >Close</button>
      </div>
    </div>
  </div>
</div>
<!--end detailsModal-->

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

let _DataTable = "";
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

   function _edit(email,role_id,user_id)
   {
        loader(true);

        $.ajax({
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
                    },
                    type: "POST",
                    url: "<?php echo e(url('/api/get-profile-fields')); ?>",
                    data: {email: email , role_id: role_id},
                    success: function(response) {
                     console.log(response);
                       var  fields = response.data.fields;

                      var html_field = ``;

                      for(i=0;i< fields.length;i++)
                      {
                        html_field += `<div class="form-group mt-2 col-6" >`
                        html_field += `<label class="mb-1" style="text-transform:capitalize;">${fields[i][1]}</label>`;
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
                      _DataTable.ajax.reload();
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
                  console.log(response)
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
                "url": "<?php echo e(url('api/users')); ?>",
                "dataSrc": "data.user",
                "data": function(d) {
                    var filter = [{
                            key: 'id',
                            value: $('#filter_id').val() ? $('#filter_id').val() : ""
                        },
                        {
                            key: 'email',
                            value: $('#filter_email').val() ? $('#filter_email').val() : ""
                        },
                        {
                            key: 'phone_number',
                            value: $('#filter_phone_number').val() ? $('#filter_phone_number').val() : ""
                        },
                        {
                            key: 'active',
                            value: $('#filter_status').val() ? $('#filter_status').val() : ""
                        },
                        {
                            key: 'role_id',
                            value: $('#filter_role').val() ? $('#filter_role').val() : ""
                        },
                        {
                            key: 'created_by',
                            value: $('#filter_created_by').val() ? $('#filter_created_by').val() : ""
                        },
                        {
                            key: 'verified',
                            value: $('#filter_verified').val() ? $('#filter_verified').val() : ""
                        },
                        {
                            key: 'created_at',
                            value: $('#filter_created_date').val() ? $('#filter_created_date').val() : ""
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
                    "data": "email"
                },
                {
                    "data": "phone_number"
                },
                {
                    "data": "active",
                    "render": function(data, type, row) {
                        return `
                        <?php if(in_array('change user status',Session::get('permission_list'))): ?>
                     <select class="form-control" id="" user_id="${row.id}" onchange="change_status(this);" >
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
                    "data": "verified",
                    "render": function(data, type, row) {
                        return data == '1' ? 'Verified' : 'Not Verified';
                    }
                },
                {
                    "data": "created_by",
                    "render": function(data, type, row) {
                        return data == '1' ? 'Admin' : 'User';
                    }
                },
                {
                    "data": "created_at",
                    "render": function(data, type, row) {
                        return formatDate(data);
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

                            <?php if(in_array('view users profile',Session::get('permission_list'))): ?>
                              <a class="dropdown-item" role="button" onclick="_profile('${row.id}','${row.role.id}');">Profile View</a>
                            <?php endif; ?>

                              <?php if(in_array('edit users profile',Session::get('permission_list'))): ?>
                              <a class="dropdown-item" role="button" onclick="_edit('${row.email}','${row.role.id}','${row.id}');">Edit Profile</a>
                              <?php endif; ?>

                              <?php if(in_array('delete users',Session::get('permission_list'))): ?>
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

   function _profile(user_id,role_id)
   {



      $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
               type: "POST",
               url: "<?php echo e(url('/api/get-profile-data')); ?>",
               data: {role_id : role_id, user_id : user_id},
               success: function(response) {

                  console.log(response);

                   var    user_profile =    response.data.user_profile



                 var html = '';


                     for(var i=0;i < user_profile.length; i++ )
                     {
                        html +=`<div class="row" ><div class="mt-1 col-6" style = "font-weight:bold !important;text-transform:capitalize;" >${user_profile[i]['option'].replace(/_/g, ' ')} :</div> <div class="col-6">${user_profile[i]['value']}</div></div>`;
                     }


                  $('#detail_table tbody').html(html);
                  $('#detailsModal').modal('show');





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

   function change_status(_this)
   {

        var value = $(_this).val();
        var user_id =  $(_this).attr('user_id');

            $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
               type: "PUT",
               url: "<?php echo e(url('/')); ?>"+"/api/users/"+user_id+"/update-status?active="+value,
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
                                    url: "<?php echo e(url('/api/users')); ?>/"+id,
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

      if (title != 'Action' && title != 'Sr.' && title != 'Role' && title != 'Status' && title != 'Created By' && title != 'Verified' && title != 'Created Date') {
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

      if (title == 'Verified') {
         $(this).append(
            `<br>
         <select class="form-control form-control-sm" id="filter_${title.toLowerCase().replace(/ /g,"_")}"   >
         <option value="">Select ${title}</option>
         <option value="1">Verified</option>
         <option value="0">Not Verified</option>
         </select>
         `
         );
      }

      if (title == 'Created By') {
         $(this).append(
            `<br>
         <select class="form-control form-control-sm" id="filter_${title.toLowerCase().replace(/ /g,"_")}"   >
         <option value="">Select ${title}</option>
         <option value="0">User</option>
         <option value="1">Admin</option>
         </select>
         `
         );
      }


      if (title == 'Created Date') {
         $(this).append(
            `<br>
         <input type="date"  class="form-control form-control-sm filter_inputs" id="filter_${title.toLowerCase().replace(/ /g,"_")}" value="" />
         `
         );

      }

      if (title == 'Sr.') {
         $(this).append('<br><button style="visibility: hidden;" >hidden</button>');
      }

      });



   });


   function formatDate(inputDate)
   {
      const date = new Date(inputDate);
      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear();
      return `${day <=9 ? '0'+day: day}-${month <=9 ? '0'+month: month}-${year}`;
   }






   </script>


<?php echo $__env->make('Admin.Includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH D:\Gtech\admin side\TheDoctorApp\resources\views/Admin/Users/list.blade.php ENDPATH**/ ?>