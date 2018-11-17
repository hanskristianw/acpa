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
            FROM jenjang";
    
    $query_kelas_info = mysqli_query($conn, $query);

    if(!$query_kelas_info){
        die("QUERY FAILED".mysqli_error($conn));
    }

    while($row = mysqli_fetch_array($query_kelas_info)){
        echo"<tr>";
        echo"<td><a rel='".$row['jenjang_id']."' class='link-jenjang' href='javascript:void(0)'>{$row['jenjang_nama']}</a></td>";
        echo"</tr>";
    }
?>

<script>

    $(".link-jenjang").on('click', function(){
        $("#container-jenjang").show();
        
        var id = $(this).attr("rel");
        
        $.post("jenjang/proses_jenjang.php",{id: id}, function(data){
            $("#container-jenjang").html(data);
        });
        
    });
</script>
