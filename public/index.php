<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NestQR - QR-Powered Listing Pages for Real Estate Agents</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="landing-page">
        <header class="header">
            <div class="container">
                <div class="logo">
                    <img src="/assets/images/nestqr-logo.svg" alt="NestQR" class="logo-img">
                    <span class="logo-text">NestQR</span>
                </div>
                <nav class="nav">
                    <a href="/login.php" class="btn btn-outline">Log In</a>
                    <a href="/signup.php" class="btn btn-primary">Sign Up</a>
                </nav>
            </div>
        </header>

        <section class="hero">
            <div class="container">
                <h1 class="hero-title">QR-powered listing pages for real estate agents</h1>
                <p class="hero-subtitle">Simple, smart, and ready to help you connect buyers to your listings.</p>
                
                <?php
                $message = '';
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    $message = '<div class="success-message">✅ Thanks for signing up! We\'ll be in touch soon.</div>';
                } elseif (isset($_GET['error'])) {
                    $message = '<div class="error-message">⚠️ ' . htmlspecialchars($_GET['error']) . '</div>';
                }
                echo $message;
                ?>
                
                <form action="/api/capture-email.php" method="POST" class="hero-form">
                    <div class="form-group-inline">
                        <input type="email" name="email" placeholder="Enter your email" required class="input input-lg">
                        <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">
                        <button type="submit" class="btn btn-primary btn-lg">Join Beta</button>
                    </div>
                    <p class="form-hint">Get early access and be the first to try NestQR</p>
                </form>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2 class="section-title">Why Agents Love NestQR</h2>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3 class="feature-title">Easy QR & Links</h3>
                        <p class="feature-text">Create QR codes and short links in seconds. No tech skills required.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📲</div>
                        <h3 class="feature-title">Mobile Ready</h3>
                        <p class="feature-text">Beautiful listing pages optimized for phones and tablets.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3 class="feature-title">Real-Time Edits</h3>
                        <p class="feature-text">Update your listings anytime. QR codes stay valid—no reprinting needed.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🔄</div>
                        <h3 class="feature-title">Reusable Signs</h3>
                        <p class="feature-text">One QR code, unlimited properties. Move signs between listings effortlessly.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3 class="feature-title">Analytics</h3>
                        <p class="feature-text">Track scans and clicks to see which listings get the most attention.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🎨</div>
                        <h3 class="feature-title">Custom Branding</h3>
                        <p class="feature-text">Add your logo and colors to match your brand (Pro plan).</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="pricing">
            <div class="container">
                <h2 class="section-title">Simple Pricing</h2>
                <p class="section-subtitle">Choose the plan that fits your business</p>
                
                <div class="pricing-grid">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3 class="pricing-title">Free</h3>
                            <div class="pricing-price">$0<span class="pricing-period">/month</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li>✓ 10 QR codes</li>
                            <li>✓ Mobile listing pages</li>
                            <li>✓ Basic analytics</li>
                            <li>✓ 10 icon designs</li>
                        </ul>
                        <a href="/signup.php" class="btn btn-outline btn-block">Get Started</a>
                    </div>
                    
                    <div class="pricing-card pricing-card-featured">
                        <div class="pricing-badge">Popular</div>
                        <div class="pricing-header">
                            <h3 class="pricing-title">Pro</h3>
                            <div class="pricing-price">$25<span class="pricing-period">/month</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li>✓ 25 QR codes</li>
                            <li>✓ All Free features</li>
                            <li>✓ 30+ icon designs</li>
                            <li>✓ Custom branding</li>
                            <li>✓ Advanced analytics</li>
                            <li>✓ Local domain options</li>
                        </ul>
                        <a href="/signup.php" class="btn btn-primary btn-block">Start Free Trial</a>
                    </div>
                    
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3 class="pricing-title">Unlimited</h3>
                            <div class="pricing-price">$50<span class="pricing-period">/month</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li>✓ Unlimited QR codes</li>
                            <li>✓ All Pro features</li>
                            <li>✓ Priority support</li>
                            <li>✓ White-label option</li>
                        </ul>
                        <a href="/signup.php" class="btn btn-outline btn-block">Get Started</a>
                    </div>
                </div>
                
                <div class="team-pricing">
                    <h4>Need a team plan?</h4>
                    <p>Get your whole brokerage on NestQR. <a href="mailto:sales@nestqr.com">Contact sales</a> for company pricing starting at $100/month + $15/agent.</p>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container">
                <h2 class="cta-title">Ready to modernize your listings?</h2>
                <p class="cta-subtitle">Join hundreds of agents using NestQR</p>
                <a href="/signup.php" class="btn btn-primary btn-lg">Create Free Account</a>
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-brand">
                        <img src="/assets/images/nestqr-logo.svg" alt="NestQR" class="footer-logo">
                        <p>© 2025 NestQR. All rights reserved.</p>
                    </div>
                    <div class="footer-links">
                        <a href="mailto:support@nestqr.com">Support</a>
                        <a href="#">Privacy</a>
                        <a href="#">Terms</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
