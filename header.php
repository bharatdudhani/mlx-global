<?php
// Get current page name for active navigation highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Simple language detection without sessions
$current_lang = 'en'; // Default to English
if (isset($_GET['lang']) && $_GET['lang'] === 'zh') {
    $current_lang = 'zh';
}

// Load translations
require_once __DIR__ . '/translations.php';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($current_lang); ?>">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z7DMX77279"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-Z7DMX77279');
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'MLX - Home'; ?></title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2077914521498331"
     crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark nav-custom <?php echo ($current_page == 'index') ? 'fixed-top' : ''; ?>">
        <div class="container">
            <a class="navbar-brand brand-container" href="<?php echo ($current_page == 'index') ? '#home' : 'index.php'; ?>">
                <img src="mlx-logo-wobg.png" alt="MLX Logo" class="logo-img">
                <span class="brand-text"><?php echo __t('brand_name'); ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" 
                           href="<?php echo ($current_page == 'index') ? '#home' : 'index.php'; ?>"><?php echo __t('nav_home'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ($current_page == 'index') ? '#about' : 'index.php#about'; ?>"><?php echo __t('nav_about'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ($current_page == 'index') ? '#services' : 'index.php#services'; ?>"><?php echo __t('nav_services'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ($current_page == 'index') ? '#contact' : 'index.php#contact'; ?>"><?php echo __t('nav_contact'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'blog') ? 'active' : ''; ?>" href="blog.php"><?php echo __t('nav_blog'); ?></a>
                    </li>
                    <?php if ($current_page == 'add-post'): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="add-post.php"><?php echo __t('nav_add_post'); ?></a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <?php echo __t('nav_vlog'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://www.youtube.com/@MLX-GLOBAL">BiliBili</a></li>
                            <li><a class="dropdown-item" href="https://space.bilibili.com/1060645689">Youtube</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'softwares') ? 'active' : ''; ?>" href="softwares.php"><?php echo __t('nav_softwares'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'donate') ? 'active' : ''; ?>" href="donate.php"><?php echo __t('nav_donate'); ?></a>
                    </li>
                    <!-- Simple Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-language me-1"></i> 
                            <?php echo ($current_lang === 'zh') ? '中文' : 'English'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item <?php echo ($current_lang === 'en') ? 'active' : ''; ?>" 
                                   href="?lang=en">
                                    English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo ($current_lang === 'zh') ? 'active' : ''; ?>" 
                                   href="?lang=zh">
                                    中文 (Chinese)
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script>
    // Simple back to top button functionality
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.querySelector('.back-to-top');
        
        if (backToTopButton) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.style.opacity = '1';
                } else {
                    backToTopButton.style.opacity = '0';
                }
            });
            
            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
    </script>