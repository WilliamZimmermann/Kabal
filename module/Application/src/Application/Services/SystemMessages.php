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
                    $message["message"] = "Imagem ." . $this->params["id"] . " removida com sucesso do banco de dados. [DBIMAGE006]";
                    $this->logPriority = 5;
                    break;
                case "DBIMAGE007":
                    $message["flag"] = 1;
                    $message["message"] = "Falha ao tentar remover a imagem ." . $this->params["id"] . " do banco de dados [DBIMAGE007]";
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
                    $message["message"] = "Página (" . $this->params["id"] . ") excluída com sucesso. [PAGE007]";
                    $this->logPriority = 5;
                    break;
                case "PAGE008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a página (" . $this->params["id"] . "). [PAGE008]";
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
                    $message["message"] = "Página (" . $this->params["id"] . ") excluída com sucesso. [PAGEL007]";
                    $this->logPriority = 5;
                    break;
                case "PAGEL008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a página (" . $this->params["id"] . "). [PAGEL008]";
                    $this->logPriority = 3;
                    break;
                case "PAGEL009":
                    $message["flag"] = 1;
                    $message["message"] = "O conteúdo em todos os idiomas relacionados à Página (" . $this->params["id"] . ") foram excluídos com sucesso. [PAGEL009]";
                    $this->logPriority = 5;
                    break;
                // ARTICLES MODULE
                // -- CATEGORIES
                case "ACAT001":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria cadastrada com sucesso. Deseja cadastrar outra categoria? [ACAT001]";
                    $this->logPriority = 5;
                    break;
                case "ACAT002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar a categoria. Por favor, tente novamente. [ACAT002]";
                    $this->logPriority = 3;
                    break;
                case "ACAT003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar categoria. Um ou mais campos possuem dados inválidos. Tente novamente. [ACAT003]";
                    $this->logPriority = 5;
                    break;
                case "ACAT004":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria alterada com sucesso. [ACAT004]";
                    $this->logPriority = 5;
                    break;
                case "ACAT005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a categoria. Tente novamente. [ACAT005]";
                    $this->logPriority = 3;
                    break;
                case "ACAT006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos essa categoria no sistema. [ACAT006]";
                    $this->logPriority = 3;
                    break;
                case "ACAT007":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria (" . $this->params["id"] . ") excluída com sucesso. [ACAT007]";
                    $this->logPriority = 5;
                    break;
                case "ACAT008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a categoria (" . $this->params["id"] . "). [ACAT008]";
                    $this->logPriority = 3;
                    break;
                case "ACATL001":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria cadastrada com sucesso. Deseja cadastrar outra categoria? [ACATL001]";
                    $this->logPriority = 5;
                    break;
                case "ACATL002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar categoria. Por favor, tente novamente. [ACATL002]";
                    $this->logPriority = 3;
                    break;
                case "ACATL003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar categoria. Um ou mais campos possuem dados inválidos. Tente novamente. [ACATL003]";
                    $this->logPriority = 5;
                    break;
                case "ACATL004":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria alterada com sucesso. [ACATL004]";
                    $this->logPriority = 5;
                    break;
                case "ACATL005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar a categoria. Tente novamente. [ACATL005]";
                    $this->logPriority = 3;
                    break;
                case "ACATL006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos essa categoria no sistema. [ACATL006]";
                    $this->logPriority = 3;
                    break;
                case "ACATL007":
                    $message["flag"] = 1;
                    $message["message"] = "Categoria (" . $this->params["id"] . ") excluída com sucesso. [ACATL007]";
                    $this->logPriority = 5;
                    break;
                case "ACATL008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir a categoria (" . $this->params["id"] . "). [ACATL008]";
                    $this->logPriority = 3;
                    break;
                case "ACATL009":
                    $message["flag"] = 1;
                    $message["message"] = "O conteúdo em todos os idiomas relacionados à Categoria (" . $this->params["id"] . ") foi excluído com sucesso. [ACATL009]";
                    $this->logPriority = 5;
                    break;
                // --ARTICLES
                case "ART001":
                    $message["flag"] = 1;
                    $message["message"] = "Artigo cadastrado com sucesso. [ART001]";
                    $this->logPriority = 5;
                    break;
                case "ART002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar artigo. Por favor, tente novamente. [ART002]";
                    $this->logPriority = 3;
                    break;
                case "ART003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar artigo. Um ou mais campos possuem dados inválidos. Tente novamente. [ART003]";
                    $this->logPriority = 5;
                    break;
                case "ART004":
                    $message["flag"] = 1;
                    $message["message"] = "Artigo alterado com sucesso. [ART004]";
                    $this->logPriority = 5;
                    break;
                case "ART005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar o artigo. Tente novamente. [ART005]";
                    $this->logPriority = 3;
                    break;
                case "ART006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos esse artigo no sistema. [ART006]";
                    $this->logPriority = 3;
                    break;
                case "ART007":
                    $message["flag"] = 1;
                    $message["message"] = "Artigo (" . $this->params["id"] . ") excluído com sucesso. [ART007]";
                    $this->logPriority = 5;
                    break;
                case "ART008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir o artigo (" . $this->params["id"] . "). [ART008]";
                    $this->logPriority = 3;
                    break;
                case "ARTL001":
                    $message["flag"] = 1;
                    $message["message"] = "Artigo cadastrado com sucesso. Deseja cadastrar outro artigo? [ARTL001]";
                    $this->logPriority = 5;
                    break;
                case "ARTL002":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar cadastrar artigo. Por favor, tente novamente. [ARTL002]";
                    $this->logPriority = 3;
                    break;
                case "ARTL003":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao cadastrar/alterar artigo. Um ou mais campos possuem dados inválidos. Tente novamente. [ARTL003]";
                    $this->logPriority = 5;
                    break;
                case "ARTL004":
                    $message["flag"] = 1;
                    $message["message"] = "Artigo alterado com sucesso. [ARTL004]";
                    $this->logPriority = 5;
                    break;
                case "ARTL005":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar alterar o artigo. Tente novamente. [ARTL005]";
                    $this->logPriority = 3;
                    break;
                case "ARTL006":
                    $message["flag"] = 2;
                    $message["message"] = "Não encontramos esse artigo no sistema. [ARTL006]";
                    $this->logPriority = 3;
                    break;
                case "ARTL007":
                    $message["flag"] = 1;
                    $message["message"] = "Artigo (" . $this->params["id"] . ") excluído com sucesso. [ARTL007]";
                    $this->logPriority = 5;
                    break;
                case "ARTL008":
                    $message["flag"] = 2;
                    $message["message"] = "Houve um erro ao tentar excluir o artigo (" . $this->params["id"] . "). [ARTL008]";
                    $this->logPriority = 3;
                    break;
                case "ARTL009":
                    $message["flag"] = 1;
                    $message["message"] = "O conteúdo em todos os idiomas relacionados ao Artigo (" . $this->params["id"] . ") foi excluído com sucesso. [ARTL009]";
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
                    $message["message"] = "Falha ao tentar alterar as permissões do usuário " . $this->params["id"] . " Houve uma falha ao tentar remover as permissões anteriores. [USER016]";
                    $this->logPriority = 3;
                    break;
                case "USER017":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar remover o usuário " . $this->params["id"] . " Houve uma falha ao tentar remover as permissões dele. [USER017]";
                    $this->logPriority = 2;
                    break;
                case "USER018":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar remover o usuário " . $this->params["id"] . " Houve uma falha ao tentar remover o relacionamento dele com outros websites. [USER018]";
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
                    $message["message"] = "Módulo adicionado com sucesso ao Website. [WEBSITE011]";
                    $this->logPriority = 5;
                    break;
                case "WEBSITE012":
                    $message["flag"] = 2;
                    $message["message"] = "Falha ao tentar remover o(s) módulo(s) do website. [WEBSITE012]";
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

