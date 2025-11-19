# Wordpress Media Organizer

A lightweight, object-oriented PHP integration for organizing WordPress Media Library attachments into hierarchical folders using custom taxonomies.

## Overview

Managing thousands of images in WordPress can be chaotic. **Wordpress Media Organizer** solves this by registering a custom `media_folder` taxonomy for the `attachment` post type. This allows developers and site administrators to categorize images just like they categorize posts, creating a virtual folder structure directly within the native WordPress interface.

### Key Features

- **Hierarchical Structure:** Create parent and child folders for deep organization.
- **Native Integration:** Uses standard WordPress UI; no intrusive 3rd party interfaces.
- **List View Support:** Adds a "Folder" column to the Media Library list view.
- **Filtering:** Includes a dropdown filter to quickly find assets within specific folders.
- **Clean Code:** Built with strict OOP principles and prefixed namespaces to prevent conflicts.

## Installation & Usage

1. Copy the `wp-media-organizer` folder into your theme directory (e.g., `your-theme/inc/`).
2. Include the loader file in your theme's `functions.php`:

   ```php
   // Adjust the path according to your directory structure
   require_once get_template_directory() . '/inc/wp-media-organizer/loader.php';

---

## How to Use

**1. Create Folders:** Go to Media > Folders in the WordPress Admin dashboard.
  - Create your desired folder structure (e.g., "Assets", "Assets > Logos", "Marketing").

**Assign Images:**
  - Go to `Media > Library`.
  - Switch to `List View` (the list icon).
  - Click "Edit" on an image, or use "Bulk Actions" to select multiple images and assign them to a "Folder" via the "Edit" bulk action.

**Filter:**
  - In the Media Library List View, use the "All Folders" dropdown menu to view images belonging to a specific category.

## Technical Details
  - **Class Name:** Hussainas_Media_Manager
  - **Taxonomy Key:** media_folder
  - **Compatibility:** PHP 7.4+, WordPress 5.8+

