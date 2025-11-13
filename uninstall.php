<?php
/**
 * Uninstall file for Perfect Content Connector
 * 
 * This file is executed when the plugin is uninstalled.
 * It removes all plugin data from the database.
 */

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Remove plugin options
delete_option('perfect_content_connector_api_key');
delete_option('perfect_content_connector_api_endpoint');

// Remove any other plugin data if needed
// Note: We don't remove posts or media as they might be valuable content
