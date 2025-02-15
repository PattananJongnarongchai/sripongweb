<?php
function initializeMessenger() {
    $settings = [
        'theme_color' => '#ffc107',
        'logged_in_greeting' => 'สวัสดีค่ะ 😊 มีอะไรให้เราช่วยไหมคะ?',
        'logged_out_greeting' => 'สวัสดีค่ะ 😊 มีอะไรให้เราช่วยไหมคะ?',
        'greeting_dialog_delay' => 5,
        'greeting_dialog_display' => 'show'
    ];
    
    return $settings;
}

function renderMessengerPlugin($settings = null) {
    if (!$settings) {
        $settings = initializeMessenger();
    }
    
    $html = '<div class="fb-customerchat"
        attribution="biz_inbox"
        page_id="' . FACEBOOK_PAGE_ID . '"
        theme_color="' . $settings['theme_color'] . '"
        greeting_dialog_display="' . $settings['greeting_dialog_display'] . '"
        greeting_dialog_delay="' . $settings['greeting_dialog_delay'] . '"
        logged_in_greeting="' . htmlspecialchars($settings['logged_in_greeting']) . '"
        logged_out_greeting="' . htmlspecialchars($settings['logged_out_greeting']) . '">
    </div>';
    
    return $html;
} 