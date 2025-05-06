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


$stmt = $jobs->searchJobs($keyword);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - Find Jobs</title>
    <link rel="stylesheet" href="../Layouts/jobseeker.css">
    <link rel="stylesheet" href="../Layouts/job_details.css">
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
                                <div class="job-card" data-job-id="{$job_id}">
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
                                        <button class="view-btn" data-job-id="{$job_id}">View Details</button>
                                    </div>
                                    
                                    <div class="job-data" style="display: none;">
                                        <div class="job-responsibilities">{$responsibilities}</div>
                                        <div class="job-requirements">{$requirements}</div>
                                        <div class="job-benefits">{$benefits_perks}</div>
                                        <div class="job-posted-date">{$date_posted}</div>
                                    </div>
                                </div>
                                HTML;
                            }
                            ?>
                        </div>
                    </div>
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
                        </div>
                    </div>
                    <div class="job-section">
                        <h3 class="job-section-title">Responsibilities</h3>
                        <div class="job-section-content" id="modalResponsibilities">
                        </div>
                    </div>
                    <div class="job-section">
                        <h3 class="job-section-title">Requirements</h3>
                        <div class="job-section-content" id="modalRequirements">
                        </div>
                    </div>
                    <div class="job-section">
                        <h3 class="job-section-title">Benefits & Perks</h3>
                        <div class="job-section-content" id="modalBenefits">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="modal-actions">
                    <a href="#" id="modalApplyBtn" class="modal-btn apply-modal-btn">
                        <i class="fas fa-paper-plane"></i> Apply Now
                    </a>
                    <button id="modalSaveBtn" class="modal-btn save-modal-btn">
                        <i class="fas fa-bookmark"></i> Save Job
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
            const searchInput = document.querySelector('input[name="keyword"]');
            const modal = document.getElementById('jobDetailsModal');
            const closeModalBtn = document.getElementById('closeModal');
            const viewButtons = document.querySelectorAll('.view-btn');
            const modalApplyBtn = document.getElementById('modalApplyBtn');
            const modalSaveBtn = document.getElementById('modalSaveBtn');

            // Focus search input and restore value
            searchInput.focus();
            const value = searchInput.value;
            searchInput.value = '';
            searchInput.value = value;

            // Clear search on empty
            searchInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    window.location.href = 'homepage.php';
                }
            });

            // Bookmark functionality
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
                                
                                // Update modal save button if it's for the same job
                                const jobId = formData.get('job_id');
                                if (modalSaveBtn.dataset.jobId === jobId) {
                                    modalSaveBtn.classList.toggle('saved', data.isSaved);
                                }
                            }
                        });
                });
            });

            // Modal functionality
            closeModalBtn.addEventListener('click', function() {
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

            // View Details button functionality
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    const jobCard = document.querySelector(`.job-card[data-job-id="${jobId}"]`);
                    
                    // Get job data
                    const title = jobCard.querySelector('.job-title-container h3').textContent;
                    const company = jobCard.querySelector('.company-name').textContent;
                    const location = jobCard.querySelector('.job-detail:nth-child(1)').textContent.trim();
                    const jobType = jobCard.querySelector('.job-detail:nth-child(2)').textContent.trim();
                    const salary = jobCard.querySelector('.job-detail:nth-child(3)').textContent.trim();
                    const description = jobCard.querySelector('.job-description p').textContent;
                    
                    // Get hidden data
                    const responsibilities = jobCard.querySelector('.job-responsibilities').textContent;
                    const requirements = jobCard.querySelector('.job-requirements').textContent;
                    const benefits = jobCard.querySelector('.job-benefits').textContent;
                    const postedDate = jobCard.querySelector('.job-posted-date').textContent;
                    
                    // Check if job is saved
                    const saveButton = jobCard.querySelector('.save-job');
                    const isSaved = saveButton.classList.contains('saved');
                    
                    // Update modal content
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
                    
                    // Update modal buttons
                    modalApplyBtn.href = `applying_job.php?job_id=${jobId}`;
                    modalSaveBtn.dataset.jobId = jobId;
                    modalSaveBtn.classList.toggle('saved', isSaved);
                    
                    // Show modal
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });

            // Modal Save button functionality
            modalSaveBtn.addEventListener('click', function() {
                const jobId = this.dataset.jobId;
                const jobCard = document.querySelector(`.job-card[data-job-id="${jobId}"]`);
                const bookmarkForm = jobCard.querySelector('.bookmark-form');
                
                // Trigger the form submission
                bookmarkForm.dispatchEvent(new Event('submit'));
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