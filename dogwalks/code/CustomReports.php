<?php
class UnpublishedDrafts extends SS_Report {
  function title() {
    return 'Unpublished Drafts';
  }

  function description() {
    return 'View pages with drafts newer than the published version';
  }

  function sourceRecords($params, $sort, $limit) {
    $Live = Versioned::get_by_stage('SiteTree', 'Live');
    $Pages = new ArrayList();
    foreach ($Live as $key => $value) {
      if (!$value->isLatestVersion()) {
        $Pages->add($value);
      }
    }
    return $Pages;
  }

  function columns() {
    $fields = array(
      'Title' => array(
        'title' => 'Page name',
        'formatting' => '<a href=\"admin/pages/edit/show/{$ID}\" title=\"Edit page\">{$value}</a>'
      ),
      'Created' => array(
        'title' => 'Created',
        'casting' => 'SS_Datetime->Full'
      ),
      'LastEdited' => array(
        'title' => 'Last Edited',
        'casting' => 'SS_Datetime->Ago'
      )
    );
    return $fields;
  }

  function parameterFields() {
    $params = new FieldList();
    return $params;
  }
}
