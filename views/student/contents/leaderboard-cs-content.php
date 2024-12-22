<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
// $student = new Student();
// $cs_top1 = $student->getCSTopNotcher();
// $it_top1 = $student->getITTopNotcher();
// $act_top1 = $student->getACTTopNotcher();
// $cs_leaderboard = $student->getCSLeaderboardData();
// $it_leaderboard = $student->getITLeaderboardData();
// $act_leaderboard = $student->getACTLeaderboardData();
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
            <a href="leaderboard" id="leaderboard-link" class="nav-items"><button class="leaderboard-course">ALL</button></a>
            <a href="leaderboard-cs" id="leaderboard-cs-link" class="nav-items"><button class="leaderboard-course active">CS</button></a>
            <a href="leaderboard-it" id="leaderboard-it-link" class="nav-items"><button class="leaderboard-course">IT</button></a>
            <a href="leaderboard-act" id="leaderboard-act-link" class="nav-items"><button class="leaderboard-course">ACT</button></a>
        </div>
    </div>
    <div id="leaderboard-top-notchers">
        <div class="topnotcher-pads">
            <div class="topnotcher-div-1"><p class="topnotcher-course">BS COMPUTER SCIENCE</p></div>
            <div class="topnotcher-div-2"><h2 class="topnotcher-name"><?php echo $cs_top1['fullname'] ?? 'None<br><br>';?></h2></div>
            <div class="topnotcher-div-3">
                <div class="topnotcher-info">
                    <h4>TOP#1</h4>
                    <div class="topnotcher-rating-score"><h3 class="topnotcher-rating"><?php echo $cs_top1['gwa'] ?? 'None'; ?></h3></div>
                    <p class="topnotcher-word-rating">RATING</p>
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
                </div>
                <?php 
                    $csi = 1;
                    foreach ($cs_leaderboard as $csl){ ?>
                        <div class="leaderboard-list-pad list-body">
                            <div class="leaderboard-list-pad-div1">
                                <p class="student-name"><?php echo $csl['fullname'] ?? "None"; ?></p>
                            </div>
                            <div class="leaderboard-list-pad-div2">
                                <p class="student-rating"><?php echo $csl['gwa'] ?? "None"; ?></p>
                            </div>
                        </div>
                <?php 
                $csi++;
                }?>
            </div>
        </div>
    </div>
</section>