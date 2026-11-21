<?php

class DefaultController extends FrontendController
{
    public $template = '';
    public $bodyPageTitle = 'Фотогалереи';
    public $pageSize = 10;

    public function actionIndex($gallery = 1)
    {             // если запросили модуль инфоблок без указания конкретного инфоблока - возвращаем ошибку
        //$sections = FotoSection::model()->findAllByAttributes(array('section_id'=>0, 'gallery_id'=>$gallery));
        $criteria = new CDbCriteria;
        $criteria->condition = 'show_in_list=:showInList';
        $criteria->params = array(
            ':showInList' => FotoGallery::ACTIVE
        );
        $criteria->order = 'sort ASC';

        $galleries = FotoGallery::model()->findAll($criteria);

        $this->render('index', array('sections'=>$galleries));
    }

    public function actionView($gallery, $section='')
    {
        $galleryM = FotoGallery::model()->active()->findByPk($gallery); // Получаем фотогалерею

        if($galleryM == null){
            throw new CHttpException(404, 'Запрошеная страница не найдена.');
        }

        // Ищем запрошенный раздел в инфоблоке
        if($section){
            $sectionModel = FotoSection::model()->active()->findByPk($section);

            if($sectionModel != null){
                /** подготовка паджинации - подсчет кол-ва элементов, определение лимитов **/
                $criteria = new CDbCriteria();
                $criteria->condition = 'section_id=:section_id';
                $criteria->params = array(
                    ':section_id' => $section
                );
                $criteria->order = 'sort ASC';
                $elements_count = Foto::model()->active()->child($sectionModel->id)->count($criteria);
                $pages = new CPagination($elements_count);
                $pages->pageSize = $this->pageSize;
                $pages->applyLimit($criteria);

                $sections = FotoSection::model()->active()->child($sectionModel->id)->findAll();
                $elements = Foto::model()->findAll($criteria);


                $this->pageTitle = $sectionModel->title;
                //$this->breadcrumbs[$iblockModel->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblockModel->code));
                //$this->breadcrumbs[$sectionModel->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblockModel->code, 'section'=>$sectionModel->path));

                /* РЕНДЕР СПИСКА РАЗДЕЛА */
                $this->render($this->template.'foto_section_list', array(
                    'gallery' => $galleryM,
                    'section' => $sectionModel,
                    'sections' => $sections,
                    'elements' => $elements,
                    'pages' => $pages
                ));
                return true; //Тормозим дальнейшее выполнение
            }
        }

        $criteria = new CDbCriteria();
        $criteria->condition = 'gallery_id=:gallery_id';
        $criteria->params = array(
            ':gallery_id' => $galleryM->id
        );
        $criteria->order = 'sort ASC';
        $elements_count = Foto::model()->active()->count($criteria);
        $pages = new CPagination($elements_count);
        $pages->pageSize = $this->pageSize;
        $pages->applyLimit($criteria);
        $sections = FotoSection::model()->active()->findAllByAttributes(array('gallery_id' => $galleryM->id));
        $elements = Foto::model()->active()->findAll($criteria);

        $this->render('foto_section_list', array('sections' => $sections, 'elements' => $elements, 'pages'=>$pages, 'gallery'=>$galleryM));
    }

    public function actionElement($e_code='', $e_id='', $section=''){

        if(empty($e_code) && empty($e_id))
            throw new CHttpException(404, 'Страница не найдена.');

        $criteria = new CDbCriteria();
        if($e_code){
            $criteria->addCondition('code=:code');
            $criteria->params = array_merge($criteria->params, array(
                        ':code'=>$e_code,
            ));


            if($section){
                $section = IBlockSection::model()->findByPath($section);
                if($section !== null){
                    $criteria->addCondition('iblock_section_id=:section_id');
                    $criteria->params = array_merge($criteria->params, array(
                        ':section_id'=>$section->id
                    ));
                }else{
                    throw new CHttpException(404, 'По указанному адресу страница не найдена.');
                }
            }else{
                $criteria->addCondition('iblock_section_id=0');
            }
            $element = IBlockElement::model()->active()->find($criteria);
        }elseif($e_id){
            $element = IBlockElement::model()->active()->findByPk($e_id);
        }
        if($element === null)
            throw new CHttpException(404, 'Запрошенная страница не найдена.');

        $this->pageTitle = $element->name;
        $this->breadcrumbs[$element->iblock->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$element->iblock->code));
        if($section){
            $this->breadcrumbs[$section->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$element->iblock->code, 'section'=>$section->path));
        }
        $this->breadcrumbs[$element->name] = '/iblock/'.$element->url;

        $template = $element->iblock->template == null ? $this->template : $element->iblock->template;
        $this->render($template.'/iblock_element_view', array('element'=>$element));
    }
}
