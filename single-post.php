<?php
// Simple language detection from header
$current_lang = 'en'; // Default to English
if (isset($_GET['lang']) && $_GET['lang'] === 'zh') {
    $current_lang = 'zh';
}

// Database connection
require_once __DIR__ . '/db_config.php';

// Initialize variables
$post = [];
$relatedPosts = [];
$post_id = 0;
$translation = null;
$has_chinese = false;

// Get post ID from URL
$post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($post_id === 0) {
    // No post ID, redirect to blog
    header("Location: blog.php");
    exit();
}

 try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($post_id > 0) {
        // Fetch the specific post
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            $error_message = "Post not found.";
        } else {
            // Check if translation exists for this post
            if ($current_lang === 'zh') {
                $translation_stmt = $pdo->prepare("SELECT * FROM post_translations WHERE post_id = :id AND language_id = 'zh' AND is_published = 1");
                $translation_stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
                $translation_stmt->execute();
                $translation = $translation_stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Check if Chinese translation exists (for language indicator)
            $chinese_stmt = $pdo->prepare("SELECT * FROM post_translations WHERE post_id = :id AND language_id = 'zh' AND is_published = 1");
            $chinese_stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
            $chinese_stmt->execute();
            $has_chinese = $chinese_stmt->fetch();

            // Fetch related posts (same category)
            $relatedStmt = $pdo->prepare("SELECT id, title, cover_image, created_at FROM posts WHERE category = :category AND id != :id ORDER BY created_at DESC LIMIT 3");
            $relatedStmt->bindValue(':category', $post['category'], PDO::PARAM_STR);
            $relatedStmt->bindValue(':id', $post_id, PDO::PARAM_INT);
            $relatedStmt->execute();
            $relatedPosts = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        $error_message = "Invalid post ID.";
    }

} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}

// Load translations for UI elements (from translations.php)
require_once __DIR__ . '/translations.php';

// Determine content to display (post content from database)
$display_title = $post ? ($translation ? $translation['translated_title'] : $post['title']) : '';
$display_content = $post ? ($translation ? $translation['translated_content'] : $post['content']) : '';
?>

<?php
$page_title = isset($display_title) ? htmlspecialchars($display_title) . ' - MLX Blog' : 'Post Not Found - MLX Blog';
include 'header.php';
?>

<?php if (isset($error_message)): ?>
    <!-- Error Message Display -->
    <div class="container mt-4">
        <div class="error-message">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!isset($error_message) && !empty($post)): ?>
    <!-- Single Post Header -->
    <section class="hero-section">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($display_title); ?></h1>
            <p class="lead"><?php echo __t('single_posted_in'); ?>     <?php echo htmlspecialchars($post['category']); ?></p>
        </div>
    </section>

    <!-- Single Post Content -->
    <section class="blog-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <article>
                        <div class="post-header">
                            <h1 class="post-title"><?php echo htmlspecialchars($display_title); ?></h1>
                            <div class="post-meta">
                                <div><i class="far fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></div>
                                <div><i class="far fa-calendar"></i>
                                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?></div>
                                <div><i class="far fa-folder"></i> <?php echo htmlspecialchars($post['category']); ?>
                                </div>
                                <?php if (!empty($post['tags'])): ?>
                                    <div><i class="fas fa-tags"></i> <?php echo htmlspecialchars($post['tags']); ?></div>
                                <?php endif; ?>
                                <!-- Language availability indicator -->
                                <div>
                                    <i class="fas fa-language"></i>
                                    <span class="language-indicator">EN</span>
                                    <?php if ($has_chinese): ?>
                                        <span class="language-indicator chinese">中文</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="post-image mb-4">
                            <img src="<?php echo htmlspecialchars($post['cover_image']); ?>"
                                alt="<?php echo htmlspecialchars($display_title); ?>" class="img-fluid rounded">
                        </div>

                        <div class="post-content">
                            <?php echo $display_content; ?>
                        </div>

                        <!-- Additional Images -->
                        <?php if (!empty($post['additional_images'])): ?>
                            <?php
                            $additional_images = json_decode($post['additional_images'], true);
                            if (is_array($additional_images) && count($additional_images) > 0):
                                foreach ($additional_images as $image_data):
                                    if (!empty($image_data['path']) && file_exists($image_data['path'])):
                                        ?>
                                        <div class="post-image mb-4 mt-4">
                                            <?php if (!empty($image_data['link'])): ?>
                                                <a href="<?php echo htmlspecialchars($image_data['link']); ?>" target="_blank">
                                                    <img src="<?php echo htmlspecialchars($image_data['path']); ?>" alt="Additional image"
                                                        class="img-fluid rounded">
                                                </a>
                                            <?php else: ?>
                                                <img src="<?php echo htmlspecialchars($image_data['path']); ?>" alt="Additional image"
                                                    class="img-fluid rounded">
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        <?php endif; ?>
                    </article>

                    <!-- Post Navigation -->
                    <div class="post-navigation">
                        <?php
                        // Get previous post
                        $prevStmt = $pdo->prepare("SELECT id, title FROM posts WHERE id < :id ORDER BY id DESC LIMIT 1");
                        $prevStmt->bindValue(':id', $post_id, PDO::PARAM_INT);
                        $prevStmt->execute();
                        $prevPost = $prevStmt->fetch(PDO::FETCH_ASSOC);

                        // Get next post
                        $nextStmt = $pdo->prepare("SELECT id, title FROM posts WHERE id > :id ORDER by id ASC LIMIT 1");
                        $nextStmt->bindValue(':id', $post_id, PDO::PARAM_INT);
                        $nextStmt->execute();
                        $nextPost = $nextStmt->fetch(PDO::FETCH_ASSOC);
                        ?>

                        <?php if ($prevPost): ?>
                            <div class="nav-previous">
                                <a
                                    href="single-post.php?id=<?php echo $prevPost['id']; ?><?php echo $current_lang === 'zh' ? '&lang=zh' : ''; ?>">
                                    <i class="fas fa-arrow-left"></i> <?php echo __t('single_prev_post'); ?>
                                    <p class="small mb-0 for-gap"><?php echo htmlspecialchars($prevPost['title']); ?></p>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($nextPost): ?>
                            <div class="nav-next">
                                <a
                                    href="single-post.php?id=<?php echo $nextPost['id']; ?><?php echo $current_lang === 'zh' ? '&lang=zh' : ''; ?>">
                                    <?php echo __t('single_next_post'); ?> <i class="fas fa-arrow-right"></i>
                                    <p class="small mb-0"><?php echo htmlspecialchars($nextPost['title']); ?></p>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Related Posts -->
                    <?php if (count($relatedPosts) > 0): ?>
                        <div class="related-posts">
                            <h3 class="related-title"><?php echo __t('single_related'); ?></h3>
                            <div class="row">
                                <?php foreach ($relatedPosts as $related):
                                    // For related posts, we'll just show the original titles
                                    // (you could add translation logic here too if needed)
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="blog-card">
                                            <div class="blog-image">
                                                <img src="<?php echo htmlspecialchars($related['cover_image']); ?>"
                                                    alt="<?php echo htmlspecialchars($related['title']); ?>">
                                            </div>
                                            <div class="blog-content">
                                                <h4 class="blog-title"><?php echo htmlspecialchars($related['title']); ?></h4>
                                                <a href="single-post.php?id=<?php echo $related['id']; ?><?php echo $current_lang === 'zh' ? '&lang=zh' : ''; ?>"
                                                    class="read-more"><?php echo __t('single_read_more'); ?> <i
                                                        class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 sidebar">
                    <!-- About Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php echo __t('blog_sidebar_about_title'); ?></h3>
                        <p><?php echo __t('blog_sidebar_about_desc'); ?></p>
                    </div>

                    <!-- Categories Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php echo __t('blog_sidebar_categories'); ?></h3>
                        <ul class="categories-list">
                            <?php
                            // Fetch categories and counts
                            $catStmt = $pdo->query("SELECT category, COUNT(*) as count FROM posts GROUP BY category ORDER BY count DESC");
                            $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($categories) > 0):
                                foreach ($categories as $category):
                                    ?>
                                    <li><a
                                            href="blog.php?category=<?php echo urlencode($category['category']); ?><?php echo $current_lang === 'zh' ? '&lang=zh' : ''; ?>"><?php echo htmlspecialchars($category['category']); ?>
                                            <span>(<?php echo $category['count']; ?>)</span></a></li>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <li><?php echo __t('blog_no_categories'); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php echo __t('blog_sidebar_recent'); ?></h3>
                        <?php
                        // Fetch recent posts
                        $recentStmt = $pdo->query("SELECT id, title, cover_image, created_at FROM posts ORDER BY created_at DESC LIMIT 3");
                        $recentPosts = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($recentPosts) > 0):
                            foreach ($recentPosts as $recent):
                                // Check if translation exists for this recent post
                                $recent_translation = null;
                                if ($current_lang === 'zh') {
                                    $recent_translation_stmt = $pdo->prepare("SELECT translated_title FROM post_translations WHERE post_id = :id AND language_id = 'zh' AND is_published = 1");
                                    $recent_translation_stmt->bindValue(':id', $recent['id'], PDO::PARAM_INT);
                                    $recent_translation_stmt->execute();
                                    $recent_translation = $recent_translation_stmt->fetch(PDO::FETCH_ASSOC);
                                }

                                // Use translation if available, otherwise use original title
                                $recent_display_title = $recent_translation ? $recent_translation['translated_title'] : $recent['title'];
                                ?>
                                <div class="recent-post">
                                    <div class="recent-post-image">
                                        <img src="<?php echo htmlspecialchars($recent['cover_image']); ?>" alt="Recent post">
                                    </div>
                                    <div class="recent-post-content">
                                        <h4><a
                                                href="single-post.php?id=<?php echo $recent['id']; ?><?php echo $current_lang === 'zh' ? '&lang=zh' : ''; ?>">
                                                <?php echo htmlspecialchars($recent_display_title); ?>
                                            </a></h4>
                                        <div class="recent-post-date">
                                            <?php echo date('F j, Y', strtotime($recent['created_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        else:
                            ?>
                            <p><?php echo __t('blog_no_recent'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php
// Set variable for footer to show social icons
$show_social_icons = true;
include 'footer.php';
?>