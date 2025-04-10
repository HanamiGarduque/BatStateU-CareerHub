<?php
session_start();

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
          <!-- Personal Information Section -->
          <div class="profile-section">
            <div class="section-header">
              <h2><i class="fas fa-user"></i> Personal Information</h2>
              <div class="section-actions">
                <button class="save-section-btn" data-section="personal"><i class="fas fa-save"></i> Save</button>
              </div>
            </div>
            <div class="profile-info">
              <div class="profile-image-container">
                <img src="../placeholder.jpg" alt="Profile Picture" class="profile-image">
                <button class="change-photo-btn"><i class="fas fa-camera"></i> Change Photo</button>
              </div>
              
              <div class="profile-details">
                <div class="info-row">
                  <div class="info-label">Full Name:</div>
                  <div class="info-value">
                    <input type="text" id="full-name" name="full-name" value="Juan Dela Cruz" class="inline-edit">
                  </div>
                </div>
                
                <div class="info-row">
                  <div class="info-label">Job Title:</div>
                  <div class="info-value">
                    <input type="text" id="job-title" name="job-title" value="HR Manager" class="inline-edit">
                  </div>
                </div>

                <div class="info-row">
                <div class="info-label">Company Name:</div>
                  <div class="info-value">
                    <input type="text" id="company-name" name="company-name" value="Tech Solutions Inc." class="inline-edit">
                  </div>
                </div>
                
                <div class="info-row">
                  <div class="info-label">Email:</div>
                  <div class="info-value">
                    <input type="email" id="email" name="email" value="juan.delacruz@example.com" class="inline-edit">
                  </div>
                </div>
                
                <div class="info-row">
                  <div class="info-label">Phone:</div>
                  <div class="info-value">
                    <input type="tel" id="phone" name="phone" value="+63 912 345 6789" class="inline-edit">
                  </div>
                </div>
                
                <div class="info-row">
                  <div class="info-label">LinkedIn:</div>
                  <div class="info-value">
                    <input type="url" id="linkedin" name="linkedin" value="https://linkedin.com/in/juandelacruz" class="inline-edit">
                  </div>
                </div>
                
                <div class="info-row">
                  <div class="info-label">Bio:</div>
                  <div class="info-value">
                    <textarea id="bio" name="bio" rows="4" class="inline-edit">Experienced HR professional with over 8 years of experience in talent acquisition and employee management. Passionate about connecting the right talent with the right opportunities.</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
        </div>
      </div>
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
      
      // Change Photo Button
      const changePhotoBtn = document.querySelector('.change-photo-btn');
      changePhotoBtn.addEventListener('click', function() {
        // Simulate file upload dialog
        Swal.fire({
          title: 'Upload Profile Photo',
          text: 'Select a new profile photo to upload.',
          input: 'file',
          inputAttributes: {
            'accept': 'image/*',
            'aria-label': 'Upload your profile picture'
          },
          showCancelButton: true,
          confirmButtonColor: '#c41e3a',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Upload'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'Photo Updated',
              text: 'Your profile photo has been updated successfully.',
              icon: 'success',
              confirmButtonColor: '#c41e3a'
            });
          }
        });
      });
      
      // Change Logo Button
      const changeLogoBtn = document.querySelector('.change-logo-btn');
      changeLogoBtn.addEventListener('click', function() {
        // Simulate file upload dialog
        Swal.fire({
          title: 'Upload Company Logo',
          text: 'Select a new company logo to upload.',
          input: 'file',
          inputAttributes: {
            'accept': 'image/*',
            'aria-label': 'Upload your company logo'
          },
          showCancelButton: true,
          confirmButtonColor: '#c41e3a',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Upload'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'Logo Updated',
              text: 'Your company logo has been updated successfully.',
              icon: 'success',
              confirmButtonColor: '#c41e3a'
            });
          }
        });
      });

    });
  </script>
</body>

</html>