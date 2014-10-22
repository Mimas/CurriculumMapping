<?php
/**
 * Model for Mapping
 * Petros Diveris, Pilot 1
 * MIMAS, Autumn 2014
 *
 * @property string uuid
 * @property string oid
 * @property string subject_area
 * @property string currency
 * @property string level
 * @property string content_usage
 * @property string created_at
 * @property string updated_at
 */
class Mapping extends EloquentUserStamp  {
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'mappings';

 /**
  * Validation rules
  */
  public static $rules = array('uuid'=>'required', 'currency'=>'required');

  protected $fillable = array('uuid', 'subject_area', 'uid', 'level', 'content_usage', 'currency');

  private $issues = null;

  //Booting the extended model to add created_by and updated_by to all tables
  public static function boot()
  {
    parent::boot();

  }


  /**
  * Get the linked qualifications for the Resource's Mappings 
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
  */
  public function qualifications() {
    $ret =  $this->belongsToMany('Qualification', 'mappings_qualifications', 'mappings_id', 'qualifications_id');
    /*
                ->withPivot('id', 'type')
                ->where('type','=','banner');
    */
    return $ret;
  }

  /**
   * Get the linked tags for the Resource's Mappings
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function tags() {
    $ret =  $this->belongsToMany('LdcsView', 'mappings_ldcs', 'mappings_id', 'ldcs_id');
    return $ret;
  }

  /**
   * Get the latest changeset from issues
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function currentIssues() {
    $sql = "changeset = (select max(changeset) from mappings_issues where mappings_id=$this->id)";
    $issues = MappingIssue::whereRaw($sql)->get();

    return $issues;
  }

  /**
   * @param $key
   * @return string
   */
  public function checked($key) {
    $issues = $this->currentIssues();
    foreach ($issues as $issue) {
      if ($issue->issue == $key) {
        return 'checked="checked"';
      }
    }
    return '';
  }

  public function otherText() {
    $issues = $this->currentIssues();
    foreach ($issues as $issue) {
      if ($issue->issue == 'Other') {
        return $issue->other;
      }
    }
    return '';

  }

  /**
   * Method to replace the attached qualifications with a bunch of new ones
   * This method deletes and then adds. There are more elegant ways of foing that
   * Also, the this method should not need to check the trpos($key,'qualification')
   *
   * @TODO: Go elegant
   *
   * @param array $qualifications
   * @return bool
   */
  public function attachQualifications($qualifications = array()) {
    MappingQualification::where('mappings_id','=',$this->id)
                        ->delete();

    foreach ($qualifications as $key=>$input) {
      if (strpos($key,'qualification')!==false) {
        $qid = str_replace('qualification_', '', $key);
        $metaQual = new MappingQualification();

        $metaQual->mappings_id = $this->id;
        $metaQual->qualifications_id = $qid;
        $metaQual->save();
      }
    }
    return true;

  }

  /**
   * Attach tags (subjects) to the Mapping. Delete them first!
   * See notes in attachQualifications for ways to improve
   *
   * @param $tags
   * @return bool
   */
  public function attachTags($tags) {
    // delete
    MappingLdcs::where('mappings_id','=',$this->id)
      ->delete();

    // attach
    foreach ($tags as $tag) {
      $ldcs = LdcsView::where('ldcs_desc','=',"$tag")->get();
      if (count($ldcs)>0 ) {
        $ldc = $ldcs[0];
        $metaLdcs = new MappingLdcs();
        $metaLdcs->mappings_id = $this->id;
        $metaLdcs->ldcs_id = $ldc->id;
        $metaLdcs->save();
      }
    }
    return true;
  }

  /**
   * Attach issues to the Mapping!
   * See notes in attachQualifications for ways to improve
   *
   * @param $issues
   * @param $other
   * @return bool
   * @internal param $tags
   */
  public function attachIssues($issues, $other) {
    if (count($issues)>0) {

      // get an identifier for all issues so that we can group (and order) them
      $changeId = uniqid();

      foreach ($issues as $i=>$issue) {
        $mappingIssue = new MappingIssue();
        $mappingIssue->mappings_id = $this->id;
        $mappingIssue->issue = $issue;

        $mappingIssue->changeset = $changeId;

        if ($issue == 'Other') {
          $mappingIssue->other = $other;
        }
        $mappingIssue->save();
      }

    }
    return true;

  }

}
