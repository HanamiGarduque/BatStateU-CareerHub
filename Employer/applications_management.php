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

$application = new JobApplication($db);
$employer = new Employers($db);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BatStateU Career Hub - Applications Management</title>
  <link rel="stylesheet" href="../Layouts/employer.css">
  <link rel="stylesheet" href="../Layouts/applications_management.css">
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
          <li>
            <a href="job_postings.php"><i class="fas fa-briefcase"></i> Job Postings</a>
          </li>
          <li class="active">
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
            <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin'; ?></span>
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="dashboard-content">
        <div class="content-header">
          <h1><i class="fas fa-file-alt"></i> Applications Management</h1>
        </div>

        <!-- Applications Filter -->
        <div class="applications-filter">
          <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">All Applications</button>
            <button class="filter-tab" data-filter="under review">Under Review</button>
            <button class="filter-tab" data-filter="shortlisted">Shortlisted</button>
            <button class="filter-tab" data-filter="interview">Interview</button>
            <button class="filter-tab" data-filter="accepted">Accepted</button>
            <button class="filter-tab" data-filter="Rejected">Rejected</button>
          </div>
        </div>

        <!-- Applications List -->
        <div class="applications-list">
          <?php
          $emp_id = $_SESSION['id'];
          $results = $application->retrieveApplicationsByEmpID($emp_id);
          $num = count($results);

          if ($num > 0) {
            foreach ($results as $row) {
              $jobseeker_id = $row['user_id'];
              $application_id = $row['id'];
          ?>
              <div class="application-item">
                <div class="application-main">
                  <div class="candidate-info">
                    <div class="candidate-avatar">
                      <img src="../Layouts/user_icon.png" alt="Applicant Photo">
                    </div>
                    <div class="candidate-details">
                      <h3 class="candidate-name"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h3>
                      <p class="candidate-title"><?php echo htmlspecialchars($row['title']); ?></p>
                      <div class="application-meta">
                        <div class="meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['address']); ?></div>
                        <div class="meta-item"><i class="fas fa-calendar"></i> <?php echo date("F j, Y", strtotime($row['created_at'])); ?></div>
                      </div>
                    </div>
                  </div>
                  <div class="job-applied">
                    <h4><?php echo htmlspecialchars($row['job_title']); ?></h4>
                    <p><?php echo htmlspecialchars($row['company_name']); ?></p>
                    <span class="application-status-badge <?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                  </div>

                  <div class="application-actions">
                    <a class="action-btn view-resume-btn" href="../JobSeeker/<?php echo htmlspecialchars($row['resume_path']); ?>" target="_blank">
                      <i class="fas fa-file-pdf"></i> View Resume
                    </a>
                    <form action="update_status.php" method="POST" class="status-form">
                      <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($application_id); ?>">
                      <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($jobseeker_id); ?>">
                      <select name="status" class="status-select">
                        <option value="Under Review" <?php echo $row['status'] == 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                        <option value="Shortlisted" <?php echo $row['status'] == 'Shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                        <option value="Interview" <?php echo $row['status'] == 'Interview' ? 'selected' : ''; ?>>Interview</option>
                        <option value="Accepted" <?php echo $row['status'] == 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                        <option value="Rejected" <?php echo $row['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                      </select>
                      <button type="submit" class="action-btn change-status-btn">
                        <i class="fas fa-exchange-alt"></i> Change Status
                      </button>
                    </form>
                    <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
                  </div>
                </div>

                <div class="application-details-expanded">
                  <div class="candidate-profile">
                    <div class="profile-section">
                      <h4>Skills</h4>
                      <div class="skills-list">
                        <?php
                        $skillsClass = new Skills($db);
                        $skills = $skillsClass->retrieveSkills($jobseeker_id);

                        if (!empty($skills)) {
                          foreach ($skills as $skill) {
                            echo "<span class='skill-tag'>" . htmlspecialchars($skill['skill_name']) . "</span>";
                          }
                        } else {
                          echo "<p>No skills listed.</p>";
                        }
                        ?>
                      </div>
                    </div>

                    <div class="profile-section">
                      <h4>Experience</h4>
                      <?php
                      $experience = new Experiences($db);
                      $stmt = $experience->retrieveExperiences($jobseeker_id);
                      $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      $stmt->closeCursor();

                      if ($experiences) {
                        foreach ($experiences as $exp) {
                          if (!empty($exp['job_title']) && !empty($exp['company_name'])) {
                            echo '<div class="experience-item">';
                            echo '<h5>' . htmlspecialchars($exp['job_title']) . '</h5>';
                            echo '<p>' . htmlspecialchars($exp['company_name']) . ' | ' .
                              htmlspecialchars($exp['start_date']) . ' - ' .
                              htmlspecialchars($exp['end_date']) . '</p>';
                            echo '</div>';
                          }
                        }
                      } else {
                        echo '<p>No experience available.</p>';
                      }
                      ?>
                    </div>

                    <div class="profile-section">
                      <h4>Education</h4>
                      <?php
                      $education = new Education($db);
                      $stmt = $education->retrieveEducationalBackground($jobseeker_id);
                      $educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      $stmt->closeCursor();

                      if ($educations) {
                        foreach ($educations as $edu) {
                          if (!empty($edu['degree']) && !empty($edu['institution'])) {
                            echo '<div class="education-item">';
                            echo '<h5>' . htmlspecialchars($edu['degree']) . '</h5>';
                            echo '<p>' . htmlspecialchars($edu['institution']) . ' | ' .
                              htmlspecialchars($edu['start_date']) . ' - ' .
                              htmlspecialchars($edu['end_date']) . '</p>';
                            echo '</div>';
                          }
                        }
                      } else {
                        echo '<p>No education details available.</p>';
                      }
                      ?>
                    </div>

                    <div class="cover-letter-section">
                      <h4>Cover Letter</h4>
                      <div class="cover-letter-content">
                        <?php
                        // check if cover letter exists
                        if (!empty($row['cover_letter'])) {
                          echo nl2br(htmlspecialchars($row['cover_letter']));
                        } else {
                          echo '<p>No cover letter provided.</p>';
                        }
                        ?>
                      </div>
                    </div>

                    <?php
                    $statusLog = new StatusLog($db);
                    $statuses = $statusLog->retrieveStatusLog($application_id);


                    $customLabels = [
                      'Under Review' => 'Application Received',
                      'Shortlisted' => 'Resume Screened',
                      'Interview' => 'Interview Scheduled',
                      'Accepted' => 'Application Accepted',
                      'Rejected' => 'Application Rejected'
                    ];

                    $allSteps = [
                      'Under Review',
                      'Shortlisted',
                      'Interview',
                      'Final Decision'
                    ];

                    echo '<div class="application-timeline">';
                    echo '<h4>Application Timeline</h4>';
                    echo '<div class="timeline">';

                    $addedStatuses = array_column($statuses, 'status');
                    $isUnderReviewChecked = !in_array('Under Review', $addedStatuses); // check if status is under review

                    foreach ($allSteps as $step) {
                      if ($step == 'Under Review' && $isUnderReviewChecked) {
                        $timestamp = date("F j, Y \\a\\t g:i A", time());
                        $label = isset($customLabels[$step]) ? $customLabels[$step] : $step;
                        echo '
                      <div class="timeline-item active">
                        <div class="timeline-icon"><i class="fas fa-check"></i></div>
                        <div class="timeline-content">
                          <h4>' . htmlspecialchars($label) . '</h4>
                          <p>' . $timestamp . '</p>
                        </div>
                      </div>';
                      } elseif ($step == 'Final Decision') {
                        $decisionMade = false;
                        $timestamp = '';

                        foreach ($statuses as $s) {
                          if ($s['status'] == 'Accepted') {
                            $timestamp = date("F j, Y \\a\\t g:i A", strtotime($s['timestamp']));
                            $label = 'Application Accepted';
                            echo '
                            <div class="timeline-item active">
                              <div class="timeline-icon"><i class="fas fa-check"></i></div>
                              <div class="timeline-content">
                                <h4>' . htmlspecialchars($label) . '</h4>
                                <p>' . $timestamp . ' </p>
                              </div>
                            </div>';
                            $decisionMade = true;
                            break;
                          } elseif ($s['status'] == 'Rejected') {
                            $timestamp = date("F j, Y \\a\\t g:i A", strtotime($s['timestamp']));
                            $label = 'Application Rejected';
                            echo '
                              <div class="timeline-item active">
                                <div class="timeline-icon"><i class="fas fa-times"></i></div>
                                <div class="timeline-content">
                                  <h4>' . htmlspecialchars($label) . '</h4>
                                  <p>' . $timestamp . ' </p>
                                </div>
                              </div>';
                            $decisionMade = true;
                            break;
                          }
                        }

                        if (!$decisionMade) {
                          $label = 'Final Decision';
                          echo ' 
                          <div class="timeline-item">
                            <div class="timeline-icon"><i class="fas fa-hourglass-half"></i></div>
                            <div class="timeline-content">
                              <h4>' . htmlspecialchars($label) . '</h4>
                              <p>Pending</p>
                            </div>
                          </div>';
                        }
                      } else {
                        // For other steps, check if they exist in the database and show the appropriate status
                        if (in_array($step, $addedStatuses)) {
                          // Find the timestamp for the step
                          $timestamp = '';
                          foreach ($statuses as $s) {
                            if ($s['status'] === $step) {
                              $timestamp = date("F j, Y \\a\\t g:i A", strtotime($s['timestamp']));
                              break;
                            }
                          }
                          $label = isset($customLabels[$step]) ? $customLabels[$step] : $step;
                          echo '
                          <div class="timeline-item active">
                            <div class="timeline-icon"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                              <h4>' . htmlspecialchars($label) . '</h4>
                              <p>' . $timestamp . '</p>
                            </div>
                          </div>';
                        } else {
                          // Step is not in the status log, so it is pending
                          $label = isset($customLabels[$step]) ? $customLabels[$step] : $step;
                          echo ' 
                          <div class="timeline-item">
                            <div class="timeline-icon"><i class="fas fa-hourglass-half"></i></div>
                            <div class="timeline-content">
                              <h4>' . htmlspecialchars($label) . '</h4>
                              <p>Pending</p>
                            </div>
                          </div>';
                        }
                      }
                    }

                    echo '</div>';
                    ?>


                    <div class="candidate-actions">
                      <form action="update_status.php" method="POST" class="interview-form" style="display: inline;">
                        <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                        <input type="hidden" name="status" value="Interview">
                        <button type="submit" class="candidate-action-btn">
                          <i class="fas fa-calendar-alt"></i> Schedule Interview
                        </button>
                      </form>
                      <button class="candidate-action-btn"><i class="fas fa-user-check"></i> Move to Shortlist</button>
                      <form action="update_status.php" method="POST" class="reject-form" style="display: inline;">
                        <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="candidate-action-btn reject">
                          <i class="fas fa-times"></i> Reject Application
                        </button>
                      </form>


                    </div>
                  </div>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<div class='no-applications'><i class='fas fa-search'></i><p>No applications found.</p></div>";
          }
          ?>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".filter-tab");
    const applications = document.querySelectorAll(".application-item");

    tabs.forEach(tab => {
      tab.addEventListener("click", function () {
        // Remove active class from all tabs
        tabs.forEach(t => t.classList.remove("active"));
        this.classList.add("active");

        const filter = this.dataset.filter.toLowerCase();

        applications.forEach(app => {
          const statusElement = app.querySelector(".application-status-badge");
          const status = statusElement ? statusElement.textContent.trim().toLowerCase() : "";

          if (filter === "all" || status === filter) {
            app.style.display = "block";
          } else {
            app.style.display = "none";
          }
        });
      });
    });
  });
    // Toggle application details
    const toggleButtons = document.querySelectorAll('.toggle-details-btn');

    toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const applicationItem = this.closest('.application-item');
      const detailsSection = applicationItem.querySelector('.application-details-expanded');

      if (detailsSection.style.display === 'block') {
        detailsSection.style.display = 'none';
        this.innerHTML = '<i class="fas fa-chevron-down"></i>';
      } else {
        detailsSection.style.display = 'block';
        this.innerHTML = '<i class="fas fa-chevron-up"></i>';
      }
    });
    });
  
  </script>
  <?php
  if (isset($_SESSION['status_success'])) {
    echo "<script>
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: '" . $_SESSION['status_success'] . "'
        });
    </script>";
    unset($_SESSION['status_success']);
  }

  if (isset($_SESSION['status_error'])) {
    echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: '" . $_SESSION['status_error'] . "'
        });
    </script>";
    unset($_SESSION['status_error']);
  }
  ?>

</body>

</html>