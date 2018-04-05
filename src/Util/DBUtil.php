<?php
/**
 * Created by PhpStorm.
 * User: Eduardo Luttinger
 * Date: 12/06/2017
 * Time: 04:40 PM
 *
 *
 * Reaxium DB utilities
 *
 *
 */
namespace App\Util;

use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

class DBUtil{

    /**
     *
     *
     *  Execute a query raw contained in a sql file by its file name.
     * the file most be located inside a sql folder at the same level of cakephp config folder.
     *
     * this method will return an object array of rows (key-value) based on the result of the query.
     *
     * User: Eduardo Luttinger
     *
     *
     *
     * @param $sqlFileName
     * @param null $parameters
     * @return null
     * @throws \Exception
     */
    public function executeSQLFile($sqlFileName, $parameters = null)
    {
        $result = null;
        Log::debug("FileName to execute: " . $sqlFileName);
        Log::debug("Params to execute: " . json_encode($parameters));
        try {

            $connectionName = 'default';
            $connection = ConnectionManager::get($connectionName);
            $query = GE3PUtil::getSQLFileContent($sqlFileName);
            //$filePath = "/var/www/html/mobile_citation_cloud/sql/";
            //$query = ReaxiumUtil::getSQLFileContentWithPath($filePath, $sqlFileName);
            Log::debug("Query: " . $query);
            if (isset($parameters)) {
                $statement = $connection->execute($query, $parameters);
            } else {
                $statement = $connection->execute($query);
            }
            $result = $statement->fetchAll('assoc');
        } catch (\Exception $e) {
            Log::info("Error executing the sql file name: " . $sqlFileName);
            throw $e;
        } finally {
            if (isset($connection)) {
                //TODO: handle a connection close process only if it is necessary.
            }
        }
        return $result;
    }


}