# Skill: Create AJAX Endpoint

## Description

This skill automates the creation of a new AJAX endpoint in Underscores Theme. It follows the security and structural guidelines in `ajax-handler.md`. The skill generates the handler file (a global function), and registers it via `includes/config/ajax.php` (consumed by `Theme\Hooks\AjaxHook`).

## Parameters

1.  `endpoint_name`: (Required) The name of the endpoint, in PascalCase (e.g., `LoadMorePosts`, `UpdateCart`). This will be used to generate the file name and function name.
2.  `for_logged_in`: (Optional) Set to `true` to create the endpoint for logged-in users. Defaults to `true`.
3.  `for_guests`: (Optional) Set to `true` to create the endpoint for guest users (not logged in). Defaults to `false`.

## Execution Steps

1.  **Normalize Names**:
    -   From `endpoint_name` (e.g., `LoadMorePosts`), generate:
        -   File Name: `load-more-posts-ajax.php`
        -   Function Name: `underscores_ajax_load_more_posts`
        -   Action Name: `underscores_ajax_load_more_posts` (handle key in `includes/config/ajax.php`)

2.  **Create AJAX Handler File**:
    -   Create a new file at `includes/ajax/{{file-name}}.php`.
    -   Insert boilerplate: `ABSPATH` check + a global function `{{function_name}}` wrapped in `function_exists`, containing `check_ajax_referer('underscores-ajax-security', 'security')`, logic placeholder, `wp_send_json(...)`, `wp_die()`.
    -   Add `require_once` for this file in `includes/bootstrap.php`.

3.  **Register in `includes/config/ajax.php`**:
    -   Add `'{{action_name}}' => '{{function_name}}',` to the returned map.
    -   `Theme\Hooks\AjaxHook::register()` wires both `wp_ajax_` and `wp_ajax_nopriv_` automatically.
    -   (If the endpoint must be logged-in only, handle the auth check inside the function.)

4.  **Report and Remind**:
    -   Notify the user that the AJAX endpoint `{{action_name}}` has been created.
    -   Provide the full path to the new handler file.
    -   **Crucially**, remind the user to pass the nonce and AJAX URL to their JavaScript file using `wp_localize_script`, providing a sample code snippet for them to use.

## Usage Example

**User**: `> create-ajax-endpoint --name=SubmitReview --for-guests=true`

**AI (using this skill)**:
1.  Generates `includes/ajax/submit-review-ajax.php` with the global function `underscores_ajax_submit_review` + security checks.
2.  Adds `require_once …/includes/ajax/submit-review-ajax.php;` to `includes/bootstrap.php`.
3.  Adds `'underscores_ajax_submit_review' => 'underscores_ajax_submit_review',` to `includes/config/ajax.php`.
4.  Responds: "Created the AJAX endpoint. Handler at `includes/ajax/submit-review-ajax.php`, registered via `includes/config/ajax.php`. **Remember** to localize your script: `wp_localize_script('underscores-frontend', 'underscores_params', ['ajaxURL' => admin_url('admin-ajax.php'), 'ajaxNonce' => wp_create_nonce('underscores-ajax-security')]);`"
