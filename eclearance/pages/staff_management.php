<?php
session_start();
include '../includes/db_connect.php';

// Initialize message variables
$message = '';
$message_type = '';

// Fetch the next available control number
$ctrl_no_query = "SELECT MAX(CtrlNo) AS max_ctrl FROM staff";
$ctrl_no_result = $conn->query($ctrl_no_query);
$row = $ctrl_no_result->fetch_assoc();
$next_ctrl_no = $row['max_ctrl'] + 1;

// Fetch departments for dropdown
$dept_query = "SELECT * FROM departments"; 
$dept_result = $conn->query($dept_query);
$departments = [];
while ($dept = $dept_result->fetch_assoc()) {
    $departments[] = $dept;
}

// Search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $conn->real_escape_string($_POST['search_query']);
    $sql = "SELECT * FROM staff WHERE 
            StaffID LIKE '%$search_query%' OR 
            Username LIKE '%$search_query%' OR 
            LastName LIKE '%$search_query%' OR 
            FirstName LIKE '%$search_query%' OR 
            Department LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM staff";
}

// Add User
if (isset($_POST['add_user'])) {
    try {
        // Validate required fields
        $required_fields = ['staff_id', 'username', 'last_name', 'first_name', 'email', 'password', 'department'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }

    $staff_id = $conn->real_escape_string($_POST['staff_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $department = $conn->real_escape_string($_POST['department']);

    // Check if staff ID or username already exists
        $check_query = "SELECT * FROM staff WHERE StaffID = ? OR Username = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking existing user.");
        }
        $stmt->bind_param("ss", $staff_id, $username);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Staff ID or Username already exists!");
        }
        $stmt->close();

        // Insert new staff member
        $insert_query = "INSERT INTO staff (CtrlNo, StaffID, Username, LastName, FirstName, Mname, Email, PasswordHash, AccountType, Department) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Staff', ?)";
        $stmt = $conn->prepare($insert_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing insert query.");
        }
        
        $stmt->bind_param("issssssss", $next_ctrl_no, $staff_id, $username, $last_name, $first_name, $middle_name, $email, $password, $department);
        
        if (!$stmt->execute()) {
            throw new Exception("Error adding user: " . $stmt->error);
        }
        
            $message = "User added successfully!";
            $message_type = "success";
        
            // Refresh the control number
            $ctrl_no_result = $conn->query("SELECT MAX(CtrlNo) AS max_ctrl FROM staff");
            $row = $ctrl_no_result->fetch_assoc();
            $next_ctrl_no = $row['max_ctrl'] + 1;
        
    } catch (Exception $e) {
        error_log("Staff management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
            $message_type = "error";
    }
}

// Edit User
if (isset($_POST['edit_user'])) {
    try {
        // Validate required fields
        $required_fields = ['ctrl_no', 'staff_id', 'username', 'last_name', 'first_name', 'email', 'department'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }

    $ctrl_no = $conn->real_escape_string($_POST['ctrl_no']);
    $staff_id = $conn->real_escape_string($_POST['staff_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $department = $conn->real_escape_string($_POST['department']);
    
    // Check if staff ID or username already exists (excluding current user)
        $check_query = "SELECT * FROM staff WHERE (StaffID = ? OR Username = ?) AND CtrlNo != ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking existing user.");
        }
        $stmt->bind_param("ssi", $staff_id, $username, $ctrl_no);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Staff ID or Username already exists for another user!");
        }
        $stmt->close();

        // Prepare update query
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $update_query = "UPDATE staff SET 
                            StaffID = ?, Username = ?, LastName = ?, FirstName = ?, 
                            Mname = ?, Email = ?, PasswordHash = ?, Department = ?
                            WHERE CtrlNo = ?";
            $stmt = $conn->prepare($update_query);
            if (!$stmt) {
                throw new Exception("Database error while preparing update query.");
            }
            $stmt->bind_param("ssssssssi", $staff_id, $username, $last_name, $first_name, 
                            $middle_name, $email, $password, $department, $ctrl_no);
        } else {
            $update_query = "UPDATE staff SET 
                            StaffID = ?, Username = ?, LastName = ?, FirstName = ?, 
                            Mname = ?, Email = ?, Department = ?
                            WHERE CtrlNo = ?";
            $stmt = $conn->prepare($update_query);
            if (!$stmt) {
                throw new Exception("Database error while preparing update query.");
            }
            $stmt->bind_param("sssssssi", $staff_id, $username, $last_name, $first_name, 
                            $middle_name, $email, $department, $ctrl_no);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating user: " . $stmt->error);
        }
        
            $message = "User updated successfully!";
            $message_type = "success";
        
    } catch (Exception $e) {
        error_log("Staff management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
            $message_type = "error";
    }
}

// Delete User
if (isset($_POST['delete_user'])) {
    try {
        if (empty($_POST['ctrl_no'])) {
            throw new Exception("Invalid user selected for deletion.");
        }

    $ctrl_no = $conn->real_escape_string($_POST['ctrl_no']);
        
        // Check if user exists before deletion
        $check_query = "SELECT * FROM staff WHERE CtrlNo = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking user existence.");
        }
        $stmt->bind_param("i", $ctrl_no);
        $stmt->execute();
    
        if ($stmt->get_result()->num_rows == 0) {
            throw new Exception("User not found.");
        }
        $stmt->close();

        // Delete user
        $delete_query = "DELETE FROM staff WHERE CtrlNo = ?";
        $stmt = $conn->prepare($delete_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing delete query.");
        }
        $stmt->bind_param("i", $ctrl_no);
        
        if (!$stmt->execute()) {
            throw new Exception("Error deleting user: " . $stmt->error);
        }
        
        $message = "User deleted successfully!";
        $message_type = "success";
        
        // Refresh the control number
        $ctrl_no_result = $conn->query("SELECT MAX(CtrlNo) AS max_ctrl FROM staff");
        $row = $ctrl_no_result->fetch_assoc();
        $next_ctrl_no = $row['max_ctrl'] + 1;
        
    } catch (Exception $e) {
        error_log("Staff management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// Clear Form
if (isset($_POST['clear_form'])) {
    // Reset to add new user mode
    $next_ctrl_no = $conn->query("SELECT MAX(CtrlNo) AS max_ctrl FROM staff")->fetch_assoc()['max_ctrl'] + 1;
}

// Fetch staff users (again after possible changes)
if (!empty($search_query)) {
    $sql = "SELECT * FROM staff WHERE 
            StaffID LIKE '%$search_query%' OR 
            Username LIKE '%$search_query%' OR 
            LastName LIKE '%$search_query%' OR 
            FirstName LIKE '%$search_query%' OR 
            Department LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM staff";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
        color: #333;
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 300px;
        background-color: #343079;
        color: white;
        height: 100vh;
        position: fixed;
        padding: 20px 0;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        overflow-y: auto;
        transition: all 0.3s;
    }

    .logo-container {
        display: flex;
        align-items: center;
        padding: 0 20px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 20px;
    }

    .logo {
        width: 50px;
        height: 50px;
        margin-right: 15px;
    }

    .logo-text h2 {
        font-size: 16px;
        margin: 0 0 5px 0;
        font-weight: 600;
    }

    .logo-text p {
        font-size: 12px;
        margin: 0;
        opacity: 0.8;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar li a {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
        font-size: 15px;
    }

    .sidebar li a:hover {
        background-color: rgba(255,255,255,0.1);
        padding-left: 30px;
    }


        

    

    .sidebar li.logout {
        margin-top: auto;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar .icon {
        margin-right: 15px;
        font-size: 18px;
        width: 20px;
        text-align: center;
    }

    .container {
        flex: 1;
        margin-left: 300px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        box-sizing: border-box;
        overflow-y: auto;
    }

    .user-panel {
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .user-panel h3 {
        margin-top: 0;
        color: #343079;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 5px;
        font-weight: 500;
        color: #555;
    }

    .form-group input,
    .form-group select {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #343079;
        box-shadow: 0 0 0 2px rgba(52, 48, 121, 0.15);
    }

    .form-buttons button {
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
    color: white;
}

.form-buttons button[name="add_user"] {
    background-color: #28a745; /* green */
}

.form-buttons button[name="add_user"]:hover {
    background-color: #218838;
}

.form-buttons button[name="edit_user"] {
    background-color: #17a2b8; /* blue-teal */
}

.form-buttons button[name="edit_user"]:hover {
    background-color: #138496;
}

.form-buttons button[name="delete_user"] {
    background-color: #dc3545; /* red */
}

.form-buttons button[name="delete_user"]:hover {
    background-color: #c82333;
}

.form-buttons button[type="button"] {
    background-color: #6c757d; /* gray */
}

.form-buttons button[type="button"]:hover {
    background-color: #5a6268;
}


    .search-container {
        margin-bottom: 20px;
    }

    .search-form {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-input-group {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .search-input-group input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px 0 0 6px;
        font-size: 14px;
    }

    .search-button,
    .clear-search-button {
        padding: 10px 15px;
        background-color: #343079;
        color: white;
        border: none;
        font-size: 14px;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        transition: 0.3s;
    }

    .search-button:hover,
    .clear-search-button:hover {
        background-color: #2c2765;
    }

    .scrollable-content {
        overflow-x: auto;
    }

    .user-list-container h3 {
        margin-bottom: 10px;
        color: #343079;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    thead th {
        background-color: #343079;
        color: white;
    }

    tbody tr {
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    tbody tr:hover {
        background-color: #f0f0f0;
    }

    tbody td button {
        background-color: #343079;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
    }

    tbody td button:hover {
        background-color: #2c2765;
    }
    
</style>
    <script>
        function selectUser(ctrlNo, staffId, username, lastName, firstName, middleName, email, department) {
            document.getElementById("ctrl_no").value = ctrlNo;
            document.getElementById("staff_id").value = staffId;
            document.getElementById("username").value = username;
            document.getElementById("last_name").value = lastName;
            document.getElementById("first_name").value = firstName;
            document.getElementById("middle_name").value = middleName;
            document.getElementById("email").value = email;
            document.getElementById("department").value = department;
            
            // Change button focus to Edit/Delete
            document.querySelector("button[name='add_user']").classList.remove('active');
            document.querySelector("button[name='edit_user']").classList.add('active');
            document.querySelector("button[name='delete_user']").classList.add('active');
        }
        
        function clearForm() {
            document.getElementById("staff_id").value = '';
            document.getElementById("username").value = '';
            document.getElementById("last_name").value = '';
            document.getElementById("first_name").value = '';
            document.getElementById("middle_name").value = '';
            document.getElementById("email").value = '';
            document.getElementById("department").value = document.getElementById("department").options[0].value;
            document.querySelector("input[name='password']").value = '';
            
            // Reset to add mode
            document.querySelector("button[name='add_user']").classList.add('active');
            document.querySelector("button[name='edit_user']").classList.remove('active');
            document.querySelector("button[name='delete_user']").classList.remove('active');
            
            // Update control number to next available
            document.getElementById("ctrl_no").value = <?php echo $next_ctrl_no; ?>;
        }
        
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?");
        }

        // Add focus effect when search input is clicked
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input-group input');
            
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    this.parentElement.querySelector('.search-button').style.color = '#4a90e2';
                });
                
                searchInput.addEventListener('blur', function() {
                    this.parentElement.querySelector('.search-button').style.color = '#666';
                });
            }
        });
    </script>
</head>
<body>

<nav class="sidebar">
    <div class="logo-container">
        <img src="../assets/dyci_logo.svg" alt="DYCI Logo" class="logo">
        <div class="logo-text">
            <h2>DYCI CampusConnect</h2>
            <p>E-Clearance System</p>
        </div>
    </div>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt icon"></i> Dashboard</a></li>
        <li><a href="eclearance.php"><i class="fas fa-clipboard-check icon"></i> E-Clearance</a></li>
        <li><a href="student_management.php"><i class="fas fa-user-graduate icon"></i> Student Management</a></li>
        <li class="active"><a href="staff_management.php"><i class="fas fa-users-cog icon"></i> Staff Management</a></li>
        <li><a href="program_section.php"><i class="fas fa-chalkboard-teacher icon"></i> Program & Section</a></li>
        <li><a href="academicyear.php"><i class="fas fa-calendar-alt icon"></i> Academic Year</a></li>
        <li><a href="registration_requests.php"><i class="fas fa-user-plus icon"></i> Registration Requests</a></li>
        <li class="logout"><a href="../includes/logout.php"><i class="fas fa-sign-out-alt icon"></i> Logout</a></li>
    </ul>
</nav>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <div class="user-panel">
        <h3>Account Information</h3>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Ctrl No.</label>
                    <input type="text" id="ctrl_no" name="ctrl_no" value="<?php echo $next_ctrl_no; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Staff ID</label>
                    <input type="text" id="staff_id" name="staff_id" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="New Password (Leave blank to keep current)">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Department</label>
                    <select id="department" name="department" required>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['DepartmentName']; ?>"><?php echo $dept['DepartmentName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Account Type</label>
                    <input type="text" name="account_type" value="Staff" readonly>
                </div>
            </div>
            <div class="form-buttons">
                <button type="submit" name="add_user" class="active">Add</button>
                <button type="submit" name="edit_user">Edit</button>
                <button type="submit" name="delete_user" onclick="return confirmDelete();">Delete</button>
                <button type="button" onclick="clearForm();">Clear</button>
            </div>
        </form>
    </div>

    <!-- Search Bar -->
    <div class="search-container">
        <form method="POST" class="search-form">
            <div class="search-input-group">
                <input type="text" name="search_query" placeholder="Search staff by ID, name, username or department..." 
                       value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" name="search" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
                <?php if (!empty($search_query)): ?>
                    <a href="staff_management.php" class="clear-search-button" title="Clear search">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Scrollable Content Area -->
    <div class="scrollable-content">
        <!-- Staff List Table -->
        <div class="user-list-container">
            <h3>Staff List</h3>
            <?php if ($result->num_rows > 0): ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Ctrl No.</th>
                                <th>Staff ID</th>
                                <th>Username</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr onclick="selectUser('<?php echo $row['CtrlNo']; ?>', '<?php echo htmlspecialchars($row['StaffID']); ?>', '<?php echo htmlspecialchars($row['Username']); ?>', '<?php echo htmlspecialchars($row['LastName']); ?>', '<?php echo htmlspecialchars($row['FirstName']); ?>', '<?php echo htmlspecialchars($row['Mname']); ?>', '<?php echo htmlspecialchars($row['Email']); ?>', '<?php echo htmlspecialchars($row['Department']); ?>')">
                                    <td><?php echo $row['CtrlNo']; ?></td>
                                    <td><?php echo htmlspecialchars($row['StaffID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['LastName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Mname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Department']); ?></td>
                                    <td><button type="button" class="select-btn">Select</button></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-results">No staff members found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>