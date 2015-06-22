<?php

/**
 * Pedro Diveris
 *
 * @property int id
 * @property int mappings_id
 * @property string issue
 * @property string other
 * @property int severity
 * @property int changeset
 * @property int updated_at
 * @property int created_at
 * @property int updated_by
 */
class MappingIssue extends EloquentUserStamp
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'mappings_issues';
  /**
   * Validation rules
   */
  public static $rules = array('mappings_id' => 'required', 'issue' => 'required', );

  /**
   * Mass assignment allowed for the following fields:
   */
  protected $fillable = array('mappings_id', 'issue', 'updated_by', 'created_by');


  //Booting the extended model to add created_by and updated_by to all tables
  public static function boot()
  {
    parent::boot();
  }

}
