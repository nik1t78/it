<?php
class StructureTree extends CWidget
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
	 * @var string|array the URL to which the treeview can be dynamically loaded (in AJAX).
	 * See {@link CHtml::normalizeUrl} for possible URL formats.
	 * Setting this property will enable the dynamic treeview loading.
	 * When the page is displayed, the browser will request this URL with a GET parameter
	 * named 'root' whose value is 'source'. The server script should then generate the
	 * needed tree view data corresponding to the root of the tree (see {@link saveDataAsJson}.)
	 * When a node has a CSS class 'hasChildren', then expanding this node will also
	 * cause a dynamic loading of its child nodes. In this case, the value of the 'root' GET parameter
	 * is the 'id' property of the node.
	 */
	public $url;
	/**
	 * @var string|integer animation speed. This can be one of the three predefined speeds
	 * ("slow", "normal", or "fast") or the number of milliseconds to run the animation (e.g. 1000).
	 * If not set, no animation is used.
	 */
	public $animated;
	/**
	 * @var boolean whether the tree should start with all branches collapsed. Defaults to false.
	 */
	public $collapsed;
	/**
	 * @var string container for a tree-control, allowing the user to expand, collapse and toggle all branches with one click.
	 * In the container, clicking on the first hyperlink will collapse the tree;
	 * the second hyperlink will expand the tree; while the third hyperlink will toggle the tree.
	 * The property should be a valid jQuery selector (e.g. '#treecontrol' where 'treecontrol' is
	 * the ID of the 'div' element containing the hyperlinks.)
	 */
	public $control;
	/**
	 * @var boolean set to allow only one branch on one level to be open (closing siblings which opening).
	 * Defaults to false.
	 */
	public $unique;
	/**
	 * @var string Callback when toggling a branch. Arguments: "this" refers to the UL that was shown or hidden
	 */
	public $toggle;
	/**
	 * @var string Persist the tree state in cookies or the page location. If set to "location", looks for
	 * the anchor that matches location.href and activates that part of the treeview it.
	 * Great for href-based state-saving. If set to "cookie", saves the state of the tree on
	 * each click to a cookie and restores that state on page load.
	 */
	public $persist;
	/**
	 * @var string The cookie name to use when persisting via persist:"cookie". Defaults to 'treeview'.
	 */
	public $cookieId;
	/**
	 * @var boolean Set to skip rendering of classes and hitarea divs, assuming that is done by the serverside. Defaults to false.
	 */
	public $prerendered;
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
	public $htmlOptions;
	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the tree view content.
	 */
	public function init()
	{	   
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$id=$this->htmlOptions['id']=$this->getId();
		if($this->url!==null)
			$this->url=CHtml::normalizeUrl($this->url);
			
        $cs=Yii::app()->getClientScript();                
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/kendoUi/kendo.all.min.js',CClientScript::POS_END);
                
       	$cs->registerCssFile(Yii::app()->baseUrl.'/css/kendoUi/kendo.common.css');
       	$cs->registerCssFile(Yii::app()->baseUrl.'/css/kendoUi/kendo.default.css');
        
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.nestedArray.js', CClientScript::POS_END);
        
        $dropFunc = '';
        $ajaxWndFunc = '';
        
        if(Yii::app()->controller->module->getSaveTreeOnAir()){ //Если в настройках включен парамет "Сохранение на лету", подключаем соотв. функцию
            $dropFunc = 'saveTree(e); //вызов функции при drop элемента';
        }
        $scriptText = ' 
                function saveTree(e){
                    if(e.valid){
                        var timer = setTimeout(function(){
                            var arraied = $(".siteTree").nestedArray();
                        
                            $.ajax({
                                url: "/backend/pages/ajax",
                                type: "POST",
                                data:{
                                    eventType: "updateTree",
                                    treeItems: $.toJSON(arraied),
                                    YII_CSRF_TOKEN: "'.Yii::app()->request->csrfToken.'",
                                },
                                dataType: "text",
        						success: function(data){
        							//console.log(data);			
        						},
        						error: function(xhr, textStatus, errorThrown){
        							console.log(textStatus);
        						}
                            });
                        }, 500);
                    }
                }
                
                
                function onDrop(e){
                    // console.log("destinationNode: "+$(e.destinationNode).attr("class")+"; dropTarget: "+$(e.destinationNode).attr("class")+"; dropPosition: "+$(e.dropPosition).attr("class"));
                    if($(e.dropTarget).hasClass("k-drop-hint") || $(e.destinationNode).hasClass("dir")) {
                        '.$dropFunc.'
                    }else{
                    	e.setValid(false);
                        return false;
                    }                    
                }
                
            $(document).ready(function() {
                var siteTree = $(".siteTree").kendoTreeView({
                    dragAndDrop: true,                    
                    drop: onDrop,
                    //expand: onExpand, //вызов функции при разворачивании ветки
                    //collapse: onCollapse, //вызов функции при сворачивании ветки
                    //error: onError, //вызов функции при ошибке
                }).data("kendoTreeView");
                /** строка ниже выдает ошибку **/
                siteTree.expand(".k-item.k-first"); //Корень раскрыт по умолчанию
                     
                $(".siteTree_actions a.delete").live("click", function(){
                    if(!confirm("Вы уверены, что хотите удалить данный элемент?")) return false;
                    
                    $.ajax({
                                url: $(this).attr("href"),
                                type: "POST",
                                data:{
                                    YII_CSRF_TOKEN: "'.Yii::app()->request->csrfToken.'",
                                },
        						success: function(data){        						  
        							$("#yw0").html($(data).find("#yw0").html());
                                    //ВСТАВИТЬ ПРОВЕРКУ НА УДАЧНОЕ УДАЛЕНИЕ
                                    var siteTree = $(".siteTree").kendoTreeView({
                                        dragAndDrop: true,                    
                                        drop: onDrop,
                                        //expand: onExpand, //вызов функции при разворачивании ветки
                                        //collapse: onCollapse, //вызов функции при сворачивании ветки
                                        //error: onError, //вызов функции при ошибке
                                    }).data("kendoTreeView");
                                    siteTree.expand(".k-item.k-first"); //Корень раскрыт по умолчанию
        						},
        						error: function(xhr, textStatus, errorThrown){
        							console.log(textStatus);
        						}
                            });
                    return false;
                });
            });
        ';
        
        $cs->registerScript('siteStructure', $scriptText,CClientScript::POS_END);
        
		if($this->cssFile===null){
			$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/structureTree.css');
        }
		else if($this->cssFile!==false)
			$cs->registerCssFile($this->cssFile);
            
		echo '<div class="siteTreeH"><div class="itemMain fleft">'.Yii::t('pages', 'Название').'</div><div class="itemAdvanced fright"><span class="siteTree_id inlineBlock">'.Yii::t('pages', 'ID').'</span><span class="siteTree_eng inlineBlock">'.Yii::t('pages', 'Имя файла').'</span><span class="siteTree_created inlineBlock">'.Yii::t('pages', 'Изменен').'</span></div><div class="clear"></div></div>';
		//VarDumper::dump($this->data);
                echo CHtml::tag('ul',$this->htmlOptions,false,false)."\n";
		echo self::saveDataAsHtml($this->data);
	}

	/**
	 * Ends running the widget.
	 */
	public function run()
	{
		echo "</ul>";
        if(!Yii::app()->controller->module->getSaveTreeOnAir()) //Если функция "Сохранение на лету" отключена, выводим кнопку сохранения изменений
            echo "<input type='button' id='testAction' onclick='saveTree();' value='Сохранить сортировку' />";
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
                //VarDumper::dump($node);
				if(!isset($node['text']))
					continue;

				if(isset($node['expanded']))
					$css=$node['expanded'] ? 'open' : 'closed';
				else
					$css='';
                    
                $css .= ' '.Pages::getTreeIcon($node['node']);

				if(isset($node['children']) && $node['children'])
				{
					if($css!=='')
						$css.=' ';
					$css.='hasChildren';
				}

				$options=isset($node['htmlOptions']) ? $node['htmlOptions'] : array();
				if($css!=='')
				{
					if(isset($options['class']))
						$options['class'].=' '.$css;
					else
						$options['class']=$css;
				}

				if(isset($node['id']))
					$options['id']=$node['id'];
                                
                $itemText = '';
                $itemText .= '<table cellspacing="0" cellpadding="0" border="0"><tr valign="top"><td class="itemMain">'; 
                $itemText .= '<span class="pageLink inlineBlock"><span class="itemIcon '.Pages::getTreeIcon($node['node']).'"></span><span class="itemName">'.$node['node']->title.'</span></span></td>';
                $itemText .= '<td class="itemAdvanced"><span class="siteTree_id inlineBlock" title="'.$node['node']->id.'">id: '.$node['node']->id.'</span> <span class="siteTree_eng inlineBlock" title="'.$node['node']->name.'">/'.$node['node']->name.'</span> <span class="siteTree_created inlineBlock">'.Yii::app()->dateFormatter->format('dd MMMM yyyy', $node['node']->update_time).'</span> <span class="siteTree_actions inlineBlock"><a href="'.Yii::app()->createUrl("/pages/admin/update", array('id'=>$node['node']->id)).'" class="tipS" title="Редактировать"><img src="'.Yii::app()->theme->baseUrl.'/images/icons/edit.png" /></a>&nbsp;&nbsp;&nbsp;<a href="'.Yii::app()->createUrl("/pages/admin/delete", array('id'=>$node['node']->id)).'" class="tipS delete" title="Удалить"><img src="'.Yii::app()->theme->baseUrl.'/images/icons/remove.png" /></a></span></td></tr></table>';
                $itemText .= "\n";
                
				$html.=CHtml::tag('li',$options,$itemText,false);
				if(!empty($node['children']))
				{
					$html.="\n<ul>\n";
					$html.=self::saveDataAsHtml($node['children']);
					$html.="</ul>\n";
				}
				$html.=CHtml::closeTag('li')."\n";
                $i++;                
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
