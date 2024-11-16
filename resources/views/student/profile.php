<section class="profile-section">
    <div class="div-pad" id="profile-div">
        <div id="profile-left-section">
            <div class="ccs-logo"></div>
        </div>
        <div id="profile-right-section">
            <div class="profile-data-pad">
                <div class="profile-data-header">Name:</div>
                <div class="profile-data-body"><?php echo $_SESSION['profile']['fullname']?></div>
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
                <div class="profile-data-header">Contact:</div>
                <div class="profile-data-body">09123456789</div>
            </div>
            <div class="profile-data-pad">
                <div class="profile-data-header">Status:</div>
                <div class="profile-data-body">Submitted and Unverified</div>
            </div>
        </div>
    </div>
</section>