<?php
// Start session at the very beginning to prevent headers already sent error
session_start();

// Database connection
require_once __DIR__ . '/db_config.php';

// Initialize variables
$title = $excerpt = $content = $author = $category = $tags = "";
$chinese_title = $chinese_excerpt = $chinese_content = "";
$cover_image = $additional_images = "";
$cover_image_link = "";
$additional_image_links = array();
$success_message = $error_message = "";

// Function to sanitize file names
function sanitizeFileName($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
    $filename = preg_replace('/_{2,}/', '_', $filename);
    $filename = trim($filename, '_');
    return $filename;
}

// Function to validate image
function validateImage($tmp_name) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $tmp_name);
    finfo_close($finfo);
    
    return in_array($mime_type, $allowed_types);
}

// Function to validate file - NO SIZE LIMIT
function validateFile($tmp_name) {
    $allowed_types = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/zip',
        'application/x-rar-compressed'
    ];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $tmp_name);
    finfo_close($finfo);
    
    return in_array($mime_type, $allowed_types);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['publish'])) {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get form data
        $title = $_POST['title'];
        $excerpt = $_POST['excerpt'];
        $content = $_POST['content'];
        $author = $_POST['author'];
        $category = $_POST['category'];
        $tags = $_POST['tags'];
        $cover_image_link = $_POST['cover_image_link'] ?? '';
        
        // Get Chinese content (optional)
        $chinese_title = $_POST['chinese_title'] ?? '';
        $chinese_excerpt = $_POST['chinese_excerpt'] ?? '';
        $chinese_content = $_POST['chinese_content'] ?? '';
        
        // Get additional image links
        $additional_image_links = $_POST['additional_image_links'] ?? array();
        
        // First insert post to get the ID
        $stmt = $pdo->prepare("INSERT INTO posts (title, excerpt, content, author, category, tags, cover_image_link) 
                              VALUES (:title, :excerpt, :content, :author, :category, :tags, :cover_image_link)");
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':excerpt', $excerpt);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':tags', $tags);
        $stmt->bindParam(':cover_image_link', $cover_image_link);
        
        if ($stmt->execute()) {
            $post_id = $pdo->lastInsertId();
            
            // Create upload directory for this post in public_html
            $upload_dir = "uploads/posts/$post_id/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $cover_image_path = "";
            $additional_images_paths = [];
            
            // Handle cover image upload
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $file_extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $cover_image_name = $post_id . '_cover_' . time() . '.' . $file_extension;
                $cover_image_tmp = $_FILES['cover_image']['tmp_name'];
                
                // Validate image
                if (validateImage($cover_image_tmp)) {
                    if (move_uploaded_file($cover_image_tmp, $upload_dir . $cover_image_name)) {
                        $cover_image_path = $upload_dir . $cover_image_name;
                    } else {
                        throw new Exception("Failed to upload cover image.");
                    }
                } else {
                    throw new Exception("Invalid cover image format. Only JPG, PNG, GIF, and WebP are allowed.");
                }
            }
            
            // Handle additional images upload
            if (!empty($_FILES['additional_images']['name'][0])) {
                foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['additional_images']['error'][$key] == 0) {
                        $file_extension = pathinfo($_FILES['additional_images']['name'][$key], PATHINFO_EXTENSION);
                        $additional_image_name = $post_id . '_' . $key . '_' . time() . '.' . $file_extension;
                        
                        // Validate image
                        if (validateImage($tmp_name)) {
                            if (move_uploaded_file($tmp_name, $upload_dir . $additional_image_name)) {
                                $additional_images_paths[] = array(
                                    'path' => $upload_dir . $additional_image_name,
                                    'link' => $additional_image_links[$key] ?? ''
                                );
                            }
                        }
                    }
                }
            }
            
            // Handle PDF files upload - NO SIZE LIMIT
            $pdf_files = [];
            if (!empty($_FILES['pdf_files']['name'][0])) {
                foreach ($_FILES['pdf_files']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['pdf_files']['error'][$key] == 0) {
                        $original_name = $_FILES['pdf_files']['name'][$key];
                        $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
                        
                        // Only process PDF files for embedding
                        if (strtolower($file_extension) === 'pdf') {
                            $pdf_name = $post_id . '_pdf_' . $key . '_' . time() . '.pdf';
                            
                            if (move_uploaded_file($tmp_name, $upload_dir . $pdf_name)) {
                                $pdf_files[] = array(
                                    'original_name' => $original_name,
                                    'file_name' => $pdf_name,
                                    'file_path' => $upload_dir . $pdf_name,
                                    'file_size' => $_FILES['pdf_files']['size'][$key]
                                );
                            }
                        }
                    }
                }
                
                // Insert PDF files into database
                if (!empty($pdf_files)) {
                    $pdf_stmt = $pdo->prepare("INSERT INTO post_pdfs (post_id, original_name, file_name, file_path, file_size) 
                                              VALUES (:post_id, :original_name, :file_name, :file_path, :file_size)");
                    
                    foreach ($pdf_files as $pdf) {
                        $pdf_stmt->bindParam(':post_id', $post_id);
                        $pdf_stmt->bindParam(':original_name', $pdf['original_name']);
                        $pdf_stmt->bindParam(':file_name', $pdf['file_name']);
                        $pdf_stmt->bindParam(':file_path', $pdf['file_path']);
                        $pdf_stmt->bindParam(':file_size', $pdf['file_size']);
                        $pdf_stmt->execute();
                    }
                }
            }
            
            // Update post with image paths and links
            $additional_images_str = json_encode($additional_images_paths);
            $update_stmt = $pdo->prepare("UPDATE posts SET cover_image = :cover_image, additional_images = :additional_images WHERE id = :id");
            
            $update_stmt->bindParam(':cover_image', $cover_image_path);
            $update_stmt->bindParam(':additional_images', $additional_images_str);
            $update_stmt->bindParam(':id', $post_id);
            
            if ($update_stmt->execute()) {
                // Insert Chinese translation if provided
                if (!empty($chinese_title) || !empty($chinese_excerpt) || !empty($chinese_content)) {
                    $translation_stmt = $pdo->prepare("INSERT INTO post_translations 
                        (post_id, language_id, translated_title, translated_excerpt, translated_content, is_published) 
                        VALUES (:post_id, 'zh', :translated_title, :translated_excerpt, :translated_content, 1)");
                    
                    $translation_stmt->bindParam(':post_id', $post_id);
                    $translation_stmt->bindParam(':translated_title', $chinese_title);
                    $translation_stmt->bindParam(':translated_excerpt', $chinese_excerpt);
                    $translation_stmt->bindParam(':translated_content', $chinese_content);
                    
                    if (!$translation_stmt->execute()) {
                        throw new Exception("Error creating Chinese translation.");
                    }
                }
                
                $success_message = "Post created successfully!" . 
                    (!empty($chinese_title) ? " Chinese translation also added." : "") .
                    (!empty($pdf_files) ? " " . count($pdf_files) . " PDF file(s) uploaded and will be embedded in the post." : "");
                
                // Clear form
                $title = $excerpt = $content = $author = $category = $tags = "";
                $chinese_title = $chinese_excerpt = $chinese_content = "";
                $cover_image_link = "";
                $additional_image_links = array();
            } else {
                throw new Exception("Error updating post with image information.");
            }
            
        } else {
            $error_message = "Error creating post. Please try again.";
        }
        
    } catch(PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    } catch(Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<?php
$page_title = "Add New Post - MLX Blog";
include 'header.php';
?>

    <!-- Form Section -->
    <section class="form-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <h2 class="section-title">Create New Blog Post</h2>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" id="postForm">
                        <input type="hidden" name="publish" value="1">
                        
                        <!-- Language Tabs -->
                        <ul class="nav nav-tabs mb-4" id="languageTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab" aria-controls="english" aria-selected="true">
                                    <i class="fas fa-language me-1"></i> English Content *
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="chinese-tab" data-bs-toggle="tab" data-bs-target="#chinese" type="button" role="tab" aria-controls="chinese" aria-selected="false">
                                    <i class="fas fa-language me-1"></i> Chinese Content (Optional)
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="languageTabsContent">
                            <!-- English Content Tab -->
                            <div class="tab-pane fade show active" id="english" role="tabpanel" aria-labelledby="english-tab">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Post Title *</label>
                                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="excerpt" class="form-label">Excerpt *</label>
                                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" required><?php echo htmlspecialchars($excerpt); ?></textarea>
                                            <div class="form-text">A short summary of your post (appears on blog listing).</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="content" class="form-label">Content *</label>
                                            <div class="editor-toolbar">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('bold', 'content')"><i class="fas fa-bold"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('italic', 'content')"><i class="fas fa-italic"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('underline', 'content')"><i class="fas fa-underline"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('heading', 'content')"><i class="fas fa-heading"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('link', 'content')"><i class="fas fa-link"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('image', 'content')"><i class="fas fa-image"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('ul', 'content')"><i class="fas fa-list-ul"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('ol', 'content')"><i class="fas fa-list-ol"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('blockquote', 'content')"><i class="fas fa-quote-right"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('code', 'content')"><i class="fas fa-code"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="autoFormat('content')"><i class="fas fa-magic"></i> Auto Format</button>
                                            </div>
                                            <textarea class="form-control" id="content" name="content" rows="15" required><?php echo htmlspecialchars($content); ?></textarea>
                                            <div class="form-text">Use the toolbar above to format your content or use the Auto Format button.</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <!-- Common fields that don't need translation -->
                                        <div class="mb-3">
                                            <label for="cover_image" class="form-label">Cover Image *</label>
                                            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" required>
                                            <div class="form-text">Main image for your post (JPG, PNG, GIF, WebP only).</div>
                                            
                                            <div class="image-preview mt-2">
                                                <span id="coverPreviewText">Image preview will appear here</span>
                                                <img id="coverPreview" src="" alt="Cover preview" style="display: none; max-width: 100%; max-height: 200px;">
                                            </div>
                                            
                                            <div class="image-link-container mt-2">
                                                <div class="image-link-label">Cover Image Link (optional):</div>
                                                <input type="url" class="form-control" id="cover_image_link" name="cover_image_link" placeholder="https://example.com" value="<?php echo htmlspecialchars($cover_image_link); ?>">
                                                <div class="form-text">URL to link this image to (leave empty for no link)</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="additional_images" class="form-label">Additional Images</label>
                                            <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" multiple>
                                            <div class="form-text">You can select multiple images to include in your post.</div>
                                            
                                            <div class="additional-images-preview mt-2" id="additionalImagesPreview">
                                                <!-- Additional images preview will appear here -->
                                            </div>
                                            
                                            <div id="additionalImageLinksContainer">
                                                <!-- Additional image links will be added here dynamically -->
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="pdf_files" class="form-label">PDF Files (Embedded Display)</label>
                                            <input type="file" class="form-control" id="pdf_files" name="pdf_files[]" accept=".pdf" multiple>
                                            <div class="form-text">PDF files will be embedded in the post for viewing and download (No size limit)</div>
                                            
                                            <div class="pdf-files-preview mt-2" id="pdfFilesPreview">
                                                <!-- PDF files preview will appear here -->
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="file_attachments" class="form-label">Other File Attachments</label>
                                            <input type="file" class="form-control" id="file_attachments" name="file_attachments[]" multiple>
                                            <div class="form-text">Optional: Word, Excel, PowerPoint, ZIP files (No size limit)</div>
                                            
                                            <div class="file-attachments-preview mt-2" id="fileAttachmentsPreview">
                                                <!-- File attachments preview will appear here -->
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="author" class="form-label">Author *</label>
                                            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category *</label>
                                            <select class="form-select" id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="Textiles" <?php echo ($category == 'Textiles') ? 'selected' : ''; ?>>Textiles</option>
                                                <option value="International Trade" <?php echo ($category == 'International Trade') ? 'selected' : ''; ?>>International Trade</option>
                                                <option value="IT Services" <?php echo ($category == 'IT Services') ? 'selected' : ''; ?>>IT Services</option>
                                                <option value="Product Sourcing" <?php echo ($category == 'Product Sourcing') ? 'selected' : ''; ?>>Product Sourcing</option>
                                                <option value="Quality Inspection" <?php echo ($category == 'Quality Inspection') ? 'selected' : ''; ?>>Quality Inspection</option>
                                                <option value="Business Strategy" <?php echo ($category == 'Business Strategy') ? 'selected' : ''; ?>>Business Strategy</option>
                                                <option value="Affiliates" <?php echo ($category == 'Affiliates') ? 'selected' : ''; ?>>Affiliates</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="tags" class="form-label">Tags</label>
                                            <input type="text" class="form-control" id="tags" name="tags" value="<?php echo htmlspecialchars($tags); ?>">
                                            <div class="form-text">Comma-separated tags (e.g., textiles,innovation,sustainability)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Chinese Content Tab -->
                            <div class="tab-pane fade" id="chinese" role="tabpanel" aria-labelledby="chinese-tab">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Chinese content is optional. If provided, it will be stored as a translation.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="chinese_title" class="form-label">Chinese Title</label>
                                            <input type="text" class="form-control" id="chinese_title" name="chinese_title" value="<?php echo htmlspecialchars($chinese_title); ?>">
                                            <div class="form-text">Title in Chinese (optional)</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="chinese_excerpt" class="form-label">Chinese Excerpt</label>
                                            <textarea class="form-control" id="chinese_excerpt" name="chinese_excerpt" rows="3"><?php echo htmlspecialchars($chinese_excerpt); ?></textarea>
                                            <div class="form-text">Short summary in Chinese (optional)</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="chinese_content" class="form-label">Chinese Content</label>
                                            <div class="editor-toolbar">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('bold', 'chinese_content')"><i class="fas fa-bold"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('italic', 'chinese_content')"><i class="fas fa-italic"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('underline', 'chinese_content')"><i class="fas fa-underline"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('heading', 'chinese_content')"><i class="fas fa-heading"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('link', 'chinese_content')"><i class="fas fa-link"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('image', 'chinese_content')"><i class="fas fa-image"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('ul', 'chinese_content')"><i class="fas fa-list-ul"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('ol', 'chinese_content')"><i class="fas fa-list-ol"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('blockquote', 'chinese_content')"><i class="fas fa-quote-right"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('code', 'chinese_content')"><i class="fas fa-code"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="autoFormat('chinese_content')"><i class="fas fa-magic"></i> Auto Format</button>
                                            </div>
                                            <textarea class="form-control" id="chinese_content" name="chinese_content" rows="15"><?php echo htmlspecialchars($chinese_content); ?></textarea>
                                            <div class="form-text">Content in Chinese (optional). Use the toolbar above to format your content.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Preview Modal -->
                    <div class="modal fade preview-modal" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="previewModalLabel">Post Preview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <ul class="nav nav-pills mb-3" id="previewTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="preview-english-tab" data-bs-toggle="pill" data-bs-target="#preview-english" type="button" role="tab" aria-controls="preview-english" aria-selected="true">English</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="preview-chinese-tab" data-bs-toggle="pill" data-bs-target="#preview-chinese" type="button" role="tab" aria-controls="preview-chinese" aria-selected="false">Chinese</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="previewTabsContent">
                                        <div class="tab-pane fade show active" id="preview-english" role="tabpanel" aria-labelledby="preview-english-tab">
                                            <div id="previewContentEnglish"></div>
                                        </div>
                                        <div class="tab-pane fade" id="preview-chinese" role="tabpanel" aria-labelledby="preview-chinese-tab">
                                            <div id="previewContentChinese"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('postForm').submit()">Publish Post</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button type="button" class="btn btn-preview" onclick="previewPost()">
                            <i class="fas fa-eye me-2"></i> Preview Post
                        </button>
                        <button type="submit" form="postForm" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i> Create Post
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    // Function to preview the post
    function previewPost() {
        // Validate required fields
        const title = document.getElementById('title').value;
        const excerpt = document.getElementById('excerpt').value;
        const content = document.getElementById('content').value;
        const author = document.getElementById('author').value;
        const category = document.getElementById('category').value;
        const coverImage = document.getElementById('cover_image').files[0];
        
        if (!title || !excerpt || !content || !author || !category || !coverImage) {
            alert('Please fill all required fields and upload a cover image before previewing.');
            return;
        }
        
        // Get form values
        const tags = document.getElementById('tags').value;
        const coverImageLink = document.getElementById('cover_image_link').value;
        const chineseTitle = document.getElementById('chinese_title').value;
        const chineseExcerpt = document.getElementById('chinese_excerpt').value;
        const chineseContent = document.getElementById('chinese_content').value;
        
        // Create English preview content
        let previewHTMLEnglish = createPreviewContent(title, excerpt, content, author, category, tags, coverImage, coverImageLink);
        document.getElementById('previewContentEnglish').innerHTML = previewHTMLEnglish;
        
        // Create Chinese preview content if available
        if (chineseTitle || chineseExcerpt || chineseContent) {
            let previewHTMLChinese = createPreviewContent(
                chineseTitle || title, 
                chineseExcerpt || excerpt, 
                chineseContent || content, 
                author, 
                category, 
                tags, 
                coverImage, 
                coverImageLink,
                true
            );
            document.getElementById('previewContentChinese').innerHTML = previewHTMLChinese;
            
            // Enable Chinese tab
            document.getElementById('preview-chinese-tab').classList.remove('disabled');
        } else {
            document.getElementById('previewContentChinese').innerHTML = '<div class="alert alert-info">No Chinese content provided.</div>';
            // Disable Chinese tab
            document.getElementById('preview-chinese-tab').classList.add('disabled');
        }
        
        // Show the modal
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    }
    
    // Helper function to create preview content
    function createPreviewContent(title, excerpt, content, author, category, tags, coverImage, coverImageLink, isChinese = false) {
        let previewHTML = `
            <article class="blog-post-preview">
                <h1 class="blog-title">${escapeHTML(title)} ${isChinese ? '<small>(Chinese)</small>' : ''}</h1>
                <div class="blog-meta">
                    <span class="author">By: ${escapeHTML(author)}</span> | 
                    <span class="category">Category: ${escapeHTML(category)}</span> | 
                    <span class="date">Date: ${new Date().toLocaleDateString()}</span>
                </div>
                <div class="cover-image-preview">
        `;
        
        // Add cover image preview
        if (coverImage) {
            const coverImageUrl = URL.createObjectURL(coverImage);
            if (coverImageLink) {
                previewHTML += `<a href="${escapeHTML(coverImageLink)}" target="_blank"><img src="${coverImageUrl}" alt="Cover image" class="img-fluid"></a>`;
            } else {
                previewHTML += `<img src="${coverImageUrl}" alt="Cover image" class="img-fluid">`;
            }
        }
        
        previewHTML += `
                </div>
                <div class="excerpt-preview">
                    <h3>Excerpt</h3>
                    <p>${escapeHTML(excerpt)}</p>
                </div>
                <div class="content-preview">
                    <h3>Content</h3>
                    <div>${formatContent(content)}</div>
                </div>
        `;
        
        // Add tags if available
        if (tags) {
            previewHTML += `
                <div class="tags-preview mt-3">
                    <strong>Tags:</strong> ${escapeHTML(tags)}
                </div>
            `;
        }
        
        previewHTML += `</article>`;
        
        return previewHTML;
    }
    
    // Helper function to escape HTML
    function escapeHTML(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Helper function to format content (preserve line breaks and basic HTML)
    function formatContent(content) {
        // Preserve line breaks
        content = content.replace(/\n/g, '<br>');
        
        // Allow basic HTML tags but sanitize to prevent XSS
        const allowedTags = ['b', 'i', 'u', 'strong', 'em', 'br', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'a', 'img', 'blockquote', 'code'];
        const div = document.createElement('div');
        div.innerHTML = content;
        
        // Remove any tags not in the allowed list
        const allElements = div.getElementsByTagName('*');
        for (let i = allElements.length - 1; i >= 0; i--) {
            const element = allElements[i];
            if (!allowedTags.includes(element.tagName.toLowerCase())) {
                element.parentNode.removeChild(element);
            }
        }
        
        return div.innerHTML;
    }
    
    // Preview cover image when selected
    document.getElementById('cover_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreview').src = e.target.result;
                document.getElementById('coverPreview').style.display = 'block';
                document.getElementById('coverPreviewText').style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Preview additional images when selected
    document.getElementById('additional_images').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('additionalImagesPreview');
        const linksContainer = document.getElementById('additionalImageLinksContainer');
        
        previewContainer.innerHTML = '';
        linksContainer.innerHTML = '';
        
        if (files && files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create image preview
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'additional-image-preview mb-2';
                    imgDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Additional image ${i+1}" style="max-width: 100px; max-height: 100px;">
                        <small>${file.name}</small>
                    `;
                    previewContainer.appendChild(imgDiv);
                    
                    // Create link input for this image
                    const linkDiv = document.createElement('div');
                    linkDiv.className = 'mb-2';
                    linkDiv.innerHTML = `
                        <label class="form-label small">Link for ${file.name}:</label>
                        <input type="url" class="form-control form-control-sm" name="additional_image_links[]" placeholder="https://example.com">
                    `;
                    linksContainer.appendChild(linkDiv);
                }
                
                reader.readAsDataURL(file);
            }
        }
    });
    
    // Preview PDF files when selected
    document.getElementById('pdf_files').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('pdfFilesPreview');
        
        previewContainer.innerHTML = '';
        
        if (files && files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Create PDF preview
                const pdfDiv = document.createElement('div');
                pdfDiv.className = 'pdf-file-preview mb-2 p-2 border rounded bg-light';
                pdfDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                        <div>
                            <div class="small fw-bold">${file.name}</div>
                            <div class="small text-muted">${formatFileSize(file.size)} - Will be embedded in post</div>
                        </div>
                    </div>
                `;
                previewContainer.appendChild(pdfDiv);
            }
        }
    });
    
    // Preview file attachments when selected
    document.getElementById('file_attachments').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('fileAttachmentsPreview');
        
        previewContainer.innerHTML = '';
        
        if (files && files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Create file preview
                const fileDiv = document.createElement('div');
                fileDiv.className = 'file-attachment-preview mb-2 p-2 border rounded';
                fileDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file me-2 text-muted"></i>
                        <div>
                            <div class="small fw-bold">${file.name}</div>
                            <div class="small text-muted">${formatFileSize(file.size)}</div>
                        </div>
                    </div>
                `;
                previewContainer.appendChild(fileDiv);
            }
        }
    });
    
    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Text formatting functions
    function formatText(formatType, textareaId) {
        const textarea = document.getElementById(textareaId);
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        const selectedText = textarea.value.substring(startPos, endPos);
        
        let formattedText = '';
        
        switch(formatType) {
            case 'bold':
                formattedText = `<strong>${selectedText}</strong>`;
                break;
            case 'italic':
                formattedText = `<em>${selectedText}</em>`;
                break;
            case 'underline':
                formattedText = `<u>${selectedText}</u>`;
                break;
            case 'heading':
                formattedText = `<h2>${selectedText}</h2>`;
                break;
            case 'link':
                const url = prompt('Enter URL:', 'https://');
                if (url) {
                    formattedText = `<a href="${url}" target="_blank">${selectedText}</a>`;
                } else {
                    formattedText = selectedText;
                }
                break;
            case 'image':
                const imgUrl = prompt('Enter image URL:', 'https://');
                if (imgUrl) {
                    formattedText = `<img src="${imgUrl}" alt="${selectedText || 'Image'}" style="max-width: 100%;">`;
                } else {
                    formattedText = selectedText;
                }
                break;
            case 'ul':
                formattedText = `<ul>\n<li>${selectedText}</li>\n</ul>`;
                break;
            case 'ol':
                formattedText = `<ol>\n<li>${selectedText}</li>\n</ol>`;
                break;
            case 'blockquote':
                formattedText = `<blockquote>${selectedText}</blockquote>`;
                break;
            case 'code':
                formattedText = `<code>${selectedText}</code>`;
                break;
        }
        
        textarea.value = textarea.value.substring(0, startPos) + formattedText + textarea.value.substring(endPos);
        textarea.focus();
    }
    
    // Auto-format function
    function autoFormat(textareaId) {
        const textarea = document.getElementById(textareaId);
        let content = textarea.value;
        
        // Convert line breaks to paragraphs
        content = content.replace(/\n\s*\n/g, '</p><p>');
        content = content.replace(/\n/g, '<br>');
        content = '<p>' + content + '</p>';
        
        // Format headings (lines that end with colon)
        content = content.replace(/<p>([^:<]+:)<\/p>/g, '<h2>$1</h2>');
        
        // Format lists (lines that start with - or *)
        content = content.replace(/<p>(\s*[-*]\s+[^<]+)<\/p>/g, '<ul><li>$1</li></ul>');
        content = content.replace(/<\/ul><ul>/g, '');
        
        // Format numbers lists (lines that start with numbers)
        content = content.replace(/<p>(\s*\d+\.\s+[^<]+)<\/p>/g, '<ol><li>$1</li></ol>');
        content = content.replace(/<\/ol><ol>/g, '');
        
        textarea.value = content;
    }
    </script>

    <style>
    .blog-post-preview {
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }
    .blog-title {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #333;
    }
    .blog-meta {
        color: #666;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    .cover-image-preview {
        margin-bottom: 1.5rem;
    }
    .cover-image-preview img {
        max-width: 100%;
        height: auto;
    }
    .excerpt-preview, .content-preview {
        margin-bottom: 1.5rem;
    }
    .excerpt-preview h3, .content-preview h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #444;
    }
    .tags-preview {
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }
    .button-group {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    .btn-preview {
        background-color: #6c757d;
        color: white;
    }
    .btn-preview:hover {
        background-color: #5a6268;
        color: white;
    }
    .image-preview {
        min-height: 50px;
        border: 1px dashed #ccc;
        padding: 10px;
        text-align: center;
        margin-bottom: 10px;
    }
    .additional-images-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .additional-image-preview {
        text-align: center;
    }
    .pdf-files-preview, .file-attachments-preview {
        max-height: 200px;
        overflow-y: auto;
    }
    .pdf-file-preview {
        background-color: #fff5f5;
    }
    .file-attachment-preview {
        background-color: #f8f9fa;
    }
    .editor-toolbar {
        margin-bottom: 10px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f8f9fa;
    }
    .editor-toolbar .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    .nav-tabs .nav-link.disabled {
        color: #6c757d;
        pointer-events: none;
    }
    
    /* Enhanced tab styling for better visibility */
    .nav-tabs {
        border-bottom: 2px solid #343a40;
    }
    .nav-tabs .nav-link {
        background-color: #495057;
        color: #fff;
        border: 1px solid #343a40;
        border-bottom: none;
        margin-right: 5px;
        border-radius: 5px 5px 0 0;
        font-weight: 500;
    }
    .nav-tabs .nav-link:hover {
        background-color: #6c757d;
        color: #fff;
        border-color: #343a40;
    }
    .nav-tabs .nav-link.active {
        background-color: #343a40;
        color: #fff;
        border-color: #343a40;
        border-bottom-color: transparent;
    }
    .nav-tabs .nav-link i {
        margin-right: 5px;
    }
    
    /* Preview modal tabs styling */
    .nav-pills .nav-link {
        background-color: #495057;
        color: #fff;
        margin-right: 5px;
    }
    .nav-pills .nav-link:hover {
        background-color: #6c757d;
        color: #fff;
    }
    .nav-pills .nav-link.active {
        background-color: #343a40;
        color: #fff;
    }
    </style>

<?php 
// Set variable for footer to show social icons
$show_social_icons = true;
include 'footer.php'; 
?>