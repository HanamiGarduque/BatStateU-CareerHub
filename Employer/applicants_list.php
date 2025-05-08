<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isEmployer()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}

$database = new Database();
$db = $database->getConnect();

$application = new JobApplication($db);
$job = new Jobs($db);
$job_id = $_GET['job_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - Applicants List</title>
    <link rel="stylesheet" href="../Layouts/employer.css">
    <link rel="stylesheet" href="../Layouts/applicants.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo-container">
                <div class="logo"></div>
                <h3>Career Hub</h3>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="employer_profile.php"><i class="fas fa-user-tie"></i> Employer Profile</a>
                    </li>
                    <li>
                        <a href="job_postings.php"><i class="fas fa-briefcase"></i> Job Postings</a>
                    </li>
                    <li>
                        <a href="applications_management.php"><i class="fas fa-file-alt"></i> Applications</a>
                    </li>

                    <li class="logout">
                        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="dashboard-header">
                <div class="search-container">

                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="../placeholder.jpg" alt="Profile Picture">
                        <span>Welcome, Employer</span>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">
                <div class="content-header">
                    <h1><i class="fas fa-users"></i> Applicants List</h1>
                    <div class="header-actions">
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Applications
                        </a>
                    </div>
                </div>

                <div class="applicants-container">
                    <?php
                    $jobData = $job->retrieveJobById($job_id);

                    ?>
                    <div class="job-details-card">
                        <div class="job-details-header">
                            <h2><?php echo ($jobData['title']); ?></h2>
                        </div>
                        <div class="job-details-info">
                            <div class="job-detail"><i class="fas fa-map-marker-alt"></i><?php echo htmlspecialchars($jobData['location']); ?></div>
                            <div class="job-detail"><i class="fas fa-briefcase"></i><?php echo htmlspecialchars($jobData['type']); ?></div>
                            <div class="job-detail"><i class="fas fa-money-bill-wave"></i> <?php echo htmlspecialchars($jobData['salary_min'] . '-' . $jobData['salary_max']); ?></div>
                            <div class="job-detail"><i class="fas fa-calendar-alt"></i> Posted: <?php echo date("F j, Y", strtotime($jobData['date_posted'])); ?></div>
                        </div>
                    </div>
                    <?php
                    $results = $application->retrieveApplicationsByJobID($job_id);
                    $num = count($results);

                    if ($num > 0) {
                        foreach ($results as $row) {
                            $application_id = $row['id'];
                    ?>

                            <div class="applicants-list">
                                <!-- Applicant 1 -->
                                <div class="applicant-card">
                                    <div class="applicant-header">
                                        <div class="applicant-profile">
                                            <div class="applicant-avatar">
                                                <img src="../placeholder.jpg" alt="Applicant Photo">
                                            </div>
                                            <div class="applicant-info">
                                                <h3><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h3>
                                                <p class="applicant-title"><?php echo htmlspecialchars($row['title']); ?></p>
                                                <p class="applicant-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['address']); ?></p>
                                            </div>
                                        </div>
                                        <div class="application-status">
                                            
                                            <span class="application-status-badge <?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                                        </div>
                                    </div>

                                    <div class="applicant-details">
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-envelope"></i> Email:</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($row['email']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-phone"></i> Phone:</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($row['phone_number']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-calendar-alt"></i> Applied:</span>
                                            <span class="detail-value"><?php echo date("F j, Y", strtotime($row['created_at'])); ?></span>
                                        </div>
                                    </div>

                                    <div class="cover-letter">
                                        <h4><i class="fas fa-file-alt"></i> Cover Letter</h4>
                                        <div class="cover-letter-content">
                                            <?php
                                            // check if cover letter exists
                                            if (!empty($row['cover_letter'])) {
                                                echo nl2br(htmlspecialchars($row['cover_letter']));
                                            } else {
                                                echo '<p>No cover letter provided.</p>';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="applicant-actions">
                                        <div class="resume-action">
                                            <a class="action-btn view-resume-btn" href="../JobSeeker/<?php echo htmlspecialchars($row['resume_path']); ?>" target="_blank">
                                                <i class="fas fa-file-pdf"></i> View Resume
                                            </a>
                                        </div>

                                        <div class="status-action">
                                            <form action="update_status.php" method="POST" class="status-form">
                                                <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($application_id); ?>">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($jobseeker_id); ?>">
                                                <select name="status" class="status-select">
                                                    <option value="Under Review" <?php echo $row['status'] == 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                                                    <option value="Shortlisted" <?php echo $row['status'] == 'Shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                                                    <option value="Interview" <?php echo $row['status'] == 'Interview' ? 'selected' : ''; ?>>Interview</option>
                                                    <option value="Accepted" <?php echo $row['status'] == 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                                                    <option value="Rejected" <?php echo $row['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                                <button type="button" class="btn btn-update">Update Status</button>
                                            </form>
                                        </div>


                                    </div>
                                </div>


                            </div>
                    <?php
                        }
                    } else {
                        echo "<div class='no-applications'><i class='fas fa-search'></i><p>No applications found.</p></div>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event for status update buttons
            const updateButtons = document.querySelectorAll('.btn-update');
            updateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const select = this.closest('.status-form').querySelector('.status-select');
                    const status = select.value;
                    const applicantName = this.closest('.applicant-card').querySelector('.applicant-info h3').textContent;

                    // Show success message
                    Swal.fire({
                        title: 'Status Updated',
                        text: `${applicantName}'s application status has been updated to ${status}.`,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#c41e3a'
                    });

                    // Update the status badge
                    const statusBadge = this.closest('.applicant-card').querySelector('.application-status');
                    statusBadge.className = 'application-status ' + status.toLowerCase();
                    statusBadge.textContent = status;
                });
            });
            const filter = this.dataset.filter.toLowerCase();

            applications.forEach(app => {
                const statusElement = app.querySelector(".application-status-badge");
                const status = statusElement ? statusElement.textContent.trim().toLowerCase() : "";

                if (filter === "all" || status === filter) {
                    app.style.display = "block";
                } else {
                    app.style.display = "none";
                }
            });

            // Add change event for job selector
            const jobSelect = document.getElementById('job_id');
            jobSelect.addEventListener('change', function() {
                const selectedJob = this.options[this.selectedIndex].text;
                if (selectedJob !== '-- Select a Job --') {
                    Swal.fire({
                        title: 'Job Selected',
                        text: `Showing applicants for: ${selectedJob}`,
                        icon: 'info',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#c41e3a'
                    });
                }
            });
        });
    </script>
</body>

</html>