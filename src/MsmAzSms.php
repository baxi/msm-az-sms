<?php

namespace baxi\MsmAzSms;

class MsmAzSms {

    private $apiUrl = "https://api.msm.az/sendsms";

    private $username = "";
    private $password = "";
    private $from = "";
    private $message = "";

    private $to = [];

    public function __construct()
    {
    }

    public function sendSms()
    {

        if (sizeof($this->to) == 0) throw new \Exception("No numbers provided");
        if (empty($this->getUsername())) throw new \Exception("No username provided");
        if (empty($this->getPassword())) throw new \Exception("No password provided");
        if (empty($this->getMessage())) throw new \Exception("No message provided");

        $xml_begin = sprintf('<SMS-InsRequest><CLIENT user="%s" pwd="%s" from="%s"/><INSERTMSG text="%s">', $this->getUsername(), $this->getPassword(), $this->getFrom(), $this->getMessage());

        $xml_to = '';
        foreach ($this->getTo() as $number) {
            $xml_to .= '<TO>'.$number.'</TO>';
        }

        $xml_end = '</INSERTMSG></SMS-InsRequest>';


        $xml = $xml_begin.$xml_to.$xml_end;

        $headers = array(
            "Content-type: text/xml",
            "Content-length: " . strlen($xml),
            "Connection: close",
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getApiUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $data = curl_exec($ch);

        if(curl_errno($ch))
            print curl_error($ch);
        else
            curl_close($ch);
    }

    /**
     * Get the array of numbers
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Clears $to numbers and set it to passed argument
     *
     * @param array|string $to
     */
    public function setTo($to)
    {
        if (is_string($to)) {
            $to = [$to];    // Convert to an array
        }

        $normalized_numbers = [];

        foreach ($to as $number) {
            if ($normalized_number = $this->normalizeNumber($number)) {
                $normalized_numbers[] = $normalized_number;
            }
            else {
                throw new \Exception(sprintf("Number %s is invalid", $number));
            }

        }
        $this->to = $normalized_numbers;
    }

    /**
     * Add $to number to list of recipients
     *
     * @param String $to
     */
    public function addTo($to)
    {
        $this->to[] = $this->normalizeNumber($to);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set sender name. Must be registered with MSM
     *
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    private function normalizeNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        switch (strlen($number)) {

            case 9:     // 551112233
                $number = '0' . $number;
                break;

            case 10:     // 0551112233
                // The format is ok. No need to normalize
                break;

            case 12:     // 994551112233
                // Remove 994 and add 0 in front of number
                $number = substr($number, 3);
                $number = '0' . $number;
                break;

            case 13:     // +994551112233
                // Remove +994 and add 0 in front of number
                $number = substr($number, 4);
                $number = '0' . $number;
                break;

            default:
                $number = '';
        }

        return $number;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * TODO: Complete this function
     *
     * @param $number
     */
    private function isValidNumber($number)
    {
        switch (strlen($number)) {

            case 9:     // 551112233
                // Add 0 in front of line
                break;

            case 10:     // 0551112233
                break;

            case 12:     // 994551112233
                break;

            case 13:     // +994551112233
                break;

        }
    }
}
