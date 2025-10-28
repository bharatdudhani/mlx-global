<?php
// Simple language detection
$current_lang = 'en'; // Default to English
if (isset($_GET['lang']) && $_GET['lang'] === 'zh') {
    $current_lang = 'zh';
}

// Database connection
require_once __DIR__ . '/db_config.php';

// Initialize variables
$posts = [];
$total = 0;
$totalPages = 1;
$page = 1;
$perPage = 6;
$categoryFilter = '';
$tagFilter = '';
$searchQuery = '';

try {
    // Use the constants from db_config.php directly
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get current page for pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 6;
    $offset = ($page > 1) ? ($page * $perPage) - $perPage : 0;
    
    // Check if category filter is applied
    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
    $tagFilter = isset($_GET['tag']) ? $_GET['tag'] : '';
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
    $langFilter = isset($_GET['lang']) ? $_GET['lang'] : '';
    
    // Build WHERE clause for filtering
    $whereClause = '';
    $params = array();
    
    if (!empty($categoryFilter)) {
        $whereClause = "WHERE category = :category";
        $params[':category'] = $categoryFilter;
    }
    
    if (!empty($tagFilter)) {
        if (!empty($whereClause)) {
            $whereClause .= " AND tags LIKE :tag";
        } else {
            $whereClause = "WHERE tags LIKE :tag";
        }
        $params[':tag'] = '%' . $tagFilter . '%';
    }
    
    if (!empty($searchQuery)) {
        if (!empty($whereClause)) {
            $whereClause .= " AND (title LIKE :search OR content LIKE :search OR excerpt LIKE :search)";
        } else {
            $whereClause = "WHERE (title LIKE :search OR content LIKE :search OR excerpt LIKE :search)";
        }
        $params[':search'] = '%' . $searchQuery . '%';
    }
    
    // Get total posts for pagination
    $totalQuery = "SELECT COUNT(*) FROM posts $whereClause";
    $totalStmt = $pdo->prepare($totalQuery);
    $totalStmt->execute($params);
    $total = $totalStmt->fetchColumn();
    $totalPages = ceil($total / $perPage);
    
    // Fetch posts with pagination and filtering
    $query = "SELECT * FROM posts $whereClause ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($query);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // Log error instead of displaying to users
    error_log("Database Error: " . $e->getMessage());
    $error_message = "Database temporarily unavailable. Please try again later.";
} catch(Exception $e) {
    error_log("General Error: " . $e->getMessage());
    $error_message = "An error occurred. Please try again.";
}

// Load translations after database operations
require_once __DIR__ . '/translations.php';
?>

<?php
$page_title = "MLX - Blog";
include 'header.php';
?>

    <!-- Blog Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo __t('blog_hero_title'); ?></h1>
                <p class="lead"><?php echo __t('blog_hero_sub'); ?></p>
            </div>
        </div>
    </section>

    <!-- Blog Posts Section -->
    <section class="blog-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title"><?php echo __t('blog_latest'); ?></h2>
                    
                    <!-- Display error message if there's a database issue -->
                    <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Search Form -->
                    <form method="GET" action="blog.php" class="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control search-input" placeholder="<?php echo __t('blog_search_placeholder'); ?>" 
                                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit" class="btn btn-primary search-button">
                                <i class="fas fa-search"></i> <?php echo __t('blog_search'); ?>
                            </button>
                        </div>
                        <?php if (!empty($categoryFilter)): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($categoryFilter); ?>">
                        <?php endif; ?>
                        <?php if (!empty($tagFilter)): ?>
                            <input type="hidden" name="tag" value="<?php echo htmlspecialchars($tagFilter); ?>">
                        <?php endif; ?>
                    </form>
                    
                    <!-- Language Filter -->
                    <div class="language-filter mb-4">
                        <strong><?php echo __t('blog_filter_language'); ?>:</strong>
                        <a href="blog.php?<?php echo http_build_query(array_merge($_GET, ['lang' => 'all'])); ?>" class="badge bg-secondary <?php echo (empty($current_lang) || $current_lang === 'all') ? 'active' : ''; ?>">
                            All Languages
                        </a>
                        <a href="blog.php?<?php echo http_build_query(array_merge($_GET, ['lang' => 'en'])); ?>" class="badge bg-primary <?php echo ($current_lang === 'en') ? 'active' : ''; ?>">
                            English
                        </a>
                        <a href="blog.php?<?php echo http_build_query(array_merge($_GET, ['lang' => 'zh'])); ?>" class="badge bg-primary <?php echo ($current_lang === 'zh') ? 'active' : ''; ?>">
                            中文
                        </a>
                    </div>
                    
                    <!-- Filter Indicator -->
                    <?php if (!empty($categoryFilter) || !empty($tagFilter) || !empty($searchQuery)): ?>
                    <div class="filter-indicator">
                        <strong><?php echo __t('blog_filtering_by'); ?></strong>
                        <?php if (!empty($categoryFilter)): ?>
                            <span class="badge bg-primary"><?php echo __t('blog_filter_category'); ?> <?php echo htmlspecialchars($categoryFilter); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($tagFilter)): ?>
                            <span class="badge bg-secondary"><?php echo __t('blog_filter_tag'); ?> <?php echo htmlspecialchars($tagFilter); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($searchQuery)): ?>
                            <span class="badge bg-info"><?php echo __t('blog_filter_search'); ?> <?php echo htmlspecialchars($searchQuery); ?></span>
                        <?php endif; ?>
                        <a href="blog.php" class="clear-filter"><?php echo __t('blog_clear_filters'); ?></a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <?php if (count($posts) > 0): ?>
                            <?php foreach ($posts as $post): 
                                // Check if Chinese translation exists for this post
                                $translation_stmt = $pdo->prepare("SELECT * FROM post_translations WHERE post_id = ? AND language_id = 'zh' AND is_published = 1");
                                $translation_stmt->execute([$post['id']]);
                                $translation = $translation_stmt->fetch(PDO::FETCH_ASSOC);
                                
                                // Use translation if available and Chinese language is selected, otherwise use original
                                if ($current_lang === 'zh' && $translation) {
                                    $display_title = $translation['translated_title'];
                                    $display_excerpt = $translation['translated_excerpt'];
                                } else {
                                    $display_title = $post['title'];
                                    $display_excerpt = $post['excerpt'];
                                }
                            ?>
                                <div class="col-md-6 mb-4">
                                    <div class="blog-card">
                                        <div class="blog-image">
                                            <img src="<?php echo htmlspecialchars($post['cover_image']); ?>" alt="<?php echo htmlspecialchars($display_title); ?>">
                                        </div>
                                        <div class="blog-content">
                                            <h3 class="blog-title"><?php echo htmlspecialchars($display_title); ?></h3>
                                            <div class="blog-meta">
                                                <div><i class="far fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></div>
                                                <div><i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></div>
                                                <?php if (!empty($post['category'])): ?>
                                                    <div><i class="far fa-folder"></i> <?php echo htmlspecialchars($post['category']); ?></div>
                                                <?php endif; ?>
                                                <!-- Language availability indicator -->
                                                <div>
                                                    <i class="fas fa-language"></i> 
                                                    <span class="language-indicator">EN</span>
                                                    <?php if ($translation): ?>
                                                        <span class="language-indicator chinese">中文</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <p class="blog-excerpt"><?php echo htmlspecialchars($display_excerpt); ?></p>
                                            <a href="single-post.php?id=<?php echo $post['id']; ?><?php echo $current_lang === 'zh' ? '&lang=zh' : ''; ?>" class="read-more">
                                                <?php echo __t('blog_read_more'); ?> <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <?php if (!empty($categoryFilter) || !empty($tagFilter) || !empty($searchQuery)): ?>
                                        <?php echo __t('blog_no_results_filter'); ?> <a href="blog.php" class="alert-link"><?php echo __t('blog_view_all'); ?></a>
                                    <?php else: ?>
                                        <?php echo __t('blog_no_results'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <div class="pagination-container">
                        <nav aria-label="Blog pagination">
                            <ul class="pagination">
                                <?php 
                                // Build pagination URL with current filters
                                $paginationBaseUrl = "blog.php?";
                                $queryParams = $_GET;
                                unset($queryParams['page']); // Remove page parameter
                                
                                if (!empty($queryParams)) {
                                    $paginationBaseUrl .= http_build_query($queryParams) . '&';
                                }
                                
                                // Set maximum number of pagination links to show
                                $maxPagesToShow = 10;
                                $startPage = max(1, $page - floor($maxPagesToShow / 2));
                                $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);
                                
                                // Adjust if we're at the end
                                if ($endPage - $startPage < $maxPagesToShow - 1) {
                                    $startPage = max(1, $endPage - $maxPagesToShow + 1);
                                }
                                ?>
                                
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $paginationBaseUrl . 'page=' . ($page - 1); ?>">Previous</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $paginationBaseUrl . 'page=1'; ?>">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link pagination-ellipsis">...</span>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo $paginationBaseUrl . 'page=' . $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link pagination-ellipsis">...</span>
                                        </li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $paginationBaseUrl . 'page=' . $totalPages; ?>"><?php echo $totalPages; ?></a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $paginationBaseUrl . 'page=' . ($page + 1); ?>">Next</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
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
                            // Check if database connection is available before trying to fetch categories
                            if (isset($pdo)) {
                                try {
                                    // Fetch categories and counts
                                    $catStmt = $pdo->query("SELECT category, COUNT(*) as count FROM posts WHERE category IS NOT NULL AND category != '' GROUP BY category ORDER BY count DESC");
                                    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    if (count($categories) > 0):
                                        foreach ($categories as $category):
                                            $isActive = ($categoryFilter === $category['category']) ? 'active-filter' : '';
                            ?>
                                            <li><a href="blog.php?category=<?php echo urlencode($category['category']); ?>" class="<?php echo $isActive; ?>"><?php echo htmlspecialchars($category['category']); ?> <span>(<?php echo $category['count']; ?>)</span></a></li>
                            <?php 
                                        endforeach;
                                    else:
                            ?>
                                        <li><?php echo __t('blog_no_categories'); ?></li>
                            <?php
                                    endif;
                                } catch (PDOException $e) {
                                    error_log("Categories query error: " . $e->getMessage());
                            ?>
                                    <li><?php echo __t('blog_unable_categories'); ?></li>
                            <?php
                                }
                            } else {
                            ?>
                                <li><?php echo __t('blog_unable_categories'); ?></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                    
                    <!-- Recent Posts Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php echo __t('blog_sidebar_recent'); ?></h3>
                        <?php
                        // Check if database connection is available
                        if (isset($pdo)) {
                            try {
                                // Fetch recent posts
                                $recentStmt = $pdo->query("SELECT id, title, cover_image, created_at FROM posts ORDER BY created_at DESC LIMIT 3");
                                $recentPosts = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (count($recentPosts) > 0):
                                    foreach ($recentPosts as $recent):
                        ?>
                                        <div class="recent-post">
                                            <div class="recent-post-image">
                                                <img src="<?php echo htmlspecialchars($recent['cover_image']); ?>" alt="Recent post">
                                            </div>
                                            <div class="recent-post-content">
                                                <h4><a href="single-post.php?id=<?php echo $recent['id']; ?>"><?php echo htmlspecialchars($recent['title']); ?></a></h4>
                                                <div class="recent-post-date"><?php echo date('F j, Y', strtotime($recent['created_at'])); ?></div>
                                            </div>
                                        </div>
                        <?php 
                                    endforeach;
                                else:
                        ?>
                                    <p><?php echo __t('blog_no_recent'); ?></p>
                        <?php
                                endif;
                            } catch (PDOException $e) {
                                error_log("Recent posts query error: " . $e->getMessage());
                        ?>
                                <p><?php echo __t('blog_unable_recent'); ?></p>
                        <?php
                            }
                        } else {
                        ?>
                            <p><?php echo __t('blog_unable_recent'); ?></p>
                        <?php
                        }
                        ?>
                    </div>
                    
                    <!-- Tags Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php echo __t('blog_sidebar_tags'); ?></h3>
                        <div class="tags-cloud" id="tagsContainer">
                            <?php
                            // Check if database connection is available
                            if (isset($pdo)) {
                                try {
                                    // Fetch all tags
                                    $tagsStmt = $pdo->query("SELECT tags FROM posts WHERE tags IS NOT NULL AND tags != ''");
                                    $allTags = [];
                                    
                                    while ($tagRow = $tagsStmt->fetch(PDO::FETCH_ASSOC)) {
                                        $postTags = explode(',', $tagRow['tags']);
                                        foreach ($postTags as $tag) {
                                            $tag = trim($tag);
                                            if (!empty($tag)) {
                                                if (isset($allTags[$tag])) {
                                                    $allTags[$tag]++;
                                                } else {
                                                    $allTags[$tag] = 1;
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Sort tags by frequency (optional)
                                    arsort($allTags);
                                    
                                    // Display tags - limit to 15 initially
                                    $tagLimit = 15;
                                    $tagCount = 0;
                                    
                                    if (count($allTags) > 0):
                                        foreach ($allTags as $tag => $count):
                                            $tagCount++;
                                            $isActive = ($tagFilter === $tag) ? 'active-filter' : '';
                                            $hiddenClass = ($tagCount > $tagLimit) ? 'hidden-tag' : '';
                            ?>
                                            <a href="blog.php?tag=<?php echo urlencode($tag); ?>" class="tag <?php echo $isActive; ?> <?php echo $hiddenClass; ?>" data-tag="<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?> <span>(<?php echo $count; ?>)</span></a>
                            <?php 
                                        endforeach;
                                    else:
                            ?>
                                        <p><?php echo __t('blog_no_tags'); ?></p>
                            <?php
                                    endif;
                                } catch (PDOException $e) {
                                    error_log("Tags query error: " . $e->getMessage());
                            ?>
                                    <p><?php echo __t('blog_unable_tags'); ?></p>
                            <?php
                                }
                            } else {
                            ?>
                                <p><?php echo __t('blog_unable_tags'); ?></p>
                            <?php
                            }
                            ?>
                        </div>
                        
                        <?php if (isset($allTags) && count($allTags) > $tagLimit): ?>
                            <button class="show-more-tags" id="showMoreTags"><?php echo __t('blog_show_more_tags'); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    // Tags show more functionality
    document.addEventListener('DOMContentLoaded', function() {
        const showMoreBtn = document.getElementById('showMoreTags');
        if (showMoreBtn) {
            showMoreBtn.addEventListener('click', function() {
                const hiddenTags = document.querySelectorAll('.hidden-tag');
                hiddenTags.forEach(tag => {
                    tag.classList.remove('hidden-tag');
                });
                showMoreBtn.style.display = 'none';
            });
        }
    });
    </script>

<?php 
// Set variable for footer to show social icons
$show_social_icons = true;
include 'footer.php'; 
?>