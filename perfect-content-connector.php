<?php
/**
 * Plugin Name: Perfect Content Connector
 * Plugin URI: https://wordpress.org/plugins/perfect-content-connector/
 * Description: Integrate with Perfect Content to automatically publish AI-generated, human checked and approved content to your WordPress site.
 * Version: 1.0.1
 * Author: Mantix BV
 * Author URI: https://mantix.nl
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: perfect-content-connector
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PERFECT_CONTENT_CONNECTOR_VERSION', '1.0.1');
define('PERFECT_CONTENT_CONNECTOR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PERFECT_CONTENT_CONNECTOR_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Main plugin class
class Perfect_Content_Connector_Plugin {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('wp_ajax_perfect_content_connector_regenerate_key', array($this, 'regenerate_api_key'));
        add_action('rest_api_init', array($this, 'register_api_endpoint'));
    }
    
    public function init() {
        // Plugin initialization
    }
    
    public function add_admin_menu() {
        add_options_page(
            __('Perfect Content Settings', 'perfect-content-connector'),
            __('Perfect Content Connector', 'perfect-content-connector'),
            'manage_options',
            'perfect-content-connector',
            array($this, 'admin_page'),
            'dashicons-admin-generic'
        );
        
        // Enqueue admin styles and scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function enqueue_admin_assets($hook) {
        // Only load on our settings page
        if ($hook !== 'settings_page_perfect-content-connector') {
            return;
        }
        
        // Register and enqueue CSS
        wp_register_style(
            'perfect-content-connector-admin',
            PERFECT_CONTENT_CONNECTOR_PLUGIN_URL . 'admin.css',
            array(),
            PERFECT_CONTENT_CONNECTOR_VERSION
        );
        wp_enqueue_style('perfect-content-connector-admin');
        
        // Register and enqueue JavaScript with jQuery dependency
        wp_register_script(
            'perfect-content-connector-admin',
            PERFECT_CONTENT_CONNECTOR_PLUGIN_URL . 'admin.js',
            array('jquery'),
            PERFECT_CONTENT_CONNECTOR_VERSION,
            true
        );
        
        // Localize script to pass PHP variables to JavaScript
        wp_localize_script(
            'perfect-content-connector-admin',
            'perfectContentConnector',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('perfect_content_connector_regenerate_key'),
                'currentApiKey' => get_option('perfect_content_connector_api_key', ''),
                'confirmMessage' => esc_js(__('Are you sure you want to regenerate the API key? This will break the connection with Perfect Content until you update the key in your dashboard.', 'perfect-content-connector')),
                'successMessage' => esc_js(__('API key regenerated successfully!', 'perfect-content-connector')),
                'errorMessage' => esc_js(__('Error regenerating API key.', 'perfect-content-connector'))
            )
        );
        wp_enqueue_script('perfect-content-connector-admin');
    }
    
    public function admin_init() {
        register_setting('perfect_content_connector_settings', 'perfect_content_connector_api_key', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        register_setting('perfect_content_connector_settings', 'perfect_content_connector_api_endpoint', array(
            'sanitize_callback' => 'esc_url_raw'
        ));
    }
    
    public function admin_page() {
        $api_key = get_option('perfect_content_connector_api_key', '');
        $api_endpoint = get_option('perfect_content_connector_api_endpoint', '');
        
        if (empty($api_endpoint)) {
            $api_endpoint = home_url('/wp-json/perfect-content-connector/v1/publish');
            update_option('perfect_content_connector_api_endpoint', $api_endpoint);
        }
        
        ?>
        <div class="wrap perfect-content-connector-admin">
            <div class="card">
                <div class="brand-header">
                    <div class="brand-logo">
                        <img src="<?php echo esc_url(PERFECT_CONTENT_CONNECTOR_PLUGIN_URL); ?>images/icon-teal.svg" alt="Perfect Content Logo" />
                    </div>
                    <h1><?php esc_html_e('Perfect Content Settings', 'perfect-content-connector'); ?></h1>
                </div>
                
                <h2><?php esc_html_e('API Configuration', 'perfect-content-connector'); ?></h2>
                <p><?php esc_html_e('Use these settings in your Perfect Content dashboard to connect your WordPress site.', 'perfect-content-connector'); ?></p>
                
                <?php if (!empty($api_key) && !empty($api_endpoint)): ?>
                <div class="api-info">
                    <strong><?php esc_html_e('Connection Status:', 'perfect-content-connector'); ?></strong>
                    <span class="status-indicator connected"><?php esc_html_e('Ready to receive content', 'perfect-content-connector'); ?></span>
                </div>
                <?php else: ?>
                <div class="api-info">
                    <strong><?php esc_html_e('Connection Status:', 'perfect-content-connector'); ?></strong>
                    <span class="status-indicator disconnected"><?php esc_html_e('Configuration incomplete', 'perfect-content-connector'); ?></span>
                </div>
                <?php endif; ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('API Endpoint', 'perfect-content-connector'); ?></th>
                        <td>
                            <input type="text" value="<?php echo esc_attr($api_endpoint); ?>" readonly class="regular-text" />
                            <p class="description"><?php esc_html_e('Copy this URL to your Perfect Content dashboard.', 'perfect-content-connector'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('API Key', 'perfect-content-connector'); ?></th>
                        <td>
                            <input type="text" value="<?php echo esc_attr($api_key); ?>" readonly class="regular-text" />
                            <button type="button" id="regenerate-key" class="button"><?php esc_html_e('Regenerate Key', 'perfect-content-connector'); ?></button>
                            <p class="description"><?php esc_html_e('Copy this key to your Perfect Content dashboard.', 'perfect-content-connector'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="card">
                <h2><?php esc_html_e('About Perfect Content', 'perfect-content-connector'); ?></h2>
                <p><?php esc_html_e('Perfect Content is a web application that generates high-quality content using AI and allows professionals to review and edit the content before publishing.', 'perfect-content-connector'); ?></p>
                <p>
                    <a href="https://perfectcontent.nl" target="_blank" class="button button-primary"><?php esc_html_e('Visit Perfect Content', 'perfect-content-connector'); ?></a>
                    <a href="https://perfectcontent.nl/dashboard" target="_blank" class="button"><?php esc_html_e('Go to Dashboard', 'perfect-content-connector'); ?></a>
                </p>
                <p class="description">
                    <?php esc_html_e('In your Perfect Content dashboard, go to "Edit" your company and fill in the API endpoint and API key from above.', 'perfect-content-connector'); ?>
                </p>
            </div>
        </div>
        <?php
    }
    
    public function regenerate_api_key() {
        check_ajax_referer('perfect_content_connector_regenerate_key', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to perform this action.', 'perfect-content-connector'));
        }
        
        $new_key = wp_generate_password(32, false);
        update_option('perfect_content_connector_api_key', $new_key);
        
        wp_send_json_success(array('new_key' => $new_key));
    }
    
    public function register_api_endpoint() {
        register_rest_route('perfect-content-connector/v1', '/publish', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_publish_request'),
            'permission_callback' => array($this, 'check_api_permission')
        ));
    }
    
    public function check_api_permission($request) {
        $api_key = $request->get_header('X-API-KEY');
        $stored_key = get_option('perfect_content_connector_api_key', '');
        
        if (empty($stored_key) || $api_key !== $stored_key) {
            return new WP_Error('invalid_api_key', __('Invalid API key', 'perfect-content-connector'), array('status' => 401));
        }
        
        return true;
    }
    
    public function handle_publish_request($request) {
        $title = sanitize_text_field($request->get_param('title'));
        $body = wp_kses_post($request->get_param('body'));
        $featured_image_url = esc_url_raw($request->get_param('featured_image_url'));
        $published_at = $request->get_param('published_at');
        
        if (empty($title) || empty($body)) {
            return new WP_Error('missing_data', __('Title and body are required', 'perfect-content-connector'), array('status' => 400));
        }
        
        // Download and save featured image
        $featured_image_id = null;
        if (!empty($featured_image_url)) {
            $featured_image_id = $this->download_and_save_image($featured_image_url, $title);
        }
        
        // Create post data
        $post_data = array(
            'post_title' => $title,
            'post_content' => $body,
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_author' => get_current_user_id() ?: 1, // Use current user or admin as fallback
        );
        
        // Handle scheduled publishing
        if (!empty($published_at)) {
            $publish_time = strtotime($published_at);
            if ($publish_time > time()) {
                $post_data['post_status'] = 'future';
                $post_data['post_date'] = gmdate('Y-m-d H:i:s', $publish_time);
            }
        }
        
        // Create the post
        $post_id = wp_insert_post($post_data);
        
        if (is_wp_error($post_id)) {
            return new WP_Error('post_creation_failed', __('Failed to create post', 'perfect-content-connector'), array('status' => 500));
        }
        
        // Set featured image
        if ($featured_image_id) {
            set_post_thumbnail($post_id, $featured_image_id);
        }
        
        // Get post URL and scheduled datetime
        $post_url = get_permalink($post_id);
        $post_date = get_post_field('post_date', $post_id);
        $post_status = get_post_status($post_id);
        
        // Format the response
        return array(
            'success' => true,
            'id' => $post_id,
            'url' => $post_url,
            'status' => $post_status,
            'published_at' => gmdate('c', strtotime($post_date)),
            'message' => __('Post created successfully', 'perfect-content-connector')
        );
    }
    
    private function download_and_save_image($image_url, $title) {
        // Validate URL
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            return null;
        }
        
        // Download the image with timeout
        $response = wp_remote_get($image_url, array(
            'timeout' => 30,
            'user-agent' => 'Perfect Content Connector WordPress Plugin'
        ));
        
        if (is_wp_error($response)) {
            return null;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            return null;
        }
        
        $image_data = wp_remote_retrieve_body($response);
        if (empty($image_data)) {
            return null;
        }
        
        // Get file extension
        $file_extension = pathinfo(wp_parse_url($image_url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (empty($file_extension)) {
            $file_extension = 'jpg'; // Default to jpg
        }
        
        // Create filename
        $filename = sanitize_file_name($title) . '.' . $file_extension;
        
        // Upload to WordPress media library
        $upload = wp_upload_bits($filename, null, $image_data);
        
        if ($upload['error']) {
            return null;
        }
        
        // Create attachment
        $attachment = array(
            'post_mime_type' => wp_check_filetype($upload['file'])['type'],
            'post_title' => sanitize_text_field($title),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        $attachment_id = wp_insert_attachment($attachment, $upload['file']);
        
        if (is_wp_error($attachment_id)) {
            return null;
        }
        
        // Generate attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        
        return $attachment_id;
    }
}

// Initialize the plugin
new Perfect_Content_Connector_Plugin();

// Activation hook
register_activation_hook(__FILE__, 'perfect_content_connector_activate');
function perfect_content_connector_activate() {
    // Generate initial API key if none exists
    if (empty(get_option('perfect_content_connector_api_key'))) {
        update_option('perfect_content_connector_api_key', wp_generate_password(32, false));
    }
    
    // Set default API endpoint
    if (empty(get_option('perfect_content_connector_api_endpoint'))) {
        update_option('perfect_content_connector_api_endpoint', home_url('/wp-json/perfect-content-connector/v1/publish'));
    }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'perfect_content_connector_deactivate');
function perfect_content_connector_deactivate() {
    // Clean up if needed
}

