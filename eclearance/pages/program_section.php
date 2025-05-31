<?php
session_start();
include '../includes/db_connect.php';

// Fetch programs for display
$program_query = "SELECT * FROM programs";
$program_result = $conn->query($program_query);

// Fetch sections for display
$section_query = "SELECT * FROM sections";
$section_result = $conn->query($section_query);

// Fetch levels for dropdown
$level_query = "SELECT * FROM levels";
$level_result = $conn->query($level_query);

// Search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $program_sql = "SELECT * FROM programs WHERE 
                   ProgramCode LIKE '%$search_query%' OR 
                   ProgramTitle LIKE '%$search_query%'";
    $section_sql = "SELECT * FROM sections WHERE 
                   SectionCode LIKE '%$search_query%' OR 
                   SectionTitle LIKE '%$search_query%'";
} else {
    $program_sql = "SELECT * FROM programs";
    $section_sql = "SELECT * FROM sections";
}

$program_result = $conn->query($program_sql);
$section_result = $conn->query($section_sql);

// Program CRUD Operations
if (isset($_POST['add_program'])) {
    try {
        // Validate required fields
        if (empty($_POST['program_code']) || empty($_POST['program_name'])) {
            throw new Exception("Please fill in all required fields.");
        }

        $program_code = $conn->real_escape_string($_POST['program_code']);
        $program_name = $conn->real_escape_string($_POST['program_name']);

        // Check if program code already exists
        $check_query = "SELECT * FROM programs WHERE ProgramCode = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking existing program.");
        }
        $stmt->bind_param("s", $program_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Program code already exists!");
        }
        $stmt->close();

        // Insert new program
        $insert_query = "INSERT INTO programs (ProgramCode, ProgramName) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing insert query.");
        }
        
        $stmt->bind_param("ss", $program_code, $program_name);
        
        if (!$stmt->execute()) {
            throw new Exception("Error adding program: " . $stmt->error);
        }
        
        $message = "Program added successfully!";
        $message_type = "success";
        
    } catch (Exception $e) {
        error_log("Program management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

if (isset($_POST['edit_program'])) {
    try {
        // Validate required fields
        if (empty($_POST['program_code']) || empty($_POST['program_name'])) {
            throw new Exception("Please fill in all required fields.");
        }

        $program_code = $conn->real_escape_string($_POST['program_code']);
        $program_name = $conn->real_escape_string($_POST['program_name']);
        
        // Check if program exists
        $check_query = "SELECT * FROM programs WHERE ProgramCode = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking program existence.");
        }
        $stmt->bind_param("s", $program_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows == 0) {
            throw new Exception("Program not found.");
        }
        $stmt->close();

        // Update program
        $update_query = "UPDATE programs SET ProgramName = ? WHERE ProgramCode = ?";
        $stmt = $conn->prepare($update_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing update query.");
        }
        $stmt->bind_param("ss", $program_name, $program_code);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating program: " . $stmt->error);
        }
        
        $message = "Program updated successfully!";
        $message_type = "success";
        
    } catch (Exception $e) {
        error_log("Program management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

if (isset($_POST['delete_program'])) {
    try {
        if (empty($_POST['program_code'])) {
            throw new Exception("Invalid program selected for deletion.");
        }

        $program_code = $conn->real_escape_string($_POST['program_code']);
        
        // Check if program exists before deletion
        $check_query = "SELECT * FROM programs WHERE ProgramCode = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking program existence.");
        }
        $stmt->bind_param("s", $program_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows == 0) {
            throw new Exception("Program not found.");
        }
        $stmt->close();

        // Check if program is in use
        $check_usage_query = "SELECT * FROM students WHERE Program = ?";
        $stmt = $conn->prepare($check_usage_query);
        if (!$stmt) {
            throw new Exception("Database error while checking program usage.");
        }
        $stmt->bind_param("s", $program_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Cannot delete program: It is currently in use by students.");
        }
        $stmt->close();

        // Delete program
        $delete_query = "DELETE FROM programs WHERE ProgramCode = ?";
        $stmt = $conn->prepare($delete_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing delete query.");
        }
        $stmt->bind_param("s", $program_code);
        
        if (!$stmt->execute()) {
            throw new Exception("Error deleting program: " . $stmt->error);
        }
        
        $message = "Program deleted successfully!";
        $message_type = "success";
        
    } catch (Exception $e) {
        error_log("Program management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// Section CRUD Operations
if (isset($_POST['add_section'])) {
    try {
        // Validate required fields
        if (empty($_POST['section_code']) || empty($_POST['section_name']) || empty($_POST['year_level'])) {
            throw new Exception("Please fill in all required fields.");
        }

        $section_code = $conn->real_escape_string($_POST['section_code']);
        $section_name = $conn->real_escape_string($_POST['section_name']);
        $year_level = intval($_POST['year_level']);

        // Check if section code already exists
        $check_query = "SELECT * FROM sections WHERE SectionCode = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking existing section.");
        }
        $stmt->bind_param("s", $section_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Section code already exists!");
        }
        $stmt->close();

        // Insert new section
        $insert_query = "INSERT INTO sections (SectionCode, SectionTitle, YearLevel) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing insert query.");
        }
        
        $stmt->bind_param("ssi", $section_code, $section_name, $year_level);
        
        if (!$stmt->execute()) {
            throw new Exception("Error adding section: " . $stmt->error);
        }
        
        $message = "Section added successfully!";
        $message_type = "success";
        
    } catch (Exception $e) {
        error_log("Section management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

if (isset($_POST['edit_section'])) {
    try {
        // Validate required fields
        if (empty($_POST['section_code']) || empty($_POST['section_name']) || empty($_POST['year_level'])) {
            throw new Exception("Please fill in all required fields.");
        }

        $section_code = $conn->real_escape_string($_POST['section_code']);
        $section_name = $conn->real_escape_string($_POST['section_name']);
        $year_level = intval($_POST['year_level']);
        
        // Check if section exists
        $check_query = "SELECT * FROM sections WHERE SectionCode = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking section existence.");
        }
        $stmt->bind_param("s", $section_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows == 0) {
            throw new Exception("Section not found.");
        }
        $stmt->close();

        // Update section
        $update_query = "UPDATE sections SET SectionTitle = ?, YearLevel = ? WHERE SectionCode = ?";
        $stmt = $conn->prepare($update_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing update query.");
        }
        $stmt->bind_param("sis", $section_name, $year_level, $section_code);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating section: " . $stmt->error);
        }
        
        $message = "Section updated successfully!";
        $message_type = "success";
        
    } catch (Exception $e) {
        error_log("Section management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

if (isset($_POST['delete_section'])) {
    try {
        if (empty($_POST['section_code'])) {
            throw new Exception("Invalid section selected for deletion.");
        }

        $section_code = $conn->real_escape_string($_POST['section_code']);
        
        // Check if section exists before deletion
        $check_query = "SELECT * FROM sections WHERE SectionCode = ?";
        $stmt = $conn->prepare($check_query);
        if (!$stmt) {
            throw new Exception("Database error while checking section existence.");
        }
        $stmt->bind_param("s", $section_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows == 0) {
            throw new Exception("Section not found.");
        }
        $stmt->close();

        // Check if section is in use
        $check_usage_query = "SELECT * FROM students WHERE SectionCode = ?";
        $stmt = $conn->prepare($check_usage_query);
        if (!$stmt) {
            throw new Exception("Database error while checking section usage.");
        }
        $stmt->bind_param("s", $section_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Cannot delete section: It is currently in use by students.");
        }
        $stmt->close();

        // Delete section
        $delete_query = "DELETE FROM sections WHERE SectionCode = ?";
        $stmt = $conn->prepare($delete_query);
        if (!$stmt) {
            throw new Exception("Database error while preparing delete query.");
        }
        $stmt->bind_param("s", $section_code);
        
        if (!$stmt->execute()) {
            throw new Exception("Error deleting section: " . $stmt->error);
        }
        
        $message = "Section deleted successfully!";
        $message_type = "success";
        
    } catch (Exception $e) {
        error_log("Section management error: " . $e->getMessage());
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// Fetch programs for section dropdown
$programs_for_section = $conn->query("SELECT * FROM programs");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<title>Program & Section Management</title>
<style>
    /* Reuse the same styles from student_management.php */
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

    .sidebar li.active a {
        background-color: rgba(255,255,255,0.2);
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

    .panel {
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .panel h3 {
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

    .form-buttons button[name="add_program"],
    .form-buttons button[name="add_section"] {
        background-color: #28a745;
    }

    .form-buttons button[name="edit_program"],
    .form-buttons button[name="edit_section"] {
        background-color: #17a2b8;
    }

    .form-buttons button[name="delete_program"],
    .form-buttons button[name="delete_section"] {
        background-color: #dc3545;
    }

    .form-buttons button[type="button"] {
        background-color: #6c757d;
    }

    .form-buttons button:hover {
        opacity: 0.9;
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

    .list-container h3 {
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

    .tab-container {
        display: flex;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }

    .tab {
        padding: 10px 20px;
        cursor: pointer;
        background-color: #f1f1f1;
        border: none;
        outline: none;
        transition: 0.3s;
        font-size: 14px;
        border-radius: 6px 6px 0 0;
        margin-right: 5px;
    }

    .tab.active {
        background-color: #343079;
        color: white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>
<script>
    function selectProgram(programCode, programTitle) {
        document.getElementById('program_code').value = programCode;
        document.getElementById('original_code').value = programCode;
        document.getElementById('program_title').value = programTitle;
    }

    function selectSection(sectionCode, sectionTitle, yearLevel) {
        document.getElementById('section_code').value = sectionCode;
        document.getElementById('original_section_code').value = sectionCode;
        document.getElementById('section_title').value = sectionTitle;
        document.getElementById('year_level').value = yearLevel;
    }

    function clearForm(formType) {
        if (formType === 'program') {
            document.getElementById('program_code').value = '';
            document.getElementById('original_code').value = '';
            document.getElementById('program_title').value = '';
        } else if (formType === 'section') {
            document.getElementById('section_code').value = '';
            document.getElementById('original_section_code').value = '';
            document.getElementById('section_title').value = '';
            document.getElementById('year_level').value = '';
        }
    }

    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        
        tablinks = document.getElementsByClassName("tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
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
        <li><a href="staff_management.php"><i class="fas fa-users-cog icon"></i> Staff Management</a></li>
        <li class="active"><a href="program_section.php"><i class="fas fa-chalkboard-teacher icon"></i> Program & Section</a></li>
        <li><a href="academicyear.php"><i class="fas fa-calendar-alt icon"></i> Academic Year</a></li>
        <li><a href="registration_requests.php"><i class="fas fa-user-plus icon"></i> Registration Requests</a></li>
        <li class="logout"><a href="../includes/logout.php"><i class="fas fa-sign-out-alt icon"></i> Logout</a></li>
    </ul>
</nav>
<div class="container">
    <!-- Tab Navigation -->
    <div class="tab-container">
        <button class="tab active" onclick="openTab(event, 'program-tab')">Programs</button>
        <button class="tab" onclick="openTab(event, 'section-tab')">Sections</button>
    </div>

    <!-- Search Bar -->
    <div class="search-container">
        <form method="POST" class="search-form">
            <div class="search-input-group">
                <input type="text" name="search_query" placeholder="Search programs or sections..." 
                       value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" name="search" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
                <?php if (!empty($search_query)): ?>
                    <a href="program_section.php" class="clear-search-button" title="Clear search">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Program Tab Content -->
    <div id="program-tab" class="tab-content active">
        <!-- Program Form -->
        <div class="panel">
            <h3>Program Information</h3>
            <form method="POST">
                <input type="hidden" id="original_code" name="original_code">
                <div class="form-row">
                    <div class="form-group">
                        <label>Program Code</label>
                        <input type="text" id="program_code" name="program_code" required>
                    </div>
                    <div class="form-group">
                        <label>Program Title</label>
                        <input type="text" id="program_title" name="program_title" required>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" name="add_program">Add</button>
                    <button type="submit" name="edit_program">Edit</button>
                    <button type="submit" name="delete_program" onclick="return confirm('Are you sure you want to delete this program?');">Delete</button>
                    <button type="button" onclick="clearForm('program')">Clear</button>
                </div>
            </form>
        </div>

        <!-- Program List -->
        <div class="panel">
            <h3>Program List</h3>
            <div class="scrollable-content">
                <table>
                    <thead>
                        <tr>
                            <th>Program Code</th>
                            <th>Program Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($program = $program_result->fetch_assoc()) { ?>
                            <tr onclick="selectProgram('<?php echo $program['ProgramCode']; ?>', '<?php echo $program['ProgramTitle']; ?>')">
                                <td><?php echo $program['ProgramCode']; ?></td>
                                <td><?php echo $program['ProgramTitle']; ?></td>
                                <td><button type="button">Select</button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Tab Content -->
    <div id="section-tab" class="tab-content">
        <!-- Section Form -->
        <div class="panel">
            <h3>Section Information</h3>
            <form method="POST">
                <input type="hidden" id="original_section_code" name="original_code">
                <div class="form-row">
                    <div class="form-group">
                        <label>Section Code</label>
                        <input type="text" id="section_code" name="section_code" required>
                    </div>
                    <div class="form-group">
                        <label>Section Title</label>
                        <input type="text" id="section_title" name="section_title" required>
                    </div>
                    <div class="form-group">
                        <label>Year Level</label>
                        <select id="year_level" name="year_level" required>
                            <option value="">Select Year Level</option>
                            <?php 
                            $level_result->data_seek(0);
                            while ($level = $level_result->fetch_assoc()) { ?>
                                <option value="<?php echo $level['LevelID']; ?>"><?php echo $level['LevelName']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" name="add_section">Add</button>
                    <button type="submit" name="edit_section">Edit</button>
                    <button type="submit" name="delete_section" onclick="return confirm('Are you sure you want to delete this section?');">Delete</button>
                    <button type="button" onclick="clearForm('section')">Clear</button>
                </div>
            </form>
        </div>

        <!-- Section List -->
        <div class="panel">
            <h3>Section List</h3>
            <div class="scrollable-content">
                <table>
                    <thead>
                        <tr>
                            <th>Section Code</th>
                            <th>Section Title</th>
                            <th>Year Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Reset pointer for section result
                        $section_result->data_seek(0);
                        while ($section = $section_result->fetch_assoc()) { 
                            // Get year level name
                            $level_query = "SELECT LevelName FROM levels WHERE LevelID = " . $section['YearLevel'];
                            $level_result = $conn->query($level_query);
                            $level_name = $level_result->fetch_assoc()['LevelName'];
                        ?>
                            <tr onclick="selectSection('<?php echo $section['SectionCode']; ?>', '<?php echo $section['SectionTitle']; ?>', <?php echo $section['YearLevel']; ?>)">
                                <td><?php echo $section['SectionCode']; ?></td>
                                <td><?php echo $section['SectionTitle']; ?></td>
                                <td><?php echo $level_name; ?></td>
                                <td><button type="button">Select</button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>