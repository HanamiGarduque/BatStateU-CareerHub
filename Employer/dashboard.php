<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BatStateU Career Hub - Admin Dashboard</title>
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
            <a href="applications.php"><i class="fas fa-file-alt"></i> Applications</a>
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
        <h1>Admin Dashboard</h1>
        
        <!-- Stats Container -->
        <div class="stats-container">
          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-info">
              <h3>24</h3>
              <p>Active Job Postings</p>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
              <h3>156</h3>
              <p>Total Applications</p>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-info">
              <h3>42</h3>
              <p>New Candidates</p>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-info">
              <h3>18</h3>
              <p>Positions Filled</p>
            </div>
          </div>
        </div>
        
        <!-- Recent Applications -->
        <div class="section-header">
          <h2>Recent Applications</h2>
          <a href="applications.php" class="view-all">View All</a>
        </div>
        
        <div class="applications-container">
          <div class="application-card">
            <div class="application-header">
              <div class="company-logo"></div>
              <div class="application-title-container">
                <h3>Software Developer</h3>
                <p class="company-name">Tech Solutions Inc.</p>
              </div>
            </div>
            
            <div class="application-details">
              <div class="application-detail">
                <i class="fas fa-user"></i> John Doe
              </div>
              <div class="application-detail">
                <i class="fas fa-calendar"></i> Applied on May 15, 2023
              </div>
              <div class="application-detail">
                <i class="fas fa-tag"></i> <span class="application-status-badge pending">Pending Review</span>
              </div>
            </div>
            
            <button class="view-application-btn">View Application</button>
          </div>
          
          <div class="application-card">
            <div class="application-header">
              <div class="company-logo"></div>
              <div class="application-title-container">
                <h3>Marketing Specialist</h3>
                <p class="company-name">Global Marketing PH</p>
              </div>
            </div>
            
            <div class="application-details">
              <div class="application-detail">
                <i class="fas fa-user"></i> Jane Smith
              </div>
              <div class="application-detail">
                <i class="fas fa-calendar"></i> Applied on May 14, 2023
              </div>
              <div class="application-detail">
                <i class="fas fa-tag"></i> <span class="application-status-badge interview">Interview Scheduled</span>
              </div>
            </div>
            
            <button class="view-application-btn">View Application</button>
          </div>
          
          <div class="application-card">
            <div class="application-header">
              <div class="company-logo"></div>
              <div class="application-title-container">
                <h3>Graphic Designer</h3>
                <p class="company-name">Creative Designs Co.</p>
              </div>
            </div>
            
            <div class="application-details">
              <div class="application-detail">
                <i class="fas fa-user"></i> Mike Johnson
              </div>
              <div class="application-detail">
                <i class="fas fa-calendar"></i> Applied on May 12, 2023
              </div>
              <div class="application-detail">
                <i class="fas fa-tag"></i> <span class="application-status-badge accepted">Offer Sent</span>
              </div>
            </div>
            
            <button class="view-application-btn">View Application</button>
          </div>
        </div>
        
        <!-- Recent Job Postings -->
        <div class="section-header">
          <h2>Recent Job Postings</h2>
          <a href="job_postings.php" class="view-all">View All</a>
        </div>
        
        <div class="jobs-container">
          <div class="job-card">
            <div class="job-header">
              <div class="company-logo"></div>
              <div class="job-title-container">
                <h3>Senior Web Developer</h3>
                <p class="company-name">Tech Solutions Inc.</p>
              </div>
            </div>
            
            <div class="job-details">
              <div class="job-detail">
                <i class="fas fa-map-marker-alt"></i> Batangas City
              </div>
              <div class="job-detail">
                <i class="fas fa-briefcase"></i> Full-time
              </div>
              <div class="job-detail">
                <i class="fas fa-money-bill-wave"></i> ₱50,000 - ₱70,000
              </div>
              <div class="job-detail">
                <i class="fas fa-calendar"></i> Posted 2 days ago
              </div>
            </div>
            
            <div class="job-description">
              <p>We are looking for an experienced web developer to join our team. The ideal candidate should have strong skills in JavaScript, React, and Node.js.</p>
            </div>
            
            <div class="job-actions">
              <button class="view-btn">Edit Job</button>
              <button class="view-btn">View Applicants (5)</button>
            </div>
          </div>
          
          <div class="job-card">
            <div class="job-header">
              <div class="company-logo"></div>
              <div class="job-title-container">
                <h3>HR Manager</h3>
                <p class="company-name">People First Inc.</p>
              </div>
            </div>
            
            <div class="job-details">
              <div class="job-detail">
                <i class="fas fa-map-marker-alt"></i> Lipa City
              </div>
              <div class="job-detail">
                <i class="fas fa-briefcase"></i> Full-time
              </div>
              <div class="job-detail">
                <i class="fas fa-money-bill-wave"></i> ₱40,000 - ₱55,000
              </div>
              <div class="job-detail">
                <i class="fas fa-calendar"></i> Posted 3 days ago
              </div>
            </div>
            
            <div class="job-description">
              <p>We are seeking an experienced HR Manager to oversee all aspects of human resources management including recruitment, employee relations, and benefits administration.</p>
            </div>
            
            <div class="job-actions">
              <button class="view-btn">Edit Job</button>
              <button class="view-btn">View Applicants (3)</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>