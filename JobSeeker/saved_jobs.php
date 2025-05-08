<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';


if (!isJobseeker()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}


$database = new Database();
$conn = $database->getConnect();
$saved_job = new Bookmarks($conn);

// Fetch saved jobs with job details
try {
    $user_id = $_SESSION['id'];

    $saved_jobs = $saved_job->retrieveBookmarks($user_id);
    $saved_count = count($saved_jobs);
} catch (PDOException $e) {
    die("Error fetching saved jobs: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - Saved Jobs</title>
    <link rel="stylesheet" href="../Layouts/jobseeker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-container">
                <div class="logo">
                    <img src="../Layouts/logo.png" alt="Profile Picture">
                </div>
                <h3>Career Hub</h3>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="homepage.php"><i class="fas fa-search"></i> Find Jobs</a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                    </li>
                    <li>
                        <a href="applications_management.php"><i class="fas fa-file-alt"></i> My Applications</a>
                    </li>
                    <li class="active">
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
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search saved jobs...">
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="../Layouts/user_icon.png" alt="Profile Picture">
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></span>
                    </div>
                </div>
            </header>

            <!-- Saved Jobs Content -->
            <div class="dashboard-content">
                <h1>Saved Jobs</h1>

                <!-- Saved Jobs Filter -->
                <div class="saved-jobs-header">
                    <div class="saved-jobs-count">
                        <h3><?php echo $saved_count; ?> Saved Jobs</h3>
                    </div>

                    <div class="saved-jobs-actions">
                        <select class="sort-select">
                            <option value="recent">Most Recent</option>
                            <option value="oldest">Oldest First</option>
                            <option value="title-az">Job Title (A-Z)</option>
                            <option value="title-za">Job Title (Z-A)</option>
                            <option value="company-az">Company (A-Z)</option>
                            <option value="company-za">Company (Z-A)</option>
                        </select>

                    </div>
                </div>

                <!-- Saved Jobs Grid -->

                <div class="saved-jobs-grid">
                    <?php foreach ($saved_jobs as $job): ?>
                        <div class="saved-job-card" data-saved-id="<?php echo $job['saved_jobs_id']; ?>">
                            <div class="saved-job-header">
                                <div class="company-logo">
                                    <img src="../Layouts/work_icon.png" alt="Job Icon">
                                </div>
                                <form method="POST" action="remove_saved_job.php">
                                    <input type="hidden" name="saved_job_id" value="<?php echo $job['saved_jobs_id']; ?>">
                                    <button type="submit" class="remove-saved-job">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="saved-job-content">
                                <h3 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h3>
                                <p class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></p>

                                <div class="job-details">
                                    <div class="job-detail">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?>
                                    </div>
                                    <div class="job-detail">
                                        <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type']); ?>
                                    </div>
                                    <div class="job-detail">
                                        <i class="fas fa-money-bill-wave"></i>
                                        ₱<?php echo number_format($job['salary_min'], 0) ?> - ₱<?php echo number_format($job['salary_max'], 0) ?>
                                    </div>
                                </div>

                                <div class="saved-date">
                                    <i class="fas fa-clock"></i>
                                    Saved <?php echo date("F j, Y g:i A", strtotime($job['saved_date'])); ?>
                                </div>

                                <div class="job-actions">
                                    <a href="applying_job.php?job_id=<?php echo htmlspecialchars($job['job_id']); ?>"
                                        class="apply-btn"
                                        style="display: flex; justify-content: center; align-items: center; text-align: center; padding: 0.6rem 1rem; border-radius: 4px; font-size: 0.9rem; font-weight: 500; cursor: pointer; transition: background-color 0.3s;">
                                        Apply Now
                                    </a>
                                    <button class="view-btn">View Details</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


            </div>
        </main>
    </div>

    <script>
        // Toggle view (grid/list)
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-btn');
            const savedJobsContainer = document.querySelector('.saved-jobs-grid');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    viewButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const view = this.getAttribute('data-view');
                    savedJobsContainer.classList.toggle('list-view', view === 'list');
                });
            });
            // Handle removing a saved job using a remove button
            document.querySelectorAll('.remove-saved-job').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent the form from submitting immediately

                    const jobCard = this.closest('.saved-job-card');
                    const savedId = jobCard.dataset.savedId;

                    Swal.fire({
                        title: 'Remove Job',
                        text: 'Are you sure you want to remove this job from your saved list?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#c41e3a',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, remove it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form using JavaScript
                            const form = this.closest('form');
                            form.submit();
                        }
                    });
                });
            });



        });
    </script>
</body>

</html>