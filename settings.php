<?php

function k_telegram_settings_page() {
    ?>
    <div class="wrap">
        <h1><?= __('Settings for telegram lead form','k_telegram') ?></h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('k_telegram_settings');
                do_settings_sections('k_telegram_settings');
                submit_button();
            ?>
        </form>
    </div>
    <script>
      jQuery(document).ready(function($) {
        wp.codeEditor.initialize($('#field_telegram_k_telegram_code_form_content'), cm_settings);
        wp.codeEditor.initialize($('#field_telegram_k_telegram_code_thank_you_content'), cm_settings);
      })
    </script>
    <style>
        #field_telegram_k_telegram_code_form_content+.CodeMirror-wrap { height: 500px; }
        #field_telegram_k_telegram_code_thank_you_content+.CodeMirror-wrap { height: 150px; }
    </style>
    <?php
}

function k_telegram_add_menu_item() {
    add_menu_page(
        __('Telegram Leads','k_telegram'), // page_title
        __('Telegram Leads','k_telegram'), // menu_title
        'manage_options', // capability
        'k_telegram_settings', // menu_slug
        'k_telegram_settings_page', // function
        plugins_url('/', __FILE__).'img/telegram-icon.png', // icon_url
        80 // position
      );
}
add_action('admin_menu', 'k_telegram_add_menu_item');

/* Settings and render input 
* --------------------------------------- */
function k_telegram_settings_init() {

    add_settings_section('k_telegram_section', __('Main settings','k_telegram'), null, 'k_telegram_settings');

    register_setting('k_telegram_settings', 'k_telegram_code_form_content');
    add_settings_field(
        'k_telegram_code_form_content', // ID
        __('Form content (raw HTML)','k_telegram').'<br><h3 style="color:#2271b1">[k_telegram_form]<h3>', // title
        function () { k_render_code_textarea_input('k_telegram_code_form_content', FORM_DEFAULT_CONTENT); },  // render callback function
        'k_telegram_settings', // page
        'k_telegram_section' // section
    );

    register_setting('k_telegram_settings', 'k_telegram_api_key');
    add_settings_field(
        'k_telegram_api_key', 
        __('API Key','k_telegram'), 
        function () { k_render_settings_input('k_telegram_api_key', '', 30); }, 
        'k_telegram_settings', 
        'k_telegram_section'
    );

    register_setting('k_telegram_settings', 'k_telegram_chat_id');
    add_settings_field(
        'k_telegram_chat_id', 
        __('Chat ID','k_telegram'), 
        function () { k_render_settings_input('k_telegram_chat_id'); }, 
        'k_telegram_settings', 
        'k_telegram_section'
    );

    register_setting('k_telegram_settings', 'k_telegram_email');
    add_settings_field(
        'k_telegram_email', 
        __('Email','k_telegram'), 
        function () { k_render_settings_input('k_telegram_email', get_bloginfo('admin_email')); }, 
        'k_telegram_settings', 
        'k_telegram_section'
    );

    register_setting('k_telegram_settings', 'k_telegram_thank_you_page_id');
    add_settings_field(
        'k_telegram_thank_you_page_id', 
        __('Select Thank You page','k_telegram'), 
        function () { k_render_page_dropdown('k_telegram_thank_you_page_id'); }, 
        'k_telegram_settings', 
        'k_telegram_section'
    );

    register_setting('k_telegram_settings', 'k_telegram_code_form_content');
    add_settings_field(
        'k_telegram_code_thank_you_content', 
        __('Thank you message content (raw HTML)','k_telegram').'<br><h3 style="color:#2271b1">[k_telegram_thank_you]<h3>', 
        function () { k_render_code_textarea_input('k_telegram_code_thank_you_content', THANK_YOU_DEFAULT_CONTENT); }, 
        'k_telegram_settings', 
        'k_telegram_section'
    );

}
add_action('admin_init', 'k_telegram_settings_init');


/* Render options fields function
* ----------------------------------------------- */
function k_render_settings_input( $name, $default="", $width=15 ) {
    $content = get_option($name,$default);
    echo '<input id="field_telegram_'.$name.'" type="text" name="'.$name.'" style="width:'.$width.'rem;" value="' . esc_attr($content) . '" />';
}
function k_render_code_textarea_input( $name, $default="" ) {
    echo '<textarea id="field_telegram_'.$name.'" rows="5" cols="55" class="regular-text code code-edit-textarea" name="'.$name.'" >'.htmlspecialchars(get_option($name,$default)).'</textarea>';
}
function k_render_page_dropdown($name) {
    $selected_page = get_option($name);
    $pages = get_pages();
    echo '<select  id="field_telegram_'.$name.'" name="'.$name.'">';
    echo '<option value="0" '.selected($selected_page, "0", false).'>'.__('Dont redirect','k_telegram').'</option>';
    foreach ($pages as $page) {
        echo '<option value="'.esc_attr($page->ID).'" '.selected($selected_page, $page->ID, false).'>'.esc_html($page->post_title).'</option>';
    }
    echo '</select>';
}


function codemirror_enqueue_scripts($hook) {
  $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
  wp_localize_script('jquery', 'cm_settings', $cm_settings);
  wp_enqueue_style('wp-codemirror');
}
add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');


/* Save form settings
* -------------------------------------*/
function k_telegram_save_settings() {
    if (isset($_POST['k_telegram_code_form_content'])) {
        update_option('k_telegram_code_form_content', htmlspecialchars_decode($_POST['k_telegram_code_form_content']));
    }
    if (isset($_POST['k_telegram_code_thank_you_content'])) {
        update_option('k_telegram_code_thank_you_content', htmlspecialchars_decode($_POST['k_telegram_code_thank_you_content']));
    }
    if (isset($_POST['k_telegram_thank_you_page_id'])) {
        update_option('k_telegram_thank_you_page_id', absint($_POST['k_telegram_thank_you_page_id']));
    }
    if (isset($_POST['k_telegram_api_key'])) {
        update_option('k_telegram_api_key', sanitize_text_field($_POST['k_telegram_api_key']));
    }
    if (isset($_POST['k_telegram_chat_id'])) {
        update_option('k_telegram_chat_id', sanitize_text_field($_POST['k_telegram_chat_id']));
    }
    if (isset($_POST['k_telegram_email'])) {
        update_option('k_telegram_email', sanitize_email($_POST['k_telegram_email']));
    }
}
add_action('admin_init', 'k_telegram_save_settings');


/* Post states
* ---------------------------------------*/
add_filter( 'display_post_states', 'ecs_add_post_state', 10, 2 );
function ecs_add_post_state( $post_states, $post ) {
	if( get_the_ID() == get_option('k_telegram_thank_you_page_id') ) {
		$post_states[] = __('Telegram Leads thank you page','k_telegram');
	}
	return $post_states;
}