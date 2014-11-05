<?php
/**
 * HT API Bitstream
 *
 * Bitstream
 *
 * Representation of anything streamable or downloadable
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service\Hairdressing;

use MIMAS\Service\Hairdressing\Db\Models\ContentTypePage;
use MIMAS\Service\Hairdressing\Db\Models\MenuLink;

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 25/04/2014
 * Time: 14:59
 */
class Bitstream extends DrupalApi
{
  /**
   * id
   * @var string
   */
  protected $id = '';

  /**
   * handle
   * @var string
   */
  protected $handle = '';

  /**
   * name
   * @var string
   */
  protected $name = '';

  /**
   * type
   * @var string
   */
  protected $type = '';

  /**
   * link
   * @var string
   */
  protected $link = '';

  /**
   * expand
   * @var array
   */
  protected $expand = array();

  /**
   * bundleName
   * @var string
   */
  protected $bundleName = '';

  /**
   * description
   * @var string
   */
  protected $description = '';

  /**
   * format
   * @var string
   */
  protected $format = '';

  /**
   * mimeType
   * @var string
   */
  protected $mimeType = '';

  /**
   * sizeBytes
   * @var string
   */
  protected $sizeBytes = '';

  /**
   * parentObject array
   * @var array
   */
  protected $parentObject = array();

  /**
   * retrieveLink
   * @var $retrieveLink
   */
  protected $retrieveLink;

  /**
   * checkSum
   * @var array checkSum
   * @todo implement checkSum functionality
   */
  protected $checkSum = array();

  /**
   * sequenceId
   * @var int $sequenceId
   */
  protected $sequenceId = 0;

  /**
   *
   * Constructor. Data or format passed to Base model
   *
   * @param string $params
   * @param string $inputFormat
   * @param array $options
   */
  public function __construct($params = '', $inputFormat = 'application/json', $options = array())
  {
    parent::__construct($params, $inputFormat, $options);

  }

  /**
   * Get id
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set id
   * @param string $id
   */
  public function setId($id = '')
  {
    $this->id = $id;
  }

  /**
   * Get handle
   * @return string
   */
  public function getHandle()
  {
    return $this->handle;
  }

  /**
   * Set handle
   * @param string $handle
   */
  public function setHandle($handle = '')
  {
    $this->handle = $handle;
  }

  /**
   * Get name
   * @return string name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set name
   * @param string $name
   */
  public function setName($name = '')
  {
    $this->name = $name;
  }

  /**
   * Get type
   * @return string type
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set type
   * @param string $type
   */
  public function setType($type = '')
  {
    $this->type = $type;
  }

  /**
   * Get link
   * @return string link
   */
  public function getLink()
  {
    return $this->link;
  }

  /**
   * Set link
   * @param string $link
   */
  public function setLink($link = '')
  {
    $this->link = $link;
  }

  /**
   * Get expand
   * @return array
   */
  public function getExpand()
  {
    return $this->expand;
  }

  /**
   * Set expand
   * @param array|string $expand
   * @param bool $reset
   */
  public function setExpand($expand = Array(), $reset = false)
  {
    $this->expand = $expand;
  }

  /**
   * Set bundleName
   * @param string $bundleName
   */
  public function setBundleName($bundleName = '')
  {
    $this->bundleName = $bundleName;
  }

  /**
   * Get bundleName
   * @return string
   */
  public function getBundleName()
  {
    return $this->bundleName;
  }

  /**
   * Get description
   * @param string $description
   */
  public function setDescription($description = '')
  {
    $this->description = $description;
  }

  /**
   * Set description
   * @return string description
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Get format
   * @param string $format
   */
  public function setFormat($format = '')
  {
    $this->format = $format;
  }

  /**
   * Set format
   * @return string format
   */
  public function getFormat()
  {
    return $this->format;
  }

  /**
   * Set mimeType
   * @param string $mimeType
   */
  public function setMimeType($mimeType = '')
  {
    $this->mimeType = $mimeType;
  }

  /**
   * Get mimeType
   * @return string mimeType
   */
  public function getMimeType()
  {
    return $this->mimeType;
  }

  /**
   * Set setSizeBytes
   * @param int $sizeBytes
   */
  public function setSizeBytes($sizeBytes = 0)
  {
    $this->sizeBytes = $sizeBytes;
  }

  /**
   * get setSizeBytes
   * @return int
   */
  public function getSizeBytes()
  {
    return $this->sizeBytes;
  }

  /**
   * Set parentObject
   * @param $parentObject
   */
  public function setParentObject($parentObject)
  {
    $this->parentObject = $parentObject;
  }

  /**
   * Get parentObject
   * @return array
   */
  public function getParentObject()
  {
    return $this->parentObject;
  }

  /**
   * Get retrieveLink
   * @param string $retrievalLink
   */
  public function setRetrieveLink($retrievalLink = '')
  {
    $this->retrieveLink = $retrievalLink;
  }

  /**
   * Set retrieveLink
   * @return mixed
   */
  public function getRetrieveLink()
  {
    return $this->retrieveLink;
  }

  /**
   * Get checkSum
   * @param array $checkSum
   */
  public function setCheckSum($checkSum = array())
  {
    $this->checkSum = $checkSum;
  }

  /**
   * Set checkSum
   * @return array checkSum
   */
  public function getCheckSum()
  {
    return $this->checkSum;
  }

  /**
   * Get sequenceId
   * @param int $sequenceId
   */
  public function setSequenceId($sequenceId = 0)
  {
    $this->sequenceId = $sequenceId;
  }

  /**
   * Set sequenceId
   * @return int sequenceId
   */
  public function getSequenceId()
  {
    return $this->sequenceId;
  }

  /**
   * Fetch the bitstream and return it, along with the mime type
   * as specified in the Bitstream
   *
   * @return \Illuminate\Http\Response
   *
   */
  public function retrieve()
  {
    $url = parent::$apiUrl . substr($this->getRetrieveLink(), 1);

    $stream = self::ApiCall($url);

    $response = \Response::make($stream);
    $response->header('Content-Type', $this->getMimeType());
    return $response;

  }

  /**
   * @param string $url
   * @return string
   */
  protected static function sanitizeMe($url='') {
    $ret = $url;
    if (strpos($url, 'vimeo.com')!==false) {

      $bits = explode('/',$url);
      $ret = $bits[0].'//'.'player.vimeo.com/video/'.$bits[count($bits)-1];

    }
    return $ret;
  }

  /**
   * Fetch the bitstream and return it, along with the mime type
   * as specified in the Bitstream
   *
   * @return \Illuminate\Http\Response
   *
   */
  public function retrieveStream()
  {
    $url = parent::$apiUrl . substr($this->getRetrieveLink(), 1);

    $stream = self::ApiCall($url);

    return $stream;

  }


  /**
   * Render!!
   */
  public function render() {

    switch ($this->getMimeType()) {
      case 'application/pdf':
        $pdfName = \Bentleysoft\Helper::makePdf($this);
        return \View::make('conversions.pdf')->with(array('file'=>$pdfName));
        break;
      case 'application/msword':
        $pdfName = \Bentleysoft\Helper::makePdf($this);
        return \View::make('conversions.pdf')->with(array('file'=>$pdfName));
        break;
      case 'application/vnd.ms-powerpoint':
        $pdfName = \Bentleysoft\Helper::makePdf($this);
        return \View::make('conversions.pdf')->with(array('file'=>$pdfName));
        break;
      case 'application/vnd.ms-excel':
        $pdfName = \Bentleysoft\Helper::makePdf($this);
        return \View::make('conversions.pdf')->with(array('file'=>$pdfName));
        break;
      case 'text/richtext':
        $pdfName = \Bentleysoft\Helper::makePdf($this);
        return \View::make('conversions.pdf')->with(array('file'=>$pdfName));
        break;
      case 'image/png':
      case 'image/jpg':
      case 'image/jpeg':
        return \View::make('conversions.image')->with(array('bitstream'=>$this));

      case 'application/x-shockwave-flash':
        $swfName = \Bentleysoft\Helper::makeLocal($this);
        return \View::make('conversions.swf')->with(array('file'=>$swfName));
        break;

      case 'text/xml':
        return \View::make('conversions.xml')->with(array('bitstream'=>$this));
        break;

      case 'text/html':
        return \View::make('conversions.html')->with(array('bitstream'=>$this));
        break;
      case 'text/plain':
        return \View::make('conversions.plain')->with(array('bitstream'=>$this));
        break;

      default:
        echo $this->getMimeType();
        echo $this->getBundleName();
        echo $this->getFormat();
        die;

    }
  }

  public function getPreviewUrl() {
    $url = '';
    switch ($this->getBundleName()) {
      case 'URL_BUNDLE':
        $url = $this->getName();
        $url = self::sanitizeMe($url);
        break;
      default:
        $url .= asset('preview/'.$this->getId());
    }
    return $url;

  }

  /**
   * Find. Wrapper for findByIdOrHandle
   * @param string $id
   * @param array $options
   * @param string $outputFormat
   * @param string $inputFormat
   * @return \MIMAS\Service\Jorum\Item $item
   *
   */
  public static function find($id = '', $options = array(), $outputFormat = '', $inputFormat)
  {
    $bitstream = new Bitstream($outputFormat, $inputFormat);
    return $bitstream->findByIdOrHandle($id, $options);
  }

  /**
   * @return bool
   */
  public function isExternal() {
    $ret = true;
    $ret = $ret && strpos($this->getName(),'http://')!==false;
    return $ret;
  }


  /**
   * Is the Bitstream content or Metadata related to the Resource?
   * @return bool
   */
  public function isContent() {
    $ret = true;

    $ret = $ret && $this->getFormat() <> 'Unknown';
    $ret = $ret && strpos($this->getName(),'license')===false;
    $ret = $ret && strpos($this->getName(),'imsmanifest')===false;
    $ret = $ret && strpos($this->getName(),'PreviewIndexBitstream')===false;

    return $ret;

  }

}