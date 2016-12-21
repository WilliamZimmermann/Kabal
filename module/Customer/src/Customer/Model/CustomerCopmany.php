<?php
namespace Customer\Model;

class CustomerCompany
{

    public $customer_id;

    public $social_name;

    public $fantasy_name;

    public $document_1;

    public $document_2;

    public function exchangeArray($data)
    {
        $this->customer_id = (! empty($data['customer_id'])) ? $data['customer_id'] : null;
        $this->social_name = (! empty($data['social_name'])) ? strip_tags($data['social_name']) : null;
        $this->fantasy_name = (! empty($data['fantasy_name'])) ? strip_tags($data['fantasy_name']) : null;
        $this->document_1 = (! empty($data['document_1'])) ? strip_tags($data['document_1']) : null;
        $this->document_2 = (! empty($data['social_name'])) ? strip_tags($data['document_2']) : null;
    }

    public function validation()
    {
        if (! $this->customer_id) {
            return false;
        }
        
        $stringValidator = new \Zend\Validator\StringLength();
        $stringValidator->setMax(100);
        $stringValidator->setMin(2);
        
        // Validate Social Name
        if (! $stringValidator->isValid($this->social_name)) {
            return false;
        }
        // Validate Fantasy Name
        if (! $stringValidator->isValid($this->fantasy_name)) {
            return false;
        }
        
        return true;
    }

    /**
     * Validador de CNPJ
     * @param string $cnpj
     * @return boolean
     */
    public static function cnpjValidator($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14){
            return false;
        }    
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i ++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
            // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i ++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }
}

