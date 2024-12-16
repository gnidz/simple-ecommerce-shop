<?php
// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Simple validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        try {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $name, $email, $message);
            if ($stmt->execute()) {
                $success = 'Thank you for contacting us! We will get back to you soon.';
            } else {
                $error = 'Failed to submit your message. Please try again later.';
            }
        } catch (Exception $e) {
            $error = 'An error occurred: ' . $e->getMessage();
        }
    }
} else {
    $error = 'Invalid request method.';
}

// Redirect back to the contact page with success or error message
if (isset($success)) {
    header('Location: contact.php?success=' . urlencode($success));
    exit();
} elseif (isset($error)) {
    header('Location: contact.php?error=' . urlencode($error));
    exit();
}
?>
