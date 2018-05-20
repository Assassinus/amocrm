<?php
/**
 * Created by PhpStorm.
 * User: zaman
 * Date: 20.05.2018
 * Time: 18:32
 */

class App
{

    /**
     * Метод вывода ошибок
     *
     * @param $form
     * @throws Exception
     */
    public static function showErrors($form)
    {
    	$errors = '';

    	foreach ($form->errors as $error) {
    		$errors .= $error . '   ';
    	}

                throw new Exception($errors,316);
    }

}