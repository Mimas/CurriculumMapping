<?php
/**
 * Pedro Diveris
 * 
 * @property integer id
 * @property string ldsc_code
 * @property string ldsc_desc
 * @property string created_on
 * @property string effective_to
 * @property string effective_from
 * @property string stuff
 * @property string created_at
 * @property string updated_at
 *
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
