<?php
/**
 * Pedro Diveris
 * 
 * @property string id
 * @property string class
 * @property string action
 */
class Bridge extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bridge';

    /**
    * Mass assignment allowed for the following fields:
    */
    protected $fillable = array('ld', 'lddebug', 'ldcode', 'viewable', 'fewindow', 'edited', 'uid', 'uuid' );

}
