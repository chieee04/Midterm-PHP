<?php
session_start(); // Start the session to hold subjects

$baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/harzwel/Midterm-PHP";

// Check if the form was submitted and if fields are empty
$showError = false;
$errorMessages = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate subject code and name
    if (empty($_POST['subject_code'])) {
        $showError = true;
        $errorMessages[] = 'Subject Code is required.';
    } elseif (!is_numeric($_POST['subject_code'])) {
        $showError = true;
        $errorMessages[] = 'Subject Code must be a number.';
    } else {
        // Check if the subject code already exists in the session
        if (isset($_SESSION['subjects'])) {
            foreach ($_SESSION['subjects'] as $subject) {
                if ($subject['code'] == $_POST['subject_code']) {
                    $showError = true;
                    $errorMessages[] = ' Duplicate Subject Code.';
                    break;
                }
            }
        }
    }

    if (empty($_POST['subject_name'])) {
        $showError = true;
        $errorMessages[] = 'Subject Name is required.';
    }

    // Add the subject to the session if no errors
    if (!$showError) {
        // Initialize subjects if not already set
        if (!isset($_SESSION['subjects'])) {
            $_SESSION['subjects'] = [];
        }

        // Add the new subject
        $subject = [
            'code' => $_POST['subject_code'],
            'name' => $_POST['subject_name']
        ];
        $_SESSION['subjects'][] = $subject;
    }
}

// Handle Edit and Delete actions
if (isset($_GET['action']) && isset($_GET['index'])) {
    $index = $_GET['index'];
    if ($_GET['action'] == 'delete') {
        // Delete subject at the given index
        unset($_SESSION['subjects'][$index]);
        $_SESSION['subjects'] = array_values($_SESSION['subjects']); // Re-index array after deletion
    } elseif ($_GET['action'] == 'edit') {
        // This can be expanded to edit functionality if needed
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Center the main content vertically and horizontally */
        .center-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Set a max-width for the form to control width on larger screens */
        .content-wrapper {
            max-width: 1200px;
            width: 100%;
        }

        /* Breadcrumb container with gray background, padding, and increased height */
        .breadcrumb-container {
            background-color: #f1f1f1;
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container center-container">
        <div class="content-wrapper">
            <!-- Breadcrumb Navigation inside a styled div -->
            <div class="breadcrumb-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo $baseUrl . '/dashboard.php'; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
                    </ol>
                </nav>
            </div>

            <!-- Show Error Message if Fields are Empty -->
            <?php if ($showError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Error:</strong> <!-- Bold text for "System Error" -->
                    <ul class="mb-0">
                        <?php foreach ($errorMessages as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Form for Adding a New Subject -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Add a New Subject</h4>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subjectCode" name="subject_code" placeholder="Enter Subject Code" >
                        </div>
                        <div class="mb-3">
                            <label for="subjectName" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="subjectName" name="subject_name" placeholder="Enter Subject Name" >
                        </div>
                        <button type="submit" class="btn btn-primary">Add Subject</button>
                    </form>
                </div>
            </div>

            <!-- Subject List Table -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Subject List</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody class="subject-tbody">
                            <?php if (!empty($_SESSION['subjects'])): ?>
                                <?php foreach ($_SESSION['subjects'] as $index => $subject): ?>
                                    <tr>
                                        <td><?php echo $subject['code']; ?></td>
                                        <td><?php echo $subject['name']; ?></td>
                                        <td>
                                            <a href="?action=edit&index=<?php echo $index; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="?action=delete&index=<?php echo $index; ?>" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No subject found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
