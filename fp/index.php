<!DOCTYPE html>
<html lang='en'>

<head>
    <title>Benjojo - Ben Cox</title>
    <link rel="stylesheet" href="/stylesheets/main.css" />
    <meta name="description" content="The content dump of Benjojo">
</head>

<body>
    <div class="background" id="tile-grid">
    </div>
    <div class="background-overlay"></div>
    <div class="listbody" style="top:200px">
    <div class="title2"></div>
<?php
$files = glob("{*.jpg,*.png}",GLOB_BRACE);
usort($files, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
foreach ($files as $key => $value) {
    if(strstr($value, "thumb.")) {
        unset($files[$key]);
    }
}
$i = 0;
$maxperpage = 30;
if(isset($_GET['i']) && is_numeric($_GET['i'])) {
    $i = (int)$_GET['i'];
}


foreach ($files as $fileno => $filename) {
    if($i + $maxperpage > $fileno && $i - $maxperpage < $fileno)
    echo("            <a href=\"$filename\"><img src=\"thumb.$filename\" width=\"120px\" height=\"90px\"></a>\n");
}
?>
    <ul class="links">
        <a href="/fp/?i=<?php echo($i + $maxperpage);?>"> Next Page </a>
    </ul>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript">
    //$(document).ready(function(){
    var properties = {
        "window": {
            "height": 0,
            "width": 0
        },
        "tiles": {
            "count": 0,
            "hCount": 0,
            "wCount": 0
        }
    };
    var avatars = [];
    var Flipping = false;
    var FlipPntr = 0;

    $.getJSON('http://daring.benjojo.co.uk/DataStore/SteamAvatars/GrabLinks.php?x=' + $(window).height() / 64 + '&y=' + $(window).width() / 64, function(data) {
        avatars = $.merge(avatars, data.img);
    });
    Flipping = true;
    $(window).bind("resize load", initTiles);

    function initTiles() {
        properties.window.height = $(window).height();
        properties.window.width = $(window).width();
        properties.tiles.hCount = Math.ceil(properties.window.height / 64);
        properties.tiles.wCount = Math.ceil(properties.window.width / 64);
        properties.tiles.count = properties.tiles.hCount * properties.tiles.wCount;
        rearrangeTiles();
    }

    function preload(arrayOfImages) {
        $(arrayOfImages).each(function() {
            $('<img/>')[0].src = this;
        });
    }

    function rearrangeTiles() {
        var oldCount = $("#tile-grid #tile").size();
        var deltaCount = properties.tiles.count - oldCount;

        $("#tile-grid").css({
            "height": properties.tiles.hCount * 64,
            "width": properties.tiles.wCount * 64
        });

        if (deltaCount < 0) {
            $("#tile-grid #tile:gt(" + properties.tiles.count + ")").remove();
        } else if (deltaCount > 0) {
            addTile(deltaCount);
        }
    }

    function addTile(count) {
        for (i = 1; i <= count; i++) {
            var hue = '#' + Math.floor(Math.random() * 256).toString(16) + Math.floor(Math.random() * 256).toString(16) + Math.floor(Math.random() * 256).toString(16);
            $("#tile-grid").append("<div id='tile' style=''></div>");
            avatars.shift();
        }
    }

    jQuery.fn.random = function() {
        var randomIndex = Math.floor(Math.random() * this.length);
        return jQuery(this[randomIndex]);
    };

    var flipInterval = setInterval(function() {
        if (Flipping) {
            if (avatars.length != 0) {
                $($("#tile-grid #tile")[FlipPntr]).css({
                    "background": "url('" + avatars[0] + "')"
                });
                avatars.shift();
                FlipPntr++;
                if (FlipPntr > (properties.tiles.hCount * properties.tiles.wCount)) {
                    avatars = [];
                    Flipping = false;
                }
            }
        }
    }, 50);

    var GetNewAvatars = setInterval(function() {
        if (!Flipping) {
            $.getJSON('http://daring.benjojo.co.uk/DataStore/SteamAvatars/GrabLinks.php?x=' + properties.window.height / 64 + '&y=' + properties.window.width / 64, function(data) {
                avatars = $.merge(avatars, data.img);
            });
            FlipPntr = 0;
            preload(avatars);
            setInterval(function() {
                Flipping = true;
            }, 3000);
        }
    }, 30000);

    //});
    </script>
    <!-- Testing the auto update system -->
</body>

</html>