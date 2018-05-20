<?php
/**
 * Created by PhpStorm.
 * User: zaman
 * Date: 19.05.2018
 * Time: 18:49
 */

define('APP_DIR', __DIR__);

require_once __DIR__ . '/classes/Form.php';
require_once __DIR__ . '/classes/App.php';
require_once __DIR__ . '/classes/AmoCrm.php';


echo AmoCrm::auth() . '<br>';

$form = new Form();

if (!$form->load() || !$form->validate())
    App::showErrors($form);


$contact_id = AmoCrm::addContact($form);
echo "Контакт создан" . '<br>';

$lead =  AmoCrm::addLeadWidthContact($form, $contact_id);
echo "Заявка создана" . '<br>';