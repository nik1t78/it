<?php
/*
 * Переопределение UrlManager`a для замены закодированных слэшей в url обратно на слэши.
 */
class UrlManager extends CUrlManager{
    public function createUrl($route, $params=array(), $ampersand='&'){
        return $this->fixPathSlashes(parent::createUrl($route, $params, $ampersand));
    }
    protected function fixPathSlashes($url){
        return preg_replace('|\%2F|i', '/', $url);
    }
}
?>
