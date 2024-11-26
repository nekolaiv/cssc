<section id="leaderboard-section">
    <div class="div-pad" id="leaderboard-div1">
        <div id="leaderboard-div1-innerdiv">
            <h2>CCS - LEADERBOARD</h2>
            <p>CONGRATULATIONS TO EVERY STUDENT</p>
        </div>
    </div>
    <div id="leaderboard-course-options">
        <div id="leaderboard-courses">
            <button onclick="loadPage('leaderboard.php')" class="leaderboard-course active">ALL</button>
            <button onclick="loadPage('leaderboard-cs.php')" class="leaderboard-course">CS</button>
            <button onclick="loadPage('leaderboard-it.php')" class="leaderboard-course">IT</button>
            <button onclick="loadPage('leaderboard-act.php')" class="leaderboard-course">ACT</button>
        </div>
    </div>
    <div id="leaderboard-top-notchers">
        <div class="topnotcher-pads">
            <div class="topnotcher-div-1"><p>BS COMPUTER SCIENCE</p></div>
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
            <div class="topnotcher-div-1"><p>BS COMPUTER SCIENCE</p></div>
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
    <!-- <div class="" id="home-div2">
        <div class="home-div2-leftdiv">
            <div class="home-div2-leftdiv-congratulations">
                <h2>Congratulations!</h2>
                <h4>TO OUR TOP RANKERS</h4>
            </div>
            <p>We’re thrilled to honor your hard work, dedication, and<br>
            exceptional achievements. Your commitment to<br>
            excellence sets a high standard and inspires others in<br>
            the academic community. Keep reaching for success—<br>
            you’ve earned it!</p>
            <button class="leaderboard-button" onclick="loadPage('leaderboard.php')">LEADERBOARD</button>
        </div>
        <div class="div-pad home-div2-rightdiv">

        </div>
    </div> -->
</section>