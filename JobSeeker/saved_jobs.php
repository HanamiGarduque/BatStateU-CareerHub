<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';


if (!isJobseeker()) {
    header('Location: /ADMSSYSTEM/logout.php'); // Or wherever you want
    exit();
  }
  

$database = new Database();
$conn = $database->getConnect();

// Fetch saved jobs with job details
try {
    $user_id = $_SESSION['id'];
    $sql = "SELECT sj.saved_jobs_id, sj.saved_date, 
                   j.job_id, j.title AS job_title, 
                   j.company_name, j.location, 
                   j.type AS job_type, 
                   j.salary_min, j.salary_max
            FROM saved_jobs sj
            INNER JOIN jobs j ON sj.job_id = j.job_id
            WHERE sj.user_id = :user_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $saved_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $saved_count = count($saved_jobs);

} catch (PDOException $e) {
    die("Error fetching saved jobs: " . $e->getMessage());
}

function time_elapsed_string($datetime) {

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
                <div class="logo"></div>
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
                        <img src="../placeholder.jpg" alt="Profile Picture">
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
                        
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid"><i class="fas fa-th-large"></i></button>
                            <button class="view-btn" data-view="list"><i class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>
                
                <!-- Saved Jobs Grid -->
                <div class="saved-jobs-grid">
                    <?php foreach ($saved_jobs as $job): ?>
                    <div class="saved-job-card" data-saved-id="<?php echo $job['saved_jobs_id']; ?>">
                        <div class="saved-job-header">
                            <div class="company-logo"></div>
                            <button class="remove-saved-job"><i class="fas fa-times"></i></button>
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
                                <i class="fas fa-clock"></i> Saved <?php echo time_elapsed_string($job['saved_date']); ?>
                            </div>
                            
                            <div class="job-actions">
                                <button class="apply-btn">Apply Now</button>
                                <button class="view-btn">View Details</button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
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
            
            // Remove saved job
            document.querySelectorAll('.remove-saved-job').forEach(button => {
                button.addEventListener('click', function() {
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
                            fetch('remove_saved_job.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ saved_job_id: savedId })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    jobCard.style.opacity = '0';
                                    setTimeout(() => {
                                        jobCard.remove();
                                        // Update saved count
                                        const countElement = document.querySelector('.saved-jobs-count h3');
                                        const currentCount = parseInt(countElement.textContent);
                                        countElement.textContent = `${currentCount - 1} Saved Jobs`;
                                    }, 300);
                                    Swal.fire('Removed!', 'Job removed successfully.', 'success');
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>