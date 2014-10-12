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
 * @property integer colour
 * @property integer label
 * @property integer url
 * @property integer qualifier
 * @property integer qualifier_short
 *
 */
class QualificationView extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'qualifications_view';
    /**
     * Validation rules
     */
    public static $rules = array('qualifier_id'=>'required', 'level'=>'required', 'qualification'=>'required');

    /**
    * Mass assignement
    */
    protected $fillable = array('qualifier_id', 'level', 'qualification');

}
