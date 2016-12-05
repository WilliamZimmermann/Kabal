<?php
namespace Application\Services;

class SystemMessages
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
                // COMPANY MODULE
                case "COMPANY001":
                    $message["flag"] = 1;
                    $message["message"] = "Empresa cadastrada com sucesso. Deseja cadastrar outra empresa? [COMPANY001]";
                    $this->logPriority = 5;
                    break;
                case "COMPANY002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar emrpesa. Tente novamente. [COMPANY002]";
                    $this->logPriority = 2;
                    break;
                case "COMPANY003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar emrpesa. Um ou mais campos possuem dados inválidos. Tente novamente. [COMPANY003]";
                    $this->logPriority = 5;
                    break;
                case "COMPANY004":
                    $message["flag"] = 1;
                    $message["message"] = "Empresa alterada com sucesso. [COMPANY004]";
                    $this->logPriority = 5;
                    break;
                case "COMPANY005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar emrpesa. Tente novamente. [COMPANY005]";
                    $this->logPriority = 2;
                    break;
                case "COMPANY006":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar a emrpesa. Um ou mais campos possuem dados inválidos. Tente novamente. [COMPANY006]";
                    $this->logPriority = 5;
                    break;
                case "COMPANY007":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar encontrar a empresa. [COMPANY007]";
                    $this->logPriority = 5;
                    break;
                case "COMPANY008":
                    $message["flag"] = 1;
                    $message["message"] = "Empresa excluída com sucesso. [COMPANY008]";
                    $this->logPriority = 5;
                    break;
                case "COMPANY009":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a empresa. Tente novamente. [COMPANY009]";
                    $this->logPriority = 3;
                    break;
                // DATABASE IMAGE MODULE
                case "DBIMAGE001":
                    $message["flag"] = 1;
                    $message["message"] = "Imagem cadastrada com sucesso. [DBIMAGE001]";
                    $this->logPriority = 5;
                    break;
                case "DBIMAGE002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar imagem no banco de dados. [DBIMAGE002]";
                    $this->logPriority = 2;
                    break;
                case "DBIMAGE003":
                    $message["flag"] = 1;
                    $message["message"] = "Imagem alterada com sucesso. [DBIMAGE003]";
                    $this->logPriority = 5;
                    break;
                case "DBIMAGE004":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar imagem no banco de dados. [DBIMAGE004]";
                    $this->logPriority = 2;
                    break;
                case "DBIMAGE005":
                    $message["flag"] = 1;
                    $message["message"] = "Imagem não encontrada no sistema. [DBIMAGE005]";
                    $this->logPriority = 5;
                    break;
                case "DBIMAGE006":
                    $message["flag"] = 1;
                    $message["message"] = "Imagem .".$this->params["id"]." removida com sucesso do banco de dados. [DBIMAGE006]";
                    $this->logPriority = 5;
                    break;
                case "DBIMAGE007":
                    $message["flag"] = 1;
                    $message["message"] = "Falha ao tentar remover a imagem .".$this->params["id"]." do banco de dados [DBIMAGE007]";
                    $this->logPriority = 2;
                    break;
                // PAGE MODULE
                case "PAGE001":
                    $message["flag"] = 1;
                    $message["message"] = "Página cadastrada com sucesso. Deseja cadastrar outra página? [PAGE001]";
                    $this->logPriority = 5;
                    break;
                case "PAGE002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar página. Por favor, tente novamente. [PAGE002]";
                    $this->logPriority = 3;
                    break;
                case "PAGE003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar página. Um ou mais campos possuem dados inválidos. Tente novamente. [PAGE003]";
                    $this->logPriority = 5;
                    break;
                case "PAGE004":
                    $message["flag"] = 1;
                    $message["message"] = "Página alterada com sucesso. [PAGE004]";
                    $this->logPriority = 5;
                    break;
                case "PAGE005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a página. Tente novamente. [PAGE005]";
                    $this->logPriority = 3;
                    break;
                case "PAGE006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos essa página no sistema. [PAGE006]";
                    $this->logPriority = 3;
                    break;
                case "PAGE007":
                    $message["flag"] = 1;
                    $message["message"] = "Página (".$this->params["id"].") excluída com sucesso. [PAGE007]";
                    $this->logPriority = 5;
                    break;
                case "PAGE008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a página (".$this->params["id"]."). [PAGE008]";
                    $this->logPriority = 3;
                    break;
                    case "PAGEL001":
                        $message["flag"] = 1;
                        $message["message"] = "Página cadastrada com sucesso. Deseja cadastrar outra página? [PAGEL001]";
                        $this->logPriority = 5;
                        break;
                    case "PAGEL002":
                        $message["flag"] = 2;
                        $message["message"] = "Falha ao tentar cadastrar página. Por favor, tente novamente. [PAGEL002]";
                        $this->logPriority = 3;
                        break;
                    case "PAGEL003":
                        $message["flag"] = 2;
                        $message["message"] = "Falha ao cadastrar/alterar página. Um ou mais campos possuem dados inválidos. Tente novamente. [PAGEL003]";
                        $this->logPriority = 5;
                        break;
                    case "PAGEL004":
                        $message["flag"] = 1;
                        $message["message"] = "Página alterada com sucesso. [PAGEL004]";
                        $this->logPriority = 5;
                        break;
                    case "PAGEL005":
                        $message["flag"] = 2;
                        $message["message"] = "Falha ao tentar alterar a página. Tente novamente. [PAGEL005]";
                        $this->logPriority = 3;
                        break;
                    case "PAGEL006":
                        $message["flag"] = 2;
                        $message["message"] = "Não encontramos essa página no sistema. [PAGEL006]";
                        $this->logPriority = 3;
                        break;
                    case "PAGEL007":
                        $message["flag"] = 1;
                        $message["message"] = "Página (".$this->params["id"].") excluída com sucesso. [PAGEL007]";
                        $this->logPriority = 5;
                        break;
                    case "PAGEL008":
                        $message["flag"] = 2;
                        $message["message"] = "Houve um erro ao tentar excluir a página (".$this->params["id"]."). [PAGEL008]";
                        $this->logPriority = 3;
                        break;
                    case "PAGEL009":
                        $message["flag"] = 1;
                        $message["message"] = "O conteúdo em todos os idiomas relacionados à Página (".$this->params["id"].") foram excluídos com sucesso. [PAGEL009]";
                        $this->logPriority = 5;
                        break;
                // USERS MODULE
                case "USER001":
                    $message["flag"] = 1;
                    $message["message"] = "Usuário cadastrado com sucesso. Deseja cadastrar outro usuário? [USER001]";
                    $this->logPriority = 5;
                    break;
                case "USER002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar usuário. Por favor, tente novamente. [USER002]";
                    $this->logPriority = 3;
                    break;
                case "USER003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar usuário. Esse usuário já está cadatrado em nosso banco de dados. [USER003]";
                    $this->logPriority = 5;
                    break;
                case "USER004":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar usuário. Um ou mais campos possuem dados inválidos. Tente novamente. [USER004]";
                    $this->logPriority = 5;
                    break;
                case "USER005":
                    $message["flag"] = 1;
                    $message["message"] = "Usuário alterado com sucesso. [USER005]";
                    $this->logPriority = 5;
                    break;
                case "USER006":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar o usuário. Tente novamente. [USER006]";
                    $this->logPriority = 3;
                    break;
                case "USER007":
                    $message["flag"] = 2;
                    $message["message"] = "Este e-mail já está cadastrado para outro usuário no sistema. Use um email diferente. [USER007]";
                    $this->logPriority = 5;
                    break;
                case "USER008":
                    $message["flag"] = 2;
                    $message["message"] = "Nenhum usuário com este ID foi encontrado no sistema. [USER008]";
                    $this->logPriority = 5;
                    break;
                case "USER009":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao editar o usuário. Um ou mais campos possuem dados inválidos. Tente novamente. [USER009]";
                    $this->logPriority = 5;
                    break;
                case "USER010":
                    $message["flag"] = 1;
                    $message["message"] = "Usuário excluído com sucesso. [USER010]";
                    $this->logPriority = 5;
                    break;
                case "USER011":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir o usuário. Tente novamente. [USER011]";
                    $this->logPriority = 3;
                    break;
                case "USER012":
                    $message["flag"] = 1;
                    $message["message"] = "Senha do usuário " . $this->params["id"] . " alterada com sucesso. [USER012]";
                    $this->logPriority = 5;
                    break;
                case "USER013":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a senha do usuário " . $this->params["id"] . ". [USER013]";
                    $this->logPriority = 3;
                    break;
                case "USER014":
                    $message["flag"] = 1;
                    $message["message"] = "Permissões do usuário " . $this->params["id"] . " alteradas com sucesso. [USER014]";
                    $this->logPriority = 5;
                    break;
                case "USER015":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar as permissões do usuário " . $this->params["id"] . ". [USER015]";
                    $this->logPriority = 3;
                    break;
                case "USER016":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar as permissões do usuário " . $this->params["id"] ." Houve uma falha ao tentar remover as permissões anteriores. [USER016]";
                    $this->logPriority = 3;
                    break;
                case "USER017":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar remover o usuário " . $this->params["id"] ." Houve uma falha ao tentar remover as permissões dele. [USER017]";
                    $this->logPriority = 2;
                    break;
                case "USER018":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar remover o usuário " . $this->params["id"] ." Houve uma falha ao tentar remover o relacionamento dele com outros websites. [USER018]";
                    $this->logPriority = 2;
                    break;
                // WEBSITE MODULE
                case "WEBSITE001":
                    $message["flag"] = 1;
                    $message["message"] = "Website cadastrado com sucesso. Deseja cadastrar outro website? [WEBSITE001]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar website. Tente novamente. [WEBSITE002]";
                    $this->logPriority = 3;
                    break;
                case "WEBSITE003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar website. Um ou mais campos possuem dados inválidos. Tente novamente. [WEBSITE003]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE004":
                    $message["flag"] = 1;
                    $message["message"] = "Website alterado com sucesso. [WEBSITE004]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar website. Tente novamente. [WEBSITE005]";
                    $this->logPriority = 3;
                    break;
                case "WEBSITE006":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao alterar o website. Um ou mais campos possuem dados inválidos. Tente novamente. [WEBSITE006]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE007":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar encontrar o website. [WEBSITE007]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE008":
                    $message["flag"] = 1;
                    $message["message"] = "Website excluído com sucesso. [WEBSITE008]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE009":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir o website. Tente novamente. [WEBSITE009]";
                    $this->logPriority = 3;
                    break;
                case "WEBSITE010":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir o website, não foi possível remover os módulos relacionados. Tente novamente. [WEBSITE010]";
                    $this->logPriority = 3;
                    break;
                case "WEBSITE011":
                    $message["flag"] = 1;
                    $message["message"] = "Módulos do website alterados com sucesso. [WEBSITE011]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE012":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar os módulos do website. [WEBSITE012]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE013":
                    $message["flag"] = 1;
                    $message["message"] = "Usuário adicionado com sucesso ao website. [WEBSITE013]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE014":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar adicionar usuário ao website. Talvez ele já esteja cadastrado neste website. [WEBSITE014]";
                    $this->logPriority = 3;
                    break;
                case "WEBSITE015":
                    $message["flag"] = 2;
                    $message["message"] = "Falha de validação. Verifique se os dados estão corretos. [WEBSITE015]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE016":
                    $message["flag"] = 1;
                    $message["message"] = "Relacionamento entre usuário e website excluído com sucesso. [WEBSITE016]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE017":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar remover o relacionamento entre usuário e website. [WEBSITE017]";
                    $this->logPriority = 3;
                    break;
                case "WEBSITE018":
                    $message["flag"] = 1;
                    $message["message"] = "Idioma(s) adicionados ao website. [WEBSITE018]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE019":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar adicionar um ou mais idiomas ao website. [WEBSITE019]";
                    $this->logPriority = 3;
                    break;
                case "WEBSITE020":
                    $message["flag"] = 1;
                    $message["message"] = "Idioma(s) removido(s) do site. [WEBSITE020]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE021":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar remover idioma do site. [WEBSITE021]";
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

