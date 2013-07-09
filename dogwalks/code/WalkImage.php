<?php
class WalkImage extends DataExtension {
  public static $belongs_many_many = array(
    'Pages' => 'Page'
  );
}
