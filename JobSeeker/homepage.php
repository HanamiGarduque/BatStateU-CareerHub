<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: ../login.php'); // Or wherever you want
    exit();
}

$database = new Database();
$db = $database->getConnect();
$jobs = new Jobs($db);
$user_id = $_SESSION['id'] ?? null; // Get the user ID from the session

// Get the search keyword from the request
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Retrieve jobs based on the keyword

$stmt = $jobs->searchJobs($keyword);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - Find Jobs</title>
    <link rel="stylesheet" href="../Layouts/jobseeker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Save scroll position before leaving
        window.addEventListener('beforeunload', function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        });

        // Restore scroll position on load
        window.addEventListener('load', function() {
            const scrollY = localStorage.getItem('scrollPosition');
            if (scrollY !== null) {
                window.scrollTo(0, parseInt(scrollY));
                localStorage.removeItem('scrollPosition'); // Optional: clean up
            }
        });
    </script>

</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo-container">
                 <div class="logo">
                <img src="../Layouts/logo.png" alt="Profile Picture">
                </div>
                <h3>Career Hub</h3>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="homepage.php"><i class="fas fa-search"></i> Find Jobs</a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                    </li>
                    <li>
                        <a href="applications_management.php"><i class="fas fa-file-alt"></i> My Applications</a>
                    </li>
                    <li>
                        <a href="saved_jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a>
                    </li>
                    <li class="logout">
                        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header -->
            <header class="dashboard-header">
            <div class="search-container">
                     
                     </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="../Layouts/user_icon.png" alt="Profile Picture">
                        <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; ?></span>
                    </div>
                </div>
            </header>

            <!-- Job Search Content -->
            <div class="dashboard-content">
                <h1>Find Jobs</h1>

                <div class="job-search-container">
                    <div class="job-listings">
                        <h3>Search for Jobs</h3>
                        <form class="filter-form" method="GET" action="homepage.php">
                            <div class="form-group">
                                <input type="text" name="keyword" placeholder="Job title, skills, or keywords" value="<?php echo htmlspecialchars($keyword); ?>">
                                <button type="submit" class="search-btn">Search</button>
                            </div>

                        </form>
                        <?php
                        $totalJobs = $jobs->countAllJobs();
                        ?>
                        <div class="job-listings-header">
                            <h3><?php echo $totalJobs; ?> Jobs Found</h3>
                        </div>


                        <div class="jobs-list">
                            <?php
                            foreach ($stmt as $row) {
                                extract($row);

                                $bookmark = new Bookmarks($db);

                                // Check if the current job is bookmarked
                                $isSaved = $bookmark->isBookmarked($user_id, $job_id);
                                $savedClass = $isSaved ? 'saved' : '';

                                // Display job card
                                echo <<<HTML
                                <div class="job-card">
                                    <div class="job-header">
                                        <div class="company-logo">
                                        <img src="../Layouts/work_icon.png" alt="Job Icon">
                                        </div>
                                        <div class="job-title-container">
                                            <h3>{$title}</h3>
                                            <p class="company-name">{$company_name}</p>
                                        </div>
                                        <form method="POST" action="bookmark_job.php" class="bookmark-form">
                                        <input type="hidden" name="job_id" value="{$job_id}">
                                HTML;

                                echo '<button type="submit" class="save-job ' . ($isSaved ? 'saved' : '') . '">';
                                echo '<i class="fas fa-bookmark"></i>';
                                echo '</button></form>';

                                echo <<<HTML
            </div>
            <div class="job-details">
                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> {$location}</div>
                <div class="job-detail"><i class="fas fa-briefcase"></i> {$type}</div>
                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱{$salary_min} - ₱{$salary_max}</div>
            </div>
            <div class="job-description">
                <p>{$description}</p>
            </div>
            <div class="job-actions">
                <a href="applying_job.php?job_id={$job_id}" class="apply-btn">Apply Now</a>
                <button class="view-btn">View Details</button>
            </div>
            </div>
            HTML;
                            }

                            ?>

                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const searchInput = document.querySelector('input[name="keyword"]');


                                searchInput.focus();
                                const value = searchInput.value;
                                searchInput.value = '';

                                searchInput.value = value;

                                searchInput.addEventListener('input', function() {
                                    if (this.value.trim() === '') {
                                        window.location.href = 'homepage.php';
                                    }
                                });
                            });
                            document.querySelectorAll('.bookmark-form').forEach(form => {
                                form.addEventListener('submit', function(e) {
                                    e.preventDefault(); // Prevent reload

                                    const formData = new FormData(this);
                                    const button = this.querySelector('button');

                                    fetch(this.action, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                button.classList.toggle('saved', data.isSaved);
                                            }
                                        });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
    </div>
    </main>
    </div>
</body>

</html>