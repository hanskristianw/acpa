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
            
            $persen_for_psi = $row3["mapel_persen_for_psi"] * 100;
            $persen_sum_psi = $row3["mapel_persen_sum_psi"] * 100;

            $persen_kog = $row3["mapel_persen_kog"] * 100;
            $persen_psi = $row3["mapel_persen_psi"] * 100;

            echo '
            <h5><u>Pengetahuan</u></h5>
            <label>% Formative Pengetahuan:</label>
            <input type="number" class="form-control form-control-sm mb-2" id="persen_for" name="persen_for" min="0" max="100" value='.$persen_for.' required>

            <label>% Summative Pengetahuan:</label> 
            <input type="number" class="form-control form-control-sm mb-4" id="persen_sum" name="persen_sum" min="0" max="100" value='.$persen_sum.' required>';

            echo '
            <h5><u>Ketrampilan</u></h5>
            <label>% Formative Ketrampilan:</label>
            <input type="number" class="form-control form-control-sm mb-2" id="persen_for_psi" name="persen_for_psi" min="0" max="100" value='.$persen_for_psi.' required>

            <label>% Summative Ketrampilan:</label> 
            <input type="number" class="form-control form-control-sm mb-4" id="persen_sum_psi" name="persen_sum_psi" min="0" max="100" value='.$persen_sum_psi.' required>';

            echo '
            <h5><u>Nilai Akhir</u></h5>
            <label>% Pengetahuan:</label>
            <input type="number" class="form-control form-control-sm mb-2" id="persen_kog" name="persen_kog" min="0" max="100" value='.$persen_kog.' required>

            <label>% Ketrampilan:</label> 
            <input type="number" class="form-control form-control-sm mb-2" id="persen_psi" name="persen_psi" min="0" max="100" value='.$persen_psi.' required>';


            echo '<input type="submit" name="submit_t_ajaran" class="btn btn-primary mt-3" value="UPDATE">';
        }
    }
?>
