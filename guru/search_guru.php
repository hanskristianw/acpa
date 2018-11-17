<?php

include ("../includes/db_con.php");

$search = $_POST['search'];

if(!empty($search)){
    $query = "SELECT guru_id, guru_name, guru_username, jabatan_id, jabatan_nama FROM guru, jabatan WHERE guru_active = 1 AND jabatan_id = guru_jabatan AND guru_name LIKE '$search%'";
    $search_query = mysqli_query($conn, $query);
    
    if(!$search_query){
        die('QUERY FAILED' . mysqli_error($connection));
    }
    
    
    while($row = mysqli_fetch_array($search_query)){
        echo"<tr>";
        echo"<td><a rel='".$row['guru_id']."' rel2='".$row['jabatan_id']."' class='link-guru' href='javascript:void(0)'>{$row['guru_name']}</a></td>";
        echo"<td>{$row['guru_username']}</td>";
        echo"<td>{$row['jabatan_nama']}</td>";
        echo"</tr>";
    }
}
?>
<script>
    //$("#container-guru").hide();

    $(".link-guru").on('click', function(){
        $("#container-guru").show();
        
        var id = $(this).attr("rel");
        var id2 = $(this).attr("rel2");
        
        $.post("guru/proses_guru.php",{id: id, id2:id2}, function(data){
            $("#container-guru").html(data);
        });
        
    });
</script>

