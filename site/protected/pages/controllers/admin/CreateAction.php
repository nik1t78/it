<?php        
/**
//Создание корневых узлов. В режиме работы "одно дерево" может быть создан только один корень, в противном случае вы получите CException
$root = new Pages;
$root->title = 'Root';
$root->saveNode();

//Добавление дочерних узлов
$category1 = new Pages;
$category1->title = 'ford';
$category2 = new Pages;
$category2->title = 'mercedes';
$category3 = new Pages;
$category3->title = 'audi';
$root = Pages::model()->findByPk(1);
$category1->appendTo($root);
$category2->insertAfter($category1);
$category3->insertBefore($category1);

$category1 = new Pages;
$category1->title = 'X100';
$category2 = new Pages;
$category2->title = 'c200';
$node = Pages::model()->findByPk(3);
$category1->appendTo($node);
$category2->prependTo($node);
**/

class CreateAction extends CAction
{
    public function run()
    {                
        $model=new Pages;

        $permission_type = 'deny';
        
        // Uncomment the following line if AJAX validation is needed
        $this->controller->performAjaxValidation($model);

        if (isset($_POST['Pages'])) {
            //$newElement = new Pages;
            $newElement = $model;
            $newElement->attributes=$_POST['Pages'];
            $newElement->author_id = Yii::app()->user->id;
            
            if (!empty($_POST['Pages']['permissions'])) {  
                $permission = $_POST['Permission']['type'] . ':';
                $sep = '';
                foreach ($_POST['Pages']['permissions'] as $per) {
                    $permission .= $sep.$per;
                    $sep = ';';
                }
                $newElement->permissions = $permission;
            }
            
            $root = Pages::model()->findByPk($newElement->parent_id);
            //VarDumper::dump($newElement->attributes);
            
            if ($newElement->validate()) {
                //if($model->save())
                //	$this->controller->redirect(array('view','id'=>$model->id));
                if($newElement->appendTo($root)) {
                    //if(!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(array('index'));
                }
            }
        }

        $this->controller->render('create',array(
            'model'=>$model,
            'permission_type'=>$permission_type,
        ));
    }
}