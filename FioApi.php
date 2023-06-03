<?php

class FioApi
{
    private $token;
    
    public function __construct($token)
    {
        $this->token = $token;
    }
    
    public function getTransactions($startDate, $endDate)
    {
        $url = 'https://www.fio.cz/ib_api/rest/periods/' . $this->token . '/' . $startDate . '/' . $endDate . '/transactions.json';
        
        $response = file_get_contents($url);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            
            if (isset($data['accountStatement']['transactionList']['transaction'])) {
                return $data['accountStatement']['transactionList']['transaction'];
            } else {
                return [];
            }
        } else {
            return [];
        }
    }
}
?>
