<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName>OpenStreetMap.la Places</ShortName>
    <Description>Search places in Laos using OpenStreetMap.la</Description>
    <Tags>openstreetmap places laos</Tags>
    <Url type="application/rss+xml"
         template="http://<?php echo $host; ?>/places/rss?q={searchTerms}&amp;p={startPage?}"/>
    <Url type="text/html"
         template="http://<?php echo $host; ?>/?q={searchTerms}"/>
    <Url type="application/x-suggestions+json"
         template="http://<?php echo $host; ?>/places/suggest?q={searchTerms}&amp;p={startPage?}"/>
    <LongName>OpenStreetMap.la places search</LongName>
    <Image height="16" width="16" type="image/vnd.microsoft.icon">http://<?php echo $host; ?>/favicon.ico</Image>
    <Query role="example" searchTerms="cat" />
    <Attribution>Data Copyright by OpenStreetMap contributors</Attribution>
    <Language>en-us</Language>
    <OutputEncoding>UTF-8</OutputEncoding>
    <InputEncoding>UTF-8</InputEncoding>
</OpenSearchDescription>