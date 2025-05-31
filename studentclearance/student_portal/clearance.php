<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db_connect.php';
redirectIfNotLoggedIn();

$studentNo = $_SESSION['student_id'];

// Get clearance status with department information
$clearanceQuery = $conn->prepare("
    SELECT cr.requirement_name, cr.description as general_description, 
           srd.description as student_description, scs.status, scs.updated_at, 
           d.DepartmentName, scs.approved_by
    FROM student_clearance_status scs
    JOIN clearance_requirements cr ON scs.requirement_id = cr.requirement_id
    LEFT JOIN student_requirement_descriptions srd ON scs.requirement_id = srd.requirement_id AND scs.studentNo = srd.studentNo
    JOIN departments d ON cr.requirement_id = d.DepartmentID
    WHERE scs.studentNo = ?
");
$clearanceQuery->bind_param("s", $studentNo);
$clearanceQuery->execute();
$clearanceStatus = $clearanceQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance Status</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="student-container">
        <nav class="sidebar">
            <div class="logo-container">
                <img src="../dyci_logo.png" alt="College Logo" class="logo">
                <div class="logo-text">
                    <h2>DR. YANGA'S COLLEGES INC.</h2>
                    <p>Student Portal</p>
                </div>
            </div>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home icon"></i> Dashboard</a></li>
                <li class="active"><a href="clearance.php"><i class="fas fa-file-alt icon"></i> Clearance Status</a></li>
                <li><a href="profile.php"><i class="fas fa-user icon"></i> My Profile</a></li>
                <li class="logout"><a href="logout.php"><i class="fas fa-sign-out-alt icon"></i> Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <header>
                <h1>Clearance Status</h1>
                <div class="date-display"><?php echo date('F j, Y'); ?></div>
            </header>

            <div class="clearance-table">
                <table>
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Requirement</th>
                            <th>General Description</th>
                            <th>Student Description</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody id="clearance-body">
                        <!-- Data will be loaded here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function fetchClearance() {
        fetch('fetch_clearance.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('clearance-body');
                tbody.innerHTML = '';
                data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.DepartmentName}</td>
                        <td>${row.requirement_name}</td>
                        <td>${row.general_description}</td>
                        <td>${row.student_description || 'No specific requirements'}</td>
                        <td class="status-${row.status.toLowerCase()}">${row.status}</td>
                        <td>${row.approved_by || '-'}</td>
                        <td>${row.updated_at}</td>
                    `;
                    tbody.appendChild(tr);
                });
            });
    }

    // Fetch every 5 seconds
    setInterval(fetchClearance, 1000);
    window.onload = fetchClearance;
    </script>
</body>
</html>