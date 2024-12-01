<?php 
$page_title = "leaderboard - cs";
include_once "../../includes/_student-head.php";
require_once("../../server/student_leaderboard-data.php");
?>

<body class="home-body">
    <main class="wrapper">
        <?php include_once "../../includes/_student-header.php"?>
        <div class="content">
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
                        <div class="topnotcher-div-2"><h2 class="topnotcher-name">YAHIYA, AHMAD FEYAZ</h2></div>
                        <div class="topnotcher-div-3">
                            <div class="topnotcher-info">
                                <h4>TOP#1</h4>
                                <div class="topnotcher-rating-score"><h3 class="topnotcher-rating">1.0 CONGRATULATIONS!</h3></div>
                                <p class="topnotcher-word-rating">RATING</p>
                            </div>
                            <div class="topnotcher-trophy">
                                trophy
                            </div>
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
                            <div class="leaderboard-list-pad list-body">
                                <div class="leaderboard-list-pad-div1">
                                    <p class="student-name">NIKOLAI</p>
                                </div>
                                <div class="leaderboard-list-pad-div2">
                                    <p class="student-rating">1.0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

<script>
    let session_length = <?= count($_SESSION["course-fields"]["subject_code"] ?? []) ?>
</script>
<!-- <script src="/csrs/js/student_ajax.js"></script> -->
<script src="/cssc/controllers/student-controller.js"></script>

<?php include_once "../../includes/_student-footer.php"?>



