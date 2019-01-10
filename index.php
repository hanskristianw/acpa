<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>

<?php
  include_once 'header.php'
?>

<?php
    if(!isset($_SESSION['guru_jabatan'])) 
    { 
        echo'
            <div class="container h-100" style="margin-top: 100px; padding-left: 30px; padding-right: 30px;">
            <div style="margin-top:50px;"></div>
                <div class="row h-100 justify-content-center align-items-center">
                    <img src="pic/nsa logo.jpg">
                    <h3 class="text-justify mt-5">Selamat datang, silahkan login untuk dapat memasuki sistem RAPOR NSA</h3>`  
                </div>
            </div>
            <div class="container h-100" style="margin-top: 200px;">
            </div>
        ';
    }
    elseif(isset($_SESSION['guru_jabatan'])) 
    {
        $nama = $_SESSION['guru_name'];
        $jabatan_nama = $_SESSION['jabatan_nama'];

        echo'
            <div class="container h-100" style="margin-top: 100px; padding-left: 30px; padding-right: 30px;">
                <div class="row h-100 justify-content-center align-items-center">
                    <h3 class="text-justify mt-5">Selamat datang, '.$nama.' jabatan anda adalah '.$jabatan_nama.' anda berhak untuk:</h3>`
                </div>
            </div>'; 

        if($_SESSION['guru_jabatan'] == 1){
            //Wakasek
            echo '<div class="container h-100" style="margin-top: 30px;">
                    <div class="row h-100 justify-content-center align-items-center">
                        <div class="span4">
                            <img style="float:left" src="pic/check.png"/>
                            <div class="content-heading"><h4>AKSES MENU WAKASEK</h4></div>
                        </div>
                        <div class="span4">
                            <img style="float:left" src="pic/check.png"/>
                            <div class="content-heading"><h4>AKSES MENU CETAK RAPOT</h4></div>
                        </div>  
                        <div class="span4">
                            <img style="float:left" src="pic/check.png"/>
                            <div class="content-heading"><h4>AKSES NILAI SESUAI MAPEL YANG DIAJAR</h4></div>
                        </div>
                    </div>
                </div>';
        }
        elseif($_SESSION['guru_jabatan'] == 2){
            //Wali Kelas
            echo '<div class="container h-100" style="margin-top: 30px;">
                    <div class="row h-100 justify-content-center align-items-center">
                        <div class="span4">
                            <img style="float:left" src="pic/check.png"/>
                            <div class="content-heading"><h4>AKSES MENU CETAK RAPOT SESUAI KELAS</h4></div>
                        </div>  
                        <div class="span4">
                            <img style="float:left" src="pic/check.png"/>
                            <div class="content-heading"><h4>AKSES NILAI SESUAI MAPEL YANG DIAJAR</h4></div>
                        </div>
                    </div>
                </div>';
        }
        elseif($_SESSION['guru_jabatan'] == 3){
            //Guru
            echo '<div class="container h-100" style="margin-top: 30px;">
                    <div class="row h-100 justify-content-center align-items-center">
                        <img style="float:left" src="pic/check.png"/>
                        <div class="content-heading"><h4 class="ml-1">AKSES NILAI SESUAI MAPEL YANG DIAJAR</h4></div>
                    </div>
                </div>';
        }
        elseif($_SESSION['guru_jabatan'] == 4){
            //BK
            echo '<div class="container h-100" style="margin-top: 30px;">
                    <div class="row h-100 justify-content-center align-items-center">
                        <img style="float:left" src="pic/check.png"/>
                        <div class="content-heading"><h4 class="ml-1">AKSES MENU BIMBINGAN KONSELING</h4></div>
                    </div>
                </div>';
        }
        elseif($_SESSION['guru_jabatan'] == 5){
            //Kesiswaan
            echo '<div class="container h-100" style="margin-top: 30px;">
                    <div class="row h-100 justify-content-center align-items-center">
                        <div class="span4">
                            <img style="float:left" src="pic/check.png"/>
                            <div class="content-heading"><h4>AKSES NILAI SIKAP</h4></div>
                        </div>  
                        <div class="span4">
                            <img style="float:left" src="pic/check.png"/>
                            <div class="content-heading"><h4>AKSES NILAI SESUAI MAPEL YANG DIAJAR</h4></div>
                        </div>
                    </div>
                </div>';
        }
    }
?>

<?php 
   include_once 'footer.php'
?>
