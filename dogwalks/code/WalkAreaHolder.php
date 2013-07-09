<?php
class WalkAreaHolder extends WalkHolder {
  static $icon = "themes/dogwalks/images/icons/walk-file.png";
  static $allowed_children = array('WalkPage', 'WalkAreaHolder');
}


class WalkAreaHolder_Controller extends WalkHolder_Controller {
}
