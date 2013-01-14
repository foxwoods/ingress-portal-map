<?php
error_reporting(0);

$offset_file = 'gps2gmapcn-offset.json';
$offset_json = file_get_contents($offset_file);
$offset_data = json_decode($offset_json, true);

/**
 * 修正偏移
 *
 * 使用了这个项目的数据和算法：
 * https://github.com/brightman/lbs
 */
function gps2gmapcn($location, $offset_data) {
    list($lat, $lng) = $location;
    $base_lat = number_format($lat, 1, '.', '');
    $base_lng = number_format($lng, 1, '.', '');
    $key = $base_lat . ',' . $base_lng;
    if(array_key_exists($key, $offset_data)) {
        list($offset_lat, $offset_lng) = $offset_data[$key];
        $new_lat = round($lat + $offset_lat, 6);
        $new_lng = round($lng + $offset_lng, 6);
        $new_location = array($new_lat, $new_lng);
        return $new_location;
    } else {
        return $location;
    }
}
?>
<?php
header('Content-Type: text/xml');
?>
<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<kml xmlns="http://earth.google.com/kml/2.2">
<Document>
  <name>Ingress Portals</name>
  <description><![CDATA[]]></description>
<?php
// ** chage the following variable to your Firebase JSON URL **
$portals_url = 'https://my-portals.firebaseio.com/portals.json';

$portals_json = file_get_contents($portals_url);
$portals = json_decode($portals_json, true);
foreach($portals as $guid => $portal) {
    $location = gps2gmapcn(array($portal['locationE6']['latE6']/1E6, $portal['locationE6']['lngE6']/1E6), $offset_data);
?>
  <Placemark>
    <name><?= htmlspecialchars($portal['portalV2']['descriptiveText']['TITLE'], ENT_COMPAT, 'UTF-8') ?></name>
    <description><![CDATA[<a href="http://www.ingress.com/intel?latE6=<?= $portal['locationE6']['latE6'] ?>&lngE6=<?= $portal['locationE6']['lngE6'] ?>&z=19" target="_blank">Intel Map</a> <br /> <img src="<?= $portal['imageByUrl']['imageUrl'] ?>" />]]></description>
    <Point>
      <coordinates><?= $location[1] ?>,<?= $location[0] ?>,0.000000</coordinates>
    </Point>
  </Placemark>
<?php
}
?>
</Document>
</kml>
