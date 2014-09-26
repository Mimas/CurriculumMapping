<?php
/**
 * Pedro Diveris
 * 
 * @property string id
 * @property string area
 * @property string stuff
 * @property string created_at
 * @property string updated_at
 */
class Subjectarea extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subjectareas';
    /**
     * Validation rules
     */
    public static $rules = array('area'=>'required', );
    /**
    *
    */
    protected $fillable = array('area', 'stuff', );

}
