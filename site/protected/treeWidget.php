<?php
class treeWidget extends CWidget
{
	/**
	 * @var array the data that can be used to generate the tree view content.
	 * Each array element corresponds to a tree view node with the following structure:
	 * <ul>
	 * <li>text: string, required, the HTML text associated with this node.</li>
	 * <li>expanded: boolean, optional, whether the tree view node is expanded.</li>
	 * <li>id: string, optional, the ID identifying the node. This is used
	 *   in dynamic loading of tree view (see {@link url}).</li>
	 * <li>hasChildren: boolean, optional, defaults to false, whether clicking on this
	 *   node should trigger dynamic loading of more tree view nodes from server.
	 *   The {@link url} property must be set in order to make this effective.</li>
	 * <li>children: array, optional, child nodes of this node.</li>
	 * <li>htmlOptions: array, additional HTML attributes (see {@link CHtml::tag}).
	 *   This option has been available since version 1.1.7.</li>
	 * </ul>
	 * Note, anything enclosed between the beginWidget and endWidget calls will
	 * also be treated as tree view content, which appends to the content generated
	 * from this data.
	 */
	public $data;
	/**
	 * @var mixed the CSS file used for the widget. Defaults to null, meaning
	 * using the default CSS file included together with the widget.
	 * If false, no CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile;
	
	/**
	 * @var array additional options that can be passed to the constructor of the treeview js object.
	 */
	public $options=array();
	/**
	 * @var array additional HTML attributes that will be rendered in the UL tag.
	 * The default tree view CSS has defined the following CSS classes which can be enabled
	 * by specifying the 'class' option here:
	 * <ul>
	 * <li>treeview-black</li>
	 * <li>treeview-gray</li>
	 * <li>treeview-red</li>
	 * <li>treeview-famfamfam</li>
	 * <li>filetree</li>
	 * </ul>
	 */
	public $htmlOptions=array();
        
        public $labelField = 'title';
	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the tree view content.
	 */
	public function init()
	{	   
        
        $cs=Yii::app()->getClientScript(); 
        
		if($this->cssFile===null){
			$cs->registerCssFile(Yii::app()->baseUrl.'/css/pagesTreeWidget.css');
        }
		else if($this->cssFile!==false)
			$cs->registerCssFile($this->cssFile);
            
		echo CHtml::tag('ul',$this->htmlOptions,false,false)."\n";
		echo self::saveDataAsHtml($this->data);
	}

	/**
	 * Ends running the widget.
	 */
	public function run()
	{
		echo "</ul>";        
	}

	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
		$options=$this->options;
		foreach(array('url','animated','collapsed','control','unique','toggle','persist','cookieId','prerendered') as $name)
		{
			if($this->$name!==null)
				$options[$name]=$this->$name;
		}
		return $options;
	}

	/**
	 * Generates tree view nodes in HTML from the data array.
	 * @param array $data the data for the tree view (see {@link data} for possible data structure).
	 * @return string the generated HTML for the tree view
	 */
	public function saveDataAsHtml($data)
	{
		$html='';
		if(is_array($data))
		{
            $i = 0;		  
			foreach($data as $node)
			{
				if(!isset($node['text']))
					continue;
/** сворачивание/разворачивание дерева (стили)**/
				if(isset($node['expanded']))
					$css=$node['expanded'] ? 'open' : 'closed';
				else
					$css='';
         
                    
                // $css .= ' '.Pages::getTreeIcon($node['node']); // Вставляем иконку папки/странички/модуля

				if(isset($node['children']) && $node['children']) // Если существуют дочерние элементы - назначить соотв. стиль
				{
					if($css!=='')
						$css.=' ';
					$css.='hasChildren';
				}

				$options=isset($node['htmlOptions']) ? $node['htmlOptions'] : array();
				if($css!=='') // Объединяем стили 
				{
					if(isset($options['class']))
						$options['class'].=' '.$css;
					else
						$options['class']=$css;
				}

				if(isset($node['id']))
					$options['id']=$node['id'];
                                
                $itemText = '';
                $txt = $this->labelField;
                $itemText .= '<a href="#">'.$node['node']->$txt.'</a>';
//                $itemText .= '<td class="itemAdvanced"><span class="siteTree_id inlineBlock" title="'.$node['node']->id.'">id: '.$node['node']->id.'</span> <span class="siteTree_eng inlineBlock" title="'.$node['node']->name.'">/'.$node['node']->name.'</span> <span class="siteTree_created inlineBlock">'.Yii::app()->dateFormatter->format('dd MMMM yyyy', $node['node']->update_time).'</span> <span class="siteTree_actions inlineBlock"><div class="treeActionButton edit" title="'.Yii::t('artcms', 'Редактировать').'">'.CHtml::link("", '/backend/pages/update?id='.$node['node']->id).'</div> <div class="treeActionButton delete" title="'.Yii::t('artcms', 'Удалить').'">'.CHtml::link("", '/backend/pages/delete?id='.$node['node']->id).'</div></span></td></tr></table>';
               
				$html.=CHtml::tag('li',$options,$itemText,false);
				if(!empty($node['children']))
				{
					$html.="\n<ul>\n";
					$html.=self::saveDataAsHtml($node['children']);
					$html.="</ul>\n";
				}
				$html.=CHtml::closeTag('li')."\n";
                $i++;   /**  **/            
			}
		}
		return $html;
	}

	/**
	 * Saves tree view data in JSON format.
	 * This method is typically used in dynamic tree view loading
	 * when the server code needs to send to the client the dynamic
	 * tree view data.
	 * @param array $data the data for the tree view (see {@link data} for possible data structure).
	 * @return string the JSON representation of the data
	 */
	public static function saveDataAsJson($data)
	{
		if(empty($data))
			return '[]';
		else
			return CJavaScript::jsonEncode($data);
	}
}
