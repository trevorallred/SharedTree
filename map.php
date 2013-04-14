<?
require_once("config.php");
require_once("inc/main.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<? echo $MAP_KEY ?>"
      type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[
    var map = null;
    var geocoder = null;
    var locs = Array();
    locs[0] = new Array("Irvine, CA 92604", null, null);
    locs[1] = new Array("Port Neches, Texas", null, null);

    function load() {
      if (GBrowserIsCompatible()) {
        geocoder = new GClientGeocoder();
        map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(37, -102), 4);
        if (geocoder) 
        {
          for (i = 0;i < locs.length;i++)
          {
            geocoder.getLatLng(
              locs[i][0],
              function(point) {
                if (!point) {
                  alert(address + " not found");
                } else {
                  var marker = new GMarker(point);
                  map.addOverlay(marker);
                  locs[i][1] = point.x;
                  locs[i][2] = point.y;
                }
              }
            );
          } //for
	} //if
      }
    } //load

    //]]>
    </script>
  </head>
  <body onunload="GUnload()">
    <div id="map" style="width: 500px; height: 400px"></div>
    <script type="text/javascript">load();</script>
  </body>
</html>
