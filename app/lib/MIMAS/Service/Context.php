<?php
/**
 * Jorum API Context
 *
 * Context
 *
 * Provides information about the context of a request that results in a list
 * such as page number, item count etc.
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service;

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 25/04/2014
 * Time: 14:59
 */
class Context extends JorumModel
{
    /**
     * limit
     * @var int $limit
     */
    protected $limit = 0;

    /**
     * offset
     * @var int $offset
     */
    protected $offset = 0;

    /**
     * totalCount
     * @var int $totalCount
     */
    protected $totalCount = 0;

    /**
     * query
     * @var string $query
     */
    protected $query = '';

    /**
     * error
     * @var string $error
     */
    protected $error = '';

    /**
     * queryDate
     * @var string $queryDate
     */
    protected $queryDate = '';

    /**
     * Constructor. Data or format passed to Base model
     *
     * Constructor. Set the properties based on data array passed, if any
     * @param null $data
     */
    public function __construct($data = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $key = camel_case($key);
                if (property_exists($this, $key)) {
                    $this->$key = $value;

                }
            }
        }
    }

    /**
     * Get limit
     * @return int limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set limit
     * @param $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * Get offset
     * @return int offset
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set offset
     * @param $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * Get totalCount
     * @return int totalCount
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * Set totalCount
     * @param $totalCount
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * Set query
     * @return string query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set query
     * @param $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Get Error
     * @return string error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

}