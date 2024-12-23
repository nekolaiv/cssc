<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
$student = new Student();
// $year_level = NULL;
// $_SESSION['cs_top1'] = $student->getStudentTopNotcher($year_level, 1);
// $_SESSION['it_top1'] = $student->getStudentTopNotchsession_start();level, 2);
// $_SESSION['act_top1'] = $student->getStudentTopNotcher($year_level, 3);
// $_SESSION['cs_leaderboard'] = $student->getStudentLeaderboardData($year_level, 1);
// $_SESSION['it_leaderboard'] = $student->getStudentLeaderboardData($year_level, 2);
// $_SESSION['act_leaderboard'] = $student->getStudentLeaderboardData($year_level, 3);
$cs_top1 = $_SESSION['cs_top1'];
$it_top1 = $_SESSION['it_top1'];
$act_top1 = $_SESSION['act_top1'];
$cs_leaderboard = $_SESSION['cs_leaderboard'];
$it_leaderboard = $_SESSION['it_leaderboard'];
$act_leaderboard = $_SESSION['act_leaderboard'];

?>
<section id="leaderboard-section">
    <div class="div-pad" id="leaderboard-div1">
        <div id="leaderboard-div1-innerdiv">
            <h2>CCS - LEADERBOARD</h2>
            <p>CONGRATULATIONS TO EVERY STUDENT</p>
        </div>
    </div>
    <div id="leaderboard-course-options">
        <div id="leaderboard-courses">
            <a href="leaderboard" id="leaderboard-link" class="nav-items"><button class="leaderboard-course active">ALL</button></a>
            <a href="leaderboard-cs" id="leaderboard-cs-link" class="nav-items"><button class="leaderboard-course">CS</button></a>
            <a href="leaderboard-it" id="leaderboard-it-link" class="nav-items"><button class="leaderboard-course">IT</button></a>
            <a href="leaderboard-act" id="leaderboard-act-link" class="nav-items"><button class="leaderboard-course">ACT</button></a>
        </div>
        <form id="filter-leaderboard" action="" method="POST">
            <select name="year-level" id="leaderboard-year-level-filter">
                <option value="all">--Year Level--</option>
                <option value="1">First Year</option>
                <option value="2">Second Year</option>
                <option value="3">Third Year</option>
                <option value="4">Fourth Year</option>
            </select>
            <select name="submission-period" id="leaderboard-submission-period-filter">
                <option value="all">--Submission Period--</option>
                <?php
                    $submission_id = $student->fetchSubmissionId();
                    foreach ($submission_id as $sid){
                ?>
                    <option value="<?= $sid['submission_id'] ?>" <?= ($submission_id == $sid['submission_id']) ? 'selected' : '' ?>><?= $sid['submission_description'] ?></option>
                <?php
                    }
                ?>
            </select>
            <input type="submit" id="filter-year-button" value="filter">
        </form>
    </div>
    <div id="leaderboard-top-notchers">
        <div class="topnotcher-pads">
            <div class="topnotcher-div-1"><p>BS COMPUTER SCIENCE</p></div>
            <div class="topnotcher-div-2"><h2><?php echo $cs_top1['fullname'] ?? 'None<br><br>'; ?></h2></div>
            <div class="topnotcher-div-3">
                <div class="topnotcher-info">
                    <h4>TOP#1</h4>
                    <div><h3><?php echo $cs_top1['total_rating'] ?? 'None'; ?></h3></div>
                    <p>RATING</p>
                </div>
                <div class="topnotcher-trophy"></div>
            </div>
        </div>
        <div class="topnotcher-pads">
            <div class="topnotcher-div-1"><p>BS INFORMATION TECHNOLOGY</p></div>
            <div class="topnotcher-div-2"><h2><?php echo $it_top1['fullname'] ?? 'None<br><br>'; ?></h2></div>
            <div class="topnotcher-div-3">
                <div class="topnotcher-info">
                    <h4>TOP#1</h4>
                    <div><h3><?php echo $it_top1['total_rating'] ?? 'None'; ?></h3></div>
                    <p>RATING</p>
                </div>
                <div class="topnotcher-trophy"></div>
            </div>
        </div>
        <div class="topnotcher-pads">
            <div class="topnotcher-div-1"><p>ASSOCIATE IN COMPUTER TECHNOLOGY</p></div>
            <div class="topnotcher-div-2"><h2><?php echo $act_top1['fullname'] ?? 'None<br><br>'; ?></h2></div>
            <div class="topnotcher-div-3">
                <div class="topnotcher-info">
                    <h4>TOP#1</h4>
                    <div><h3><?php echo $act_top1['total_rating'] ?? 'None'; ?></h3></div>
                    <p>RATING</p>
                </div>
                <div class="topnotcher-trophy"></div>
            </div>
        </div>
    </div>
    <div id="leaderboard-list">
        <div class="bscs-list">
            <div class="course-header-pad">BS COMPUTER SCIENCE</div>
            <div class="list-table">
                <div class="leaderboard-list-pad list-header">
                    <div class="leaderboard-list-pad-div1">
                        <p class="list-header-name">NAME:</p>
                    </div>
                    <div class="leaderboard-list-pad-div2">
                        <p class="list-header-rating">RATING:</p>
                    </div>
                    <div class="leaderboard-list-pad-div2">
                        <p class="list-header-rating">YEAR LEVEL:</p>
                    </div>
                    <div class="leaderboard-list-pad-div2">
                        <p class="list-header-rating">SUBMISSION PERIOD:</p>
                    </div>
                </div>
                <?php 
                    $csi = 1;
                    foreach ($cs_leaderboard as $csl){ ?>
                        <div class="leaderboard-list-pad list-body">
                            <div class="leaderboard-list-pad-div1">
                                <p class="student-name"><?php echo $csl['fullname']; ?></p>
                            </div>
                            <div class="leaderboard-list-pad-div2">
                                <p class="student-rating"><?php echo $csl['total_rating']; ?></p>
                            </div>
                            <div class="leaderboard-list-pad-div2">
                                <p class="student-rating"><?php echo $csl['year_level']; ?></p>
                            </div>
                            <div class="leaderboard-list-pad-div2">
                                <p class="student-rating"><?php echo $csl['submission_description']; ?></p>
                            </div>
                        </div>
                <?php 
                $csi++;
                }?>
            </div>
        </div>
        <div class="bsit-list">
            <div class="course-header-pad">BS INFORMATION TECHNOLOGY</div>
            <div class="list-table">
                <div class="leaderboard-list-pad list-header">
                    <div class="leaderboard-list-pad-div1">
                        <p class="list-header-name">NAME:</p>
                    </div>
                    <div class="leaderboard-list-pad-div2">
                        <p class="list-header-rating">RATING:</p>
                    </div>
                </div>
                <?php  
                    $iti = 1;
                    foreach ($it_leaderboard as $itl){ ?>
                        <div class="leaderboard-list-pad list-body">
                            <div class="leaderboard-list-pad-div1">
                                <p class="student-name"><?php echo $itl['fullname'] ?? "None"; ?></p>
                            </div>
                            <div class="leaderboard-list-pad-div2">
                                <p class="student-rating"><?php echo $itl['total_rating'] ?? "None"; ?></p>
                            </div>
                        </div>
                <?php 
                        $iti++;
                    }?>
        </div>
        <div class="bsact-list">
            <div class="course-header-pad">ASSOCIATE IN COMPUTER TECHNOLOGY</div>
            <div class="list-table">
                <div class="leaderboard-list-pad list-header">
                    <div class="leaderboard-list-pad-div1">
                        <p class="list-header-name">NAME:</p>
                    </div>
                    <div class="leaderboard-list-pad-div2">
                        <p class="list-header-rating">RATING:</p>
                    </div>
                </div>
                <?php 
                    $acti = 1;
                    foreach ($act_leaderboard as $actl){ ?>
                        <div class="leaderboard-list-pad list-body">
                            <div class="leaderboard-list-pad-div1">
                                <p class="student-name"><?php echo $actl['fullname']; ?></p>
                            </div>
                            <div class="leaderboard-list-pad-div2">
                                <p class="student-rating"><?php echo $actl['total_rating']; ?></p>
                            </div>
                        </div>
                <?php 
                $acti++;
                }?>
            </div>
        </div>
        
    </div>
</section>
<script src="/cssc/controllers/leaderboard-filter.js"></script>