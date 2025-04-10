<?php
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

$database = new Database();
$db = $database->getConnect();
$jobs = new Jobs($db);
$stmt = $jobs->retrieveJobs();
$num = $stmt->rowCount();
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
            <header class="dashboard-header">.
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
                            <?php
                            $stmt = $jobs->retrieveJobs();
                            if ($num > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);

                                    // Get user ID (from session ideally)
                                    $user_id = 1;

                                    // Instantiate bookmark handler
                                    $bookmark = new Bookmarks($db);

                                    // Check if the current job is bookmarked
                                    $isSaved = $bookmark->isBookmarked($user_id, $job_id);
                                    $savedClass = $isSaved ? 'saved' : '';

                                    echo <<<HTML
                                        <div class="job-card">
                                            <div class="job-header">
                                                <div class="company-logo"></div>
                                                <div class="job-title-container">
                                                    <h3>{$title}</h3>
                                                    <p class="company-name">{$company_name}</p>
                                                </div>
                                                <button class="save-job {$savedClass}" data-job-id="{$job_id}"><i class="fas fa-bookmark"></i></button>
                                            </div>
                                            <div class="job-details">
                                                <div class="job-detail"><i class="fas fa-map-marker-alt"></i> {$location}</div>
                                                <div class="job-detail"><i class="fas fa-briefcase"></i> {$type}</div>
                                                <div class="job-detail"><i class="fas fa-money-bill-wave"></i> ₱{$salary_min} - ₱{$salary_max}</div>
                                            </div>
                                            <div class="job-description">
                                                <p>{$description}</p>
                                            </div>
                                            <div class="job-description">
                                                <p>{$job_id}</p>
                                            </div>
                                            <div class="job-actions">
                                                <button class="apply-btn">Apply Now</button>
                                                <button class="view-btn">View Details</button>
                                            </div>
                                        </div>
                                    HTML;
                                }
                            } else {
                                echo "<p>No jobs found.</p>";
                            }

                            ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.querySelectorAll('.save-job').forEach(button => {
                                        button.addEventListener('click', function() {
                                            const jobId = this.getAttribute('data-job-id');

                                            fetch('bookmark_job.php', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/x-www-form-urlencoded',
                                                    },
                                                    body: 'job_id=' + jobId
                                                })
                                                .then(response => response.text())
                                                .then(data => {
                                                    if (data === 'saved') {
                                                        this.classList.add('saved');
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Job bookmarked!',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                        });

                                                    } else if (data === 'unsaved') {
                                                        this.classList.remove('saved');
                                                        alert('Bookmark removed!');
                                                    } else {
                                                        alert('Something went wrong.');
                                                    }
                                                });
                                        });
                                    });
                                });
                            </script>