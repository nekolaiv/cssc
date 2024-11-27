<header>
    <p class="logo"><a onclick="loadPage('home.php')">CSRS</a></p>
    <button class="menu-toggle" id="menu-toggle">
        <span class="menu-icon"></span>
    </button>
    <nav class="nav-menu" id="nav-menu">
        <ul>
            <a href="home" id="home-link" class="nav-items"><button>Home</button></a>
            <a href="leaderboard" id="leaderboard-link" class="nav-items"><button>Leaderboard</button></a>
            <a href="calculate" id="calculate-link" class="nav-items"><button>Calculate</button></a>
            <a href="about" id="about-link" class="nav-items"><button>About</button></a>
        </ul>
        <div class="notification-bell">
            <span class="badge">3</span> <!-- Change this number dynamically with PHP -->
        </div>
        <div class="profile-icon" id="profile-icon">
            <div class="dropdown" id="profile-dropdown">
                <a href="profile" id="profile-link" class="nav-items"><button>profile</button></a>
                <a href="settings" id="settings-link" class="nav-items"><button>settings</button></a>
                
                <form action="" method="POST">
                    <!-- <input type="hidden" name="action" value="logout"> -->
                    <button type="submit" name="logout" value="logout" id="logout-button">Logout</button>
                </form>
            </div>
        </div>
    </nav>  
</header>
<script src="/cssc/js/student_hamburger-menu.js"></script>
<script src="/cssc/js/student_profile-dropdown.js"></script>