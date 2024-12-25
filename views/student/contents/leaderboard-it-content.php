<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
$student = new Student();
$it_top1 = $_SESSION['it_top1'];
$it_leaderboard = $_SESSION['it_leaderboard'];
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
            <a href="leaderboard-cs" id="leaderboard-cs-link" class="nav-items"><button class="leaderboard-course">CS</button></a>
            <a href="leaderboard-it" id="leaderboard-it-link" class="nav-items"><button class="leaderboard-course active">IT</button></a>
            <a href="leaderboard-act" id="leaderboard-act-link" class="nav-items"><button class="leaderboard-course">ACT</button></a>
        </div>
        <form id="filter-leaderboard" action="" method="POST">
            <select name="year-level" id="leaderboard-year-level-filter">
                <option value="all">--All--</option>
                <option value="1">First Year</option>
                <option value="2">Second Year</option>
                <option value="3">Third Year</option>
                <option value="4">Fourth Year</option>
            </select>
            <input type="submit" id="filter-year-button" value="filter-year">
        </form>
    </div>
    <div id="leaderboard-top-notchers">
        <div class="topnotcher-pads">
            <div class="topnotcher-div-1"><p class="topnotcher-course">BS INFORMATION TECHNOLOGY</p></div>
            <div class="topnotcher-div-2"><h2 class="topnotcher-name"><?php echo $it_top1['fullname'] ?? 'None<br>';?></h2></div>
            <div class="topnotcher-div-3">
                <div class="topnotcher-info">
                    <h4>TOP#1</h4>
                    <div class="topnotcher-rating-score"><h3 class="topnotcher-rating"><?php  echo $it_top1['total_rating'] ?? 'None';?></h3></div>
                    <p class="topnotcher-word-rating">RATING</p>
                </div>
                <div class="topnotcher-trophy"></div>
            </div>
        </div>
    </div>
    <div id="leaderboard-list">
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
        </div>
    </div>
</section>
<script src="/cssc/controllers/leaderboard-filter.js"></script>