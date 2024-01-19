@include('Admin.Includes.header')
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<?php



if(date('N') == 1 )
{
  $monday = date('Y-m-d');
}
else
{
  $monday = date('Y-m-d',strtotime('last monday',strtotime(date('Y-m-d'))));
}

?>
<!-- Start Wrapper -->
      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                  Overview
                </div>
                <h2 class="page-title">
                  Dashboard
                </h2>
              </div>
            </div>
          </div>
        </div>
     
        <div class="page-body">
          <div class="container-xl">
            <div class="row row-deck row-cards">
              <div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><i class="fas fa-user-md" style="font-size:40px;color:#02b2b0 !important;"></i></div>
                      <div class="ms-auto lh-1">
                  
                      </div>
                    </div>
                    <div class="d-flex justify-content-between mt-5">
                      <div class="h2 text-mute doctor_text">Doctors</div>
                      <div class="ml-1 h1 doctor_number count_doctors" style="color:#02b2b0 !important;">-</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><i class="fa-solid fa-head-side-cough" style="font-size:40px;color:#02b2b0 !important;"></i></div>
                      <div class="ms-auto lh-1">
        
                      </div>
                    </div>
                    <div class="d-flex justify-content-between mt-5">
                      <div class="h2 text-mute doctor_text">Patients</div>
                      <div class="ml-1 h1 doctor_number count_patients" style="color:#02b2b0 !important;">-</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><i class="fa fa-user" style="font-size:40px;color:#02b2b0 !important;"></i></div>
                      <div class="ms-auto lh-1">
                      <select class="date_select" onchange="counterFunction();" style="border-color: transparent;outline: none;">
                        <option value="{{date('Y-m-d')}}" selected>Today</option>
                        <option value="{{$monday}}">This Week</option>
                        <option value="{{date('Y-m-01')}}">This Month</option>
                        <option value="total">Total</option>
                      </select>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between mt-5">
                      <div class="h2 text-mute doctor_text">User</div>
                      <div class="ml-1 h1 doctor_number count_users" style="color:#02b2b0 !important;">-</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><i class="fas fa-parking" style="font-size:40px;color:#02b2b0 !important;"></i></div>
                      <div class="ms-auto lh-1">
                       <a href="{{url('/')}}/users/list" class="btn btn-secondary btn-sm">View Users</a>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between mt-5">
                      <div class="h2 text-mute doctor_text ">Pending Verification</div>
                      <div class="ml-1 h1 doctor_number count_pending_verification" style="color:#02b2b0 !important;">-</div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>

            <div class="col-12 mt-3">
                <div class="row row-cards">

                  <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span class="text-white avatar" style="background: #02b2b0 !important;">
                              <div class=""><i class="fa-solid fa-capsules" style="font-size:20px !important;"></i></div>
                            </span>
                          </div>
                          <div class="col">
                            <div class="d-flex justify-content-between mt-5">
                                <div class="h2 text-mute doctor_text">Total Medicines</div>
                                <div class="ml-1 h1 doctor_number count_medication" style="color:#02b2b0 !important;">-</div>
                            </div>
                          </div>
                          <div class="row mt-3 mb-3 text-center">
                            <i class="col">Most Use Medicines</i>
                            <div class="col">
                              <select class="doctor_select ml-auto mr-auto" data-name="medicine"  onchange="get_no_of_records(this);" style="border-color: transparent;outline: none;">
                                  <option value="" selected >select</option>
                                  <option value="5" >5</option>
                                  <option value="10" >10</option>
                                  <option value="15" >15</option>
                              </select>
                            </div>
                          </div>
                          <div class=" text-center medicine_table">
                             
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span class="text-white avatar" style="background: #02b2b0 !important;">
                              <div class=""><i class="fa-solid fa-bacteria" style="font-size:20px !important;"></i></div>
                            </span>
                          </div>
                          <div class="col">
                            <div class="d-flex justify-content-between mt-5">
                                <div class="h2 text-mute doctor_text">Total Adverse Effects</div>
                                <div class="ml-1 h1 doctor_number count_adverse_effects" style="color:#02b2b0 !important;">-</div>
                            </div>
                          </div>
                          <div class="row mt-3 mb-3 text-center">
                            <i class="col">Top Adverse Effects</i>
                            <div class="col">
                              <select class="doctor_select ml-auto mr-auto" data-name="adverse_effect"  onchange="get_no_of_records(this);" style="border-color: transparent;outline: none;">
                                  <option value="" selected>select</option>
                                  <option value="5">5</option>
                                  <option value="10">10</option>
                                  <option value="15" >15</option>
                              </select>
                            </div>
                          </div>
                          <div class=" text-center  adverse_effect_table">
            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span class="text-white avatar" style="background: #02b2b0 !important;">
                              <div class=""><i class="fa-solid fa-virus-covid" style="font-size:20px !important;"></i></div>
                            </span>
                          </div>
                          <div class="col">
                            <div class="d-flex justify-content-between mt-5">
                                <div class="h2 text-mute doctor_text">Total Disorders</div>
                                <div class="ml-1 h1 doctor_number count_disorders" style="color:#02b2b0 !important;">-</div>
                            </div>
                          </div>
                          <div class="row mt-3 mb-3 text-center">
                            <i class="col">Top Disorders</i>
                            <div class="col">
                              <select class="da_select ml-auto mr-auto" data-name="disorder"  onchange="get_no_of_records(this);"  style="border-color: transparent;outline: none;">
                                  <option value="" selected>select</option>
                                  <option value="5">5</option>
                                  <option value="10">10</option>
                                  <option value="15" >15</option>
                              </select>
                            </div>
                          </div>
                          <div class=" text-center disorder_table">
                             
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
            </div>
        </div>
      </div>


@include('Admin.Includes.scripts')
<script>


function counterFunction() {

    $.ajax({
        headers: {
            "Accept": "application/json"
        },
        type: "POST",
        url: "{{url('api/counter-function')}}",
        data: {
            "date": $('.date_select').val(),
        },
        success: function(response) {

          $('.count_users').html(response.user);

          loader(false);
        },
        error: function(response) {
          loader(false);
        }

    });
}

function get_no_of_records(_this)
{

  loader(true);

  $.ajax({
        headers: {
            "Accept": "application/json"
        },
        type: "POST",
        url: "{{url('api/get-no-of-records')}}",
        data: {
            "name": $(_this).attr('data-name'),
            "no_of_records": $(_this).val(),
        },
        success: function(html) {
          $("."+$(_this).attr('data-name')+"_table").html(html);
          loader(false);
        },
        error: function(response) {
          loader(false);
        }

    });
}

$(document).ready(function() {

  loader(true);
  $.ajax({
        headers: {
            "Accept": "application/json"
        },
        type: "POST",
        url: "{{url('api/counter-function')}}",
        data: {
            "date": $('.date_select').val(),
        },
        success: function(response) {

          $('.count_users').html(response.user);
          $('.count_patients').html(response.patient);
          $('.count_pending_verification').html(response.pending_verification);
          $('.count_doctors').html(response.doctors);
          $('.count_adverse_effects').html(response.adverse_effects);
          $('.count_disorders').html(response.disorders);
          $('.count_medication').html(response.medication);

          loader(false);
        },
        error: function(response) {
          loader(false);
        }

    });

});


  </script>
@include('Admin.Includes.footer')
