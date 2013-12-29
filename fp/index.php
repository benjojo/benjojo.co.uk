<!DOCTYPE html>
<html lang='en'>

<head>
    <title>Benjojo - Ben Cox</title>
    <link rel="stylesheet" href="/stylesheets/main.css" />
    <meta name="description" content="The content dump of Benjojo">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript">
var properties={window:{height:0,width:0},tiles:{count:0,hCount:0,wCount:0}},avatars=[],Flipping=!1,FlipPntr=0;$.getJSON("http://daring.benjojo.co.uk/DataStore/SteamAvatars/GrabLinks.php?x="+$(window).height()/64+"&y="+$(window).width()/64,function(a){avatars=$.merge(avatars,a.img)});Flipping=!0;$(window).bind("resize load",initTiles);
function initTiles(){properties.window.height=$(window).height();properties.window.width=$(window).width();properties.tiles.hCount=Math.ceil(properties.window.height/64);properties.tiles.wCount=Math.ceil(properties.window.width/64);properties.tiles.count=properties.tiles.hCount*properties.tiles.wCount;rearrangeTiles()}function preload(a){$(a).each(function(){$("<img/>")[0].src=this})}
function rearrangeTiles(){var a=$("#tile-grid #tile").size(),a=properties.tiles.count-a;$("#tile-grid").css({height:64*properties.tiles.hCount,width:64*properties.tiles.wCount});0>a?$("#tile-grid #tile:gt("+properties.tiles.count+")").remove():0<a&&addTile(a)}function addTile(a){for(i=1;i<=a;i++)Math.floor(256*Math.random()).toString(16),Math.floor(256*Math.random()).toString(16),Math.floor(256*Math.random()).toString(16),$("#tile-grid").append("<div id='tile' style=''></div>"),avatars.shift()}
jQuery.fn.random=function(){var a=Math.floor(Math.random()*this.length);return jQuery(this[a])};
var flipInterval=setInterval(function(){Flipping&&0!=avatars.length&&($($("#tile-grid #tile")[FlipPntr]).css({background:"url('"+avatars[0]+"')"}),avatars.shift(),FlipPntr++,FlipPntr>properties.tiles.hCount*properties.tiles.wCount&&(avatars=[],Flipping=!1))},50),GetNewAvatars=setInterval(function(){Flipping||($.getJSON("http://daring.benjojo.co.uk/DataStore/SteamAvatars/GrabLinks.php?x="+properties.window.height/64+"&y="+properties.window.width/64,function(a){avatars=$.merge(avatars,a.img)}),FlipPntr=
0,preload(avatars),setInterval(function(){Flipping=!0},3E3))},3E4);
    </script>
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
    if(!file_exists("thumb." . $value)) {
        $svalue = escapeshellcmd($value);
        shell_exec("/usr/bin/convert -thumbnail 200 $svalue thumb.$svalue");
    }
    if(strstr($value, "thumb.")) {
        unset($files[$key]);
    }
}
$i = 0;
$maxperpage = 30;
if(isset($_GET['i']) && is_numeric($_GET['i'])) {
    $i = (int)$_GET['i'] + 30;
}


foreach ($files as $fileno => $filename) {
    if($i + $maxperpage > $fileno && $i - $maxperpage < $fileno) {
        echo("            <a href=\"$filename\"><img src=\"thumb.$filename\" width=\"120px\" height=\"90px\"></a>\n");
    }
}
?>
    <ul class="links">
        <?php
            if(count($files) > $i + 30) {
        ?>
        <a href="/fp/?i=<?php echo($i + $maxperpage);?>"> Next Page </a>
        <?php
        }
        ?>
    </ul>
    </div>
</body>

</html>
