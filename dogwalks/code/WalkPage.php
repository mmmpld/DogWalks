<?php
class WalkPage extends WalkAreaHolder {
  static $icon = "themes/dogwalks/images/icons/news-file.png";

	static $db = array(
    'Leash' => 'Int(0)',
    'Bins' => 'Int(0)',
    'Toilets' => 'Int(0)',
    'Fenced' => 'Int(0)',
    'Water' => 'Int(0)',
    'Pram' => 'Int(0)',
    'Wheelchair' => 'Int(0)',
    'Playground' => 'Int(0)',
    'Cost' => 'Currency',
    'CostDescription' => 'Text',
    'Author' => 'Text',
    'Address' => 'Text',
    'Lat' => 'Decimal(10,6)',
    'Lng' => 'Decimal(10,6)'
  );

	static $many_many = array(
    'Images' => 'Image'
  );
  public static $many_many_extraFields = array(
    'Images' => array('SortOrder' => 'Int')
  );

	public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Info', new DropdownField('Leash',     'Leash',     $this->infoMapLeash(),'0'));
	  $fields->addFieldToTab('Root.Info', new DropdownField('Bins',      'Bins',      $this->infoMap(),     '0'));
		$fields->addFieldToTab('Root.Info', new DropdownField('Toilets',   'Toilets',   $this->infoMap(),     '0'));
		$fields->addFieldToTab('Root.Info', new DropdownField('Fenced',    'Fenced',    $this->infoMap(),     '0'));
    $fields->addFieldToTab('Root.Info', new DropdownField('Water',     'Water',     $this->infoMapWater(),'0'));
    $fields->addFieldToTab('Root.Info', new DropdownField('Pram',      'Pram',      $this->infoMap(),     '0'));
    $fields->addFieldToTab('Root.Info', new DropdownField('Wheelchair','Wheelchair',$this->infoMap(),     '0'));
    $fields->addFieldToTab('Root.Info', new DropdownField('Playground','Playground',$this->infoMap(),     '0'));
		$fields->addFieldToTab('Root.Info', new CurrencyField('Cost'));
    $fields->addFieldToTab('Root.Info', new TextField('CostDescription', 'Cost Description'));
    $fields->addFieldToTab('Root.Info', new TextField('Author'));
    $fields->addFieldToTab('Root.Info', new TextField('Address'));
    $fields->addFieldToTab('Root.Info', new TextField('Lat'));
    $fields->addFieldToTab('Root.Info', new TextField('Lng'));

    $imageField = new SortableUploadField('Images', 'Gallery Images');
    $imageField->setFolderName('walks');
    $fields->addFieldToTab('Root.Images', $imageField);

    return $fields;
  }

  public static function infoMap() {
    return array('Unknown','No','Yes');
  }
  public static function infoMapLeash() {
    return array('Unknown','Dogs not allowed','On-Leash','Off-Leash','Both on-leash and off-leash');
  }
  public static function infoMapWater() {
    return array('Unknown','No','Tap Water','River Water');
  }

  public function WalkInfoText($key, $lookup = NULL) {
    $map = $this->{'infoMap'.$lookup}();
    if ($map[(int)$key]) {
      return $map[(int)$key];
    }
  }
}

class WalkPage_Controller extends WalkAreaHolder_Controller {
  static $allowed_actions = array('edit', 'suggested');
  public function SortedImages() {
    return $this->Images('', 'SortOrder ASC');
  }

  private $walkInfoText = array(
    'Leash' => array('Unknown', 'Dogs are not allowed', 'Dogs allowed on-leash', 'Dogs allowed off-leash', 'Dogs allowed on and off-leash'),
    'Bins' => array('Unknown', 'Bins not provided', 'Bins provided'),
    'Toilets' => array('Unknown', 'No toilet', 'Toilets available'),
    'Fenced' => array('Unknown', 'Not fenced', 'There is a fenced area'),
    'Cost' => array('Free', 'Associated cost'),
    'Water' => array('Unknown', 'Water not provided', 'Tap water available', 'River nearby'),
    'Pram' => array('Unknown', 'Not suitable for pushchairs', 'Suitable for pushchairs'),
    'Wheelchair' => array('Unknown', 'Not suitable for wheelchairs', 'Suitable for wheelchairs'),
    'Playground' => array('Unknown', 'No playground', 'Playground nearby')
  );
  public function WalkInfo() {
    $walkInfo = new ArrayList();

    //if ($this->Cost != 0) $walkInfo->push(new ArrayData(array('Name' => 'Cost', 'Class' => 'Cost', 'Value' => $this->Cost, 'Title' => 'Cost', 'Text' => $this->Cost > 0 ? 'Costs $'.$this->Cost : 0, 'Sort' => 5)));
    if ($this->Leash == 1) {
      $walkInfo->push(new ArrayData(array('Name' => 'No Dogs', 'Class' => 'No-Dogs', 'Value' => $this->Leash, 'Title' => 'Leash requirements', 'Text' => $this->walkInfoText['Leash'][$this->Leash], 'Sort' => 1)));
    } elseif ($this->Leash == 2) {
      $walkInfo->push(new ArrayData(array('Name' => 'On Leash', 'Class' => 'On-Leash', 'Value' => $this->Leash, 'Title' => 'Leash requirements', 'Text' => $this->walkInfoText['Leash'][$this->Leash], 'Sort' => 10)));
    } elseif ($this->Leash == 3) {
      $walkInfo->push(new ArrayData(array('Name' => 'Off Leash', 'Class' => 'Off-Leash', 'Value' => $this->Leash, 'Title' => 'Leash requirements', 'Text' => $this->walkInfoText['Leash'][$this->Leash], 'Sort' => 11)));
    } elseif ($this->Leash == 4) {
      $walkInfo->push(new ArrayData(array('Name' => 'On Leash', 'Class' => 'On-Leash', 'Value' => $this->Leash, 'Title' => 'Leash requirements', 'Text' => $this->walkInfoText['Leash'][2], 'Sort' => 10)));
      $walkInfo->push(new ArrayData(array('Name' => 'Off Leash', 'Class' => 'Off-Leash', 'Value' => $this->Leash, 'Title' => 'Leash requirements', 'Text' => $this->walkInfoText['Leash'][3], 'Sort' => 11)));
    }
    if ($this->Bins != 0) $walkInfo->push(new ArrayData(array('Name' => 'Bins', 'Class' => 'Bins', 'Value' => $this->Bins, 'Title' => 'Rubbish bins', 'Text' => $this->walkInfoText['Bins'][$this->Bins], 'Sort' => 20)));
    if ($this->Toilets != 0) $walkInfo->push(new ArrayData(array('Name' => 'Toilets', 'Class' => 'Toilets', 'Value' => $this->Toilets, 'Title' => 'Toilets', 'Text' => $this->walkInfoText['Toilets'][$this->Toilets], 'Sort' => 30)));
    if ($this->Fenced != 0) $walkInfo->push(new ArrayData(array('Name' => 'Fenced', 'Class' => 'Fenced', 'Value' => $this->Fenced, 'Title' => 'Fenced area', 'Text' => $this->walkInfoText['Fenced'][$this->Fenced], 'Sort' => 40)));
    if ($this->Water == 1) {
      $walkInfo->push(new ArrayData(array('Name' => 'Tap Water', 'Class' => 'Tap-Water', 'Value' => $this->Water, 'Title' => 'Water Availability', 'Text' => $this->walkInfoText['Water'][$this->Water], 'Sort' => 50)));
    } elseif ($this->Water == 2) {
      $walkInfo->push(new ArrayData(array('Name' => 'Tap Water', 'Class' => 'Tap-Water', 'Value' => $this->Water, 'Title' => 'Water Availability', 'Text' => $this->walkInfoText['Water'][$this->Water], 'Sort' => 51)));
    } elseif ($this->Water == 3) {
      $walkInfo->push(new ArrayData(array('Name' => 'River Water', 'Class' => 'River-Water', 'Value' => $this->Water, 'Title' => 'Water Availability', 'Text' => $this->walkInfoText['Water'][$this->Water], 'Sort' => 52)));
    }
    if ($this->Pram != 0) $walkInfo->push(new ArrayData(array('Name' => 'Pushchair', 'Class' => 'Pushchair', 'Value' => $this->Pram, 'Title' => 'Pushchair access', 'Text' => $this->walkInfoText['Pram'][$this->Pram], 'Sort' => 60)));
    if ($this->Wheelchair != 0) $walkInfo->push(new ArrayData(array('Name' => 'Wheelchair', 'Class' => 'Wheelchair', 'Value' => $this->Wheelchair, 'Title' => 'Wheelchair access', 'Text' => $this->walkInfoText['Wheelchair'][$this->Wheelchair], 'Sort' => 70)));
    if ($this->Playground != 0) $walkInfo->push(new ArrayData(array('Name' => 'Playground', 'Class' => 'Playground', 'Value' => $this->Playground, 'Title' => 'Playground', 'Text' => $this->walkInfoText['Playground'][$this->Playground], 'Sort' => 80)));

    // move negative results to bottom
    $walkInfo->sort(array('Value' => 'DESC', 'Sort' => 'ASC'));

    return $walkInfo;
  }

  public function edit() {
    return $this->renderWith(array('WalkPageEdit','WalkPage','Page'));
  }
  public function suggested() {
    $form = new WalkSuggestForm($this, 'suggested');
    $form->loadDataFrom($this);
    return $form;
  }
  public function Breadcrumbs() {
    $Breadcrumbs = parent::Breadcrumbs();
    $path = "$_SERVER[REQUEST_URI]";
    $tokens = explode('/', $path);
    if (in_array('edit', $tokens)) {
      $breadcrumbs_delimiter = '&raquo;';
      $Parts = explode($breadcrumbs_delimiter, $Breadcrumbs);
      $link = $this->Link();
      end($Parts);
      $key = key($Parts);
      $Parts[$key] = ' <a href="'.$link.'">'.trim($Parts[$key]).'</a> ';
      $Parts[] = ' Edit ';
      $Breadcrumbs = implode($breadcrumbs_delimiter, $Parts);
    }
    return $Breadcrumbs;
  }

  /*
   * function also exists on WalkNear
   */
  public function StaticMapURL() {
    $base = 'https://maps.googleapis.com/maps/api/staticmap?markers=';
    $params = '&visual_refresh=true&size=640x400&scale=2&zoom=15&sensor=true';
    $markerIcon = Director::BaseURL().'/themes/dogwalks/images/map/marker.png';
    $markerUserIcon = Director::BaseURL().'/themes/dogwalks/images/map/marker_user.png';
    $markerLocalIcon = Director::BaseURL().'/themes/dogwalks/images/map/marker_local.png';

    // TODO remove
    $markerIcon = 'http://i.imgur.com/QEH0WH7.png';
    $markerUserIcon = 'http://i.imgur.com/cDgBaTU.png';
    $markerLocalIcon = 'http://i.imgur.com/UH3rwuP.png';

    $markerLatLng = $this->Lat.','.$this->Lng;

    return $base.urlencode('icon:'.$markerIcon.'&chld=walk|'.$markerLatLng).$params;
  }
}
