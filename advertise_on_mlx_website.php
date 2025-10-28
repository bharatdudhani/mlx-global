<?php
$page_title = "Advertise on MLX Website";
include 'header.php';
?>

    <!-- Hero Section -->
    <section class="hero-ad-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="display-4 fw-bold"><?php echo __t('ad_title'); ?></h1>
                    <p class="lead"><?php echo __t('ad_sub'); ?></p>
                    <a href="#contact" class="btn btn-advertise btn-lg mt-3"><?php echo __t('ad_cta'); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="bg-light">
        <div class="container">
            <h2 class="section-title ad-section-title"><?php echo __t('ad_why'); ?></h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3><?php echo __t('ad_benefit_audience'); ?></h3>
                        <p><?php echo __t('ad_benefit_audience_desc'); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3><?php echo __t('ad_benefit_engagement'); ?></h3>
                        <p><?php echo __t('ad_benefit_engagement_desc'); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3><?php echo __t('ad_benefit_global'); ?></h3>
                        <p><?php echo __t('ad_benefit_global_desc'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section>
        <div class="container">
            <h2 class="section-title ad-section-title"><?php echo __t('ad_audience'); ?></h2>
            <div class="row">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">15K+</div>
                        <div class="stat-label"><?php echo __t('ad_audience_visitors'); ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">45%</div>
                        <div class="stat-label"><?php echo __t('ad_audience_decision'); ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">2.5M</div>
                        <div class="stat-label"><?php echo __t('ad_audience_pageviews'); ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">72%</div>
                        <div class="stat-label"><?php echo __t('ad_audience_return'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Advertising Options -->
    <section class="bg-light">
        <div class="container">
            <h2 class="section-title ad-section_title"><?php echo __t('ad_options'); ?></h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="ad-type-card">
                        <h4><?php echo __t('ad_banner'); ?></h4>
                        <p><?php echo __t('ad_banner_desc'); ?></p>
                        <ul class="terms-list">
                            <li>Leaderboard (728x90)</li>
                            <li>Medium Rectangle (300x250)</li>
                            <li>Skyscraper (160x600)</li>
                            <li><?php echo __t('ad_banner_sizes'); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ad-type-card">
                        <h4><?php echo __t('ad_sponsored'); ?></h4>
                        <p><?php echo __t('ad_sponsored_desc'); ?></p>
                        <ul class="terms-list">
                            <li>Featured blog posts</li>
                            <li>Product showcases</li>
                            <li>Industry insights</li>
                            <li>Case studies</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ad-type-card">
                        <h4><?php echo __t('ad_newsletter'); ?></h4>
                        <p><?php echo __t('ad_newsletter_desc'); ?></p>
                        <ul class="terms-list">
                            <li>Header banner placement</li>
                            <li>Featured sponsor section</li>
                            <li>Dedicated sponsored content</li>
                            <li>Customizable options</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms and Conditions -->
    <section>
        <div class="container">
            <h2 class="section-title ad-section-title"><?php echo __t('ad_terms'); ?></h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo __t('ad_basic_terms'); ?></h4>
                            <ul class="terms-list">
                                <li><?php echo __t('ad_term_1'); ?></li>
                                <li><?php echo __t('ad_term_2'); ?></li>
                                <li><?php echo __t('ad_term_3'); ?></li>
                                <li><?php echo __t('ad_term_4'); ?></li>
                                <li><?php echo __t('ad_term_5'); ?></li>
                                <li><?php echo __t('ad_term_6'); ?></li>
                                <li><?php echo __t('ad_term_7'); ?></li>
                                <li><?php echo __t('ad_term_8'); ?></li>
                            </ul>
                            <p class="mt-4"><strong><?php echo __t('ad_note'); ?></strong> <?php echo __t('ad_note_text'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-light">
        <div class="container">
            <h2 class="section-title ad-section-title"><?php echo __t('ad_contact_title'); ?></h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-form">
                        <p class="text-center"><?php echo __t('ad_contact_intro'); ?></p>
                        
                        <form action="https://formsubmit.co/mahalaxmi.bharat@hotmail.com" method="POST">
                            <input type="hidden" name="_subject" value="<?php echo __t('ad_form_subject'); ?>">
                            <input type="hidden" name="_template" value="table">
                            <input type="text" name="_honey" style="display:none">
                            
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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="company" class="form-label"><?php echo __t('ad_form_company'); ?></label>
                                    <input type="text" class="form-control" id="company" name="company">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label"><?php echo __t('ad_form_website'); ?></label>
                                    <input type="url" class="form-control" id="website" name="website">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ad-type" class="form-label"><?php echo __t('ad_form_type'); ?></label>
                                <select class="form-select" id="ad-type" name="ad-type">
                                    <option value=""><?php echo __t('ad_form_select'); ?></option>
                                    <option value="banner"><?php echo __t('ad_form_type_banner'); ?></option>
                                    <option value="sponsored"><?php echo __t('ad_form_type_sponsored'); ?></option>
                                    <option value="newsletter"><?php echo __t('ad_form_type_newsletter'); ?></option>
                                    <option value="other"><?php echo __t('ad_form_type_other'); ?></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label"><?php echo __t('ad_form_message'); ?></label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-advertise btn-lg"><?php echo __t('ad_form_send'); ?></button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-5 text-center">
                        <h4><?php echo __t('direct_contact'); ?></h4>
                        <p><i class="fas fa-envelope me-2"></i> mahalaxmi.bharat@hotmail.com</p>
                        <p><i class="fas fa-phone me-2"></i> +86 15088547783</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>