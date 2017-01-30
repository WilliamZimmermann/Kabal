<?php
namespace Product\Services;

class ProductMessages
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
                // -- COLORS
                case "PCOL001":
                    $message["flag"] = 1;
                    $message["message"] = "Cor cadastrada com sucesso. Deseja cadastrar outra cor? [PCOL001]";
                    $this->logPriority = 5;
                    break;
                case "PCOL002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a cor. Por favor, tente novamente. [PCOL002]";
                    $this->logPriority = 3;
                    break;
                case "PCOL003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar a cor. Um ou mais campos possuem dados inválidos. Tente novamente. [PCOL003]";
                    $this->logPriority = 5;
                    break;
                case "PCOL004":
                    $message["flag"] = 1;
                    $message["message"] = "Cor alterada com sucesso. [PCOL004]";
                    $this->logPriority = 5;
                    break;
                case "PCOL005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a cor. Tente novamente. [PCOL005]";
                    $this->logPriority = 3;
                    break;
                case "PCOL006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos essa cor no sistema. [PCOL006]";
                    $this->logPriority = 3;
                    break;
                case "PCOL007":
                    $message["flag"] = 1;
                    $message["message"] = "Cor (" . $this->params["id"] . ") excluída com sucesso. [PCOL007]";
                    $this->logPriority = 5;
                    break;
                case "PCOL008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a cor (" . $this->params["id"] . "). [PCOL008]";
                    $this->logPriority = 3;
                    break;
                // -- CATEGORIES
                case "PCAT001":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria cadastrada com sucesso. Deseja cadastrar outra categoria? [PCAT001]";
                    $this->logPriority = 5;
                    break;
                case "PCAT002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar a categoria. Por favor, tente novamente. [PCAT002]";
                    $this->logPriority = 3;
                    break;
                case "PCAT003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar categoria. Um ou mais campos possuem dados inválidos. Tente novamente. [PCAT003]";
                    $this->logPriority = 5;
                    break;
                case "PCAT004":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria alterada com sucesso. [PCAT004]";
                    $this->logPriority = 5;
                    break;
                case "PCAT005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a categoria. Tente novamente. [PCAT005]";
                    $this->logPriority = 3;
                    break;
                case "PCAT006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos essa categoria no sistema. [PCAT006]";
                    $this->logPriority = 3;
                    break;
                case "PCAT007":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria (" . $this->params["id"] . ") excluída com sucesso. [PCAT007]";
                    $this->logPriority = 5;
                    break;
                case "PCAT008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a categoria (" . $this->params["id"] . "). [PCAT008]";
                    $this->logPriority = 3;
                    break;
                case "PCATL001":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria cadastrada com sucesso. Deseja cadastrar outra categoria? [PCATL001]";
                    $this->logPriority = 5;
                    break;
                case "PCATL002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar categoria. Por favor, tente novamente. [PCATL002]";
                    $this->logPriority = 3;
                    break;
                case "PCATL003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar categoria. Um ou mais campos possuem dados inválidos. Tente novamente. [PCATL003]";
                    $this->logPriority = 5;
                    break;
                case "PCATL004":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria alterada com sucesso. [PCATL004]";
                    $this->logPriority = 5;
                    break;
                case "PCATL005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a categoria. Tente novamente. [PCATL005]";
                    $this->logPriority = 3;
                    break;
                case "PCATL006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos essa categoria no sistema. [PCATL006]";
                    $this->logPriority = 3;
                    break;
                case "PCATL007":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria (" . $this->params["id"] . ") excluída com sucesso. [PCATL007]";
                    $this->logPriority = 5;
                    break;
                case "PCATL008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a categoria (" . $this->params["id"] . "). [PCATL008]";
                    $this->logPriority = 3;
                    break;
                case "PCATL009":
                    $message["flag"] = 1;
                    $message["message"] = "O conteúdo em todos os idiomas relacionados à Categoria (" . $this->params["id"] . ") foi excluído com sucesso. [PCATL009]";
                    $this->logPriority = 5;
                // PRODUCT MODULE
                case "PRO001":
                    $message["flag"] = 1;
                    $message["message"] = "Produto cadastrado com sucesso. [PRO001]";
                    $this->logPriority = 5;
                    break;
                case "PRO002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar produto. Tente novamente. [PRO002]";
                    $this->logPriority = 2;
                    break;
                case "PRO003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar produto. Um ou mais campos possuem dados inválidos. Tente novamente. [PRO003]";
                    $this->logPriority = 5;
                    break;
                case "PRO004":
                    $message["flag"] = 1;
                    $message["message"] = "Dados do produto alterados com sucesso. [PRO004]";
                    $this->logPriority = 5;
                    break;
                case "PRO005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar dados do produto. Tente novamente. [PRO005]";
                    $this->logPriority = 2;
                    break;
                case "PRO006":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar dados do produto. Um ou mais campos possuem dados inválidos. Tente novamente. [PRO006]";
                    $this->logPriority = 5;
                    break;
                case "PRO007":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar encontrar o produto. [PRO007]";
                    $this->logPriority = 5;
                    break;
                case "PRO008":
                    $message["flag"] = 1;
                    $message["message"] = "Produto excluído com sucesso. [PRO008]";
                    $this->logPriority = 5;
                    break;
                case "PRO009":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir o produto. Tente novamente. [PRO009]";
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

