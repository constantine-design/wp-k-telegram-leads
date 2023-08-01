<?php

/* Wordpress form processing
----------------------------------------- */
function k_teleram_form_handle() {
    status_header(200);
	
    if ( ! isset( $_POST['_k_telegram_verif'] ) )  {
		exit();
	}
	if ( ! wp_verify_nonce( $_POST['_k_telegram_verif'], 'k_telegram_action' ) ) {
		exit();
	}

    $token = get_option( 'k_telegram_api_key', '');
    $chat_id = get_option( 'k_telegram_chat_id', '');

    $arr = [];
    foreach ( $_POST as $key => $value ) {
        if ( $key!='_k_telegram_verif' && $key!='_wp_http_referer' && $key!='action' ) {
            $name = ucwords(str_replace('_', ' ', $key)).': ';
            $arr[$name] = $value;
        }
    }

    /* Send copy of the lead to the email */
    $mail_lead = "";
    foreach( $arr as $key => $value ) {
        $mail_lead .= " ".$key." ".strip_tags($value).PHP_EOL;
    };
    $mail_txt = PHP_EOL.__('Form data','k_telegram').':'.PHP_EOL.PHP_EOL.$mail_lead.PHP_EOL.__('Form sent from','k_telegram').' '.get_site_url().PHP_EOL;
    wp_mail( get_option( 'k_telegram_email',get_bloginfo('admin_email')), __('You have new lead','k_telegram'), $mail_txt );

    /* Send Lead to the Telegram */
    $telegram_txt = '';
    foreach( $arr as $key => $value ) {
        $stripped_value = strip_tags($value);
        $telegram_txt .= "<b>".$key."</b> ".( strlen($stripped_value) > 500 ? substr($stripped_value,0,500)."..." : $stripped_value )."%0A";
    };
    $sendToTelegram = wp_remote_get("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$telegram_txt}");

    if ( $sendToTelegram ) {
        $redirect_target = get_page_link(get_option('k_telegram_thank_you_page_id'));
        if (get_option('k_telegram_thank_you_page_id','0')=='0') $redirect_target = wp_get_referer();
        $redirect_url = esc_url(add_query_arg(array( 'status' => 'lead_sent' ), $redirect_target));
        wp_redirect( $redirect_url );
        exit();
    } else {
        exit('<h2>'.__('The service is unavailable, sorry for the technical issues, the message was not sent','k_telegram').'</h2>');
    }

    /* request handlers should exit() when they complete their task */
    exit("Server received '{$_REQUEST['data']}' from your browser.");

}
add_action( 'admin_post_k_teleram_form', 'k_teleram_form_handle' );
add_action( 'admin_post_nopriv_k_teleram_form', 'k_teleram_form_handle' );
