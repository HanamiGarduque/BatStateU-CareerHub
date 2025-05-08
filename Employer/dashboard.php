<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isEmployer()) {
  header('Location: /ADMSSYSTEM/login.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BatStateU Career Hub - Employer Dashboard</title>
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
            <img src="../Layouts/emp_icon.png" alt="Profile Picture">
            <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Employer'; ?></span>
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="dashboard-content">
        <h1>Employer Dashboard</h1>

        <!-- Stats Container -->
        <div class="stats-container">
          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <?php
            $database = new Database();
            $db = $database->getConnect();

            $emp_id = $_SESSION['id'];

            $applications = new JobApplication($db);
            $jobs = new Jobs($db);
            $totalApplications = $applications->getTotalJobApplications($emp_id);
            $totalJobPostings = $jobs->countAllJobsPerEmp($emp_id);
            $totalPositionsFilled = $applications->getTotalPositionsFilled($emp_id);

            ?>

            <div class="stat-info">
              <h3><?php echo htmlspecialchars((string)$totalJobPostings); ?></h3>
              <p>Active Job Postings</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
              <h3><?php echo htmlspecialchars((string)$totalApplications); ?></h3>
              <p>Total Applications</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-info">
              <h3><?php echo htmlspecialchars((string)$totalApplications); ?></h3>
              <p>New Candidates</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-info">
            <h3><?php echo htmlspecialchars((string)$totalPositionsFilled); ?></h3>
            <p>Positions Filled</p>
            </div>
          </div>
        </div>

        <!-- Recent Applications -->
        <div class="dashboard-section">
          <div class="section-header">
            <h2>Recent Applications</h2>
            <a href="applications_management.php" class="view-all">View All</a>
          </div>
          
          <div class="applications-container">
            <?php
            $stmt = $applications->retrieveRecentApplications($emp_id);
            foreach ($stmt as $row) {
              extract($row); // make sure the row has the correct keys
              $formatted_date = date("F j, Y", strtotime($row['created_at']));

              echo <<<HTML
              <div class="application-card">
                <div class="application-header">
                  <div class="company-logo">
                    <img src="../Layouts/work_icon.png" alt="Job Icon">
                  </div>
                  <div class="application-title-container">
                    <h3>{$job_title}</h3>
                    <p class="company-name">{$company_name}</p>
                  </div>
                </div>

                <div class="application-details">
                  <div class="application-detail">
                    <i class="fas fa-user"></i> {$first_name} {$last_name}
                  </div>
                  <div class="application-detail">
                    <i class="fas fa-calendar"></i> Applied on {$formatted_date}
                  </div>
                  <div class="application-detail">
                    <i class="fas fa-tag"></i> <span class="application-status-badge pending">{$status}</span>
                  </div>
                </div>
                <button class="view-application-btn" onclick="window.location.href='applicants_list.php?job_id={$job_id}';">View Application</button>

              </div>
              HTML;
            }
            ?>
          </div>
        </div>

        <!-- Recent Job Postings -->
        <div class="dashboard-section">
          <div class="section-header">
            <h2>Recent Job Postings</h2>
            <a href="job_postings.php" class="view-all">View All</a>
          </div>

          <div class="jobs-container">
            <?php
            $stmt = $jobs->retrieveRecentJobs($emp_id);
            foreach ($stmt as $row) {
              extract($row);
              $jobId = $row['job_id'];
              $formatted_date = date("F j, Y", strtotime($row['date_posted']));

              echo <<<HTML
              <div class="job-card">
                <div class="job-header">
                  <div class="company-logo">
                    <img src="../Layouts/work_icon.png" alt="Job Icon">
                  </div>
                  <div class="job-title-container">
                    <h3>{$title}</h3>
                    <p class="company-name">{$company_name}</p>
                  </div>
                </div>

                <div class="job-details">
                  <div class="job-detail">
                    <i class="fas fa-map-marker-alt"></i> {$location}
                  </div>
                  <div class="job-detail">
                    <i class="fas fa-briefcase"></i> {$type}
                  </div>
                  <div class="job-detail">
                    <i class="fas fa-money-bill-wave"></i> ₱{$salary_min} - ₱{$salary_max}
                  </div>
                  <div class="job-detail">
                    <i class="fas fa-calendar"></i> Posted {$formatted_date}
                  </div>
                </div>

                <div class="job-description">
                  <p>{$description}</p>
                </div>

                <div class="job-actions">
                  <button class="view-btn" onclick="window.location.href='edit_job.php?job_id={$jobId}';">Edit Job</button>
                  <button class="view-application-btn" onclick="window.location.href='applicants_list.php?job_id={$job_id}';">View Application</button>
                  </div>
              </div>            
              HTML;
            }
            ?>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>