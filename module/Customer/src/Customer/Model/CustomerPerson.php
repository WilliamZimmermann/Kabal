<?php
namespace Customer\Model;

class CustomerPerson
{

    public $customer_id;

    public $name;

    public $last_name;

    public $document_1;

    public $document_2;

    public function exchangeArray($data)
    {
        $this->customer_id = (! empty($data['customer_id'])) ? $data['customer_id'] : null;
        $this->name = (! empty($data['name'])) ? strip_tags($data['name']) : null;
        $this->last_name = (! empty($data['last_name'])) ? strip_tags($data['last_name']) : null;
        $this->document_1 = (! empty($data['document_1'])) ? strip_tags($data['document_1']) : null;
        $this->document_2 = (! empty($data['social_name'])) ? strip_tags($data['document_2']) : null;
    }

    public function validation()
    {
        $stringValidator = new \Zend\Validator\StringLength();
        $stringValidator->setMax(50);
        $stringValidator->setMin(2);
     
        // Validate Name
        if (! $stringValidator->isValid($this->name)) {
            return false;
        }
        // Validate Last name
        $stringValidator->setMax(60);
        if (! $stringValidator->isValid($this->last_name)) {
            return false;
        }
        
        return true;
    }

    /**
     * Validador de CPF
     * @param string $cpf
     * @return boolean
     */
    public static function cpfValidator($cpf)
    {
        $invalidos = array('00000000000',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999');
        if (in_array($cpf, $invalidos)){
            return false;
        }
        $cpf = preg_replace('/[^0-9]/', '', (string) $cpf);
    	// Valida tamanho
    	if (strlen($cpf) != 11)
    		return false;
    	// Calcula e confere primeiro dígito verificador
    	for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
    		$soma += $cpf{$i} * $j;
    	$resto = $soma % 11;
    	if ($cpf{9} != ($resto < 2 ? 0 : 11 - $resto))
    		return false;
    	// Calcula e confere segundo dígito verificador
    	for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
    		$soma += $cpf{$i} * $j;
    	$resto = $soma % 11;
    	return $cpf{10} == ($resto < 2 ? 0 : 11 - $resto);
    }
}

