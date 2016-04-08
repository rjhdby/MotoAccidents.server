<?php

abstract class Core
{
    protected $prerequisites;
    protected $result;
    protected $error        = false;
    protected $errorDetails = "";

    const OK             = 'OK';
    const PREREQUISITES  = 'PREREQ';
    const USER_UNKNOWN   = 'USER UNKNOWN';
    const CREDENTIALS    = 'CREDENTIALS';
    const WRONG_METHOD   = 'WRONG METHOD';
    const CREATE_TIMEOUT = 'CREATE TIMEOUT';
    const DATABASE       = 'SERVER ERROR';
    const RIGHTS         = 'INSUFFICIENT RIGHTS';

    /**
     * @param array $data
     */
    protected function setPrerequisites($data)
    {
        $this->prerequisites = $data;
    }

    /**
     * @param array $data
     * @param array $prerequisites
     * @return bool
     */
    protected function checkPrerequisites($data, $prerequisites = array())
    {
        if (count($prerequisites) != 0) $this->setPrerequisites($prerequisites);
        foreach ($this->prerequisites as $key) {
            if (!isset($data[ $key ])) {
                $this->error        = self::PREREQUISITES;
                $this->errorDetails = $key;

                return false;
            }
        }

        return true;
    }

    /**
     * @param array $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        if ($this->error) return $this->returnError();

        return array('r' => $this->result);
    }

    /**
     * @return array
     */
    protected function returnError()
    {
        return array('e' => $this->error, 'd' => $this->errorDetails);
    }

    /**
     * @return boolean|string
     */
    public function getError()
    {
        return $this->error;
    }
}