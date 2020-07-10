<?php
ob_start();
session_start();


$URLS = [
    'jav.tf',
    'jav.yt',
    'jav.wf',
    'jav.com.se',
    'javre.ru',
    'javre.xyz',
    'jav.re',
];

if(isset($_SESSION['skip']) && !empty($_SESSION['skip']))
{
    var_dump(1);
    $URL = $_SESSION['skip'];
    //header("Location: $URL");
    //ob_end_flush();
    //die();
} else {
    var_dump(2);
    $URL = $_SESSION['skip'] = $URLS[array_rand($URLS)];
    //header("Location: $URL");
    //ob_end_flush();
    //die();
}
var_dump($URL);
var_dump($_SESSION);



function Redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    exit();
}

function Redirect2($url, $code = 302)
{
    if (strncmp('cli', PHP_SAPI, 3) !== 0)
    {
        if (headers_sent() !== true)
        {
            if (strlen(session_id()) > 0) // If using sessions
            {
                session_regenerate_id(true); // Avoids session fixation attacks
                session_write_close(); // Avoids having sessions lock other requests
            }

            if (strncmp('cgi', PHP_SAPI, 3) === 0)
            {
                header(sprintf('Status: %03u', $code), true, $code);
            }

            header('Location: ' . $url, true, (preg_match('~^30[1237]$~', $code) > 0) ? $code : 302);
        }

        exit();
    }
}

/*
$fh = fopen('/tmp/track.txt', 'a');
fwrite($fh, $_SERVER['REMOTE_ADDR'] . ' ' . date('c') . "\n");
fclose($fh);

echo '<script type="text/javascript">window.location = "http://www.google.com/"</script>';

*/


