<?php
/**
 * Pedro Diveris
 * 
 * @property string id
 * @property string ldsc_code
 * @property string ldsc_desc
 * @property string created_on
 * @property string effective_to
 * @property string effective_from
 * @property string depth
 * @property string stuff
 * @property string created_at
 * @property string updated_at
 *
 */
class LdcsView extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ldcs_view';
    /**
     * Validation rules
     */
    public static $rules = array('ldsc_code'=>'required', 'ldsc_code'=>'required', );
    /**
    *
    */
    protected $fillable = array('ldsc_cocde', 'ldsc_desc', 'stuff', );

}
