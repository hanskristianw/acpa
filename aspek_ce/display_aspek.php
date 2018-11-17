<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 4){
        header("Location: ../index.php");
    }
    
    include ("../includes/db_con.php");

    $query = "SELECT *
            from ce
            LEFT JOIN t_ajaran 
            ON ce_t_ajaran_id = t_ajaran_id
            WHERE t_ajaran_active = 1";
    $query_ssp_info = mysqli_query($conn, $query);

    if(!$query_ssp_info){
        die("QUERY FAILED".mysqli_error($conn));
    }

    while($row = mysqli_fetch_array($query_ssp_info)){
        echo"<tr>";
        echo"<td><a rel='".$row['ce_id']."' class='link-ssp' href='javascript:void(0)'>{$row['ce_aspek']}</a></td>";
        echo"<td>{$row['ce_a']}</td>";
        echo"<td>{$row['ce_b']}</td>";
        echo"<td>{$row['ce_c']}</td>";
        echo"</tr>";
    }
?>

<script>
    //$("#container-guru").hide();

    $(".link-ssp").on('click', function(){
        $("#container-ssp").show();
        
        var ce_id = $(this).attr("rel");
        
        $.post("aspek_ce/proses_aspek_ce.php",{ce_id: ce_id}, function(data){
            $("#container-ssp").html(data);
        });
        
    });
</script>
