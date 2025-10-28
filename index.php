<?php
$page_title = "MLX - Home";
include 'header.php';
?>

    <!-- Home/Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-content">
            <h1><?php echo __t('home_hero_title'); ?></h1>
            <p><?php echo __t('home_hero_sub'); ?></p>
            <div class="mt-4">
                <a href="#contact" class="get-started-btn btn btn-primary btn-lg me-3"><?php echo __t('home_cta_get_started'); ?></a>
                <a href="#services" class="btn btn-outline-light btn-lg"><?php echo __t('home_cta_services'); ?></a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2 class="section-title"><?php echo __t('about_title'); ?></h2>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-content">
                        <h3><?php echo __t('about_heading'); ?></h3>
                        <p><?php echo __t('about_p1'); ?></p>
                        <p><?php echo __t('about_p2'); ?></p>
                        <p><?php echo __t('about_p3'); ?></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Team meeting" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title"><?php echo __t('services_title'); ?></h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h3><?php echo __t('service_textiles'); ?></h3>
                        <p><?php echo __t('service_textiles_desc'); ?></p>
                        <a href="#contact" class="btn btn-outline-primary mt-3"><?php echo __t('service_get_service'); ?></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <h3><?php echo __t('service_trade'); ?></h3>
                        <p><?php echo __t('service_trade_desc'); ?></p>
                        <a href="#contact" class="btn btn-outline-primary mt-3"><?php echo __t('service_get_service'); ?></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3><?php echo __t('service_inspection'); ?></h3>
                        <p><?php echo __t('service_inspection_desc'); ?></p>
                        <a href="#contact" class="btn btn-outline-primary mt-3"><?php echo __t('service_get_service'); ?></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h3><?php echo __t('service_it'); ?></h3>
                        <p><?php echo __t('service_it_desc'); ?></p>
                        <a href="#contact" class="btn btn-outline-primary mt-3"><?php echo __t('service_get_service'); ?></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3><?php echo __t('service_sourcing'); ?></h3>
                        <p><?php echo __t('service_sourcing_desc'); ?></p>
                        <a href="#contact" class="btn btn-outline-primary mt-3"><?php echo __t('service_get_service'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">15+</div>
                        <div class="stat-label"><?php echo __t('stats_years'); ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">200+</div>
                        <div class="stat-label"><?php echo __t('stats_clients'); ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label"><?php echo __t('stats_projects'); ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label"><?php echo __t('stats_satisfaction'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title"><?php echo __t('testimonials_title'); ?></h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "<?php echo __t('testi_1_text'); ?>"
                        </div>
                        <div class="client-name"><?php echo __t('testi_1_name'); ?></div>
                        <div class="client-position"><?php echo __t('testi_1_pos'); ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "<?php echo __t('testi_2_text'); ?>"
                        </div>
                        <div class="client-name"><?php echo __t('testi_2_name'); ?></div>
                        <div class="client-position"><?php echo __t('testi_2_pos'); ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "<?php echo __t('testi_3_text'); ?>"
                        </div>
                        <div class="client-name"><?php echo __t('testi_3_name'); ?></div>
                        <div class="client-position"><?php echo __t('testi_3_pos'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title"><?php echo __t('contact_title'); ?></h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-form">

                        <form action="https://formsubmit.co/mahalaxmi.bharat@hotmail.com" method="POST">
                            <input type="hidden" name="_subject" value="<?php echo __t('form_subject'); ?>">
                            <input type="hidden" name="_template" value="table">
                            <input type="text" name="_honey" style="display:none">

                            <!-- Your form fields here -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label"><?php echo __t('form_label_name'); ?></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label"><?php echo __t('form_label_email'); ?></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label"><?php echo __t('form_label_subject'); ?></label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label"><?php echo __t('form_label_message'); ?></label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg"><?php echo __t('form_btn_send'); ?></button>
                            </div>
                        </form>
                        <div id="formMessage" class="mt-3" style="display: none;"></div>
                    </div>
                    <div class="mt-5 text-center">
                        <h4><?php echo __t('contact_direct'); ?></h4>
                        <p><i class="fas fa-envelope me-2"></i> mahalaxmi.bharat@hotmail.com</p>
                        <p><i class="fas fa-phone me-2"></i> +86 15088547783</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <h2><?php echo __t('cta_title'); ?></h2>
            <p class="lead mb-4"><?php echo __t('cta_desc'); ?></p>
            <a href="#contact" class="btn btn-light btn-lg"><?php echo __t('cta_btn'); ?></a>
        </div>
    </section>

<?php include 'footer.php'; ?>