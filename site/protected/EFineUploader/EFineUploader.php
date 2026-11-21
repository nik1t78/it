<?php
/**
 * EFineUploader class file.
 * This extension is a wrapper of https://github.com/Widen/fine-uploader
 *
 * @author Vladimir Papaev <kosenka@gmail.com>
 * @version 0.1
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

/**
        How to use:

        view:
$this->widget('ext.EFineUploader.EFineUploader',
 array(
       'id'=>'FineUploader',
       'config'=>array(
                       'autoUpload'=>true,
                       'request'=>array(
                                        'endpoint'=>'/files/upload',// OR $this->createUrl('files/upload'),
                                        'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
                                       ),
                       'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
                       'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
                       'callbacks'=>array(
                                          'onComplete'=>"js:function(id, name, response){  }",
                                          //'onError'=>"js:function(id, name, errorReason){ }",
                                         ),
                       'validation'=>array(
                                           'allowedExtensions'=>array('jpg','jpeg'),
                                           'sizeLimit'=>2 * 1024 * 1024,//maximum file size in bytes
                                           'minSizeLimit'=>2*1024*1024,// minimum file size in bytes
                                          ),
                       'messages'=>array(
                                         'typeError'=>"Файл {file} имеет неверное расширение. Разрешены файлы только с расширениями: {extensions}.",
                                         'sizeError'=>"Размер файла {file} велик, максимальный размер {sizeLimit}.",
                                         'minSizeError'=>"Размер файла {file} мал, минимальный размер {minSizeLimit}.",
                                         'emptyError'=>"{file} is empty, please select files again without it.",
                                         'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
                                        ),
                      )
      ));

        controller:

        public function actionUpload()
        {
                $tempFolder=Yii::getPathOfAlias('webroot').'/upload/temp/';

                mkdir($tempFolder, 0777, TRUE);
                mkdir($tempFolder.'chunks', 0777, TRUE);

                Yii::import("ext.EFineUploader.qqFileUploader");

                $uploader = new qqFileUploader();
                $uploader->allowedExtensions = array('jpg','jpeg');
                $uploader->sizeLimit = 2 * 1024 * 1024;//maximum file size in bytes
                $uploader->chunksFolder = $tempFolder.'chunks';

                $result = $uploader->handleUpload($tempFolder);
                $result['filename'] = $uploader->getUploadName();
                $result['folder'] = $webFolder;

                $uploadedFile=$tempFolder.$result['filename'];

                header("Content-Type: text/plain");
                $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
                echo $result;
                Yii::app()->end();
        }

*/
class EFineUploader extends CWidget
{
        //public $version="3.4.1";
        public $version="4.1.1";
        public $id="fineUploader";
	public $config=array();
	public $css=null;
        public $userScript = '';
        
        public function run()
        {
		if(empty($this->config['request']['endpoint']))
		{
		      throw new CException('EFineUploader: param "request::endpoint" cannot be empty.');
                }

		if(!is_array($this->config['validation']['allowedExtensions']))
		{
		      throw new CException('EFineUploader: param "validation::allowedExtensions" must be an array.');
                }

		if(empty($this->config['validation']['sizeLimit']))
		{
		      throw new CException('EFineUploader: param "validation::sizeLimit" cannot be empty.');
                }

                unset($this->config['element']);

                echo '<div id="'.$this->id.'"><noscript><p>Please enable JavaScript to use file uploader.</p></noscript></div>';


		$assets = dirname(__FILE__).'/assets';
                $baseUrl = Yii::app()->assetManager->publish($assets);

                $this->config['thumbnails'] = array(
                    'placeholders' => array(
                        'waitingPath' => $baseUrl."/waiting-generic.png",
                        'notAvailablePath' => $baseUrl."/not_available-generic.png"
                    )
                );

                echo '
<script type="text/template" id="qq-template">
  <div class="qq-uploader-selector qq-uploader">
    <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
      <span>Перетащите сюда файлы</span>
    </div>
    <div class="qq-upload-button-selector qq-upload-button">
      <div>Выбрать файл</div>
    </div>
    <span class="qq-drop-processing-selector qq-drop-processing">
      <span>Обработка файлов...</span>
      <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
    </span>
    <ul class="qq-upload-list-selector qq-upload-list">
      <li>
        <div class="qq-progress-bar-container-selector">
          <div class="qq-progress-bar-selector qq-progress-bar"></div>
        </div>
        <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
        <span class="qq-upload-file-selector qq-upload-file"></span>
        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
        <span class="qq-upload-size-selector qq-upload-size"></span>
        <a class="qq-upload-cancel-selector qq-upload-cancel" href="#">Отменить</a>
        <a class="qq-upload-retry-selector qq-upload-retry" href="#">Повторить</a>
        <a class="qq-upload-delete-selector qq-upload-delete" href="#">Удалить</a>
        <span class="qq-upload-status-text-selector qq-upload-status-text"></span>
      </li>
    </ul>
  </div>
</script>
<script type="text/template" id="qq-simple-thumbnails-template">
  <div class="qq-uploader-selector qq-uploader">
    <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
      <span>Перетащите сюда файлы</span>
    </div>
    <div class="qq-upload-button-selector qq-upload-button">
      <div>Выбрать файл</div>
    </div>
    <span class="qq-drop-processing-selector qq-drop-processing">
      <span>Обработка файла...</span>
      <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
    </span>
    <ul class="qq-upload-list-selector qq-upload-list">
      <li>
        <div class="qq-progress-bar-container-selector">
          <div class="qq-progress-bar-selector qq-progress-bar"></div>
        </div>
        <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
        <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
        <span class="qq-upload-file-selector qq-upload-file"></span>
        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
        <span class="qq-upload-size-selector qq-upload-size"></span>
        <a class="qq-upload-cancel-selector qq-upload-cancel" href="#">Отменить</a>
        <a class="qq-upload-retry-selector qq-upload-retry" href="#">Повторить</a>
        <a class="qq-upload-delete-selector qq-upload-delete" href="#">Удалить</a>
        <span class="qq-upload-status-text-selector qq-upload-status-text"></span>
      </li>
    </ul>
  </div>
</script>';

                $fJs=(YII_DEBUG)?$baseUrl."/jquery.fineuploader-{$this->version}.js":$baseUrl."/jquery.fineuploader-{$this->version}.min.js";
                Yii::app()->clientScript->registerScriptFile($fJs, CClientScript::POS_HEAD);

                $this->css=(!empty($this->css))?$this->css:$baseUrl."/fineuploader-{$this->version}.css";
                Yii::app()->clientScript->registerCssFile($this->css);

		$config = array(
                                'element'=>'js:document.getElementById("'.$this->id.'")',
                                'debug'=>false,
                                'multiple'=>false
                               );
		$config = array_merge($config, $this->config);
               
		//$config['params']=$postParams;
		//$config = CJavaScript::encode($config);
               
                /*
                echo '<script>'
                . '$(document).ready(function(){'
                        . "var FineUploader_".$this->id." = new qq.FineUploader($config);"
                        . $this->userScript
                . '});'
                . '</script>';*/
                $script = /*'<script>'
                . '$(document).ready(function(){'
                        . */"var FineUploader_".$this->id." = $('#".$this->id."').fineUploader(".CJavaScript::encode($config).");"
                        . $this->userScript
                /*. '});'
                . '</script>'*/;
                Yii::app()->getClientScript()->registerScript("FineUploader_".$this->id, $script, CClientScript::POS_READY);
                //Yii::app()->getClientScript()->registerScript("FineUploader_".$this->id, "var FineUploader_".$this->id." = new qq.FineUploader($config);",CClientScript::POS_LOAD);
	}


}