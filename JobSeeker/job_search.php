<?php
session_start();
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
                    <input type="text" placeholder="Search for jobs...">
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

            <!-- Job Search Content -->
            <div class="dashboard-content">
                <h1>Find Jobs</h1>
                
                <div class="job-search-container">
                    <!-- Advanced Search Form -->
                    <div class="advanced-search">
                        <h3>Filter Jobs</h3>
                        <form class="filter-form">
                            <div class="form-group">
                                <label>Keywords</label>
                                <input type="text" placeholder="Job title, skills, or keywords">
                            </div>
                            
                            <div class="form-group">
                                <label>Location</label>
                                <select>
                                    <option value="">All Locations</option>
                                    <option value="batangas-city">Batangas City</option>
                                    <option value="lipa-city">Lipa City</option>
                                    <option value="tanauan">Tanauan</option>
                                    <option value="santo-tomas">Santo Tomas</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Job Type</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" value="full-time"> Full-time
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" value="part-time"> Part-time
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" value="contract"> Contract
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" value="internship"> Internship
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Industry</label>
                                <select>
                                    <option value="">All Industries</option>
                                    <option value="technology">Information Technology</option>
                                    <option value="healthcare">Healthcare</option>
                                    <option value="education">Education</option>
                                    <option value="finance">Finance</option>
                                    <option value="manufacturing">Manufacturing</option>
                                    <option value="retail">Retail</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Experience Level</label>
                                <select>
                                    <option value="">Any Experience</option>
                                    <option value="entry">Entry Level</option>
                                    <option value="mid">Mid Level</option>
                                    <option value="senior">Senior Level</option>
                                    <option value="executive">Executive</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Salary Range</label>
                                <select>
                                    <option value="">Any Salary</option>
                                    <option value="0-15000">Below ₱15,000</option>
                                    <option value="15000-25000">₱15,000 - ₱25,000</option>
                                    <option value="25000-40000">₱25,000 - ₱40,000</option>
                                    <option value="40000-60000">₱40,000 - ₱60,000</option>
                                    <option value="60000+">Above ₱60,000</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Date Posted</label>
                                <select>
                                    <option value="">Any Time</option>
                                    <option value="today">Today</option>
                                    <option value="3days">Last 3 Days</option>
                                    <option value="week">Last Week</option>
                                    <option value="month">Last Month</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="filter-btn">Apply Filters</button>
                            <button type="reset" class="reset-btn">Reset Filters</button>
                        </form>
                    </div>
                    
                    <!-- Job Listings -->
                    <div class="job-listings">
                        <div class="job-listings-header">
                            <h3>120 Jobs Found</h3>
                            <div class="sort-options">
                                <label>Sort by:</label>
                                <select>
                                    <option value="relevance">Relevance</option>
                                    <option value="date">Date Posted</option>
                                    <option value="salary-high">Salary (High to Low)</option>
                                    <option value="salary-low">Salary (Low to High)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="jobs-list">
                            <!-- Job Card 1 -->
                            <div class="job-card">
                                <div class="job-header">
                                    <div class="company-logo"></div>
                                    <div class="job-title-container">
                                        <h3>Software Developer</h3>
                                        <p class="company-name">Tech Solutions Inc.</p>
                                    </div>
                                    <button class="save-job"><i class="far fa-bookmark"></i></button>
                                </div>
                                <div class="job-details">
                                    <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                                    <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                    <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱30,000 - ₱45,000</div>
                                </div>
                                <div class="job-description">
                                    <p>We are looking for a skilled software developer to join our team. The ideal candidate should have experience in web development...</p>
                                </div>
                                <div class="job-actions">
                                    <button class="apply-btn">Apply Now</button>
                                    <button class="view-btn">View Details</button>
                                </div>
                            </div>
                            
                            <!-- Job Card 2 -->
                            <div class="job-card">
                                <div class="job-header">
                                    <div class="company-logo"></div>
                                    <div class="job-title-container">
                                        <h3>Marketing Specialist</h3>
                                        <p class="company-name">Global Marketing PH</p>
                                    </div>
                                    <button class="save-job saved"><i class="fas fa-bookmark"></i></button>
                                </div>
                                <div class="job-details">
                                    <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Lipa City</div>
                                    <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                    <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱25,000 - ₱35,000</div>
                                </div>
                                <div class="job-description">
                                    <p>We are seeking a creative and analytical Marketing Specialist to develop and implement marketing strategies that increase our brand awareness...</p>
                                </div>
                                <div class="job-actions">
                                    <button class="apply-btn">Apply Now</button>
                                    <button class="view-btn">View Details</button>
                                </div>
                            </div>
                            
                            <!-- Job Card 3 -->
                            <div class="job-card">
                                <div class="job-header">
                                    <div class="company-logo"></div>
                                    <div class="job-title-container">
                                        <h3>Customer Service Representative</h3>
                                        <p class="company-name">Support Solutions</p>
                                    </div>
                                    <button class="save-job"><i class="far fa-bookmark"></i></button>
                                </div>
                                <div class="job-details">
                                    <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                                    <div class="job-detail"><i class="fas fa-briefcase"></i> Part-time</div>
                                    <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱18,000 - ₱22,000</div>
                                </div>
                                <div class="job-description">
                                    <p>We are looking for a Customer Service Representative to join our team. The ideal candidate will have excellent communication skills...</p>
                                </div>
                                <div class="job-actions">
                                    <button class="apply-btn">Apply Now</button>
                                    <button class="view-btn">View Details</button>
                                </div>
                            </div>
                            
                            <!-- Job Card 4 -->
                            <div class="job-card">
                                <div class="job-header">
                                    <div class="company-logo"></div>
                                    <div class="job-title-container">
                                        <h3>Graphic Designer</h3>
                                        <p class="company-name">Creative Designs Co.</p>
                                    </div>
                                    <button class="save-job"><i class="far fa-bookmark"></i></button>
                                </div>
                                <div class="job-details">
                                    <div class="job-detail"><i class="fas fa-map-marker-alt"></i> Santo Tomas</div>
                                    <div class="job-detail"><i class="fas fa-briefcase"></i> Full-time</div>
                                    <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱22,000 - ₱30,000</div>
                                </div>
                                <div class="job-description">
                                    <p>We are looking for a creative Graphic Designer to join our team. The ideal candidate should have experience with Adobe Creative Suite...</p>
                                </div>
                                <div class="job-actions">
                                    <button class="apply-btn">Apply Now</button>
                                    <button class="view-btn">View Details</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="pagination">
                            <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
                            <button class="pagination-btn active">1</button>
                            <button class="pagination-btn">2</button>
                            <button class="pagination-btn">3</button>
                            <button class="pagination-btn">4</button>
                            <button class="pagination-btn">5</button>
                            <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>