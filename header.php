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
    <link rel="stylesheet" href="CSS/customCSS_preview.css">
    <title>CPA SMP</title>
</head>
<body>
    <!--##################START HERE###################-->

    <!-- NAVBAR WITH FORM -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary mb-3">
        <div class="container">
          <a class="navbar-brand" href="index.php">CPA SMP</a>
          <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav mr-auto">
                  <?php
                    if(isset($_SESSION['guru_jabatan']))
                    {
                        $jabatan = $_SESSION['guru_jabatan'];
                        $guru_id = $_SESSION['guru_id'];
                        //wakasek
                        if ($jabatan == 1){
                          echo'<li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                            <div class="dropdown-menu">
                              <a href="t_ajaran.php" class="dropdown-item">1. Tahun Ajaran</a>
                              <a href="guru.php" class="dropdown-item">2. Guru</a>
                              <a href="jenjang.php" class="dropdown-item">3. Jenjang</a>
                              <a href="kelas.php" class="dropdown-item">4. Kelas</a>
                              <a href="siswa.php" class="dropdown-item">5. Siswa</a>
                              <a href="mapel_new.php" class="dropdown-item">6. Mata Pelajaran</a>
                              <a href="karakter_mapel.php" class="dropdown-item">7. Karakter</a>
                              <a href="detail_karakter_mapel.php" class="dropdown-item">8. Karakter Pelajaran</a>
                              <a href="topik_kognitif.php" class="dropdown-item">9. Topik</a>
                              <a href="ssp.php" class="dropdown-item">10. SSP</a>
                            ';
                            echo'</div>
                                </li>';
                            
                          echo '<li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                <div class="dropdown-menu">
                                  <a href="kognitif_nilai.php" class="dropdown-item">1. Quiz, Test, Assignment</a>
                                  <a href="uts_uas.php" class="dropdown-item">2. UTS & UAS</a>
                                  <a href="afektif.php" class="dropdown-item">3. Afektif</a>
                                ';
                            
                            include_once 'includes/db_con.php';
                            $sql = "SELECT * FROM ssp 
                                    LEFT JOIN guru
                                    ON ssp_guru_id = guru_id
                                    LEFT JOIN t_ajaran
                                    ON ssp_t_ajaran_id = t_ajaran_id
                                    WHERE guru_id = $guru_id AND t_ajaran_active = 1";
                            
                            $result = mysqli_query($conn, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if($resultCheck > 0){
                              echo '<a href="ssp_nilai_input.php" class="dropdown-item">4. SSP</a>';
                            }
                          
                              echo '</div>
                                </li>
                              
                              <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Rapot</a>
                                <div class="dropdown-menu">
                                  <h6 class="dropdown-header">SISIPAN</h6>
                                  <a href="terima_rapot_sisipan.php" class="dropdown-item">1. Set Rapot Sisipan</a>
                                  <a href="rapot_waka_sisipan.php" class="dropdown-item">2. Cetak Rapot Sisipan</a>
                                  <div class="dropdown-divider"></div>
                                  <h6 class="dropdown-header">SEMESTER</h6>
                                  <a href="terima_rapot.php" class="dropdown-item">1. Set Rapot Semester</a>
                                  <a href="rapot_waka.php" class="dropdown-item">2. Cetak Rapot Semester</a>
                                  <a href="analisis_rapor.php" class="dropdown-item">3. Analisis Rapot Semester</a>
                                </div>
                              </li>
                              
                              ';
                              
                              echo '
                                  <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Revisi</a>
                                    <div class="dropdown-menu">
                                      <a href="revisi.php" class="dropdown-item">1. Daftar Revisi</a>
                                      <a href="" class="dropdown-item">2. History Revisi</a>
                                    </div>
                                  </li>
                                <li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                  <div class="dropdown-menu">
                                    <a href="lap_nilai_topik.php" class="dropdown-item">1. Nilai QUIZ, TES, ASS</a>
                                    <a href="lap_nilai.php" class="dropdown-item">2. Nilai UTS & UAS</a>
                                  </div>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                                </li>';
                        }
                        elseif ($jabatan == 2){
                          //Wali kelas
                          echo '
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                                <div class="dropdown-menu">
                                  <a href="topik_kognitif.php" class="dropdown-item">1. Topik Pelajaran</a>
                                  <a href="ssp_kriteria.php" class="dropdown-item">2. Detail SSP</a>
                                </div>
                                </li>
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                <div class="dropdown-menu">
                                  <a href="kognitif_nilai.php" class="dropdown-item">1. Quiz, Test, Assignment</a>
                                  <a href="uts_uas.php" class="dropdown-item">2. UTS & UAS</a>
                                  <a href="afektif.php" class="dropdown-item">3. Afektif</a>
                                  <a href="p_fitness.php" class="dropdown-item">4. P Fitness & Healthful Habit</a>
                                  <a href="social_skill.php" class="dropdown-item">5. Social Skill</a>';
                          
                            include_once 'includes/db_con.php';
                            $sql = "SELECT * FROM ssp 
                                    LEFT JOIN guru
                                    ON ssp_guru_id = guru_id
                                    LEFT JOIN t_ajaran
                                    ON ssp_t_ajaran_id = t_ajaran_id
                                    WHERE guru_id = $guru_id AND t_ajaran_active = 1";
                            
                            $result = mysqli_query($conn, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if($resultCheck > 0){
                              echo '<a href="ssp_nilai_input.php" class="dropdown-item">4. SSP</a>';
                            }
                          
                          echo '          
                                </div>
                                </li>
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Rapor</a>
                                <div class="dropdown-menu">
                                  <h6 class="dropdown-header">SISIPAN</h6>
                                  <a href="komentar.php" class="dropdown-item">1. Input Komentar & Absen</a>
                                  <a href="rapot_walkel_sisipan.php" class="dropdown-item">2. Preview</a>
                                  <h6 class="dropdown-header">SEMESTER</h6>
                                  <a href="rapot.php" class="dropdown-item">1. Preview</a>
                                </div>
                                </li>
                                
                                <li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                  <div class="dropdown-menu">
                                    <a href="lap_nilai_topik.php" class="dropdown-item">1. Nilai QUIZ, TES, ASS</a>
                                    <a href="lap_nilai.php" class="dropdown-item">2. Nilai UTS & UAS</a>
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
                              <a href="aspek_ce.php" class="dropdown-item">1. Aspek CE</a>
                              <a href="detail_aspek_ce.php" class="dropdown-item">2. Detail Aspek</a>
                            </div>
                          </li>    

                          <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Bimbingan Konseling</a>
                            <div class="dropdown-menu">
                              <a href="kriteria.php" class="dropdown-item">Kriteria Afektif</a>
                              <a href="info_afektif.php" class="dropdown-item">Laporan Afektif</a>
                            </div>
                          </li>
                          
                          <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                            <div class="dropdown-menu">
                              <a href="emotional.php" class="dropdown-item">1. Emotional Awareness</a>
                              <a href="spirit.php" class="dropdown-item">2. Sprituality</a>
                              <a href="ce_nilai.php" class="dropdown-item">3. Character Education</a>
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
                                    <div class="dropdown-menu">
                                      <a href="topik_kognitif.php" class="dropdown-item">1. Topik Pelajaran</a>
                                      <a href="ssp_kriteria.php" class="dropdown-item">2. Detail SSP</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                    <div class="dropdown-menu">
                                      <a href="kognitif_nilai.php" class="dropdown-item">1. Quiz, Test, Assignment</a>
                                      <a href="uts_uas.php" class="dropdown-item">2. UTS & UAS</a>
                                      <a href="afektif.php" class="dropdown-item">3. Afektif</a>
                                      <a href="ssp_nilai_input.php" class="dropdown-item">4. SSP</a>
                                      <a href="moral.php" class="dropdown-item">5. Moral Behavior</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="ganti_password.php">Ubah Password</a>
                                </li>';
                        }
                        else{
                            //GURU (3)
                            echo '
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Master</a>
                                <div class="dropdown-menu">
                                  <a href="topik_kognitif.php" class="dropdown-item">1. Topik Pelajaran</a>
                                  ';
                            
                            include_once 'includes/db_con.php';
                            $sql = "SELECT * FROM ssp 
                                    LEFT JOIN guru
                                    ON ssp_guru_id = guru_id
                                    LEFT JOIN t_ajaran
                                    ON ssp_t_ajaran_id = t_ajaran_id
                                    WHERE guru_id = $guru_id AND t_ajaran_active = 1";
                            
                            $result = mysqli_query($conn, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if($resultCheck > 0){
                              echo '<a href="ssp_kriteria.php" class="dropdown-item">2. SSP</a>';
                            }
                            
                            echo '         
                                </div>
                                </li>
                                <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Nilai</a>
                                <div class="dropdown-menu">
                                  <a href="kognitif_nilai.php" class="dropdown-item">1. Quiz, Test, Assignment</a>
                                  <a href="uts_uas.php" class="dropdown-item">2. UTS & UAS</a>
                                  <a href="afektif.php" class="dropdown-item">3. Afektif</a>
                                  ';
                            
                            if($resultCheck > 0){
                              echo '<a href="ssp_nilai_input.php" class="dropdown-item">4. SSP</a>';
                            }
                            
                            echo '
                                </div>
                                </li>
                                <li class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laporan</a>
                                  <div class="dropdown-menu">
                                    <a href="lap_nilai_topik.php" class="dropdown-item">1. Nilai QUIZ, TES, ASS</a>
                                    <a href="lap_nilai.php" class="dropdown-item">2. Nilai UTS & UAS</a>
                                  </div>
                                </li>';
                            
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