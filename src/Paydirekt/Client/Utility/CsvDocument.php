<?php

namespace Paydirekt\Client\Utility;

/**
 * Parser and facility for accessing CSV documents.
 */
class CsvDocument
{
    private $header;
    private $data;

    /**
     * CsvDocument constructor.
     * <p>
     * Example-Input:
     * product_name,merchant_id,price
     * Bobbycar,1015,25.99
     * Helm,1015,18.53
     * <p>
     * @param string $csvDocumentAsString The whole csv document as one string.
     */
    public function __construct($csvDocumentAsString)
    {
        $csvLines = explode("\n", $csvDocumentAsString);
        $headline = array_shift($csvLines); //split headline from data
        $this->header = $this->parseCsvLine($headline);
        $csvLines = $this->removeEmptyLines($csvLines);
        $this->data = $this->parseCsvLines($this->header, $csvLines);
    }

    /**
     * Returns the data of the csv document easy accessible.
     * <p>
     * Example:
     * Array
     * (
     *     [0] => Array
     *         (
     *             [product_name] => Bobbycar
     *             [merchant_id] => 1015
     *             [price] => 25.99
     *         )
     *
     *     [1] => Array
     *         (
     *             [product_name] => Helm
     *             [merchant_id] => 1015
     *             [price] => 18.53
     *         )
     * )
     * <p>
     * @return array The csv data in lines of associative arrays.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the header of the csv document.
     * <p>
     * Example:
     * Array
     * (
     *     [0] => product_name
     *     [1] => merchant_id
     *     [2] => price
     * )
     * <p>
     * @return array The keys of csv document.
     */
    public function getHeader()
    {
        return $this->header;
    }

    private function removeEmptyLines($lines)
    {
        return array_filter($lines, function ($value) {
            return $value !== '';
        });
    }

    private function parseCsvLines($header, $csvLines)
    {
        $data = array_map('str_getcsv', $csvLines); //parse csv
        $csv = [];
        foreach ($data as $line) {
            $csv[] = array_combine($header, $line);
        }
        return $csv;
    }

    private function parseCsvLine($csvLineAsString)
    {
        if ($csvLineAsString == '')
        {
            return [];
        }

        return str_getcsv($csvLineAsString, ',', '"', "\\");
    }
}