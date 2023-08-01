<?php

/* Build form shortcode
----------------------------------------------- */
function add_lead_form_shortcode() {

    if ( isset($_GET['status']) && $_GET['status']=='lead_sent' )  {
        return strip_shortcodes( get_option( 'k_telegram_code_thank_you_content', THANK_YOU_DEFAULT_CONTENT) );
    } 
    return '
        <form action="'.esc_url(admin_url('admin-post.php')).'" class="k-telegram-lead-form" method="post">
            <input type="hidden" name="action" value="k_teleram_form">
            '.wp_nonce_field( 'k_telegram_action', '_k_telegram_verif', true, false ).'
            '.do_shortcode( get_option( 'k_telegram_code_form_content', FORM_DEFAULT_CONTENT) ).'
        </form>
    ';
        
}
add_shortcode( 'k_telegram_form', 'add_lead_form_shortcode' );


/* Conditionally show thank-you content message
----------------------------------------------- */
function sent_to_telegram_shortcode() { 

    if ( isset($_GET['status']) && $_GET['status']=='lead_sent' )  {
        return do_shortcode( get_option( 'k_telegram_code_thank_you_content', THANK_YOU_DEFAULT_CONTENT) );
    } 
    return '';

}
add_shortcode( 'k_telegram_thank_you', 'sent_to_telegram_shortcode' );