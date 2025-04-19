<?php
session_start();
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
                    <li class="active">                        <a href="saved_jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a>
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
                        <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; ?></span>
                    </div>
                </div>
            </header>

            <!-- Saved Jobs Content -->
            <div class="dashboard-content">
                <h1>Saved Jobs</h1>
                
                <!-- Saved Jobs Filter -->
                <div class="saved-jobs-header">
                    <div class="saved-jobs-count">
                        <h3>12 Saved Jobs</h3>
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
                    <!-- Saved Job Card 1 -->
                    <div class="saved-job-card">
                        <div class="saved-job-header">
                            <div class="company-logo"></div>
                            <button class="remove-saved-job"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="saved-job-content">
                            <h3 class="job-title">Software Developer</h3>
                            <p class="company-name">Tech Solutions Inc.</p>
                            
                            <div class="job-details">
                                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                                <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱30,000 - ₱45,000</div>
                            </div>
                            
                            <div class="saved-date">
                                <i class="fas fa-clock"></i> Saved 2 days ago
                            </div>
                            
                            <div class="job-actions">
                                <button class="apply-btn">Apply Now</button>
                                <button class="view-btn">View Details</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Saved Job Card 2 -->
                    <div class="saved-job-card">
                        <div class="saved-job-header">
                            <div class="company-logo"></div>
                            <button class="remove-saved-job"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="saved-job-content">
                            <h3 class="job-title">Marketing Specialist</h3>
                            <p class="company-name">Global Marketing PH</p>
                            
                            <div class="job-details">
                                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Lipa City</div>
                                <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱25,000 - ₱35,000</div>
                            </div>
                            
                            <div class="saved-date">
                                <i class="fas fa-clock"></i> Saved 3 days ago
                            </div>
                            
                            <div class="job-actions">
                                <button class="apply-btn">Apply Now</button>
                                <button class="view-btn">View Details</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Saved Job Card 3 -->
                    <div class="saved-job-card">
                        <div class="saved-job-header">
                            <div class="company-logo"></div>
                            <button class="remove-saved-job"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="saved-job-content">
                            <h3 class="job-title">Graphic Designer</h3>
                            <p class="company-name">Creative Designs Co.</p>
                            
                            <div class="job-details">
                                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Santo Tomas</div>
                                <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱22,000 - ₱30,000</div>
                            </div>
                            
                            <div class="saved-date">
                                <i class="fas fa-clock"></i> Saved 5 days ago
                            </div>
                            
                            <div class="job-actions">
                                <button class="apply-btn">Apply Now</button>
                                <button class="view-btn">View Details</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Saved Job Card 4 -->
                    <div class="saved-job-card">
                        <div class="saved-job-header">
                            <div class="company-logo"></div>
                            <button class="remove-saved-job"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="saved-job-content">
                            <h3 class="job-title">Customer Service Representative</h3>
                            <p class="company-name">Support Solutions</p>
                            
                            <div class="job-details">
                                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                                <div class="job-detail"><i class="fas fa-briefcase"></i> Part-time</div>
                                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱18,000 - ₱22,000</div>
                            </div>
                            
                            <div class="saved-date">
                                <i class="fas fa-clock"></i> Saved 1 week ago
                            </div>
                            
                            <div class="job-actions">
                                <button class="apply-btn">Apply Now</button>
                                <button class="view-btn">View Details</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Saved Job Card 5 -->
                    <div class="saved-job-card">
                        <div class="saved-job-header">
                            <div class="company-logo"></div>
                            <button class="remove-saved-job"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="saved-job-content">
                            <h3 class="job-title">Data Analyst</h3>
                            <p class="company-name">Tech Insights Inc.</p>
                            
                            <div class="job-details">
                                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Tanauan</div>
                                <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱28,000 - ₱40,000</div>
                            </div>
                            
                            <div class="saved-date">
                                <i class="fas fa-clock"></i> Saved 1 week ago
                            </div>
                            
                            <div class="job-actions">
                                <button class="apply-btn">Apply Now</button>
                                <button class="view-btn">View Details</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Saved Job Card 6 -->
                    <div class="saved-job-card">
                        <div class="saved-job-header">
                            <div class="company-logo"></div>
                            <button class="remove-saved-job"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="saved-job-content">
                            <h3 class="job-title">HR Assistant</h3>
                            <p class="company-name">People First Inc.</p>
                            
                            <div class="job-details">
                                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                                <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱20,000 - ₱25,000</div>
                            </div>
                            
                            <div class="saved-date">
                                <i class="fas fa-clock"></i> Saved 2 weeks ago
                            </div>
                            
                            <div class="job-actions">
                                <button class="apply-btn">Apply Now</button>
                                <button class="view-btn">View Details</button>
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
        // Toggle view (grid/list)
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-btn');
            const savedJobsContainer = document.querySelector('.saved-jobs-grid');
            
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    viewButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Change view
                    const view = this.getAttribute('data-view');
                    if (view === 'grid') {
                        savedJobsContainer.classList.remove('list-view');
                        savedJobsContainer.classList.add('grid-view');
                    } else {
                        savedJobsContainer.classList.remove('grid-view');
                        savedJobsContainer.classList.add('list-view');
                    }
                });
            });
            
            // Remove saved job
            const removeButtons = document.querySelectorAll('.remove-saved-job');
            
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobCard = this.closest('.saved-job-card');
                    
                    // Confirm before removing
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
                            // Remove the job card with animation
                            jobCard.style.opacity = '0';
                            setTimeout(() => {
                                jobCard.remove();
                            }, 300);
                            
                            // Show success message
                            Swal.fire(
                                'Removed!',
                                'The job has been removed from your saved list.',
                                'success'
                            );
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>