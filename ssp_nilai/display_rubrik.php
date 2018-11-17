<?php
    session_start();
    
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    
    include ("../includes/db_con.php");

    $ssp_id = $_POST["ssp_id"];
    
    $query = "SELECT * from d_ssp where d_ssp_ssp_id = $ssp_id";
    $query_guru_info = mysqli_query($conn, $query);

    if(!$query_guru_info){
        die("QUERY FAILED".mysqli_error($conn));
    }
    
    echo '<h4 class="text-center"><u>Daftar Rubrik</u></h4>';
    
    echo '<table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Nama Rubrik</th>
                    <th>Jika A</th>
                    <th>Jika B</th>
                    <th>Jika C</th>
                </tr>
            </thead>';
    
    while($row = mysqli_fetch_array($query_guru_info)){
        echo"<tr>";
        echo"<td><a rel='".$row['d_ssp_id']."' class='link-d_ssp' href='javascript:void(0)'>{$row['d_ssp_kriteria']}</a></td>";
        echo"<td>{$row['d_ssp_a']}</td>";
        echo"<td>{$row['d_ssp_b']}</td>";
        echo"<td>{$row['d_ssp_c']}</td>";
        echo"</tr>";
    }
    
    echo '</table>';
?>

<script>
    //$("#container-guru").hide();

    $(".link-d_ssp").on('click', function(){
        $("#container-rubrik").show();
        
        var d_ssp_id = $(this).attr("rel");
        //var id2 = $(this).attr("rel2");
        
        $.post("ssp_nilai/proses_rubrik.php",{d_ssp_id: d_ssp_id}, function(data){
            $("#container-rubrik").html(data);
        });
        
    });
</script>