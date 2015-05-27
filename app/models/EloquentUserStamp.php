<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 21/10/2014
 * Time: 14:36
 */

class EloquentUserStamp extends Eloquent {

  public static function boot()
  {
    parent::boot();

    static::creating(function($model)
    {

      //change to Auth::user() if you are using the default auth provider
      try {
        $user = $user = \Sentry::getUser();
      } catch (Exception $e) {
      // TODO: Make use of Sentry specific exceptions?!
      // do something
      }
      $model->created_by = $user->id;
      $model->updated_by = $user->id;
    });

    static::updating(function($model)
    {
      //change to Auth::user() if you are using the default auth provider
      try {
        $user = $user = \Sentry::getUser();
      } catch (Exception $e) {
        // TODO: Make use of Sentry specific exceptions?!
        // do something
      }
      $model->updated_by = $user->id;
    });
  }

} 