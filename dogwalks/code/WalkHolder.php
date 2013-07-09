<?php
class WalkHolder extends Page {
  static $icon = "themes/dogwalks/images/icons/walk-file.png";
  static $allowed_children = array('WalkAreaHolder', 'WalkSuggestPage', 'WalkNear');
}

class WalkHolder_Controller extends Page_Controller {
  /*
   * Get walks near the current area
   */
  public function getLocalWalks($Radius = NULL) { // very roughly 0.1 = 10Km
    if ($Radius == NULL) {$Radius = 0.2;}

    // walk location
    if ($this->Class == 'WalkPage') {
      $LatThis = $this->Lat;
      $LngThis = $this->Lng;
    } else {
      @$Address = $_POST['Address'];
      @$AddressDefault = $_POST['AddressDefault'];

      //if (!$LatThis || !$LngThis) {
      if ($Address) {
        if ($Address == $AddressDefault) { // user location from form only if address matches default
          @$LatThis = $_POST['Lat'];
          @$LngThis = $_POST['Lng'];
        } else {
          /*
           * user has set a new address
           * so we are going to have to abort
           * and wait for js to request
           */
          return FALSE;
        }
      } else { // user location from cookies
        // ( !$LatThis ? $LatThis = Cookie::get('Lat') : '' );
        // ( !$LngThis ? $LngThis = Cookie::get('Lng') : '' );
        $LatThis = Cookie::get('Lat');
        $LngThis = Cookie::get('Lng');
      }
      //}
    }

    if (!$LatThis || !$LngThis) {
      return FALSE;
    }

    $LatMax = $LatThis + $Radius;
    $LngMax = $LngThis + $Radius;
    $LatMin = $LatThis - $Radius;
    $LngMin = $LngThis - $Radius;

    // get all areas inside a box
    $sqlQuery = new SQLQuery();
    $sqlQuery->setFrom('WalkPage');
    $sqlQuery->setSelect('Lat, Lng, Title, URLSegment, WalkPage.ID');
    $sqlQuery->addInnerJoin('SiteTree_Live','"WalkPage"."ID" = "SiteTree_Live"."ID"');
    $sqlQuery->addWhere("WalkPage.ID <> $this->ID");
    $sqlQuery->addWhere('Lat <> 0');
    $sqlQuery->addWhere('Lng <> 0');
    $sqlQuery->addWhere("Lat > $LatMin");
    $sqlQuery->addWhere("Lng > $LngMin");
    $sqlQuery->addWhere("Lat < $LatMax");
    $sqlQuery->addWhere("Lng < $LngMax");
    $result = $sqlQuery->execute();

    // calculate distance
    $localWalks = array();
    foreach($result as $row) {
      //echo '<pre>'; print_r($row); echo '</pre>';
      $Lat = $row['Lat'];
      $Lng = $row['Lng'];
      $title = $row['Title'];
      $ID = $row['ID'];
      $url = $row['ID'] ? DataObject::get_by_id("SiteTree", $row['ID'])->Link() : NULL ;
      $distance = $this->getDistance($LatThis, $LngThis, $Lat, $Lng);
      $localWalks[] = array('Lat' => $Lat, 'Lng' => $Lng, 'Name' => $title, 'URL' => $url, 'CenterOn' => FALSE, 'Distance' => $distance);
    }
    usort($localWalks, array($this, 'sortByDistance'));
    return $localWalks;
  }

  private function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
    $earth_radius = 6371;  // In the unit you want the result in. (Km)
    $dLat = deg2rad($latitude2 - $latitude1);
    $dLon = deg2rad($longitude2 - $longitude1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;
    return $d;
  }

  private function sortByDistance($a, $b) {
    return $a['Distance'] - $b['Distance'];
  }

  public function LocalWalks() {
    $localWalks = $this->getLocalWalks();
    $localWalksList = new ArrayList();
    if ($localWalks) {
      foreach ($localWalks as $walk) {
        $arrayData = new ArrayData($walk);
        $localWalksList->add($arrayData);
      }
    }
    return $localWalksList;
  }

  public function LocalWalksJSON() {
    return json_encode($this->getLocalWalks());
  }

}