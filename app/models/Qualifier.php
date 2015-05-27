<?php
/**
 * Pedro Diveris
 * 
 * @property integer id
 * @property string label
 * @property string short
 * @property string colour
 * @property string url
 * @property integer created_at
 * @property integer updated_at
 *
 */
class Qualifier extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'qualifiers';
    /**
     * Validation rules
     */
    public static $rules = array('label'=>'required',);
    /**
    *
    */
    protected $fillable = array('label', 'colour', 'url');

}
