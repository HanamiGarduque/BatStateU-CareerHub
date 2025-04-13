<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isEmployer()) {
  header('Location: /ADMSSYSTEM/login.php'); // Or wherever you want
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
        <div class="logo"></div>
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
        <div class="profile-header">
          <h1>Employer's Profile</h1>
          <div class="profile-actions">
          </div>
        </div>

        <div class="profile-container">
          <?php
          $database = new Database();
          $db = $database->getConnect();

          $user = new Users($db);
          $user_id = $_SESSION['id'];

          $stmt = $user->retrieveProfile($user_id);
          $num = $stmt->rowCount();

          if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              // Extract variables
              $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
              $job_title = htmlspecialchars($row['job_title'] ?? '');
              $company_name = htmlspecialchars($row['company_name'] ?? '');
              $email = htmlspecialchars($row['email']);
              $phone = htmlspecialchars($row['phone_number']);
              $bio = htmlspecialchars($row['bio'] ?? '');
          ?>
              <form method="POST" action="">
                <!-- Personal Information Section -->
                <div class="profile-section">
                  <div class="section-header">
                    <h2><i class="fas fa-user"></i> Personal Information</h2>
                    <div class="section-actions">
                      <button class="save-section-btn" data-section="personal"><i class="fas fa-save"></i> Save</button>
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
                        <input type="text" id="job-title" name="job-title" value="<?= $job_title ?>" class="inline-edit">
                      </div>
                    </div>

                    <div class="info-row">
                      <div class="info-label">Company Name:</div>
                      <div class="info-value">
                        <input type="text" id="company-name" name="company-name" value="<?= $company_name ?>" class="inline-edit">
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
            } // while
          } // if
    ?>
      </div>
  </div>
  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = isset($_POST['company-name']) ? $_POST['company-name'] : '';
    $job_title = isset($_POST['job-title']) ? $_POST['job-title'] : '';
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';

    $user->company_name = $company_name;
    $user->job_title = $job_title;
    $user->bio = $bio;

    if ($user->updateProfile($user_id)) {
      echo "<script>
            Swal.fire({
            title: 'Section Updated',
            text: `Your]\ information has been updated successfully.`,
            icon: 'success',
            confirmButtonColor: '#c41e3a'
          });
              }).then(() => {
                  window.location.href = 'employer_profile.php';
              });
            </script>";
    } else {
      echo "<script>
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
            </script>";
    }
  }
  ?>
  </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Save Section Buttons
      const saveSectionBtns = document.querySelectorAll('.save-section-btn');

      saveSectionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const section = this.getAttribute('data-section');

          // Simulate saving changes for the specific section
          Swal.fire({
            title: 'Section Updated',
            text: `Your ${section} information has been updated successfully.`,
            icon: 'success',
            confirmButtonColor: '#c41e3a'
          });
        });
      });

      // Save All Button
      const saveAllBtn = document.querySelector('.save-all-btn');

      saveAllBtn.addEventListener('click', function() {
        // Validate form fields
        const requiredFields = document.querySelectorAll('.inline-edit[required]');
        let isValid = true;

        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            isValid = false;
            field.classList.add('error');
          } else {
            field.classList.remove('error');
          }
        });

        if (!isValid) {
          Swal.fire({
            title: 'Error',
            text: 'Please fill in all required fields.',
            icon: 'error',
            confirmButtonColor: '#c41e3a'
          });
          return;
        }

        // Simulate saving all changes
        Swal.fire({
          title: 'Profile Updated',
          text: 'All your profile information has been updated successfully.',
          icon: 'success',
          confirmButtonColor: '#c41e3a'
        });
      });

    });
  </script><script>
  document.addEventListener('DOMContentLoaded', function() {
    // Save Section Buttons
    const saveSectionBtns = document.querySelectorAll('.save-section-btn');

    saveSectionBtns.forEach(btn => {
      btn.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent form submission

        const section = this.getAttribute('data-section');
        const sectionContainer = this.closest('.profile-section');

        // Find all inline-edit inputs within the section
        const inputs = sectionContainer.querySelectorAll('.inline-edit');

        inputs.forEach(input => {
          const value = input.value.trim();

          // Find the corresponding display element and update it
          const displayElement = input.closest('.info-row').querySelector('.info-value');
          if (displayElement) {
            displayElement.textContent = value; // Update the displayed value
          }
        });

        // Simulate saving changes for the specific section
        Swal.fire({
          title: 'Section Updated',
          text: `Your ${section} information has been updated successfully.`,
          icon: 'success',
          confirmButtonColor: '#c41e3a'
        });
      });
    });

    // Save All Button
    const saveAllBtn = document.querySelector('.save-all-btn');

    saveAllBtn.addEventListener('click', function(event) {
      event.preventDefault(); // Prevent form submission

      // Validate form fields
      const requiredFields = document.querySelectorAll('.inline-edit[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add('error');
        } else {
          field.classList.remove('error');
        }
      });

      if (!isValid) {
        Swal.fire({
          title: 'Error',
          text: 'Please fill in all required fields.',
          icon: 'error',
          confirmButtonColor: '#c41e3a'
        });
        return;
      }

      // Update all displayed values
      const allInputs = document.querySelectorAll('.inline-edit');
      allInputs.forEach(input => {
        const value = input.value.trim();

        // Find the corresponding display element and update it
        const displayElement = input.closest('.info-row').querySelector('.info-value');
        if (displayElement) {
          displayElement.textContent = value; // Update the displayed value
        }
      });

      // Simulate saving all changes
      Swal.fire({
        title: 'Profile Updated',
        text: 'All your profile information has been updated successfully.',
        icon: 'success',
        confirmButtonColor: '#c41e3a'
      });
    });
  });
</script>
</body>

</html>