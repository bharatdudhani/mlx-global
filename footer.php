    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="footer-heading">MLX CONSULTANCY</h4>
                    <p><?php echo __t('footer_about_line'); ?></p>
                    <?php if (isset($show_social_icons) && $show_social_icons): ?>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h4 class="footer-heading"><?php echo __t('footer_quick_links'); ?></h4>
                    <div class="footer-links">
                        <a href="<?php echo ($current_page == 'index') ? '#home' : 'index.php'; ?>"><?php echo __t('nav_home'); ?></a>
                        <a href="<?php echo ($current_page == 'index') ? '#about' : 'index.php#about'; ?>"><?php echo __t('nav_about'); ?></a>
                        <a href="<?php echo ($current_page == 'index') ? '#services' : 'index.php#services'; ?>"><?php echo __t('nav_services'); ?></a>
                        <a href="<?php echo ($current_page == 'index') ? '#contact' : 'index.php#contact'; ?>"><?php echo __t('nav_contact'); ?></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h4 class="footer-heading"><?php echo __t('footer_resources'); ?></h4>
                    <div class="footer-links">
                        <a href="blog.php"><?php echo __t('nav_blog'); ?></a>
                        <a href="donate.php"><?php echo __t('nav_donate'); ?></a>
                        <a href="privacy-policy.php"><?php echo __t('nav_privacy'); ?></a>
                        <a href="terms-of-service.php"><?php echo __t('nav_terms'); ?></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h4 class="footer-heading"><?php echo __t('footer_contact_info'); ?></h4>
                    <p><i class="fas fa-envelope me-2"></i>mahalaxmi.bharat@hotmail.com</p>
                    <p><i class="fas fa-phone me-2"></i> +86 15088547783</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo __t('footer_location'); ?>
                </div>
            </div>
            
            <?php if ($current_page == 'index'): ?>
            <!-- Advertising Button Section - Only on Home Page -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="advertise-container">
                        <h4><?php echo __t('footer_ad_title'); ?></h4>
                        <p><?php echo __t('footer_ad_desc'); ?></p>
                        <a href="advertise_on_mlx_website.php" class="advertise-btn">
                            <i class="fas fa-bullhorn me-2"></i><?php echo __t('footer_ad_cta'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="copyright">
                <p>&copy; 2025 MLX. <?php echo __t('footer_rights'); ?></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if ($current_page == 'index'): ?>
    <script>
        // Add active class to nav links on scroll
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', function () {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Contact form submission
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const messageDiv = document.getElementById('formMessage');

            if (contactForm && submitBtn) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Disable submit button and show loading state
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
                    submitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(contactForm);

                    // Send form data
                    fetch('contact_handler.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Show message to user
                            if (messageDiv) {
                                messageDiv.style.display = 'block';
                                messageDiv.className = data.status === 'success' ?
                                    'alert alert-success' : 'alert alert-danger';
                                messageDiv.innerHTML = data.message;
                            }

                            // Reset form if successful
                            if (data.status === 'success') {
                                contactForm.reset();
                            }
                        })
                        .catch(error => {
                            // Show error message
                            if (messageDiv) {
                                messageDiv.style.display = 'block';
                                messageDiv.className = 'alert alert-danger';
                                messageDiv.innerHTML = 'There was an error sending your message. Please try again later or contact us directly at mahalaxmi.bharat@hotmail.com.';
                            }
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            // Reset button state
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        });
                });
            }
        });
    </script>
    <?php endif; ?>

    <?php if ($current_page == 'blog'): ?>
    <script>
        // Tags show more functionality
        document.addEventListener('DOMContentLoaded', function() {
            const showMoreButton = document.getElementById('showMoreTags');
            const tagsContainer = document.getElementById('tagsContainer');
            const hiddenTags = tagsContainer ? tagsContainer.querySelectorAll('.hidden-tag') : [];
            let showingAllTags = false;
            
            if (showMoreButton && hiddenTags.length > 0) {
                showMoreButton.addEventListener('click', function() {
                    if (showingAllTags) {
                        // Hide extra tags
                        hiddenTags.forEach(tag => {
                            tag.classList.add('hidden-tag');
                        });
                        showMoreButton.textContent = 'Show More Tags';
                    } else {
                        // Show all tags
                        hiddenTags.forEach(tag => {
                            tag.classList.remove('hidden-tag');
                        });
                        showMoreButton.textContent = 'Show Less Tags';
                    }
                    showingAllTags = !showingAllTags;
                });
            }
        });
    </script>
    <?php endif; ?>

    <?php if ($current_page == 'add-post'): ?>
    <script>
        // Cover image preview
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('coverPreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });

        // Content preview functionality
        function updatePreview() {
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const previewContent = document.getElementById('previewContent');
            
            if (title || content) {
                previewContent.innerHTML = `
                    <h2>${title || 'Untitled'}</h2>
                    <div>${content.replace(/\n/g, '<br>')}</div>
                `;
            } else {
                previewContent.innerHTML = '<p>Start typing to see preview...</p>';
            }
        }

        // Update preview on input
        document.getElementById('title').addEventListener('input', updatePreview);
        document.getElementById('content').addEventListener('input', updatePreview);
    </script>
    <?php endif; ?>

    <?php if ($current_page == 'privacy-policy' || $current_page == 'terms-of-service'): ?>
    <script>
        // Back to top button functionality
        const backToTopButton = document.querySelector('.back-to-top');
        
        if (backToTopButton) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.style.display = 'flex';
                } else {
                    backToTopButton.style.display = 'none';
                }
            });
            
            backToTopButton.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    </script>
    <?php endif; ?>

</body>
</html>
