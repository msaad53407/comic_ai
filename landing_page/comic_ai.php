<?php
// Read proposal content from file
$proposalContent = "";
$proposalFile = "proposal.txt";

if (file_exists($proposalFile)) {
    $proposalContent = file_get_contents($proposalFile);
    // Convert line breaks to <br> tags for HTML display
    $proposalContent = nl2br(htmlspecialchars($proposalContent));
} else {
    // Default content if file doesn't exist
    $proposalContent = "<strong>Name:</strong> Alex \"The Shadow\" Mercer<br/>
<strong>Roll Number:</strong> 78901<br/>
<strong>Role:</strong> Lead Detective<br/>
<strong>Background:</strong> A former police officer who now operates as a private investigator in the neon-drenched streets of Neo-Kyoto. Haunted by a past case, Mercer is known for his sharp intellect and unorthodox methods. He is a master of disguise and information gathering, often blending into the city's underbelly to solve the most complex cases. His cynical exterior hides a strong sense of justice.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComicAI</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <header>
            <div class="container">
                <div class="header-content">
                    <div class="logo-section">
                        <svg class="logo-icon" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd"
                                d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z"
                                fill="currentColor" fill-rule="evenodd" />
                        </svg>
                        <h1 class="logo-text">ComicAI</h1>
                    </div>
                    <nav class="nav-menu">
                        <a class="nav-link" href="#">Home</a>
                        <a class="nav-link" href="#">About</a>
                        <a class="nav-link" href="#">Contact</a>
                    </nav>
                    <div class="header-actions">
                        <button class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
                            <span class="material-symbols-outlined theme-icon-light">light_mode</span>
                            <span class="material-symbols-outlined theme-icon-dark">dark_mode</span>
                        </button>
                        <button class="menu-toggle">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="container">
                    <div class="hero-grid">
                        <div class="hero-content">
                            <h2 class="hero-title">Unleash Your Creativity with AI-Powered Comics</h2>
                            <p class="hero-description">
                                Transform your ideas into captivating comic strips effortlessly. Our AI-driven platform
                                makes comic creation accessible to everyone.
                            </p>
                            <div class="hero-cta">
                                <button class="btn-large">Create Your Comic</button>
                            </div>
                        </div>
                        <div class="animate-float">
                            <div class="hero-image"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDGW4VertI_6GDQZiUCuAQ1YQLkIZOJSHX8AFINIiz5F2p1TbCPLvokxAJ_pZWpcuMQTYrkekHoVu709z4UbZHSumY0FDQrEye2ujnOx0LnOZZoQ4t2G6mwpHkBisYHW6o-WgueEgzQhb8sqXbciwhqLTsREcRtjdYxgAVuU5aUkA19wvSq0SOtLvbFP_vt13mnGHiNMgTGeZxTrs_42wNxVBk7MVsZnPjb0IhsHGY68swM0_Zbb6XAgI4a36-td-scKXE3M_p9L48n");'>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Proposal Section -->
            <section class="proposal-section">
                <div class="container">
                    <div class="proposal-container">
                        <div class="section-header">
                            <h3 class="section-title">Navigate Proposal</h3>
                        </div>

                        <div class="form-card">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="topic-select">Select Proposal Topic</label>
                                    <div class="select-wrapper">
                                        <select class="form-select" id="topic-select">
                                            <option>General Proposal</option>
                                            <option>Member Introduction</option>
                                            <option>Story Arc Pitch</option>
                                        </select>
                                        <div class="select-icon">
                                            <span class="material-symbols-outlined">expand_more</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="roll-number">Find Member by Roll Number</label>
                                    <div class="input-wrapper">
                                        <span class="material-symbols-outlined input-icon">search</span>
                                        <input class="form-input" id="roll-number" placeholder="e.g., 12345"
                                            type="text">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="preview-card">
                            <div class="preview-content">
                                <h4 class="preview-title">Content from Proposal</h4>
                                <div class="preview-text">
                                    <p>
                                        <?php echo $proposalContent; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Art Styles Section -->
            <section class="art-section">
                <div class="container">
                    <div class="art-grid">
                        <div class="order-2 md-order-1">
                            <div class="art-showcase">
                                <div class="art-grid-inner">
                                    <div class="art-panel"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiu7A50OAX5gQrmTqXoUmOOpPFMOWv_tC9kl3UEAzEOYG6oPK_yqWwcXRZg-3CmJUWDE4-7Sxb50tDHPjUhwwdIZZmmpARIywrfzSiBo_6SrBGxacIz00HyBS78uzJDtPkIvn0y_59OlGRQDPy8bhcVUth-TKzjO-ul-zfKCugzMW7qimAt8UM5SonCRM_JNZa_h-xgv1o3FCN4zXwW92vWBktqrP59IZSHeUcWe9aCYm8iVsBYlFkXuwhFQd0nSOlJ4QOZ0Q8QqGo')">
                                    </div>
                                    <div class="art-panel"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiu7A50OAX5gQrmTqXoUmOOpPFMOWv_tC9kl3UEAzEOYG6oPK_yqWwcXRZg-3CmJUWDE4-7Sxb50tDHPjUhwwdIZZmmpARIywrfzSiBo_6SrBGxacIz00HyBS78uzJDtPkIvn0y_59OlGRQDPy8bhcVUth-TKzjO-ul-zfKCugzMW7qimAt8UM5SonCRM_JNZa_h-xgv1o3FCN4zXwW92vWBktqrP59IZSHeUcWe9aCYm8iVsBYlFkXuwhFQd0nSOlJ4QOZ0Q8QqGo')">
                                    </div>
                                    <div class="art-panel"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiu7A50OAX5gQrmTqXoUmOOpPFMOWv_tC9kl3UEAzEOYG6oPK_yqWwcXRZg-3CmJUWDE4-7Sxb50tDHPjUhwwdIZZmmpARIywrfzSiBo_6SrBGxacIz00HyBS78uzJDtPkIvn0y_59OlGRQDPy8bhcVUth-TKzjO-ul-zfKCugzMW7qimAt8UM5SonCRM_JNZa_h-xgv1o3FCN4zXwW92vWBktqrP59IZSHeUcWe9aCYm8iVsBYlFkXuwhFQd0nSOlJ4QOZ0Q8QqGo')">
                                    </div>
                                    <div class="art-panel"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiu7A50OAX5gQrmTqXoUmOOpPFMOWv_tC9kl3UEAzEOYG6oPK_yqWwcXRZg-3CmJUWDE4-7Sxb50tDHPjUhwwdIZZmmpARIywrfzSiBo_6SrBGxacIz00HyBS78uzJDtPkIvn0y_59OlGRQDPy8bhcVUth-TKzjO-ul-zfKCugzMW7qimAt8UM5SonCRM_JNZa_h-xgv1o3FCN4zXwW92vWBktqrP59IZSHeUcWe9aCYm8iVsBYlFkXuwhFQd0nSOlJ4QOZ0Q8QqGo')">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-1 md-order-2 art-content">
                            <h3 class="section-title">Diverse Art Styles</h3>
                            <p class="hero-description">
                                Choose from a wide range of art styles to match your story's tone. From classic cartoons
                                to modern manga, ComicAI has you covered.
                            </p>
                            <ul class="feature-list">
                                <li class="feature-item">
                                    <span class="material-symbols-outlined check-icon">check_circle</span>
                                    <span>Dynamic Panel Layouts</span>
                                </li>
                                <li class="feature-item">
                                    <span class="material-symbols-outlined check-icon">check_circle</span>
                                    <span>Customizable Characters</span>
                                </li>
                                <li class="feature-item">
                                    <span class="material-symbols-outlined check-icon">check_circle</span>
                                    <span>AI-Generated Scenery</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Script Section -->
            <section class="script-section">
                <div class="container">
                    <div class="art-grid">
                        <div class="art-content">
                            <h3 class="section-title">From Script to Strip</h3>
                            <p class="hero-description">
                                Simply type your story, and our AI will generate a complete comic strip with characters,
                                dialogue, and panels. It's that easy!
                            </p>
                            <div class="script-example">
                                <p class="script-text">"In a city of towering chrome, a lone detective sips
                                    synth-coffee. A mysterious dame walks in. 'I need your help,' she says, her voice
                                    like digital rain."</p>
                            </div>
                        </div>
                        <div>
                            <div class="comic-preview">
                                <div class="comic-panels">
                                    <div class="comic-panel"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiu7A50OAX5gQrmTqXoUmOOpPFMOWv_tC9kl3UEAzEOYG6oPK_yqWwcXRZg-3CmJUWDE4-7Sxb50tDHPjUhwwdIZZmmpARIywrfzSiBo_6SrBGxacIz00HyBS78uzJDtPkIvn0y_59OlGRQDPy8bhcVUth-TKzjO-ul-zfKCugzMW7qimAt8UM5SonCRM_JNZa_h-xgv1o3FCN4zXwW92vWBktqrP59IZSHeUcWe9aCYm8iVsBYlFkXuwhFQd0nSOlJ4QOZ0Q8QqGo')">
                                    </div>
                                    <div class="comic-panel"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiu7A50OAX5gQrmTqXoUmOOpPFMOWv_tC9kl3UEAzEOYG6oPK_yqWwcXRZg-3CmJUWDE4-7Sxb50tDHPjUhwwdIZZmmpARIywrfzSiBo_6SrBGxacIz00HyBS78uzJDtPkIvn0y_59OlGRQDPy8bhcVUth-TKzjO-ul-zfKCugzMW7qimAt8UM5SonCRM_JNZa_h-xgv1o3FCN4zXwW92vWBktqrP59IZSHeUcWe9aCYm8iVsBYlFkXuwhFQd0nSOlJ4QOZ0Q8QqGo')">
                                    </div>
                                    <div class="comic-panel"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiu7A50OAX5gQrmTqXoUmOOpPFMOWv_tC9kl3UEAzEOYG6oPK_yqWwcXRZg-3CmJUWDE4-7Sxb50tDHPjUhwwdIZZmmpARIywrfzSiBo_6SrBGxacIz00HyBS78uzJDtPkIvn0y_59OlGRQDPy8bhcVUth-TKzjO-ul-zfKCugzMW7qimAt8UM5SonCRM_JNZa_h-xgv1o3FCN4zXwW92vWBktqrP59IZSHeUcWe9aCYm8iVsBYlFkXuwhFQd0nSOlJ4QOZ0Q8QqGo')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <!-- <section class="testimonials-section">
                <div class="container">
                    <div class="testimonials-header">
                        <h2 class="section-title">What Our Users Say</h2>
                        <p class="hero-description">
                            Trusted by creators and storytellers worldwide.
                        </p>
                    </div>
                    <div class="testimonials-grid">
                        <div class="testimonial-card">
                            <p class="testimonial-text">"ComicAI is a game-changer for indie creators. The AI
                                beautifully interprets my scripts, and the art styles are stunning. I've saved countless
                                hours."</p>
                            <div class="testimonial-author">
                                <img alt="User avatar" class="author-avatar"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhiW7tTt6QoiHYrm2erwsWuK7ElLo12RguWCsZJzExysazYU5yWw9Eipb-MN4PsaTICkHzKCfBewn51omtxTwxdecRFtqbgEEbyfDN62ODZFzC5wRJ1SnHUuKO12IxoBYgaTtEMTgVT66-6PiiGjSRlBnWQptze59PHNxh17Xn0yVGYoJyqgmYT9DGvZGkDiQPS3dlY1P1_mu0Q3puUdaWeihOqfQDy4OCCBnHp3iAJ9szmhKDObzaWdLoXJyBB1LHIABCPZm3yETN">
                                <div>
                                    <p class="author-name">Alex Johnson</p>
                                    <p class="author-role">Comic Artist</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <p class="testimonial-text">"I'm not an artist, but with ComicAI, I feel like one. It's
                                incredibly intuitive and the results are professional-grade. Highly recommended for any
                                storyteller."</p>
                            <div class="testimonial-author">
                                <img alt="User avatar" class="author-avatar"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuA57fZxSgiV-Ov3fhB7M83IYt-2lsfzBfbTGiQGX2YxN4E6ytHcB52FLewpR96xr0VBO0lAZKRv9PIlK36XBAyz5zcUz5rC6YE8SyKxQIClfa62Iephl1EkJrLXBUc8fQVibDxPleXXjogrFlsLTfUU3JEUE4kGgerD1Se4AhF38LqJYoa6w78EXhzUItEUvA8rAln0_JxgAIzYnp1DGqQNHmoaP-ukbcBxu3zM5fZnBMEuURQsWBD4L1bNvH7L1Z-AlvplFQbKTt5R">
                                <div>
                                    <p class="author-name">Samantha Lee</p>
                                    <p class="author-role">Novelist</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <p class="testimonial-text">"The speed at which I can visualize scenes is mind-blowing.
                                ComicAI helps me storyboard my animations in a fraction of the time. An essential tool."
                            </p>
                            <div class="testimonial-author">
                                <img alt="User avatar" class="author-avatar"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJvHhVhCVcLC9C8XFTc76zzXx_PKLgJmsxBWszA-22E9-1tY2VeaNWmtBMhNuZpVveB6Hp_1nTKlaBoycTngG4IoG6bSF-XIUs_V5BPgkxuRwMWaTY-MUf-JGrf5pneQ2KqSXK0183VuRWAKE9JCX_0JYmvDi30HaOSnjJDJIjDS6rZbPS5Cm2nVuVMn14QeueNUKiAeG3c8XYTPYnRs91HzoI6Z2QzT40JdiMmxvVD5jX-oye8Lvc19jpbBxLICeYjqBnunxL5E_G">
                                <div>
                                    <p class="author-name">Ben Carter</p>
                                    <p class="author-role">Animator</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->

            <!-- FAQ Section -->
            <section class="faq-section">
                <div class="container">
                    <div class="faq-container">
                        <h2 class="section-title">Frequently Asked Questions</h2>
                        <p class="hero-description">
                            Have questions? We have answers. If you can't find what you're looking for, feel free to
                            contact us.
                        </p>
                    </div>
                    <div class="faq-list">
                        <details class="faq-item">
                            <summary class="faq-question">
                                <h3>How does the AI generate comics?</h3>
                                <span class="material-symbols-outlined plus-icon">add</span>
                            </summary>
                            <div class="faq-answer">
                                <p>Our AI uses advanced natural language processing (NLP) to understand your script and
                                    a sophisticated generative adversarial network (GAN) to create corresponding images.
                                    It analyzes character descriptions, actions, and settings to produce a cohesive and
                                    visually appealing comic strip.</p>
                            </div>
                        </details>
                        <details class="faq-item">
                            <summary class="faq-question">
                                <h3>Can I customize the art style?</h3>
                                <span class="material-symbols-outlined plus-icon">add</span>
                            </summary>
                            <div class="faq-answer">
                                <p>Yes! We offer a wide variety of pre-trained art styles, from vintage comic book looks
                                    to modern webtoon aesthetics. You can select your preferred style before generating
                                    the comic. We are constantly adding new styles to our library.</p>
                            </div>
                        </details>
                        <details class="faq-item">
                            <summary class="faq-question">
                                <h3>What are the subscription plans?</h3>
                                <span class="material-symbols-outlined plus-icon">add</span>
                            </summary>
                            <div class="faq-answer">
                                <p>We have a range of subscription plans to suit different needs. Our free tier allows
                                    you to create a limited number of comics per month. Our paid plans offer unlimited
                                    creations, access to premium art styles, higher resolution exports, and commercial
                                    usage rights.</p>
                            </div>
                        </details>
                        <details class="faq-item">
                            <summary class="faq-question">
                                <h3>Do I own the rights to the comics I create?</h3>
                                <span class="material-symbols-outlined plus-icon">add</span>
                            </summary>
                            <div class="faq-answer">
                                <p>For users on our paid subscription plans, you own the full commercial rights to the
                                    comics you generate. You are free to use them for personal or commercial projects.
                                    For users on the free plan, the comics are licensed for personal, non-commercial use
                                    only.</p>
                            </div>
                        </details>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="footer-content">
                    <p class="footer-copyright">© 2025 ComicAI. All rights reserved.</p>
                    <div class="footer-links">
                        <a class="footer-link" href="#">Privacy</a>
                        <a class="footer-link" href="#">Terms</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;

        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            body.classList.add('dark');
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark');

            // Save theme preference
            const theme = body.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        });
    </script>
</body>

</html>