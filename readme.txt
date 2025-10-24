=== Perfect Content ===
Contributors: perfectcontent
Tags: content, ai, automation, publishing, api
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Integrate with Perfect Content to automatically publish AI-generated content to your WordPress site.

== Description ==

Perfect Content is a WordPress plugin that seamlessly integrates with the Perfect Content web application to automatically publish AI-generated content to your WordPress site.

= Key Features =

* **Secure API Integration**: REST API endpoint for receiving content from Perfect Content
* **Automatic Image Handling**: Downloads and saves featured images to WordPress media library
* **Scheduled Publishing**: Supports scheduled post publishing based on provided datetime
* **Admin Interface**: Easy-to-use admin page for API configuration
* **Multilingual Support**: Available in Dutch and English
* **Security First**: API key authentication and comprehensive input validation

= How It Works =

1. Install and activate the plugin
2. Go to Settings > Perfect Content in your WordPress admin
3. Copy the API endpoint and API key
4. Configure these settings in your Perfect Content dashboard
5. Perfect Content will automatically publish content to your WordPress site

= API Response =

The plugin returns detailed information about published posts:

* **Post URL**: Direct link to the published post
* **Post Status**: Whether the post is published or scheduled
* **Datetime Information**: Publication or scheduled datetime in both ISO format and readable format
* **Post ID**: WordPress post ID for reference

This allows Perfect Content to track and manage published content effectively.

= Perfect Content Service =

Perfect Content is a web application that generates high-quality content using AI and allows professionals to review and edit the content before publishing. The service provides:

* AI-powered content generation
* Professional content review and editing
* Content scheduling and management
* Multi-platform publishing

Visit [Perfect Content](https://perfectcontent.nl) to learn more about the service.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/perfect-content/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Perfect Content to configure your API settings
4. Copy the API endpoint and key to your Perfect Content dashboard

== Frequently Asked Questions ==

= Do I need a Perfect Content account? =

Yes, you need a Perfect Content account to use this plugin. Visit [Perfect Content](https://perfectcontent.nl) to sign up.

= Is the plugin secure? =

Yes, the plugin uses API key authentication and validates all input data. All communication is encrypted and secure.

= Can I schedule posts? =

Yes, the plugin supports scheduled publishing based on the datetime provided by Perfect Content.

= What languages are supported? =

The plugin supports English and Dutch languages.

== Screenshots ==

1. Admin settings page with API configuration
2. Connection status indicator
3. API endpoint and key management

== Changelog ==

= 1.0.0 =
* Initial release
* API integration with Perfect Content
* Automatic image handling
* Scheduled publishing support
* Multilingual support (English/Dutch)
* Secure authentication

== Upgrade Notice ==

= 1.0.0 =
Initial release of Perfect Content WordPress plugin.

== Support ==

For support and more information, visit [Perfect Content](https://perfectcontent.nl).

== Privacy Policy ==

This plugin does not collect or store personal data. It only processes content data sent from the Perfect Content service when explicitly configured by the user.
