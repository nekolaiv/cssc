<div id="result-section">
    <h2 id="result-message-1"><?php echo $_SESSION['GWA']['message-1']?></h2>
    <h2 id="result-message-2"><?php echo $_SESSION['GWA']['message-2']?></h2>
    <h2 id="result-message-3"><?php echo $_SESSION['GWA']['message-3']?></h2>
    <h2 id="result-message-4">GWA SCORE: <?php echo $_SESSION['GWA']['gwa-score']?></h2>
    <h2 id="result-verification-status">Verification Status: 
        <?php echo $_SESSION['profile']['status'];?>
    </h2>
    <div id="result-action-buttons">
        <button class="nav-items" onclick="loadPage('home.php')">Back to Home</button>
        <button class="nav-items" onclick="loadPage('calculate.php')">Edit Inputs</button>
        <button type="submit" class="nav-items" id="submit-calculation-entry"> <?php echo $validate_button ?? "Validate Entry 1" ?></button>
    </div>
</div>
