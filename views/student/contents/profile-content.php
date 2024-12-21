<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
?>
<section class="profile-section">
    <div class="div-pad" id="profile-div">
        <div id="profile-left-section">
            <div class="ccs-logo"></div>
        </div>
        <div id="profile-right-section">
            <div class="profile-data-pad">
                <div class="profile-data-header">Name:</div>
                <div class="profile-data-body"><?php echo $_SESSION['profile']['user-name']?></div>
            </div>
            <div class="profile-data-pad">
                <div class="profile-data-header">Student Id:</div>
                <div class="profile-data-body"><?php echo $_SESSION['profile']['student-id']?></div>
            </div>
            <div class="profile-data-pad">
                <div class="profile-data-header">S-Y:</div>
                <div class="profile-data-body"><?php echo $_SESSION['profile']['school-year']?></div>
            </div>
            <div class="profile-data-pad">
                <div class="profile-data-header">Course & Year:</div>
                <div class="profile-data-body"><?php echo $_SESSION['profile']['course'],' ', $_SESSION['profile']['year-level'] ?></div>
            </div>
            <div class="profile-data-pad">
                <div class="profile-data-header">Status:</div>
                <div class="profile-data-body"><?php echo $_SESSION['profile']['status'] ?? "Not Submitted"?></div>
            </div>
        </div>
    </div>
</section>