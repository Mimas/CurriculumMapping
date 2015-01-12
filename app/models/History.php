<?php
/**
 * Pedro Diveris
 * January 2015
 *
 * This model represents the History View
 * History returns an exportable list of actions
 * its therefore a read-only pseudo table, only used as a convenient means
 * of generating activity reports
 *
 * @property integer id
 * @property string uuid
 * @property string uid
 * @property string subject_area
 * @property string currency
 * @property string level
 * @property string checksum
 * @property string content_usage
 * @property string other_resources
 * @property string desired_content
 * @property string other_qualifications
 * @property string first_name
 * @property string last_name
 * @property datetime last_login
 * @property string tags
 * @property datetime created_at
 * @property datetime updated_at
 * @property integer updated_by
 * @property integer created_by
 */
class History extends Eloquent  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'history';


}
