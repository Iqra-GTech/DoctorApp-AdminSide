<?php
$i = 1;
$table_columns_count = count($table_columns);
$pagination_btn_number = ceil(($total_records/10));
$required_col = '';
$html_table_column = '';
$these_numbers=[];
?>

<div id="required_col" class="mt-3 mb-5">
<?php

function getNumbersAroundValue($value) {
    $result = [];
    
    for ($i = $value - 3; $i <= $value + 3; $i++) {
        $result[] = $i;
    }
    
    return $result;
}


    foreach($table_columns as $table_column)
    {
            if($i < $table_columns_count)
            {
                $required_col .= $table_column.',';
            }
            else
            {
                $required_col .= $table_column;
            }
            $html_table_column .='<th class="text-transform:capitalize;">'.ucwords(str_replace("_", " ", $table_column)).'</th>';
            $i++;
    }
    echo '<b>List of columns required:</b> '.$required_col;
    ?>
</div>
<div class="d-flex mb-3" style="justify-content: space-between; width: 37%;">
        <select id="fillter_key">
            <?php 
            
            foreach($fillter_dropdown as $fd)
            { 
            
            ?>
                <option value="<?php echo $fd['value']; ?>" <?php if($request_filter &&  $fd['value'] == $request_filter[0]['key'] ?? ""  ){ echo 'selected'; } ?> ><?php echo $fd['label']; ?></option>
            <?php 
            
            } 
            
            ?>
        </select>
        <input type="text" id="fillter_value" value="{{$request_filter[0]['value'] ?? ''}}" >
        <button type="button" class="btn btn-primary btn-sm" onclick="fetch_all_data('{{$module_id}}','{{$table_name}}','0',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}],getCookie('user_id'));">search</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="fetch_all_data('{{$module_id}}','{{$table_name}}','0','',getCookie('user_id'));">x</button>
        </div>
    <table id="uploadCsvModalTable" class="display">
        <thead>
            <tr id="csv_column_list">
                <th class="text-transform:capitalize;">Sr.</th>
                <?php echo $html_table_column; ?>
                <th class="text-transform:capitalize;">Action</th>
            </tr>
        </thead>
        <tbody id="csv_tbody">
            <?php
            $edit_id_field ="";
        for($i = 0; $i < count($table_data);$i++)
            {
                     $add_array = [];


                     $sr = $from+($i+1); 
                     $edit_id_field .="
                     <tr>
                     <td>
                     ".$sr." 
                     <span class='edit_box  d-none'>
                     <i 
                     class='fa-solid fa-check update_btn_check' 
                     data_id='".$table_data[$i]['id']."'
                     data_table='".$table_name."'
                     onclick='edit_section_data(this);' 
                     style='margin-left:10px;color: green; font-size: 18px;' 
                     role='button'
                     >
                     </i>
                     </span>
                     </td>
                     ";                    

                     
                  for($j = 0; $j<count($table_columns);$j++)
                  {
                    if($table_columns[$j] == "hide__or__show")
                    {
                        if($table_data[$i][$table_columns[$j]] == 1)
                        {
                            $edit_id_field .='<td><i role="button" onclick="hide_or_show('.$table_data[$i]["id"].',\''.$table_name.'\',0);"  style="font-size:20px;color:#02b2b0;" class="ms-4 text-center fa-sharp fa-solid fa-eye-slash" ></i></td>';

                        }
                        else
                        {
                            $edit_id_field .='<td><i role="button" onclick="hide_or_show('.$table_data[$i]["id"].',\''.$table_name.'\',1);"  style="font-size:20px;color:#02b2b0;" class="ms-4 text-center fa fa-eye" ></i></td>';

                        }

                    }
                    else  if($table_columns[$j] == "_Verify_")
                    {
                        if($table_data[$i][$table_columns[$j]] == '0'  ||  $table_data[$i][$table_columns[$j]] == "")
                        {
                            $edit_id_field .="<td><img  src='/notverify.png' /></td>";
                        }
                        else
                        {
                            $edit_id_field .="<td><img  data-bs-toggle='modal' data-bs-target='#verifyByModal' role='button' src='/verify.png' alt='verify' onclick='verify_by(".$table_data[$i][$table_columns[$j]].");' ></td>";
                        }
                    }
                    else  if(str_contains($table_columns[$j], 'date'))
                    {
                        $edit_id_field .="<td>".date("d-m-Y", strtotime($table_data[$i][$table_columns[$j]]))."</td>";
                    }
                    else{

                    $edit_id_field .="<td>".$table_data[$i][$table_columns[$j]]."</td>";

                    }
                  }

                 
                 
                  $PREMS = "'".$table_data[$i]["id"]."','".$table_name."','".$module_id."'";

                  $edit_id_field .= '<td>
                                <button class="btn badge bg-warning" type="submit" data-bs-toggle="modal" data-bs-target="#edit-model" onclick="fetchAndEditSection('.$table_data[$i]["id"].',\''.$table_name.'\','.$module_id.');">Edit</button>
                                <button class="btn badge bg-danger" onclick="_delete_csv_data('.$table_data[$i]["id"].',\''.$table_name.'\');">Delete</button>
                                </td></tr>';
            }

            if( $edit_id_field == "")
            {
               $edit_id_field = "<td class='text-center' colspan='";
               $edit_id_field .= $table_columns_count+2;
               echo $edit_id_field .= "' >No data available in table </td>";
            }
            else
            {
                echo $edit_id_field;
            }
                ?>
        </tbody>

        <tfoot>
            <tr id="csv_column_list">
                <th class="text-transform:capitalize;">Sr.</th>
                <?php echo $html_table_column; ?>
                <th class="text-transform:capitalize;">Action</th>
            </tr>
        </tfoot>
    </table>

       


    <div class="pagination-class  mt-2">
        <a role="button" onclick="fetch_all_data('{{$module_id}}','{{$table_name}}','{{(1-1)*10}}',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}],getCookie('user_id'));" >First</a>
        
        <a role="button" onclick="$('.active').prev('.p-btn').trigger('onclick');"   >Previous</a>
        
         <?php
            for($i=1; $i<= $pagination_btn_number;$i++){
                
                if(($from+1) >= (($i-1)*10+1) && ($from+1) <= ((($i-1)*10)+10))
                {
                    $these_numbers =   getNumbersAroundValue($i);
                }
            } 
            
            $max_i = $i-1;
         ?>
        
        
        @foreach($these_numbers as $i)
        @if($i >= 1 && $i <= $max_i)
            <a role="button" onclick="fetch_all_data('{{$module_id}}','{{$table_name}}','{{($i-1)*10}}',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}],getCookie('user_id'));" class="p-btn<?php if( ($from+1) >= (($i-1)*10+1) && ($from+1) <= ((($i-1)*10)+10) ){ echo  ' active'; } ?>"   >{{$i}}</a>
        @endif
        @endforeach
        
        <a role="button" onclick="$('.active').next('.p-btn').trigger('onclick');"  >Next</a>
        
        <a role="button" onclick="fetch_all_data('{{$module_id}}','{{$table_name}}','{{($max_i-1)*10}}',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}],getCookie('user_id'));" >Last</a>


    </div>
 

        
<style>

 .pagination-class {
  display: inline-block;
  margin-left: auto;
}

.pagination-class a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination-class a.active {
  background-color: #02b2b0;
  color: white;
}

.pagination-class a:hover:not(.active) {background-color: #02b2b0;}
                    </style>