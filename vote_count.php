<?php 

    session_start();
    include 'db.php';
    require 'config.php';

    date_default_timezone_set('Asia/Hong_Kong');
    function getSchoolYear($currentDate) {
        $currentMonth = (int) date('m', strtotime($currentDate));
        $currentYear = (int) date('Y', strtotime($currentDate));
        
        if ($currentMonth >= 8) {
            $startYear = $currentYear;
            $endYear = $currentYear + 1;
        } else {
            $startYear = $currentYear - 1;
            $endYear = $currentYear;
        }
        return "$startYear-$endYear";
        }
    
    $currentDate = date('Y-m-d');
    $schoolYear = getSchoolYear($currentDate);

    $pres = isset($_POST['president']) ? mysqli_real_escape_string($conn, $_POST['president']) : null;;
    $tervp = isset($_POST['tervpresident']) ? mysqli_real_escape_string($conn, $_POST['tervpresident']) : null;;
    $shvp = isset($_POST['shvpresident']) ? mysqli_real_escape_string($conn, $_POST['shvpresident']) : null;;
    $intsec = isset($_POST['intsec']) ? mysqli_real_escape_string($conn, $_POST['intsec']) : null;;
    $extsec = isset($_POST['extsec']) ? mysqli_real_escape_string($conn, $_POST['extsec']) : null;;
    $trea = isset($_POST['trea']) ? mysqli_real_escape_string($conn, $_POST['trea']) : null;;
    $aud = isset($_POST['aud']) ? mysqli_real_escape_string($conn, $_POST['aud']) : null;;
    $pio1 = isset($_POST['pio1']) ? mysqli_real_escape_string($conn, $_POST['pio1']) : null;
    $pio2 = isset($_POST['pio2']) ? mysqli_real_escape_string($conn, $_POST['pio2']) : null;
    $g11abmrep = isset($_POST['11abmrep']) ? mysqli_real_escape_string($conn, $_POST['11abmrep']) : null;; 
    $g11humssrep = isset($_POST['11humssrep']) ? mysqli_real_escape_string($conn, $_POST['11humssrep']) : null;; 
    $g11stemrep = isset($_POST['11stemrep']) ? mysqli_real_escape_string($conn, $_POST['11stemrep']) : null;;
    $g11cuartrep = isset($_POST['11cuartrep']) ? mysqli_real_escape_string($conn, $_POST['11cuartrep']) : null;;
    $g11mawdrep = isset($_POST['11mawdrep']) ? mysqli_real_escape_string($conn, $_POST['11mawdrep']) : null;;
    $g12abmrep = isset($_POST['12abmrep']) ? mysqli_real_escape_string($conn, $_POST['12abmrep']) : null;;
    $g12humssrep = isset($_POST['12humssrep']) ? mysqli_real_escape_string($conn, $_POST['12humssrep']) : null;;
    $g12stemrep = isset($_POST['12stemrep']) ? mysqli_real_escape_string($conn, $_POST['12stemrep']) : null;;
    $g12cuartrep = isset($_POST['12cuartrep']) ? mysqli_real_escape_string($conn, $_POST['12cuartrep']) : null;;
    $g12mawdrep = isset($_POST['12mawdrep']) ? mysqli_real_escape_string($conn, $_POST['12mawdrep']) : null;;
    $bstm1arep = isset($_POST['bstm1arep_']) ? mysqli_real_escape_string($conn, $_POST['bstm1arep_']) : null;;
    $bstm2arep = isset($_POST['bstm2arep']) ? mysqli_real_escape_string($conn, $_POST['bstm2arep']) : null;;
    $bstm2brep = isset($_POST['bstm2brep']) ? mysqli_real_escape_string($conn, $_POST['bstm2brep']) : null;;
    $bstm3rep = isset($_POST['bstm3rep']) ? mysqli_real_escape_string($conn, $_POST['bstm3rep']) : null;;
    $bstm4rep = isset($_POST['bstm4rep']) ? mysqli_real_escape_string($conn, $_POST['bstm4rep']) : null;;
    $bsis1rep = isset($_POST['bsis1rep']) ? mysqli_real_escape_string($conn, $_POST['bsis1rep']) : null;;
    $bsis2rep = isset($_POST['bsis2rep']) ? mysqli_real_escape_string($conn, $_POST['bsis2rep']) : null;;
    $bsis3rep = isset($_POST['bsis3rep']) ? mysqli_real_escape_string($conn, $_POST['bsis3rep']) : null;;
    $bsis4rep = isset($_POST['bsis4rep']) ? mysqli_real_escape_string($conn, $_POST['bsis4rep']) : null;;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $selected_pio = isset($_POST['pio']) ? $_POST['pio'] : [];

        $selected_pio = array_map(function($value) use ($conn) {
            return mysqli_real_escape_string($conn, $value);
        }, $selected_pio);

        if (count($selected_pio) > 2) {
            $selected_pio = array_slice($selected_pio, 0, 2);
        }

        $qry1 = "SELECT voter_id FROM voters WHERE user_id = '$_SESSION[userID]'";
        $result = mysqli_query($conn, $qry1);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $voter_id = $row['voter_id'];


            $qry2 = "INSERT INTO registered_votes (r_vote_id, voter_id, president, ter_vpresident, sh_vpresident, secretary_inter, secretary_exter, treasurer, auditor, pio, pio_2, g11abm_representative, g11humss_representative, g11stem_representative, g11cuart_representative, g11mawd_representative, g12abm_representative, g12humss_representative, g12stem_representative, g12cuart_representative, g12mawd_representative, g12toper_representative, bstm1a_representative, bstm1b_representative, bsis1_representative, bstm2a_representative, bstm2b_representative, bsis2_representative, bstm3_representative, bsis3_representative, bstm4_representative, bsis4_representative, academic_year, vote_date) VALUES ('','$voter_id','$pres','$tervp','$shvp','$intsec','$extsec','$trea','$aud','" . (isset($selected_pio[0]) ? $selected_pio[0] : '') . "','" . (isset($selected_pio[1]) ? $selected_pio[1] : '') . "','$g11abmrep','$g11humssrep','$g11stemrep','$g11cuartrep','$g11mawdrep','$g12abmrep','$g12humssrep','$g12stemrep','$g12cuartrep','$g12mawdrep','','$bstm1arep', '','$bsis1rep','$bstm2arep','$bstm2brep','$bsis2rep','$bstm3rep','$bsis3rep','$bstm4rep','$bsis4rep', '$schoolYear', NOW())";
            $results = mysqli_query($conn, $qry2);

            $vote_status = "UPDATE voters SET vote_status = 'YES' WHERE voter_id = '$voter_id'";
            $status = mysqli_query($conn, $vote_status);
            
            if ($results) {
                $qry3 = "SELECT * FROM registered_votes WHERE voter_id = '$voter_id'";
                $result_votes = mysqli_query($conn, $qry3);

                if ($result_votes) {
                    while ($row_votes = mysqli_fetch_assoc($result_votes)) {
                        $pres = $row_votes['president'];
                        $tervp = $row_votes['ter_vpresident'];
                        $shvp = $row_votes['sh_vpresident'];
                        $intsec = $row_votes['secretary_inter'];
                        $extsec = $row_votes['secretary_exter'];
                        $trea = $row_votes['treasurer'];
                        $aud = $row_votes['auditor'];
                        $pio1 = $row_votes['pio'];
                        $pio2 = $row_votes['pio_2'];
                        $g11abmrep = $row_votes['g11abm_representative'];
                        $g11humssrep = $row_votes['g11humss_representative'];
                        $g11stemrep = $row_votes['g11stem_representative'];
                        $g11cuartrep = $row_votes['g11cuart_representative'];
                        $g11mawdrep = $row_votes['g11mawd_representative'];
                        $g12abmrep = $row_votes['g12abm_representative'];
                        $g12humssrep = $row_votes['g12humss_representative'];
                        $g12stemrep = $row_votes['g12stem_representative'];
                        $g12cuartrep = $row_votes['g12cuart_representative'];
                        $g12mawdrep = $row_votes['g12mawd_representative'];
                        $bstm1arep = $row_votes['bstm1a_representative'];
                        $bstm2arep = $row_votes['bstm2a_representative'];
                        $bstm2brep = $row_votes['bstm2b_representative'];
                        $bstm3rep = $row_votes['bstm3_representative'];
                        $bstm4rep = $row_votes['bstm4_representative'];
                        $bsis1rep = $row_votes['bsis1_representative'];
                        $bsis2rep = $row_votes['bsis2_representative'];
                        $bsis3rep = $row_votes['bsis3_representative'];
                        $bsis4rep = $row_votes['bsis4_representative'];
                        
                        if (!empty($pres)) {
                            $qry4 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            INNER JOIN voters v ON c.voter_id = v.voter_id 
                            INNER JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$pres' AND c.position_id = 'PRES' AND c.academic_year = '$schoolYear'";
                            $result_pres = mysqli_query($conn, $qry4);


                            if ($result_pres->num_rows > 0) {
                                $row_pres = mysqli_fetch_assoc($result_pres);
                                $candidate_id_pres = $row_pres['candidate_id'];

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                            if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                                $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_pres'";
                                                $update_result_pres = mysqli_query($conn, $qry5);
                                            } else {
                                                $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_pres'";
                                                $update_result_pres = mysqli_query($conn, $qry5);
                                            }          

                                    }

                                }
    
                            } 
                        } 

                        if (!empty($tervp)) {
                            $qry6 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$tervp' AND c.position_id = 'TERVP' AND c.academic_year = '$schoolYear'";
                            $result_vp = mysqli_query($conn, $qry6);
    
                            if ($result_vp && mysqli_num_rows($result_vp) > 0) {
                                $row_vp = mysqli_fetch_assoc($result_vp);
                                $candidate_id_vp = $row_vp['candidate_id'];

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                      

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_vp'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_vp'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($shvp)) {
                            $qry8 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$shvp' AND c.position_id = 'SHVP' AND c.academic_year = '$schoolYear'";
                            $result_shvp = mysqli_query($conn, $qry8);
    
                            if ($result_shvp && mysqli_num_rows($result_shvp) > 0) {
                                $row_shvp = mysqli_fetch_assoc($result_shvp);
                                $candidate_id_shvp = $row_shvp['candidate_id'];
    
                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                       

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_shvp'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_shvp'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($intsec)) {
                            $qry10 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$intsec' AND c.position_id = 'INTSEC' AND c.academic_year = '$schoolYear'";
                            $result_intsec = mysqli_query($conn, $qry10);
    
                            if ($result_intsec && mysqli_num_rows($result_intsec) > 0) {
                                $row_intsec = mysqli_fetch_assoc($result_intsec);
                                $candidate_id_intsec = $row_intsec['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_intsec'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_intsec'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($extsec)) {
                            $qry12 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$extsec' AND c.position_id = 'EXTSEC' AND c.academic_year = '$schoolYear'";
                            $result_extsec = mysqli_query($conn, $qry12);
    
                            if ($result_extsec && mysqli_num_rows($result_extsec) > 0) {
                                $row_extsec = mysqli_fetch_assoc($result_extsec);
                                $candidate_id_extsec = $row_extsec['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                       

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_extsec'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_extsec'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($trea)) {
                            $qry14 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$trea' AND c.position_id = 'TREA' AND c.academic_year = '$schoolYear'";
                            $result_trea = mysqli_query($conn, $qry14);
    
                            if ($result_trea && mysqli_num_rows($result_trea) > 0) {
                                $row_trea = mysqli_fetch_assoc($result_trea);
                                $candidate_id_trea = $row_trea['candidate_id'];
                                
    
                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];


                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_trea'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_trea'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                        
                                    }
        
                                } 
                            }
                        }

                        if (!empty($aud)) {
                            $qry16 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$aud' AND c.position_id = 'AUD' AND c.academic_year = '$schoolYear'";
                            $result_aud = mysqli_query($conn, $qry16);
    
                            if ($result_aud && mysqli_num_rows($result_aud) > 0) {
                                $row_aud = mysqli_fetch_assoc($result_aud);
                                $candidate_id_aud = $row_aud['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                    

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_aud'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_aud'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                  
                                    
                                        }
            
                                    } 
                                }
                            }

                        if (!empty($pio1)) {
                            $qry18 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$pio1' AND c.position_id = 'PIO' AND c.academic_year = '$schoolYear'";
                            $result_pio1 = mysqli_query($conn, $qry18);
    
                            if ($result_pio1 && mysqli_num_rows($result_pio1) > 0) {
                                $row_pio1 = mysqli_fetch_assoc($result_pio1);
                                $candidate_id_pio1 = $row_pio1['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_pio1'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_pio1'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($pio2)) {
                            $qry19 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$pio2' AND c.position_id = 'PIO' AND c.academic_year = '$schoolYear'";
                            $result_pio2 = mysqli_query($conn, $qry19);
    
                            if ($result_pio2 && mysqli_num_rows($result_pio2) > 0) {
                                $row_pio2 = mysqli_fetch_assoc($result_pio2);
                                $candidate_id_pio2 = $row_pio2['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];
                          

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_pio2'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_pio2'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g11abmrep)) {
                            $qry20 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g11abmrep' AND c.position_id = '11ABMREP' AND c.academic_year = '$schoolYear'";
                            $result_11abmrep = mysqli_query($conn, $qry20);
    
                            if ($result_11abmrep && mysqli_num_rows($result_11abmrep) > 0) {
                                $row_11abmrep = mysqli_fetch_assoc($result_11abmrep);
                                $candidate_id_11abmrep = $row_11abmrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                     

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11abmrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11abmrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g11humssrep)) {
                            $qry22 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g11humssrep' AND c.position_id = '11HUMSSREP' AND c.academic_year = '$schoolYear'";
                            $result_11humssrep = mysqli_query($conn, $qry22);
    
                            if ($result_11humssrep && mysqli_num_rows($result_11humssrep) > 0) {
                                $row_11humssrep = mysqli_fetch_assoc($result_11humssrep);
                                $candidate_id_11humssrep = $row_11humssrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        
                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11humssrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11humssrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g11stemrep)) {
                            $qry24 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g11stemrep' AND c.position_id = '11STEMREP' AND c.academic_year = '$schoolYear'";
                            $result_11stemrep = mysqli_query($conn, $qry24);
    
                            if ($result_11stemrep && mysqli_num_rows($result_11stemrep) > 0) {
                                $row_11stemrep = mysqli_fetch_assoc($result_11stemrep);
                                $candidate_id_11stemrep = $row_11stemrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11stemrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11stemrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g11cuartrep)) {
                            $qry26 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g11cuartrep' AND c.position_id = '11CUARTREP' AND c.academic_year = '$schoolYear'";
                            $result_11cuartrep = mysqli_query($conn, $qry26);
    
                            if ($result_11cuartrep && mysqli_num_rows($result_11cuartrep) > 0) {
                                $row_11cuartrep = mysqli_fetch_assoc($result_11cuartrep);
                                $candidate_id_11cuartrep = $row_11cuartrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                       

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11cuartrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11cuartrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g11mawdrep)) {
                            $qry28 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g11mawdrep' AND c.position_id = '11MAWDREP' AND c.academic_year = '$schoolYear'";
                            $result_11mawdrep = mysqli_query($conn, $qry28);
    
                            if ($result_11mawdrep && mysqli_num_rows($result_11mawdrep) > 0) {
                                $row_11mawdrep = mysqli_fetch_assoc($result_11mawdrep);
                                $candidate_id_11mawdrep = $row_11mawdrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11mawdrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_11mawdrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g12abmrep)) {
                            $qry30 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g12abmrep' AND c.position_id = '12ABMREP' AND c.academic_year = '$schoolYear'";
                            $result_12abmrep = mysqli_query($conn, $qry30);
    
                            if ($result_12abmrep && mysqli_num_rows($result_12abmrep) > 0) {
                                $row_12abmrep = mysqli_fetch_assoc($result_12abmrep);
                                $candidate_id_12abmrep = $row_12abmrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                       

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12abmrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12abmrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g12humssrep)) {
                            $qry32 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g12humssrep' AND c.position_id = '12HUMSSREP' AND c.academic_year = '$schoolYear'";
                            $result_12humssrep = mysqli_query($conn, $qry32);
    
                            if ($result_12humssrep && mysqli_num_rows($result_12humssrep) > 0) {
                                $row_12humssrep = mysqli_fetch_assoc($result_12humssrep);
                                $candidate_id_12humssrep = $row_12humssrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12humssrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12humssrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g12cuartrep)) {
                            $qry34 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g12cuartrep' AND c.position_id = '12CUARTREP' AND c.academic_year = '$schoolYear'";
                            $result_12cuartrep = mysqli_query($conn, $qry34);
    
                            if ($result_12cuartrep && mysqli_num_rows($result_12cuartrep) > 0) {
                                $row_12cuartrep = mysqli_fetch_assoc($result_12cuartrep);
                                $candidate_id_12cuartrep = $row_12cuartrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12cuartrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12cuartrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($g12mawdrep)) {
                            $qry36 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$g12mawdrep' AND c.position_id = '12MAWDREP' AND c.academic_year = '$schoolYear'";
                            $result_12mawdrep = mysqli_query($conn, $qry36);
    
                            if ($result_12mawdrep && mysqli_num_rows($result_12mawdrep) > 0) {
                                $row_12mawdrep = mysqli_fetch_assoc($result_12mawdrep);
                                $candidate_id_12mawdrep = $row_12mawdrep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12mawdrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_12mawdrep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($bstm1arep)) {
                            $qry38 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bstm1arep' AND c.position_id = 'BSTM1AREP' AND c.academic_year = '$schoolYear'";
                            $result_bstm1arep = mysqli_query($conn, $qry38);
    
                            if ($result_bstm1arep && mysqli_num_rows($result_bstm1arep) > 0) {
                                $row_bstm1arep = mysqli_fetch_assoc($result_bstm1arep);
                                $candidate_id_bstm1arep = $row_bstm1arep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm1arep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm1arep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($bstm2arep)) {
                            $qry42 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bstm2arep' AND c.position_id = 'BSTM2AREP' AND c.academic_year = '$schoolYear'";
                            $result_bstm2arep = mysqli_query($conn, $qry42);
    
                            if ($result_bstm2arep && mysqli_num_rows($result_bstm2arep) > 0) {
                                $row_bstm2arep = mysqli_fetch_assoc($result_bstm2arep);
                                $candidate_id_bstm2arep = $row_bstm2arep['candidate_id'];
                               
                    
                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];


                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm2arep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm2arep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($bstm2brep)) {
                            $qry42 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bstm2brep' AND c.position_id = 'BSTM2BREP' AND c.academic_year = '$schoolYear'";
                            $result_bstm2brep = mysqli_query($conn, $qry42);
    
                            if ($result_bstm2brep && mysqli_num_rows($result_bstm2brep) > 0) {
                                $row_bstm2brep = mysqli_fetch_assoc($result_bstm2brep);
                                $candidate_id_bstm2brep = $row_bstm2brep['candidate_id'];
                               
                    
                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm2brep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm2brep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }


                        if (!empty($bstm3rep)) {
                            $qry43 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bstm3rep' AND c.position_id = 'BSTM3REP' AND c.academic_year = '$schoolYear'";
                            $result_bstm3rep = mysqli_query($conn, $qry43);
    
                            if ($result_bstm3rep && mysqli_num_rows($result_bstm3rep) > 0) {
                                $row_bstm3rep = mysqli_fetch_assoc($result_bstm3rep);
                                $candidate_id_bstm3rep = $row_bstm3rep['candidate_id'];
                               
                    
                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm3rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm3rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($bstm4rep)) {
                            $qry44 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bstm4rep' AND c.position_id = 'BSTM4REP' AND c.academic_year = '$schoolYear'";
                            $result_bstm4rep = mysqli_query($conn, $qry44);
    
                            if ($result_bstm4rep && mysqli_num_rows($result_bstm4rep) > 0) {
                                $row_bstm4rep = mysqli_fetch_assoc($result_bstm4rep);
                                $candidate_id_bstm4rep = $row_bstm4rep['candidate_id'];
                               

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm4rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bstm4rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    }
                                
                                }
    
                            } 
                        }

                        if (!empty($bsis1rep)) {
                            $qry46 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bsis1rep' AND c.position_id = 'BSIS1REP' AND c.academic_year = '$schoolYear'";
                            $result_bsis1rep = mysqli_query($conn, $qry46);
    
                            if ($result_bsis1rep && mysqli_num_rows($result_bsis1rep) > 0) {
                                $row_bsis1rep = mysqli_fetch_assoc($result_bsis1rep);
                                $candidate_id_bsis1rep = $row_bsis1rep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis1rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis1rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($bsis2rep)) {
                            $qry48 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bsis2rep' AND c.position_id = 'BSIS2REP' AND c.academic_year = '$schoolYear'";
                            $result_bsis2rep = mysqli_query($conn, $qry48);
    
                            if ($result_bsis2rep && mysqli_num_rows($result_bsis2rep) > 0) {
                                $row_bsis2rep = mysqli_fetch_assoc($result_bsis2rep);
                                $candidate_id_bsis2rep = $row_bsis2rep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis2rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis2rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($bsis3rep)) {
                            $qry50 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bsis3rep' AND c.position_id = 'BSIS3REP' AND c.academic_year = '$schoolYear'";
                            $result_bsis3rep = mysqli_query($conn, $qry50);
    
                            if ($result_bsis3rep && mysqli_num_rows($result_bsis3rep) > 0) {
                                $row_bsis3rep = mysqli_fetch_assoc($result_bsis3rep);
                                $candidate_id_bsis3rep = $row_bsis3rep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                        

                                        if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                            $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis3rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        } else {
                                            $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis3rep'";
                                            $update_result_pres = mysqli_query($conn, $qry5);
                                        }
                                    
                                    }
                                }
    
                            } 
                        }

                        if (!empty($bsis4rep)) {
                            $qry52 = "SELECT c.candidate_id, COUNT(c.candidate_id) AS c_id FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            WHERE u.user_name = '$bsis4rep' AND c.position_id = 'BSIS4REP' AND c.academic_year = '$schoolYear'";
                            $result_bsis4rep = mysqli_query($conn, $qry52);
    
                            if ($result_bsis4rep && mysqli_num_rows($result_bsis4rep) > 0) {
                                $row_bsis4rep = mysqli_fetch_assoc($result_bsis4rep);
                                $candidate_id_bsis4rep = $row_bsis4rep['candidate_id'];
                                

                                $query = "SELECT v.voter_grade FROM candidate c JOIN voters v ON c.voter_id = v.voter_id WHERE v.voter_id = '$voter_id'";
                                $result_q = mysqli_query($conn, $query);

                                if ($result_q->num_rows > 0) {
                                    while ($row = $result_q->fetch_assoc()) {
                                        $voter_grade = $row['voter_grade'];

                                       

                                            if ($voter_grade == 'g11' || $voter_grade == 'g12') {
                                                $qry5 = "UPDATE votes SET SHvote_count = SHvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis4rep'";
                                                $update_result_pres = mysqli_query($conn, $qry5);
                                            } else {
                                                $qry5 = "UPDATE votes SET TERvote_count = TERvote_count + 1, total_votes = SHvote_count + TERvote_count, date_updated = NOW() WHERE candidate_id = '$candidate_id_bsis4rep'";
                                                $update_result_pres = mysqli_query($conn, $qry5);
                                            }
                                        }   
                                    }
                                }
                            }
                            } 
                        }

                    }
                }
                
            }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
</head>
<style>

</style>
<body style="background-color: #0079c2;">
<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thank you for voting!</h5>
            </div>
            <div class="modal-body">
                <p>Thank you for participating in the Council of Leaders election at STI College Iligan. Your vote is a vital part of our democratic process and helps us shape a better future for our school community.
                We appreciate your time and involvement in making a difference. Stay tuned for the results and continue to engage with your student council!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='homepage.php';">Close</button>
            </div>
        </div>
    </div>
</div>
    <script>

        $(document).ready(function() {
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        });
    </script>
</body>
</html>