<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - My Applications</title>
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
                    <li class="active">
                        <a href="homepage.php"><i class="fas fa-search"></i> Find Jobs</a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                    </li>
                    <li>
                        <a href="applications.php"><i class="fas fa-file-alt"></i> My Applications</a>
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
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search applications...">
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="../placeholder.jpg" alt="Profile Picture">
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
                    <!-- Application Item 1 -->
                    <div class="application-item">
                        <div class="application-main">
                            <div class="company-logo"></div>
                            <div class="application-details">
                                <h3 class="job-title">Web Developer</h3>
                                <p class="company-name">Digital Creations Co.</p>
                                <div class="application-meta">
                                    <span class="meta-item"><i class="fas fa-calendar"></i> Applied: May 15, 2023</span>
                                    <span class="meta-item"><i class="fas fa-map-marker-alt"></i> Batangas City</span>
                                    <span class="meta-item"><i class="fas fa-briefcase"></i> Full-time</span>
                                </div>
                            </div>
                            <div class="application-status">
                                <div class="application-status-badge pending">Pending</div>
                                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        
                        <div class="application-details-expanded">
                            <div class="timeline">
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-paper-plane"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Submitted</h4>
                                        <p>May 15, 2023 at 10:30 AM</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon"><i class="fas fa-eye"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Viewed</h4>
                                        <p>Pending</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon"><i class="fas fa-phone"></i></div>
                                    <div class="timeline-content">
                                        <h4>Interview</h4>
                                        <p>Pending</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="timeline-content">
                                        <h4>Decision</h4>
                                        <p>Pending</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="application-actions">
                                <button class="view-job-btn"><i class="fas fa-external-link-alt"></i> View Job</button>
                                <button class="withdraw-btn"><i class="fas fa-times"></i> Withdraw Application</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Application Item 2 -->
                    <div class="application-item">
                        <div class="application-main">
                            <div class="company-logo"></div>
                            <div class="application-details">
                                <h3 class="job-title">UI/UX Designer</h3>
                                <p class="company-name">Creative Solutions</p>
                                <div class="application-meta">
                                    <span class="meta-item"><i class="fas fa-calendar"></i> Applied: May 10, 2023</span>
                                    <span class="meta-item"><i class="fas fa-map-marker-alt"></i> Lipa City</span>
                                    <span class="meta-item"><i class="fas fa-briefcase"></i> Full-time</span>
                                </div>
                            </div>
                            <div class="application-status">
                                <div class="application-status-badge interview">Interview</div>
                                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        
                        <div class="application-details-expanded">
                            <div class="timeline">
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-paper-plane"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Submitted</h4>
                                        <p>May 10, 2023 at 2:15 PM</p>
                                    </div>
                                </div>
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-eye"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Viewed</h4>
                                        <p>May 12, 2023 at 11:45 AM</p>
                                    </div>
                                </div>
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-phone"></i></div>
                                    <div class="timeline-content">
                                        <h4>Interview Scheduled</h4>
                                        <p>May 20, 2023 at 10:00 AM</p>
                                        <div class="interview-details">
                                            <p><strong>Type:</strong> Video Call (Zoom)</p>
                                            <p><strong>Contact:</strong> Maria Santos, HR Manager</p>
                                            <p><strong>Notes:</strong> Please prepare a 10-minute presentation of your portfolio.</p>
                                            <button class="add-calendar-btn"><i class="fas fa-calendar-plus"></i> Add to Calendar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="timeline-content">
                                        <h4>Decision</h4>
                                        <p>Pending</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="application-actions">
                                <button class="view-job-btn"><i class="fas fa-external-link-alt"></i> View Job</button>
                                <button class="withdraw-btn"><i class="fas fa-times"></i> Withdraw Application</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Application Item 3 -->
                    <div class="application-item">
                        <div class="application-main">
                            <div class="company-logo"></div>
                            <div class="application-details">
                                <h3 class="job-title">Marketing Assistant</h3>
                                <p class="company-name">Global Marketing PH</p>
                                <div class="application-meta">
                                    <span class="meta-item"><i class="fas fa-calendar"></i> Applied: May 5, 2023</span>
                                    <span class="meta-item"><i class="fas fa-map-marker-alt"></i> Batangas City</span>
                                    <span class="meta-item"><i class="fas fa-briefcase"></i> Part-time</span>
                                </div>
                            </div>
                            <div class="application-status">
                                <div class="application-status-badge accepted">Accepted</div>
                                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        
                        <div class="application-details-expanded">
                            <div class="timeline">
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-paper-plane"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Submitted</h4>
                                        <p>May 5, 2023 at 9:20 AM</p>
                                    </div>
                                </div>
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-eye"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Viewed</h4>
                                        <p>May 6, 2023 at 3:30 PM</p>
                                    </div>
                                </div>
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-phone"></i></div>
                                    <div class="timeline-content">
                                        <h4>Interview Completed</h4>
                                        <p>May 10, 2023 at 2:00 PM</p>
                                    </div>
                                </div>
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="timeline-content">
                                        <h4>Offer Extended</h4>
                                        <p>May 15, 2023 at 11:15 AM</p>
                                        <div class="offer-details">
                                            <p><strong>Position:</strong> Marketing Assistant</p>
                                            <p><strong>Salary:</strong> ₱22,000 per month</p>
                                            <p><strong>Start Date:</strong> June 1, 2023</p>
                                            <div class="offer-actions">
                                                <button class="accept-offer-btn"><i class="fas fa-check"></i> Accept Offer</button>
                                                <button class="decline-offer-btn"><i class="fas fa-times"></i> Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="application-actions">
                                <button class="view-job-btn"><i class="fas fa-external-link-alt"></i> View Job</button>
                                <button class="contact-employer-btn"><i class="fas fa-envelope"></i> Contact Employer</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Application Item 4 -->
                    <div class="application-item">
                        <div class="application-main">
                            <div class="company-logo"></div>
                            <div class="application-details">
                                <h3 class="job-title">Data Analyst</h3>
                                <p class="company-name">Tech Insights Inc.</p>
                                <div class="application-meta">
                                    <span class="meta-item"><i class="fas fa-calendar"></i> Applied: April 28, 2023</span>
                                    <span class="meta-item"><i class="fas fa-map-marker-alt"></i> Tanauan</span>
                                    <span class="meta-item"><i class="fas fa-briefcase"></i> Full-time</span>
                                </div>
                            </div>
                            <div class="application-status">
                                <div class="application-status-badge rejected">Rejected</div>
                                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        
                        <div class="application-details-expanded">
                            <div class="timeline">
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-paper-plane"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Submitted</h4>
                                        <p>April 28, 2023 at 4:45 PM</p>
                                    </div>
                                </div>
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-eye"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Viewed</h4>
                                        <p>May 2, 2023 at 10:20 AM</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon"><i class="fas fa-phone"></i></div>
                                    <div class="timeline-content">
                                        <h4>Interview</h4>
                                        <p>Not Selected</p>
                                    </div>
                                </div>
                                <div class="timeline-item active">
                                    <div class="timeline-icon"><i class="fas fa-times-circle"></i></div>
                                    <div class="timeline-content">
                                        <h4>Application Rejected</h4>
                                        <p>May 5, 2023 at 2:30 PM</p>
                                        <div class="rejection-details">
                                            <p><strong>Feedback:</strong> Thank you for your interest in the Data Analyst position. While we were impressed with your qualifications, we have decided to move forward with candidates whose experience more closely aligns with our current needs.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="application-actions">
                                <button class="view-job-btn"><i class="fas fa-external-link-alt"></i> View Job</button>
                                <button class="view-similar-jobs-btn"><i class="fas fa-search"></i> View Similar Jobs</button>
                            </div>
                        </div>
                    </div>
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
        // Toggle application details
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-details-btn');
            
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
        });
    </script>
</body>

</html>