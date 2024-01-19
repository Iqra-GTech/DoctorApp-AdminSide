@include('Admin.Includes.header')
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
              Pages List
            </h2>
         </div>
         <div class="col-1">
            <h2 class="page-title">
               @if(in_array('Create page',Session::get('permission_list')))
            <a href="/general-settings/pages/create" class="btn btn-primary rounded" style="margin-left:20px !important" >Create</a>
            @endif
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
               <th>Name</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>       
        </tbody>
    </table>

      </div>
   </div>


</div>


@include('Admin.Includes.scripts')

<script>

let _DataTable = "";

   function fetch_all_data() {
    $(document).ready(function() {


        _DataTable = $('#table_id').DataTable({
            "serverSide": true,
            "ordering": false,
            "searching": false,

            "ajax": {
                headers: {
                    "Accept": "application/json",
                    "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
                },
                "type": "GET",
                "url": "{{url('api/pages')}}",
                "dataSrc": "data.pages",
                "data": function(d) {
                    var filter = [{
                            key: 'name',
                            value: $('#filter_name').val() ? $('#filter_name').val() : ""
                        },
                        {
                            key: 'status',
                            value: $('#filter_status').val() ? $('#filter_status').val() : ""
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
                    "data": "name"
                },
                {
                    "data": "status",
                    "render": function(data, type, row) {
                        return `
                     <select class="form-control" id="" user_id="${row.id}" onchange="change_status(this);" >
                        <option value="0"  ${row.status == '0' ? 'selected' : ''}  >Inactive</option>
                        <option value="1"  ${row.status == '1' ? 'selected' : ''}  >Active</option>
                     </select>`;
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
                              <a class="dropdown-item" role="button" href="{{Url('/general-settings/pages/edit')}}/${row.id}" >Edit</a>
                              <a class="dropdown-item" role="button" onclick="_delete('${row.id}');">Delete</a>
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

   function change_status(_this)
   {

        var value = $(_this).val();
        var user_id =  $(_this).attr('user_id');

            $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
               },
               type: "PUT", 
               url: "{{url('/')}}"+"/api/pages/"+user_id+"/update-status?status="+value,
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

   function _delete(id)
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
                                        "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
                                    },
                                    type: "DELETE",
                                    url: "{{url('/api/pages')}}/"+id,
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

      if (title != 'Action' && title != 'Sr.' && title != 'Status') {
         $(this).append('<br><input class="form-control form-control-sm filter_inputs" type="text" id="filter_' + title.toLowerCase().replace(/ /g, "_") + '" placeholder="Search ' + title + '" />');
      }

      if (title == 'Action') {
         $(this).append('<br><button id="filter_button" class="btn btn-secondary btn-sm">Search</button> <button id="filter_input_clear" class="btn btn-secondary btn-sm">X</button>');
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


   </script>


@include('Admin.Includes.footer')
