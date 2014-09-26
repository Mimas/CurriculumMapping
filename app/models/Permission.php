<?php
/**
 * Pedro Diveris
 * 
 * @property string id
 * @property string class
 * @property string action
 */
class Permission extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'permissions';
    /**
     * Validation rules
     */
    public static $rules = array('class'=>'required', 'action'=>'required');
    /**
    * Mass assignment allowed for the following fields:
    */
    protected $fillable = array('class', 'action', );

}
