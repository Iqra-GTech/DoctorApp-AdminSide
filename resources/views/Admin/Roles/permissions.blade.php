@include('Admin.Includes.header');





<form class="pt-3 container-fluid justify-content-between" action="" id="form_role" method="POST">
    @csrf

    {{-- {{ csrf_field() }} --}}
    {{-- <input type="hidden" name="_token" value="tM7jhkjsZXdpTVAWexR3Z8wQkQwz3oipOV4EwWyO">                     <input type="hidden" name="role_id" value="2"> --}}
    <div class="col-md-3">
        <input type="checkbox" id="selectAll"> <!-- "Select All" checkbox -->
        <label class="text-capitalize ml-2" for="selectAll">Select All</label>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="mt-2 mb-2">Dashboard</h5>
    <div class="row">
       <div class="col-md-3">
               <input type="checkbox" name="permission[]" value="View Dashboard"   >
               <label class="text-capitalize ml-2" for="View Dashboard">view dashboard</label>
       </div>
    </div>
        </div>
    </div>

<br>
     <div class="card">
        <div class="card-body">
            <h5 class="mt-2 mb-2">Users</h5>
    <div class="row">
       <div class="col-md-3">
               <input type="checkbox" name="permission[]" value="view users"   >
               <label class="text-capitalize ml-2" for="View Dashboard">view users</label>
       </div>

       <div class="col-md-3">
        <input type="checkbox" name="permission[]" value="create users">
        <label class="text-capitalize ml-2" for="View Dashboard">create users</label>
</div>

<div class="col-md-3">
    <input type="checkbox" name="permission[]" value="edit users"   >
    <label class="text-capitalize ml-2" for="View Dashboard">edit users</label>
</div>

<div class="col-md-3">
    <input type="checkbox" name="permission[]" value="edit users profile"   >
    <label class="text-capitalize ml-2" for="View Dashboard">edit users profile</label>
</div>

<div class="col-md-3">
    <input type="checkbox" name="permission[]" value="delete users"   >
    <label class="text-capitalize ml-2" for="View Dashboard">delete users</label>
</div>

<div class="col-md-3">
    <input type="checkbox" name="permission[]" value="change user status"   >
    <label class="text-capitalize ml-2" for="View Dashboard">change user status</label>
</div>
    </div>
        </div>
    </div>

    <br>
  <div class="card">
    <div class="card-body">
        <h5 class="mt-2 mb-2">Roles</h5>
        <div class="row">
            <div class="col-md-3">
                <input type="checkbox" name="permission[]" value="View Roles"   >
                <label class="text-capitalize ml-2" for="Quick Register View">View Roles</label>
        </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Create Role"   >
                    <label class="text-capitalize ml-2" for="Quick Register View">Create Role</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Edit Role" id="Quick Register Create"  >
                    <label class="text-capitalize ml-2" for="Quick Register Create">Edit Role</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Delete Role" id="Quick Register Delete"  >
                    <label class="text-capitalize ml-2" for="Quick Register Delete">Delete Role</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="View and change permissions" id="Quick Register Approve"  >
                    <label class="text-capitalize ml-2" for="Quick Register Approve">View and change permissions</label>
            </div>

        </div>
    </div>
  </div>

  <br>

  <div class="card">
    <div class="card-body">
        <h5 class="mt-2 mb-2">Modules</h5>
        <div class="row">
            <div class="col-md-3">
                <input type="checkbox" name="permission[]" value="View modules"   >
                <label class="text-capitalize ml-2" for="Quick Register View">View modules</label>
        </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Create module"   >
                    <label class="text-capitalize ml-2" for="Quick Register View">Create module</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Edit module" id="Quick Register Create"  >
                    <label class="text-capitalize ml-2" for="Quick Register Create">Edit module</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Delete module" id="Quick Register Delete"  >
                    <label class="text-capitalize ml-2" for="Quick Register Delete">Delete module</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="change module status" id="Quick Register Approve"  >
                    <label class="text-capitalize ml-2" for="Quick Register Approve">change module status</label>
            </div>

        </div>
    </div>
  </div>

  <br>

  <div class="card">
    <div class="card-body">
        <h5 class="mt-2 mb-2">Sponsers</h5>
        <div class="row">
            <div class="col-md-3">
                <input type="checkbox" name="permission[]" value="View sponsers"   >
                <label class="text-capitalize ml-2" for="Quick Register View">View sponsers</label>
        </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Create sponsers"   >
                    <label class="text-capitalize ml-2" for="Quick Register View">Create sponsers</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Edit sponsers" id="Quick Register Create"  >
                    <label class="text-capitalize ml-2" for="Quick Register Create">Edit sponsers</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Delete sponsers" id="Quick Register Delete"  >
                    <label class="text-capitalize ml-2" for="Quick Register Delete">Delete sponsers</label>
            </div>

        </div>
    </div>
  </div>

  <br>

  <div class="card">
    <div class="card-body">
        <h5 class="mt-2 mb-2">General Settings</h5>
        <div class="row">
            <h5 class="mt-2 mb-2">Pages</h5>
            <div class="col-md-3">
                <input type="checkbox" name="permission[]" value="View pages"   >
                <label class="text-capitalize ml-2" for="Quick Register View">View pages</label>
        </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Create page"   >
                    <label class="text-capitalize ml-2" for="Quick Register View">Create page</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Edit page" id="Quick Register Create"  >
                    <label class="text-capitalize ml-2" for="Quick Register Create">Edit page</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Delete page" id="Quick Register Delete"  >
                    <label class="text-capitalize ml-2" for="Quick Register Delete">Delete page</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Change page status" id="Quick Register Approve"  >
                    <label class="text-capitalize ml-2" for="Quick Register Approve">Change page status</label>
            </div>

        </div>


        <div class="row">
            <h5 class="mt-2 mb-2">App Support</h5>
            
            <div class="col-md-3">
                <input type="checkbox" name="permission[]" value="View App support"   >
                <label class="text-capitalize ml-2" for="Quick Register View">View App support</label>
        </div>
        <div class="col-md-3">
                <input type="checkbox" name="permission[]" value="Change App support Status" id="Quick Register Create"  >
                <label class="text-capitalize ml-2" for="Quick Register Create">Change App support Status</label>
        </div>


        </div>

        <div class="row">
            <h5 class="mt-2 mb-2">Request for Updates</h5>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="View Requests"   >
                    <label class="text-capitalize ml-2" for="Quick Register View">View Requests</label>
            </div>
            <div class="col-md-3">
                    <input type="checkbox" name="permission[]" value="Change Requests Status" id="Quick Register Create"  >
                    <label class="text-capitalize ml-2" for="Quick Register Create">Change Requests Status</label>
            </div>


        </div>
    </div>
  </div>

  <br>
  <br>
  <div class="row text-center justify-content-between">
    <button id="updatePermissions" type="button" class="btn btn-primary" >Update</button>
                        </div>
                        <br>
                        <br>

</form>



@include('Admin.Includes.scripts')
<script>
    function getParams(name) {
        var url = new URL(window.location.href);
        return url.searchParams.get(name);
}


    $("#selectAll").change(function () {
        $('input[name="permission[]"]').prop('checked', $(this).prop("checked"));
    });


    $("#updatePermissions").click(function () {
        var role_id = getParams('role_id');
        console.log(role_id);

        var permissions = [];
        $('input[name="permission[]"]:checked').each(function () {
            permissions.push($(this).val());
        });

        var requestData = {
            role_id: role_id,
            permission: permissions
        };
        console.log(requestData);

        var customHeaders = {
            "Accept": "application/json",
            "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
        };

        $.ajax({
            headers: customHeaders,
            type: 'POST',
            url: "{{url('api/permission_update')}}",
            data: requestData,
            success: function (data) {

                $('input[name="permission[]"]').each(function () {
                    localStorage.setItem($(this).val(), $(this).prop("checked"));
                });

                toastr.success('Permissions Updated Successfully');
               
                console.log(data);
            },
            error: function (xhr, status, error) {

                console.error(error);
            }
        });
    });


    $(document).ready(function () {
        $('input[name="permission[]"]').each(function () {
            var isChecked = localStorage.getItem($(this).val());
            if (isChecked === "true") {
                $(this).prop("checked", true);
            }
        });
    });






// $(document).ready(function(){
//     var role_id = getParams('role_id');

// console.log(role_id);
// })


</script>



@include('Admin.Includes.footer')
