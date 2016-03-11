<?php
function get_content($url)
{
   $ch = curl_init();

   curl_setopt ($ch, CURLOPT_URL, $url);
   curl_setopt ($ch, CURLOPT_HEADER, 0);

   ob_start();

   curl_exec ($ch);
   curl_close ($ch);
   $string = ob_get_contents();

   ob_end_clean();
  
   return $string;   
}

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n";
echo "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\"\n";
echo " \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n";
echo "<rss version=\"0.91\">\n\n";
echo "<channel>\n";

echo "<title>NCAA Scores</title>\n";
echo "<link>http://www.ncaasports.com/</link>\n";
echo "<description>NCAA Scores</description>\n";
echo "<language>en-us</language>\n";
echo "<image>\n";
echo " <title>NBA Scores</title>\n";
echo " <url>http://www.mpiii.com/scores/ncaa.gif</url>\n";
echo " <link>http://www.ncaa.com</link>\n";
echo "</image>\n";
echo "<webMaster>info@ncaa.com</webMaster>\n";

$content = file_get_contents("http://sports.espn.go.com/ncb/bottomline/scores");

$content_array=explode("&", $content);

$scorearray = array();
$i=0;
foreach($content_array as $content) {
    if (strpos($content, "_left")) {
        $equalpos = strpos($content, "=");
        $end = strlen($content);
        $title = substr($content, ($equalpos+1), $end);
        $title = str_replace("^", " vs. ", $title);
        $title = str_replace("%20", " ", $title);
        $scorearray[$i]["title"] = $title;

    }
    if (strpos($content, "_url")) {
        $equalpos = strpos($content, "=");
        $end = strlen($content);
        $url = substr($content, ($equalpos+1), $end);
        $url = str_replace("^", "", $url);
        $url = str_replace("%20", " ", $url);
        $scorearray[$i]["url"] = $url;
                $i++;

    }
}
foreach($scorearray as $score) {
    echo "<item>\n";
    echo "<title>".$score["title"]."</title>\n";
    echo "<link>".$score["url"]."</link>\n";
    echo "</item>\n";
}

echo "</channel>\n";
echo "</rss>\n";
?>