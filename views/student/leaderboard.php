<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "leaderboard";
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');

$student = new Student();
// $cs_top1 = $student->getCSTopNotcher();
// $it_top1 = $student->getITTopNotcher();
// $act_top1 = $student->getACTTopNotcher();
// $cs_leaderboard = $student->getCSLeaderboardData();
// $it_leaderboard = $student->getITLeaderboardData();
// $act_leaderboard = $student->getACTLeaderboardData();

$year_level = NULL;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['filter-year'])){
        $year_level = $_POST['year-level'];
        echo "<script>alert('leaderboard')</script>";
    }
}
$_SESSION['cs_top1'] = $student->getStudentTopNotcher($year_level, 'BSCS');
$_SESSION['it_top1'] = $student->getStudentTopNotcher($year_level, 'BSIT');
$_SESSION['act_top1'] = $student->getStudentTopNotcher($year_level, 'ACT');
$_SESSION['cs_leaderboard'] = $student->getStudentLeaderboardData($year_level, 'BSCS');
$_SESSION['it_leaderboard'] = $student->getStudentLeaderboardData($year_level, 'BSIT');
$_SESSION['act_leaderboard'] = $student->getStudentLeaderboardData($year_level, 'ACT');
$cs_top1 = $_SESSION['cs_top1'];
$it_top1 = $_SESSION['it_top1'];
$act_top1 = $_SESSION['act_top1'];
$cs_leaderboard = $_SESSION['cs_leaderboard'];
$it_leaderboard = $_SESSION['it_leaderboard'];
$act_leaderboard = $_SESSION['act_leaderboard'];

?>

<body class="home-body">
    <main class="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-header.php');?>
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
                        <a href="leaderboard" id="leaderboard-link" class="nav-items"><button class="leaderboard-course active">ALL</button></a>
                        <a href="leaderboard-cs" id="leaderboard-cs-link" class="nav-items"><button class="leaderboard-course">CS</button></a>
                        <a href="leaderboard-it" id="leaderboard-it-link" class="nav-items"><button class="leaderboard-course">IT</button></a>
                        <a href="leaderboard-act" id="leaderboard-act-link" class="nav-items"><button class="leaderboard-course">ACT</button></a>
                    </div>
                    <form action="" method="POST">
                        <select name="year-level" id="leaderboard-year-level-filter">
                            <option value="">--Filter Year--</option>
                            <?php
                                $year_levels = $student->fetchYearLevels();
                                foreach ($year_levels as $year){
                            ?>
                                <option value="<?= $year['year_level'] ?>" <?= ($year_levels == $year['year_level']) ? 'selected' : '' ?>><?= $year['year_level'] ?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <!-- <input type="submit" id="filter-year-button" value="filter-year"> -->
                        <a href="results" id="results-link"><button id="student-calculate-calculate">Calculate</button></a>   

                    </form>
                </div>
                <div id="leaderboard-top-notchers">
                    <div class="topnotcher-pads">
                        <div class="topnotcher-div-1"><p>BS INFORMATION TECHNOLOGY</p></div>
                        <div class="topnotcher-div-2"><h2><?php echo $cs_top1['fullname'] ?? 'None<br><br>'; ?></h2></div>
                        <div class="topnotcher-div-3">
                            <div class="topnotcher-info">
                                <h4>TOP#1</h4>
                                <div><h3><?php echo $cs_top1['gwa'] ?? 'None'; ?></h3></div>
                                <p>RATING</p>
                            </div>
                            <div class="topnotcher-trophy">
                                trophy
                            </div>
                        </div>
                    </div>
                    <div class="topnotcher-pads">
                        <div class="topnotcher-div-1"><p>ASSOCIATE IN COMPUTER TECHNOLOGY</p></div>
                        <div class="topnotcher-div-2"><h2><?php echo $it_top1['fullname'] ?? 'None<br><br>'; ?></h2></div>
                        <div class="topnotcher-div-3">
                            <div class="topnotcher-info">
                                <h4>TOP#1</h4>
                                <div><h3><?php echo $it_top1['gwa'] ?? 'None'; ?></h3></div>
                                <p>RATING</p>
                            </div>
                            <div class="topnotcher-trophy">
                                trophy
                            </div>
                        </div>
                    </div>
                    <div class="topnotcher-pads">
                        <div class="topnotcher-div-1"><p>BS COMPUTER SCIENCE</p></div>
                        <div class="topnotcher-div-2"><h2><?php echo $act_top1['fullname'] ?? 'None<br><br>'; ?></h2></div>
                        <div class="topnotcher-div-3">
                            <div class="topnotcher-info">
                                <h4>TOP#1</h4>
                                <div><h3><?php echo $act_top1['gwa'] ?? 'None'; ?></h3></div>
                                <p>RATING</p>
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
                            <?php 
                                $csi = 1;
                                foreach ($cs_leaderboard as $csl){ ?>
                                    <div class="leaderboard-list-pad list-body">
                                        <div class="leaderboard-list-pad-div1">
                                            <p class="student-name"><?php echo $csl['fullname']; ?></p>
                                        </div>
                                        <div class="leaderboard-list-pad-div2">
                                            <p class="student-rating"><?php echo $csl['gwa']; ?></p>
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
                                            <p class="student-name"><?php echo $itl['fullname']; ?></p>
                                        </div>
                                        <div class="leaderboard-list-pad-div2">
                                            <p class="student-rating"><?php echo $itl['gwa']; ?></p>
                                        </div>
                                    </div>
                            <?php 
                            $iti++;
                            }?>
                        </div>
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
                                            <p class="student-rating"><?php echo $actl['gwa']; ?></p>
                                        </div>
                                    </div>
                            <?php 
                            $acti++;
                            }?>
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



