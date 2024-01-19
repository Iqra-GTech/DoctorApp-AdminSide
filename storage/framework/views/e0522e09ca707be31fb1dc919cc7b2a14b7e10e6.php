<?php echo $__env->make('Admin.Includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="page-wrapper 100vh">

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
            App Support List
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
        <h5 class="modal-title" id="detailsModalLabel">Discription</h5>
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


<?php echo $__env->make('Admin.Includes.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>

// function fetch_all_data()
//    {


//          let _DataTable = new DataTable('#table_id');
//          _DataTable.clear().draw();

//             $.ajax({
//                headers: {
//                      "Accept": "application/json",
//                      "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
//                },
//                type: "GET",
//                url: "<?php echo e(url('api/supports')); ?>",
//                success: function(response) {
//                   var  supports = response.data.supports;
//                   var  created_by = '';
//                   var resolved = '';
//                      if(supports.length > 0){

//                            for(var i = 0; i<supports.length;i++){

//                             var  created_by  =   supports[i]['created_by'] == '1' ? 'Admin': 'User';

//                             resolved =  `<select class="form-control" id="" support_id="${supports[i]['id']}" onchange="change_status(this);" >
//                                     <option value="Pending"  ${ supports[i]['resolved'] == 'Pending' ? 'selected' : ''}  >Pending</option>
//                                     <option value="Resolved"  ${ supports[i]['resolved'] == 'Resolved' ? 'selected' : ''}  >Resolved</option>
//                                     </select>`;

//                               _DataTable.row.add([
//                                     i+1,
//                                     supports[i]['title'],
//                                     resolved,
//                                     supports[i]['date'],
//                                     supports[i]['user']['email'],
//                                     supports[i]['user']['phone_number'],
//                                     ` <div class=" text-dark dropdown">
//                   <a class="text-dark" href="#navbar-supports" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
//                     <span class="d-inline-block">
//                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
//                      <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
//                      </svg>
//                   </span>
//                   </a>
//                   <div class="dropdown-menu">
//                     <a class="dropdown-item" role="button" onclick="_discription('${supports[i]['id']}');" >View Discription</a>
//                   </div>
//                 </div>`
//                                     ] ).draw();
//                            }
//                   }


//                },
//                error: function(response) {
//                      if (response.status == 500) {
//                         toastr.error("Something went wrong")
//                      } else {
//                         toastr.error(response.responseJSON.message)
//                      }
//                }
//             });

//    }


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
                "url": "<?php echo e(url('api/supports')); ?>",
                "dataSrc": "data.supports",
                "data": function(d) {
                    var filter = [{
                            key: 'title',
                            value: $('#filter_title').val() ? $('#filter_title').val() : ""
                        },
                        {
                            key: 'resolved',
                            value: $('#filter_resolved').val() ? $('#filter_resolved').val() : ""
                        },
                        {
                            key: 'phone_number',
                            value: $('#filter_phone_number').val() ? $('#filter_phone_number').val() : ""
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
                        }

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
                    "data": "title"

                },
                {
                    "data": "resolved",
                    "render": function(data, type, row) {
                        return `
                        <?php if(in_array('Change App support Status',Session::get('permission_list'))): ?>
                              <select class="form-control" id="" support_id="${row.id}" onchange="change_status(this);" >
                                 <option value="Pending"  ${ row.resolved == 'Pending' ? 'selected' : ''}  >Pending</option>
                                 <option value="Approve"  ${ row.resolved == 'Approve' ? 'selected' : ''}  >Approve</option>
                                 <option value="Unapproved"  ${ row.resolved == 'Unapproved' ? 'selected' : ''}  >Unapproved</option>
                              </select>
                              <?php endif; ?>
                              `;
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
                           <a class="dropdown-item" role="button" onclick="_discription('${row.id}');" >View Discription</a>
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
        var support_id =  $(_this).attr('support_id');

            $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
               type: "PUT",
               url: "<?php echo e(url('/')); ?>"+"/api/supports/"+support_id,
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
                     "Authorization": "Bearer <?php if(session()->has('token')): ?><?php echo e(session('token')); ?><?php endif; ?>"
               },
               type: "GET",
               url: "<?php echo e(url('/api/supports')); ?>/"+id,
               success: function(response) {
                  $('.append_discription').html(response.data.discription);
                  var images =  response.data.images;
                 var img_html = `<div class="mt-5 d-flex justify-content-around flex-wrap">`;

                  for(i=0;i < images.length;i++)
                  {
                     img_html +=`

                     <a href="${images[i]}" target="_blank" class="mt-2" style="border:2px solid gray;" >
                        <div style="
                        background: url(${images[i]});
                        background-repeat: no-repeat;
                        background-size: cover;
                        width: 100px;
                        height: 100px;
                        cursor: pointer;
                        " >
                        </div>
                        </a>
                        `;
                  }

                  img_html +=`</div>`;

                  $('.append_discription').append(img_html);
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


<?php echo $__env->make('Admin.Includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH D:\Gtech\admin side\TheDoctorApp\resources\views/Admin/Support/list.blade.php ENDPATH**/ ?>