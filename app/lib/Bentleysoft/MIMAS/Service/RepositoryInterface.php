<?php
/**
 * Enforce at least the following three methods
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 * Created by PhpStorm.
 * User: pedro
 * Date: 02/06/2014
 * Time: 14:37
 */

namespace MIMAS\Service;

/**
 * Interface RepositoryInterface
 * @package MIMAS\Service
 */
interface RepositoryInterface
{

    /**
     * Find by id or handle
     * @param string $id
     * @param array $options
     * @return mixed
     */
    public function findByIdOrHandle($id = '', $options = array());

    /**
     * Return all rows
     * @return mixed
     */
    public function all();

    /**
     * Return as pretty HTML
     * @return mixed
     */
    public function toHtml();

} 