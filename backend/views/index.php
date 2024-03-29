<?php
$store_locally = true;
function getContent($url, $geturl = false)
{
    $ch = curl_init();
    $options = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Mobile Safari/537.36',
        CURLOPT_ENCODING       => "utf-8",
        CURLOPT_AUTOREFERER    => false,
        CURLOPT_COOKIEJAR      => 'cookie.txt',
        CURLOPT_COOKIEFILE     => 'cookie.txt',
        CURLOPT_REFERER        => 'https://www.tiktok.com/',
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array($ch, $options);
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($geturl === true) {
        return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    }
    curl_close($ch);
    return strval($data);
}
function getKey($playable)
{
    $ch = curl_init();
    $headers = [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Accept-Encoding: gzip, deflate, br',
        'Accept-Language: en-US,en;q=0.9',
        'Range: bytes=0-200000'
    ];

    $options = array(
        CURLOPT_URL            => $playable,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        CURLOPT_ENCODING       => "utf-8",
        CURLOPT_AUTOREFERER    => false,
        CURLOPT_COOKIEJAR      => 'cookie.txt',
        CURLOPT_COOKIEFILE     => 'cookie.txt',
        CURLOPT_REFERER        => 'https://www.tiktok.com/',
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array($ch, $options);
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $tmp = explode("vid:", $data);
    if (count($tmp) > 1) {
        $key = trim(explode("%", $tmp[1])[0]);
    } else {
        $key = "";
    }
    return $key;
}
function downloadVideo($video_url, $file = "", $geturl = false)
{
    if (!file_exists("tiktok/" . md5($file) . ".mp4")) {
        $ch = curl_init();
        $headers = array(
            'Range: bytes=0-',
        );
        $options = array(
            CURLOPT_URL            => $video_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLINFO_HEADER_OUT    => true,
            CURLOPT_USERAGENT => 'okhttp',
            CURLOPT_ENCODING       => "utf-8",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_COOKIEJAR      => 'cookie.txt',
            CURLOPT_COOKIEFILE     => 'cookie.txt',
            CURLOPT_REFERER        => 'https://www.tiktok.com/',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_MAXREDIRS      => 10,
        );
        curl_setopt_array($ch, $options);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($geturl === true) {
            return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        }
        curl_close($ch);
        file_put_contents("tiktok/" . md5($file) . ".mp4", $data);
    }

    $token = base64_encode("tiktok/" . md5($file) . ".mp4");
    // return $download = $_SERVER['HTTP_HOST'] . "/download.php?token=$token";
    return $download = "https://localhost/wordpress/download.php?token=$token";
}

if (isset($_POST['url']) && !empty($_POST['url'])) {
    $url = $_POST['url'];
    $video_id = explode("video/", $url);
    $file = $video_id[1] ? intval($video_id[1]) : rand(
        11111111111111,
        9999999999999
    );

    $resp = getContent($url);
    $check = explode('"downloadAddr":"', $resp);
    if (count($check) > 1) {
        $contentURL = explode("\"", $check[1])[0];
        $contentURL = str_replace("\\u0026", "&", $contentURL);
        $contentURL = str_replace("\\u002F", "/", $contentURL);
        $thumb = explode("\"", explode('og:image" content="', $resp)[1])[0];
        $username = explode('"', explode('"uniqueId":"', $resp)[1])[0];
        $create_time = explode('"', explode('"createTime":"', $resp)[1])[0];
        $dt = new DateTime("@$create_time");
        $create_time = $dt->format("d M Y H:i:s A");
        $videoKey = getKey($contentURL);
        $cleanVideo = "https://api2-16-h2.musical.ly/aweme/v1/play/?video_id=$videoKey&vr_type=0&is_play_url=1&source=PackSourceEnum_PUBLISH&media_type=4";
        $cleanVideo = getContent($cleanVideo, true);
        if ($store_locally) {
            $link = downloadVideo($contentURL, $file);
            if ($link) {
                echo json_encode([
                    "success" => true,
                    "data" => [
                        "username" => $username,
                        "create_time" => $create_time,
                        "download" => $link,
                        "thumb" => $thumb,
                    ],
                ]);
                die;
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Something went wrong",
                ]);
                die;
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Something went wrong",
            ]);
            die;
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Something went wrong",
        ]);
        die;
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong",
    ]);
    die;
}
