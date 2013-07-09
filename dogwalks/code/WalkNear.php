<?php
class WalkNear extends WalkHolder {
}

class WalkNear_Controller extends WalkHolder_Controller {
  /*
   * handles location search result
   */
  public function locations() {
    @$Lat = $_POST['Lat'];
    @$Lng = $_POST['Lng'];
    @$Address = $_POST['Address'];
    @$AddressDefault = $_POST['AddressDefault'];
    //echo $Lat . $Lng . $Address . $AddressDefault;

    if ($Address == $AddressDefault && $Lat && $Lng) { // user used default search address so we can use default latlng
      $this->saveGeoData($Lat, $Lng, $Address);
    } elseif ($Address) { // get co-ords first
      $this->saveGeoData('', '', $Address);
    } else { // redirect to search page
      return $this->redirect('walks/near-you/');
    }

    return array(
        'MenuTitle' => 'Your Location',
        'Content' => ''
      );
  }

  /*
   * function also exists on WalkPage
   */
  public function StaticMapURL() {
    //$markerIcon = Director::BaseURL().'/themes/dogwalks/images/map/marker.png';
    $markerUserIcon = Director::BaseURL().'/themes/dogwalks/images/map/marker_user.png';
    $markerLocalIcon = Director::BaseURL().'/themes/dogwalks/images/map/marker_local.png';

    // TODO remove once live http://imgur.com/delete/OY0UTn3sL9qvlTF http://imgur.com/delete/i5jndl2LpWX6h7g http://imgur.com/delete/r0m0PWR35uzrfhH
    //$markerIcon = 'http://i.imgur.com/QEH0WH7.png';
    $markerUserIcon = 'http://i.imgur.com/cDgBaTU.png';
    $markerLocalIcon = 'http://i.imgur.com/UH3rwuP.png';

    $base = 'https://maps.googleapis.com/maps/api/staticmap?';
    $params = '&visual_refresh=true&size=640x400&scale=2&sensor=true';

    $userLat = $this->UserLat();
    $userLng = $this->UserLng();

    if ($userLat && $userLng) {
      $userLatLng = $userLat.','.$userLng;

      $localWalksArray = $this->LocalWalks();
      if ($localWalksArray) {
        $localWalksLatLngArray = array();
        foreach ($localWalksArray as $walk) {
          $localWalksLatLngArray[] = $walk->Lat.','.$walk->Lng;
        }
        $localWalks = implode('|', $localWalksLatLngArray);

        //if ($walk)       $markersArray['walk'] = 'markers='.urlencode("icon:$markerIcon&chld=walk|$walk");
        if ($localWalks) $markersArray['local'] = 'markers='.urlencode("icon:$markerLocalIcon&chld=local|$localWalks");
        if ($userLatLng) $markersArray['user'] = 'markers='.urlencode("icon:$markerUserIcon&chld=user|$userLatLng");
        $markers = implode('&', $markersArray);

        if ($markers) return $base.$markers.$params;
      }
    }
  }
}
