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
            FROM mapel_khusus_master
            LEFT JOIN mapel
            ON mapel_k_m_mapel_id = mapel_id
            LEFT JOIN t_ajaran
            ON mapel_k_m_t_ajaran_id = t_ajaran_id
            WHERE t_ajaran_active = 1";
    $query_ssp_info = mysqli_query($conn, $query);

    if(!$query_ssp_info){
        die("QUERY FAILED".mysqli_error($conn));
    }

    while($row = mysqli_fetch_array($query_ssp_info)){
        echo"<tr>";
        echo"<td>{$row['mapel_nama']}</td>";
        echo"<td><a rel='".$row['mapel_k_m_id']."' rel2='".$row['mapel_k_m_mapel_id']."' class='link-ssp' href='javascript:void(0)'>{$row['mapel_k_m_nama']}</a></td>";
        echo"</tr>";
    }
?>

<script>
    //$("#container-guru").hide();

    $(".link-ssp").on('click', function(){
        $("#container-ssp").show();
        
        var mapel_k_m_id = $(this).attr("rel");
        var mapel_k_m_mapel_id = $(this).attr("rel2");
        
        $.post("mapel_khusus/proses_mapel_khusus.php",{mapel_k_m_id: mapel_k_m_id, mapel_k_m_mapel_id:mapel_k_m_mapel_id}, function(data){
            $("#container-ssp").html(data);
        });
        
    });
</script>
