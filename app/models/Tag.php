<?php
/**
 * Model for Tag
 * Petros Diveris, Pilot 1
 * MIMAS, Autumn 2014
 *
 * @property int id
 * @property string label
 * @property string created_at
 * @property string updated_at
 * @property string created_by
 * @property string updated_by
 *
 */
class Tag extends EloquentUserStamp  {
  /**
   * The database table used by the model.
    * @var string
   */
  protected $table = 'tags';

 /**
  * Validation rules
  */
  public static $rules = array('label'=>'required', );

  protected $fillable = array('label', 'created_by', 'updated_by',);


  //Booting the extended model to add created_by and updated_by to all tables
  public static function boot()
  {
    parent::boot();
  }

}
