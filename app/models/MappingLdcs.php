<?php
/**
 * Pedro Diveris
 * 
 * @property int id
 * @property int mappings_id
 * @property string ldcs_id
 * @property int created_at
 * @property int updated_at
 *
 */
class MappingLdcs extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mappings_ldcs';
    /**
     * Validation rules
     */
    public static $rules = array('mappings_id'=>'required', 'ldcs_id'=>'required');

    /**
    * Mass assignment allowed for the following fields:
    */
    protected $fillable = array('mappings_id', 'ldcs_id', );

}
