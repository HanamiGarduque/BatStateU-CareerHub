<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BatStateU Career Hub - Notifications</title>
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
          <li>
            <a href="job_postings.php"><i class="fas fa-briefcase"></i> Job Postings</a>
          </li>
          <li>
            <a href="applications_management.php"><i class="fas fa-file-alt"></i> Applications</a>
          </li>
          <li class="active">
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
          <input type="text" placeholder="Search notifications...">
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
        <div class="notifications-header">
          <h1>Notifications</h1>
          <div class="notifications-actions">
            <button class="mark-all-read-btn"><i class="fas fa-check-double"></i> Mark All as Read</button>
            <select class="filter-select">
              <option value="all">All Notifications</option>
              <option value="unread">Unread</option>
              <option value="applications">Applications</option>
              <option value="messages">Messages</option>
              <option value="system">System</option>
            </select>
          </div>
        </div>
        
        <!-- Notifications List -->
        <div class="notifications-list">
          <!-- Notification Item 1 (Unread) -->
          <div class="notification-item unread">
            <div class="notification-icon application">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="notification-content">
              <div class="notification-header">
                <h3 class="notification-title">New Application Received</h3>
                <span class="notification-time">10 minutes ago</span>
              </div>
              <p class="notification-message">John Doe has applied for the position of Senior Web Developer.</p>
              <div class="notification-actions">
                <button class="notification-action-btn"><i class="fas fa-eye"></i> View Application</button>
                <button class="notification-action-btn"><i class="fas fa-check"></i> Mark as Read</button>
              </div>
            </div>
          </div>
          
          <!-- Notification Item 2 (Unread) -->
          <div class="notification-item unread">
            <div class="notification-icon message">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="notification-content">
              <div class="notification-header">
                <h3 class="notification-title">New Message from Candidate</h3>
                <span class="notification-time">30 minutes ago</span>
              </div>
              <p class="notification-message">Jane Smith has sent you a message regarding her application for Marketing Specialist.</p>
              <div class="notification-actions">
                <button class="notification-action-btn"><i class="fas fa-reply"></i> Reply</button>
                <button class="notification-action-btn"><i class="fas fa-check"></i> Mark as Read</button>
              </div>
            </div>
          </div>
          
          <!-- Notification Item 3 (Unread) -->
          <div class="notification-item unread">
            <div class="notification-icon application">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="notification-content">
              <div class="notification-header">
                <h3 class="notification-title">New Application Received</h3>
                <span class="notification-time">1 hour ago</span>
              </div>
              <p class="notification-message">Mike Johnson has applied for the position of Graphic Designer.</p>
              <div class="notification-actions">
                <button class="notification-action-btn"><i class="fas fa-eye"></i> View Application</button>
                <button class="notification-action-btn"><i class="fas fa-check"></i> Mark as Read</button>
              </div>
            </div>
          </div>
          
          <!-- Notification Item 4 (Read) -->
          <div class="notification-item">
            <div class="notification-icon system">
              <i class="fas fa-cog"></i>
            </div>
            <div class="notification-content">
              <div class="notification-header">
                <h3 class="notification-title">Job Posting Expiring Soon</h3>
                <span class="notification-time">5 hours ago</span>
              </div>
              <p class="notification-message">Your job posting for "HR Manager" will expire in 3 days. Consider renewing it to continue receiving applications.</p>
              <div class="notification-actions">
                <button class="notification-action-btn"><i class="fas fa-sync"></i> Renew Job</button>
              </div>
            </div>
          </div>
          
          <!-- Notification Item 5 (Read) -->
          <div class="notification-item">
            <div class="notification-icon application">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="notification-content">
              <div class="notification-header">
                <h3 class="notification-title">Application Status Updated</h3>
                <span class="notification-time">1 day ago</span>
              </div>
              <p class="notification-message">Maria Santos has updated the status of Robert Lee's application for Data Analyst to "Rejected".</p>
              <div class="notification-actions">
                <button class="notification-action-btn"><i class="fas fa-eye"></i> View Application</button>
              </div>
            </div>
          </div>
          
          <!-- Notification Item 6 (Read) -->
          <div class="notification-item">
            <div class="notification-icon message">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="notification-content">
              <div class="notification-header">
                <h3 class="notification-title">Interview Confirmation</h3>
                <span class="notification-time">2 days ago</span>
              </div>
              <p class="notification-message">John Doe has confirmed the interview scheduled for May 22, 2023 at 10:00 AM.</p>
              <div class="notification-actions">
                <button class="notification-action-btn"><i class="fas fa-calendar-alt"></i> View Schedule</button>
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
      // Filter notifications
      const filterSelect = document.querySelector('.filter-select');
      const notificationItems = document.querySelectorAll('.notification-item');
      
      filterSelect.addEventListener('change', function() {
        const filter = this.value;
        
        notificationItems.forEach(item => {
          if (filter === 'all') {
            item.style.display = 'flex';
          } else if (filter === 'unread' && item.classList.contains('unread')) {
            item.style.display = 'flex';
          } else if (filter === 'applications' && item.querySelector('.notification-icon.application')) {
            item.style.display = 'flex';
          } else if (filter === 'messages' && item.querySelector('.notification-icon.message')) {
            item.style.display = 'flex';
          } else if (filter === 'system' && item.querySelector('.notification-icon.system')) {
            item.style.display = 'flex';
          } else {
            item.style.display = 'none';
          }
        });
      });
      
      // Mark individual notification as read
      const markReadButtons = document.querySelectorAll('.notification-action-btn:nth-child(2)');
      
      markReadButtons.forEach(button => {
        button.addEventListener('click', function() {
          const notificationItem = this.closest('.notification-item');
          notificationItem.classList.remove('unread');
          this.style.display = 'none';
          
          // Update notification badge count
          updateNotificationBadge();
        });
      });
      
      // Mark all as read
      const markAllReadBtn = document.querySelector('.mark-all-read-btn');
      
      markAllReadBtn.addEventListener('click', function() {
        const unreadItems = document.querySelectorAll('.notification-item.unread');
        
        unreadItems.forEach(item => {
          item.classList.remove('unread');
          const markReadBtn = item.querySelector('.notification-action-btn:nth-child(2)');
          if (markReadBtn) {
            markReadBtn.style.display = 'none';
          }
        });
        
        // Update notification badge count
        updateNotificationBadge();
        
        Swal.fire(
          'Marked as Read',
          'All notifications have been marked as read.',
          'success'
        );
      });
      
      // Function to update notification badge count
      function updateNotificationBadge() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        const badge = document.querySelector('.notification-badge');
        
        badge.textContent = unreadCount;
        
        if (unreadCount === 0) {
          badge.style.display = 'none';
        } else {
          badge.style.display = 'flex';
        }
      }
    });
  </script>
</body>

</html>