<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - Create Job Posting</title>
    <link rel="stylesheet" href="../Layouts/employer.css">
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
                        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="employer_profile.php"><i class="fas fa-user-tie"></i> Employer Profile</a>
                    </li>
                    <li class="active">
                        <a href="job-postings.php"><i class="fas fa-briefcase"></i> Job Postings</a>
                    </li>
                    <li>
                        <a href="applications.php"><i class="fas fa-file-alt"></i> Applications</a>
                    </li>
                    <li>
                        <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                    </li>
                    <li>
                        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
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
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </div>
                    <div class="user-profile">
                        <img src="../placeholder.jpg" alt="Profile Picture">
                        <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin'; ?></span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="create-job-header">
                    <h1>Create New Job Posting</h1>
                </div>

                <div class="create-job-container">
                    <form id="create-job-form" class="create-job-form">
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h2 class="section-title">Basic Information</h2>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="job-title">Job Title <span class="required">*</span></label>
                                    <input type="text" id="job-title" name="job-title" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="job-type">Job Type <span class="required">*</span></label>
                                    <select id="job-type" name="job-type" required>
                                        <option value="full-time">Full-time</option>
                                        <option value="part-time">Part-time</option>
                                        <option value="contract">Contract</option>
                                        <option value="internship">Internship</option>
                                        <option value="temporary">Temporary</option>
                                        <option value="remote">Remote</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="job-category">Job Category <span class="required">*</span></label>
                                    <select id="job-category" name="job-category" required>
                                        <option value="technology">Information Technology</option>
                                        <option value="marketing">Marketing</option>
                                        <option value="design">Design</option>
                                        <option value="finance">Finance</option>
                                        <option value="healthcare">Healthcare</option>
                                        <option value="education">Education</option>
                                        <option value="engineering">Engineering</option>
                                        <option value="customer-service">Customer Service</option>
                                        <option value="administrative">Administrative</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="job-location">Location <span class="required">*</span></label>
                                    <input type="text" id="job-location" name="job-location" required>
                                </div>


                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="salary-min">Minimum Salary (₱)</label>
                                        <input type="number" id="salary-min" name="salary-min" placeholder="e.g., 25000">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="salary-max">Maximum Salary (₱)</label>
                                    <input type="number" id="salary-max" name="salary-max" placeholder="e.g., 35000">
                                </div>


                            </div>

                            <!-- Job Details Section -->
                            <div class="form-section">
                                <h2 class="section-title">Job Details</h2>

                                <div class="form-group">
                                    <label for="job-description">Job Description <span class="required">*</span></label>
                                    
                                    <textarea id="job-description" name="job-description" rows="6" required></textarea>
                                    <p class="form-help-text">Provide a detailed description of the job, including the role's purpose and how it fits within the company.</p>
                                </div>

                                <div class="form-group">
                                    <label for="job-responsibilities">Responsibilities <span class="required">*</span></label<>
                                    <textarea id="job-responsibilities" name="job-responsibilities" rows="6" required></textarea>
                                    <p class="form-help-text">List the key responsibilities and duties of the role.</p>
                                </div>

                                <div class="form-group">
                                    <label for="job-requirements">Requirements <span class="required">*</span></label>                                
                                    <textarea id="job-requirements" name="job-requirements" rows="6" required></textarea>
                                    <p class="form-help-text">Specify the qualifications, skills, and experience required for the position.</p>
                                </div>

                                <div class="form-group">
                                    <label for="job-benefits">Benefits & Perks</label>
                                    <textarea id="job-benefits" name="job-benefits" rows="4"></textarea>
                                    <p class="form-help-text">Highlight the benefits and perks offered with this position.</p>
                                </div>
                            </div>

                            <div class="create-job-actions">
                        <button class="create-job-btn"><i class="fas fa-paper-plane"></i> Publish Job</button>
                    </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

</body>

</html>