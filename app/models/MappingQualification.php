<?php
/**
 * Pedro Diveris
 * 
 * @property int id
 * @property int mappings_id
 * @property string qualifications_id
 * @property int created_at
 * @property int updated_at
 *
 */
class MappingQualification extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mappings_qualifications';
    /**
     * Validation rules
     */
    public static $rules = array('mappings_id'=>'required', 'qualifications_id'=>'required');

    /**
    * Mass assignment allowed for the following fields:
    */
    protected $fillable = array('mappings_id', 'qualifications_id', );

}
