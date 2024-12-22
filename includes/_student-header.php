<header>
    <p class="logo"><a href="home" id="home-logo-link" class="nav-items">CSRS</a></p>
    <button class="menu-toggle" id="menu-toggle">
        <span class="menu-icon"></span>
    </button>
    <nav class="nav-menu" id="nav-menu">
        <ul>
            <a href="home" id="home-link" class="nav-items"><button>Home</button></a>
            <a href="leaderboard" id="leaderboard-link" class="nav-items"><button>Leaderboard</button></a>
            <a href="calculate" id="calculate-link" class="nav-items"><button>Calculate</button></a>
        </ul>
        <div class="profile-icon" id="profile-icon">
            <div class="dropdown" id="profile-dropdown">
                <a href="profile" id="profile-link" class="nav-items"><button>Profile</button></a>
                
                <form action="" method="POST">
                    <!-- <input type="hidden" name="action" value="logout"> -->
                    <button type="submit" name="logout" value="logout" id="logout-button">Logout</button>
                </form>
            </div>
        </div>
    </nav> 
    <input type="hidden" id="calculate-link"> 
    <input type="hidden" id="calculate-gwa"> 
    <input type="hidden" id="results-link"> 
    <input type="hidden" id="previous-link"> 
    <input type="hidden" id="filter-year"> 
    <input type="hidden" id="leaderboard-cs-link">
    <input type="hidden" id="leaderboard-it-link">
    <input type="hidden" id="leaderboard-act-link">
</header>
<script src="/cssc/js/student_hamburger-menu.js"></script>
<script src="/cssc/js/student_profile-dropdown.js"></script>