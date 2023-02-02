# book-list
 A lightweight php-based list solution for your media

# Requirements
- A webserver (with write access to the cloned directory)
- PHP 7.0 or higher

# Installation
1. Clone the repository to your webserver
2. Acecss the directory via your web browser
3. done

# Usage

## Adding a new list
1. If you are inside a list, click the `back` button (<-) in the bottom left corner
2. Enter the name of the new list in the input field (you cannot use a name that is already in use)
3. Press the `go` button (->) to create the new list
    <hr>
## Deleting a list
1. If you are inside a list, click the `delete` button (trashcan) in the bottom right corner
    <hr>
## Adding a new entry
1. Choose the type of media you want to add from the dropdown menu
2. Fill out the form
3. Press the `+` button to add the entry
    <hr>
## Editing an entry
1. Click on the entry or hover over the entry and click the `edit` button
2. Edit the entry in the form
3. Press the `save` button (checkmark) to save the changes (or the `cancel` button (x) to discard the changes)
    <hr>
## Deleting an entry
1. Click on the entry or hover over the entry and click the `delete` button
    <hr>
## Searching for an entry
1. Enter the search term in the search field in the top left corner
2. Press the `search` button (magnifying glass) to search for the term
    <hr>
## Sorting the list
1. Select the sorting method from the dropdown menu in the top left corner (below the search field)
2. If you choose `custom`, you can click on or hover over an entry and then click the `move` buttons (< >) to move the entry to a different position
    <hr>
## Adding a list to another website via an iframe
1. On the website you want to add the list to, create an iframe element. It should look like this:
    ```html
    <iframe src="https://your-domain.com/path/to/book-list/actions/to-view?dir=<list-name>" frameborder="0" allowtransparency="true"></iframe>
    ```
    - `<list-name>`: The name of the list you want to add.
2. Set the width and height of the iframe to the desired size.
    <hr>
## Adding a single entry to another website via an iframe
1. On the website you want to add the entry to, create an iframe element. It should look like this:
    ```html
    <iframe src="https://your-domain.com/path/to/book-list/actions/single-view?type=<media-type>&title=<title>" frameborder="0" allowtransparency="true"></iframe>
    ```
    - `<media-type>`: The type of the media you want to add. For example, if you want to add a book, you would use `book` as the media type.
    - `<title>`: The title of the media you want to add.
2. Set the width and height of the iframe to the desired size.

# Configuration
## Changing the storage location
1. Open the [config.json](config.json) file.
2. Change the `storage-folder` variable to the path of the directory you want to use for storage.
3. Create the directory if it does not exist.
4. Make sure the webserver has write access to the directory.
## Changing the color theme
1. Open the [css/main-page.css](css/main-page.css) file.
2. Set the color variables in the `:root` selector to the colors you want to use.

    `--color-main` Used for the menu

    `--color-secondary` Used for buttons

    `--color-background` Used for the background

    `--color-accent` Used for the hover and editing effect

    `--color-text` Used for text

    `--color-text-secondary` Used for less important text

    `--color-shadow` Used for shadows

## Adding new types of media
1. Create a new entry in the `types` list in [config.json](config.json). It should look like this:
    ```json
    "<type-name>": {
        "icon": "<icon-name>",
        "artist-name": "<artist-name>",
        "category": "<category>",
        "key-file": "<key-file>",
        "layout": "<layout>"
    },
    ```
    - `<type-name>`: The name of the type. This is what you will use to identify the type later on.
    For example, if you want to add a new type called "book", you would use `book` as the type name.
    - `<icon-name>`: The name of the icon that will be used for this type. We use [Google's Material Icons](https://material.io/resources/icons/?style=baseline) for this. You can find the name of the icon you want there. For example, if you want to use the `Music Note` icon, you would use that as the icon name.
    - `<artist-name>`: The name of the artist. This is what will be displayed in the form when adding a new entry. For example, if you want to add a new type called `movie`, you would use `Director` as the artist name.
    - `<category>`: The category of the media. This determines where the media will be displayed in the dropdown menu. This should be a number between 0 and 3. The categories are as follows:
        - 0: Reading
        - 1: Watching
        - 2: Listening
        - 3: Playing
    - `<key-file>`: The name of the file that will contain your api-key information. Before letting the user add an entry the site will check if the file exists and contains some information. If it does not, the type will not be displayed in the dropdown menu. The files are located in the [data](data) directory. For example, if you want to add a new type called `song`, you could create a file called `spotify_key.txt` in the [data](data) directory and use `spotify_key` as the key file.
    If you do not need an api key, you can leave this empty.
    - `<layout>`: The layout of the entry. This determines how the entry will be displayed in the list. This should be one of the following:
        - "" (empty): The entry will be displayed in a book like format.
        - "squared": The entry will be displayed in a squared format.
        - "landscape": The entry will be displayed in a landscape format.
2. Create a `get_<type-name>_data.php` file in the [actions/apis](actions/apis/) directory.
3. Write a `get_data` function.
It should look like this:
```php
<?php
function get_data($data_reset, $title, $author, $image, $length, $link)
{

    $key = file_get_contents("../data/<key-file>.txt");

    /// Authorize at the API Service

    //get info from Service
    $url = "https://api.service.com/v1/search?q=" . urlencode($title) . ($author != null && $author != "" ? urlencode(" " . $author) : "");
    $service_search_json = file_get_contents($url);
    $service_search = json_decode($service_search_json, true);

    // did we get results?
    if (count($service_search["albums"]["items"]) == 0) {
        if ($data_reset) {
            $author = "Unknown";
            $image = "none";
            $length = "Unknown";
        }
    } else {
        if ($author == null || $author == "") {
            $author = $service_search[...]["artist"];
        }

        if ($image == null || $image == "") {
            $image = $service_search[...]["imge_url"];
        }

        if ($length == null || $length == "") {
            // Number of tracks
            $length = $service_search[...]["time_ammount"];
        }
    }

    return array($author, $image, $length, $link);
}
?>
```

The following types already exist and use the following apis:
- book (google books)
- movie (omdb)
- series (omdb)
- video (youtube)
- song (spotify)
- album (spotify)
- game (rawg)

The youtube, spotify and rawg apis require an api key. You can get one for free at the following links:
- [youtube](https://developers.google.com/youtube/v3/getting-started)
- [spotify](https://developer.spotify.com/documentation/web-api/)
- [rawg](https://rawg.io/apidocs)

The api keys should be stored in the [data](data) directory. The file names should be the same as the key-file in the [config.json](config.json) file.
They should be stored in the following format:

`youtube_key.tyt`
```
<key>
```

`spotify_cid_cis.txt`
```
<client-id>
<client-secret>
```

`rawg_key.txt`
```
<key>
```

# Contributing
If you want to contribute, feel free to open a pull request.