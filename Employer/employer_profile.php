<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isEmployer()) {
  header('Location: /ADMSSYSTEM/logout.php'); 
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BatStateU Career Hub - Employer's Profile</title>
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
          <li class="active">
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
        <div class="profile-header">
          <h1>Employer's Profile</h1>
          <div class="profile-actions">
          </div>
        </div>

        <div class="profile-container">
          <?php
          $updateStatus = '';
          $database = new Database();
          $db = $database->getConnect();

          $employer = new Employers($db);
          $user_id = $_SESSION['id'];

          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
            $job_title = isset($_POST['job_title']) ? $_POST['job_title'] : '';
            $bio = isset($_POST['bio']) ? $_POST['bio'] : '';


            $employer->company_name = $company_name;
            $employer->job_title = $job_title;
            $employer->bio = $bio;

            if ($employer->updateProfile($user_id)) {
              $updateStatus = 'success';
            } else {
              $updateStatus = 'error';
            }
          }

          $stmt = $employer->retrieveEmpProfile($user_id);

          if ($stmt && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              // Extract variables
              $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
              $job_title = htmlspecialchars($row['job_title'] ?? '');
              $company_name = htmlspecialchars($row['company_name'] ?? '');
              $email = htmlspecialchars($row['email']);
              $phone = htmlspecialchars($row['phone_number']);
              $bio = htmlspecialchars($row['bio'] ?? '');
          ?>
              <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="profile-section">
                  <div class="section-header">
                    <h2><i class="fas fa-user"></i> Personal Information</h2>
                    <div class="section-actions">
                      <button class="save-section-btn" type="submit" name="save_personal" data-section="personal"><i class="fas fa-save"></i> Save</button>
                    </div>
                  </div>

                  <div class="profile-details">
                    <div class="info-row">
                      <div class="info-label">Full Name:</div>
                      <div class="info-value"><?= $full_name ?></div>
                    </div>

                    <div class="info-row">
                      <div class="info-label">Email:</div>
                      <div class="info-value"><?= $email ?></div>
                    </div>

                    <div class="info-row">
                      <div class="info-label">Phone:</div>
                      <div class="info-value"><?= $phone ?></div>
                    </div>

                    <div class="info-row">
                      <div class="info-label">Job Title:</div>
                      <div class="info-value">
                        <input type="text" id="job_title" name="job_title" value="<?= $job_title ?>" class="inline-edit">
                      </div>
                    </div>

                    <div class="info-row">
                      <div class="info-label">Company Name:</div>
                      <div class="info-value">
                        <input type="text" id="company_name" name="company_name" value="<?= $company_name ?>" class="inline-edit">
                      </div>
                    </div>

                    <div class="info-row">
                      <div class="info-label">Bio:</div>
                      <div class="info-value">
                        <textarea id="bio" name="bio" rows="4" class="inline-edit"><?= $bio ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
        </div>
    <?php
            } 
          } 
    ?>
      </div>
  </div>

  </main>
  </div>
  <?php if ($updateStatus === 'success'): ?>
    <script>
      Swal.fire({
        title: 'Section Updated',
        text: 'Your information has been updated successfully.',
        icon: 'success',
        confirmButtonColor: '#c41e3a'
      }).then(() => {
        window.location.href = 'employer_profile.php';
      });
    </script>
  <?php elseif ($updateStatus === 'error'): ?>
    <script>
      Swal.fire({
        title: 'Error!',
        text: 'Error updating user',
        icon: 'error',
        confirmButtonText: 'Try Again',
        background: '#fff',
        backdrop: true,
      }).then(() => {
        window.location.href = 'employer_profile.php';
      });
    </script>
  <?php endif; ?>

</body>

</html>