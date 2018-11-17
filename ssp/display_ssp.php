<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: ../index.php");
    }
    
    include ("../includes/db_con.php");

    $query = "SELECT *
            from ssp
            LEFT JOIN guru 
            ON ssp_guru_id = guru_id
            LEFT JOIN t_ajaran 
            ON ssp_t_ajaran_id = t_ajaran_id
            WHERE t_ajaran_active = 1";
    $query_ssp_info = mysqli_query($conn, $query);

    if(!$query_ssp_info){
        die("QUERY FAILED".mysqli_error($conn));
    }

    while($row = mysqli_fetch_array($query_ssp_info)){
        echo"<tr>";
        echo"<td><a rel='".$row['ssp_id']."' rel2='".$row['ssp_guru_id']."' class='link-ssp' href='javascript:void(0)'>{$row['ssp_nama']}</a></td>";
        echo"<td>{$row['guru_name']}</td>";
        echo"</tr>";
    }
?>

<script>
    //$("#container-guru").hide();

    $(".link-ssp").on('click', function(){
        $("#container-ssp").show();
        
        var ssp_id = $(this).attr("rel");
        var guru_id = $(this).attr("rel2");
        
        $.post("ssp/proses_ssp.php",{ssp_id: ssp_id, guru_id:guru_id}, function(data){
            $("#container-ssp").html(data);
        });
        
    });
</script>
