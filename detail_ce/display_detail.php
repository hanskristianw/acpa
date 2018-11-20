<?php

    include ("../includes/db_con.php");
    
    $ce_id = $_POST['ce_id'];
    
    if($ce_id > 0) {

        //cek pernah isi atau belum
        
        $query =    "SELECT *
                    from d_ce
                    LEFT JOIN ce 
                    ON d_ce_ce_id = ce_id
                    LEFT JOIN t_ajaran 
                    ON ce_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1 AND ce_id = $ce_id";

        $query_afektif_info = mysqli_query($conn, $query);
          while($row = mysqli_fetch_array($query_afektif_info)){

            echo'<tr>';
                echo"<td><a rel='".$row['d_ce_id']."' class='link-ssp' href='javascript:void(0)'>{$row['d_ce_nama']}</a></td>";
                echo"<td>{$row['d_ce_a']}</td>";
                echo"<td>{$row['d_ce_b']}</td>";
                echo"<td>{$row['d_ce_c']}</td>";
            echo'</tr>';
          }
    }
?>

<script>
    //$("#container-guru").hide();

    $(".link-ssp").on('click', function(){
        $("#container-ssp").show();
        
        var d_ce_id = $(this).attr("rel");
        
        $.post("detail_ce/proses_detail.php",{d_ce_id: d_ce_id}, function(data){
            $("#container-ssp").html(data);
        });
        
    });
</script>