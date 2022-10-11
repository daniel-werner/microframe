<?php

namespace Microframe\Utils;

class ParseCsv {

    private $delimiter = ',';
    private $filename;
    private $header;
    private $data=[];
    private $row_count = 0;

    public function __construct($filename='', $delimiter = ',')
    {
        $this->delimiter = $delimiter;
        if ($filename != '') {
        $this->setFile($filename);
        }
    }

    public function setFile($filename)
    {
        if (!file_exists($filename)) {

        return false;
        } elseif (!is_readable($filename)) {

        return false;
        }

        $this->filename = $filename;

        return true;
    }

    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function getDelimiter()
    {
        return $this->delimiter;
    }

    public function parse()
    {
        if(!isset($this->filename)) {

        return false;
        }

        // clear any previous results
        $this->reset();

        $file = fopen($this->filename, 'r');
        while (!feof($file)) {
        $row = fgetcsv($file, 0, self::$delimiter);
        if ($row == [NULL] || $row === FALSE) { continue; }
        if (!$this->header) {
            $this->header = $row;
        } else {
            $this->data[] = array_combine($this->header, $row);
            $this->row_count++;
            }
        }

        fclose($file);

        return $this->data;
    }

    public function lastResults()
    {
        return $this->data;
    }

    public function rowCount()
    {
        return $this->row_count;
    }

    private function reset()
    {
        $this->header = NULL;
        $this->data = [];
        $this->row_count = 0;
    }

}
