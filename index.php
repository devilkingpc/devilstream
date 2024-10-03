<?php
// Function to generate the embed URL based on the type and ID
function getEmbedUrl($type, $id, $season = null, $episode = null) {
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

// Variables to store user input and results
$type = isset($_GET['type']) ? $_GET['type'] : (isset($_POST['type']) ? $_POST['type'] : null);
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);
$season = isset($_GET['season']) ? $_GET['season'] : (isset($_POST['season']) ? $_POST['season'] : null);
$episode = isset($_GET['episode']) ? $_GET['episode'] : (isset($_POST['episode']) ? $_POST['episode'] : null);
$embedUrl = null;

if ($type && $id) {
    // Generate the embed URL
    $embedUrl = getEmbedUrl($type, $id, $season, $episode);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trailer Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: 0;
            pointer-events: auto;
        }
        form {
            margin-bottom: 20px;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        function toggleSeasonEpisodeFields() {
            var type = document.getElementById('type').value;
            var seasonEpisodeFields = document.getElementById('seasonEpisodeFields');
            if (type === 'tv') {
                seasonEpisodeFields.classList.remove('hidden');
            } else {
                seasonEpisodeFields.classList.add('hidden');
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const iframe = document.querySelector("iframe");
            iframe.contentWindow.addEventListener("click", function (event) {
                const url = event.target.closest('a')?.href;
                if (url && !url.includes("player.autoembed.cc")) {
                    event.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Trailer Preview</h1>
        <form method="POST" action="">
            <label for="type">Type:</label>
            <select name="type" id="type" onchange="toggleSeasonEpisodeFields()">
                <option value="movie" <?php echo ($type == 'movie') ? 'selected' : ''; ?>>Movie</option>
                <option value="tv" <?php echo ($type == 'tv') ? 'selected' : ''; ?>>TV Show</option>
            </select>
            <br><br>
            <label for="id">ID:</label>
            <input type="text" name="id" id="id" value="<?php echo htmlspecialchars($id); ?>" required>
            <br><br>
            <div id="seasonEpisodeFields" class="<?php echo ($type == 'tv') ? '' : 'hidden'; ?>">
                <label for="season">Season (for TV shows):</label>
                <input type="number" name="season" id="season" value="<?php echo htmlspecialchars($season); ?>">
                <br><br>
                <label for="episode">Episode (for TV shows):</label>
                <input type="number" name="episode" id="episode" value="<?php echo htmlspecialchars($episode); ?>">
                <br><br>
            </div>
            <input type="submit" value="Preview Trailer">
        </form>

        <?php if ($embedUrl): ?>
            <h2>Trailer Preview:</h2>
            <iframe src="<?php echo htmlspecialchars($embedUrl); ?>" allowfullscreen></iframe>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p>Trailer not available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
