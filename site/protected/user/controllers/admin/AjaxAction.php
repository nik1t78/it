<?php

class AjaxAction extends CAction{
    /**
     * Контроллер, обрабатывающий события вызванные ajax-запросами
     * принимает данные, закодированные json
     * {
     *      data: {
     *          var: val,
     *          var: val,
     *      },
     *      action: 'viewItem',
     *      
     * }
     **/
    public function run(){   
	//если запрос асинхронный, то нам нужно отдать только данные
        if(Yii::app()->request->isAjaxRequest){	
            $model = new ContragentUpload;
            if(isset($_POST['ContragentUpload'])){                
                $model->attributes = $_POST['ContragentUpload'];
                $model->import = CUploadedFile::getInstance($model, 'import');
                
                
                if($model->validate()){
                    $filePath = YiiBase::getPathOfAlias('webroot.upload.import').'/contragents.csv';
                    $model->import->saveAs($filePath);
                    
                  
                    $defaultParams = array(
                            'id' => 'ID',
                            'name' => 'Контрагент',
                            'tin' => 'ИНН',
                            'region' => 'Регион',
                    );
                    $params['none'] = 'не использовать';
                    $params['Общие'] = $defaultParams;
                    //$params = array_merge($params, $model->getParamsForImport());
                    
                    if(isset($_POST['ContragentUpload']['dataType']) && $_POST['ContragentUpload']['dataType'] == 'json')
                        echo json_encode(array('filePath'=>$filePath, 'params'=>$params));
                    else{
                        $parseContent = $model->parseFirstString($filePath);
                        
                        $stringCount = count($parseContent);
                        
                        
                        $html = '<input type="hidden" name="filePath" value="'.$filePath.'" />';
                        $html .= '<table  class="sTable withCheck"><thead><tr>';
                        //$html .= '<th>Поле в файле</th>';
                        for($i = 0; $i < $stringCount; $i++){
                            $html .= '<th>'.$parseContent[$i].'</th>';
                        }
                        $html .= '</tr></thead>'; 
                        $html .= '<tbody><tr>';
                        //$html .= '<td>Поле в БД</td>';
                        for($i = 0; $i < $stringCount; $i++){
                            $html .= '<td>'.CHtml::dropDownList('ContragentsUploadParams['.$i.']', '', $params).'</td>';
                        }
                        $html .= '</tr></tbody></table>';
                        echo $html;
                    }
                }
            }elseif(isset($_POST['ContragentsUploadParams'])){
                $filePath = $_POST['filePath'];
                $targetArr = $_POST['ContragentsUploadParams'];
                
                $parseContent = $model->parseFile($filePath, $targetArr);
            }
            
            Yii::app()->end();
        }else{
            //если запрос не асинхронный, редиректим в корень модуля
            $this->controller->redirect(Yii::app()->createUrl('user/admin/orglist'));
	}
    }
    
	
    public function filters(){
	return array(
            'ajaxOnly + index',
	);
    }
    
    
    /**
     * Проверяем запись из архива - является она разделом или товаром
     * @param type $arr 
     */
    private function detectDir($arr){
        return empty($arr[$this->priceField]) ? true : false;
    }
}