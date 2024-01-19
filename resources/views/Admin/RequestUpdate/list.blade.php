@include('Admin.Includes.header')
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
            Request For Updates List
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
               <th>Module</th>
               <th>Title</th>
               <th>Resolved</th>
               <th>Date</th>
               <th>Email</th>
               <th>Number</th>
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
        <h5 class="modal-title" id="detailsModalLabel">Details</h5>
      </div>
      <div class="modal-body append_discription"></div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#detailsModal').modal('hide');" >Close</button>
      </div>
    </div>
  </div>
</div>
<!--end detailsModal-->


      </div>
   </div>


</div>


@include('Admin.Includes.scripts')

<script>
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
                "url": "{{url('api/request-updates')}}",
                "dataSrc": "data.request_updates",
                "data": function(d) {
                  var filter = [{
                           key: 'module_id.module_manager.name',
                           value: $('#filter_module').val() ? $('#filter_module').val() : ""
                        },
                     {
                           key: 'title',
                           value: $('#filter_title').val() ? $('#filter_title').val() : ""
                        },
                        {
                           key: 'resolved',
                           value: $('#filter_resolved').val() ? $('#filter_resolved').val() : ""
                        },
                        {
                           key: 'date',
                           value: $('#filter_date').val() ? $('#filter_date').val() : ""
                        },
                        {
                           key: 'user_id.users.email',
                           value: $('#filter_email').val() ? $('#filter_email').val() : ""
                        },
                        {
                           key: 'user_id.users.phone_number',
                           value: $('#filter_number').val() ? $('#filter_number').val() : ""
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
                    "data": "module.name"
                },
                {
                    "data": "title"
                },
                {
                "data": "resolved",
                    "render": function(data, type, row) {
                        return `
                        <select class="form-control" id="" request_updates_id="${row.id}" onchange="change_status(this);" >
                                 <option value="Pending"  ${ row.resolved == 'Pending' ? 'selected' : ''}  >Pending</option>
                                 <option value="Approve"  ${ row.resolved == 'Approve' ? 'selected' : ''}  >Approve</option>
                                 <option value="Unapproved"  ${ row.resolved == 'Unapproved' ? 'selected' : ''}  >Unapproved</option>
                        </select>`;
                    }
                  },
                {
                    "data": "date",
                    "render": function(data, type, row) {
                        return formatDate(data);
                    }
                },
                {
                    "data": "user.email"
                },
                {
                    "data": "user.phone_number"
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
                           <a class="dropdown-item" role="button" onclick="_discription('${row.id}');" >View Details</a>

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

      if (title != 'Action' && title != 'Sr.' && title != 'Resolved' && title != 'Date') {
         $(this).append('<br><input class="form-control form-control-sm filter_inputs" type="text" id="filter_' + title.toLowerCase().replace(/ /g, "_") + '" placeholder="Search ' + title + '" />');
      }

      if (title == 'Date') {
         $(this).append(
            `<br>
         <input type="date"  class="form-control form-control-sm filter_inputs" id="filter_${title.toLowerCase().replace(/ /g,"_")}" value="" />
         `
         );

      }

      if (title == 'Action') {
         $(this).append('<br><button id="filter_button" class="btn btn-secondary btn-sm">Search</button> <button id="filter_input_clear" class="btn btn-secondary btn-sm">X</button>');
      }


      if (title == 'Resolved') {
         $(this).append(
            `<br>
         <select class="form-control form-control-sm"  id="filter_${title.toLowerCase().replace(/ /g,"_")}" >
         <option value="">Select ${title}</option>
         <option value="Pending">Pending</option>
         <option value="Approve">Approve</option>
         <option value="Unapproved">Unapproved</option>
         </select>
         `
         );
      }


      if (title == 'Sr.') {
         $(this).append('<br><button style="visibility: hidden;" >hidden</button>');
      }

      });

   });

   function change_status(_this)
   {

        var value = $(_this).val();
        var request_updates_id =  $(_this).attr('request_updates_id');

            $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
               },
               type: "PUT", 
               url: "{{url('/')}}"+"/api/request-updates/"+request_updates_id,
               data: {'resolved' : value },
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

   function _discription(id)
   {
      
      $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
               },
               type: "GET", 
               url: "{{url('/api/request-updates')}}/"+id,
               success: function(response) {
                  $('.append_discription').html(response.data.discription);
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


   function formatDate(inputDate) 
   {
      const date = new Date(inputDate);
      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear();
      return `${day <=9 ? '0'+day: day}-${month <=9 ? '0'+month: month}-${year}`;
   }



</script>


@include('Admin.Includes.footer')
