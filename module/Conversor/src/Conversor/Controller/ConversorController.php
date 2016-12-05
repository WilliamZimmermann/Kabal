<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Conversor for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Conversor\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ConversorController extends AbstractActionController
{
    protected $moduleId = 6;
    
    public function indexAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
        ->get('user')
        ->getUserSession();
        $permission = $this->getServiceLocator()
        ->get('permissions')
        ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "insert") || $logedUser["idCompany"] == 1) {
                $request = $this->getRequest();
                if($request->isPost()){
                    $files = $request->getFiles()->toArray();
                    $temp = explode(".", $files["arquivo"]["name"]);
                    $ext = end($temp);
                    if($ext == "txt"){
                        $newName = date('YmdHis');
                        $newfilename = $newName . '.' . $ext;
                        $diretorio = "public/files_database/" . $logedUser["idWebsite"] . "/" . $newfilename;
                        //move_uploaded_file($_FILES["arquivo"]["tmp_name"], $diretorio);
                        //echo file_get_contents($_FILES["arquivo"]["tmp_name"]);
                        //$arquivo = fopen($_FILES["arquivo"]["tmp_name"]);
                        
                        //$fh = fopen($_FILES["arquivo"]["tmp_name"], 'r');
                        /*
                        $newText = array();
                        foreach(file($_FILES["arquivo"]["tmp_name"]) as $line){
                            echo substr($line, 85, 20);
                            echo "<br><br>";
                            //str_replace("2019", "196", $line);
                            //substr_replace($line, $replacement, $start)
                            //echo $line."<br><br>";
                            //$newText[] = $line;
                        }
                        */
                        $file = new \SplFileObject($_FILES["arquivo"]["tmp_name"]);
                        $dataNew = "";
                        $dataNew2 = "";
                        while (!$file->eof()) {
                            $data = '<pre>' . $file->fgets() . '</pre>';
                            
                            //1 Coluna
                            $dataToChange = substr($data, 89, 4);
                            $dataToChange2 = substr($data, 90, 3);
                            if($dataToChange == "4183"){
                                $dataNew = substr_replace($data, "2542", 89, 4);
                            }else if($dataToChange == "2019"){
                                $dataNew = substr_replace($data, "196 ", 89, 4);
                            }else if($dataToChange2 == "182"){
                                $dataNew = substr_replace($data, "55 ", 90, 3);
                            }else if($dataToChange2 == "181"){
                                $dataNew = substr_replace($data, "110", 90, 3);
                            }else if($dataToChange == "2031"){
                                $dataNew = substr_replace($data, "191 ", 89, 4);
                            }else if($dataToChange == "2046"){
                                $dataNew = substr_replace($data, "192 ", 89, 4);
                            }else if($dataToChange == "2047"){
                                $dataNew = substr_replace($data, "193 ", 89, 4);
                            }else if($dataToChange == "2015"){
                                $dataNew = substr_replace($data, "196 ", 89, 4);
                            }else if($dataToChange == "2016"){
                                $dataNew = substr_replace($data, "196 ", 89, 4);
                            }else if($dataToChange == "2052"){
                                $dataNew = substr_replace($data, "206 ", 89, 4);
                            }else if($dataToChange == "2451"){
                                $dataNew = substr_replace($data, "242 ", 89, 4);
                            }else if($dataToChange == "2450"){
                                $dataNew = substr_replace($data, "243 ", 89, 4);
                            }else if($dataToChange == "2429"){
                                $dataNew = substr_replace($data, "312 ", 89, 4);
                            }else if($dataToChange == "4188"){
                                $dataNew = substr_replace($data, "377 ", 89, 4);
                            }else if($dataToChange == "4189"){
                                $dataNew = substr_replace($data, "378 ", 89, 4);
                            }else if($dataToChange == "4333"){
                                $dataNew = substr_replace($data, "379 ", 89, 4);
                            }else if($dataToChange == "4332"){
                                $dataNew = substr_replace($data, "380 ", 89, 4);
                            }else if($dataToChange == "4274"){
                                $dataNew = substr_replace($data, "382 ", 89, 4);
                            }else if($dataToChange == "4770"){
                                $dataNew = substr_replace($data, "416 ", 89, 4);
                            }else if($dataToChange == "4684"){
                                $dataNew = substr_replace($data, "429 ", 89, 4);
                            }else if($dataToChange == "4655"){
                                $dataNew = substr_replace($data, "510 ", 89, 4);
                            }else if($dataToChange == "4679"){
                                $dataNew = substr_replace($data, "511 ", 89, 4);
                            }else if($dataToChange == "4680"){
                                $dataNew = substr_replace($data, "511 ", 89, 4);
                            }else if($dataToChange == "4681"){
                                $dataNew = substr_replace($data, "511 ", 89, 4);
                            }else if($dataToChange == "4793"){
                                $dataNew = substr_replace($data, "512 ", 89, 4);
                            }else if($dataToChange == "4794"){
                                $dataNew = substr_replace($data, "513 ", 89, 4);
                            }else if($dataToChange == "4798"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4801"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4803"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4804"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4805"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4819"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4820"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4823"){
                                $dataNew = substr_replace($data, "516 ", 89, 4);
                            }else if($dataToChange == "4795"){
                                $dataNew = substr_replace($data, "517 ", 89, 4);
                            }else if($dataToChange == "4829"){
                                $dataNew = substr_replace($data, "519 ", 89, 4);
                            }else if($dataToChange == "4828"){
                                $dataNew = substr_replace($data, "520 ", 89, 4);
                            }else if($dataToChange == "4799"){
                                $dataNew = substr_replace($data, "521 ", 89, 4);
                            }else if($dataToChange == "4272"){
                                $dataNew = substr_replace($data, "523 ", 89, 4);
                            }else if($dataToChange == "4768"){
                                $dataNew = substr_replace($data, "523 ", 89, 4);
                            }else if($dataToChange == "4311"){
                                $dataNew = substr_replace($data, "526 ", 89, 4);
                            }else if($dataToChange == "4807"){
                                $dataNew = substr_replace($data, "529 ", 89, 4);
                            }else if($dataToChange == "5044"){
                                $dataNew = substr_replace($data, "706 ", 89, 4);
                            }else if($dataToChange == "5189"){
                                $dataNew = substr_replace($data, "707 ", 89, 4);
                            }else if($dataToChange == "5188"){
                                $dataNew = substr_replace($data, "708 ", 89, 4);
                            }else if($dataToChange == "5167"){
                                $dataNew = substr_replace($data, "722 ", 89, 4);
                            }else if($dataToChange == "5130"){
                                $dataNew = substr_replace($data, "725 ", 89, 4);
                            }else if($dataToChange == "4183"){
                                $dataNew = substr_replace($data, "2542", 89, 4);
                            }else if($dataToChange == "4185"){
                                $dataNew = substr_replace($data, "2542", 89, 4);
                            }else if($dataToChange == "4299"){
                                $dataNew = substr_replace($data, "2543", 89, 4);
                            }else if($dataToChange == "4302"){
                                $dataNew = substr_replace($data, "2544", 89, 4);
                            }else if($dataToChange == "4307"){
                                $dataNew = substr_replace($data, "2544", 89, 4);
                            }else if($dataToChange == "4308"){
                                $dataNew = substr_replace($data, "2544", 89, 4);
                            }else if($dataToChange == "4309"){
                                $dataNew = substr_replace($data, "2544", 89, 4);
                            }else if($dataToChange == "4323"){
                                $dataNew = substr_replace($data, "2544", 89, 4);
                            }else if($dataToChange == "4324"){
                                $dataNew = substr_replace($data, "2544", 89, 4);
                            }else if($dataToChange == "4327"){
                                $dataNew = substr_replace($data, "2544", 89, 4);
                            }else if($dataToChange == "4298"){
                                $dataNew = substr_replace($data, "2545", 89, 4);
                            }else if($dataToChange == "4297"){
                                $dataNew = substr_replace($data, "2546", 89, 4);
                            }else if($dataToChange == "5158"){
                                $dataNew = substr_replace($data, "2597", 89, 4);
                            }else if($dataToChange == "5163"){
                                $dataNew = substr_replace($data, "2597", 89, 4);
                            }else if($dataToChange == "5164"){
                                $dataNew = substr_replace($data, "2597", 89, 4);
                            }else if($dataToChange == "5165"){
                                $dataNew = substr_replace($data, "2597", 89, 4);
                            }else if($dataToChange == "5179"){
                                $dataNew = substr_replace($data, "2597", 89, 4);
                            }else if($dataToChange == "5180"){
                                $dataNew = substr_replace($data, "2597", 89, 4);
                            }else if($dataToChange == "5183"){
                                $dataNew = substr_replace($data, "2597", 89, 4);
                            }else if($dataToChange == "5156"){
                                $dataNew = substr_replace($data, "2598", 89, 4);
                            }else if($dataToChange == "5039"){
                                $dataNew = substr_replace($data, "2601", 89, 4);
                            }else if($dataToChange == "5040"){
                                $dataNew = substr_replace($data, "2601", 89, 4);
                            }else if($dataToChange == "5041"){
                                $dataNew = substr_replace($data, "2601", 89, 4);
                            }else if($dataToChange == "5154"){
                                $dataNew = substr_replace($data, "2604", 89, 4);
                            }else if($dataToChange == "5153"){
                                $dataNew = substr_replace($data, "2605", 89, 4);
                            }else if($dataToChange == "5045"){
                                $dataNew = substr_replace($data, "2606", 89, 4);
                            }else if($dataToChange == "5128"){
                                $dataNew = substr_replace($data, "2619", 89, 4);
                            }else{
                                $dataNew = $data;
                            }
                            //2 Coluna
                            $dataNewToChange = substr($dataNew, 109, 4);
                            $dataNewToChange2 = substr($dataNew, 109, 3);
                            if($dataNewToChange == "4183"){
                                $dataNew2 .= substr_replace($dataNew, "2542", 109, 4);
                            }else if($dataNewToChange == "2019"){
                                $dataNew2 .= substr_replace($dataNew, "196 ", 109, 4);
                            }else if($dataNewToChange2 == "182"){
                                $dataNew2 .= substr_replace($dataNew, "55 ", 109, 3);
                            }else if($dataNewToChange2 == "181"){
                                $dataNew2 .= substr_replace($dataNew, "110", 109, 3);
                            }else if($dataNewToChange == "2031"){
                                $dataNew2 .= substr_replace($dataNew, "191 ", 109, 4);
                            }else if($dataNewToChange == "2046"){
                                $dataNew2 .= substr_replace($dataNew, "192 ", 109, 4);
                            }else if($dataNewToChange == "2047"){
                                $dataNew2 .= substr_replace($dataNew, "193 ", 109, 4);
                            }else if($dataNewToChange == "2015"){
                                $dataNew2 .= substr_replace($dataNew, "196 ", 109, 4);
                            }else if($dataNewToChange == "2016"){
                                $dataNew2 .= substr_replace($dataNew, "196 ", 109, 4);
                            }else if($dataNewToChange == "2052"){
                                $dataNew2 .= substr_replace($dataNew, "206 ", 109, 4);
                            }else if($dataNewToChange == "2451"){
                                $dataNew2 .= substr_replace($dataNew, "242 ", 109, 4);
                            }else if($dataNewToChange == "2450"){
                                $dataNew2 .= substr_replace($dataNew, "243 ", 109, 4);
                            }else if($dataNewToChange == "2429"){
                                $dataNew2 .= substr_replace($dataNew, "312 ", 109, 4);
                            }else if($dataNewToChange == "4188"){
                                $dataNew2 .= substr_replace($dataNew, "377 ", 109, 4);
                            }else if($dataNewToChange == "4189"){
                                $dataNew2 .= substr_replace($dataNew, "378 ", 109, 4);
                            }else if($dataNewToChange == "4333"){
                                $dataNew2 .= substr_replace($dataNew, "379 ", 109, 4);
                            }else if($dataNewToChange == "4332"){
                                $dataNew2 .= substr_replace($dataNew, "380 ", 109, 4);
                            }else if($dataNewToChange == "4274"){
                                $dataNew2 .= substr_replace($dataNew, "382 ", 109, 4);
                            }else if($dataNewToChange == "4770"){
                                $dataNew2 .= substr_replace($dataNew, "416 ", 109, 4);
                            }else if($dataNewToChange == "4684"){
                                $dataNew2 .= substr_replace($dataNew, "429 ", 109, 4);
                            }else if($dataNewToChange == "4655"){
                                $dataNew2 .= substr_replace($dataNew, "510 ", 109, 4);
                            }else if($dataNewToChange == "4679"){
                                $dataNew2 .= substr_replace($dataNew, "511 ", 109, 4);
                            }else if($dataNewToChange == "4680"){
                                $dataNew2 .= substr_replace($dataNew, "511 ", 109, 4);
                            }else if($dataNewToChange == "4681"){
                                $dataNew2 .= substr_replace($dataNew, "511 ", 109, 4);
                            }else if($dataNewToChange == "4793"){
                                $dataNew2 .= substr_replace($dataNew, "512 ", 109, 4);
                            }else if($dataNewToChange == "4794"){
                                $dataNew2 .= substr_replace($dataNew, "513 ", 109, 4);
                            }else if($dataNewToChange == "4798"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4801"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4803"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4804"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4805"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4819"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4820"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4823"){
                                $dataNew2 .= substr_replace($dataNew, "516 ", 109, 4);
                            }else if($dataNewToChange == "4795"){
                                $dataNew2 .= substr_replace($dataNew, "517 ", 109, 4);
                            }else if($dataNewToChange == "4829"){
                                $dataNew2 .= substr_replace($dataNew, "519 ", 109, 4);
                            }else if($dataNewToChange == "4828"){
                                $dataNew2 .= substr_replace($dataNew, "520 ", 109, 4);
                            }else if($dataNewToChange == "4799"){
                                $dataNew2 .= substr_replace($dataNew, "521 ", 109, 4);
                            }else if($dataNewToChange == "4272"){
                                $dataNew2 .= substr_replace($dataNew, "523 ", 109, 4);
                            }else if($dataNewToChange == "4768"){
                                $dataNew2 .= substr_replace($dataNew, "523 ", 109, 4);
                            }else if($dataNewToChange == "4311"){
                                $dataNew2 .= substr_replace($dataNew, "526 ", 109, 4);
                            }else if($dataNewToChange == "4807"){
                                $dataNew2 .= substr_replace($dataNew, "529 ", 109, 4);
                            }else if($dataNewToChange == "5044"){
                                $dataNew2 .= substr_replace($dataNew, "706 ", 109, 4);
                            }else if($dataNewToChange == "5189"){
                                $dataNew2 .= substr_replace($dataNew, "707 ", 109, 4);
                            }else if($dataNewToChange == "5188"){
                                $dataNew2 .= substr_replace($dataNew, "708 ", 109, 4);
                            }else if($dataNewToChange == "5167"){
                                $dataNew2 .= substr_replace($dataNew, "722 ", 109, 4);
                            }else if($dataNewToChange == "5130"){
                                $dataNew2 .= substr_replace($dataNew, "725 ", 109, 4);
                            }else if($dataNewToChange == "4183"){
                                $dataNew2 .= substr_replace($dataNew, "2542", 109, 4);
                            }else if($dataNewToChange == "4185"){
                                $dataNew2 .= substr_replace($dataNew, "2542", 109, 4);
                            }else if($dataNewToChange == "4299"){
                                $dataNew2 .= substr_replace($dataNew, "2543", 109, 4);
                            }else if($dataNewToChange == "4302"){
                                $dataNew2 .= substr_replace($dataNew, "2544", 109, 4);
                            }else if($dataNewToChange == "4307"){
                                $dataNew2 .= substr_replace($dataNew, "2544", 109, 4);
                            }else if($dataNewToChange == "4308"){
                                $dataNew2 .= substr_replace($dataNew, "2544", 109, 4);
                            }else if($dataNewToChange == "4309"){
                                $dataNew2 .= substr_replace($dataNew, "2544", 109, 4);
                            }else if($dataNewToChange == "4323"){
                                $dataNew2 .= substr_replace($dataNew, "2544", 109, 4);
                            }else if($dataNewToChange == "4324"){
                                $dataNew2 .= substr_replace($dataNew, "2544", 109, 4);
                            }else if($dataNewToChange == "4327"){
                                $dataNew2 .= substr_replace($dataNew, "2544", 109, 4);
                            }else if($dataNewToChange == "4298"){
                                $dataNew2 .= substr_replace($dataNew, "2545", 109, 4);
                            }else if($dataNewToChange == "4297"){
                                $dataNew2 .= substr_replace($dataNew, "2546", 109, 4);
                            }else if($dataNewToChange == "5158"){
                                $dataNew2 .= substr_replace($dataNew, "2597", 109, 4);
                            }else if($dataNewToChange == "5163"){
                                $dataNew2 .= substr_replace($dataNew, "2597", 109, 4);
                            }else if($dataNewToChange == "5164"){
                                $dataNew2 .= substr_replace($dataNew, "2597", 109, 4);
                            }else if($dataNewToChange == "5165"){
                                $dataNew2 .= substr_replace($dataNew, "2597", 109, 4);
                            }else if($dataNewToChange == "5179"){
                                $dataNew2 .= substr_replace($dataNew, "2597", 109, 4);
                            }else if($dataNewToChange == "5180"){
                                $dataNew2 .= substr_replace($dataNew, "2597", 109, 4);
                            }else if($dataNewToChange == "5183"){
                                $dataNew2 .= substr_replace($dataNew, "2597", 109, 4);
                            }else if($dataNewToChange == "5156"){
                                $dataNew2 .= substr_replace($dataNew, "2598", 109, 4);
                            }else if($dataNewToChange == "5039"){
                                $dataNew2 .= substr_replace($dataNew, "2601", 109, 4);
                            }else if($dataNewToChange == "5040"){
                                $dataNew2 .= substr_replace($dataNew, "2601", 109, 4);
                            }else if($dataNewToChange == "5041"){
                                $dataNew2 .= substr_replace($dataNew, "2601", 109, 4);
                            }else if($dataNewToChange == "5154"){
                                $dataNew2 .= substr_replace($dataNew, "2604", 109, 4);
                            }else if($dataNewToChange == "5153"){
                                $dataNew2 .= substr_replace($dataNew, "2605", 109, 4);
                            }else if($dataNewToChange == "5045"){
                                $dataNew2 .= substr_replace($dataNew, "2606", 109, 4);
                            }else if($dataNewToChange == "5128"){
                                $dataNew2 .= substr_replace($dataNew, "2619", 109, 4);
                            }else{
                                $dataNew2 .= $dataNew;
                            }
                            
                        }
                        echo $dataNew2;
                        
                    }
                    
                    
                    die("morreu");
                }
                return array();
            }
    }

}
