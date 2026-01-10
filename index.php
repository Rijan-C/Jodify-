<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jodify - Nepal's Premier Matchmaking Platform</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #9D2235;
            --secondary: #C9A227;
            --bg: #FAF7F2;
            --text: #2B2B2B;
            --border: #E5E5E5;
            --light-text: #666666;
            --white: #FFFFFF;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background-color: var(--bg);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 32px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #7a1b2a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(157, 34, 53, 0.2);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #b08e22;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(201, 162, 39, 0.2);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-outline:hover {
            background-color: var(--primary);
            color: white;
        }
        .log-links a{
            margin-left: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .log-btn{
            background-color: transparent;
            color: var(--primary);
            padding: 10px 20px;
            box-shadow: 0 5px 15px rgba(201, 162, 39, 0.2); 
            border: 2px solid var(--primary);
            border-radius: 30px;
        }
        .log-btn:hover{
            background-color: var(--primary);
            color: white;
        }
        .reg-btn{
            background-color: var(--primary);
            color: white;
            padding: 10px 20px;
            box-shadow: 0 5px 15px rgba(157, 34, 53, 0.2); 
            border: 1px solid var(--primary);
            border-radius: 30px;
        }
        .reg-btn:hover{
            background-color: transparent;
            color: var(--text);
      
        }
        
        /* Header & Navigation */
        header {
            padding: 20px 0;
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background-color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
        }
        
        .logo-icon::after {
            content: "🇳🇵";
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 12px;
            background-color: var(--secondary);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-text h1 {
            font-size: 24px;
            color: var(--primary);
        }
        
        .logo-text p {
            font-size: 10px;
            color: var(--light-text);
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .nav-links a {
            color: var(--text);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
      
        
        /* Hero Section */
        .hero {
            padding: 100px 0;
            background: linear-gradient(135deg, rgba(157, 34, 53, 0.03) 0%, rgba(201, 162, 39, 0.03) 100%);
        }
        
        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        
        .hero-text h2 {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-text p {
            font-size: 18px;
            color: var(--light-text);
            margin-bottom: 30px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 15px;
        }
        
        .hero-image {
            position: relative;
        }
        
        .hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .floating-card {
            position: absolute;
            background-color: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-1 {
            top: -20px;
            left: -20px;
            width: 200px;
        }
        
        .card-2 {
            bottom: -20px;
            right: -20px;
            width: 180px;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            background-color: var(--bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
        
        /* Features Section */
        .features {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 36px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .section-title p {
            color: var(--light-text);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background-color: var(--bg);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--primary);
            font-size: 28px;
        }
        
        .feature-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: var(--text);
        }
        
        /* How It Works */
        .how-it-works {
            padding: 80px 0;
            background: linear-gradient(135deg, rgba(157, 34, 53, 0.03) 0%, rgba(201, 162, 39, 0.03) 100%);
        }
        
        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .step {
            text-align: center;
            position: relative;
        }
        
        .step-number {
            width: 50px;
            height: 50px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        
        .step h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--text);
        }
        
        /* Testimonials */
        .testimonials {
            padding: 80px 0;
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .testimonial-card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
        }
        
        .testimonial-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .testimonial-info h4 {
            color: var(--text);
            margin-bottom: 5px;
        }
        
        .testimonial-info p {
            color: var(--light-text);
            font-size: 14px;
        }
        
        .testimonial-text {
            color: var(--light-text);
            font-style: italic;
            line-height: 1.8;
        }
        
        /* CTA Section */
        .cta {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary) 0%, #7a1b2a 100%);
            color: white;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        
        .cta p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        .cta .btn {
            background-color: white;
            color: var(--primary);
        }
        
        .cta .btn:hover {
            background-color: var(--bg);
        }
        
        /* Footer */
        footer {
            background-color: var(--text);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .footer-logo .logo-icon {
            background-color: white;
            color: var(--primary);
        }
        
        .footer-logo h3 {
            color: white;
        }
        
        .footer-about p {
            opacity: 0.8;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        
        .footer-links h4 {
            color: white;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .footer-links ul {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        
        .footer-links a:hover {
            opacity: 1;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-icons a {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }
        
        .social-icons a:hover {
            background-color: var(--primary);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
            font-size: 14px;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .hero-text h2 {
                font-size: 36px;
            }
            
            .hero-buttons {
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .hero {
                padding: 60px 0;
            }
            
            .hero-text h2 {
                font-size: 32px;
            }
            
            .section-title h2 {
                font-size: 28px;
            }
            
            .floating-card {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="logo-text">
                        <h1>Jodify</h1>
                        <p>Nepal's Trusted Matchmaking Platform</p>
                    </div>
                </div>
                
                <div class="nav-links">
                    <a href="#home">Home</a>
                    <a href="#features">Features</a>
                    <a href="#how-it-works">How It Works</a>
                    <a href="#testimonials">Success Stories</a>
                    
                </div>
                <div class="log-links">
                    <a href="public/login.php" class="log-btn">Login</a>
                    <a href="public/register.php" class="reg-btn">Sign Up</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h2>Find Your Perfect Match in Nepal</h2>
                    <p>Jodify connects compatible singles based on culture, values, and lifestyle. Join thousands of Nepali singles finding meaningful relationships through our trusted platform.</p>
                    <div class="hero-buttons">
                        <a href="register.html" class="btn btn-primary">Create Free Profile</a>
                        <a href="#how-it-works" class="btn btn-outline">How It Works</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1518568814500-bf0f8d125f46?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Happy couple">
                    <div class="floating-card card-1">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4>500+</h4>
                            <p>Matches Made</p>
                        </div>
                    </div>
                    <div class="floating-card card-2">
                        <div class="card-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <h4>98%</h4>
                            <p>Success Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Jodify?</h2>
                <p>Our platform is designed specifically for Nepali singles looking for serious relationships</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Verified Profiles</h3>
                    <p>Every profile undergoes verification to ensure authenticity and safety for our community.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h3>Smart Matching</h3>
                    <p>Advanced algorithms match you based on compatibility, values, and lifestyle preferences.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Privacy Focused</h3>
                    <p>Your privacy is our priority. Control what information you share and with whom.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Friendly</h3>
                    <p>Access Jodify on any device. Stay connected with matches wherever you go.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated team is here to help you navigate your journey to finding love.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <h3>Free to Start</h3>
                    <p>Create your profile and browse matches completely free. Upgrade only when ready.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-title">
                <h2>How Jodify Works</h2>
                <p>Finding your perfect match is simple with our 4-step process</p>
            </div>
            
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Create Your Profile</h3>
                    <p>Sign up and create a detailed profile highlighting your personality, values, and preferences.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Set Your Preferences</h3>
                    <p>Tell us what you're looking for in a partner - from lifestyle to cultural values.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Browse Matches</h3>
                    <p>Our algorithm suggests compatible profiles based on your preferences and compatibility.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Connect & Chat</h3>
                    <p>Send interests to matches you like and start meaningful conversations in a safe environment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Success Stories</h2>
                <p>Real couples who found love through Jodify</p>
            </div>
            
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Raj">
                        </div>
                        <div class="testimonial-info">
                            <h4>Raj & Priya</h4>
                            <p>Married • 2024</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "We connected on Jodify and instantly knew there was something special. Our values matched perfectly, and 6 months later we were engaged. Thank you Jodify for bringing us together!"
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616c113a1c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Sita">
                        </div>
                        <div class="testimonial-info">
                            <h4>Sita & Amit</h4>
                            <p>Engaged • 2024</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "As working professionals, we didn't have time for traditional matchmaking. Jodify made it easy to find someone who shared our lifestyle and goals. We're getting married next month!"
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Anjali">
                        </div>
                        <div class="testimonial-info">
                            <h4>Anjali & Rohan</h4>
                            <p>Together • 1 Year</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "What I loved about Jodify was how seriously everyone takes finding a partner. No games, just genuine people looking for meaningful connections. Rohan and I couldn't be happier!"
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Find Your Perfect Match?</h2>
            <p>Join thousands of Nepali singles who have found love through Jodify. Create your free profile today and start your journey.</p>
            <a href="register.php" class="btn">Start Free Today</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">
                        <div class="logo-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Jodify</h3>
                    </div>
                    <p>Connecting hearts across Nepal through trusted matchmaking. Our mission is to help Nepali singles find meaningful, lasting relationships.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#testimonials">Success Stories</a></li>
                        <li><a href="login.html">Login</a></li>
                        <li><a href="register.html">Sign Up</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">Safety Guidelines</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Contact Us</h4>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Kathmandu, Nepal</li>
                        <li><i class="fas fa-phone"></i> +977 1-1234567</li>
                        <li><i class="fas fa-envelope"></i> hello@jodify.com</li>
                        <li><i class="fas fa-clock"></i> Support: 24/7</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2026 Jodify. All rights reserved. Made with ❤️ in Nepal 🇳🇵</p>
            </div>
        </div>
    </footer>
</body>
</html>