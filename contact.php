<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .contact-header {
            background: linear-gradient(135deg, #7f7fd5, #86a8e7, #91eae4);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        .contact-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .contact-header p {
            font-size: 1.2rem;
        }
        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }
        .contact-info {
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<!-- Display Contact Header  -->
<div class="contact-header">
    <h1>Contact Us</h1>
    <p>We’d love to hear from you. Get in touch with us today!</p>
</div>

<div class="container my-5">

    <!-- Display success or error message -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="row gy-4">

        <!-- Contact Form -->
        <div class="col-lg-6">
            <div class="contact-info">
                <h3>Send Us a Message</h3>
                <form action="submit_contact.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea id="message" name="message" rows="5" class="form-control" placeholder="Enter your message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>

        <!-- Map and Contact Details -->
        <div class="col-lg-6">
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3874.0670041973276!2d100.5659888!3d13.7459797!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29ef0f2d82ecb%3A0xf190ba28a1ce0516!2sFaculty%20of%20Science%20Srinakharinwirot%20University!5e0!3m2!1sen!2sth!4v1699978263917!5m2!1sen!2sth" 
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
            <div class="mt-4">
                <h3>Our Location</h3>
                <p><i class="fas fa-map-marker-alt me-2"></i>Faculty of Science, Srinakharinwirot University, Bangkok, Thailand</p>
                <p><i class="fas fa-phone me-2"></i>+66 2 649 5000</p>
                <p><i class="fas fa-envelope me-2"></i>contact@swu.ac.th</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
