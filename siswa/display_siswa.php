<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");
    
    if(!empty($_POST["kelas_id"])) {
        $id_kelas = $_POST["kelas_id"];
        $query = "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, kelas_id
                    FROM siswa
                    LEFT JOIN kelas
                    ON siswa_id_kelas = kelas_id
                    LEFT JOIN t_ajaran
                    ON kelas_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1 AND siswa_id_kelas = $id_kelas";
        $query_siswa_info = mysqli_query($conn, $query);

        if(!$query_siswa_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        while($row = mysqli_fetch_array($query_siswa_info)){
            echo"<tr>";
            echo"<td>{$row['siswa_no_induk']}</td>";
            echo"<td><a rel='".$row['siswa_id']."' rel2='".$row['kelas_id']."' class='link-siswa' href='javascript:void(0)'>{$row['siswa_nama_depan']} {$row['siswa_nama_belakang']}</a></td>";
            echo"</tr>";
        }
    }
?>

<script>

    $(".link-siswa").on('click', function(){
        $("#container-siswa").show();
        
        var id = $(this).attr("rel");
        var id2 = $(this).attr("rel2");
        
        $.post("siswa/proses_siswa.php",{id: id, id2:id2}, function(data){
            $("#container-siswa").html(data);
        });
        
    });
</script>
