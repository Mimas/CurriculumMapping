<?php
/**
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

}
