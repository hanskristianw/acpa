<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="CSS/customCSS_preview.css">
    <title>CPA NSA</title>
    <!-- </head> -->
</head>
<body>
    <!--##################START HERE###################-->

    <!-- NAVBAR WITH FORM -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary mb-3">
        <div class="container">
          <a class="navbar-brand" href="index.php">CPA NSA</a>
          <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav mr-auto">
                  <?php
                    include ("includes/fungsi_lib.php"); 
                    if(isset($_SESSION['guru_jabatan']))
                    {
                        $jabatan = $_SESSION['guru_jabatan'];
                        $guru_id = $_SESSION['guru_id'];
                        //wakasek
                        if ($jabatan == 1){
                          echo'<li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                            <div class="dropdown-menu">
                              <h6 class="dropdown-header">Setting</h6>
                              <a href="t_ajaran.php" class="dropdown-item fa fa-caret-right mb-1"> Tahun Ajaran</a>
                              <a href="guru.php" class="dropdown-item fa fa-caret-right mb-1"> Guru</a>
                              <a href="jenjang.php" class="dropdown-item fa fa-caret-right mb-1"> Jenjang</a>
                              <a href="kelas.php" class="dropdown-item fa fa-caret-right mb-1"> Kelas</a>
                              <a href="siswa.php" class="dropdown-item fa fa-caret-right mb-1"> Siswa</a>
                              <a href="mapel_new.php" class="dropdown-item fa fa-caret-right mb-1"> Mata Pelajaran</a>
                              <a href="scout.php" class="dropdown-item fa fa-caret-right mb-1"> Scout</a>
                              <a href="karakter_mapel.php" class="dropdown-item fa fa-caret-right mb-1"> Karakter</a>
                              <a href="detail_karakter_mapel.php" class="dropdown-item fa fa-caret-right mb-1"> Karakter Pelajaran</a>
                              <a href="ssp.php" class="dropdown-item fa fa-caret-right mb-1"> SSP</a>
                              
                              <div class="dropdown-divider"></div>
                              <h6 class="dropdown-header">Mapel Khusus</h6>
                              <a href="mapel_khusus.php" class="dropdown-item fa fa-caret-right mb-1"> Mata Pelajaran</a>
                              <a href="mapel_khusus_daftar.php" class="dropdown-item fa fa-caret-right mb-1"> Daftarkan Siswa</a>
                              <a href="mapel_khusus_hapus.php" class="dropdown-item fa fa-caret-right mb-1"> Hapus Pendaftaran</a>
                              
                            ';
                              if(cekGuruExistInMapel($guru_id)){
                                  echo'<div class="dropdown-divider"></div>
                                  <h6 class="dropdown-header">Mapel Yang Diajar</h6>
                                  <a href="topik_kognitif.php" class="dropdown-item fa fa-caret-right mb-1"> Topik</a>';
                                  echo'<a href="persen_for_sum.php" class="dropdown-item fa fa-caret-right mb-1"> % Formative dan Summative</a>';
                              }

                            echo'
                                      
                                </div>
                                </li>';
                            
                          echo '<li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                <div class="dropdown-menu">';

                              if(cekGuruExistInMapel($guru_id)){
                                echo'<a href="kognitif_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Quiz, Test, Assignment</a>
                                    <a href="uts_uas.php" class="dropdown-item fa fa-caret-right mb-1"> UTS & UAS</a>
                                    <a href="afektif.php" class="dropdown-item fa fa-caret-right mb-1"> Afektif</a>';
                              }

                              if(cekSspGuruId($guru_id)){
                                echo '<a href="ssp_nilai_input.php" class="dropdown-item fa fa-caret-right mb-1"> SSP</a>';
                              }

                              if(cekGuruExistInTajaranScout($guru_id)){
                                echo '<a href="scout_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Scout</a>';
                              }
                          
                          echo  '</div>
                                </li>
                              
                              <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Rapot</a>
                                <div class="dropdown-menu">
                                  <h6 class="dropdown-header">SISIPAN</h6>
                                  <a href="terima_rapot_sisipan.php" class="dropdown-item fa fa-caret-right mb-1"> Set Rapot Sisipan</a>
                                  <a href="rapot_waka_sisipan.php" class="dropdown-item fa fa-caret-right mb-1"> Cetak Rapot Sisipan</a>
                                  <div class="dropdown-divider"></div>
                                  <h6 class="dropdown-header">SEMESTER</h6>
                                  <a href="terima_rapot.php" class="dropdown-item fa fa-caret-right mb-1"> Set Rapot Semester</a>
                                  <a href="rapot_waka.php" class="dropdown-item fa fa-caret-right mb-1"> Cetak Rapot Semester</a>
                                  <a href="analisis_rapor.php" class="dropdown-item fa fa-caret-right mb-1"> Analisis Rapot Semester</a>
                                </div>
                              </li>
                              
                              ';
                              
                              echo '
                                  <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Revisi</a>
                                    <div class="dropdown-menu">
                                      <a href="revisi.php" class="dropdown-item fa fa-caret-right mb-1"> Daftar Revisi</a>
                                      <a href="" class="dropdown-item fa fa-caret-right mb-1"> History Revisi</a>
                                    </div>
                                  </li>
                                <li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                  <div class="dropdown-menu">
                                    <a href="lap_remidial.php" class="dropdown-item fa fa-caret-right mb-1"> Remidial</a>
                                    <a href="lap_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai</a>
                                    <a href="lap_ssp.php" class="dropdown-item fa fa-caret-right mb-1"> SSP</a>
                                    <a href="laporan_DKN.php" class="dropdown-item fa fa-caret-right mb-1"> DKN</a>
                                    <a href="buku_induk.php" class="dropdown-item fa fa-caret-right mb-1"> Buku Induk</a>
                                  </div>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                                </li>';
                        }
                        elseif ($jabatan == 2){
                          //Wali kelas
                          echo '<li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                                <div class="dropdown-menu">';

                                if(cekGuruExistInMapel($guru_id)){
                                  echo'<a href="topik_kognitif.php" class="dropdown-item fa fa-caret-right mb-1"> Topik</a>';
                                  echo'<a href="persen_for_sum.php" class="dropdown-item fa fa-caret-right mb-1"> % Formative dan Summative</a>';
                                }

                                if(cekSspGuruId($guru_id)){
                                  echo '<a href="ssp_kriteria.php" class="dropdown-item fa fa-caret-right mb-1"> Detail SSP</a>';
                                }

                          echo '</div>
                                </li>
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                <div class="dropdown-menu">
                                  <a href="p_fitness.php" class="dropdown-item fa fa-caret-right mb-1"> P Fitness & Healthful Habit</a>
                                  <a href="social_skill.php" class="dropdown-item fa fa-caret-right mb-1"> Social Skill</a>';

                                  if(cekGuruExistInMapel($guru_id)){
                                    echo'<a href="kognitif_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Quiz, Test, Assignment</a>
                                        <a href="uts_uas.php" class="dropdown-item fa fa-caret-right mb-1"> UTS & UAS</a>
                                        <a href="afektif.php" class="dropdown-item fa fa-caret-right mb-1"> Afektif</a>';
                                  }
                                  if(cekSspGuruId($guru_id)){
                                    echo '<a href="ssp_nilai_input.php" class="dropdown-item fa fa-caret-right mb-1"> SSP</a>';
                                  }

                                  if(cekGuruExistInTajaranScout($guru_id)){
                                    echo '<a href="scout_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Scout</a>';
                                  }
                          
                          echo '          
                                </div>
                                </li>
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Rapor</a>
                                <div class="dropdown-menu">
                                  <a href="komentar.php" class="dropdown-item fa fa-caret-right mb-1"> Input Komentar & Absen</a>
                                  <a href="rapot_walkel_sisipan.php" class="dropdown-item fa fa-caret-right mb-1"> Preview SISIPAN</a>
                                  <a href="rapot.php" class="dropdown-item fa fa-caret-right mb-1"> Preview SEMESTER</a>
                                </div>
                                </li>
                                
                                <li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                  <div class="dropdown-menu">
                                    <a href="lap_remidial.php" class="dropdown-item fa fa-caret-right mb-1"> Remidial</a>
                                    <a href="lap_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai</a>
                                  </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                                </li>';
                        }
                        elseif ($jabatan == 4){
                          //BK
                          echo'
                          <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                            <div class="dropdown-menu">
                              <a href="aspek_ce.php" class="dropdown-item fa fa-caret-right mb-1"> Topik CB</a>
                              <a href="detail_ce.php" class="dropdown-item fa fa-caret-right mb-1"> Indikator Topik CB</a>
                            </div>
                          </li>    

                          <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Bimbingan Konseling</a>
                            <div class="dropdown-menu">
                              <a href="kriteria.php" class="dropdown-item fa fa-caret-right mb-1"> Kriteria Afektif</a>
                              <a href="info_afektif.php" class="dropdown-item fa fa-caret-right mb-1"> Laporan Afektif</a>
                            </div>
                          </li>
                          
                          <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                            <div class="dropdown-menu">
                              <a href="emotional.php" class="dropdown-item fa fa-caret-right mb-1"> Emotional Awareness</a>
                              <a href="spirit.php" class="dropdown-item fa fa-caret-right mb-1"> Sprituality</a>
                              <a href="ce_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Character Building</a>
                            </div>
                          </li>

                          <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                            <div class="dropdown-menu">
                              <a href="rekap_cb.php" class="dropdown-item fa fa-caret-right mb-1"> Rekap Nilai</a>
                            </div>
                          </li>

                          <li class="nav-item">
                                  <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                          </li>';
                        }
                        elseif ($jabatan == 5){
                          //kesiswaan
                          echo  '<li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                                  <div class="dropdown-menu">';

                                    if(cekGuruExistInMapel($guru_id)){
                                      echo '<a href="topik_kognitif.php" class="dropdown-item fa fa-caret-right mb-1"> Topik</a>';
                                      echo'<a href="persen_for_sum.php" class="dropdown-item fa fa-caret-right mb-1"> % Formative dan Summative</a>';
                                    }

                                    if(cekSspGuruId($guru_id)){
                                      echo '<a href="ssp_kriteria.php" class="dropdown-item fa fa-caret-right mb-1"> Detail SSP</a>';
                                    }
                                    
                          echo  '</div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                    <div class="dropdown-menu">
                                      ';

                                    if(cekGuruExistInMapel($guru_id)){
                                      echo '<a href="kognitif_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Quiz, Test, Assignment</a>
                                            <a href="uts_uas.php" class="dropdown-item fa fa-caret-right mb-1"> UTS & UAS</a>
                                            <a href="afektif.php" class="dropdown-item fa fa-caret-right mb-1"> Afektif</a>
                                            <a href="moral.php" class="dropdown-item fa fa-caret-right mb-1"> Moral Behavior</a>';
                                    }

                                    if(cekSspGuruId($guru_id)){
                                      echo '<a href="ssp_nilai_input.php" class="dropdown-item fa fa-caret-right mb-1"> SSP</a>';
                                    }

                                    if(cekGuruExistInTajaranScout($guru_id)){
                                      echo '<a href="scout_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Scout</a>';
                                    }
                          echo  '</div>
                                </li>';
                          if(cekGuruExistInMapel($guru_id)){
                            echo'<li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                  <div class="dropdown-menu">
                                    <a href="lap_remidial.php" class="dropdown-item fa fa-caret-right mb-1"> Remidial</a>
                                    <a href="lap_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai</a>
                                  </div>
                                </li>';
                          }    
                          echo'<li class="nav-item">
                                        <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                                </li>';
                        }
                        elseif ($jabatan == 6){
                          //superadmin
                          echo'<li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                  <div class="dropdown-menu">
                                    <a href="super_admin_cek_topik.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai Quiz, Tes, Ass</a>
                                    <a href="super_admin_cek_uts_uas.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai UTS, UAS</a>
                                    <a href="super_admin_cek_afektif.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai Afektif</a>
                                    <a href="super_admin_cek_ssp.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai SSP</a>
                                    <a href="super_admin_cek_cb.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai CB</a>
                                  </div>
                                </li>';

                          echo'<li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Admin</a>
                                  <div class="dropdown-menu">
                                    <a href="super_admin_pindahssp.php" class="dropdown-item fa fa-caret-right mb-1"> Pindah SSP</a>
                                    <a href="super_admin_susulssp.php" class="dropdown-item fa fa-caret-right mb-1"> Susulan Nilai SSP</a>
                                  </div>
                                </li>';      
                          echo'<li class="nav-item">
                                        <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                                </li>';
                        }
                        else{
                            //GURU (3)
                            echo '
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                                <div class="dropdown-menu">
                                  
                                  ';
                                if(cekGuruExistInMapel($guru_id)){
                                    echo'<a href="topik_kognitif.php" class="dropdown-item fa fa-caret-right mb-1"> Topik</a>';
                                    echo'<a href="persen_for_sum.php" class="dropdown-item fa fa-caret-right mb-1"> % Formative dan Summative</a>';
                                }

                                if(cekSspGuruId($guru_id)){
                                    echo '<a href="ssp_kriteria.php" class="dropdown-item fa fa-caret-right mb-1"> SSP</a>';
                                }

                            echo '         
                                </div>
                                </li>
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                <div class="dropdown-menu">
                                  
                                  ';
                                if(cekGuruExistInMapel($guru_id)){
                                  echo'<a href="kognitif_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Quiz, Test, Assignment</a>
                                      <a href="uts_uas.php" class="dropdown-item fa fa-caret-right mb-1"> UTS & UAS</a>
                                      <a href="afektif.php" class="dropdown-item fa fa-caret-right mb-1"> Afektif</a>';
                                }
                                if(cekSspGuruId($guru_id)){
                                  echo '<a href="ssp_nilai_input.php" class="dropdown-item fa fa-caret-right mb-1"> SSP</a>';
                                }

                                if(cekGuruExistInTajaranScout($guru_id)){
                                  echo '<a href="scout_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Scout</a>';
                                }
                            
                            echo '</div>
                                </li>';

                                if(cekGuruExistInMapel($guru_id)){
                                  echo'<li class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                        <div class="dropdown-menu">
                                          <a href="lap_remidial.php" class="dropdown-item fa fa-caret-right mb-1"> Remidial</a>
                                          <a href="lap_nilai.php" class="dropdown-item fa fa-caret-right mb-1"> Nilai</a>
                                        </div>
                                      </li>';
                                }    
                            echo '      
                                <li class="nav-item">
                                    <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                                </li>';
                            
                        }
                    }    
                        
                  ?>
              </ul>

                 <?php
                    if(isset($_SESSION['guru_jabatan']))
                    {
                        echo '<form action="includes/logout.php" method="POST" class="form-inline my-2 my-lg-0">
                            <label class="text-light mr-3 mt-1">'.$_SESSION['guru_name'].'</label>
                            <button type="submit" name="submit" class="btn btn-danger my-2 my-sm-0">Logout</button>
                          </form>';
                    }
                    else{
                        echo '<form action="includes/login.php" method="POST" class="form-inline my-2 my-lg-0">
                            <input type="text" name="uid" class="form-control mr-sm-2" placeholder="Username">
                            <input type="password" name="pwd" class="form-control mr-sm-2" placeholder="Password">
                            <button type="submit" name="submit" class="btn btn-success my-2 my-sm-0">Login</button>
                          </form>';
                    }
                 ?>
            </div>
          </div>
    </nav>

    
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.12.3/printThis.js"></script>
    <script src="printMe.js"></script>