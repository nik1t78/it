<?php

Yii::import('zii.widgets.CMenu');

class MenuWidget extends CMenu
{
    public $hasSubmenuCssClass = 'dropdown';
    public $linkToSubmenuHtmlOptions = array('class' => 'dropdown-toggle', 'data-toggle'=>"dropdown");
    public $subWrapper = array();

    protected function renderMenuRecursive($items)
	{
		$count=0;
		$n=count($items);
		foreach($items as $item)
		{
			$count++;
			$options=isset($item['itemOptions']) ? $item['itemOptions'] : array();
			$class=array();
			if($item['active'] && $this->activeCssClass!='')
				$class[]=$this->activeCssClass;
			if($count===1 && $this->firstItemCssClass!==null)
				$class[]=$this->firstItemCssClass;
			if($count===$n && $this->lastItemCssClass!==null)
				$class[]=$this->lastItemCssClass;
			if($this->itemCssClass!==null)
				$class[]=$this->itemCssClass;
                        if(isset($item['items']) && count($item['items'])) {
                                $class[]=$this->hasSubmenuCssClass;
                                $item['linkOptions'] = $this->linkToSubmenuHtmlOptions;
                        }

			if($class!==array())
			{
				if(empty($options['class']))
					$options['class']=implode(' ',$class);
				else
					$options['class'].=' '.implode(' ',$class);
			}

			echo CHtml::openTag('li', $options);

			$menu=$this->renderMenuItem($item);
			if(isset($this->itemTemplate) || isset($item['template']))
			{
				$template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
				echo strtr($template,array('{menu}'=>$menu));
			}
			else
				echo $menu;

			if(isset($item['items']) && count($item['items']))
			{
                $subWrapOpen = $subWrapClose = '';
                if (!empty($this->subWrapper)){
                    foreach ($this->subWrapper as $tag) {
                        $subWrapOpen .= "\n".CHtml::openTag($tag['tag'], isset($tag['htmlOptions'])?$tag['htmlOptions']:array())."\n";
                        $subWrapClose .= CHtml::closeTag($tag['tag'])."\n";
                    }
                }
				echo $subWrapOpen."\n".CHtml::openTag('ul',isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions)."\n";
				$this->renderMenuRecursive($item['items']);
				echo CHtml::closeTag('ul')."\n".$subWrapClose."\n";
                $subWrapOpen = $subWrapClose = '';
			}

			echo CHtml::closeTag('li')."\n";
		}
	}
}
