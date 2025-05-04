<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isEmployer()) {
  header('Location: /ADMSSYSTEM/logout.php');
  exit();
}

$database = new Database();
$db = $database->getConnect();
$job = new Jobs($db);
$application = new JobApplication($db);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BatStateU Career Hub - Job Postings</title>
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
            <a href="job_postings.php"><i class="fas fa-briefcase"></i> Job Postings</a>
          </li>
          <li>
            <a href="applications_management.php"><i class="fas fa-file-alt"></i> Applications</a>
          </li>
          <li>
            <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
          </li>
          <li>
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
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
          <input type="text" placeholder="Search job postings...">
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
        <div class="job-postings-header">
          <h1>Job Postings</h1>
          <button class="create-job-btn" onclick="window.location.href='create_job.php';">
            <i class="fas fa-plus"></i> Create New Job
          </button>
        </div>

        <!-- Job Postings Filter -->
        <div class="job-postings-filter">
          <div class="filter-options">
            <select class="sort-select">
              <option value="newest">Newest First</option>
              <option value="oldest">Oldest First</option>
              <option value="title-az">Title (A-Z)</option>
              <option value="title-za">Title (Z-A)</option>
              <option value="most-applications">Most Applications</option>
              <option value="least-applications">Least Applications</option>
            </select>
          </div>
        </div>

        <!-- Job Postings List -->
        <div class="job-postings-list">
          <?php
          $user_id = $_SESSION['id'];
          $results = $job->retrieveJobs($user_id);

          if (count($results) > 0) {
            foreach ($results as $row) {
              $jobId = $row['job_id'];

              // Call the procedure only once for each status
              $submittedData = $application->retrieveNoOfApplications('submitted', $jobId);
              $interviewData = $application->retrieveNoOfApplications('interview', $jobId);
              $shortlistedData = $application->retrieveNoOfApplications('shortlisted', $jobId);

              $applicationCount = $submittedData['application_count'] ?? 0;
              $interviewCount = $interviewData['application_count'] ?? 0;
              $shortlistedCount = $shortlistedData['application_count'] ?? 0;
          ?>
              <div class="job-posting-item"
                data-date="<?php echo $row['date_posted']; ?>"
                data-title="<?php echo htmlspecialchars($row['title']); ?>"
                data-applications="<?php echo $applicationCount; ?>">

                <div class="job-posting-main">
                  <div class="job-posting-info">
                    <h3 class="job-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                    <div class="job-meta">
                      <div class="meta-item"><i class="fas fa-map-marker-alt"></i><?php echo htmlspecialchars($row['location']); ?></div>
                      <div class="meta-item"><i class="fas fa-briefcase"></i><?php echo htmlspecialchars($row['type']); ?></div>
                      <div class="meta-item"><i class="fas fa-money-bill-wave"></i><?php echo htmlspecialchars($row['salary_min'] . ' - ' . $row['salary_max']); ?></div>
                      <div class="meta-item"><i class="fas fa-calendar"></i><?php echo date("F j, Y", strtotime($row['date_posted'])); ?></div>
                    </div>
                  </div>

                  <div class="job-posting-stats">
                    <div class="job-stat">
                      <span class="stat-value"><?php echo $applicationCount; ?></span>
                      <span class="stat-label">Applications</span>
                    </div>
                    <div class="job-stat">
                      <span class="stat-value"><?php echo $interviewCount; ?></span>
                      <span class="stat-label">Interviews</span>
                    </div>  
                    <div class="job-stat">
                      <span class="stat-value"><?php echo $shortlistedCount; ?></span>
                      <span class="stat-label">Shortlisted</span>
                    </div>
                  </div>

                  <div class="job-posting-actions">
                    <button class="action-btn view-btn"><i class="fas fa-eye"></i> View</button>
                    <button class="action-btn edit-btn" onclick="window.location.href='edit_job.php?job_id=<?php echo $jobId; ?>';"><i class="fas fa-edit"></i> Edit</button>
                    <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Delete</button>
                  </div>
                </div>
              </div>
          <?php
            }
          } else { 
            echo "<p>No applications found.</p>";
          }
          ?>
        </div>


        <!-- Pagination -->
        <div class="pagination">
          <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
          <button class="pagination-btn active">1</button>
          <button class="pagination-btn">2</button>
          <button class="pagination-btn">3</button>
          <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sortSelect = document.querySelector('.sort-select');
      const jobList = document.querySelector('.job-postings-list');

      sortSelect.addEventListener('change', function() {
        const items = Array.from(jobList.querySelectorAll('.job-posting-item'));

        const sorted = items.sort((a, b) => {
          switch (this.value) {
            case 'newest':
              return new Date(b.dataset.date) - new Date(a.dataset.date);
            case 'oldest':
              return new Date(a.dataset.date) - new Date(b.dataset.date);
            case 'title-az':
              return a.dataset.title.localeCompare(b.dataset.title);
            case 'title-za':
              return b.dataset.title.localeCompare(a.dataset.title);
            case 'most-applications':
              return b.dataset.applications - a.dataset.applications;
            case 'least-applications':
              return a.dataset.applications - b.dataset.applications;
            default:
              return 0;
          }
        });

        // Clear and re-append sorted items
        jobList.innerHTML = '';
        sorted.forEach(item => jobList.appendChild(item));
      });
    });


    // Delete job confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        Swal.fire({
          title: 'Delete Job Posting',
          text: 'Are you sure you want to delete this job posting? This action cannot be undone.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#c41e3a',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete it'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire(
              'Deleted!',
              'The job posting has been deleted.',
              'success'
            );
          }
        });
      });
    });
  </script>
</body>

</html>