<?php
$i = 1;
$table_columns_count = count($table_columns);
$pagination_btn_number = ceil(($total_records/10));
$required_col = '';
$html_table_column = '';
$these_numbers=[];

function getNumbersAroundValue($value) {
    $result = [];
    
    for ($i = $value - 3; $i <= $value + 3; $i++) {
        $result[] = $i;
    }
    
    return $result;
}


?>

<div id="required_col" class="mt-3 mb-5">
<?php
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
        <button type="button" class="btn btn-primary btn-sm" onclick="_upload_csv('{{$module_id}}','{{$table_name}}','0',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}]);">search</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="_upload_csv('{{$module_id}}','{{$table_name}}','0','');">x</button>
        </div>
    <table id="uploadCsvModalTable" class="display table table-striped table-bordered">
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
                    $edit_id_field .="
                    <td>
                     <input 
                     type='text' 
                     value='".$table_data[$i][$table_columns[$j]]."'
                     name='".$table_columns[$j]."'
                     disabled 
                     class='edit_field disabled_style'
                     style='width:100% !important;'
                     />
                     </td>
                     ";
                  }

                 
                 
                  $PREMS = "'".$table_data[$i]["id"]."','".$table_name."','".$module_id."'";

                  $edit_id_field .= '<td><div class=" text-dark dropdown">
                     <a class="text-dark" href="#navbar-users" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                     <span class="d-inline-block">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                     <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                     </svg>                 
                     </span>
                     </a>
                     <div class="dropdown-menu">
                     <a class="dropdown-item" role="button" onclick="_edit_csv_data(this);">Edit</a>
                     <a class="dropdown-item" role="button" onclick="_delete_csv_data('.$PREMS.');"  >Delete</a>
                     </div>
                     </div></td></tr>';
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
    </table>

    <div class="pagination-class">
        <a role="button" onclick="_upload_csv('{{$module_id}}','{{$table_name}}','{{(1-1)*10}}',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}]);" >First</a>
        
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
            <a role="button" onclick="_upload_csv('{{$module_id}}','{{$table_name}}','{{($i-1)*10}}',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}]);" class="p-btn<?php if( ($from+1) >= (($i-1)*10+1) && ($from+1) <= ((($i-1)*10)+10) ){ echo  ' active'; } ?>"   >{{$i}}</a>
        @endif
        @endforeach
        
        <a role="button" onclick="$('.active').next('.p-btn').trigger('onclick');"  >Next</a>
        
        <a role="button" onclick="_upload_csv('{{$module_id}}','{{$table_name}}','{{($max_i-1)*10}}',[{key:$('#fillter_key').val(),value:$('#fillter_value').val()}]);" >Last</a>


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
  background-color: lightgray;
  color: black;
}

.pagination-class a:hover:not(.active) {background-color: #ddd;}
                    </style>