<?php

// Flush rewrite rules on activation
function sagargrup_rewrite_flush() {
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'sagargrup_rewrite_flush' );

// Add theme support
function sagargrup_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'sagargrup_theme_support');

// Register navigation menus
function sagargrup_register_menus() {
    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu', 'sagargrup' ),
            'footer_quick_links' => __( 'Footer Quick Links', 'sagargrup' ),
            'footer_services' => __( 'Footer Services', 'sagargrup' ),
        )
    );
}
add_action( 'init', 'sagargrup_register_menus' );

// Enqueue scripts and styles
function sagargrup_enqueue_scripts() {
    wp_enqueue_style( 'sagargrup-style', get_stylesheet_uri() );
    wp_enqueue_script( 'sagargrup-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'sagargrup_enqueue_scripts' );

// Custom Nav Walker to apply Tailwind classes
class SagarGrup_Nav_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $output .= '<li class="mr-6">';
        $output .= '<a href="' . esc_url( $item->url ) . '" class="text-white hover:text-yellow-300 font-semibold transition duration-300">';
        $output .= $item->title;
        $output .= '</a>';
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}

// Register Custom Post Type for Apps
function sagargrup_custom_post_type() {

    $labels = array(
        'name'                  => _x( 'Apps', 'Post Type General Name', 'sagargrup' ),
        'singular_name'         => _x( 'App', 'Post Type Singular Name', 'sagargrup' ),
        'menu_name'             => __( 'Apps', 'sagargrup' ),
        'name_admin_bar'        => __( 'App', 'sagargrup' ),
        'archives'              => __( 'App Archives', 'sagargrup' ),
        'attributes'            => __( 'App Attributes', 'sagargrup' ),
        'parent_item_colon'     => __( 'Parent App:', 'sagargrup' ),
        'all_items'             => __( 'All Apps', 'sagargrup' ),
        'add_new_item'          => __( 'Add New App', 'sagargrup' ),
        'add_new'               => __( 'Add New', 'sagargrup' ),
        'new_item'              => __( 'New App', 'sagargrup' ),
        'edit_item'             => __( 'Edit App', 'sagargrup' ),
        'update_item'           => __( 'Update App', 'sagargrup' ),
        'view_item'             => __( 'View App', 'sagargrup' ),
        'view_items'            => __( 'View Apps', 'sagargrup' ),
        'search_items'          => __( 'Search App', 'sagargrup' ),
        'not_found'             => __( 'Not found', 'sagargrup' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'sagargrup' ),
        'featured_image'        => __( 'Featured Image', 'sagargrup' ),
        'set_featured_image'    => __( 'Set featured image', 'sagargrup' ),
        'remove_featured_image' => __( 'Remove featured image', 'sagargrup' ),
        'use_featured_image'    => __( 'Use as featured image', 'sagargrup' ),
        'insert_into_item'      => __( 'Insert into app', 'sagargrup' ),
        'uploaded_to_this_item' => __( 'Uploaded to this app', 'sagargrup' ),
        'items_list'            => __( 'Apps list', 'sagargrup' ),
        'items_list_navigation' => __( 'Apps list navigation', 'sagargrup' ),
        'filter_items_list'     => __( 'Filter apps list', 'sagargrup' ),
    );
    $args = array(
        'label'                 => __( 'App', 'sagargrup' ),
        'description'           => __( 'Post Type for Apps', 'sagargrup' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'app-category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-smartphone',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'apps'),
    );
    register_post_type( 'app', $args );

}
add_action( 'init', 'sagargrup_custom_post_type', 0 );

// Create Email Management Admin Panel
function sagargrup_email_admin_menu() {
    add_menu_page(
        'Email Management',
        'Emails',
        'manage_options',
        'sagargrup_emails',
        'sagargrup_email_list_page',
        'dashicons-email',
        6
    );
    
    add_submenu_page(
        'sagargrup_emails',
        'Contact Library',
        'Contact Library',
        'manage_options',
        'sagargrup_contact_library',
        'sagargrup_contact_library_page'
    );
    
    add_submenu_page(
        'sagargrup_emails',
        'Compose Email',
        'Compose',
        'manage_options',
        'sagargrup_compose_email',
        'sagargrup_compose_email_page'
    );
    
    add_submenu_page(
        'sagargrup_emails',
        'Sent Emails',
        'Sent',
        'manage_options',
        'sagargrup_sent_emails',
        'sagargrup_sent_emails_page'
    );
}
add_action('admin_menu', 'sagargrup_email_admin_menu');

// Create database table for emails
function sagargrup_create_email_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        sender_name varchar(100) NOT NULL,
        sender_email varchar(100) NOT NULL,
        recipient_email varchar(100) DEFAULT NULL,
        subject varchar(255) NOT NULL,
        message text NOT NULL,
        attachment_path varchar(255) DEFAULT NULL,
        attachment_type varchar(50) DEFAULT NULL,
        email_type enum('received', 'sent') DEFAULT 'received',
        status enum('unread', 'read', 'sent', 'failed') DEFAULT 'unread',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'sagargrup_create_email_table');

// Add custom CSS for admin panel
function sagargrup_admin_email_styles() {
    echo '<style>
        .email-message {
            border: 1px solid #ddd !important;
            padding: 15px !important;
            margin: 15px 0 !important;
            background: #f9f9f9 !important;
            border-radius: 4px !important;
        }
        
        .email-attachment {
            margin-top: 20px !important;
            padding: 15px !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            background: #fff !important;
        }
        
        .email-attachment img {
            max-width: 100% !important;
            height: auto !important;
            border: 1px solid #ddd !important;
            padding: 5px !important;
            border-radius: 4px !important;
        }
        
        .wp-list-table th {
            background: #f8f9fa !important;
            font-weight: 600 !important;
        }
        
        .wrap .card {
            background: #fff !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            padding: 20px !important;
            margin-top: 20px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        }
        
        .button {
            background: #0073aa !important;
            color: #fff !important;
            border: 1px solid #0073aa !important;
            padding: 6px 12px !important;
            border-radius: 3px !important;
            text-decoration: none !important;
            display: inline-block !important;
            margin-right: 5px !important;
        }
        
        .button:hover {
            background: #005a87 !important;
            border-color: #005a87 !important;
        }
        
        .compose-form {
            background: #fff !important;
            border: 1px solid #ddd !important;
            border-radius: 8px !important;
            padding: 30px !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
            max-width: 800px !important;
        }
        
        .compose-form .form-field {
            margin-bottom: 20px !important;
        }
        
        .compose-form label {
            display: block !important;
            font-weight: 600 !important;
            margin-bottom: 5px !important;
            color: #333 !important;
        }
        
        .compose-form .regular-text {
            width: 100% !important;
            max-width: 100% !important;
            padding: 8px 12px !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            font-size: 14px !important;
        }
        
        .compose-form .regular-text:focus {
            outline: none !important;
            border-color: #0073aa !important;
            box-shadow: 0 0 0 2px rgba(0,115,170,0.25) !important;
        }
        
        .compose-form #message-editor {
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
        }
        
        .compose-form .submit-field {
            margin-top: 30px !important;
            padding-top: 20px !important;
            border-top: 1px solid #eee !important;
        }
        
        .compose-form input[type="file"] {
            padding: 8px 0 !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            width: 100% !important;
        }
        
        .compose-form small {
            color: #666 !important;
            font-size: 12px !important;
            display: block !important;
            margin-top: 5px !important;
        }
        
        .email-config-notice {
            background: #fff3cd !important;
            border: 1px solid #ffeaa7 !important;
            color: #856404 !important;
            padding: 15px !important;
            margin: 20px 0 !important;
            border-radius: 4px !important;
        }
    </style>';
}

// Fix WordPress email settings
function sagargrup_fix_email_settings() {
    // Set correct from email and name
    add_filter('wp_mail_from', function($email) {
        $admin_email = get_option('admin_email');
        return $admin_email;
    });
    
    add_filter('wp_mail_from_name', function($name) {
        $site_name = get_option('blogname');
        return $site_name;
    });
}
add_action('init', 'sagargrup_fix_email_settings');
add_action('admin_head', 'sagargrup_admin_email_styles');

// Create table on theme activation as well
function sagargrup_create_table_on_theme_activation() {
    sagargrup_create_email_table();
}
add_action('after_setup_theme', 'sagargrup_create_table_on_theme_activation');

// Add admin action to manually create table and add sample data
function sagargrup_manual_create_table() {
    if (isset($_GET['create_email_table']) && current_user_can('manage_options')) {
        sagargrup_create_email_table();
        
        // Add sample data for testing
        global $wpdb;
        $table_name = $wpdb->prefix . 'sagargrup_emails';
        
        $wpdb->insert(
            $table_name,
            array(
                'sender_name' => 'John Doe',
                'sender_email' => 'john@example.com',
                'subject' => 'Test Email with Image',
                'message' => 'This is a test email with an image attachment.',
                'attachment_path' => 'https://via.placeholder.com/300x200',
                'attachment_type' => 'image/png',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        $wpdb->insert(
            $table_name,
            array(
                'sender_name' => 'Jane Smith',
                'sender_email' => 'jane@example.com',
                'subject' => 'Another Test Email',
                'message' => 'This is another test email without attachment.',
                'attachment_path' => null,
                'attachment_type' => null,
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        wp_redirect(admin_url('admin.php?page=sagargrup_emails&table_created=1'));
        exit;
    }
}
add_action('admin_init', 'sagargrup_manual_create_table');

// AJAX handler for sent email details
function sagargrup_get_sent_email_details() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    if (isset($_POST['email_id'])) {
        $email_id = intval($_POST['email_id']);
        $email = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $email_id));
        
        if ($email && $email->email_type == 'sent') {
            echo '<div class="wrap">';
            echo '<h2>📤 Sent Email Details</h2>';
            echo '<div class="card">';
            echo '<h3>' . esc_html($email->subject) . '</h3>';
            echo '<p><strong>From:</strong> ' . esc_html($email->sender_name) . ' &lt;' . esc_html($email->sender_email) . '&gt;</p>';
            echo '<p><strong>To:</strong> ' . esc_html($email->recipient_email) . '</p>';
            echo '<p><strong>Date:</strong> ' . esc_html($email->created_at) . '</p>';
            echo '<p><strong>Status:</strong> ' . ($email->status == 'sent' ? '<span style="color: #28a745;">✅ Sent</span>' : '<span style="color: #dc3545;">❌ Failed</span>') . '</p>';
            
            echo '<div class="email-message" style="border: 1px solid #ddd; padding: 15px; margin: 15px 0; background: #f9f9f9; border-radius: 4px;">';
            echo '<p><strong>Message:</strong></p>';
            echo '<div>' . wpautop(esc_html($email->message)) . '</div>';
            echo '</div>';
            
            if ($email->attachment_path) {
                echo '<div class="email-attachment" style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">';
                echo '<h4>📎 Attachment</h4>';
                
                $file_url = $email->attachment_path;
                $file_type = $email->attachment_type;
                
                if ($file_type && strpos($file_type, 'image') !== false) {
                    echo '<div style="margin-bottom: 10px;">';
                    echo '<img src="' . esc_url($file_url) . '" alt="Attachment Image" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">';
                    echo '</div>';
                    echo '<p><a href="' . esc_url($file_url) . '" target="_blank" class="button">🔍 View Full Size</a> | <a href="' . esc_url($file_url) . '" download class="button">💾 Download</a></p>';
                } else {
                    $icon = '📄';
                    if (strpos($file_type, 'pdf') !== false) $icon = '📕';
                    elseif (strpos($file_type, 'word') !== false) $icon = '📘';
                    elseif (strpos($file_type, 'text') !== false) $icon = '📝';
                    
                    echo '<p style="font-size: 16px; margin-bottom: 10px;">' . $icon . ' <strong>File Type:</strong> ' . esc_html($file_type) . '</p>';
                    echo '<p><a href="' . esc_url($file_url) . '" target="_blank" class="button">📂 Open File</a> | <a href="' . esc_url($file_url) . '" download class="button">💾 Download</a></p>';
                }
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }
    }
    
    wp_die();
}
add_action('wp_ajax_get_sent_email_details', 'sagargrup_get_sent_email_details');

// AJAX handler for contact details
function sagargrup_get_contact_details() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    if (isset($_POST['contact_id'])) {
        $contact_id = intval($_POST['contact_id']);
        $contact = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $contact_id));
        
        if ($contact && $contact->email_type == 'received') {
            echo '<div class="wrap">';
            echo '<h2>📧 Contact Message Details</h2>';
            echo '<div class="card">';
            echo '<h3>' . esc_html($contact->subject) . '</h3>';
            echo '<p><strong>👤 Name:</strong> ' . esc_html($contact->sender_name) . '</p>';
            echo '<p><strong>📧 Email:</strong> ' . esc_html($contact->sender_email) . '</p>';
            echo '<p><strong>📅 Date:</strong> ' . esc_html($contact->created_at) . '</p>';
            echo '<p><strong>📊 Status:</strong> ' . ($contact->status == 'read' ? '<span style="color: #28a745;">✅ Read</span>' : '<span style="color: #ffc107;">📖 Unread</span>') . '</p>';
            
            echo '<div class="email-message" style="border: 1px solid #ddd; padding: 15px; margin: 15px 0; background: #f9f9f9; border-radius: 4px;">';
            echo '<p><strong>📝 Message:</strong></p>';
            echo '<div>' . wpautop(esc_html($contact->message)) . '</div>';
            echo '</div>';
            
            if ($contact->attachment_path) {
                echo '<div class="email-attachment" style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">';
                echo '<h4>📎 Attachment</h4>';
                
                $file_url = $contact->attachment_path;
                $file_type = $contact->attachment_type;
                
                if ($file_type && strpos($file_type, 'image') !== false) {
                    echo '<div style="margin-bottom: 10px;">';
                    echo '<img src="' . esc_url($file_url) . '" alt="Attachment Image" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">';
                    echo '</div>';
                    echo '<p><a href="' . esc_url($file_url) . '" target="_blank" class="button">🔍 View Full Size</a> | <a href="' . esc_url($file_url) . '" download class="button">💾 Download</a></p>';
                } else {
                    $icon = '📄';
                    if (strpos($file_type, 'pdf') !== false) $icon = '📕';
                    elseif (strpos($file_type, 'word') !== false) $icon = '📘';
                    elseif (strpos($file_type, 'text') !== false) $icon = '📝';
                    
                    echo '<p style="font-size: 16px; margin-bottom: 10px;">' . $icon . ' <strong>File Type:</strong> ' . esc_html($file_type) . '</p>';
                    echo '<p><a href="' . esc_url($file_url) . '" target="_blank" class="button">📂 Open File</a> | <a href="' . esc_url($file_url) . '" download class="button">💾 Download</a></p>';
                }
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }
    }
    
    wp_die();
}
add_action('wp_ajax_get_contact_details', 'sagargrup_get_contact_details');

// AJAX handler to mark contact as read
function sagargrup_mark_contact_read() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    if (isset($_POST['contact_id'])) {
        $contact_id = intval($_POST['contact_id']);
        $wpdb->update(
            $table_name,
            array('status' => 'read'),
            array('id' => $contact_id),
            array('%s'),
            array('%d')
        );
    }
    
    wp_die();
}
add_action('wp_ajax_mark_contact_read', 'sagargrup_mark_contact_read');

// Contact Library Page
function sagargrup_contact_library_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    // Handle bulk actions
    if (isset($_POST['bulk_action']) && isset($_POST['contact_ids'])) {
        $contact_ids = array_map('intval', $_POST['contact_ids']);
        
        if ($_POST['bulk_action'] == 'mark_read') {
            foreach ($contact_ids as $id) {
                $wpdb->update(
                    $table_name,
                    array('status' => 'read'),
                    array('id' => $id),
                    array('%s'),
                    array('%d')
                );
            }
            echo '<div class="notice notice-success is-dismissible"><p>✅ Contacts marked as read!</p></div>';
        } elseif ($_POST['bulk_action'] == 'delete') {
            foreach ($contact_ids as $id) {
                $wpdb->delete($table_name, array('id' => $id), array('%d'));
            }
            echo '<div class="notice notice-success is-dismissible"><p>🗑️ Contacts deleted!</p></div>';
        }
    }
    
    $contacts = $wpdb->get_results("SELECT * FROM $table_name WHERE email_type = 'received' ORDER BY created_at DESC");
    
    echo '<div class="wrap">';
    echo '<h1>📚 Contact Library</h1>';
    
    // Action buttons
    echo '<div style="margin-bottom: 20px;">';
    echo '<a href="' . admin_url('admin.php?page=sagargrup_compose_email') . '" class="button button-primary">✉️ Compose</a>';
    echo '<a href="' . admin_url('admin.php?page=sagargrup_emails') . '" class="button">📥 Inbox</a>';
    echo '<a href="' . admin_url('admin.php?page=sagargrup_sent_emails') . '" class="button">📤 Sent</a>';
    echo '</div>';
    
    if (empty($contacts)) {
        echo '<p>No contacts found.</p>';
        echo '<p><a href="' . admin_url('post-new.php?post_type=page') . '" class="button button-primary">Create Contact Page</a> (Use "Contact Form" template)</p>';
    } else {
        echo '<form method="post">';
        echo '<div class="tablenav top">';
        echo '<div class="alignleft actions bulkactions">';
        echo '<select name="bulk_action" id="bulk-action-selector-top">';
        echo '<option value="-1">Bulk Actions</option>';
        echo '<option value="mark_read">Mark as Read</option>';
        echo '<option value="delete">Delete</option>';
        echo '</select>';
        echo '<input type="submit" name="doaction" id="doaction" class="button action" value="Apply">';
        echo '</div>';
        echo '</div>';
        
        echo '<table class="wp-list-table widefat fixed striped contacts-table">';
        echo '<thead><tr>';
        echo '<th class="check-column"><input type="checkbox" id="select-all-contacts"></th>';
        echo '<th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Attachment</th><th>Actions</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        
        foreach ($contacts as $contact) {
            $row_class = $contact->status == 'unread' ? 'unread-contact' : 'read-contact';
            echo '<tr class="' . $row_class . '">';
            echo '<th class="check-column"><input type="checkbox" name="contact_ids[]" value="' . $contact->id . '"></th>';
            echo '<td>' . esc_html($contact->sender_name) . '</td>';
            echo '<td>' . esc_html($contact->sender_email) . '</td>';
            echo '<td>' . esc_html($contact->subject) . '</td>';
            echo '<td>' . esc_html($contact->created_at) . '</td>';
            echo '<td>' . ($contact->status == 'read' ? '<span style="color: #28a745;">✅ Read</span>' : '<span style="color: #ffc107;">📖 Unread</span>') . '</td>';
            echo '<td>' . ($contact->attachment_path ? '<span style="color: #28a745;">📎 Yes</span>' : '<span style="color: #6c757d;">No</span>') . '</td>';
            echo '<td><a href="#" onclick="showContactDetails(' . $contact->id . '); return false;" class="button">View</a></td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        echo '</form>';
    }
    
    // Add modal (reuse from inbox)
    echo '<div id="contact-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">';
    echo '<div style="position: relative; background: white; margin: 50px auto; max-width: 800px; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-height: 80vh; overflow-y: auto;">';
    echo '<div id="contact-content"></div>';
    echo '<button onclick="closeContactModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer;">×</button>';
    echo '</div>';
    echo '</div>';
    
    echo '<script>
    function showContactDetails(contactId) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "get_contact_details",
                contact_id: contactId
            },
            success: function(response) {
                document.getElementById("contact-content").innerHTML = response;
                document.getElementById("contact-modal").style.display = "block";
                markAsRead(contactId);
            }
        });
    }
    
    function closeContactModal() {
        document.getElementById("contact-modal").style.display = "none";
    }
    
    function markAsRead(contactId) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "mark_contact_read",
                contact_id: contactId
            }
        });
    }
    
    // Select all functionality
    document.addEventListener("DOMContentLoaded", function() {
        var selectAll = document.getElementById("select-all-contacts");
        if (selectAll) {
            selectAll.addEventListener("change", function() {
                var checkboxes = document.querySelectorAll("input[name=\'contact_ids[]\']");
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAll.checked;
                });
            });
        }
    });
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById("contact-modal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>';
    
    // Add CSS for unread styling
    echo '<style>
    .unread-contact {
        background-color: #fff3cd !important;
        font-weight: 600;
    }
    .read-contact {
        background-color: #ffffff;
    }
    .contacts-table th {
        background: #f8f9fa !important;
        font-weight: 600 !important;
    }
    </style>';
    
    echo '</div>';
}

// Email list page
function sagargrup_email_list_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    // Check if table exists, create if not
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        sagargrup_create_email_table();
    }
    
    $emails = $wpdb->get_results("SELECT * FROM $table_name WHERE email_type = 'received' ORDER BY created_at DESC");
    
    echo '<div class="wrap">';
    echo '<h1>📧 Email Management</h1>';
    
    // Action buttons
    echo '<div style="margin-bottom: 20px;">';
    echo '<a href="' . admin_url('admin.php?page=sagargrup_compose_email') . '" class="button button-primary">✉️ Compose</a>';
    echo '<a href="' . admin_url('admin.php?page=sagargrup_sent_emails') . '" class="button">📤 Sent</a>';
    echo '<a href="' . admin_url('admin.php?page=sagargrup_emails') . '" class="button">📥 Inbox</a>';
    echo '</div>';
    
    // Success message
    if (isset($_GET['table_created'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Email table created successfully with sample data!</p></div>';
    }
    
    // Debug info
    echo '<div style="background: #f9f9f9; padding: 10px; margin-bottom: 20px; border-left: 4px solid #0073aa;">';
    echo '<p><strong>Debug Info:</strong></p>';
    echo '<p>Table: ' . $table_name . '</p>';
    echo '<p>Email count: ' . count($emails) . '</p>';
    echo '</div>';
    
    if (empty($emails)) {
        echo '<p>No emails found. Users can submit emails through the contact form.</p>';
        echo '<p><a href="' . admin_url('admin.php?page=sagargrup_emails&create_email_table=1') . '" class="button">Create Table & Add Sample Data</a></p>';
        echo '<p><a href="' . admin_url('post-new.php?post_type=page') . '" class="button">Create Contact Page</a> (Use "Contact Form" template)</p>';
    } else {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>ID</th><th>Sender Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Attachment</th><th>Actions</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        
        foreach ($emails as $email) {
            echo '<tr>';
            echo '<td>' . esc_html($email->id) . '</td>';
            echo '<td>' . esc_html($email->sender_name) . '</td>';
            echo '<td>' . esc_html($email->sender_email) . '</td>';
            echo '<td>' . esc_html($email->subject) . '</td>';
            echo '<td>' . esc_html($email->created_at) . '</td>';
            echo '<td>' . ($email->attachment_path ? '<span style="color: #28a745;">📎 Yes</span>' : '<span style="color: #6c757d;">No</span>') . '</td>';
            echo '<td><a href="#" onclick="showContactDetails(' . $email->id . '); return false;" class="button">View</a></td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
    
    echo '</div>';
    
    // Contact details modal
    echo '<div id="contact-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">';
    echo '<div style="position: relative; background: white; margin: 50px auto; max-width: 800px; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-height: 80vh; overflow-y: auto;">';
    echo '<div id="contact-content"></div>';
    echo '<button onclick="closeContactModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer;">×</button>';
    echo '</div>';
    echo '</div>';
    
    echo '<script>
    function showContactDetails(contactId) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "get_contact_details",
                contact_id: contactId
            },
            success: function(response) {
                document.getElementById("contact-content").innerHTML = response;
                document.getElementById("contact-modal").style.display = "block";
                // Mark as read
                markAsRead(contactId);
            }
        });
    }
    
    function closeContactModal() {
        document.getElementById("contact-modal").style.display = "none";
    }
    
    function markAsRead(contactId) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "mark_contact_read",
                contact_id: contactId
            }
        });
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById("contact-modal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>';
    
    echo '</div>';
}

// Compose Email Page
function sagargrup_compose_email_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    // Handle email sending
    if ($_POST && isset($_POST['send_email'])) {
        $to = sanitize_email($_POST['recipient_email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = wpautop(sanitize_textarea_field($_POST['message']));
        
        // Get correct admin email from WordPress settings
        $admin_email = get_option('admin_email');
        $site_name = get_option('blogname');
        
        // Set proper headers with correct sender information
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>',
            'Reply-To: ' . $admin_email
        );
        
        // Handle attachment
        $attachment_path = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $uploaded_file = $_FILES['attachment'];
            $upload_dir = wp_upload_dir();
            $filename = time() . '_' . sanitize_file_name($uploaded_file['name']);
            $filepath = $upload_dir['path'] . '/' . $filename;
            
            if (move_uploaded_file($uploaded_file['tmp_name'], $filepath)) {
                $attachment_path = $filepath;
            }
        }
        
        // Send email with correct sender
        $sent = wp_mail($to, $subject, $message, $headers, $attachment_path);
        
        // Save to sent items
        $wpdb->insert(
            $table_name,
            array(
                'sender_name' => wp_get_current_user()->display_name,
                'sender_email' => wp_get_current_user()->user_email,
                'recipient_email' => $to,
                'subject' => $subject,
                'message' => sanitize_textarea_field($_POST['message']),
                'attachment_path' => $attachment_path ? str_replace(ABSPATH, site_url('/'), $attachment_path) : null,
                'attachment_type' => isset($uploaded_file) ? $uploaded_file['type'] : null,
                'email_type' => 'sent',
                'status' => $sent ? 'sent' : 'failed',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        if ($sent) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ Email sent successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>❌ Failed to send email. Please check your settings.</p></div>';
        }
    }
    
    echo '<div class="wrap">';
    echo '<h1>✉️ Compose Email</h1>';
    
    // Show current email configuration
    $admin_email = get_option('admin_email');
    $site_name = get_option('blogname');
    echo '<div class="email-config-notice">';
    echo '<strong>📧 Email Configuration:</strong><br>';
    echo 'From: ' . $site_name . ' &lt;' . $admin_email . '&gt;<br>';
    echo '<small>Using WordPress Administration Email settings. <a href="' . admin_url('options-general.php') . '">Configure Settings</a></small>';
    echo '</div>';
    
    echo '<form method="post" enctype="multipart/form-data" class="compose-form">';
    echo '<div class="form-field">';
    echo '<label for="recipient_email">To:</label>';
    echo '<input type="email" name="recipient_email" id="recipient_email" class="regular-text" required placeholder="recipient@example.com">';
    echo '</div>';
    
    echo '<div class="form-field">';
    echo '<label for="subject">Subject:</label>';
    echo '<input type="text" name="subject" id="subject" class="regular-text" required placeholder="Enter subject">';
    echo '</div>';
    
    echo '<div class="form-field">';
    echo '<label for="message">Message:</label>';
    echo '<div id="message-editor">';
    wp_editor('', 'message', array(
        'textarea_name' => 'message',
        'textarea_rows' => 15,
        'media_buttons' => true,
        'teeny' => false,
        'quicktags' => true
    ));
    echo '</div>';
    echo '</div>';
    
    echo '<div class="form-field">';
    echo '<label for="attachment">Attachment (Optional):</label>';
    echo '<input type="file" name="attachment" id="attachment" accept="image/*,.pdf,.doc,.docx,.txt">';
    echo '<small>Maximum file size: 10MB</small>';
    echo '</div>';
    
    echo '<div class="submit-field">';
    echo '<input type="submit" name="send_email" class="button button-primary" value="📤 Send Email">';
    echo '<a href="' . admin_url('admin.php?page=sagargrup_emails') . '" class="button">Cancel</a>';
    echo '</div>';
    
    echo '</form>';
    echo '</div>';
}

// Sent Emails Page
function sagargrup_sent_emails_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    $sent_emails = $wpdb->get_results("SELECT * FROM $table_name WHERE email_type = 'sent' ORDER BY created_at DESC");
    
    echo '<div class="wrap">';
    echo '<h1>📤 Sent Emails</h1>';
    
    if (empty($sent_emails)) {
        echo '<p>No sent emails found.</p>';
        echo '<p><a href="' . admin_url('admin.php?page=sagargrup_compose_email') . '" class="button button-primary">✉️ Compose Email</a></p>';
    } else {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>To</th><th>Subject</th><th>Date</th><th>Status</th><th>Attachment</th><th>Actions</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        
        foreach ($sent_emails as $email) {
            echo '<tr>';
            echo '<td>' . esc_html($email->recipient_email) . '</td>';
            echo '<td>' . esc_html($email->subject) . '</td>';
            echo '<td>' . esc_html($email->created_at) . '</td>';
            echo '<td>' . ($email->status == 'sent' ? '<span style="color: #28a745;">✅ Sent</span>' : '<span style="color: #dc3545;">❌ Failed</span>') . '</td>';
            echo '<td>' . ($email->attachment_path ? '<span style="color: #28a745;">📎 Yes</span>' : '<span style="color: #6c757d;">No</span>') . '</td>';
            echo '<td><a href="#" onclick="showSentEmailDetails(' . $email->id . '); return false;" class="button">View</a></td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
    
    // Email details modal
    echo '<div id="sent-email-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">';
    echo '<div style="position: relative; background: white; margin: 50px auto; max-width: 800px; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">';
    echo '<div id="sent-email-content"></div>';
    echo '<button onclick="closeSentEmailModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer;">×</button>';
    echo '</div>';
    echo '</div>';
    
    echo '<script>
    function showSentEmailDetails(emailId) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "get_sent_email_details",
                email_id: emailId
            },
            success: function(response) {
                document.getElementById("sent-email-content").innerHTML = response;
                document.getElementById("sent-email-modal").style.display = "block";
            }
        });
    }
    
    function closeSentEmailModal() {
        document.getElementById("sent-email-modal").style.display = "none";
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById("sent-email-modal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>';
    
    echo '</div>';
}

// View email page
function sagargrup_view_email_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_emails';
    
    if (isset($_GET['id'])) {
        $email_id = intval($_GET['id']);
        $email = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $email_id));
        
        if ($email) {
            echo '<div class="wrap">';
            echo '<h1>View Email</h1>';
            echo '<div class="card">';
            echo '<h2>' . esc_html($email->subject) . '</h2>';
            echo '<p><strong>From:</strong> ' . esc_html($email->sender_name) . ' &lt;' . esc_html($email->sender_email) . '&gt;</p>';
            echo '<p><strong>Date:</strong> ' . esc_html($email->created_at) . '</p>';
            echo '<div class="email-message" style="border: 1px solid #ddd; padding: 15px; margin: 15px 0; background: #f9f9f9;">';
            echo '<p><strong>Message:</strong></p>';
            echo '<div>' . wpautop(esc_html($email->message)) . '</div>';
            echo '</div>';
            
            if ($email->attachment_path) {
                echo '<div class="email-attachment">';
                echo '<h3>📎 Attachment</h3>';
                
                // Get file info
                $file_url = $email->attachment_path;
                $file_type = $email->attachment_type;
                $file_name = 'attachment'; // You could store this in DB too
                
                if ($file_type && strpos($file_type, 'image') !== false) {
                    echo '<div style="margin-bottom: 10px;">';
                    echo '<img src="' . esc_url($file_url) . '" alt="Attachment Image" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">';
                    echo '</div>';
                    echo '<p><a href="' . esc_url($file_url) . '" target="_blank" class="button">🔍 View Full Size</a> | <a href="' . esc_url($file_url) . '" download class="button">💾 Download</a></p>';
                } else {
                    // File icon based on type
                    $icon = '📄';
                    if (strpos($file_type, 'pdf') !== false) $icon = '📕';
                    elseif (strpos($file_type, 'word') !== false) $icon = '📘';
                    elseif (strpos($file_type, 'text') !== false) $icon = '📝';
                    
                    echo '<p style="font-size: 16px; margin-bottom: 10px;">' . $icon . ' <strong>File Type:</strong> ' . esc_html($file_type) . '</p>';
                    echo '<p><a href="' . esc_url($file_url) . '" target="_blank" class="button">📂 Open File</a> | <a href="' . esc_url($file_url) . '" download class="button">💾 Download</a></p>';
                }
                echo '</div>';
            }
            
            echo '<p><a href="' . admin_url('admin.php?page=sagargrup_emails') . '" class="button">← Back to Email List</a></p>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="wrap"><h1>Email not found</h1></div>';
        }
    }
}

// Register Custom Taxonomy for Apps
function sagargrup_register_taxonomies() {
    $labels = array(
        'name'              => _x( 'App Categories', 'taxonomy general name', 'sagargrup' ),
        'singular_name'     => _x( 'App Category', 'taxonomy singular name', 'sagargrup' ),
        'search_items'      => __( 'Search App Categories', 'sagargrup' ),
        'all_items'         => __( 'All App Categories', 'sagargrup' ),
        'parent_item'       => __( 'Parent App Category', 'sagargrup' ),
        'parent_item_colon' => __( 'Parent App Category:', 'sagargrup' ),
        'edit_item'         => __( 'Edit App Category', 'sagargrup' ),
        'update_item'       => __( 'Update App Category', 'sagargrup' ),
        'add_new_item'      => __( 'Add New App Category', 'sagargrup' ),
        'new_item_name'     => __( 'New App Category Name', 'sagargrup' ),
        'menu_name'         => __( 'App Category', 'sagargrup' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'app-category' ),
    );

    register_taxonomy( 'app-category', array( 'app' ), $args );
}
add_action( 'init', 'sagargrup_register_taxonomies', 0 );

// Add meta box for App Details
function sagargrup_add_app_details_meta_box() {
    add_meta_box(
        'sagargrup_app_details',
        'App Details',
        'sagargrup_app_details_meta_box_html',
        'app',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sagargrup_add_app_details_meta_box');

// Meta box HTML
function sagargrup_app_details_meta_box_html($post) {
    wp_nonce_field('sagargrup_update_app_details', 'sagargrup_app_details_nonce');
    $playstore_link = get_post_meta($post->ID, '_app_playstore_link', true);
    $app_logo = get_post_meta($post->ID, '_app_logo', true);
    $app_thumbnail = get_post_meta($post->ID, '_app_thumbnail', true);
    $screenshots = get_post_meta($post->ID, '_app_screenshots', true);
    ?>
    <p>
        <label for="app_playstore_link">Google Play Store Link</label><br>
        <input type="url" name="app_playstore_link" id="app_playstore_link" class="widefat" value="<?php echo esc_url($playstore_link); ?>">
    </p>
    <p>
        <label for="app_logo">App Logo URL</label><br>
        <input type="url" name="app_logo" id="app_logo" class="widefat" value="<?php echo esc_url($app_logo); ?>"><button type="button" class="button sagargrup-upload-button">Upload Image</button>
    </p>
    <p>
        <label for="app_thumbnail">App Thumbnail URL</label><br>
        <input type="url" name="app_thumbnail" id="app_thumbnail" class="widefat" value="<?php echo esc_url($app_thumbnail); ?>"><button type="button" class="button sagargrup-upload-button">Upload Image</button>
    </p>
    <p>
        <label for="app_screenshots">Screenshots (minimum 3, maximum 10)</label><br>
        <div id="screenshots_wrapper">
            <?php
            if ($screenshots) {
                foreach ($screenshots as $screenshot) {
                    echo '<div><input type="text" name="app_screenshots[]" class="widefat" value="' . esc_url($screenshot) . '"><button type="button" class="button sagargrup-upload-button">Upload Image</button> <a href="#" class="remove_screenshot">Remove</a></div>';
                }
            }
            ?>
        </div>
        <a href="#" id="add_screenshot">Add Screenshot</a>
    </p>
    <?php
}

// Save meta box data
function sagargrup_save_app_details_meta($post_id) {
    if (!isset($_POST['sagargrup_app_details_nonce']) || !wp_verify_nonce($_POST['sagargrup_app_details_nonce'], 'sagargrup_update_app_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['app_playstore_link'])) {
        update_post_meta($post_id, '_app_playstore_link', sanitize_text_field($_POST['app_playstore_link']));
    }

    if (isset($_POST['app_logo'])) {
        update_post_meta($post_id, '_app_logo', sanitize_text_field($_POST['app_logo']));
    }

    if (isset($_POST['app_thumbnail'])) {
        update_post_meta($post_id, '_app_thumbnail', sanitize_text_field($_POST['app_thumbnail']));
    }

    if (isset($_POST['app_screenshots'])) {
        $screenshots = array_map('sanitize_text_field', $_POST['app_screenshots']);
        update_post_meta($post_id, '_app_screenshots', $screenshots);
    }
}
add_action('save_post_app', 'sagargrup_save_app_details_meta');

// Enqueue admin scripts for media uploader
function sagargrup_enqueue_admin_scripts($hook) {
    if ('post.php' != $hook && 'post-new.php' != $hook) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('sagargrup-admin-script', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'sagargrup_enqueue_admin_scripts');

// Add a menu page for "Get In Touch" settings
function sagargrup_add_get_in_touch_menu() {
    add_menu_page(
        'Home Page Settings',
        'Home Page Settings',
        'manage_options',
        'sagargrup-get-in-touch-settings',
        'sagargrup_get_in_touch_page_html',
        'dashicons-admin-home',
        20
    );
}
add_action('admin_menu', 'sagargrup_add_get_in_touch_menu');

// HTML for the "Get In Touch" settings page
function sagargrup_get_in_touch_page_html() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('sagargrup_get_in_touch_options');
            do_settings_sections('sagargrup-get-in-touch-settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Register settings for the "Get In Touch" section
function sagargrup_get_in_touch_settings_init() {
    register_setting('sagargrup_get_in_touch_options', 'sagargrup_get_in_touch_title');
    register_setting('sagargrup_get_in_touch_options', 'sagargrup_get_in_touch_description');

    add_settings_section(
        'sagargrup_get_in_touch_section',
        'Get In Touch Section',
        'sagargrup_get_in_touch_section_callback',
        'sagargrup-get-in-touch-settings'
    );

    add_settings_field(
        'sagargrup_get_in_touch_title_field',
        'Title',
        'sagargrup_get_in_touch_title_field_html',
        'sagargrup-get-in-touch-settings',
        'sagargrup_get_in_touch_section'
    );

    add_settings_field(
        'sagargrup_get_in_touch_description_field',
        'Description',
        'sagargrup_get_in_touch_description_field_html',
        'sagargrup-get-in-touch-settings',
        'sagargrup_get_in_touch_section'
    );
}
add_action('admin_init', 'sagargrup_get_in_touch_settings_init');

// Section callback
function sagargrup_get_in_touch_section_callback() {
    echo '<p>Manage the content of the "Get In Touch" section on the home page.</p>';
}

// Title field HTML
function sagargrup_get_in_touch_title_field_html() {
    $title = get_option('sagargrup_get_in_touch_title');
    ?>
    <input type="text" name="sagargrup_get_in_touch_title" value="<?php echo esc_attr($title); ?>" class="regular-text">
    <?php
}

// Description field HTML
function sagargrup_get_in_touch_description_field_html() {
    $description = get_option('sagargrup_get_in_touch_description');
    ?>
    <textarea name="sagargrup_get_in_touch_description" rows="5" class="regular-text"><?php echo esc_textarea($description); ?></textarea>
    <?php
}

// Create database table for contact messages
function sagargrup_create_contact_messages_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_contact_messages';
    
    // Check if table exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    if (!$table_exists) {
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            subject varchar(200) NOT NULL,
            message text NOT NULL,
            status varchar(20) DEFAULT 'unread',
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Create table on admin init
function sagargrup_ensure_table_exists() {
    sagargrup_create_contact_messages_table();
}
add_action('admin_init', 'sagargrup_ensure_table_exists');

register_activation_hook(__FILE__, 'sagargrup_create_contact_messages_table');

// Process contact form submission
function sagargrup_process_contact_form() {
    if (isset($_POST['sagargrup_contact_form']) && wp_verify_nonce($_POST['sagargrup_contact_nonce'], 'sagargrup_contact_form_action')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sagargrup_contact_messages';
        
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_textarea_field($_POST['message']);
        
        // Handle file attachment
        $attachment_info = '';
        $attachment_path = '';
        
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $enable_attachments = get_option('sagargrup_enable_email_attachments', 0);
            
            if ($enable_attachments) {
                $allowed_types = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt');
                $max_size = 5 * 1024 * 1024; // 5MB
                
                $file_info = wp_check_filetype_and_ext($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
                $file_ext = strtolower($file_info['ext']);
                
                if (in_array($file_ext, $allowed_types) && $_FILES['attachment']['size'] <= $max_size) {
                    // Use WordPress upload functions
                    $upload_overrides = array(
                        'test_form' => false,
                        'mimes' => array(
                            'pdf' => 'application/pdf',
                            'doc' => 'application/msword',
                            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'jpg' => 'image/jpeg',
                            'jpeg' => 'image/jpeg',
                            'png' => 'image/png',
                            'txt' => 'text/plain'
                        )
                    );
                    
                    $uploaded_file = wp_handle_upload($_FILES['attachment'], $upload_overrides);
                    
                    if (isset($uploaded_file['file']) && !isset($uploaded_file['error'])) {
                        $attachment_path = $uploaded_file['file'];
                        $attachment_info = basename($attachment_path) . ' (' . size_format($_FILES['attachment']['size']) . ')';
                    }
                }
            }
        }
        
        // Validate required fields
        if (empty($first_name) || empty($last_name) || empty($email) || empty($subject) || empty($message)) {
            wp_redirect(add_query_arg('contact_status', 'error', wp_get_referer()));
            exit;
        }
        
        // Validate email
        if (!is_email($email)) {
            wp_redirect(add_query_arg('contact_status', 'invalid_email', wp_get_referer()));
            exit;
        }
        
        // Insert message into database
        $result = $wpdb->insert(
            $table_name,
            array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'status' => 'unread',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        if ($result) {
            // Send auto email to user and admin notification
            $email_sent = sagargrup_send_auto_email($first_name, $last_name, $email, $subject, $message, $attachment_path);
            
            if ($email_sent) {
                wp_redirect(add_query_arg('contact_status', 'success', wp_get_referer()));
            } else {
                wp_redirect(add_query_arg('contact_status', 'success_no_email', wp_get_referer()));
            }
            exit;
        } else {
            wp_redirect(add_query_arg('contact_status', 'error', wp_get_referer()));
            exit;
        }
    }
}
add_action('template_redirect', 'sagargrup_process_contact_form');

// Display contact form status messages
function sagargrup_display_contact_status() {
    if (isset($_GET['contact_status'])) {
        $status = sanitize_text_field($_GET['contact_status']);
        $message = '';
        $class = '';
        
        switch ($status) {
            case 'success':
                $message = 'Your message has been sent successfully! We will get back to you soon.';
                $class = 'bg-green-100 border border-green-400 text-green-700';
                break;
            case 'success_no_email':
                $message = 'Your message has been saved! However, there was an issue sending the confirmation email. We will still respond to your inquiry.';
                $class = 'bg-yellow-100 border border-yellow-400 text-yellow-700';
                break;
            case 'error':
                $message = 'There was an error sending your message. Please try again.';
                $class = 'bg-red-100 border border-red-400 text-red-700';
                break;
            case 'invalid_email':
                $message = 'Please enter a valid email address.';
                $class = 'bg-red-100 border border-red-400 text-red-700';
                break;
        }
        
        if ($message) {
            echo '<div class="' . esc_attr($class) . ' px-4 py-3 rounded mb-4" role="alert">' . esc_html($message) . '</div>';
        }
    }
}

// Add admin menu page for contact messages
function sagargrup_add_contact_messages_menu() {
    add_submenu_page(
        'sagargrup-get-in-touch-settings',
        'Contact Messages',
        'Contact Messages',
        'manage_options',
        'sagargrup-contact-messages',
        'sagargrup_contact_messages_page_html'
    );
    
    add_submenu_page(
        'sagargrup-get-in-touch-settings',
        'Email Settings',
        'Email Settings',
        'manage_options',
        'sagargrup-email-settings',
        'sagargrup_email_settings_page_html'
    );
}
add_action('admin_menu', 'sagargrup_add_contact_messages_menu');

// Register email settings
function sagargrup_email_settings_init() {
    register_setting('sagargrup_email_options', 'sagargrup_enable_auto_email');
    register_setting('sagargrup_email_options', 'sagargrup_admin_email');
    register_setting('sagargrup_email_options', 'sagargrup_email_subject');
    register_setting('sagargrup_email_options', 'sagargrup_email_message');
    register_setting('sagargrup_email_options', 'sagargrup_enable_email_copy');
    register_setting('sagargrup_email_options', 'sagargrup_email_copy_addresses');
    register_setting('sagargrup_email_options', 'sagargrup_enable_email_attachments');
    register_setting('sagargrup_email_options', 'sagargrup_email_template');
    register_setting('sagargrup_email_options', 'sagargrup_company_signature');
    register_setting('sagargrup_email_options', 'sagargrup_email_reply_to');

    add_settings_section(
        'sagargrup_email_section',
        'Auto Email Settings',
        'sagargrup_email_section_callback',
        'sagargrup-email-settings'
    );

    add_settings_section(
        'sagargrup_advanced_email_section',
        'Advanced Email Features',
        'sagargrup_advanced_email_section_callback',
        'sagargrup-email-settings'
    );

    add_settings_field(
        'sagargrup_enable_auto_email_field',
        'Enable Auto Email',
        'sagargrup_enable_auto_email_field_html',
        'sagargrup-email-settings',
        'sagargrup_email_section'
    );

    add_settings_field(
        'sagargrup_admin_email_field',
        'Admin Email (for notifications)',
        'sagargrup_admin_email_field_html',
        'sagargrup-email-settings',
        'sagargrup_email_section'
    );

    add_settings_field(
        'sagargrup_email_subject_field',
        'Email Subject',
        'sagargrup_email_subject_field_html',
        'sagargrup-email-settings',
        'sagargrup_email_section'
    );

    add_settings_field(
        'sagargrup_email_message_field',
        'Email Message',
        'sagargrup_email_message_field_html',
        'sagargrup-email-settings',
        'sagargrup_email_section'
    );

    add_settings_field(
        'sagargrup_email_template_field',
        'Email Template',
        'sagargrup_email_template_field_html',
        'sagargrup-email-settings',
        'sagargrup_email_section'
    );

    add_settings_field(
        'sagargrup_company_signature_field',
        'Company Signature',
        'sagargrup_company_signature_field_html',
        'sagargrup-email-settings',
        'sagargrup_email_section'
    );

    add_settings_field(
        'sagargrup_email_reply_to_field',
        'Reply-To Email',
        'sagargrup_email_reply_to_field_html',
        'sagargrup-email-settings',
        'sagargrup_email_section'
    );

    add_settings_field(
        'sagargrup_enable_email_copy_field',
        'Send Copy to Multiple Emails',
        'sagargrup_enable_email_copy_field_html',
        'sagargrup-email-settings',
        'sagargrup_advanced_email_section'
    );

    add_settings_field(
        'sagargrup_email_copy_addresses_field',
        'Additional Email Addresses',
        'sagargrup_email_copy_addresses_field_html',
        'sagargrup-email-settings',
        'sagargrup_advanced_email_section'
    );

    add_settings_field(
        'sagargrup_enable_email_attachments_field',
        'Enable Email Attachments',
        'sagargrup_enable_email_attachments_field_html',
        'sagargrup-email-settings',
        'sagargrup_advanced_email_section'
    );
}
add_action('admin_init', 'sagargrup_email_settings_init');

// Email settings callbacks
function sagargrup_email_section_callback() {
    echo '<p>Configure automatic email responses for contact form submissions.</p>';
}

function sagargrup_advanced_email_section_callback() {
    echo '<p>Advanced email features for enhanced contact management.</p>';
}

function sagargrup_enable_auto_email_field_html() {
    $enabled = get_option('sagargrup_enable_auto_email', 1);
    echo '<input type="checkbox" name="sagargrup_enable_auto_email" value="1" ' . checked(1, $enabled, false) . '> Enable automatic email to users';
}

function sagargrup_admin_email_field_html() {
    $email = get_option('sagargrup_admin_email', get_option('admin_email'));
    echo '<input type="email" name="sagargrup_admin_email" value="' . esc_attr($email) . '" class="regular-text">';
    echo '<p class="description">Email address to receive admin notifications</p>';
}

function sagargrup_email_subject_field_html() {
    $subject = get_option('sagargrup_email_subject', 'Thank you for your inquiry');
    echo '<input type="text" name="sagargrup_email_subject" value="' . esc_attr($subject) . '" class="regular-text">';
}

function sagargrup_email_message_field_html() {
    $message = get_option('sagargrup_email_message', 'Dear [user-name], thank you for your inquiry. We will respond soon under 48 hours.');
    echo '<textarea name="sagargrup_email_message" rows="5" class="large-text">' . esc_textarea($message) . '</textarea>';
    echo '<p class="description">Use [user-name], [user-email], [user-subject], [user-message] as placeholders</p>';
}

function sagargrup_email_template_field_html() {
    $template = get_option('sagargrup_email_template', 'simple');
    $templates = array(
        'simple' => 'Simple Text',
        'professional' => 'Professional HTML',
        'modern' => 'Modern Design',
        'corporate' => 'Corporate Style'
    );
    echo '<select name="sagargrup_email_template" class="regular-text">';
    foreach ($templates as $value => $label) {
        echo '<option value="' . esc_attr($value) . '" ' . selected($template, $value, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
}

function sagargrup_company_signature_field_html() {
    $signature = get_option('sagargrup_company_signature', 'Best regards,<br>' . get_bloginfo('name') . '<br>support@sagargrup.xyz<br>9741860714');
    echo '<textarea name="sagargrup_company_signature" rows="4" class="large-text">' . esc_textarea($signature) . '</textarea>';
    echo '<p class="description">Company signature that will be added to all emails</p>';
}

function sagargrup_email_reply_to_field_html() {
    $reply_to = get_option('sagargrup_email_reply_to', get_option('admin_email'));
    echo '<input type="email" name="sagargrup_email_reply_to" value="' . esc_attr($reply_to) . '" class="regular-text">';
    echo '<p class="description">Reply-to email address for user responses</p>';
}

function sagargrup_enable_email_copy_field_html() {
    $enabled = get_option('sagargrup_enable_email_copy', 0);
    echo '<input type="checkbox" name="sagargrup_enable_email_copy" value="1" ' . checked(1, $enabled, false) . '> Send copy to additional email addresses';
}

function sagargrup_email_copy_addresses_field_html() {
    $addresses = get_option('sagargrup_email_copy_addresses', '');
    echo '<textarea name="sagargrup_email_copy_addresses" rows="3" class="large-text">' . esc_textarea($addresses) . '</textarea>';
    echo '<p class="description">Enter email addresses separated by commas (one per line)</p>';
}

function sagargrup_enable_email_attachments_field_html() {
    $enabled = get_option('sagargrup_enable_email_attachments', 0);
    echo '<input type="checkbox" name="sagargrup_enable_email_attachments" value="1" ' . checked(1, $enabled, false) . '> Enable file attachments in contact form';
}

// Email settings page HTML
function sagargrup_email_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Email Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('sagargrup_email_options');
            do_settings_sections('sagargrup-email-settings');
            submit_button('Save Email Settings');
            ?>
        </form>
    </div>
    <?php
}

// Send auto email to user
function sagargrup_send_auto_email($first_name, $last_name, $email, $subject, $message, $attachment_path = '') {
    $enabled = get_option('sagargrup_enable_auto_email', 1);
    
    if (!$enabled) {
        return false;
    }
    
    $email_subject = get_option('sagargrup_email_subject', 'Thank you for your inquiry');
    $email_message = get_option('sagargrup_email_message', 'Dear [user-name], thank you for your inquiry. We will respond soon under 48 hours.');
    $admin_email = get_option('sagargrup_admin_email', get_option('admin_email'));
    $reply_to = get_option('sagargrup_email_reply_to', get_option('admin_email'));
    $signature = get_option('sagargrup_company_signature', 'Best regards,<br>' . get_bloginfo('name'));
    $template = get_option('sagargrup_email_template', 'simple');
    $enable_copy = get_option('sagargrup_enable_email_copy', 0);
    $copy_addresses = get_option('sagargrup_email_copy_addresses', '');
    
    // Replace placeholders
    $user_name = $first_name . ' ' . $last_name;
    $placeholders = array(
        '[user-name]' => $user_name,
        '[user-email]' => $email,
        '[user-subject]' => $subject,
        '[user-message]' => $message,
        '[company-name]' => get_bloginfo('name'),
        '[current-date]' => date('Y-m-d'),
        '[current-time]' => date('H:i:s')
    );
    
    $email_message = str_replace(array_keys($placeholders), array_values($placeholders), $email_message);
    
    // Apply template styling
    $email_template = sagargrup_apply_email_template($template, $email_message, $signature);
    $email_body = $email_template['html'];
    
    // Improved email headers for better deliverability
    $headers = array(
        'Content-Type: multipart/alternative; boundary="=_boundary_' . md5(time()) . '"',
        'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
        'Reply-To: ' . $reply_to,
        'Return-Path: ' . $admin_email,
        'Sender: ' . $admin_email,
        'X-Mailer: PHP/' . phpversion(),
        'X-Priority: 3',
        'MIME-Version: 1.0'
    );
    
    // Create multipart email with both HTML and plain text
    $boundary = '=_boundary_' . md5(time());
    $multipart_body = "--{$boundary}\n";
    $multipart_body .= "Content-Type: text/plain; charset=UTF-8\n";
    $multipart_body .= "Content-Transfer-Encoding: 7bit\n\n";
    $multipart_body .= $email_template['text'] . "\n\n";
    $multipart_body .= "--{$boundary}\n";
    $multipart_body .= "Content-Type: text/html; charset=UTF-8\n";
    $multipart_body .= "Content-Transfer-Encoding: 7bit\n\n";
    $multipart_body .= $email_body . "\n\n";
    $multipart_body .= "--{$boundary}--";
    
    // Prepare recipient list
    $recipients = array($email);
    
    // Add copy addresses if enabled
    if ($enable_copy && !empty($copy_addresses)) {
        $additional_emails = array_map('trim', explode(',', $copy_addresses));
        foreach ($additional_emails as $additional_email) {
            if (is_email($additional_email)) {
                $recipients[] = $additional_email;
            }
        }
    }
    
    // Prepare attachments with proper content type
    $attachments = array();
    if (!empty($attachment_path) && file_exists($attachment_path)) {
        $attachments[] = $attachment_path;
    }
    
    // Send email to user and copies
    $user_email_sent = wp_mail($recipients, $email_subject, $multipart_body, $headers, $attachments);
    
    // Send notification to admin
    $admin_subject = 'New Contact Form Submission: ' . $subject;
    $admin_template = sagargrup_apply_email_template('admin', '', '', true, array(
        'user_name' => $user_name,
        'user_email' => $email,
        'user_subject' => $subject,
        'user_message' => $message,
        'submission_time' => current_time('Y-m-d H:i:s'),
        'attachment_info' => !empty($attachment_path) ? basename($attachment_path) . ' (' . size_format(filesize($attachment_path)) . ')' : 'None'
    ));
    
    $admin_headers = array(
        'Content-Type: multipart/alternative; boundary="=_boundary_' . md5(time() . 'admin') . '"',
        'From: ' . get_bloginfo('name') . ' Contact Form <' . $admin_email . '>',
        'Reply-To: ' . $admin_email,
        'Return-Path: ' . $admin_email,
        'X-Mailer: PHP/' . phpversion(),
        'X-Priority: 3'
    );
    
    $admin_boundary = '=_boundary_' . md5(time() . 'admin');
    $admin_multipart_body = "--{$admin_boundary}\n";
    $admin_multipart_body .= "Content-Type: text/plain; charset=UTF-8\n";
    $admin_multipart_body .= "Content-Transfer-Encoding: 7bit\n\n";
    $admin_multipart_body .= $admin_template['text'] . "\n\n";
    $admin_multipart_body .= "--{$admin_boundary}\n";
    $admin_multipart_body .= "Content-Type: text/html; charset=UTF-8\n";
    $admin_multipart_body .= "Content-Transfer-Encoding: 7bit\n\n";
    $admin_multipart_body .= $admin_template['html'] . "\n\n";
    $admin_multipart_body .= "--{$admin_boundary}--";
    
    $admin_email_sent = wp_mail($admin_email, $admin_subject, $admin_multipart_body, $admin_headers, $attachments);
    
    return $user_email_sent && $admin_email_sent;
}

// Apply template styling
function sagargrup_apply_email_template($template, $message, $signature = '', $is_admin = false, $admin_data = array()) {
    $company_name = get_bloginfo('name');
    $site_url = home_url();
    
    if ($is_admin) {
        $html_content = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #0073aa; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>
                <h2 style='margin: 0;'>New Contact Message</h2>
            </div>
            <div style='background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 5px 5px;'>
                <p><strong>Name:</strong> {$admin_data['user_name']}</p>
                <p><strong>Email:</strong> {$admin_data['user_email']}</p>
                <p><strong>Subject:</strong> {$admin_data['user_subject']}</p>
                <p><strong>Attachment:</strong> {$admin_data['attachment_info']}</p>
                <p><strong>Message:</strong></p>
                <div style='background: white; padding: 15px; border-left: 4px solid #0073aa; margin: 10px 0;'>
                    " . nl2br(esc_html($admin_data['user_message'])) . "
                </div>
                <p><strong>Time:</strong> {$admin_data['submission_time']}</p>
                <hr style='margin: 20px 0; border: none; border-top: 1px solid #ddd;'>
                <p style='color: #666; font-size: 12px;'>
                    This message was sent from the contact form on <a href='{$site_url}'>{$company_name}</a>
                </p>
            </div>
        </div>";
        
        // Plain text version
        $plain_text = "New Contact Message\n\n";
        $plain_text .= "Name: {$admin_data['user_name']}\n";
        $plain_text .= "Email: {$admin_data['user_email']}\n";
        $plain_text .= "Subject: {$admin_data['user_subject']}\n";
        $plain_text .= "Attachment: {$admin_data['attachment_info']}\n";
        $plain_text .= "Message:\n{$admin_data['user_message']}\n\n";
        $plain_text .= "Time: {$admin_data['submission_time']}\n";
        $plain_text .= "Sent from: {$site_url}";
        
        return array('html' => $html_content, 'text' => $plain_text);
    }
    
    $html_content = '';
    $plain_text = '';
    
    switch ($template) {
        case 'professional':
            $html_content = "
            <div style='font-family: Georgia, serif; max-width: 600px; margin: 0 auto; padding: 30px; background: #ffffff; border: 1px solid #e0e0e0;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #2c3e50; margin: 0;'>{$company_name}</h1>
                    <div style='width: 50px; height: 2px; background: #3498db; margin: 10px auto;'></div>
                </div>
                <div style='background: #f8f9fa; padding: 25px; border-radius: 8px; margin: 20px 0;'>
                    {$message}
                </div>
                <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; color: #666;'>
                    {$signature}
                </div>
            </div>";
            $plain_text = "{$company_name}\n\n{$message}\n\n{$signature}";
            break;
            
        case 'modern':
            $html_content = "
            <div style='font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>
                <div style='background: white; margin: 20px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden;'>
                    <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;'>
                        <h1 style='color: white; margin: 0; font-size: 28px; font-weight: 700;'>{$company_name}</h1>
                        <p style='color: rgba(255,255,255,0.9); margin: 10px 0 0 0;'>Thank you for reaching out!</p>
                    </div>
                    <div style='padding: 40px 30px;'>
                        <div style='color: #333; line-height: 1.6; font-size: 16px;'>
                            {$message}
                        </div>
                        <div style='margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;'>
                            {$signature}
                        </div>
                    </div>
                </div>
            </div>";
            $plain_text = "{$company_name}\nThank you for reaching out!\n\n{$message}\n\n{$signature}";
            break;
            
        case 'corporate':
            $html_content = "
            <div style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-width: 650px; margin: 0 auto; padding: 20px; background: #ffffff;'>
                <div style='background: #1a1a1a; padding: 20px; text-align: center;'>
                    <h1 style='color: white; margin: 0; font-size: 24px; font-weight: 300; letter-spacing: 2px; text-transform: uppercase;'>{$company_name}</h1>
                </div>
                <div style='padding: 30px; border: 1px solid #e0e0e0; border-top: none;'>
                    <div style='color: #333; line-height: 1.8; font-size: 15px;'>
                        {$message}
                    </div>
                    <div style='margin-top: 40px; padding-top: 20px; border-top: 2px solid #1a1a1a; color: #666; font-size: 13px;'>
                        {$signature}
                    </div>
                    <div style='margin-top: 30px; text-align: center; color: #999; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;'>
                        <p>© " . date('Y') . " {$company_name}. All rights reserved.</p>
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </div>";
            $plain_text = "{$company_name}\n\n{$message}\n\n{$signature}\n\n© " . date('Y') . " {$company_name}. All rights reserved.";
            break;
            
        default: // simple
            $html_content = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: #f5f5f5; padding: 20px; border-radius: 5px; border-left: 4px solid #0073aa;'>
                    {$message}
                </div>
                <div style='margin-top: 20px; color: #666; font-size: 14px;'>
                    {$signature}
                </div>
                <div style='margin-top: 20px; font-size: 12px; color: #999;'>
                    <p>This message was sent from <a href='{$site_url}'>{$company_name}</a></p>
                </div>
            </div>";
            $plain_text = "{$message}\n\n{$signature}\n\nSent from {$company_name} - {$site_url}";
            break;
    }
    
    return array('html' => $html_content, 'text' => $plain_text);
}

// Add test message functionality
function sagargrup_add_test_message() {
    if (isset($_POST['add_test_message']) && current_user_can('manage_options')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sagargrup_contact_messages';
        
        // Ensure table exists
        sagargrup_create_contact_messages_table();
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
                'subject' => 'Test Message',
                'message' => 'This is a test message to verify the contact system is working.',
                'status' => 'unread',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        if ($result) {
            wp_redirect(add_query_arg(['page' => 'sagargrup-contact-messages', 'test_added' => '1'], admin_url('admin.php')));
            exit;
        }
    }
}
add_action('admin_init', 'sagargrup_add_test_message');

// HTML for the contact messages admin page
function sagargrup_contact_messages_page_html() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sagargrup_contact_messages';
    
    // Ensure table exists
    sagargrup_create_contact_messages_table();
    
    // Handle message status update
    if (isset($_POST['update_message_status']) && isset($_POST['message_id']) && isset($_POST['status'])) {
        $message_id = intval($_POST['message_id']);
        $status = sanitize_text_field($_POST['status']);
        
        if (in_array($status, ['read', 'unread'])) {
            $wpdb->update(
                $table_name,
                array('status' => $status),
                array('id' => $message_id),
                array('%s'),
                array('%d')
            );
            echo '<div class="notice notice-success is-dismissible"><p>Message status updated successfully!</p></div>';
        }
    }
    
    // Handle message deletion
    if (isset($_POST['delete_message']) && isset($_POST['message_id'])) {
        $message_id = intval($_POST['message_id']);
        $wpdb->delete(
            $table_name,
            array('id' => $message_id),
            array('%d')
        );
        echo '<div class="notice notice-success is-dismissible"><p>Message deleted successfully!</p></div>';
    }
    
    // Debug: Check if table exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    // Get messages
    if ($table_exists) {
        $messages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        $unread_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'unread'");
    } else {
        $messages = array();
        $unread_count = 0;
        echo '<div class="notice notice-error"><p>Database table not found. Please contact administrator.</p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1>Contact Messages</h1>
        <p>
            Total Messages: <?php echo count($messages); ?> | 
            Unread: <strong><?php echo $unread_count; ?></strong>
            <?php if ($table_exists): ?>
                <span style="color: green;">✓ Table exists</span>
            <?php else: ?>
                <span style="color: red;">✗ Table missing</span>
            <?php endif; ?>
        </p>
        
        <?php if (isset($_GET['test_added'])): ?>
            <div class="notice notice-success is-dismissible">
                <p>Test message added successfully!</p>
            </div>
        <?php endif; ?>
        
        <?php if (empty($messages)): ?>
            <div class="notice notice-info">
                <p>No contact messages found.</p>
                <p><small>Table: <?php echo esc_html($table_name); ?></small></p>
                <form method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="add_test_message" value="1">
                    <button type="submit" class="button button-primary">Add Test Message</button>
                </form>
            </div>
        <?php else: ?>
            <p style="margin-bottom: 15px;">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="add_test_message" value="1">
                    <button type="submit" class="button button-secondary">Add Test Message</button>
                </form>
            </p>
            <style>
                .message-table {
                    margin-top: 20px;
                }
                .message-row {
                    border-left: 4px solid #ddd;
                    margin-bottom: 15px;
                    padding: 15px;
                    background: #fff;
                    border-radius: 5px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                }
                .message-row.unread {
                    border-left-color: #0073aa;
                    background: #f9f9f9;
                }
                .message-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 10px;
                }
                .message-meta {
                    color: #666;
                    font-size: 12px;
                }
                .message-subject {
                    font-weight: bold;
                    margin-bottom: 8px;
                }
                .message-content {
                    margin-bottom: 10px;
                    line-height: 1.5;
                }
                .message-actions {
                    display: flex;
                    gap: 10px;
                }
                .status-badge {
                    padding: 3px 8px;
                    border-radius: 3px;
                    font-size: 11px;
                    font-weight: bold;
                    text-transform: uppercase;
                }
                .status-badge.unread {
                    background: #0073aa;
                    color: white;
                }
                .status-badge.read {
                    background: #46b450;
                    color: white;
                }
            </style>
            
            <div class="message-table">
                <?php foreach ($messages as $message): ?>
                    <div class="message-row <?php echo $message->status === 'unread' ? 'unread' : ''; ?>">
                        <div class="message-header">
                            <div>
                                <strong><?php echo esc_html($message->first_name . ' ' . $message->last_name); ?></strong>
                                <span class="message-meta">
                                    &lt;<?php echo esc_html($message->email); ?>&gt; • 
                                    <?php echo date('M j, Y, g:i a', strtotime($message->created_at)); ?>
                                </span>
                            </div>
                            <span class="status-badge <?php echo $message->status; ?>">
                                <?php echo esc_html($message->status); ?>
                            </span>
                        </div>
                        
                        <div class="message-subject">
                            <?php echo esc_html($message->subject); ?>
                        </div>
                        
                        <div class="message-content">
                            <?php echo nl2br(esc_html($message->message)); ?>
                        </div>
                        
                        <div class="message-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="message_id" value="<?php echo $message->id; ?>">
                                <input type="hidden" name="status" value="<?php echo $message->status === 'unread' ? 'read' : 'unread'; ?>">
                                <input type="hidden" name="update_message_status" value="1">
                                <button type="submit" class="button button-secondary">
                                    Mark as <?php echo $message->status === 'unread' ? 'Read' : 'Unread'; ?>
                                </button>
                            </form>
                            
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                <input type="hidden" name="message_id" value="<?php echo $message->id; ?>">
                                <input type="hidden" name="delete_message" value="1">
                                <button type="submit" class="button button-link-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

if ( ! function_exists( 'sagargrup_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function sagargrup_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x( 'Posted on %s', 'post date', 'sagargrup' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

    }
endif;

if ( ! function_exists( 'sagargrup_posted_by' ) ) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function sagargrup_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x( 'by %s', 'post author', 'sagargrup' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

    }
endif;

if ( ! function_exists( 'sagargrup_entry_footer' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function sagargrup_entry_footer() {
        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( esc_html__( ', ', 'sagargrup' ) );
            if ( $categories_list ) {
                /* translators: 1: list of categories. */
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'sagargrup' ) . '</span>', $categories_list ); // WPCS: XSS OK.
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'sagargrup' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'sagargrup' ) . '</span>', $tags_list ); // WPCS: XSS OK.
            }
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'sagargrup' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'sagargrup' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

// Create job applications table on theme activation
function create_job_applications_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';
    
    // Check if table already exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    if (!$table_exists) {
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            application_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            full_name varchar(255) NOT NULL,
            phone varchar(50) NOT NULL,
            email varchar(255) NOT NULL,
            date_of_birth date NOT NULL,
            gender varchar(20) NOT NULL,
            marital_status varchar(20) NOT NULL,
            current_address text NOT NULL,
            permanent_address text,
            father_name varchar(255) NOT NULL,
            mother_name varchar(255) NOT NULL,
            nationality varchar(100) NOT NULL,
            religion varchar(100),
            position_applied varchar(255) NOT NULL,
            expected_salary varchar(100) NOT NULL,
            available_date date NOT NULL,
            work_type varchar(50) NOT NULL,
            cover_letter text,
            highest_education varchar(100) NOT NULL,
            field_of_study varchar(255) NOT NULL,
            university varchar(255) NOT NULL,
            graduation_year int NOT NULL,
            additional_education text,
            total_experience decimal(5,2) NOT NULL,
            current_company varchar(255),
            current_position varchar(255),
            key_skills text,
            experience_details text,
            cv_file_path varchar(500) NOT NULL,
            cover_letter_file_path varchar(500),
            ref1_name varchar(255),
            ref1_contact varchar(255),
            ref2_name varchar(255),
            ref2_contact varchar(255),
            languages varchar(500),
            hobbies varchar(500),
            additional_info text,
            terms_accepted tinyint(1) NOT NULL DEFAULT 0,
            status varchar(50) DEFAULT 'pending',
            PRIMARY KEY  (id),
            KEY email (email),
            KEY application_date (application_date),
            KEY status (status)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Log table creation
        error_log('Job applications table created: ' . $table_name);
    }
}
add_action('after_switch_theme', 'create_job_applications_table');

// Also try to create table on every admin page load (temporary fix)
add_action('admin_init', function() {
    create_job_applications_table();
});

// Handle job application submission
function handle_job_application_submission() {
    if (!isset($_POST['action']) || $_POST['action'] !== 'submit_job_application') {
        return;
    }
    
    if (!wp_verify_nonce($_POST['job_nonce'], 'job_application_nonce')) {
        wp_die('Security check failed!');
    }
    
    // Ensure table exists before processing
    create_job_applications_table();
    
    // Validate required fields
    $required_fields = array(
        'full_name', 'phone', 'email', 'date_of_birth', 'gender', 'marital_status',
        'current_address', 'father_name', 'mother_name', 'nationality',
        'position_applied', 'expected_salary', 'available_date', 'work_type',
        'highest_education', 'field_of_study', 'university', 'graduation_year',
        'total_experience', 'terms_accepted'
    );
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_redirect(add_query_arg('error', 'missing_fields', wp_get_referer()));
            exit;
        }
    }
    
    // Handle file uploads
    $cv_file_path = '';
    $cover_letter_file_path = '';
    
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] == 0) {
        $cv_file_path = handle_cv_upload($_FILES['cv_file']);
        if (!$cv_file_path) {
            wp_redirect(add_query_arg('error', 'cv_upload_failed', wp_get_referer()));
            exit;
        }
    } else {
        // CV is required
        wp_redirect(add_query_arg('error', 'cv_required', wp_get_referer()));
        exit;
    }
    
    if (isset($_FILES['cover_letter_file']) && $_FILES['cover_letter_file']['error'] == 0) {
        $cover_letter_file_path = handle_cv_upload($_FILES['cover_letter_file']);
    }
    
    // Save to database
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';
    
    // Check if table exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    if (!$table_exists) {
        error_log('Job applications table does not exist: ' . $table_name);
        wp_redirect(add_query_arg('error', 'table_not_exists', wp_get_referer()));
        exit;
    }
    
    $data = array(
        'application_date' => current_time('mysql'),
        'full_name' => sanitize_text_field($_POST['full_name']),
        'phone' => sanitize_text_field($_POST['phone']),
        'email' => sanitize_email($_POST['email']),
        'date_of_birth' => sanitize_text_field($_POST['date_of_birth']),
        'gender' => sanitize_text_field($_POST['gender']),
        'marital_status' => sanitize_text_field($_POST['marital_status']),
        'current_address' => sanitize_textarea_field($_POST['current_address']),
        'permanent_address' => sanitize_textarea_field($_POST['permanent_address']),
        'father_name' => sanitize_text_field($_POST['father_name']),
        'mother_name' => sanitize_text_field($_POST['mother_name']),
        'nationality' => sanitize_text_field($_POST['nationality']),
        'religion' => sanitize_text_field($_POST['religion']),
        'position_applied' => sanitize_text_field($_POST['position_applied']),
        'expected_salary' => sanitize_text_field($_POST['expected_salary']),
        'available_date' => sanitize_text_field($_POST['available_date']),
        'work_type' => sanitize_text_field($_POST['work_type']),
        'cover_letter' => sanitize_textarea_field($_POST['cover_letter']),
        'highest_education' => sanitize_text_field($_POST['highest_education']),
        'field_of_study' => sanitize_text_field($_POST['field_of_study']),
        'university' => sanitize_text_field($_POST['university']),
        'graduation_year' => intval($_POST['graduation_year']),
        'additional_education' => sanitize_textarea_field($_POST['additional_education']),
        'total_experience' => floatval($_POST['total_experience']),
        'current_company' => sanitize_text_field($_POST['current_company']),
        'current_position' => sanitize_text_field($_POST['current_position']),
        'key_skills' => sanitize_text_field($_POST['key_skills']),
        'experience_details' => sanitize_textarea_field($_POST['experience_details']),
        'cv_file_path' => $cv_file_path,
        'cover_letter_file_path' => $cover_letter_file_path,
        'ref1_name' => sanitize_text_field($_POST['ref1_name']),
        'ref1_contact' => sanitize_text_field($_POST['ref1_contact']),
        'ref2_name' => sanitize_text_field($_POST['ref2_name']),
        'ref2_contact' => sanitize_text_field($_POST['ref2_contact']),
        'languages' => sanitize_text_field($_POST['languages']),
        'hobbies' => sanitize_text_field($_POST['hobbies']),
        'additional_info' => sanitize_textarea_field($_POST['additional_info']),
        'terms_accepted' => isset($_POST['terms_accepted']) ? 1 : 0,
        'status' => 'pending'
    );
    
    $format = array(
        '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%d', '%s'
    );
    
    $result = $wpdb->insert($table_name, $data, $format);
    
    if ($result === false) {
        // Debug: Show the actual database error
        $error_message = $wpdb->last_error;
        error_log('Job Application Database Error: ' . $error_message);
        error_log('Data attempted: ' . print_r($data, true));
        
        wp_redirect(add_query_arg(['error' => 'database_error', 'msg' => urlencode($error_message)], wp_get_referer()));
        exit;
    }
    
    // Send email notification
    send_job_application_email($data);
    
    // Send confirmation email to applicant
    send_applicant_confirmation_email($data);
    
    // Redirect to success page
    wp_redirect(add_query_arg('success', '1', wp_get_referer()));
    exit;
}
add_action('admin_post_submit_job_application', 'handle_job_application_submission');
add_action('admin_post_nopriv_submit_job_application', 'handle_job_application_submission');

// Handle CV file upload
function handle_cv_upload($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Check file type
    $file_type = wp_check_filetype($file['name']);
    if ($file_type['ext'] !== 'pdf') {
        return false;
    }
    
    // Check file size (5MB limit)
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }
    
    // Create upload directory if it doesn't exist
    $upload_dir = wp_upload_dir();
    $job_applications_dir = $upload_dir['basedir'] . '/job-applications';
    
    if (!file_exists($job_applications_dir)) {
        wp_mkdir_p($job_applications_dir);
    }
    
    // Generate unique filename
    $filename = uniqid('cv_') . '_' . sanitize_file_name($file['name']);
    $filepath = $job_applications_dir . '/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return '/job-applications/' . $filename;
    }
    
    return false;
}

// Send email notification for new job application
function send_job_application_email($application_data) {
    $admin_email = get_option('admin_email');
    $subject = 'New Job Application: ' . $application_data['full_name'] . ' for ' . $application_data['position_applied'];
    
    $message = "
    <html>
    <body>
        <h2>New Job Application Received</h2>
        <p><strong>Applicant Name:</strong> {$application_data['full_name']}</p>
        <p><strong>Email:</strong> {$application_data['email']}</p>
        <p><strong>Phone:</strong> {$application_data['phone']}</p>
        <p><strong>Position Applied:</strong> {$application_data['position_applied']}</p>
        <p><strong>Expected Salary:</strong> {$application_data['expected_salary']}</p>
        <p><strong>Available Date:</strong> {$application_data['available_date']}</p>
        <p><strong>Work Type:</strong> {$application_data['work_type']}</p>
        <p><strong>Experience:</strong> {$application_data['total_experience']} years</p>
        <p><strong>Education:</strong> {$application_data['highest_education']} in {$application_data['field_of_study']}</p>
        <p><strong>University:</strong> {$application_data['university']}</p>
        <p><strong>Application Date:</strong> {$application_data['application_date']}</p>
        
        <h3>Cover Letter:</h3>
        <p>" . nl2br($application_data['cover_letter']) . "</p>
        
        <p><strong>CV File:</strong> Available in admin panel</p>
        
        <p>Please check the admin panel for complete details and CV download.</p>
    </body>
    </html>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    wp_mail($admin_email, $subject, $message, $headers);
}

// Send confirmation email to applicant
function send_applicant_confirmation_email($application_data) {
    $applicant_email = $application_data['email'];
    $applicant_name = $application_data['full_name'];
    $subject = 'Job Application Received - SagarGrup';
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h2 style='color: #2563eb; margin-bottom: 10px;'>SagarGrup</h2>
                <p style='color: #666; font-size: 14px;'>Career Opportunities</p>
            </div>
            
            <div style='background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 20px;'>
                <h3 style='color: #1f2937; margin-top: 0;'>Dear {$applicant_name},</h3>
                
                <p>Thank you for your job application. We have successfully received your submission for the <strong>" . ucwords(str_replace('_', ' ', $application_data['position_applied'])) . "</strong> position.</p>
                
                <p>Your application is currently under review by our hiring team. We will respond within 7 days regarding the next steps in the recruitment process.</p>
                
                <div style='background: white; padding: 15px; border-left: 4px solid #2563eb; margin: 20px 0;'>
                    <p style='margin: 0; font-size: 14px;'><strong>Application Details:</strong></p>
                    <ul style='margin: 10px 0; padding-left: 20px; font-size: 14px;'>
                        <li>Position: " . ucwords(str_replace('_', ' ', $application_data['position_applied'])) . "</li>
                        <li>Application Date: " . date('F j, Y', strtotime($application_data['application_date'])) . "</li>
                        <li>Reference ID: #" . str_pad(substr(md5($application_data['application_date'] . $application_data['email']), 0, 8), 8, '0', STR_PAD_LEFT) . "</li>
                    </ul>
                </div>
                
                <p>If you have any questions or need to update your application information, please don't hesitate to contact us.</p>
                
                <p>We appreciate your interest in joining SagarGrup and look forward to reviewing your qualifications.</p>
            </div>
            
            <div style='border-top: 1px solid #eee; padding-top: 20px; text-align: center;'>
                <h4 style='margin-bottom: 15px; color: #1f2937;'>Best Regards,</h4>
                <p style='margin: 5px 0; font-weight: bold; color: #2563eb;'>SagarGrup Team</p>
                <p style='margin: 5px 0; font-size: 14px;'>
                    📞 <a href='tel:9741860714' style='color: #2563eb; text-decoration: none;'>9741860714</a><br>
                    ✉️ <a href='mailto:support@sagargrup.xyz' style='color: #2563eb; text-decoration: none;'>support@sagargrup.xyz</a>
                </p>
                
                <div style='margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; font-size: 12px; color: #666;'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>© 2026 SagarGrup. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    wp_mail($applicant_email, $subject, $message, $headers);
}

// Send status change email to applicant
function send_status_change_email($application, $new_status) {
    $applicant_email = $application->email;
    $applicant_name = $application->full_name;
    $position_applied = ucwords(str_replace('_', ' ', $application->position_applied));
    
    if ($new_status === 'rejected') {
        $subject = 'Application Status Update - SagarGrup';
        
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h2 style='color: #dc2626; margin-bottom: 10px;'>SagarGrup</h2>
                    <p style='color: #666; font-size: 14px;'>Career Opportunities</p>
                </div>
                
                <div style='background: #fef2f2; padding: 20px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #dc2626;'>
                    <h3 style='color: #991b1b; margin-top: 0;'>Dear {$applicant_name},</h3>
                    
                    <p>We regret to inform you that your application for the <strong>{$position_applied}</strong> position has been rejected due to some document issues.</p>
                    
                    <p>We encourage you to review your documents and try again with the corrected information. We believe in giving opportunities to qualified candidates like yourself.</p>
                    
                    <div style='background: white; padding: 15px; border-radius: 4px; margin: 20px 0;'>
                        <p style='margin: 0; font-size: 14px;'><strong>What to do next:</strong></p>
                        <ul style='margin: 10px 0; padding-left: 20px; font-size: 14px;'>
                            <li>Review all submitted documents for completeness</li>
                            <li>Ensure all required information is provided</li>
                            <li>Check that documents are clear and readable</li>
                            <li>Feel free to apply again with corrected documents</li>
                        </ul>
                    </div>
                    
                    <p>We appreciate your interest in joining SagarGrup and hope to see an improved application from you in the future.</p>
                </div>
                
                <div style='border-top: 1px solid #eee; padding-top: 20px; text-align: center;'>
                    <h4 style='margin-bottom: 15px; color: #1f2937;'>Thank You,</h4>
                    <p style='margin: 5px 0; font-weight: bold; color: #dc2626;'>SagarGrup Team</p>
                    <p style='margin: 5px 0; font-size: 14px;'>
                        📞 <a href='tel:9741860714' style='color: #2563eb; text-decoration: none;'>9741860714</a><br>
                        ✉️ <a href='mailto:support@sagargrup.xyz' style='color: #2563eb; text-decoration: none;'>support@sagargrup.xyz</a>
                    </p>
                    
                    <div style='margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; font-size: 12px; color: #666;'>
                        <p>This is an automated message. Please do not reply to this email.</p>
                        <p>© 2026 SagarGrup. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
    } elseif ($new_status === 'hired') {
        $subject = 'Congratulations! You\'re Hired - SagarGrup';
        
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h2 style='color: #16a34a; margin-bottom: 10px;'>SagarGrup</h2>
                    <p style='color: #666; font-size: 14px;'>Career Opportunities</p>
                </div>
                
                <div style='background: #f0fdf4; padding: 20px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #16a34a;'>
                    <h3 style='color: #166534; margin-top: 0;'>Dear {$applicant_name},</h3>
                    
                    <p><strong>Congratulations! 🎉</strong> We are pleased to inform you that you have been <strong>HIRED</strong> for the <strong>{$position_applied}</strong> position at SagarGrup!</p>
                    
                    <p>Your qualifications, experience, and interview performance have impressed our team, and we believe you will be a valuable addition to our organization.</p>
                    
                    <div style='background: white; padding: 15px; border-radius: 4px; margin: 20px 0; border: 2px solid #16a34a;'>
                        <p style='margin: 0; font-size: 16px; font-weight: bold; color: #16a34a; text-align: center;'>🎊 Welcome to the SagarGrup Team! 🎊</p>
                    </div>
                    
                    <p>We will contact you soon with further details regarding your onboarding process, start date, and next steps.</p>
                    
                    <p>We are excited to have you join our team and look forward to working with you!</p>
                </div>
                
                <div style='border-top: 1px solid #eee; padding-top: 20px; text-align: center;'>
                    <h4 style='margin-bottom: 15px; color: #1f2937;'>Thank You,</h4>
                    <p style='margin: 5px 0; font-weight: bold; color: #16a34a;'>SagarGrup Team</p>
                    <p style='margin: 5px 0; font-size: 14px;'>
                        📞 <a href='tel:9741860714' style='color: #2563eb; text-decoration: none;'>9741860714</a><br>
                        ✉️ <a href='mailto:support@sagargrup.xyz' style='color: #2563eb; text-decoration: none;'>support@sagargrup.xyz</a>
                    </p>
                    
                    <div style='margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; font-size: 12px; color: #666;'>
                        <p>This is an automated message. Please do not reply to this email.</p>
                        <p>© 2026 SagarGrup. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    wp_mail($applicant_email, $subject, $message, $headers);
}

// Create admin menu for job applications
function create_job_applications_menu() {
    add_menu_page(
        'Job Applications',
        'Job Applications',
        'manage_options',
        'job-applications',
        'job_applications_page',
        'dashicons-portfolio',
        30
    );
}
add_action('admin_menu', 'create_job_applications_menu');

// Job applications admin page
function job_applications_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';
    
    // Handle bulk actions
    if (isset($_POST['action']) && $_POST['action'] === 'bulk_update_status') {
        if (!empty($_POST['application_ids']) && !empty($_POST['new_status'])) {
            $ids = array_map('intval', $_POST['application_ids']);
            $new_status = sanitize_text_field($_POST['new_status']);
            $placeholders = implode(',', array_fill(0, count($ids), '%d'));
            
            // Get application details before updating for emails
            $applications = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table_name WHERE id IN ($placeholders)",
                $ids
            ));
            
            $wpdb->query($wpdb->prepare(
                "UPDATE $table_name SET status = %s WHERE id IN ($placeholders)",
                array_merge([$new_status], $ids)
            ));
            
            // Send email notifications for rejected or hired status
            if (in_array($new_status, ['rejected', 'hired'])) {
                foreach ($applications as $application) {
                    send_status_change_email($application, $new_status);
                }
            }
            
            echo '<div class="notice notice-success"><p>Status updated successfully!</p></div>';
        }
    }
    
    // Handle single status update
    if (isset($_GET['update_status']) && isset($_GET['application_id'])) {
        $application_id = intval($_GET['application_id']);
        $new_status = sanitize_text_field($_GET['update_status']);
        
        // Get application details before updating
        $application = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $application_id));
        
        $wpdb->update(
            $table_name,
            ['status' => $new_status],
            ['id' => $application_id],
            ['%s'],
            ['%d']
        );
        
        // Send email notification for rejected or hired status
        if ($application && in_array($new_status, ['rejected', 'hired'])) {
            send_status_change_email($application, $new_status);
        }
        
        echo '<div class="notice notice-success"><p>Status updated successfully!</p></div>';
    }
    
    // Get applications
    $applications = $wpdb->get_results("SELECT * FROM $table_name ORDER BY application_date DESC");
    
    ?>
    <div class="wrap">
        <h1>Job Applications</h1>
        
        <div class="tablenav top">
            <form method="post" action="">
                <select name="new_status">
                    <option value="">Bulk Actions</option>
                    <option value="pending">Mark as Pending</option>
                    <option value="reviewing">Mark as Reviewing</option>
                    <option value="shortlisted">Mark as Shortlisted</option>
                    <option value="rejected">Mark as Rejected</option>
                    <option value="hired">Mark as Hired</option>
                </select>
                <input type="submit" name="action" value="bulk_update_status" class="button" onclick="return confirm('Update selected applications?')">
            </form>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column">
                        <input type="checkbox" id="cb-select-all-1">
                    </th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Experience</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <th>
                            <input type="checkbox" name="application_ids[]" value="<?php echo $app->id; ?>" class="application-checkbox">
                        </th>
                        <td>
                            <strong><?php echo esc_html($app->full_name); ?></strong><br>
                            <small><?php echo esc_html($app->gender); ?>, <?php echo esc_html($app->marital_status); ?></small>
                        </td>
                        <td>
                            <a href="mailto:<?php echo esc_attr($app->email); ?>"><?php echo esc_html($app->email); ?></a>
                        </td>
                        <td><?php echo esc_html($app->phone); ?></td>
                        <td><?php echo esc_html(ucwords(str_replace('_', ' ', $app->position_applied))); ?></td>
                        <td><?php echo esc_html($app->total_experience); ?> years</td>
                        <td>
                            <span class="status-badge status-<?php echo esc_attr($app->status); ?>">
                                <?php echo esc_html(ucfirst($app->status)); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($app->application_date)); ?></td>
                        <td>
                            <div class="row-actions">
                                <span class="view">
                                    <a href="#" onclick="showApplicationDetails(<?php echo $app->id; ?>); return false;">View</a> |
                                </span>
                                <span class="status">
                                    <a href="#" onclick="showStatusOptions(<?php echo $app->id; ?>, '<?php echo esc_attr($app->status); ?>'); return false;">Status</a> |
                                </span>
                                <?php if ($app->cv_file_path): ?>
                                    <span class="download">
                                        <a href="<?php echo wp_get_upload_dir()['baseurl'] . $app->cv_file_path; ?>" target="_blank">CV</a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($applications)): ?>
            <p>No job applications found.</p>
        <?php endif; ?>
    </div>
    
    <style>
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-reviewing { background: #dbeafe; color: #1e40af; }
        .status-shortlisted { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-hired { background: #e9d5ff; color: #6b21a8; }
        
        .application-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #000;
        }
    </style>
    
    <script>
        function showApplicationDetails(appId) {
            // AJAX call to get application details
            jQuery.post(ajaxurl, {
                action: 'get_application_details',
                application_id: appId
            }, function(response) {
                if (response.success) {
                    jQuery('#application-details-content').html(response.data.html);
                    jQuery('#application-details-modal').show();
                }
            });
        }
        
        function showStatusOptions(appId, currentStatus) {
            var newStatus = prompt('Enter new status (pending, reviewing, shortlisted, rejected, hired):', currentStatus);
            if (newStatus && ['pending', 'reviewing', 'shortlisted', 'rejected', 'hired'].includes(newStatus)) {
                window.location.href = window.location.href + '&update_status=' + newStatus + '&application_id=' + appId;
            }
        }
        
        jQuery(document).ready(function($) {
            $('.close').click(function() {
                $('.application-modal').hide();
            });
            
            $(window).click(function(event) {
                if ($(event.target).hasClass('application-modal')) {
                    $('.application-modal').hide();
                }
            });
            
            $('#cb-select-all-1').click(function() {
                $('.application-checkbox').prop('checked', $(this).prop('checked'));
            });
        });
    </script>
    
    <!-- Application Details Modal -->
    <div id="application-details-modal" class="application-modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="application-details-content"></div>
        </div>
    </div>
    
    <?php
}

// AJAX handler for application details
function get_application_details() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';
    
    $application_id = intval($_POST['application_id']);
    $application = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $application_id));
    
    if ($application) {
        $html = "
            <h2>Application Details: " . esc_html($application->full_name) . "</h2>
            
            <div style='display: grid; grid-template-columns: 1fr 1fr; gap: 20px;'>
                <div>
                    <h3>Personal Information</h3>
                    <p><strong>Name:</strong> " . esc_html($application->full_name) . "</p>
                    <p><strong>Email:</strong> " . esc_html($application->email) . "</p>
                    <p><strong>Phone:</strong> " . esc_html($application->phone) . "</p>
                    <p><strong>Date of Birth:</strong> " . esc_html($application->date_of_birth) . "</p>
                    <p><strong>Gender:</strong> " . esc_html($application->gender) . "</p>
                    <p><strong>Marital Status:</strong> " . esc_html($application->marital_status) . "</p>
                    <p><strong>Current Address:</strong> " . esc_html($application->current_address) . "</p>
                    <p><strong>Father's Name:</strong> " . esc_html($application->father_name) . "</p>
                    <p><strong>Mother's Name:</strong> " . esc_html($application->mother_name) . "</p>
                    <p><strong>Nationality:</strong> " . esc_html($application->nationality) . "</p>
                </div>
                
                <div>
                    <h3>Position & Education</h3>
                    <p><strong>Position Applied:</strong> " . esc_html(ucwords(str_replace('_', ' ', $application->position_applied))) . "</p>
                    <p><strong>Expected Salary:</strong> " . esc_html($application->expected_salary) . "</p>
                    <p><strong>Available Date:</strong> " . esc_html($application->available_date) . "</p>
                    <p><strong>Work Type:</strong> " . esc_html(ucwords(str_replace('_', ' ', $application->work_type))) . "</p>
                    <p><strong>Experience:</strong> " . esc_html($application->total_experience) . " years</p>
                    <p><strong>Education:</strong> " . esc_html(ucwords(str_replace('_', ' ', $application->highest_education))) . "</p>
                    <p><strong>Field of Study:</strong> " . esc_html($application->field_of_study) . "</p>
                    <p><strong>University:</strong> " . esc_html($application->university) . "</p>
                    <p><strong>Graduation Year:</strong> " . esc_html($application->graduation_year) . "</p>
                </div>
            </div>
            
            <div style='margin-top: 20px;'>
                <h3>Cover Letter</h3>
                <p>" . nl2br(esc_html($application->cover_letter)) . "</p>
            </div>
            
            <div style='margin-top: 20px;'>
                <h3>Documents</h3>";
                
                if ($application->cv_file_path) {
                    $html .= "<p><a href='" . wp_get_upload_dir()['baseurl'] . $application->cv_file_path . "' target='_blank' class='button'>Download CV</a></p>";
                }
                
                if ($application->cover_letter_file_path) {
                    $html .= "<p><a href='" . wp_get_upload_dir()['baseurl'] . $application->cover_letter_file_path . "' target='_blank' class='button'>Download Cover Letter</a></p>";
                }
                
                $html .= "
            </div>
        ";
        
        wp_send_json_success(['html' => $html]);
    } else {
        wp_send_json_error('Application not found');
    }
}
add_action('wp_ajax_get_application_details', 'get_application_details');

?>
