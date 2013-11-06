<?php
class WalkSuggestPage extends Page {
  static $icon = "themes/dogwalks/images/icons/walk-file.png";
}


class WalkSuggestPage_Controller extends Page_Controller {
  static $allowed_actions = array('suggested');

  // Not sure if this is needed anymore as the ajax/form split is handled by WalkPage.php
  public function suggested() {
    $fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
    echo $fn;
    if ($fn) { // ajax upload
      $uploadDir = Director::baseFolder() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'walks' . DIRECTORY_SEPARATOR . 'submitted' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;

      $file = file_get_contents('php://input');
      if (!$file && $_FILES) {
        if ($_FILES['UploadedFiles']) {
          if ($_FILES['UploadedFiles']['tmp_name']) {
            if ($_FILES['UploadedFiles']['tmp_name'][0]) {
              $file = file_get_contents($_FILES['UploadedFiles']['tmp_name'][0]);
              file_put_contents($uploadDir . $fn, $file);
              echo "$fn uploaded";
              exit();
            } else {
              echo 'index zero not found';
            }
          } else {
            echo 'tmp_name not found';
          }
        } else {
          echo 'UploadedFiles not found';
        }
      }
      file_put_contents($uploadDir . $fn, $file);
      echo "$fn uploaded";
      exit();
    } else { // normal form handling
      return new WalkSuggestForm($this, 'suggested');
    }
  }
}
