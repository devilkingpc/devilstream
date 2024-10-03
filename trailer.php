<?php
// Function to generate the embed URL based on the type and ID
function getEmbedUrl($type, $id, $season = null, $episode = null) {
    // Validate and sanitize inputs
    $type = htmlspecialchars($type);
    $id = htmlspecialchars($id);
    $season = htmlspecialchars($season);
    $episode = htmlspecialchars($episode);

    if (preg_match('/^tt\d+$/', $id)) {
        // IMDb ID
        if ($type == 'movie') {
            return "https://player.autoembed.cc/embed/movie/$id?server=2&autoplay=0";
        } elseif ($type == 'tv') {
            return "https://player.autoembed.cc/embed/tv/$id/$season/$episode?server=2&autoplay=0";
        }
    } elseif (is_numeric($id)) {
        // TMDB ID
        if ($type == 'movie') {
            return "https://player.autoembed.cc/embed/movie/$id?server=2&autoplay=0";
        } elseif ($type == 'tv') {
            return "https://player.autoembed.cc/embed/tv/$id/$season/$episode?server=2&autoplay=0";
        }
    }
    return null;
}

// Get parameters from the URL
$type = isset($_GET['type']) ? $_GET['type'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$season = isset($_GET['season']) ? $_GET['season'] : null;
$episode = isset($_GET['episode']) ? $_GET['episode'] : null;

if ($type && $id) {
    // Generate the embed URL
    $embedUrl = getEmbedUrl($type, $id, $season, $episode);
    
    if ($embedUrl) {
        // Display the content in a sandboxed iframe
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Trailer</title>
            <style>
                body, html {
                    margin: 0;
                    padding: 0;
                    width: 100%;
                    height: 100%;
                    overflow: hidden;
                }
                iframe {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    border: none;
                }
            </style>
        </head>
        <body>
            <iframe src='$embedUrl' sandbox='allow-scripts allow-same-origin' frameborder='0' allow='encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
        </body>
        </html>";
    } else {
        echo "Trailer not available.";
    }
} else {
    echo "Invalid parameters.";
}
?>
