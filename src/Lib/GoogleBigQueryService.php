<?php

namespace App\Lib;

use App\Exceptions\InternalErrorException;

class GoogleBigQueryService
{

    /**
     *
     * @var GoogleApiConnector;
     */
    private $googleApi;

    /**
     *
     * @var \Google_Service_Bigquery 
     */
    private $service;
    
    private $dataset;

    public function __construct($googleApi, $dataset)
    {
        $this->googleApi = $googleApi;
        $this->dataset = $dataset;
        $this->googleApi->connect(\Google_Service_Bigquery::BIGQUERY);
        $this->service = new \Google_Service_Bigquery($this->googleApi->getClient());
    }

    
    /**
     * 
     * @param string $table
     * @param array $data
     * @param boolean $many
     * @return \Google_Service_Bigquery_TableDataInsertAllResponse
     * @throws InternalErrorException
     */
    public function insert($table, array $data, $many = false)
    {
        try {
            $rows = [];
            if ($many) {
                foreach ($data as $rowData) {
                    $rows[] = $this->createRow($rowData);
                }
            } else {
                $rows[0] = $this->createRow($data);
            }

            $request = new \Google_Service_Bigquery_TableDataInsertAllRequest();
            $request->setKind('bigquery#tableDataInsertAllRequest');
            $request->setRows($rows);

            $res = $this->service->tabledata->insertAll($this->googleApi->getProjectId(), $this->dataset, $table, $request);

            if ($errors = $res->getInsertErrors()) {
                $error_messages = [];
                foreach ($errors as $e) {
                    foreach ($e as $v) {
                        $error_messages[] = "{$v->getLocation()} {$v->getMessage()}";
                    }
                }

                throw new InternalErrorException(implode("<br>\n", $error_messages));
            }

            return $res;
        } catch (\Exception $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

    /**
     * 
     * @param string $table
     * @param array $data
     * @return \Google_Service_Bigquery_TableDataInsertAllResponse
     */
    public function insertMany($table, array $data)
    {
        return $this->insert($table, $data, true);
    }

    /**
     * 
     * @param array $data
     * @return \Google_Service_Bigquery_TableDataInsertAllRequestRows
     */
    private function createRow(array $data)
    {
        $row = new \Google_Service_Bigquery_TableDataInsertAllRequestRows();
        $row->setJson($data);
        return $row;
    }

    /**
     * 
     * @param string $sql
     * @param boolean $cache
     * @return array
     * @throws InternalErrorException
     */
    public function executeSql($sql, $cache = false)
    {
        try {
            $query = new \Google_Service_Bigquery_QueryRequest();
            $query->setQuery($sql);
            $query->setTimeoutMs(10000);
            $query->setUseQueryCache($cache);
            $response = $this->service->jobs->query($this->googleApi->getProjectId(), $query);

            $columns = $rows = array();
            // get columns...
            $fields = $response->getSchema()->getFields();
            foreach ($fields as $field) {
                $columns[] = $field->getName();
            }
            // ...then get rows
            foreach ($response->getRows() as $row) {
                $index = 0; // reset value
                $data = array(); // reset value
                foreach ($row->getF() as $k => $v) {
                    $data[$columns[$index]] = $v->getV();
                    $index++;
                }
                $rows[] = $data;
            }

            return $rows;
        } catch (\Exception $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

}
