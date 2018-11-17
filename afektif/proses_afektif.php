<?php

    include_once '../includes/db_con.php';
    
    if(isset($_POST['insertafektif'])){
        
        //pisahkan dan dapatkan mapel id dan kelas id
        list($mapel_id, $kelas_id) = explode('_', $_POST["mapel_kelas"]);
        
        //dapatkan afektif id pada tahun ajaran aktif
        $query = "SELECT k_afektif_id
                FROM k_afektif, t_ajaran 
                WHERE k_afektif_t_ajaran_id = t_ajaran_id AND
                    t_ajaran_active = 1 AND 
                    k_afektif_bulan = {$_POST["bulan_id"]}";
        $query_k_afektif_info = mysqli_query($conn, $query);
        if(!$query_k_afektif_info){
            die("QUERY FAILED".mysqli_error($conn));
        }
        while($row = mysqli_fetch_array($query_k_afektif_info)){
            $afektif_id = $row['k_afektif_id'];
        }
        
        //dapatkan nilai dan siswa id
        $afektif_nilai = $_POST['afek'];
        $siswa_id = $_POST['siswa_id'];
        
        $sql = "INSERT INTO afektif(afektif_k_afektif_id, afektif_nilai, afektif_mapel_id, afektif_siswa_id) VALUES "; 
        
        for ($i = 0; $i < count($afektif_nilai); $i++)
        {
            $sql .= "(";
            $sql .= "$afektif_id,";
            $sql .= "'$afektif_nilai[$i]',";
            $sql .= "$mapel_id,";
            $sql .= "$siswa_id[$i]";
            if($i<count($afektif_nilai)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        $query2 =    "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, afektif_nilai, afektif_id 
                        from afektif, siswa
                        WHERE afektif_k_afektif_id = $afektif_id AND afektif_mapel_id = {$mapel_id} AND afektif_siswa_id = siswa_id AND siswa_id_kelas = {$kelas_id} ORDER BY siswa_nama_depan";

        $query_afektif_info2 = mysqli_query($conn, $query2);
        $resultCheck = mysqli_num_rows($query_afektif_info2);
        if($resultCheck == 0){
            if (!mysqli_query($conn, $sql))
            {
                echo("<br> Error description: " . mysqli_error($conn));
            }
            else{
                echo '<div class="alert alert-success alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>Data berhasil disimpan</strong>
                    </div>';
            }
            mysqli_close($conn);
        }else{
            echo '<div class="alert alert-danger alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>Data sudah ada!</strong>
                    </div>';
        }
        
        //echo $sql;
        
        
    }
    
    if(isset($_POST['updateafektif'])){
        
        //pisahkan dan dapatkan mapel id dan kelas id
        list($mapel_id, $kelas_id) = explode('_', $_POST["mapel_kelas"]);
        
        //dapatkan afektif id pada tahun ajaran aktif
        $query = "SELECT k_afektif_id
                FROM k_afektif, t_ajaran 
                WHERE k_afektif_t_ajaran_id = t_ajaran_id AND
                    t_ajaran_active = 1 AND 
                    k_afektif_bulan = {$_POST["bulan_id"]}";
        $query_k_afektif_info = mysqli_query($conn, $query);
        if(!$query_k_afektif_info){
            die("QUERY FAILED".mysqli_error($conn));
        }
        while($row = mysqli_fetch_array($query_k_afektif_info)){
            $afektif_id = $row['k_afektif_id'];
        }
        
        //dapatkan nilai dan siswa id
        $afektif_nilai = $_POST['afek'];
        $afektif_id = $_POST['siswa_id'];
        
        $sql = "UPDATE afektif SET afektif_nilai = CASE "; 
        
        for ($i = 0; $i < count($afektif_nilai); $i++)
        {
            $sql .= "WHEN afektif_id= ";
            $sql .= "$afektif_id[$i]";
            $sql .= " THEN ";
            $sql .= "'$afektif_nilai[$i]' ";
            if($i == count($afektif_nilai)-1)
            {$sql .= "ELSE afektif_nilai END";}
        }
        
        $sql .= " WHERE afektif_id in (";
        
        for ($i = 0; $i < count($afektif_nilai); $i++)
        {
            $sql .= $afektif_id[$i];
            if($i != count($afektif_nilai)-1)
            {$sql .= ",";}
        }
        
        $sql .= ")";
        
        //echo $sql;
        //mysqli_query($conn, $sql);
        
        if (!mysqli_query($conn, $sql))
        {
            echo("<br> Error description: " . mysqli_error($conn));
        }
        else{
            echo '<div class="alert alert-success alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>Data berhasil diupdate</strong>
                </div>';
        }
        mysqli_close($conn);
    }
?>

<script>
        
    $(document).ready(function(){
        
        
    });
</script>

