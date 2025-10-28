<?php
$page_title = "Software Development Services - MLX Consultancy";
include 'header.php';
?>

<!-- Hero Section -->
<section class="hero-section position-relative py-5 text-white">
    <div class="hero-bg position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3"><?= __t('software_dev_hero_title') ?></h1>
                <p class="lead mb-4"><?= __t('software_dev_hero_sub') ?></p>
                <a href="index.php#contact" class="btn btn-success btn-lg px-4 py-2">
                    <?= __t('software_dev_start_project') ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold text-dark mb-3"><?= __t('software_dev_services_title') ?></h2>
                <p class="lead text-muted"><?= __t('software_dev_services_desc') ?></p>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Website Development -->
            <div class="col-lg-4 col-md-6">
                <div class="card service-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon bg-primary text-white rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-laptop fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?= __t('software_dev_website') ?></h4>
                        <p class="text-muted"><?= __t('software_dev_website_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Mobile App Development -->
            <div class="col-lg-4 col-md-6">
                <div class="card service-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon bg-info text-white rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-phone fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?= __t('software_dev_mobile') ?></h4>
                        <p class="text-muted"><?= __t('software_dev_mobile_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Backend Development -->
            <div class="col-lg-4 col-md-6">
                <div class="card service-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon bg-warning text-white rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-server fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?= __t('software_dev_backend') ?></h4>
                        <p class="text-muted"><?= __t('software_dev_backend_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Approach Section -->
<section id="approach" class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold text-dark mb-4"><?= __t('software_dev_approach_title') ?></h2>
                <p class="text-muted mb-4"><?= __t('software_dev_approach_text') ?></p>
                
                <!-- AI Feature -->
                <div class="feature-card bg-white p-4 rounded shadow-sm mb-4 border-start border-success border-4">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-robot fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2 text-success"><?= __t('software_dev_ai_title') ?></h5>
                            <p class="text-muted mb-0"><?= __t('software_dev_ai_text') ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Junior Team Feature -->
                <div class="feature-card bg-white p-4 rounded shadow-sm border-start border-warning border-4">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2 text-warning"><?= __t('software_dev_junior_title') ?></h5>
                            <p class="text-muted mb-0"><?= __t('software_dev_junior_text') ?></p>
                        </div>
                    </div>
                </div>
                
                <p class="text-muted mt-4"><?= __t('software_dev_collaboration') ?></p>
                <a href="index.php#contact" class="discuss-project btn btn-success btn-lg px-4 mt-3">
                    <?= __t('software_dev_discuss_project') ?>
                </a>
            </div>
            
            <div class="col-lg-6">
                <div class="text-center">
                    <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Development Process" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technology Stack Section -->
<section id="tech-stack" class="py-5 tech-stack-bg text-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-3"><?= __t('software_dev_tech_stack') ?></h2>
                <p class="lead"><?= __t('software_dev_tech_desc') ?></p>
            </div>
        </div>
        
        <div class="row g-4 text-center">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tech-item bg-white bg-opacity-10 p-4 rounded">
                    <i class="bi bi-filetype-html fs-1 text-primary mb-3"></i>
                    <h6 class="fw-bold">HTML5</h6>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tech-item bg-white bg-opacity-10 p-4 rounded">
                    <i class="bi bi-filetype-css fs-1 text-info mb-3"></i>
                    <h6 class="fw-bold">CSS3</h6>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tech-item bg-white bg-opacity-10 p-4 rounded">
                    <i class="bi bi-filetype-js fs-1 text-warning mb-3"></i>
                    <h6 class="fw-bold">JavaScript</h6>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tech-item bg-white bg-opacity-10 p-4 rounded">
                    <i class="bi bi-filetype-php fs-1 text-purple mb-3"></i>
                    <h6 class="fw-bold">PHP</h6>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tech-item bg-white bg-opacity-10 p-4 rounded">
                    <i class="bi bi-cup-hot fs-1 text-danger mb-3"></i>
                    <h6 class="fw-bold">Java</h6>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tech-item bg-white bg-opacity-10 p-4 rounded">
                    <i class="bi bi-cpu fs-1 text-success mb-3"></i>
                    <h6 class="fw-bold">AI Tools</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="contact" class="py-5 cta-section position-relative text-white">
    <div class="cta-bg position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="container position-relative">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-3"><?= __t('software_dev_ready_title') ?></h2>
                <p class="lead mb-4"><?= __t('software_dev_ready_text') ?></p>
                <a href="index.php#contact" class="btn btn-light btn-lg px-4">
                    <?= __t('nav_contact') ?>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: url('https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80');
    background-size: cover;
    background-position: center;
}

.hero-bg {
    display: none; /* Remove the overlay */
}

.tech-stack-bg {
    background: linear-gradient(135deg, #058b6aff 0%, #0657a8ff 100%);
}

.cta-section {
    background: linear-gradient(135deg, rgba(26, 32, 44, 0.9) 0%, rgba(45, 55, 72, 0.9) 100%);
}

.cta-bg {
    background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80');
    background-size: cover;
    background-position: center;
    opacity: 0.2;
}

.service-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

.service-icon {
    width: 80px;
    height: 80px;
}

.feature-icon {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
}

.tech-item {
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.tech-item:hover {
    transform: scale(1.05);
    background-color: rgba(255,255,255,0.2) !important;
}

.border-success {
    border-color: #28a745 !important;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}
</style>

<?php include 'footer.php'; ?>