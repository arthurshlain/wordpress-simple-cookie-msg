<?php
/**
 * Display a notice about the use of Cookies for Wordpress.
 * Works even with server caching. Protected from search engine indexation.
 * Usage:
 * 1. Include this file in `functions.php` file of your WordPress theme.
 * 2. Modify settings below.
 */

/**
 * Translations. Array key is the language code in lower case. Must match the value of the `lang` attribute in html tag.
 */
$_ENV['cookie-notification-lang'] = array(
    'en' => [
        'text'        => 'We use cookies and other technologies to improve the quality of the site, analyze the data received and be convenient for visitors. By staying on the site, you agree to this.',
        'btn-caption' => 'OK',
    ],
    'ru' => [
        'text'        => 'Мы используем cookie и другие технологии, чтобы улучшать качество сайта, анализировать полученные данные и быть удобными для посетителей. Оставаясь на сайте, вы соглашаетесь с этим.',
        'btn-caption' => 'ОК',
    ],
);

// Language code aliases
$_ENV['cookie-notification-lang']['en-us'] = $_ENV['cookie-notification-lang']['en'];
$_ENV['cookie-notification-lang']['en-gb'] = $_ENV['cookie-notification-lang']['en'];

// Cookie name
$_ENV['cookie-notification-key'] = 'is_cookie_accepted';

// Accepted cookie value
$_ENV['cookie-notification-value'] = 'yes';

// Cookie lifetime in seconds. If not specified, the session cookie will be cleared when the browser is closed.
$_ENV['cookie-notification-expires'] = 1 * (365 * 24 * 60 * 60);

/**
 * HTML template and javascript
 */
add_action('wp_footer', function () {
    ?>

    <div id="cookie-notification" class="fixed-bottom bg-white text-black" style="display: none">
        <div class="container">
            <p id="cookie-notification-text" data-text=""></p>
            <button id="cookie-notification-btn" class="btn btn-primary"></button>
        </div>
    </div>

    <script>
        (function () {

            function readCookie(name) {
                name += "=";
                let ca = document.cookie.split( ';' );
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt( 0 ) === ' ') c = c.substring( 1, c.length );
                    if (c.indexOf( name ) === 0) return c.substring( name.length, c.length );
                }
                return null;
            }

            if (readCookie( '<?php echo $_ENV['cookie-notification-key'] ?>' ) === '<?php echo $_ENV['cookie-notification-value'] ?>') {
                return;
            }

            function createCookie(name, value, seconds) {
                let expires = "";
                if (seconds) {
                    let date = new Date();
                    date.setTime( date.getTime() + (seconds * 1000) );
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + value + expires + "; path=/";
            }

            let lang = document.documentElement.getAttribute( 'lang' ).toLowerCase() || 'en';

            let cookie_text = '';
            let cookie_btn_caption = '';

            <?php foreach ($_ENV['cookie-notification-lang'] as $lang => $values): ?>
            if (lang === '<?php echo $lang ?>') {
                cookie_text = '<?php echo $values['text'] ?>';
                cookie_btn_caption = '<?php echo $values['btn-caption'] ?>';
            }
            <?php endforeach; ?>

            let cookieNotification = document.getElementById( 'cookie-notification' );
            let cookieNotificationText = document.getElementById( 'cookie-notification-text' );
            let cookieNotificationBtn = document.getElementById( 'cookie-notification-btn' );

            cookieNotification.style.display = null;
            cookieNotificationText.innerText = cookie_text;
            cookieNotificationBtn.innerText = cookie_btn_caption;

            cookieNotificationBtn.addEventListener( 'click', function () {
                createCookie( '<?php echo $_ENV['cookie-notification-key'] ?>', '<?php echo $_ENV['cookie-notification-value'] ?>', '<?php echo $_ENV['cookie-notification-expires'] ?>' );
                cookieNotification.style.display = 'none';
            } );

        })();

    </script>

    <?php
});
