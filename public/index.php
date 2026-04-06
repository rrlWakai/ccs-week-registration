<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS Week 2026</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<div class="header">
    <div class="container header-flex">
        <a href="#" class="brand"><i class="fa fa-code"></i> CCS Week</a>
        <nav class="nav">
            <a href="#about">About</a>
            <a href="#features">Features</a>
            <a href="#developers">Developers</a>
            <a href="#contact">Contact</a>
           <button onclick="window.location.href='register.php'" class="btn btn-primary"><i class="fa fa-user-plus"></i> Register Now</button> 
        </nav>
    </div>
</div>

<!-- HERO -->
<section class="section section-white">
    <div class="container grid-2">
        <div>
            <div class="hero-badge"><i class="fa fa-calendar-star"></i> CCS Week 2026</div>
            <h1 class="hero-title">Compete. Connect.<br><span>Celebrate.</span></h1>
            <p class="hero-desc">Register for sports and esports events at CCS Week 2026. Showcase your talent and represent your course.</p>
            <div class="hero-actions">
                <a href="register.php" class="btn btn-primary"><i class="fa fa-user-plus"></i> Register Now</a>
                <a href="#about" class="btn btn-outline">Learn More</a>
            </div>
        </div>
        <img src="../assets/images/logo.jpg" class="hero-img" alt="CCS Week">
    </div>
</section>

<!-- ABOUT -->
<section id="about" class="section section-alt">
    <div class="container grid-2">
        <img src="../assets/images/about.jpg" class="hero-img" alt="About">
        <div>
            <div class="section-tag"><i class="fa fa-info-circle"></i> About</div>
            <h2 style="text-align:left;">About the System</h2>
            <p style="font-size:16px; line-height:1.8;">The CCS Week Registration System streamlines event sign-ups for the College of Computer Studies' annual celebration. From sports to esports, manage everything in one place with full admin control and real-time tracking.</p>
            <br>
            <div style="display:flex; gap:24px; margin-top:16px;">
                <div><strong style="font-size:24px; color:var(--primary);">5+</strong><br><small style="color:var(--text-muted);">Events</small></div>
                <div><strong style="font-size:24px; color:var(--primary);">100+</strong><br><small style="color:var(--text-muted);">Participants</small></div>
                <div><strong style="font-size:24px; color:var(--primary);">2</strong><br><small style="color:var(--text-muted);">Categories</small></div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="section section-white">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fa fa-star"></i> Features</div>
            <h2>Everything You Need</h2>
            <p>A complete registration management system built for CCS Week events.</p>
        </div>
        <div class="grid-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-user-plus"></i></div>
                <h3>Registration</h3>
                <p>Quick and easy participant sign-up for any event or activity.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-database"></i></div>
                <h3>CRUD Management</h3>
                <p>Full create, read, update, and delete operations for all records.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-search"></i></div>
                <h3>Smart Search</h3>
                <p>Filter participants by name, course, activity, or category instantly.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-lock"></i></div>
                <h3>Admin Auth</h3>
                <p>Secure login system protecting all administrative functions.</p>
            </div>
        </div>
    </div>
</section>

<!-- DEVELOPERS -->
<section id="developers" class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fa fa-users"></i> Team</div>
            <h2>Meet the Developers</h2>
            <p>The team behind CCS Week Registration System.</p>
        </div>
        <div class="grid-3">
            <div class="dev-card" style="background-image: url('../assets/images/dev1.jpg')">
                <div class="dev-initials">RL</div>
                <div class="dev-content">
                    <h3>Rhen-Rhen A. Lumbo</h3>
                    <p><i class="fa fa-code" style="margin-right:5px;"></i>Full Stack Developer</p>
                </div>
            </div>
            <div class="dev-card" style="background-image: url('../assets/images/dev2.jpg')">
                <div class="dev-initials">JA</div>
                <div class="dev-content">
                    <h3>Justine James A. Abejuela</h3>
                    <p><i class="fa fa-palette" style="margin-right:5px;"></i>Frontend Developer</p>
                </div>
            </div>
            <div class="dev-card" style="background-image: url('../assets/images/dev3.jpg')">
                <div class="dev-initials">MA</div>
                <div class="dev-content">
                    <h3>Mike Jaillie P. Ananca</h3>
                    <p><i class="fa fa-server" style="margin-right:5px;"></i>Database Engineer</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT - 2 COLUMN LAYOUT -->
<section id="contact" class="section section-white">
    <div class="container">
        <div class="contact-grid">
            <!-- LEFT: Form -->
            <div class="contact-form-card">
                <h3><i class="fa fa-paper-plane" style="color:var(--primary); margin-right:10px;"></i>Send Us a Message</h3>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="../actions/send_contact.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required placeholder="Your name">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" required placeholder="09XX-XXX-XXXX">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="you@email.com">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required placeholder="Your message or question..." rows="5" style="resize:vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        <i class="fa fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

            <!-- RIGHT: Info -->
            <div>
                <div class="section-tag" style="margin-bottom:16px;"><i class="fa fa-map-marker-alt"></i> Get in Touch</div>
                <h2 class="contact-info-title" style="text-align:left;">We'd Love to<br>Hear From You</h2>
                <p class="contact-info-desc">Have questions about registration, events, or the system? Reach out and our team will get back to you promptly.</p>

                <div class="contact-info-items">
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa fa-envelope"></i></div>
                        <div class="contact-info-text">
                            <h4>Email</h4>
                            <p>ccsweek@university.edu</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa fa-phone"></i></div>
                        <div class="contact-info-text">
                            <h4>Phone</h4>
                            <p>(082) 123-4567</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa fa-map-marker-alt"></i></div>
                        <div class="contact-info-text">
                            <h4>Location</h4>
                            <p>CCS Building, University Campus</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa fa-clock"></i></div>
                        <div class="contact-info-text">
                            <h4>Office Hours</h4>
                            <p>Mon&ndash;Fri, 8:00 AM &ndash; 5:00 PM</p>
                        </div>
                    </div>
                </div>

                <div class="contact-stats">
                    <div class="stat-item">
                        <span class="stat-num">100+</span>
                        <span class="stat-label">Registered</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">5+</span>
                        <span class="stat-label">Events</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">2</span>
                        <span class="stat-label">Categories</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">24h</span>
                        <span class="stat-label">Response</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <p>&copy; 2026 CCS Week Registration System &mdash; College of Computer Studies</p>
</footer>

</body>
</html>
