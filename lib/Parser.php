<?php

namespace MaxKnowBox\IBlockShortcodes;

use \Bitrix\Main\Config\Option;

class Parser{
    public static function parseContent($content){
        $moduleID = "maxknowbox.iblockshortcodes";
        $isEnabled = Option::get($moduleID, "is_enabled", false);
        if($isEnabled == "Y"){
            //  Определяем url
            $url = explode('?', $_SERVER['REQUEST_URI'])[0];
            $isAdminUrl = strpos($url, "/bitrix/admin");

            // Если вы не в админке
            if($isAdminUrl !== 0){
                // Определяем название файла по шорткоду
                $dir = Option::get($moduleID, "templates_dir", false);
                $arr = [];
                $html1 = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
                $html2 = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html1);
                $html3 = preg_replace('#<div id="bx-panel"(.*?)>(.*?)</div>#', '', $html2);
                preg_match_all('/\{.*?}/', $html3, $arr);
                $filenames = str_replace("{", '', str_replace("}", '', $arr[0]));

                // Меняем шорткод на блок из файлов
                foreach($filenames as $filename){
                    if(!empty($filename) && $filename !== ''){
                        if(strpos($filename, "'content_url'") !== 0 && strpos($filename, "window.") !== 0){
                            $shortcode = "{".$filename."}";
                            $path = $_SERVER['DOCUMENT_ROOT'].$dir.'/'.$filename.'.php';
                            if(is_file($path)){
                                ob_start();
                                require_once $path;
                                $html = ob_get_clean();
                                $content = str_replace($shortcode, $html, $content);
                                ob_end_flush();
                            }
                        }
                    }
                }

                // Выводим страницу
                echo $content;
                die();
            }
        }
    }
}