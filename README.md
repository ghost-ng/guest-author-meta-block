# Guest Author Meta Block

A WordPress plugin that adds a "Guest Author(s)" meta box to the post editing sidebar and a Gutenberg block to display the guest author information dynamically on the front end.

## Plugin Information

- **Version**: 0.75
- **Author**: [ghost-ng](https://github.com/ghost-ng/)
- **Requires WordPress Version**: 5.0 or higher
- **Tested up to**: 6.0
- **License**: GPLv2 or later

## Description

This plugin allows WordPress users to easily assign a guest author to posts using a meta box in the post editing sidebar. The plugin also provides a Gutenberg block that displays the guest author information dynamically on both the editor and the front-end of the website.

### Features

- Adds a "Guest Author(s)" meta box to posts.
- Supports dynamic display of guest authors using a custom Gutenberg block.
- Includes options to style the author block (font size, bold, italic, underline).
- Ability to hide the guest author block on the front end.
- Secure processing of input fields with nonce verification and input sanitization.

## Installation

### Automatic Installation

1. In your WordPress dashboard, go to **Plugins** > **Add New**.  (not yet in the plugin directory)
2. Search for "Guest Author Meta Block."
3. Click **Install Now**, then **Activate**.

### Manual Installation

1. Download the plugin zip file.
2. Unzip the file.
3. Upload the `guest-author-meta-block` folder to the `/wp-content/plugins/` directory.
4. Activate the plugin through the **Plugins** menu in WordPress.

## Usage

### Adding Guest Author Meta

1. In the WordPress editor, locate the **Guest Author(s)** meta box in the post editing screen (right sidebar).
2. Enter the guest author(s) name(s) in the input field.
3. Publish or update your post.

### Displaying Guest Author in Gutenberg

1. Open the post where you want to display the guest author.
2. Add the **Guest Author(s)** block in the Gutenberg editor.
3. The block will automatically fetch the guest author meta from the current post.
4. Use the block settings to adjust the font size, bold, italic, or underline text.
5. Optionally, you can hide the guest author block on the front end.

### Block Attributes

- **Font Size**: Adjust the font size of the guest author name.
- **Bold**: Toggle to display the author name in bold.
- **Italic**: Toggle to display the author name in italic.
- **Underline**: Toggle to underline the author name.
- **Hide**: Toggle to hide the block from rendering on the front end.

## Security

This plugin uses:
- **Nonce verification** for form data security.
- **Input sanitization** for all user inputs to prevent XSS attacks.

## Changelog

### Version 0.75

- Initial release.
- Adds guest author meta box in the sidebar.
- Introduces dynamic Gutenberg block to display guest author information.

## License

This plugin is licensed under the GPLv2 or later. See the [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) file for more details.

