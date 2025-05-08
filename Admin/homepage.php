<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isAdmin()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}

$database = new Database();
$db = $database->getConnect();

$user = new Users($db); 
$users = $user->getAllUsers(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - User Management</title>
    <link rel="stylesheet" href="../Layouts/employer.css">
    <link rel="stylesheet" href="../Layouts/user_management.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-container">
                <div class="logo">
                    <img src="../Layouts/logo.png" alt="Logo">
                </div>
                <h3>Career Hub</h3>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="homepage.php"><i class="fas fa-users"></i> Users</a>
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
                    <form id="search-form">
                        <input type="text" id="search-input" placeholder="Search users...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="../Layouts/emp_icon.png" alt="Admin Profile">
                        <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin'; ?></span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="content-header">
                    <h1><i class="fas fa-users"></i> User Management</h1>
                    <div class="header-actions">
                        <div class="filter-dropdown">
                            <select id="role-filter">
                                <option value="all">All Roles</option>
                                <option value="jobseeker">Jobseekers</option>
                                <option value="employer">Employers</option>
                                <option value="admin">Admins</option>
                            </select>
                        </div>
                        <div class="filter-dropdown">
                            <select id="status-filter">
                                <option value="all">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Users List -->
                <div class="users-list">
                    <div class="users-table-container">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($users)) {
                                    foreach ($users as $row) {
                                        $userId = $row['user_id'];
                                        $status = $row['status']; // checks if user is active or suspended
                                        $status = strtolower($row['status']);
                                        $statusClass = $status === 'active' ? 'status-active' : 'status-suspended';
                                ?>
                                        <tr data-role="<?php echo strtolower($row['roles']); ?>" data-status="<?php echo $status; ?>">
                                            <td class="user-name">
                                                <div class="user-info">
                                                    <div class="user-avatar">
                                                        <img src="../Layouts/user_icon.png" alt="User Avatar">
                                                    </div>
                                                    <div>
                                                        <h4><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h4>
                                                        <span class="user-id">ID: <?php echo $userId; ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                                            <td>
                                                <span class="role-badge <?php echo strtolower($row['roles']); ?>">
                                                    <?php echo htmlspecialchars($row['roles']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <?php if ($status === 'active') { ?>
                                                        <form action="update_user_status.php" method="POST" class="status-change-form">
                                                            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                                                            <input type="hidden" name="new_status" value="suspended">
                                                            <button type="submit" class="action-btn suspend-btn">
                                                                Suspend
                                                            </button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <form action="update_user_status.php" method="POST" class="status-change-form">
                                                            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                                                            <input type="hidden" name="new_status" value="active">
                                                            <button type="submit" class="action-btn activate-btn">
                                                                <i class="fas fa-check-circle"></i> Activate
                                                            </button>
                                                        </form>
                                                    <?php } ?>

                                                    <button class="action-btn view-btn" data-user-id="<?php echo $userId; ?>">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="no-data">No users found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Role filter functionality
            const roleFilter = document.getElementById('role-filter');
            roleFilter.addEventListener('change', filterUsers);

            // Status filter functionality
            const statusFilter = document.getElementById('status-filter');
            statusFilter.addEventListener('change', filterUsers);

            // Search functionality
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                filterUsers();
            });

            function filterUsers() {
                const roleValue = roleFilter.value;
                const statusValue = statusFilter.value;
                const searchValue = searchInput.value.toLowerCase();

                const rows = document.querySelectorAll('.users-table tbody tr');

                rows.forEach(row => {
                    const role = row.getAttribute('data-role');
                    const status = row.getAttribute('data-status');
                    const name = row.querySelector('.user-name h4').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                    const roleMatch = roleValue === 'all' || role === roleValue;
                    const statusMatch = statusValue === 'all' || status === statusValue;
                    const searchMatch = name.includes(searchValue) || email.includes(searchValue);

                    if (roleMatch && statusMatch && searchMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Status change confirmation
            const statusForms = document.querySelectorAll('.status-change-form');

            statusForms.forEach(form => {
                form.addEventListener('submit', function(e) {   
                    e.preventDefault();

                    const userId = this.querySelector('input[name="user_id"]').value;
                    const newStatus = this.querySelector('input[name="new_status"]').value.toLowerCase();

                    let title, text, confirmButtonText, icon;

                    if (newStatus === 'suspended') {
                        title = 'Suspend User Account';
                        text = 'Are you sure you want to suspend this user? They will not be able to access the system until reactivated.';
                        confirmButtonText = 'Yes, Suspend Account';
                        icon = 'warning';
                    } else if (newStatus === 'active') {
                        title = 'Activate User Account';
                        text = 'Are you sure you want to activate this user?';
                        confirmButtonText = 'Yes, Activate Account';
                        icon = 'question';
                    }

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#c41e3a',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: confirmButtonText
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // View user details
            const viewButtons = document.querySelectorAll('.view-btn');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    // Redirect to user details page or show modal
                    // window.location.href = `user_details.php?id=${userId}`;

                    // For demo purposes, show an alert
                    Swal.fire({
                        title: 'User Details',
                        text: `Viewing details for user ID: ${userId}`,
                        icon: 'info',
                        confirmButtonColor: '#c41e3a'
                    });
                });
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