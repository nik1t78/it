<?php

/**
        <div class="nNote nWarning hideit">
            <p><strong>ИНФОРМАЦИЯ: </strong>Top buttons area has 3 versions - 2 kinds of buttons and statistics. All of them could be viewed on <a href="ui_elements.html" title="">Interface elements page</a></p>
        </div>
        <div class="nNote nInformation hideit">
            <p><strong>ИНФОРМАЦИЯ: </strong>Top buttons area has 3 versions - 2 kinds of buttons and statistics. All of them could be viewed on <a href="ui_elements.html" title="">Interface elements page</a></p>
        </div>
        <div class="nNote nSuccess hideit">
            <p><strong>ИНФОРМАЦИЯ: </strong>Top buttons area has 3 versions - 2 kinds of buttons and statistics. All of them could be viewed on <a href="ui_elements.html" title="">Interface elements page</a></p>
        </div>
        <div class="nNote nFailure hideit">
            <p><strong>ИНФОРМАЦИЯ: </strong>Top buttons area has 3 versions - 2 kinds of buttons and statistics. All of them could be viewed on <a href="ui_elements.html" title="">Interface elements page</a></p>
        </div>
 */
class AFlashMessages extends CWidget
{
    const NOTICE_MESSAGE = 'notice';

    const WARNING_MESSAGE = 'warning';

    const ERROR_MESSAGE = 'error';
    
    const SUCCESS_MESSAGE = 'success';

    public $error = 'error';

    public $warning = 'warning';

    public $notice = 'notice';
    
    public $success = 'success';

    public $autoHide = false;

    public $autoHideSeconds = 3600;

    public $customJsCode;

    public function run()
    {
        //VarDumper::dump(Yii::app()->user);
        if (count(Yii::app()->user->getFlashes(false)))
        {
            if ($this->autoHide)
            {
                $this->autoHideSeconds = (int)$this->autoHideSeconds;
                $this->error = CHtml::encode($this->error);
                $this->warning = CHtml::encode($this->warning);
                $this->notice = CHtml::encode($this->notice);
                $this->success = CHtml::encode($this->success);
                Yii::app()->getClientScript()->registerCoreScript('jquery');
                Yii::app()->getClientScript()->registerScript(md5($this->id), "
                        $('#{$this->divId}').fadeOut({$this->autoHideSeconds});                        
                ", CClientScript::POS_END);
            }
            elseif ($this->customJsCode)
            {
                Yii::app()->getClientScript()->registerCoreScript('jquery');
                Yii::app()->getClientScript()->registerScript(md5($this->customJsCode), $this->customJsCode, CClientScript::POS_END);
            }

            $this->render('flashmessages');
        }
    }
}