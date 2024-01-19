<script>

function formatDate(date) {
   date = new Date(date);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();

  return `${day}-${month}-${year}`;
}
    function loader(show) {

        if(show == true)
        {
        $('.full_loader').removeClass('d-none');
        }
        else
        {
        $('.full_loader').addClass('d-none');
        }
    }


   function logout(e){

    var this_token = "Bearer @if(session()->has('token')){{session('token')}}@endif"
          $.ajax({
              headers: {
                  "Accept": "application/json",
                  "Authorization": this_token
              },
              type: "POST",
              url: "{{url('api/logout')}}",
              success: function(response) {
                  js_logout_btn(e);
                  toastr.success(response.message);

                  window.location.href = "{{url('/')}}";


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


    function js_logout_btn(e)
    {

        e.preventDefault();

        history.pushState({
            state: 'data1'
        }, 'title1', '/');

        history.pushState({
            state: 'data2'
        }, 'title2', '/');

        history.pushState({
            state: 'data3'
        }, 'title3', '/');

        history.pushState({
            state: 'data4'
        }, 'title4', '/');

        history.pushState({
            state: 'data5'
        }, 'title5', '/');

        history.pushState({
            state: 'data6'
        }, 'title6', '/');

        history.pushState({
            state: 'data7'
        }, 'title7', '/');

        history.pushState({
            state: 'data8'
        }, 'title8', '/');

        history.pushState({
            state: 'data9'
        }, 'title9', '/');

        history.pushState({
            state: 'data10'
        }, 'title10', '/');

        window.location.href = "/logout";

    }

    $(document).ready(function() {

            $('.show_notification').on('click', function () {
                get_notification(0,10);
            });

            $('.page-wrapper').on('click', function () {
                $('.notification_menu').css('display','none');
            });

            count_notification();

    });


    setInterval(function() {
        count_notification();
 }, 90000);



 function count_notification()
 {
            $.ajax({
            headers: {
            "Accept": "application/json"
            },
            type: "POST",
            url: "{{url('api/count-notifications')}}",
            data: {
            "id" : "0",
            },
            success: function(response) {
              
                
                if(response.data.count)
                {
                    $('.notification_dot').show();
                }
                else
                {
                    $('.notification_dot').hide();
                }
            }
            });

 }


 function get_notification(from,to)
 {
            $.ajax({
            headers: {
            "Accept": "application/json"
            },
            type: "POST",
            url: "{{url('api/get-notifications')}}",
            data: {
            "id" : "0",
            "from": from, 
            "to" : to,     
            },
            success: function(response) {

                console.log("response.data =>", response.data);
                console.log("from =>", from);
                console.log("to =>", to);

                var notifications = response.data.notifications;
                if(notifications.length)
                {
                  var  html = ``;
                  var _style = "";
                  var notifications_date = "";
                        for(i=0;i<notifications.length;i++)
                        {

                            if(notifications[i].receiver == "0" )
                            {
                                _style =  notifications[i].seen_by_receiver == '0'  ?  'background: beige' : '';
                            }
                            else if(notifications[i].sender == "0" )
                            {
                                _style =  notifications[i].seen_by_sender == '0'  ?  'background: beige' : '';
                            }

                            


                          html +=`<div no="${notifications[i].id}" role="button" onclick="move_to_url('${notifications[i].type}','${notifications[i].id}');" class="list-group-item" style="${_style}" >
                            <div class="row align-items-center">
                            <div class="col text-truncate">
                                <div class="d-flex justify-content-between">
                                <div  class="text-body d-block">${notifications[i].title}</div>
                                <div  class="text-body d-block text-secondary" style="font-size:12px;">${formatDate(notifications[i].date)}</div>
                                </div>
                                <div class="d-block text-secondary text-truncate mt-n1">
                                ${notifications[i].short_desc}
                                </div>
                            </div>
                            </div>
                            </div>`;
                        }

                        
                        if(from == 0)
                        {
                            $('.append_notifications').html('');
                        }
                       
                        $('.append_notifications').append(html);
                        $('.load_more').attr('from',(10+parseInt(from)));
                        $('.load_more').attr('to',10);
                        $('.load_more').css('display','block');

                }
                else
                {
                    if(from == 0)
                        {
                                $('.append_notifications').html('<center style="margin-top: 210px!important;">No Notification Yet</center>');
                                $('.load_more').css('display','none');
                        }
                        else
                        {
                            $('.load_more').css('display','none');

                        }
                }

                $('.notification_menu').css('display','block');
               
            }
            });

 }

 function move_to_url(type,id)
{
    var url = ""

    if(type == "support_resolved" || type == "support_store" )
    {
        url = "{{url('/general-settings/supports/list')}}?notify_id="+id;
    }
    else if(type == "request_to_doctor" || type == "request_to_doctor_accepted" || type == "request_to_doctor_accepted_by_patient")
    {
        url = "{{url('/general-settings/request-updates/list')}}?notify_id="+id;
    }

    $.ajax({
            headers: {
            "Accept": "application/json"
            },
            type: "POST",
            url: "{{url('api/single-notifications-mark-as-read')}}",
            data: {
            "id" : "0",
            "notification_id" : id
            },
            success: function(response) {
              
                window.location.href = url
            }
            });

    
}

function all_read()
{
    $.ajax({
            headers: {
            "Accept": "application/json"
            },
            type: "POST",
            url: "{{url('api/all-notifications-mark-as-read')}}",
            data: {
            "id" : "0"
            },
            success: function(response) {
                $('.notification_menu').css('display','none');
                $('.notification_dot').css('display','none');
                
            }
            });

    
}



</script>  

<style>
    .dataTables_length
    {
      margin-bottom: 5px;
    }

    input[type="date"]::-webkit-datetime-edit, input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-clear-button {
        color: #fff;
        position: relative;
    }

    input[type="date"]::-webkit-datetime-edit-day-field{
        position: absolute !important;
        color:#000;
        padding: 2px;
        padding-right: 0px !important;
        left: 4px;
    }

    input[type="date"]::-webkit-datetime-edit-month-field{
        position: absolute !important;
        border-left:1px solid #8c8c8c;
        padding: 2px;
        color:#000;
        left: 28px;
    }

    input[type="date"]::-webkit-datetime-edit-year-field{
        position: absolute !important;
        border-left:1px solid #8c8c8c;
        padding: 2px;
        color:#000;
        left: 56px;
    }


thead th 
{
   font-size: 13px !important; 
}

thead th button
{
   --tblr-btn-font-size: 0.75rem;
}

thead th button,thead th input,thead th select
{
   margin-top: 3px;
}

.filter_inputs::placeholder {
  color: black !important;
}


@media (min-width: 1200px){
    
.container, .container-lg, .container-md, .container-sm, .container-xl {
    max-width: 1366px;
}

}
</style>

  
  </body>
</html>

