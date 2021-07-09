<?php

namespace App\Http\Traits;

trait NotificationTrait {
    function notification($data, $model, $key, $text, $arr = null) {
        if($arr) {
            if($data[$key] && !$model->$key) {
                return "додано ".$text.": " . $arr[$data[$key]] . ";<br>";
            }
            if(($data[$key] != $model->$key) && $data[$key] && $model->$key) {
                return "змінено ".$text.": " . $arr[$model->$key] . " на " . $arr[$data[$key]] . ";<br>";
            }
            if(!$data[$key] && $model->$key) {
                return "видалено ".$text.": ".$arr[$model->$key].";<br>";
            }
        } else {
            if($data[$key] && !$model->$key) {
                return "додано ".$text.": " . $data[$key] . ";<br>";
            }
            if(($data[$key] != $model->$key) && $data[$key] && $model->$key) {
                return "змінено ".$text.": " . $model->$key . " на " . $data[$key] . ";<br>";
            }
            if(!$data[$key] && $model->$key) {
                return "видалено ".$text.": ".$model->$key.";<br>";
            }
        }
    }
}