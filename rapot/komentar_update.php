<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] == 4 || $_SESSION['guru_jabatan'] == 3){
        header("Location: index.php");
    }
?>

<?php
    if(isset($_POST['kelas_id'])){
        
        include ("../includes/db_con.php");
        
        $siswa_id = mysqli_real_escape_string($conn, $_POST['option_siswa']);
        $siswa_komen = mysqli_real_escape_string($conn, $_POST['siswa_komen']);
        $siswa_komen_akhir = mysqli_real_escape_string($conn, $_POST['siswa_komen_akhir']);
        $siswa_absenin = mysqli_real_escape_string($conn, $_POST['siswa_absenin']);
        $siswa_absenex = mysqli_real_escape_string($conn, $_POST['siswa_absenex']);
        $siswa_tardy = mysqli_real_escape_string($conn, $_POST['siswa_tardy']);
        $siswa_special_note = mysqli_real_escape_string($conn, $_POST['siswa_special_note']);
        
        $query_updaterubrik = "UPDATE siswa SET siswa_komen = '$siswa_komen',siswa_komen_akhir = '$siswa_komen_akhir', siswa_absenin = $siswa_absenin, siswa_absenex = $siswa_absenex, siswa_tardy = $siswa_tardy, siswa_special_note = '$siswa_special_note' WHERE siswa_id = $siswa_id";
        $result_setrubrik = mysqli_query($conn, $query_updaterubrik);
        
        if(!$result_setrubrik){
            die("QUERY FAILED".mysqli_error($conn));
        }
        else{
            echo '<div class="alert alert-success alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>Info:</strong> Data berhasil diupdate
                  </div>';
        }
    }

?>

