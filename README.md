# Perfect Content Connector WordPress Plugin

A WordPress plugin developed by Mantix BV that integrates with Perfect Content to automatically publish AI-generated content to your WordPress site.

## Features

- **API Integration**: Secure API endpoint for receiving content from Perfect Content
- **Image Handling**: Automatically downloads and saves featured images to WordPress media library
- **Scheduled Publishing**: Supports scheduled post publishing based on provided datetime
- **Admin Interface**: Easy-to-use admin page for API configuration
- **Multilingual**: Supports Dutch and English languages
- **Security**: API key authentication and input validation

## Installation

1. Upload the plugin files to `/wp-content/plugins/perfect-content-connector/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Perfect Content Connector to configure your API settings

## Configuration

1. After activation, go to **Settings > Perfect Content Connector** in your WordPress admin
2. Copy the **API Endpoint** URL and **API Key** 
3. In your Perfect Content dashboard, go to "Edit" your company
4. Fill in the API endpoint and API key from the WordPress plugin
5. Save the settings in Perfect Content

## API Usage

The plugin creates a REST API endpoint at `/wp-json/perfect-content-connector/v1/publish` that accepts POST requests with the following data:

```json
{
    "title": "Your Blog Post Title",
    "body": "Your blog post content with HTML formatting",
    "featured_image_url": "https://example.com/image.jpg",
    "published_at": "2024-01-01T12:00:00Z"
}
```

### Headers Required

- `X-API-KEY`: Your API key from the plugin settings
- `Content-Type`: application/json

### API Response

The API returns detailed information about the created post:

```json
{
    "success": true,
    "id": 123,
    "url": "https://yoursite.com/your-blog-post/",
    "status": "future",
    "published_at": "2024-01-01T12:00:00+00:00",
    "message": "Post created successfully"
}
```

## Security

- All API requests are authenticated using the API key
- Input data is sanitized and validated
- Images are downloaded securely and saved to WordPress media library
- Proper WordPress nonces are used for admin actions

## Multilingual Support

The plugin supports:
- English (en_US)
- Dutch (nl_NL)

Language files are included in the `/languages/` directory.

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- cURL extension (for image downloads)

## Support

For support and more information, visit [Perfect Content](https://perfectcontent.nl).

## About Mantix BV

This plugin is developed by [Mantix BV](https://mantix.nl), a Dutch software development company specializing in web applications and WordPress solutions.

## License

This plugin is licensed under the GPL v2 or later.
