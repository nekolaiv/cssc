<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
<section class="calculate-section">
    <div class="div-pad" id="calculate-div1">
        <header>
            <h1>Grade Calculator</h1>
        </header>
    </div>
    <div class="div-pad" id="calculate-div2">  
        <form id="grading" method="POST">
            <?php if (isset($_SESSION['course-fields'])): ?>
                <?php for ($i = 0; $i < count($_SESSION['course-fields']['subject-code']); $i++): ?>
                    <div id="row-<?= $i ?>">
                        <input type="text" name="subject-code[]" value="<?= $_SESSION['course-fields']['subject-code'][$i] ?>">
                        <input type="number" name="unit[]" value="<?= $_SESSION['course-fields']['unit'][$i] ?>">
                        <input type="number" name="grade[]" value="<?= $_SESSION['course-fields']['grade'][$i] ?>">
                        <button type="button" onclick="removeSubjectRow(<?= $i ?>)">X</button>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </form>
        <div>
            <button type="button" onclick="addSubjectRow()">
                Add Row +
            </button>
           <form action="" method="POST">
                <input type="hidden" name="calculate-GWA" value="calculate-GWA">
                <button type="submit" onclick="loadPage('results.php')">
                    Calculate
                </button>
           </form>
        </div>
    </div>
</section>

