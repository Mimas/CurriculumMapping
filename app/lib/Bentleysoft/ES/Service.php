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
   * @internal param int $sice
   * @return mixed
   */
  public static function browse($from = 0, $size = 20, $pattern = '*', $audience='FE')
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

    if ($audience=='*') {

    } else {

      if (!is_array($audience)) {
        $audience = explode(':', $audience);
      }

      foreach ($audience as $what ) {
        $filter['or'][]['term'] = array('audience'=>$what);
      }
    }

    $query = array(
      'wildcard'=>array('summary_title'=>"*$pattern*")
    );

    $searchParams['body']['query']['filtered'] = array(
        "filter" => $filter,
        "query" => $query,
      );

    $result = \Es::search($searchParams);


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

    $filter['prefix'] = array('_id'=>'jorum');

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
   * Map the semi-wrong (approximate) LD label from Jorum to the correct one
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
   * Get the Learnin Direct Code from a label - top level only
   * @param $area
   * @return string
   */
  public static function getLdCodeFromLabel($area) {
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

    if (array_key_exists($area, $map)) {
      return $map[$area];
    } else {
      return 'U';
    }
  }

} 