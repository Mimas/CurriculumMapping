<?php
/**
 * Model for Tracking
 * Petros Diveris, Phase 2 1
 * MIMAS, Early 2015
 *
 * @property int id
 * @property string key
 * @property string key_name
 * @property string user_id
 * @property string resource
 * @property string started_at
 * @property string ended_at
 * @property string created_at
 * @property string updated_at
 */
class Tracking extends EloquentUserStamp  {
  /**
   * The database table used by the model.
    * @var string
   */
  protected $table = 'tracking';


 /**
  * Validation rules
  */
  public static $rules = array('uid'=>'required', );

  protected $fillable = array('user_id', 'uid', 'created_by', 'updated_by',);


  //Booting the extended model to add created_by and updated_by to all tables
  public static function boot()
  {
    parent::boot();
  }


}
