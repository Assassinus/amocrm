<?php
/**
 * Created by PhpStorm.
 * User: zaman
 * Date: 20.05.2018
 * Time: 18:28
 */

class Form
{

    public $name;

    public $email;

    public $phone;

    public $price;


    private $loadErrors = [
        'name' => 'Не заполнено поле Имя',
        'email' => 'Не заполнено поле Email',
        'phone' => 'Не заполнено поле Телефон',
        'price' => 'Не заполнено поле Цена',
    ];

    /**
     * Метод для валидации (не реализован по причине отсутствия в ТЗ)
     *
     * по умолчанию валидация пройдена
     * @return bool
     */
    public function validate()
    {

        /**
         * TO DO валидация
         */

        return true;

    }

    /**
     *  Метод для загрузки всех полей в форму
     *
     * @return bool
     */
    public function load()
    {
        foreach ($this->loadErrors as $field => $error){
            if(empty($_POST[$field])) {
                $this->errors[] = $error;
            } else {
                $this->{$field} = $_POST[$field];
            }
        }
        if (empty($this->errors)) {
            return true;
        } else {
            return false;
        }
    }

    public $errors = [];


}