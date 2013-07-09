<?php
class WalkPageEditForm extends Form {
  public function __construct($controller, $name) {
    $imageField = new FileField('Walk-Image');
    $imageField->getValidator()->setAllowedExtensions(array('jpg', 'gif', 'png'));
    $imageField->getValidator()->setAllowedMaxFileSize($this->GetMaxUpload()*1024*1024);
    $fields = new FieldList(
      new TextField('Title'),
      new TextareaField('Content'),
      $imageField,
      new TextField('Address'),
      new HiddenField('Lat'),
      new HiddenField('Lng'),

      new DropdownField('Leash','Leash',WalkPage::infoMapLeash(),'0'),
      new DropdownField('Bins','Bins',WalkPage::infoMap(),'0'),
      new DropdownField('Toilets','Toilets',WalkPage::infoMap(),'0'),
      new DropdownField('Fenced','Fenced',WalkPage::infoMap(),'0'),
      new DropdownField('Water','Water',WalkPage::infoMapWater(),'0'),
      new DropdownField('Pram','Pram',WalkPage::infoMap(),'0'),
      new DropdownField('Wheelchair','Wheelchair',WalkPage::infoMap(),'0'),

      new CurrencyField('Cost'),
      new TextField('Cost-Description'),

      new TextField('Name'),
      new EmailField('Email'),
      new TextareaField('Message')
    );

    $actions = new FieldList(new FormAction('submitwalk', 'Submit'));
    $validator = new SuggestValidator();

    parent::__construct($controller, $name, $fields, $actions, $validator);


  }

  public function forTemplate() {
    return $this->renderWith(array($this->class, 'Form'));
  }

  public function submitwalk($data, $form) {
    /*
     * Handles html form submit only
     * Multiple image upload handled by WalkSuggestPage.php
     */
    $dataPrint = '<pre>'.print_r($data, TRUE).'</pre>';

    $email = new Email();

    $email->setTo('richardmolloy+dogwalks@gmail.com'); // TODO
    $email->setFrom($data['Email']);
    $email->setSubject("Walk suggestion from {$data["Name"]}");

    //make draft
    $page = new WalkPage;
    $page->ParentID = 9; // Suggest a walk
    $page->ClassName = 'WalkPage';

    $page->Title = $data['Walk-Name'];
    $page->Content = $data['Walk-Description'];

    $page->Author = $data['Name'];
    $page->Email = $data['Email'];
    $page->Leash = $data['Leash'];
    $page->Bins = $data['Bins'];
    $page->Toilets = $data['Toilets'];
    $page->Fenced = $data['Fenced'];
    $page->Pram = $data['Pram'];
    $page->Wheelchair = $data['Wheelchair'];
    $page->Cost = $data['Cost'];
    $page->CostDescription = $data['Cost-Description'];
    $page->Water = $data['Water'];
    $page->Address = $data['Address'];
    $page->Lat = $data['Lat'];
    $page->Lng = $data['Lng'];

    $page->writeToStage('Stage');
    $pageID = $page->ID;

    $uploadTmpDir = 'assets' . DIRECTORY_SEPARATOR . 'walks' . DIRECTORY_SEPARATOR . 'submitted' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
    $uploadBase = Director::baseFolder() . DIRECTORY_SEPARATOR;
    $uploadDir = 'assets' . DIRECTORY_SEPARATOR . 'walks' . DIRECTORY_SEPARATOR . 'submitted' . DIRECTORY_SEPARATOR;

    @$UploadedImages = $data['UploadedFiles'];
    if (isset($UploadedImages)) {
      /*
       * ajax uploaded files
       */
      foreach ($UploadedImages as $file) {
        $parentID = 21; // assets/walks/submitted/ folder
        $ownerID = 2; // content editors group
        $className = 'Image';
        $name = $file;
        $ext = pathinfo($uploadBase.$uploadTmpDir.$file, PATHINFO_EXTENSION);
        $fileNum = 1;
        // check file doesn't exist
        while ($fileNum<1000 && file_exists($uploadBase.$uploadDir.$data['Walk-Name'].'-'.str_pad($fileNum, 3, '0', STR_PAD_LEFT).'.'.$ext)) {
          $fileNum++;
        }
        $filename = $data['Walk-Name'].'-'.str_pad($fileNum, 3, '0', STR_PAD_LEFT).'.'.$ext;
        $title = $data['Walk-Name'].' '.$fileNum;
        $moveSuccess = rename($uploadBase.$uploadTmpDir.$file, $uploadBase.$uploadDir.$filename); // move file out of temp
        if ($moveSuccess) {
          $image = new Image();
          $image->Filename = $uploadDir.$filename;
          $image->Title = $title;
          $image->ParentID = $parentID;
          $image->write();
          $fileID = $image->ID;
        }
        if (isset($fileID)) {
          DB::query("INSERT \"walkpage_images\" SET \"ImageID\"='$fileID', \"WalkPageID\"='$pageID'");
        }
        $fileObj = File::find($uploadDir.$filename);
        if ($fileObj) {
          echo 'fileObj set';
          // //print_r($fileObj);
          if (isset($fileObj) && !$data['Lat'] && !$data['Lng']) {
            setLatLng($fileObj);
          }
        }
      }
    } else if (isset($data['Walk-Image'])) {
      if ($data['Walk-Image']['error'] == 0) {
        /*
         * oldstyle uploader fallback
         * create new single file array from file uploads array
         */
        $file = $data['Walk-Image'];
        $extType =  $file['type'];
        switch ($extType) {
          case 'image/png':
            $ext = 'png';
            break;
          case 'image/jpeg':
            $ext = 'jpg';
            break;
          case 'image/gif':
            $ext = 'gif';
            break;
          default:
            $form->sessionMessage('Extension not allowed...','bad');
            return $this->redirectBack();
            break;
        }
        $file['name'] = $data['Walk-Name'].'.'.$ext; // rename
        try {
          $newFile = new Image();
          $upload = new Upload();
          $folder = 'walks/submitted/';
          $folderObj = Folder::find_or_make($folder);
          $upload->loadIntoFile($file, $newFile, $folder);
          $fileObj = $upload->getFile();
          //echo '<pre>'; print_r($fileObj); echo '</pre>';
        } catch(ValidationException $e) {
          $form->sessionMessage('Extension not allowed...','bad');
          return $this->redirectBack();
        }
        $fileID = $fileObj->ID;
        if (isset($fileID)) {
          DB::query("INSERT \"walkpage_images\" SET \"ImageID\"='$fileID', \"WalkPageID\"='$pageID'");
        }
        if ($fileObj && !$data['Lat'] && !$data['Lng']) {
          $exifGPS = $this->getExifLonLat($fileObj->getFullPath());
          if ($exifGPS) {
            $page->Lat = $exifGPS['Lat'];
            $page->Lng = $exifGPS['Lng'];
            $page->writeToStage('Stage');
          }
        }
      }
    }

    $messageBody = "
      <h1>A new walk has been suggested!</h1>
      <p><strong>Name:</strong> {$data['Name']}</p>
      <p><strong>Email:</strong> {$data['Email']}</p>
      <p><strong>Message:</strong> {$data['Message']}</p>
    ";
    $email->setBody($messageBody);
    //$email->send(); // TODO disabled for testing

    // temp dir cleanup
    $cleanupTempDir = true; // Remove old files
    $maxFileAge = 5 * 3600; // Temp file age in seconds [5 hours]
    $time = time();
    $maxTime = $time-$maxFileAge;
    if ($cleanupTempDir) {
      if (is_dir($uploadBase.$uploadTmpDir) && ($dir = opendir($uploadBase.$uploadTmpDir))) {
        while (($file = readdir($dir)) !== false) {
          $tmpfilePath = $uploadBase.$uploadTmpDir.$file;
          if (filemtime($tmpfilePath) < $maxTime) {
            @unlink($tmpfilePath);
          }
        }
        closedir($dir);
      }
    }

    return array(
      'Content' => 'Thanks for the suggestion. ' . (($data['Email'])? 'We\'ll let you know as soon as it is available on the site.' : 'We\'ll add it to the site shortly. ' ),
      'SuggestForm' => $dataPrint//''
    );
  }

  public static function GetMaxUpload() {
    $upload_max_filesize = (int)ini_get('upload_max_filesize');
    $post_max_size = (int)ini_get('post_max_size');
    if($upload_max_filesize < $post_max_size) {
      return $upload_max_filesize;
    } else {
      return $post_max_size;
    }
  }

  public function PreviouslyUploadedImages() {
    $UploadedFiles = Session::get('UploadedFiles');
    Session::clear('UploadedFiles');
    if (is_array($UploadedFiles)) return json_encode($UploadedFiles);
  }

  private function getExifLonLat($absoluteFilename) {
    $image = $absoluteFilename;
    $exif = exif_read_data($image, 0, true);
    if (isset($exif)) {
      @$GPS = $exif['GPS'];
      if (isset($GPS)) {
        $LatRaw = $GPS["GPSLatitude"];
        $LatRefRaw = $GPS["GPSLatitudeRef"];
        $LngRaw = $GPS["GPSLongitude"];
        $LngRefRaw = $GPS["GPSLongitudeRef"];
        if (isset($LatRaw) && isset($LatRefRaw) && isset($LngRaw) && isset($LngRefRaw)) {
          $Lat = $this->getGps($LatRaw,$LatRefRaw);
          $Lng = $this->getGps($LngRaw,$LngRefRaw);
          if (isset($Lat) && isset($Lng)) {
            return array('Lat' => round($Lat,6), 'Lng' => round($Lng,6));
          }
        }
      }
    }
  }
  private function getGps($exifCoord, $hemi) {
    $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;
    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;
    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
  }
  private function gps2Num($coordPart) {
    $parts = explode('/', $coordPart);
    if (count($parts) <= 0) {return 0;}
    if (count($parts) == 1) {return $parts[0];}
    return floatval($parts[0]) / floatval($parts[1]);
  }

  public function GetPlainContent() {
    $link = 'walks/auckland/south/auckland-botanic-gardens/';
    $page = SiteTree::get_by_link($link);
    $content = $page->getField('Content');
    return strip_tags($content);
  }
}

class SuggestValidator extends Validator {
  public function php($data) {
    if (!$data['Walk-Name']) {
      $this->validationError('Walk-Name', 'Please name this walk', 'required bad');
    }
    $this->walkImageError($data['Walk-Image']['error']);

    if (!$this->getErrors()) {
      return TRUE;
    } else {
      $UploadedFiles = $_POST['UploadedFiles'];
      if (is_array($UploadedFiles)) {
        Session::set('UploadedFiles', $UploadedFiles);
      }
    }
  }
  public function javascript() {
    return '';
  }

  private function walkImageError($errorNum) {
    $maxSize = WalkSuggestForm::GetMaxUpload();
    $maxSizeBytes = $maxSize * 1024 * 1024;
    switch ($errorNum) {
      case 0:
        return;
        break;
      case 1:
      case 2:
        return $this->validationError('Walk-Image', 'Image is over the max size of '.$maxSize.'MB, please reduce the filesize and try again', 'required bad');
        break;
      case 3:
        return $this->validationError('Walk-Image', 'Upload failed part way through, please retry', 'required bad');
        break;
      case 4:
        return;// $this->validationError('Walk-Image', 'No file was selected, please select image', 'required bad');
        break;
      default:
        return $this->validationError('Walk-Image', 'Upload failed unexpectedly, please try again', 'required bad');
        break;
    }
  }
}
