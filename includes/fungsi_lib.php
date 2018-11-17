<?php
    function return_abjad_lifeskill($nilai){
        
        $abjad_nilai = "";
        if(round($nilai)>=3){
            $abjad_nilai = "A";
        }elseif(round($nilai)>=2){
            $abjad_nilai = "B";
        }elseif(round($nilai)>=1){
            $abjad_nilai = "C";
        }
        
        return $abjad_nilai;
    }
    
    function return_combo_kelas($guru_id){
        include ("db_con.php");
        
        if($guru_id == 0){
            $query =    "SELECT *
                    FROM kelas
                    LEFT JOIN t_ajaran
                    ON kelas_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1";

            $query_info = mysqli_query($conn, $query);

            $options = "<option value= 0>Pilih Kelas</option>";
             while($row = mysqli_fetch_array($query_info)){
                $kelas_nama = $row['kelas_nama'];

                $options .= "<option value={$row['kelas_id']}>$kelas_nama</option>";
             }

            echo "<label>Pilih Kelas:</label>"; 
            echo"<select class='form-control form-control-sm mb-2' name='option_kelas' id='option_kelas'>";
                echo $options;
            echo"</select>";
        }
        else{
            
        }
        return $options;
    }

