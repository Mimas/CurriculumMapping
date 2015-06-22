<?php
/**
 * Pedro Diveris
 * 
 * @property int id
 * @property int users_id
 * @property string subjectareas_id
 */
class UserSubjectareas extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_subjectareas';
    /**
     * Validation rules
     */
    public static $rules = array('users_id'=>'required', 'subjectareas_id'=>'required');

    /**
    * Mass assignment allowed for the following fields:
    */
    protected $fillable = array('users_id', 'subjectareas_id', );

}
