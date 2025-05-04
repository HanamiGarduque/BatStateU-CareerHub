<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isEmployer()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}
$updateStatus = null;

$emp_id = $_SESSION['id'] ?? null;

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $database = new Database();
    $db = $database->getConnect();
    $job = new Jobs($db);
    $jobDetails = $job->getJobById($job_id);

    if ($jobDetails) {
        $title = $jobDetails['title'];
        $job_category = $jobDetails['job_category'];
        $company_name = $jobDetails['company_name'];
        $location = $jobDetails['location'];
        $type = $jobDetails['type'];
        $salary_min = $jobDetails['salary_min'];
        $salary_max = $jobDetails['salary_max'];
        $description = $jobDetails['description'];
        $responsibilities = $jobDetails['responsibilities'];
        $requirements = $jobDetails['requirements'];
        $benefits_perks = $jobDetails['benefits_perks'];
    } else {
        header('Location: job_postings.php');
        exit();
    }
} else {
    header('Location: job_postings.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $job_category = $_POST['job_category'];
    $company_name = $_POST['company_name'];
    $location = $_POST['location'];
    $type = $_POST['type'];
    $salary_min = $_POST['salary_min'];
    $salary_max = $_POST['salary_max'];
    $description = $_POST['description'];
    $responsibilities = $_POST['responsibilities'];
    $requirements = $_POST['requirements'];
    $benefits_perks = $_POST['benefits_perks'];

    $job->user_id = $emp_id; 
    $job->title = $title;
    $job->job_category = $job_category;
    $job->company_name = $company_name;
    $job->location = $location;
    $job->type = $type;
    $job->salary_min = $salary_min;
    $job->salary_max = $salary_max;
    $job->description = $description;
    $job->responsibilities = $responsibilities;
    $job->requirements = $requirements;
    $job->benefits_perks = $benefits_perks;

    if ($job->updateJobById($job_id)) {
        $updateStatus = 'success';
    } else {
        $updateStatus = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - Edit Job Post</title>
    <link rel="stylesheet" href="../Layouts/employer.css">
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
                        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="employer_profile.php"><i class="fas fa-user-tie"></i> Employer Profile</a>
                    </li>
                    <li class="active">
                        <a href="job_postings.php"><i class="fas fa-briefcase"></i> Job Postings</a>
                    </li>
                    <li>
                        <a href="applications_management.php"><i class="fas fa-file-alt"></i> Applications</a>
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
                     
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </div>
                    <div class="user-profile">
                        <img src="../Layouts/user_icon.png" alt="Profile Picture">
                        <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin'; ?></span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="create-job-header">
                    <h1>Update Job Post</h1>
                </div>

                <div class="create-job-container">
                    <form id="update-job-form" class="create-job-form" method="POST" action="">
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h2 class="section-title">Basic Information</h2>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="job-title">Job Title <span class="required">*</span></label>
                                    <input type="text" id="job-title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="company_name">Company Name <span class="required">*</span></label>
                                    <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="job-type">Job Type <span class="required">*</span></label>
                                    <select id="job-type" name="type" required>
                                        <option value="Full-time" <?php echo $type === 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
                                        <option value="Part-time" <?php echo $type === 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
                                        <option value="Contract" <?php echo $type === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                                        <option value="Internship" <?php echo $type === 'Internship' ? 'selected' : ''; ?>>Internship</option>
                                        <option value="Temporary" <?php echo $type === 'Temporary' ? 'selected' : ''; ?>>Temporary</option>
                                        <option value="Remote" <?php echo $type === 'Remote' ? 'selected' : ''; ?>>Remote</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="job-category">Job Category <span class="required">*</span></label>
                                    <select id="job-category" name="job_category" required>
                                        <option value="Technology" <?php echo $job_category === 'Technology' ? 'selected' : ''; ?>>Information Technology</option>
                                        <option value="Marketing" <?php echo $job_category === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                        <option value="Design" <?php echo $job_category === 'Design' ? 'selected' : ''; ?>>Design</option>
                                        <option value="Finance" <?php echo $job_category === 'Finance' ? 'selected' : ''; ?>>Finance</option>
                                        <option value="Healthcare" <?php echo $job_category === 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                        <option value="Education" <?php echo $job_category === 'Education' ? 'selected' : ''; ?>>Education</option>
                                        <option value="Engineering" <?php echo $job_category === 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                                        <option value="Customer-service" <?php echo $job_category === 'Customer-service' ? 'selected' : ''; ?>>Customer Service</option>
                                        <option value="Administrative" <?php echo $job_category === 'Administrative' ? 'selected' : ''; ?>>Administrative</option>
                                        <option value="Other" <?php echo $job_category === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="job-location">Location <span class="required">*</span></label>
                                    <input type="text" id="job-location" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="salary-min">Minimum Salary (₱)</label>
                                    <input type="number" id="salary-min" name="salary_min" value="<?php echo htmlspecialchars($salary_min); ?>" placeholder="e.g., 25000">
                                </div>

                                <div class="form-group">
                                    <label for="salary-max">Maximum Salary (₱)</label>
                                    <input type="number" id="salary-max" name="salary_max" value="<?php echo htmlspecialchars($salary_max); ?>" placeholder="e.g., 35000">
                                </div>
                            </div>
                        </div>

                        <!-- Job Details Section -->
                        <div class="form-section">
                            <h2 class="section-title">Job Details</h2>

                            <div class="form-group">
                                <label for="job-description">Job Description <span class="required">*</span></label>
                                <textarea id="job-description" name="description" rows="6" required><?php echo htmlspecialchars($description); ?></textarea>
                                <p class="form-help-text">Provide a detailed description of the job, including the role's purpose and how it fits within the company.</p>
                            </div>

                            <div class="form-group">
                                <label for="job-responsibilities">Responsibilities <span class="required">*</span></label>
                                <textarea id="job-responsibilities" name="responsibilities" rows="6" required><?php echo htmlspecialchars($responsibilities); ?></textarea>
                                <p class="form-help-text">List the key responsibilities and duties of the role.</p>
                            </div>

                            <div class="form-group">
                                <label for="job-requirements">Requirements <span class="required">*</span></label>
                                <textarea id="job-requirements" name="requirements" rows="6" required><?php echo htmlspecialchars($requirements); ?></textarea>
                                <p class="form-help-text">Specify the qualifications, skills, and experience required for the position.</p>
                            </div>

                            <div class="form-group">
                                <label for="job-benefits">Benefits & Perks</label>
                                <textarea id="job-benefits" name="benefits_perks" rows="4"><?php echo htmlspecialchars($benefits_perks); ?></textarea>
                                <p class="form-help-text">Highlight the benefits and perks offered with this position.</p>
                            </div>
                        </div>

                        <div class="create-job-actions">
                            <button class="create-job-btn"><i class="fas fa-paper-plane"></i> Update Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php if ($updateStatus === 'success'): ?>
    <script>
      Swal.fire({
        title: 'Job Updated',
        text: 'Your job posting has been updated successfully.',
        icon: 'success',
        confirmButtonColor: '#c41e3a'
      }).then(() => {
        window.location.href = 'job_postings.php';
      });
    </script>
    <?php elseif ($updateStatus === 'error'): ?>
    <script>
      Swal.fire({
        title: 'Error!',
        text: 'Job update error. Please try again.',
        icon: 'error',
        confirmButtonText: 'Try Again',
        background: '#fff',
        backdrop: true,
      }).then(() => {
        window.location.href = 'job_postings.php';
      });
    </script>
    <?php endif; ?>
</body>

</html>