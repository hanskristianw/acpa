<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    else{
        if(isset($_POST['mapel_id'])){
            $mapel_id = $_POST['mapel_id'];
            include '../includes/db_con.php';
            $sql3 = "SELECT *
                    FROM mapel
                    WHERE mapel_id = $mapel_id";
            $result3 = mysqli_query($conn, $sql3);

            $row3 = mysqli_fetch_assoc($result3);

            $persen_for = $row3["mapel_persen_for"] * 100;
            $persen_sum = $row3["mapel_persen_sum"] * 100;

            echo '
            <label>% Formative:</label>
            <input type="number" class="form-control form-control-sm mb-2" name="persen_for" min="1" max="100" value='.$persen_for.' required>

            <label>% Summative:</label> 
            <input type="number" class="form-control form-control-sm mb-2" name="persen_for" min="1" max="100" value='.$persen_sum.' required>';
        }
    }
?>
