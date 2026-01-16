<?php
/*
Template Name: Contact Form
*/

get_header();

if ($_POST && isset($_POST['submit_contact'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);
    
    $attachment_path = null;
    $attachment_type = null;
    $attachment_name = null;
    $file_size = 0;
    
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $uploaded_file = $_FILES['attachment'];
        $upload_dir = wp_upload_dir();
        
        // Validate file
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain');
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($uploaded_file['type'], $allowed_types) && $uploaded_file['size'] <= $max_size) {
            $filename = time() . '_' . sanitize_file_name($uploaded_file['name']);
            $filepath = $upload_dir['path'] . '/' . $filename;
            
            if (move_uploaded_file($uploaded_file['tmp_name'], $filepath)) {
                $attachment_path = $upload_dir['url'] . '/' . $filename;
                $attachment_type = $uploaded_file['type'];
                $attachment_name = $uploaded_file['name'];
                $file_size = $uploaded_file['size'];
                
                // Add attachment info to subject
                $subject .= ' [📎 Attachment: ' . $attachment_name . ']';
            }
        }
    }
    
    $wpdb->insert(
        $table_name,
        array(
            'sender_name' => $name,
            'sender_email' => $email,
            'subject' => $subject,
            'message' => $message,
            'attachment_path' => $attachment_path,
            'attachment_type' => $attachment_type,
            'created_at' => current_time('mysql')
        ),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
    );
    
    $attachment_info = $attachment_name ? '<br><small>Attachment: ' . $attachment_name . ' (' . round($file_size / 1024, 2) . ' KB)</small>' : '';
    $success_message = '<div class="success-message" style="background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">✅ Your message has been sent successfully!' . $attachment_info . '</div>';
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Contact Us</h1>
            
            <?php if (isset($success_message)) echo $success_message; ?>
            
            <form method="post" enctype="multipart/form-data" class="contact-form">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Your Email *</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea name="message" id="message" rows="6" class="form-control" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="attachment">📎 Attachment (Optional)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control" accept="image/*,.pdf,.doc,.docx,.txt">
                    <small class="form-text text-muted">📁 Supported files: Images (JPG, PNG, GIF, WebP), PDF, Word documents, Text files<br>📏 Maximum file size: 5MB</small>
                    <div id="file-preview" style="margin-top: 10px; display: none;">
                        <img id="preview-img" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px; display: none;">
                        <div id="file-info" style="margin-top: 5px; font-size: 14px; color: #666;"></div>
                    </div>
                </div>
                
                <button type="submit" name="submit_contact" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</div>

<style>
.contact-form {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-top: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.btn {
    background: #007bff;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background: #0056b3;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.row {
    display: flex;
    flex-wrap: wrap;
}

.col-md-8 {
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
}

.offset-md-2 {
    margin-left: 16.666667%;
}

@media (max-width: 768px) {
    .col-md-8 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .offset-md-2 {
        margin-left: 0;
    }
}

.file-preview-container {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f8f9fa;
}

.file-icon {
    font-size: 24px;
    margin-right: 10px;
}

.upload-progress {
    width: 100%;
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 5px;
}

.upload-progress-bar {
    height: 100%;
    background: #007bff;
    width: 0%;
    transition: width 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('attachment');
    const filePreview = document.getElementById('file-preview');
    const previewImg = document.getElementById('preview-img');
    const fileInfo = document.getElementById('file-info');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Show file info
            const fileSize = (file.size / 1024).toFixed(2);
            const fileType = file.type;
            
            fileInfo.innerHTML = `
                <div class="file-preview-container">
                    <span class="file-icon">📄</span>
                    <strong>${file.name}</strong><br>
                    <small>Size: ${fileSize} KB | Type: ${fileType}</small>
                </div>
            `;
            
            // Show image preview if it's an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewImg.style.display = 'none';
            }
            
            filePreview.style.display = 'block';
        } else {
            filePreview.style.display = 'none';
            previewImg.style.display = 'none';
        }
    });
});
</script>

<?php get_footer(); ?>
