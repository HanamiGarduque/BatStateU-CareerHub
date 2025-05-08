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
                        <div class="saved-job-card" data-saved-id="<?php echo $job['saved_jobs_id']; ?>" data-job-id="<?php echo $job['job_id']; ?>">
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
                                    <button class="view-btn" data-job-id="<?php echo htmlspecialchars($job['job_id']); ?>">View Details</button>
                                </div>
                                
                                <!-- Hidden job data for modal -->
                                <div class="job-data" style="display: none;">
                                    <div class="job-description"><?php echo isset($job['description']) ? htmlspecialchars($job['description']) : ''; ?></div>
                                    <div class="job-responsibilities"><?php echo isset($job['responsibilities']) ? htmlspecialchars($job['responsibilities']) : ''; ?></div>
                                    <div class="job-requirements"><?php echo isset($job['requirements']) ? htmlspecialchars($job['requirements']) : ''; ?></div>
                                    <div class="job-benefits"><?php echo isset($job['benefits_perks']) ? htmlspecialchars($job['benefits_perks']) : ''; ?></div>
                                    <div class="job-posted-date"><?php echo isset($job['date_posted']) ? htmlspecialchars($job['date_posted']) : ''; ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                    <a href="#" id="modalApplyBtn" class="modal-btn apply-modal-btn">
                        <i class="fas fa-paper-plane"></i> Apply Now
                    </a>
                    <button id="modalCloseBtn" class="modal-btn save-modal-btn">
                        <i class="fas fa-arrow-left"></i> Back to Saved Jobs
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
            const viewButtons = document.querySelectorAll('.view-btn');
            const savedJobsContainer = document.querySelector('.saved-jobs-grid');
            const modal = document.getElementById('jobDetailsModal');
            const closeModalBtn = document.getElementById('closeModal');
            const modalCloseBtn = document.getElementById('modalCloseBtn');
            
            // View Details button functionality
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    const jobCard = this.closest('.saved-job-card');
                    
                    // Get job data
                    const title = jobCard.querySelector('.job-title').textContent;
                    const company = jobCard.querySelector('.company-name').textContent;
                    const location = jobCard.querySelector('.job-detail:nth-child(1)').textContent.trim();
                    const jobType = jobCard.querySelector('.job-detail:nth-child(2)').textContent.trim();
                    const salary = jobCard.querySelector('.job-detail:nth-child(3)').textContent.trim();
                    
                    // Get hidden data
                    const jobData = jobCard.querySelector('.job-data');
                    const description = jobData.querySelector('.job-description') ? 
                        jobData.querySelector('.job-description').textContent : 'No description available.';
                    const responsibilities = jobData.querySelector('.job-responsibilities') ? 
                        jobData.querySelector('.job-responsibilities').textContent : 'No responsibilities listed.';
                    const requirements = jobData.querySelector('.job-requirements') ? 
                        jobData.querySelector('.job-requirements').textContent : 'No requirements listed.';
                    const benefits = jobData.querySelector('.job-benefits') ? 
                        jobData.querySelector('.job-benefits').textContent : 'No benefits listed.';
                    const postedDate = jobData.querySelector('.job-posted-date') ? 
                        jobData.querySelector('.job-posted-date').textContent : 'Unknown date';
                    
                    // Get job description from the server using AJAX
                    fetch(`get_job_details.php?job_id=${jobId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Update modal content
                            document.getElementById('modalJobTitle').textContent = title;
                            document.getElementById('modalCompanyName').textContent = company;
                            document.getElementById('modalLocation').textContent = location;
                            document.getElementById('modalJobType').textContent = jobType;
                            document.getElementById('modalSalary').textContent = salary;
                            document.getElementById('modalDescription').innerHTML = formatContent(data.description || description);
                            document.getElementById('modalResponsibilities').innerHTML = formatContent(data.responsibilities || responsibilities);
                            document.getElementById('modalRequirements').innerHTML = formatContent(data.requirements || requirements);
                            document.getElementById('modalBenefits').innerHTML = formatContent(data.benefits_perks || benefits);
                            document.getElementById('modalPostedDate').textContent = formatDate(data.posted_date || postedDate);
                            
                            // Set apply button link
                            document.getElementById('modalApplyBtn').href = `applying_job.php?job_id=${jobId}`;
                            
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
                            document.getElementById('modalSalary').textContent = salary;
                            document.getElementById('modalDescription').innerHTML = formatContent(description);
                            document.getElementById('modalResponsibilities').innerHTML = formatContent(responsibilities);
                            document.getElementById('modalRequirements').innerHTML = formatContent(requirements);
                            document.getElementById('modalBenefits').innerHTML = formatContent(benefits);
                            document.getElementById('modalPostedDate').textContent = formatDate(postedDate);
                            
                            // Set apply button link
                            document.getElementById('modalApplyBtn').href = `applying_job.php?job_id=${jobId}`;
                            
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