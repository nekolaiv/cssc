

<section id="leaderboard-section">
    <div class="div-pad" id="leaderboard-div1">
        <div id="leaderboard-div1-innerdiv">
            <h2>CCS - LEADERBOARD</h2>
            <p>CONGRATULATIONS TO EVERY STUDENT</p>
        </div>
    </div>
    <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h1 class="page-title">Current Topnotchers</h1>
                </div>
            </div>
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
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">Student Overall Leaderboard</h1>
            </div>
        </div>
    </div>
   <div class="container-fluid">
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <?php
                        require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
                        $student = new Student();
                        ?>
                        <div class="d-flex justify-content-center align-items-center">
                            <form class="d-flex me-2">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search for a keyword...">
                                    <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                        <p>X</p>
                                    </span>
                                </div>
                            </form>
                            <div class="d-flex align-items-center">
                                <select id="course-filter" class="form-select">
                                    <option value="">All Courses</option>
                                    <?php
                                    $courses = $student->fetchCourses();
                                    foreach ($courses as $cor) {
                                    ?>
                                        <option value="<?= $cor['course'] ?>"><?= $cor['course'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <select id="year-filter" class="form-select">
                                    <option value="">All Year Levels</option>
                                    <?php
                                    $year_levels = $student->fetchYearLevels();
                                    foreach ($year_levels as $yls) {
                                    ?>
                                        <option value="<?= $yls['year_level'] ?>"><?= $yls['year_level'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <select id="period-filter" class="form-select">
                                    <option value="choose">All Submission Period</option>
                                    <?php
                                    $submission_id = $student->fetchSubmissionId();
                                    foreach ($submission_id as $sid) {
                                    ?>
                                        <option value="<?= $sid['submission_description'] ?>"><?= $sid['submission_description'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="table-products" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th>StudentID</th>
                                    <th>Name</th>
                                    <th>Rating</th>
                                    <th>Course</th>
                                    <th>Year</th>
                                    <th>Submission Period</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $array = $student->loadLeaderboard();

                                foreach ($array as $arr) {
                                ?>
                                    <tr>
                                        <td class="text-start"><?= $i ?></td>
                                        <td><?= $arr['student_id'] ?></td>
                                        <td><?= $arr['fullname'] ?></td>
                                        <td><?= $arr['total_rating'] ?></td>
                                        <td><?= $arr['course'] ?></td>
                                        <td><?= $arr['year_level'] ?></td>
                                        <td><?= $arr['submission_description'] ?></td>
                                        <!-- <td class="text-center">
                                            <span class="
                                                <?php
                                                // if ($available < 1) {
                                                //     echo 'badge rounded-pill bg-danger px-3';
                                                // } elseif ($available <= 5) {
                                                //     echo 'badge rounded-pill bg-warning px-3';
                                                // }
                                                ?>
                                            ">
                                                <?= $available ?>
                                            </span>
                                        </td> -->
                                    </tr>
                                <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/cssc/vendor/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="/cssc/vendor/datatable-2.1.8/datatables.min.js"></script>