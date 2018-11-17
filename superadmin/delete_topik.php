<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
?>

<?php

    if(isset($_POST['option_topik'])){
        
        
        include ("../includes/db_con.php");
        
        $mapel_id = $_POST['mapel_id_option'];
        $topik_id = $_POST['option_topik'];
        
        if($mapel_id > 0 && $topik_id>0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu

            echo $topik_id;
            echo "<br>";
            $query2 =    "SELECT *
                        FROM kog_psi
                        WHERE kog_psi_topik_id= $topik_id";

            $query_info2 = mysqli_query($conn, $query2);
            $resultCheck = mysqli_num_rows($query_info2);
            
            echo "Pada tabel kog_psi terdapat: ".$resultCheck;
            echo "<br>";
            
            while($row2 = mysqli_fetch_array($query_info2)){
                echo $row2['kog_psi_id'];
                echo ",";
            }
            echo "<br>";
        }
    }
?>

<script>
    $(document).ready(function(){
        
    });
</script>