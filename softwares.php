<?php
$page_title = "Softwares - MLX Consultancy";
include 'header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- New Button Above Card -->
            <div class="text-end mb-3">
                <a href="software-development-services.php" class="btn btn-lg px-4" 
                   style="background: linear-gradient(135deg, #22c55e, #16a34a); 
                          border: 2px solid #2297c5ff;
                          box-shadow: 0 0 10px rgba(34, 197, 94, 0.5), 
                                      inset 0 1px 0 rgba(255, 255, 255, 0.2);
                          color: white;
                          font-weight: 600;
                          text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);">
                    <i class="bi bi-code-slash me-2"></i><?= __t('software_development_services_introduction') ?>
                </a>
            </div>
            
            <div class="card shadow">
                <div class="card-header custom-header text-white">
                    <h1 class="h3 mb-0"><?= __t('software_card_title') ?></h1>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h2 class="h4 text-custom"><?= __t('software_weather_app') ?></h2>
                            <h3 class="h5 text-muted"><?= __t('software_name') ?></h3>
                            <p class="lead"><?= __t('software_description') ?></p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="mlx-weather.php" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-browser-chrome me-2"></i><?= __t('software_open_browser') ?>
                            </a>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Uncomment this section if you want to show downloads -->
                    <!--
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-3"><?= __t('software_downloads') ?></h4>
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="bi bi-google me-2 text-primary"></i><?= __t('software_chrome_extension') ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="bi bi-apple me-2 text-dark"></i><?= __t('software_mac') ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="bi bi-windows me-2 text-info"></i><?= __t('software_windows') ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="bi bi-phone me-2 text-success"></i><?= __t('software_android') ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="bi bi-phone me-2 text-secondary"></i><?= __t('software_ios') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>