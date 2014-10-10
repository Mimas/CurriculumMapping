<?php
/**
 * Pedro Diveris
 * 
 * @property integer id
 * @property integer qualifier_id
 * @property string level
 * @property string qualification
 * @property integer created_at
 * @property integer updated_at
 *
 */
class Qualification extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'qualifications';
    /**
     * Validation rules
     */
    public static $rules = array('qualifier_id'=>'required', 'level'=>'required', 'qualification'=>'required');
    /**
    *
    */
    protected $fillable = array('qualifier_id', 'level', 'qualification');

}
