@include('Admin.Includes.header')
<style>
        .min-height
     {
        min-height: 75vh;
        max-height: 75vh;
     }
     .bold
     {
        font-weight: bold;
        font-size: 16px;
     }
     .pointer
     {
        cursor: pointer;
     }
     .overflow-set
     {
        max-height: 540px;
        min-height: 540px;
        border: 1px solid lightgray;
        overflow-y: auto;
        padding: 10px;
     }
     .form-height
     {
      max-height: 100%;
        min-height: 100%;
        overflow-y: auto;
        padding: 10px;
     }
     .mx-min-height
     {
      min-height: 540px;
      max-height: 540px;
      overflow-y: auto;
     }
     .update_field_box
     {
      min-height: 540px;
      max-height: 540px;
      overflow-y: auto;
     }
     .active_class{
      border-color: #0054a6 !important;
     }

     @media (max-width: 1440px) {
      .overflow-set {
         max-height: 308px;
         min-height: 308px;
      }

      .mx-min-height {
         min-height: 308px;
         max-height: 308px;
         
      }

      .update_field_box {
         min-height: 408px;
         max-height: 408px;
         
      }

   }


 
     
    </style>

<div class="page-wrapper">
<!-- Page header -->
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col-11">
            <h2 class="page-title">
              Module Create
            </h2>
         </div>
         <div class="col-1">
            <h2 class="page-title">
            <button type="button" class="btn btn-primary rounded save_fields_info" style="margin-left:20px !important">Submit</button>
            </h2>
         </div>
      </div>
   </div>
</div>
<!-- Page body -->
<div class="page-body">
   <div class="container-xl">
      <div class="row mb-3" >
      <div class="col-6">
         <div class="form-group mt-2">
            <label for="" id="">Module Name</label>
            <input type="text" class="form-control" id="module_name" name="module_name" onchange="check_module_name(this);" placeholder="Module Name">
         </div>
      </div>
      <div class="col-6">
         <div class="form-group mt-2">
            <label for="" id="" >Role</label>
            <select class="form-control" id="module_role" name="module_role">
               @php
               $roles =   DB::table('roles')->where('del', 0)->get();
               @endphp
               @foreach($roles as $role)
               <option value="{{$role->id}}" >{{$role->name}}</option>
               @endforeach
            </select>
         </div>
      </div>
      <div class="col-6">
         <div class="form-group mt-2">
            <label for="" id="">Module Icon</label>
            <input type="file" class="form-control" class="module_icon" id="module_icon" name="module_icon"  placeholder="Module Icon" >
            <img src="{{url('/logo-placeholder.png')}}" id="module_icon_image" width="50" height="50" class="mt-2" >
         </div>
      </div>
      <div class="col-6">
         <div class="form-group mt-2">
            <label for="" id="">Module Header Icon</label>
            <input type="file" class="form-control" class="module_header_icon" id="module_header_icon" name="module_header_icon"  placeholder="Module Header Icon" >
            <img src="{{url('/logo-placeholder.png')}}" id="module_header_icon_image" width="50" height="50" class="mt-2" >
         </div>
      </div>
   </div>
      <div class="row" >
         <div class="col-4 border min-height">
            <div class="w-100 mt-2 bold" > Generated Fields  </div>
            <div class="w-100 mt-3 overflow-set all_field_list">
            </div>
                    <button type="button" class="btn btn-primary w-100 p-3 mt-3 text-white add_another_field"> Add Another Field</button>
                    <button type="button" class="btn btn-secondary w-100 p-3 mb-3 mt-1 text-white reset_fields">Reset</button>
         </div>
         <div class="col-4 border min-height">
         <div class="w-100 mt-2 bold d-flex justify-content-between" ><div> Update field </div>  <div><button type="button" class="btn btn-primary btn-sm w-100 text-white apply_changes">Apply changes</button></div> </div>
            <form class="mt-5 update_field_box">

                <div class="form-group mt-2">
                    <label for="option" id="label_option">Option</label>
                    <input type="text" class="form-control" id="option" name="option"  placeholder="Option">
                </div>

                <div class="form-group mt-2">
                <label for="type" id="label_type" >Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="text" >Text</option>
                    <option value="number" >Number</option>
                    <option value="email" >Email</option>
                    <option value="image" >Image</option>
                    <option value="textarea" >Textarea</option>
                    <option value="datepicker" >Datepicker</option>
                    <option value="dropdown" >Dropdown</option>
                    <option value="radio" >Radio</option>
                    <option value="checkbox" >Checkbox</option>
                </select>
                </div>

                <div class="form-group mt-2">
                    <label for="required" id="label_required">Required</label>
                    <select   class="form-control" id="required"  name="Required">
                        <option value="1">Required</option>
                        <option value="0">Not Required</option>
                    </select>
                </div>

                    <div class="form-group mt-2" id="value_box" style="display:none;">
                        <label for="value" id="label_value" >Default Value</label>
                        <input type="text" class="form-control" id="value" name="value"  placeholder="Default Value">
                    </div>

                    <div class="form-group mt-2" id="import_option_box" style="display:none;" >
                        <label for="import_option" id="label_import_option"  >Import Option</label>
                        <select   class="form-control" id="import_option" name="import_option">
                           <option value="0" >No</option>
                           <option value="1" >Yes</option>
                        </select>
                    </div>

                    <div class="form-group mt-2" id="csv_file_box" style="display:none;">
                        <label for="csv_file" id="label_csv_file" >CSV File</label>
                        <input type="file" class="form-control" onchange="read_csv_file(this.files)" id="csv_file" name="csv_file"  placeholder="CSV File" accept=".csv">
                    </div>

                    <div class="form-group mt-2" id="select_csv_column_box" style="display:none;">
                        <label for="select_csv_column" id="label_select_csv_column" >Select CSV Column</label>
                        <select   class="form-control" id="select_csv_column" name="select_csv_column">
                           <option value="" >Select CSV Column</option>
                        </select>            
                     </div>

                     <div class="form-group mt-2" id="comma_separated_values_box" style="display:none;">
                        <label for="comma_separated_values" id="label_comma_separated_values" >Comma Separated Values</label>
                        <input type="text" class="form-control" id="comma_separated_values" name="comma_separated_values"  placeholder="Comma Separated Values" >
                    </div>
                     

                    <div class="form-group mt-2" id="table_name_box" style="display:none;">
                        <label for="table_name" id="label_table_name" >Select Table</label>
                        <select   class="form-control" id="table_name" name="table_name" onchange="getColumns(this.value);">
                        </select>                    
                     </div>

                     <div class="form-group mt-2" id="tables_column_name_value_box" style="display:none;">
                        <label for="tables_column_name_value" id="label_tables_column_name_value" >Select Table Column (value)</label>
                        <select   class="form-control" id="tables_column_name_value" name="tables_column_name_value" >
                           <option value="" >Select Table Column (value)</option>
                        </select>                    
                     </div>

                     <div class="form-group mt-2" id="tables_column_name_show_box" style="display:none;">
                        <label for="tables_column_name_show" id="label_tables_column_name_show" >Select Table Column (show)</label>
                        <select   class="form-control" id="tables_column_name_show" name="tables_column_name_show" >
                           <option value="" >Select Table Column (show)</option>
                        </select>                    
                     </div>


            </form>

         </div>
         <div class="col-4 border min-height">
         <div class="w-100 mt-2 bold" >Preview</div>

               <form class="mt-5 form-height">

               <div class="w-100 mx-min-height preview_field_box">
               </div>
                     <div class="form-group mt-2">
                           <button type="button" class="btn btn-primary w-100 p-3 mt-3 text-white" disabled >Submit</button>
                     </div>
               </form>
         </div>
      </div>
   </div>
</div>





@include('Admin.Includes.scripts')

<script>

function remove_special_characters(value)
{
   var  value = remove_spaces(value)
   $('#option').val(value.replace(/[^a-zA-Z_]/g, ""));
}

function check_module_name(_this)
{
      if(!/^[A-Za-z\s]*$/.test($(_this).val()))
      {
         toastr.error('Module Name field contains only alphabets and space');
         $('.save_fields_info').attr('disabled','disabled');
      }
      else
      {
         $('.save_fields_info').removeAttr('disabled');
      }
    $str =   $(_this).val().trim();
      $(_this).val($str);
}

function getTables()
{
   loader(true);
            $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
               },
               type: "GET",
               url: "{{url('api/module-managers/get-tables')}}",
               success: function(response) 
               {
                  loader(false);
                 
                 var tables = response.data.tables;
                 
                  option = `<option value="">Select Table</option>`;

                  for(i=0; i < tables.length; i++)
                  {
                     option += `<option value="${tables[i]}" >${tables[i]}</option>`;
                  }

                     $('#table_name').html(option);
                     
               },
               error: function(response) {
                  loader(false);
                     if (response.status == 500) {
                        toastr.error("Something went wrong")
                     } else {
                        toastr.error(response.responseJSON.message)
                     }
               }
            });

}


function getColumns(table_name)
{



   if(table_name == 'users')
   {

      var option = '';

      @foreach($roles as $role)
      @if($role->id != '1')
      option +=    '<option value="role.{{$role->id}}" >{{$role->name}}</option>';
      @endif
      @endforeach

   }
   else
   {
     var option = '<option value="id">id</option>';
   }


   $('#tables_column_name_value').html(option);

   loader(true);

            $.ajax({
               headers: {
                     "Accept": "application/json",
                     "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
               },
               type: "put",
               data: {'table_name' : table_name},
               url: "{{url('api/module-managers/get-columns')}}",
               success: function(response) 
               {
                  loader(false);
                 var columns = response.data.columns;
                 
                  option = `<option value="" >Select Table Column (show)</option>`;

                  for(i=0; i < columns.length; i++)
                  {
                     if(columns[i] != 'show__to' && columns[i] != 'user__id' &&  columns[i] != 'module__id')
                     {
                        option += `<option value="${columns[i]}" >${columns[i]}</option>`;
                     }
                  }

                     $('#tables_column_name_show').html(option);
                     
               },
               error: function(response) {
                  loader(false);
                     if (response.status == 500) {
                        toastr.error("Something went wrong")
                     } else {
                        toastr.error(response.responseJSON.message)
                     }
               }
            });

}

function read_csv_file(files) {
      var file = files[0];

      var reader = new FileReader();

      reader.onload = function(e) {

        var contents = e.target.result;
        var lines = contents.split("\n");
        var column = lines[0].split(",");

        var arr = [];
        var column_name = '';
        var all_values = '';
        var option = `<option value="" >Select CSV Column</option>`;

        for (var i = 0; i < column.length; i++) {

         all_values = '';
            for (var j = 1; j < lines.length; j++) {

               var _firstColumn = lines[j].split(",")[i];

               if(_firstColumn != undefined)
               {

                  all_values +=  _firstColumn+','
                  
               }
               

            }

             column_name = column[i]; //.replace(/ /g,"_");
            arr[i]= { [column_name] : all_values };
            option += `<option value="${all_values.replace(/,\s*$/, '')}" >${column_name}</option>`;
            

      }

         $('#select_csv_column').html(option)
       
      };

      reader.readAsText(file);
    }
      
      function getRandomNumber(min, max) 
      {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
      }

      //loader(false);
      function field_delete(unique_number)
      {
          swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this filed",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
               if (willDelete) {
               
                     $(`[unique_number='${unique_number}']`).remove();
                     $(`[preview_unique_number='${unique_number}']`).remove();
               }

            });




      }

      function field_detail(unique_number)
      {
        $('.created_field').removeClass("active_class");
        $(`[unique_number='${unique_number}']`).addClass("active_class");
        var _option =  $(`[detail_unique_number='${unique_number}']`).attr('_option');
        var _type =  $(`[detail_unique_number='${unique_number}']`).attr('_type');
        var _required =  $(`[detail_unique_number='${unique_number}']`).attr('_required');
        var _value =  $(`[detail_unique_number='${unique_number}']`).attr('_value');
        var _import_option =  $(`[detail_unique_number='${unique_number}']`).attr('_import_option');
        var _comma_separated_values =  $(`[detail_unique_number='${unique_number}']`).attr('_comma_separated_values');
        var _table_name =  $(`[detail_unique_number='${unique_number}']`).attr('_table_name');
        var _tables_column_name_value =  $(`[detail_unique_number='${unique_number}']`).attr('_tables_column_name_value');
        var _tables_column_name_show =  $(`[detail_unique_number='${unique_number}']`).attr('_tables_column_name_show');

        $('.update_field_box #option').val(_option);
        $('.update_field_box #type').val(_type);  
        $('.update_field_box #required').val(_required);  
        $('.update_field_box #value').val(_value);  
        $('.update_field_box #import_option').val(_import_option);
        $('.update_field_box #comma_separated_values').val(_comma_separated_values);
        $('.update_field_box #table_name').val(_table_name);
        $('.update_field_box #tables_column_name_value').val(_tables_column_name_value);
        $('.update_field_box #tables_column_name_show').val(_tables_column_name_show);

        if(_type == 'checkbox' || _type == 'radio' || _type == 'dropdown')
        {


            if(_import_option == '1')
            {
               $('#csv_file_box').css('display','block');
               $('#comma_separated_values_box').css('display','block');
               $('#select_csv_column_box').css('display','block');
               $('#table_name_box').css('display','none');
               $('#tables_column_name_value_box').css('display','none');
               $('#tables_column_name_show_box').css('display','none');
               

            }
            else
            {
               $('#csv_file_box').css('display','none');
               $('#comma_separated_values_box').css('display','none');
               $('#select_csv_column_box').css('display','none');
               $('#table_name_box').css('display','block');
               $('#tables_column_name_value_box').css('display','block');
               $('#tables_column_name_show_box').css('display','block');
            }

            $('#import_option_box').css('display','block');
            $('#value_box').css('display','none');

        }
        else
        {

            $('#import_option_box').css('display','none');
            $('#table_name_box').css('display','none');
            $('#tables_column_name_value_box').css('display','none');
            $('#tables_column_name_show_box').css('display','none');
            $('#csv_file_box').css('display','none');
            $('#select_csv_column_box').css('display','none');
            $('#comma_separated_values_box').css('display','none');
            $('#value_box').css('display','block');
        }
        

      }

      function remove_spaces(text)
      {
         return text.toString().replace(/ /g,"_");
      }

      $(document).ready(function() {

         getTables();
       
         $(".add_another_field").on("click", function() {

            var unique_number =  getRandomNumber(10000,999999);
            var new_field = `New Field ${unique_number}`;

            var field =    `<div class="w-100 border p-3 mt-1  created_field d-flex justify-content-between"  unique_number="${unique_number}" >
                                    <div>
                                       <span class="text${unique_number}">${new_field}</span>
                                    </div>
                                    <div>
                                       <span class="pointer del" onclick="field_delete('${unique_number}');" ><i class="fa-solid fa-xmark"></i></span>
                                       <span class="pointer detail" detail_unique_number="${unique_number}"  _option="${new_field}" _type="text"  _required="1" _value="" _import_option="0"  _comma_separated_values=""   _table_name=""  _tables_column_name_value=""  _tables_column_name_show=""  onclick="field_detail('${unique_number}');" ><i class="fa-solid fa-arrow-right"></i></span>
                                    </div>
                                    <input type="file" class="d-none" id="Files_${unique_number}" value="" >
                           </div>`;


            var preview_field = `<div class="form-group mt-2"  preview_unique_number="${unique_number}" >
                                    <label for="">${new_field}</label>
                                    <input type="text" class="form-control" id="${remove_spaces(new_field)}" name="${remove_spaces(new_field)}"  placeholder="${new_field}">
                                 </div>`;

            
                              
            $(".all_field_list").append(field);
            $(".preview_field_box").append(preview_field);

         });


         $(".reset_fields").on("click", function() {

            swal({
            title: "Are you sure?",
            text: "You want to reset/delete all fields",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
               if (willDelete) {
               
                     $(".all_field_list").html('');
                     $(".preview_field_box").html('');
               }

            });
           
         });

         $("#type").on("change", function() {

                  if($(this).val() == 'checkbox' || $(this).val() == 'radio' || $(this).val() == 'dropdown')
                  {
                     $('#import_option_box').css('display','block');
                     $('#csv_file_box').css('display','none');
                     $('#select_csv_column_box').css('display','none');
                     $('#tables_column_name_value_box').css('display','block');
                     $('#tables_column_name_show_box').css('display','block');
                     $('#table_name_box').css('display','block');
                     $('#value_box').css('display','none');
                  }
                  else
                  {

                     $('#import_option_box').css('display','none');
                     $('#select_csv_column_box').css('display','none');
                     $('#csv_file_box').css('display','none');
                     $('#value_box').css('display','block');
                     $('#comma_separated_values_box').css('display','none');
                     $('#tables_column_name_value_box').css('display','none');
                     $('#tables_column_name_show_box').css('display','none');
                     $('#table_name_box').css('display','none');
                     
                  }
                  
                  $('#value').val('');
                  $('#import_option').val('0');
                  $('#select_csv_column').val('');
                  $('#comma_separated_values').val('');
                  $('#tables_column_name_value').val('');
                  $('#tables_column_name_show').val('');
                  $('#table_name').val('');
                  $('#csv_file_box').val('');
  
         });

         $("#import_option").on("change", function() {

            if($(this).val() == '1' )
            {
               $('#csv_file_box').css('display','block');
               $('#select_csv_column_box').css('display','block');
               $('#comma_separated_values_box').css('display','block');
               $('#tables_column_name_value_box').css('display','none');
               $('#tables_column_name_show_box').css('display','none');
               $('#table_name_box').css('display','none');

            }
            else
            {
               $('#csv_file_box').css('display','none');
               $('#select_csv_column_box').css('display','none');
               $('#comma_separated_values_box').css('display','none');
               $('#tables_column_name_value_box').css('display','block');
               $('#tables_column_name_show_box').css('display','block');
               $('#table_name_box').css('display','block');
            }

         });  
         
         
         function linked_table_data(data) {
            loader(true);
               return new Promise(function(resolve, reject) {
                  
                     $.ajax({
                        headers: {
                              "Accept": "application/json",
                              "Authorization": "Bearer @if(session()->has('token')){{session('token')}}@endif"
                        },
                        type: "POST",
                        url: "{{url('api/module-managers/comma-separated-values-array-from-linked-table')}}",
                        data: data,
                        success: function(response) 
                        {
                           loader(false);
                           resolve(response.data);
                        // var  _comma_separated_values_array = response.data
                        },
                        error: function(response) {
                           loader(false);
                           reject(response);
                              if (response.status == 500) {
                                 toastr.error("Something went wrong")
                              } else {
                                 toastr.error(response.responseJSON.message)
                              }
                        }
                     });

               });
         }
         

         $(".apply_changes").on("click", async  function() {

               var unique_number = $('.active_class').attr('unique_number');

               if(unique_number)
               {
                  var _option =   $('.update_field_box #option').val();
                  var _type = $('.update_field_box #type').val();  
                  var _required = $('.update_field_box #required').val();  
                  var _value = $('.update_field_box #value').val();  
                  var _import_option = $('.update_field_box #import_option').val();
                  var _comma_separated_values = $('.update_field_box #comma_separated_values').val().toLowerCase();
                  var _table_name = $('.update_field_box #table_name').val();
                  var _tables_column_name_value = $('.update_field_box #tables_column_name_value').val();
                  var _tables_column_name_show = $('.update_field_box #tables_column_name_show').val();


                  if(_option == '')
                  {
                     toastr.error('Option is required');
                     return 0;
                  }

                  if(!/^[A-Za-z\s]*$/.test(_option))
                  {
                     toastr.error('Option contains only alphabets and spaces');
                     return 0;
                  }

                  $("#Files_"+unique_number)[0].files = $('#csv_file')[0].files;

                 
                  $(`.text${unique_number}`).html(_option);
                  $(`[detail_unique_number='${unique_number}']`).attr('_option',_option);
                  $(`[detail_unique_number='${unique_number}']`).attr('_type',_type);
                  $(`[detail_unique_number='${unique_number}']`).attr('_required',_required);
                  $(`[detail_unique_number='${unique_number}']`).attr('_value',_value);
                  $(`[detail_unique_number='${unique_number}']`).attr('_import_option',_import_option);
                  $(`[detail_unique_number='${unique_number}']`).attr('_comma_separated_values',_comma_separated_values);
                  $(`[detail_unique_number='${unique_number}']`).attr('_table_name',_table_name);
                  $(`[detail_unique_number='${unique_number}']`).attr('_tables_column_name_value',_tables_column_name_value);
                  $(`[detail_unique_number='${unique_number}']`).attr('_tables_column_name_show',_tables_column_name_show);


                  if(_type == 'radio' || _type == 'dropdown' || _type == 'checkbox')
                  {
                    
                     if(_import_option == '1')
                     {
                              /// validation //////////

                              if(_comma_separated_values == '')
                              {
                                    toastr.error('Comma Separated Values field is required');
                                    return 0;
                              }


                              /// validation end /////

                        var _comma_separated_values_array =  [];

                        arr = _comma_separated_values.split(','); 
                        
                        
                        for(i=0;i<arr.length; i++)
                        {
                           _comma_separated_values_array[i] =   { 'value': arr[i], 'label': arr[i] }
                        }   
 
                     }
                     else
                     {

                                    /// validation //////////

                                       if(_table_name == '')
                                       {
                                          toastr.error('Select Table field is required');
                                          return 0;
                                       }

                                       if(_tables_column_name_value == '')
                                       {
                                          toastr.error('Select Table Column (value) field is required');
                                          return 0;
                                       }


                                       if(_tables_column_name_show == '')
                                       {
                                          toastr.error('Select Table Column (show) field is required');
                                          return 0;
                                       }



                                    /// validation end /////


                        var data = {
                           "dependency" : _table_name,
                           "dependency_options" : _tables_column_name_show,
                           "dependency_values" : _tables_column_name_value,
                        }
                        

                        _comma_separated_values_array = await linked_table_data(data);
                            
                     }
                  }

                  // change in preview field
                  var html_field = `<label for="">${_option}</label>`;

                  if(_type == 'text')
                  {
                     html_field += `
                     <input type="text" class="form-control" id="${remove_spaces(_option)}" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''}  placeholder="${_option}">
                     `;
                  }
                  if(_type == 'number')
                  {
                     html_field += `
                     <input type="number" class="form-control" id="${remove_spaces(_option)}" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''}  placeholder="${_option}">
                     `;
                  }
                  if(_type == 'email')
                  {
                     html_field += `
                     <input type="email" class="form-control" id="${remove_spaces(_option)}" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''}  placeholder="${_option}">
                     `;
                  }
                  else
                  if(_type == 'image')
                  {
                     html_field += `
                     <input type="file" class="form-control" id="${remove_spaces(_option)}" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''}  placeholder="${_option}">
                     `;
                  }
                  else
                  if(_type == 'datepicker')
                  {
                     html_field += `
                     <input type="date" class="form-control" id="${remove_spaces(_option)}" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''}  placeholder="${_option}">
                     `;
                  }
                  else
                  if(_type == 'radio')
                  {
                     
                     for(var i=0; i < _comma_separated_values_array.length; i++)
                     {

                        html_field += `
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''} value="${_comma_separated_values_array[i].value.trim()}" ${ i=='0' ? 'checked' : '' } >
                                 <label class="form-check-label" ${ _import_option == '0' ?  '' : 'style="text-transform: capitalize;"'} >${_comma_separated_values_array[i].label.trim()}</label>
                              </div>
                        `;
                     }
                  }
                  else 
                  if(_type == 'checkbox')
                  {
                     
                     for(var i=0; i < _comma_separated_values_array.length; i++)
                     {

                     html_field += `
                                 <div class="form-check">
                                 <input class="form-check-input" type="checkbox"  name="${remove_spaces(_option)}[]"  value="${_comma_separated_values_array[i].value.trim()}">
                                 <label class="form-check-label" ${ _import_option == '0' ?  '' : 'style="text-transform: capitalize;"'} >${_comma_separated_values_array[i].label.trim()}</label>
                                 </div>
                     `;

                     }
                  }
                  else
                  if(_type == 'dropdown')
                  {

                     html_field += `<select class="form-control" ${ _import_option == '0' ?  '' : 'style="text-transform: capitalize;"'} id="${remove_spaces(_option)}" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''} >`;

                     for(var i=0; i < _comma_separated_values_array.length; i++)
                     {
                        html_field +=`<option value="${_comma_separated_values_array[i].value.trim()}" >${_comma_separated_values_array[i].label.trim()}</option>`;
                     }

                     html_field +=`</select>`;
                  }
                  else
                  if(_type == 'textarea')
                  {
                     html_field += `
                     <textarea class="form-control"  id="${remove_spaces(_option)}" name="${remove_spaces(_option)}" ${_required ? ' required ' : ''}  placeholder="${_option}"></textarea>
                     `;
                  }

                  $(`[preview_unique_number='${unique_number}']`).html(html_field);

                     


               swal({
                        title: "Changed Successfully",
                        text: "",
                        icon: "success",
                        });
        
               }
               else
               {
                        swal({
                        title: "Error",
                        text: "No field is selected",
                        icon: "error",
                        });
               }
               
         });

         $("#module_icon").on("change", function(e) {
               var file = e.target.files[0];
               var reader = new FileReader();
               reader.onload = function(e) {
                  $("#module_icon_image").attr("src", e.target.result);
               };
               reader.readAsDataURL(file);
            });


            $("#module_header_icon").on("change", function(e) {
               var file = e.target.files[0];
               var reader = new FileReader();
               reader.onload = function(e) {
                  $("#module_header_icon_image").attr("src", e.target.result);
               };
               reader.readAsDataURL(file);
            });


         $(".save_fields_info").on("click", function() {
            loader(true);
            const detail = document.getElementsByClassName("detail");

            if($('#module_name').val() == '')
            {
               toastr.error("Module Name is required");
               loader(false);
               return 0;
            }
            if(!$('#module_icon')[0].files[0])
            {
               toastr.error("Module Icon is required");
               loader(false);
               return 0;
            }

            if(!$('#module_header_icon')[0].files[0])
            {
               toastr.error("Module Header Icon is required");
               loader(false);
               return 0;
            }

            if(detail.length <=0)
            {
               toastr.error("Please add at least one field");
               loader(false);
               return 0;
            }


            var formData = new FormData();
            var data = [];

            for (let i = 0; i < detail.length; i++) {

               if(!/^[A-Za-z\s]*$/.test($(detail[i]).attr('_option')))
               {
                  toastr.error('Option contains only alphabets and spaces');
                  loader(false);
                  return  0;
               }

                     file = $('#Files_'+$(detail[i]).attr('detail_unique_number'))[0].files[0] ? $('#Files_'+$(detail[i]).attr('detail_unique_number'))[0].files[0] : '';
                     formData.append('file['+i+']', file);
                     formData.append('name', $('#module_name').val());
                     formData.append('module_icon', $('#module_icon')[0].files[0]);
                     formData.append('module_header_icon', $('#module_header_icon')[0].files[0]);
                     formData.append('role_id', $('#module_role').val());
                     formData.append('table_name', remove_spaces($('#module_name').val()));
                     formData.append('option['+i+']',  $(detail[i]).attr('_option').trim());
                     formData.append('type['+i+']',  $(detail[i]).attr('_type'));
                     formData.append('required['+i+']',  $(detail[i]).attr('_required'));
                     formData.append('value['+i+']',  $(detail[i]).attr('_value'));
                     formData.append('import_option['+i+']',  $(detail[i]).attr('_import_option'));
                     formData.append('tables_column_name_value['+i+']',  $(detail[i]).attr('_tables_column_name_value'));
                     formData.append('tables_column_name_show['+i+']',  $(detail[i]).attr('_tables_column_name_show'));
                     formData.append('table_name_for_field['+i+']',  $(detail[i]).attr('_table_name'));
                     formData.append('comma_separated_values['+i+']',  $(detail[i]).attr('_comma_separated_values')); 
               
            }

                  $.ajax({
                  url: '{{url("api/module-managers")}}',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                     loader(false);
                     toastr.success(response.message);
                     window.location.href = "{{route('Admin.modules.list')}}";

                  } ,
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


         $("#select_csv_column").on("change", function() {
            $('#comma_separated_values').val($(this).val());
         });


         

});





</script>

@include('Admin.Includes.footer')
