<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");

/*
 * (c) payFurl
 */
class Charge
{
    private $Action;
    private $ChargeData;
    private $PaymentInformation;
    private $ActionMap = array("charge_card" => "POST");

    public function CreateWithCard($Amount, $Currency, $ProviderId, $Reference, $CardNumber, $ExpiryDate, $Ccv, $Cardholder)
    {
        $this->Action = "charge_card";
        $this->ChargeData = ["amount" => $Amount, "currency" => $Currency, "providerId" => $ProviderId, "reference" => $Reference];
        $this->PaymentInformation = ["cardNumber" => $CardNumber, "expiryDate" => $ExpiryDate, "ccv" => $Ccv, "cardholder" => $Cardholder];
        return $this;
    }

    public function Call()
    {
        $Endpoint = $this->CreateEndpoint();
        $Data = $this->CreateJson();

        return HttpWrapper::CallApi($Endpoint, $this->ActionMap[$this->Action], $Data);
    }

    private function CreateEndpoint()
    {
        switch ($this->Action)
        {
            case "charge_card":
                return "/charge/card";
        }
    }

    private function CreateJson()
    {
        switch ($this->Action)
        {
            case "charge_card":
                $Data = [
                    'amount'        => $this->ChargeData["amount"],
                    'currency'      => $this->ChargeData["currency"],
                    'providerId'    => $this->ChargeData["providerId"],
                    'reference'     => $this->ChargeData["reference"],
                    'paymentInformation' => [
                        'cardNumber' => $this->PaymentInformation["cardNumber"],
                        'expiryDate' => $this->PaymentInformation["expiryDate"],
                        'ccv' => $this->PaymentInformation["ccv"],
                        'cardholder' => $this->PaymentInformation["cardholder"]
                    ]
                ];
        }

        $Data = ArrayTools::CleanEmpty($Data);

        return json_encode($Data);
    }
}