<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<TileMapService version="1.0.0" services="<?php echo $rootServices ?>">
    <TileMaps>
        <Title>Tile Map Service provided by OpenStreetMap.la</Title>
        <Abstract>Tile Map Service provided by OpenStreetMap.la</Abstract>
        <?php
        foreach ($tileMaps as $tileMap) {

            $srs = $tileMap['srs'];
            $href = $tileMap['href'];
            $title = $tileMap['title'];
            $profile = $tileMap['profile'];

            echo "<TileMap href=\"$href\" srs=\"$srs\" title=\"$title\" profile=\"$profile\"/>";
        }
        ?>
    </TileMaps>
</TileMapService>