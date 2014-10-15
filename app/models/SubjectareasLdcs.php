<?php
/**
 * Pedro Diveris
 * 
 * @property int id
 * @property string subjectarea_id Subject Area Id
 * @property string ldcs_id Learn Diect Id
 */
class SubjectareasLdcs extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subjectareas_ldcs';
    /**
     * Validation rules
     */
    public static $rules = array('subjectarea_id'=>'required', 'ldcs_id'=>'required');

    /**
    * Mass assignment allowed for the following fields:
    */
    protected $fillable = array('subjectarea_id', 'ldcs_id', );

}
