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
   * @param array|string $audience
   * @param array $areas
   * @param bool $showMapped
   * @param bool $showUnmapped
   * @param bool $showViewable
   * @param bool $showUnviewable
   * @return mixed
   */
  public static function browse($from = 0, $size = 20,
                                $pattern = '*',
                                $audience='FE',
                                $areas = array(),
                                $showMapped = true,
                                $showUnmapped = true,
                                $showViewable = false,
                                $showUnviewable = false)
  {
    $searchParams['index'] = 'ciim';

    $searchParams['size'] = $size;
    $searchParams['from'] = $from;

    $searchParams['sort'] = array(
      'summary_title:asc',
      'processed:desc',
      'edited:desc'
    );


    $must = array();
    $must[] = array("query_string"=>array("query"=>"$pattern"));

    if (!empty($areas)) {
      $extraTerms = self::getLdCodesForSubjects($areas);

      $must[] = array( 'terms'=>array(
                          'audience'=>array('FE'),
                          'subject.ldcode'=>$extraTerms,
                        )
                      );
    } else {
      $must[] = array( 'terms'=>array(
        'audience'=>array($audience),
        )
      );

    }

    $bool = array(
      'bool'=>array('must'=>$must),
    );

    $filters = array();

    if ($showMapped && $showUnmapped) {
      $filters[0]['or'][] = array("term"=>array("edited"=>true));
      $filters[0]['or'][] = array("term"=>array("edited"=>false));
      $filters[0]['or'][] = array("term"=>array("edited"=>'no'));
      $filters[0]['or'][] = array("missing"=>array("field"=>"edited"));
    } elseif ( $showMapped ) {
      $filters[0]['or'][] = array("term"=>array("edited"=>true));
    } elseif ($showUnmapped ) {
      $filters[0]['or'][] = array('not'=>array(
         "term"=>array("edited"=>true)
        )
      );
      $filters[0]['or'][] = array("missing"=>array("field"=>"edited"));
    }


    if ($showViewable && $showUnviewable) {
      $filters[1]['or'][] = array("term"=>array("viewable"=>true));
      $filters[1]['or'][] = array("term"=>array("viewable"=>false));
      $filters[1]['or'][] = array("missing"=>array("field"=>"viewable"));
    } elseif ( $showViewable ) {
      $filters[1]['or'][] = array("term"=>array("viewable"=>true));
    } elseif ($showUnviewable ) {
      $filters[1]['or'][] = array("term"=>array("viewable"=>false));
      $filters[1]['or'][] = array("missing"=>array("field"=>"viewable"));
    } else {
      $filters[1]['or'][] = array("exists"=>array("field"=>'uid'));
    }

    foreach ($filters as $i=>$flt) {
      if ($i==666);

      $filter['and'][] = $flt;
    }

    $query = array(
      'filtered'=>array(
         'query'=>$bool,
         'filter'=>$filter
       )
    );

    $searchParams['body']['query'] = $query;
    $result = \Es::search($searchParams);

    return ($result);
  }


  /**
   * @param int $from
   * @param int $size
   * @param string $pattern
   * @param array|string $audience
   * @internal param int $sice
   * @return mixed
   */
  public static function test($from = 0, $size = 20, $pattern = '*', $audience='FE')
  {
    $searchParams['index'] = 'ciim';

    $searchParams['size'] = $size;
    $searchParams['from'] = $from;

    $searchParams['sort'] = array(
      'summary_title:asc',
      'processed:desc',
      'edited:asc'
    );

    ///    $searchParams['body']['query']['wildcard']['summary_title'] = "*$pattern*";

    $filter = array();

    $must = array();
    $must[] = array("query_string"=>array("query"=>$pattern));
    $must[] = array( 'terms'=>array('audience'=>array('FE')) );

    // THIS HALF WORKS
    $query = array(
      'bool'=>array(
          'must'=>$must,  
        )
    );

    $searchParams['body']['query'] = $query;

    $result = \Es::search($searchParams);


    return ($result);
  }


  /**
  * Get the "mapped" resources - the main bit of the beast...
  */
  public static function mapped($from = 0, $size = 20, $pattern = '*')
  {

    $searchParams['index'] = 'ciim';

    $searchParams['size'] = $size;
    $searchParams['from'] = $from;

    $searchParams['sort'] = array(
      'processed:desc',
      'edited:asc'
    );

    $extraTerms = array();

    if (!empty($areas)) {
      $extraTerms = self::getLdCodesForSubjects($areas);

      $must[] = array( 'terms'=>array(
                          'audience'=>array('FE'),
                          'subject.ldcode'=>$extraTerms,
                        )
                      );
    } else {
      $must[] = array( 'terms'=>array(
        'audience'=>array('FE'),

        )
      );

    }

    $bool = array(
      'bool'=>array('must'=>$must),
    );

    $query = array(
      'filtered'=>array(
        'query'=>$bool,
        'filter'=>array('term'=>array('edited'=>true)
          )
        ),

      );

    $searchParams['body']['query'] = $query;
    $result = \Es::search($searchParams);


    return ($result);

  }
  

  /**
  * Get the "mapped" resources - the main bit of the beast...
  */
  public static function mappedX($from = 0, $size = 20, $pattern = '*')
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

  public static function codeH()
  {

    $searchParams['index'] = 'ciim';

    $searchParams['size'] = 25;
    $searchParams['from'] = 0;

    $filter['or'][]['term'] = array('subject'=>'[]' );
    //$filter = array();

    $query = array(
      'wildcard'=>array('summary_title'=>"**")
    );

    $searchParams['body']['query']['filtered'] = array(
      "filter" => $filter,
      "query" => $query,
    );

    $result = \Es::search($searchParams);

    var_dump($result);
  }


  public static function get($id) {
    $searchParams = array('index'=>'ciim', 'id'=>$id, 'type'=>'learning resource');
    $result = \Es::get($searchParams);
    return ($result);

  }

  /**
   * @param int $from
   * @param int $size
   * @return array
   */
  public static function orphans($from = 0, $size = 20)
  {
    $searchParams['index'] = 'ciim';

    $searchParams['size'] = $size;
    $searchParams['from'] = $from;

    $filter = array();

    $filter['prefix'] = array('_id'=>'ht');

    $query = array(
      'wildcard'=>array('summary_title'=>"**")
    );

    $searchParams['body']['query']['filtered'] = array(
      "filter" => $filter,
      "query" => $query,
    );

    $result = \Es::search($searchParams);

    return ($result);
  }

  /**
   * Return an array of LD codes groupped under the subjectId
   * Petros Diveris, Oct 14/14
   * Important: They need to be lowercase due to the tokenizer used
   *
   * @param array $subjectIds
   * @throws \Exception
   * @return array
   */
  public static function getLdCodesForSubjects($subjectIds = array()) {
    $ret = array();
    foreach ($subjectIds as $i=>$sid) {
      $s2ls = \SubjectareasLdcs::where('subjectarea_id','=',$sid)->get();
      foreach ($s2ls as $s2l) {
        $learnDirect = \Ldcs::find($s2l->ldcs_id);
        if (!$learnDirect) {
          throw new \Exception("Strange inability to find ldcs for id {$sld->ldcs_id} ");
        }
        $ret[] = strtolower($learnDirect->ldcs_code);
      }
    }
    return $ret;
  }

  /**
   * Map the semi-wrong (approximate) LD label from Jorum to the correct one
   * Return 'Unknown' if nothing found  (this will match '' as well)
   * This is primarily so we can then also get the correct code using the function
   *
   * @param $wrongCode
   * @return string
   */
  public static function getCorrectLdFromWrongJorumLd($wrongCode) {
    $map = array(
      'FE - Construction & Property (Built Environment)'=>'CONSTRUCTION and PROPERTY (BUILT ENVIRONMENT)',
      'FE - Education / Training / Teaching'=>'EDUCATION / TRAINING / TEACHING',
      'FE - Sciences & Mathematics'=>'SCIENCES and MATHEMATICS',
      'FE - Communication / Media / Publishing'=>'COMMUNICATION / MEDIA / PUBLISHING',
      'FE - Construction & Property (Built Environment)'=>'CONSTRUCTION and PROPERTY (BUILT ENVIRONMENT)',
      'FE - Politics / Economics / Law / Social Sciences'=>'POLITICS / ECONOMICS / LAW / SOCIAL SCIENCES',
      'FE - Engineering'=>'ENGINEERING',
      'FE - Arts & Crafts'=>'ARTS and CRAFTS',
      'FE - Catering / Food / Leisure Services / Tourism'=>'CATERING / FOOD / LEISURE SERVICES / TOURISM',
      'FE - Area Studies / Cultural Studies / Languages / Literature'=>'AREA STUDIES / CULTURAL STUDIES / LANGUAGES / LITERATURE',
      'FE - Family Care / Personal Development / Personal Care & Appearance'=>'FAMILY CARE / PERSONAL DEVELOPMENT / PERSONAL CARE and APPEARANCE',
      'FE - Health Care / Medicine / Health & Safety'=>'HEALTH CARE / MEDICINE / HEALTH and SAFETY',
      'FE - Business / Management / Office Studies'=>'BUSINESS / MANAGEMENT / OFFICE STUDIES',
      'FE - Information Technology & Information'=>'INFORMATION TECHNOLOGY and INFORMATION',
      'FE - Performing Arts'=>'PERFORMING ARTS',
      'FE - Sales Marketing & Retailing'=>'SALES MARKETING and RETAILING',
      'FE - Humanities (History / Archaeology / Religious Studies / Philosophy)'=>'HUMANITIES (HISTORY / ARCHAEOLOGY / RELIGIOUS STUDIES / PHILOSOPHY)',
      'FE - Agriculture Horticulture & Animal Care'=>'AGRICULTURE HORTICULTURE and ANIMAL CARE',
      'FE - Sports Games & Recreation'=>'SPORTS GAMES and RECREATION',
      'FE - Manufacturing / Production Work'=>'MANUFACTURING / PRODUCTION WORK',
      'Information and Digital Literacy Skills'=>'INFORMATION TECHNOLOGY and INFORMATION',
      'FE - Environment Protection / Energy / Cleansing / Security'=>'ENVIRONMENT PROTECTION / ENERGY / CLEANSING / SECURITY',
    );
    if (array_key_exists($wrongCode, $map)) {
      return $map[$wrongCode];
    }
    return 'Unknown';

  }

  /**
   * Get the Learning Direct Code from a label - top level only
   * @param $subject
   * @internal param $area
   * @return string
   */
  public static function getLdCodeFromLabel($subject) {
    $map = array(
      'BUSINESS / MANAGEMENT / OFFICE STUDIES'=>'A',
      'SALES MARKETING and RETAILING'=>'B',
      'INFORMATION TECHNOLOGY and INFORMATION'=>'C',
      'HUMANITIES (HISTORY / ARCHAEOLOGY / RELIGIOUS STUDIES / PHILOSOPHY)'=>'D',
      'POLITICS / ECONOMICS / LAW / SOCIAL SCIENCES'=>'E',
      'AREA STUDIES / CULTURAL STUDIES / LANGUAGES / LITERATURE'=>'F',
      'EDUCATION / TRAINING / TEACHING'=>'G',
      'FAMILY CARE / PERSONAL DEVELOPMENT / PERSONAL CARE and APPEARANCE'=>'H',
      'ARTS and CRAFTS'=>'J',
      'COMMUNICATION / MEDIA / PUBLISHING'=>'K',
      'PERFORMING ARTS'=>'L',
      'SPORTS GAMES and RECREATION'=>'M',
      'CATERING / FOOD / LEISURE SERVICES / TOURISM'=>'N',
      'HEALTH CARE / MEDICINE / HEALTH and SAFETY'=>'P',
      'ENVIRONMENT PROTECTION / ENERGY / CLEANSING / SECURITY'=>'Q',
      'SCIENCES and MATHEMATICS'=>'R',
      'AGRICULTURE HORTICULTURE and ANIMAL CARE'=>'S',
      'CONSTRUCTION and PROPERTY (BUILT ENVIRONMENT)'=>'T',
      'Unknown'=>'U',
      'SERVICES TO INDUSTRY and COMMERCE'=>'V',
      'MANUFACTURING / PRODUCTION WORK'=>'W',
      'ENGINEERING'=>'X',
      'OIL / MINING / PLASTICS / CHEMICALS'=>'Y',
      'LOGISTICS / DISTRIBUTION / TRANSPORT / DRIVING'=>'Z'
    );

    if (array_key_exists($subject, $map)) {
      return $map[$subject];
    } else {
      return 'U';
    }
  }

} 