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
class Mapping extends Eloquent  {
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'mappings';

 /**
  * Validation rules
  */
  public static $rules = array('uuid'=>'required', 'subject_area'=>'required','currency'=>'required');

  protected $fillable = array('uuid', 'subject_area', 'oid', 'level', 'content_usage', 'currency');

 /**
  * Get the linked qualifications for the Resource's Mappings 
  *
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
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function tags() {
    $ret =  $this->belongsToMany('LdcsView', 'mappings_ldcs', 'mappings_id', 'ldcs_id');
    /*
                ->withPivot('id', 'type')
                ->where('type','=','banner');
    */
    return $ret;
  }
}
