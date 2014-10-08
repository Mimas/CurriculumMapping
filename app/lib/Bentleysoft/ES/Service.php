<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 18/09/2014
 * Time: 13:19
 */

namespace Bentleysoft\ES;

class Service
{
  /**
   * @param int $from
   * @param int $size
   * @param string $pattern
   * @internal param int $sice
   * @return mixed
   */
  public static function browse($from = 0, $size = 20, $pattern = '*')
  {

    $searchParams['index'] = 'ciim';

    $searchParams['size'] = $size;
    $searchParams['from'] = $from;


    $searchParams['sort'] = array(
      /*
      '_type:desc',
      'summary_title:asc'
      */
      'processed:desc'
    ,
      'edited:asc'

    );

///    $searchParams['body']['query']['wildcard']['summary_title'] = "*$pattern*";

    $filter = array();
    $filter['term']['audience'] = 'FE';


    $query = array();
//    $query['match']['summary_title'] = "*$pattern*";

///    $searchParams['body']['query']['bool']['must'][]['match']['audience'] = 'FE';

    $searchParams['body']['query']['filtered'] = array(
        "filter" => $filter,
        "query" => $query,
      );

    $result = \Es::search($searchParams);

    //$searchParams['id'] = 'ht-node/805';
    //$searchParams['type'] = 'collection';
    // $result = Es::get($searchParams);

    return ($result);
  }

  public static function mapped($from = 0, $size = 20, $pattern = '*')
  {

    $searchParams['index'] = 'ciim';

    $searchParams['size'] = $size;
    $searchParams['from'] = $from;


    $searchParams['sort'] = array(
      /*
      '_type:desc',
      'summary_title:asc'
      */
      'processed:desc'
    ,
      'edited:asc'

    );

    $searchParams['body']['query']['wildcard']['edited'] = "yes";
    /// $searchParams['body']['query']['wildcard']['summary_title'] = "*$pattern*";


    $result = \Es::search($searchParams);

    //$searchParams['id'] = 'ht-node/805';
    //$searchParams['type'] = 'collection';
    // $result = Es::get($searchParams);

    return ($result);
  }

  public static function get($id) {
    $searchParams = array('index'=>'ciim', 'id'=>$id, 'type'=>'learning resource');
    $result = \Es::get($searchParams);
    return ($result);

  }

} 