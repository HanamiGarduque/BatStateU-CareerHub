<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}

$database = new Database();
$db = $database->getConnect();

$application = new JobApplication($db);
$jobs = new Jobs($db); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - My Applications</title>
    <link rel="stylesheet" href="../Layouts/jobseeker.css">
    <link rel="stylesheet" href="../Layouts/job_details.css">

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
                    <li class="active">
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

            <!-- Applications Content -->
            <div class="dashboard-content">
                <h1>My Applications</h1>

                <!-- Applications Filter -->
                <div class="applications-filter">
                    <div class="filter-tabs">
                        <button class="filter-tab active" data-filter="all">All Applications (8)</button>
                        <button class="filter-tab" data-filter="pending">Pending (3)</button>
                        <button class="filter-tab" data-filter="interview">Interview (2)</button>
                        <button class="filter-tab" data-filter="accepted">Accepted (1)</button>
                        <button class="filter-tab" data-filter="rejected">Rejected (2)</button>
                    </div>

                    <div class="filter-options">
                        <select class="sort-select">
                            <option value="recent">Most Recent</option>
                            <option value="oldest">Oldest First</option>
                            <option value="company-az">Company (A-Z)</option>
                            <option value="company-za">Company (Z-A)</option>
                        </select>
                    </div>
                </div>

                <!-- Applications List -->
                <div class="applications-list">
                    <?php
                    $emp_id = $_SESSION['id'];

                    $results = $application->retrieveUserApplications($emp_id);
                    $num = count($results);

                    if ($num > 0) {
                        foreach ($results as $row) {
                            $application_id = $row['id'];
                            $job_id = $row['job_id'];

                            // Get full job details for the modal
                            $jobDetails = $jobs->retrieveJobById($job_id);
                            $jobData = $jobDetails->fetch(PDO::FETCH_ASSOC);

                            echo <<<HTML
                            <div class="job-card" data-job-id="{$job_id}">
                            HTML;
                    ?>
                            <div class="application-item">
                                <div class="application-main">
                                    <div class="company-logo">
                                        <img src="../Layouts/work_icon.png" alt="Job Icon">
                                    </div>
                                    <div class="application-details">
                                        <h3 class="job-title"><?php echo htmlspecialchars($row['job_title']); ?></h3>
                                        <p class="company-name"><?php echo htmlspecialchars($row['company_name']); ?></p>
                                        <div class="application-meta">
                                            <span class="meta-item"><i class="fas fa-calendar"></i> Applied: <?php echo date("F j, Y", strtotime($row['created_at'])); ?></span>
                                            <span class="meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['location']); ?></span>
                                            <span class="meta-item"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($row['type']); ?></span>
                                        </div>
                                    </div>
                                    <div class="application-status">
                                        <div class="application-status-badge pending"><?php echo htmlspecialchars($row['status']); ?></div>
                                        <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
                                    </div>
                                </div>

                                <div class="application-details-expanded">
                                    <?php
                                    $statusLog = new StatusLog($db);
                                    $statuses = $statusLog->retrieveStatusLog($application_id);


                                    $customLabels = [
                                        'Under Review' => 'Application Received',
                                        'Shortlisted' => 'Resume Screened',
                                        'Interview' => 'Interview Scheduled',
                                        'Accepted' => 'Application Accepted',
                                        'Rejected' => 'Application Rejected'
                                    ];

                                    $allSteps = [
                                        'Under Review',
                                        'Shortlisted',
                                        'Interview',
                                        'Final Decision'
                                    ];
                                    echo '<div class="timeline">';
                                    $addedStatuses = array_column($statuses, 'status');
                                    $isUnderReviewChecked = !in_array('Under Review', $addedStatuses); // Check if "Under Review" is missing

                                    foreach ($allSteps as $step) {
                                        if ($step == 'Under Review' && $isUnderReviewChecked) {
                                            $timestamp = date("F j, Y \\a\\t g:i A", time());
                                            $label = isset($customLabels[$step]) ? $customLabels[$step] : $step;
                                            echo '
                                        <div class="timeline-item active">
                                            <div class="timeline-icon"><i class="fas fa-check"></i></div>
                                            <div class="timeline-content">
                                            <h4>' . htmlspecialchars($label) . '</h4>
                                            <p>' . $timestamp . '</p>
                                            </div>
                                        </div>';
                                        } elseif ($step == 'Final Decision') {
                                            $decisionMade = false;
                                            $timestamp = '';

                                            foreach ($statuses as $s) {
                                                if ($s['status'] == 'Accepted') {
                                                    $timestamp = date("F j, Y \\a\\t g:i A", strtotime($s['timestamp']));
                                                    $label = 'Application Accepted';
                                                    echo '
                                            <div class="timeline-item active">
                                              <div class="timeline-icon"><i class="fas fa-check"></i></div>
                                              <div class="timeline-content">
                                                <h4>' . htmlspecialchars($label) . '</h4>
                                                <p>' . $timestamp . ' </p>
                                              </div>    
                                            </div>';
                                                    $decisionMade = true;
                                                    break;
                                                } elseif ($s['status'] == 'Rejected') {
                                                    $timestamp = date("F j, Y \\a\\t g:i A", strtotime($s['timestamp']));
                                                    $label = 'Application Rejected';
                                                    echo '
                                              <div class="timeline-item active">
                                                <div class="timeline-icon"><i class="fas fa-times"></i></div>
                                                <div class="timeline-content">
                                                  <h4>' . htmlspecialchars($label) . '</h4>
                                                  <p>' . $timestamp . ' </p>
                                                </div>
                                              </div>';
                                                    $decisionMade = true;
                                                    break;
                                                }
                                            }

                                            if (!$decisionMade) {
                                                $label = 'Final Decision';
                                                echo ' 
                                          <div class="timeline-item">
                                            <div class="timeline-icon"><i class="fas fa-hourglass-half"></i></div>
                                            <div class="timeline-content">
                                              <h4>' . htmlspecialchars($label) . '</h4>
                                              <p>Pending</p>
                                            </div>
                                          </div>';
                                            }
                                        } else {
                                            // For other steps, check if they exist in the database and show the appropriate status
                                            if (in_array($step, $addedStatuses)) {
                                                // Find the timestamp for the step
                                                $timestamp = '';
                                                foreach ($statuses as $s) {
                                                    if ($s['status'] === $step) {
                                                        $timestamp = date("F j, Y \\a\\t g:i A", strtotime($s['timestamp']));
                                                        break;
                                                    }
                                                }
                                                $label = isset($customLabels[$step]) ? $customLabels[$step] : $step;
                                                echo '
                                          <div class="timeline-item active">
                                            <div class="timeline-icon"><i class="fas fa-check"></i></div>
                                            <div class="timeline-content">
                                              <h4>' . htmlspecialchars($label) . '</h4>
                                              <p>' . $timestamp . '</p>
                                            </div>
                                          </div>';
                                            } else {
                                                // Step is not in the status log, so it is pending
                                                $label = isset($customLabels[$step]) ? $customLabels[$step] : $step;
                                                echo ' 
                                          <div class="timeline-item">
                                            <div class="timeline-icon"><i class="fas fa-hourglass-half"></i></div>
                                            <div class="timeline-content">
                                              <h4>' . htmlspecialchars($label) . '</h4>
                                              <p>Pending</p>
                                            </div>
                                          </div>';
                                            }
                                        }
                                    }

                                    echo '</div>';
                                    ?>

                                    <div class="application-actions">
                                        <button class="view-job-btn" data-job-id="<?php echo htmlspecialchars($row['job_id']); ?>">
                                            <i class="fas fa-external-link-alt"></i> View Job
                                        </button>
                                    </div>

                                    <!-- Hidden job data for modal -->
                                    <div class="job-data" style="display: none;">
                                        <div class="salary_range">
                                            <?php
                                            if (isset($jobData['salary_min']) && isset($jobData['salary_max'])) {
                                                echo htmlspecialchars($jobData['salary_min']) . ' - ' . htmlspecialchars($jobData['salary_max']);
                                            } elseif (isset($jobData['salary_min'])) {
                                                echo htmlspecialchars($jobData['salary_min']);
                                            } elseif (isset($jobData['salary_max'])) {
                                                echo htmlspecialchars($jobData['salary_max']);
                                            }
                                            ?>
                                        </div>
                                        <div class="job-responsibilities"><?php echo isset($jobData['responsibilities']) ? htmlspecialchars($jobData['responsibilities']) : ''; ?></div>
                                        <div class="job-requirements"><?php echo isset($jobData['requirements']) ? htmlspecialchars($jobData['requirements']) : ''; ?></div>
                                        <div class="job-benefits"><?php echo isset($jobData['benefits_perks']) ? htmlspecialchars($jobData['benefits_perks']) : ''; ?></div>
                                        <div class="job-posted-date"><?php echo isset($jobData['date_posted']) ? htmlspecialchars($jobData['date_posted']) : ''; ?></div>
                                    </div>
                                </div>
                            </div>
                    <?php
                            echo '</div>';
                        }
                    } else {
                        echo "<div class='no-applications'><i class='fas fa-search'></i><p>No applications found.</p></div>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Job Details Modal -->
    <div class="modal-overlay" id="jobDetailsModal">
        <div class="job-details-modal">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-company-logo">
                        <img src="../Layouts/work_icon.png" alt="Company Logo">
                    </div>
                    <div class="modal-job-title">
                        <h2 id="modalJobTitle">Job Title</h2>
                        <p class="modal-company-name" id="modalCompanyName">Company Name</p>
                    </div>
                </div>
                <button class="modal-close" id="closeModal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="job-overview">
                    <div class="job-overview-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="modalLocation">Location</span>
                    </div>
                    <div class="job-overview-item">
                        <i class="fas fa-briefcase"></i>
                        <span id="modalJobType">Job Type</span>
                    </div>
                    <div class="job-overview-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span id="modalSalary">Salary Range</span>
                    </div>
                </div>
                <div class="job-content">
                    <div class="job-section">
                        <h3 class="job-section-title">Job Description</h3>
                        <div class="job-section-content" id="modalDescription">
                            <!-- Job description will be inserted here -->
                        </div>
                    </div>
                    <div class="job-section">
                        <h3 class="job-section-title">Responsibilities</h3>
                        <div class="job-section-content" id="modalResponsibilities">
                            <!-- Responsibilities will be inserted here -->
                        </div>
                    </div>
                    <div class="job-section">
                        <h3 class="job-section-title">Requirements</h3>
                        <div class="job-section-content" id="modalRequirements">
                            <!-- Requirements will be inserted here -->
                        </div>
                    </div>
                    <div class="job-section">
                        <h3 class="job-section-title">Benefits & Perks</h3>
                        <div class="job-section-content" id="modalBenefits">
                            <!-- Benefits will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="modal-actions">
                    <a href="#" id="modalApplyBtn" class="modal-btn apply-modal-btn" style="display: none;">
                        <i class="fas fa-paper-plane"></i> Apply Now
                    </a>
                    <button id="modalCloseBtn" class="modal-btn save-modal-btn">
                        <i class="fas fa-arrow-left"></i> Back to Applications
                    </button>
                </div>
                <div class="job-posted">
                    Posted on <span id="modalPostedDate">Date</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-details-btn');
            const viewJobButtons = document.querySelectorAll('.view-job-btn');
            const modal = document.getElementById('jobDetailsModal');
            const closeModalBtn = document.getElementById('closeModal');
            const modalCloseBtn = document.getElementById('modalCloseBtn');

            // Toggle application details
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const applicationItem = this.closest('.application-item');
                    const detailsSection = applicationItem.querySelector('.application-details-expanded');

                    // Toggle expanded details
                    if (detailsSection.style.display === 'block') {
                        detailsSection.style.display = 'none';
                        this.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    } else {
                        detailsSection.style.display = 'block';
                        this.innerHTML = '<i class="fas fa-chevron-up"></i>';
                    }
                });
            });

            // Filter tabs
            const filterTabs = document.querySelectorAll('.filter-tab');
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    filterTabs.forEach(t => t.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Filter logic would go here
                    const filter = this.getAttribute('data-filter');
                    console.log('Filter by:', filter);
                });
            });

            // View Job button functionality
            viewJobButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    const jobCard = document.querySelector(`.job-card[data-job-id="${jobId}"]`);

                    // Get job data
                    const title = jobCard.querySelector('.job-title').textContent;
                    const company = jobCard.querySelector('.company-name').textContent;
                    const location = jobCard.querySelector('.meta-item:nth-child(2)').textContent.replace('Location: ', '').trim();
                    const jobType = jobCard.querySelector('.meta-item:nth-child(3)').textContent.replace('Job Type: ', '').trim();

                    // Get hidden data
                    const salary_range = jobCard.querySelector('.salary_range') ?
                        jobCard.querySelector('.salary_range').textContent : 'No responsibilities listed.';
                    const responsibilities = jobCard.querySelector('.job-responsibilities') ?
                        jobCard.querySelector('.job-responsibilities').textContent : 'No responsibilities listed.';
                    const requirements = jobCard.querySelector('.job-requirements') ?
                        jobCard.querySelector('.job-requirements').textContent : 'No requirements listed.';
                    const benefits = jobCard.querySelector('.job-benefits') ?
                        jobCard.querySelector('.job-benefits').textContent : 'No benefits listed.';
                    const postedDate = jobCard.querySelector('.job-posted-date') ?
                        jobCard.querySelector('.job-posted-date').textContent : 'Unknown date';

                    // Get job description from the server using AJAX
                    fetch(`get_job_details.php?job_id=${jobId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Update modal content
                            document.getElementById('modalJobTitle').textContent = title;
                            document.getElementById('modalCompanyName').textContent = company;
                            document.getElementById('modalLocation').textContent = location;
                            document.getElementById('modalJobType').textContent = jobType;
                            document.getElementById('modalSalary').innerHTML = formatContent(salary_range || 'Salary not specified');
                            document.getElementById('modalDescription').innerHTML = formatContent(data.description || 'No description available.');
                            document.getElementById('modalResponsibilities').innerHTML = formatContent(responsibilities);
                            document.getElementById('modalRequirements').innerHTML = formatContent(requirements);
                            document.getElementById('modalBenefits').innerHTML = formatContent(benefits);
                            document.getElementById('modalPostedDate').textContent = formatDate(postedDate);

                            // Show modal
                            modal.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        })
                        .catch(error => {
                            console.error('Error fetching job details:', error);

                            // Fallback if AJAX fails - use data from the page
                            document.getElementById('modalJobTitle').textContent = title;
                            document.getElementById('modalCompanyName').textContent = company;
                            document.getElementById('modalLocation').textContent = location;
                            document.getElementById('modalJobType').textContent = jobType;
                            document.getElementById('modalSalary').textContent = 'Salary information unavailable';
                            document.getElementById('modalDescription').innerHTML = '<p>Job description unavailable.</p>';
                            document.getElementById('modalResponsibilities').innerHTML = formatContent(responsibilities);
                            document.getElementById('modalRequirements').innerHTML = formatContent(requirements);
                            document.getElementById('modalBenefits').innerHTML = formatContent(benefits);
                            document.getElementById('modalPostedDate').textContent = formatDate(postedDate);

                            // Show modal
                            modal.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        });
                });
            });

            // Close modal functionality
            closeModalBtn.addEventListener('click', function() {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });

            modalCloseBtn.addEventListener('click', function() {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // Helper function to format content with bullet points
            function formatContent(content) {
                if (!content) return '<p>No information provided.</p>';

                // Check if content already has HTML formatting
                if (content.includes('<ul>') || content.includes('<li>')) {
                    return content;
                }

                // Split by new lines or bullet points
                const lines = content.split(/\n|•/).filter(line => line.trim() !== '');

                // If there's only one line, return it as a paragraph
                if (lines.length === 1) {
                    return `<p>${lines[0]}</p>`;
                }

                // Otherwise, format as a list
                return `<ul>${lines.map(line => `<li>${line.trim()}</li>`).join('')}</ul>`;
            }

            // Helper function to format date
            function formatDate(dateString) {
                if (!dateString) return 'Unknown date';

                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString;

                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
        });
    </script>
</body>

</html>