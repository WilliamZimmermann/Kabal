<?php
namespace Customer\Services;

class CustomerMessages
{

    public $code;

    public $params;

    public $logPriority;

    public function setCode($code, $params = null)
    {
        $this->code = $code;
        $this->params = $params;
    }

    public function getMessage()
    {
        if ($this->code != '') {
            switch ($this->code) {
                case "APPLICATION003":
                    $message["flag"] = 2;
                    $message["message"] = "Você não tem acesso a essa página. [APPLICATION003]";
                    $this->logPriority = 6;
                    break;
                // CUSTOMER MODULE
                case "CUSTOMER001":
                    $message["flag"] = 1;
                    $message["message"] = "Cliente cadastrado com sucesso. [CUSTOMER001]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar cliente. Tente novamente. [CUSTOMER002]";
                    $this->logPriority = 2;
                    break;
                case "CUSTOMER003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar cliente. Um ou mais campos possuem dados inválidos. Tente novamente. [CUSTOMER003]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER004":
                    $message["flag"] = 1;
                    $message["message"] = "Dados do cliente alterados com sucesso. [CUSTOMER004]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar dados do cliente. Tente novamente. [CUSTOMER005]";
                    $this->logPriority = 2;
                    break;
                case "CUSTOMER006":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar dados do cliente. Um ou mais campos possuem dados inválidos. Tente novamente. [CUSTOMER006]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER007":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar encontrar o cliente. [CUSTOMER007]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER008":
                    $message["flag"] = 1;
                    $message["message"] = "Cliente excluído com sucesso. [CUSTOMER008]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER009":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir o cliente. Tente novamente. [CUSTOMER009]";
                    $this->logPriority = 3;
                    break;
                case "CUSTOMER010":
                    $message["flag"] = 2;
                    $message["message"] = "Ação não executada. Esse email já está sendo usado por outro cliente. [CUSTOMER010]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER011":
                    $message["flag"] = 1;
                    $message["message"] = "Endereço do cliente cadastrado com sucesso. [CUSTOMER011]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER012":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar endereço do cliente. [CUSTOMER012]";
                    $this->logPriority = 2;
                    break;
                case "CUSTOMER013":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar/alterar endereço do cliente. Um ou mais campos possuem dados inválidos. [CUSTOMER013]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER014":
                    $message["flag"] = 1;
                    $message["message"] = "Endereço do cliente alterado com sucesso. [CUSTOMER014]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER015":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar endereço do cliente. [CUSTOMER015]";
                    $this->logPriority = 2;
                    break;
                case "CUSTOMER016":
                    $message["flag"] = 2;
                    $message["message"] = "Falha - esse endereço não existe. [CUSTOMER016]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER017":
                    $message["flag"] = 1;
                    $message["message"] = "Endereço removido com sucesso. [CUSTOMER017]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER018":
                    $message["flag"] = 1;
                    $message["message"] = "Houve um erro ao tentar excluir o endereço. [CUSTOMER018]";
                    $this->logPriority = 3;
                    break;
                case "CUSTOMER019":
                    $message["flag"] = 1;
                    $message["message"] = "Contato do cliente adicionado com sucesso. [CUSTOMER019]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER020":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar adicionar um contato ao cliente. [CUSTOMER020]";
                    $this->logPriority = 2;
                    break;
                case "CUSTOMER021":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar/alterar contato do cliente. Um ou mais campos possuem dados inválidos. [CUSTOMER021]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER022":
                    $message["flag"] = 1;
                    $message["message"] = "Contato do cliente alterado com sucesso. [CUSTOMER022]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER023":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar contato do cliente. [CUSTOMER023]";
                    $this->logPriority = 2;
                    break;
                case "CUSTOMER024":
                    $message["flag"] = 2;
                    $message["message"] = "Falha - esse contato não existe. [CUSTOMER024]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER025":
                    $message["flag"] = 1;
                    $message["message"] = "Contato removido com sucesso. [CUSTOMER025]";
                    $this->logPriority = 5;
                    break;
                case "CUSTOMER026":
                    $message["flag"] = 1;
                    $message["message"] = "Houve um erro ao tentar excluir o contato. [CUSTOMER026]";
                    $this->logPriority = 3;
                    break;
                default:
                    $message["flag"] = 2;
                    $message["message"] = "Mensagem desconhecida.";
                    $this->logPriority = 4;
                    break;
            }
            return $message;
        } else {
            $message["flag"] = 0;
            $message["message"] = "";
        }
    }

    public function getLogPriority()
    {
        return $this->logPriority;
    }
}

