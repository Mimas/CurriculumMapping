<?php
/**
 * Model for Mapping
 * Petros Diveris, Pilot 1
 * MIMAS, Autumn 2014

 * Petros Diveris, Phase 2
 * JISC, Winter 2015
 *
 * @property string uid
 * @property string subject_area
 * @property string currency
 * @property string viewable
 * @property string level
 * @property string checksum
 * @property string content_usage
 * @property string other_resources
 * @property string desired_content
 * @property string other_qualifications
 * @property string created_at
 * @property string updated_at
 *
 */
class Mapping extends EloquentUserStamp  {
  /**
   * The database table used by the model.
    * @var string
   */
  protected $table = 'mappings';

  /**
   * @var
   */
  protected $tracking = null;

 /**
  * Validation rules
  */
  public static $rules = array('uuid'=>'required', 'currency'=>'required');

  protected $fillable = array('uuid', 'subject_area', 'uid', 'level', 'content_usage', 'currency', 'created_by', 'udpated_by');

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
   * Get the linked USER (custom) tags for the Resource's Mappings
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function userTags() {
    $ret =  $this->belongsToMany('Tag', 'mappings_tags', 'mappings_id', 'tags_id');
    return $ret;
  }

  /**
   * Get the latest changeset from issues
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function currentIssues() {
    $id = intVal($this->id);
    $sql = "changeset = (select max(changeset) from mappings_issues where mappings_id=$id)";
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
      if (strpos($key,'qualification_')!==false) {
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
      $ldc = LdcsView::where('ldcs_desc','=',"$tag")->first();
      if (null != $ldc) {
        $metaLdcs = new MappingLdcs();
        $metaLdcs->mappings_id = $this->id;
        $metaLdcs->ldcs_id = $ldc->id;
        $metaLdcs->save();
      }
    }
    return true;
  }

  /**
   * Attach custom (user) tags to the Mapping. Delete them first!
   * See notes in attachQualifications for ways to improve
   * @param $tags
   * @return bool
   *
   */
  public function attachUserTags($tags) {
    // delete
    MappingTag::where('mappings_id','=',$this->id)->delete();

    // attach
    foreach ($tags as $tag) {
      $userTag = Tag::where('label','=',"$tag")->first();

      if (null != $userTag) {

      } else {
        $userTag = new Tag;
        $userTag->label = $tag;
        $userTag->save();
      }

      $mapTag = new MappingTag;
      $mapTag->tags_id = $userTag->id;
      $mapTag->mappings_id = $this->id;
      $mapTag->save();

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

  /**
   * Start a timer for the mapping
   */
  public static function startTracking($uid) {
    $t = new Tracking();

    try {
      $user = $user = \Sentry::getUser();
    } catch (Exception $e) {
      // TODO: Make use of Sentry specific exceptions?!
      // do something
    }
 
    $t->started_at = date('Y-m-d H:i:s');
    $t->user_id = $user->id;
    $t->resource = 'Mapping';
    $t->key_name = 'uid';
    $t->key = $uid;

    $t->save();

    return $t->id;
  }

  /**
   * @throws Exception
   */
  public static function stopTracking($id) {
    $t = Tracking::find($id);
    
    if (!$t) {
      // throw exception
    }
    $t->ended_at = date('Y-m-d H:i:s');
    $t->save();

  }

  /**
   * @param $uid
   * @return bool
   */
  public static function getCurrent($uid) {
    $mapping = Mapping::where('uid','=',"$uid")->first();

    return ( ($mapping==null || $mapping->current==1) );
    return $ret;
  }

  /**
   * @param $uid
   * @return bool
   *
   */
  public static function getViewable($uid) {
    $mapping = Mapping::where('uid','=',"$uid")->first();
    $ret = ($mapping<>null && $mapping->viewable==1);
    return $ret;
  }

  /**
   * @param $uid
   * @param int $viewable
   * @throws Exception
   * @return bool
   *
   */
  public static function setViewable($uid,$viewable=1) {
    $mapping = Mapping::where('uid','=',"$uid")->first();

    if ($mapping!=null) {
    } else {
      $mapping = new Mapping;
      $mapping->uid = $uid;
    }
    $mapping->viewable = $viewable;

    if (!$mapping->save()) {
      // throw exception
      throw new Exception("Couldn't save the 'viewable' flag for this resource [$uid] ");
    }

    return true;
  }


}
