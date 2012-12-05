<?php
echo '<?xml version="1.0" encoding="UTF-8" ?>';

// Define some variables
$host = $result['metadata']['host'];
$searchTerm = htmlspecialchars($result['metadata']['searchTerm']);
$startPage = $result['metadata']['startPage'];
?>
<rss version="2.0" 
     xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:georss="http://www.georss.org/georss">
    <channel>
        <title>OpenStreetMap.la Places</title>
        <link><?php echo "http://$host/places/rss?q=$searchTerm"; ?></link>
        <description>Search results for "<?php echo $searchTerm; ?>" at OpenStreetMap.la</description>
        <opensearch:totalResults><?php echo $result['metadata']['totalResults']; ?></opensearch:totalResults>
        <opensearch:startIndex><?php echo $result['metadata']['startIndex']; ?></opensearch:startIndex>
        <opensearch:itemsPerPage><?php echo $result['metadata']['itemsPerPage']; ?></opensearch:itemsPerPage>
        <atom:link rel="self" href="<?php echo htmlspecialchars($result['metadata']['fullUrl']); ?>" type="application/rss+xml"/>
        <atom:link rel="search" type="application/opensearchdescription+xml"
                   href="<?php echo "http://$host/places.xml"; ?>"/>
        <opensearch:Query role="request"
                          searchTerms="<?php echo $searchTerm; ?>"
                          startPage="<?php echo $startPage ?>" />

        <?php
        foreach ($result['data'] as $item) {
            echo "<item>";
            echo "<title>" . htmlspecialchars($item['name']) . "</title>";
            $link = "http://$host/?mlon=" . $item['lon'] . "&mlat=" . $item['lat'] . "&zoom=14";
            echo "<link>" . htmlspecialchars($link) . "</link>";
            echo "<description>" . $item['feature'] . "</description>";
            echo "<georss:point>" . $item['lat'] . " " . $item['lon'] . "</georss:point>";
            echo "</item>";
        }
        ?>

    </channel>
</rss>