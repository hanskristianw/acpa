<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");

    $query = "SELECT * from karakter";

    $query_k_afektif_info = mysqli_query($conn, $query);
    
    if(!$query_k_afektif_info){
        die("QUERY FAILED".mysqli_error($conn));
    }

    echo"<table class='table table-sm table-striped mb-2 mt-2' id='tabel_kriteria'>
        <thead>
            <tr>
                <th>Nama Karakter</th>
                <th>Jika A</th>
                <th>Jika B</th>
                <th>Jika C</th>
                <th>Urutan Cetak</th>
            </tr>
        </thead>
        <tbody>";

    
    while($row = mysqli_fetch_array($query_k_afektif_info)){
        echo"<tr>";
            echo"<td><a rel='".$row['karakter_id']."' class='link-karakter' href='javascript:void(0)'>{$row['karakter_nama']}</a></td>";
            echo"<td>{$row['karakter_a']}</td>";
            echo"<td>{$row['karakter_b']}</td>";
            echo"<td>{$row['karakter_c']}</td>";
            echo"<td>{$row['karakter_urutan']}</td>";
        echo"</tr>";
    }

    echo"</tbody>
        </table>";
?>

<script>
    $(".link-karakter").on('click', function(){
        $("#container-karakter").show();
        
        var karakter_id = $(this).attr("rel");
        
        $.post("karakter/proses_karakter.php",{karakter_id: karakter_id}, function(data){
            $("#container-karakter").html(data);
        });
        
    });
</script>
