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
          <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">All Jobs (24)</button>
            <button class="filter-tab" data-filter="active">Active (18)</button>
            <button class="filter-tab" data-filter="expired">Expired (3)</button>
          </div>

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
          <!-- Job Posting Item 1 -->
          <div class="job-posting-item">
            <div class="job-posting-main">
              <div class="job-posting-info">
                <h3 class="job-title">Senior Web Developer</h3>
                <div class="job-meta">
                  <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                  <div class="meta-item"><i class="fas fa-briefcase"></i> Full-time</div>
                  <div class="meta-item"><i class="fas fa-money-bill-wave"></i> ₱50,000 - ₱70,000</div>
                  <div class="meta-item"><i class="fas fa-calendar"></i> Posted on May 15, 2023</div>
                  <div class="meta-item"><i class="fas fa-clock"></i> Expires on June 15, 2023</div>
                </div>
              </div>

              <div class="job-posting-stats">
                <div class="job-stat">
                  <span class="stat-value">12</span>
                  <span class="stat-label">Applications</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">5</span>
                  <span class="stat-label">Interviews</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">2</span>
                  <span class="stat-label">Shortlisted</span>
                </div>
              </div>

              <div class="job-posting-actions">
                <button class="action-btn view-btn"><i class="fas fa-eye"></i> View</button>
                <button class="action-btn edit-btn"><i class="fas fa-edit"></i> Edit</button>
                <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Delete</button>
              </div>
            </div>
          </div>

          <!-- Job Posting Item 2 -->
          <div class="job-posting-item">
            <div class="job-posting-main">
              <div class="job-posting-info">
                <h3 class="job-title">HR Manager</h3>
                <div class="job-meta">
                  <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Lipa City</div>
                  <div class="meta-item"><i class="fas fa-briefcase"></i> Full-time</div>
                  <div class="meta-item"><i class="fas fa-money-bill-wave"></i> ₱40,000 - ₱55,000</div>
                  <div class="meta-item"><i class="fas fa-calendar"></i> Posted on May 12, 2023</div>
                  <div class="meta-item"><i class="fas fa-clock"></i> Expires on June 12, 2023</div>
                </div>
              </div>

              <div class="job-posting-stats">
                <div class="job-stat">
                  <span class="stat-value">8</span>
                  <span class="stat-label">Applications</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">3</span>
                  <span class="stat-label">Interviews</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">1</span>
                  <span class="stat-label">Shortlisted</span>
                </div>
              </div>

              <div class="job-posting-actions">
                <button class="action-btn view-btn"><i class="fas fa-eye"></i> View</button>
                <button class="action-btn edit-btn"><i class="fas fa-edit"></i> Edit</button>
                <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Delete</button>
              </div>
            </div>
          </div>

          <!-- Job Posting Item 3 -->
          <div class="job-posting-item">
            <div class="job-posting-main">
              <div class="job-posting-info">
                <h3 class="job-title">Marketing Specialist</h3>
                <div class="job-meta">
                  <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                  <div class="meta-item"><i class="fas fa-briefcase"></i> Full-time</div>
                  <div class="meta-item"><i class="fas fa-money-bill-wave"></i> ₱35,000 - ₱45,000</div>
                  <div class="meta-item"><i class="fas fa-calendar"></i> Posted on May 10, 2023</div>
                  <div class="meta-item"><i class="fas fa-clock"></i> Expires on June 10, 2023</div>
                </div>
              </div>

              <div class="job-posting-stats">
                <div class="job-stat">
                  <span class="stat-value">15</span>
                  <span class="stat-label">Applications</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">7</span>
                  <span class="stat-label">Interviews</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">3</span>
                  <span class="stat-label">Shortlisted</span>
                </div>
              </div>

              <div class="job-posting-actions">
                <button class="action-btn view-btn"><i class="fas fa-eye"></i> View</button>
                <button class="action-btn edit-btn"><i class="fas fa-edit"></i> Edit</button>
                <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Delete</button>
              </div>
            </div>
          </div>

          <!-- Job Posting Item 4 (Draft) -->
          <div class="job-posting-item draft">
            <div class="job-posting-main">
              <div class="job-posting-info">
                <h3 class="job-title">Customer Service Representative</h3>
                <div class="job-meta">
                  <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Santo Tomas</div>
                  <div class="meta-item"><i class="fas fa-briefcase"></i> Part-time</div>
                  <div class="meta-item"><i class="fas fa-money-bill-wave"></i> ₱18,000 - ₱25,000</div>
                  <div class="meta-item"><i class="fas fa-file-alt"></i> <span class="status-badge draft">Draft</span></div>
                  <div class="meta-item"><i class="fas fa-calendar"></i> Last edited on May 8, 2023</div>
                </div>
              </div>

              <div class="job-posting-stats">
                <div class="job-stat">
                  <span class="stat-value">-</span>
                  <span class="stat-label">Applications</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">-</span>
                  <span class="stat-label">Interviews</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">-</span>
                  <span class="stat-label">Shortlisted</span>
                </div>
              </div>

              <div class="job-posting-actions">
                <button class="action-btn view-btn"><i class="fas fa-eye"></i> Preview</button>
                <button class="action-btn edit-btn"><i class="fas fa-edit"></i> Edit</button>
                <button class="action-btn publish-btn"><i class="fas fa-paper-plane"></i> Publish</button>
              </div>
            </div>
          </div>

          <!-- Job Posting Item 5 (Expired) -->
          <div class="job-posting-item expired">
            <div class="job-posting-main">
              <div class="job-posting-info">
                <h3 class="job-title">Graphic Designer</h3>
                <div class="job-meta">
                  <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                  <div class="meta-item"><i class="fas fa-briefcase"></i> Full-time</div>
                  <div class="meta-item"><i class="fas fa-money-bill-wave"></i> ₱25,000 - ₱35,000</div>
                  <div class="meta-item"><i class="fas fa-file-alt"></i> <span class="status-badge expired">Expired</span></div>
                  <div class="meta-item"><i class="fas fa-calendar"></i> Expired on May 1, 2023</div>
                </div>
              </div>

              <div class="job-posting-stats">
                <div class="job-stat">
                  <span class="stat-value">6</span>
                  <span class="stat-label">Applications</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">2</span>
                  <span class="stat-label">Interviews</span>
                </div>
                <div class="job-stat">
                  <span class="stat-value">1</span>
                  <span class="stat-label">Shortlisted</span>
                </div>
              </div>

              <div class="job-posting-actions">
                <button class="action-btn view-btn"><i class="fas fa-eye"></i> View</button>
                <button class="action-btn renew-btn"><i class="fas fa-sync"></i> Renew</button>
                <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Delete</button>
              </div>
            </div>
          </div>
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
      // Filter tabs functionality
      const filterTabs = document.querySelectorAll('.filter-tab');
      const jobItems = document.querySelectorAll('.job-posting-item');

      filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
          // Remove active class from all tabs
          filterTabs.forEach(t => t.classList.remove('active'));

          // Add active class to clicked tab
          this.classList.add('active');

          // Get filter value
          const filter = this.getAttribute('data-filter');

          // Show/hide job items based on filter
          jobItems.forEach(item => {
            if (filter === 'all') {
              item.style.display = 'block';
            } else if (filter === 'draft' && item.classList.contains('draft')) {
              item.style.display = 'block';
            } else if (filter === 'expired' && item.classList.contains('expired')) {
              item.style.display = 'block';
            } else if (filter === 'active' && !item.classList.contains('draft') && !item.classList.contains('expired')) {
              item.style.display = 'block';
            } else {
              item.style.display = 'none';
            }
          });
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
    });
  </script>
</body>

</html>