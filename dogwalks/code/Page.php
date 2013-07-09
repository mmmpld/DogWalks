<?php
class Page extends SiteTree {
	public static $db = array(
	);

	public static $has_one = array(
	);

    /**
   * Return a breadcrumb trail to this page. Excludes "hidden" pages
   * (with ShowInMenus=0).
   *
   * @param int $maxDepth The maximum depth to traverse.
   * @param boolean $unlinked Do not make page names links
   * @param string $stopAtPageType ClassName of a page to stop the upwards traversal.
   * @param boolean $showHidden Include pages marked with the attribute ShowInMenus = 0
   * @return string The breadcrumb trail.
   */
  public function Breadcrumbs($maxDepth = 20, $unlinked = false, $stopAtPageType = false, $showHidden = false) {
    $page = $this;
    $pages = array();

    while(
      $page
      && (!$maxDepth || count($pages) < $maxDepth)
      && (!$stopAtPageType || $page->ClassName != $stopAtPageType)
    ) {
      if($showHidden || $page->ShowInMenus || ($page->ID == $this->ID)) {
        $pages[] = $page;
      }

      $page = $page->Parent;
    }

    $template = new SSViewer('BreadcrumbsTemplate');

    return $template->process($this->customise(new ArrayData(array(
      'Pages' => new ArrayList(array_reverse($pages))
    ))));
  }

}
class Page_Controller extends ContentController {
	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	public static $allowed_actions = array (
	);

	public function init() {
		parent::init();
	}

  public function ListChildWalkAreas($parentID = NULL) { // accepts parentID for use outside walk hierarchy
    if (!$parentID) {$parentID = $this->ID;}
    return SiteTree::get()->filter(array('ClassName' => 'WalkAreaHolder', 'ParentID' => $parentID));
  }
  public function ListChildWalkPages() {
    return SiteTree::get()->filter(array('ClassName' => 'WalkPage', 'ParentID' => $this->ID));
  }
  public function ListWalkPages() {
    $areas = SiteTree::get()->filter(array('ClassName' => 'WalkAreaHolder', 'ParentID' => $this->ID));
    $areaIDs = $areas->getIDList();
    $areaIDs[$this->ID] = $this->ID;
    asort($areaIDs);
    $walks = SiteTree::get()->filter(array('ClassName' => 'WalkPage', 'ParentID' => $areaIDs));
    return $walks;
  }

  public function saveGeoData($Lat, $Lng, $Address) {
    Cookie::set('Lat', $Lat);
    Cookie::set('Lng', $Lng);
    Cookie::set('Address', $Address);
  }

  public function GetRequestOrCookie($string) {
    @$request = $_POST[$string];
    if ($request) {
      return $request;
    } else {
      return Cookie::get($string);
    }
  }
  public function UserLat() {
    return Cookie::get('Lat');
  }
  public function UserLng() {
    return Cookie::get('Lng');
  }
  public function UserAddress() {
    return $this->GetRequestOrCookie('Address');
  }

  public function RandomNumber($min = 0, $max = 10) {
    return rand($min, $max);
  }

  // used by form result page to disable rendering of page sections
  public function FormReturn() {
    return FALSE;
  }

}
