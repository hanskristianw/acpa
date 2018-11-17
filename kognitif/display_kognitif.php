<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    
    if(!empty($_POST["mapel_id"])) {
        
        $mapel_id = $_POST["mapel_id"];
        
        if($mapel_id != 0){
            include ("../includes/db_con.php");

            $query = "SELECT * FROM topik, jenjang WHERE topik_mapel_id = $mapel_id AND topik_jenjang_id = jenjang_id ORDER BY topik_jenjang_id, topik_id";
            $query_guru_info = mysqli_query($conn, $query);

            if(!$query_guru_info){
                die("QUERY FAILED".mysqli_error($conn));
            }

            while($row = mysqli_fetch_array($query_guru_info)){
                echo"<tr>";
                echo"<td>{$row['topik_urutan']}</td>";
                echo"<td>{$row['jenjang_nama']}</td>";
                echo"<td><a rel='".$row['topik_id']."' rel2='".$row['topik_jenjang_id']."' class='link-topik' href='javascript:void(0)'>{$row['topik_nama']}</a></td>";
                echo"</tr>";
            }
        }
    }
    
?>

<script>

    $(".link-topik").on('click', function(){
        $("#container-topik").show();
        
        var id = $(this).attr("rel");
        var id2 = $(this).attr("rel2");
        
        $.post("kognitif/proses_kognitif.php",{id: id, id2: id2}, function(data){
            $("#container-topik").html(data);
        });
        
    });
</script>