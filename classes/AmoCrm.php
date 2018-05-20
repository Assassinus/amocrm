<?php
/**
 * Created by PhpStorm.
 * User: zaman
 * Date: 20.05.2018
 * Time: 18:59
 */

class AmoCrm
{
    /**
     * данные для авторизации в AmoCrm
     */
    const USER = [
        'USER_LOGIN' => 'zamanov.aydin@gmail.com',
        'USER_HASH' => '9ce02ed5cb1ac5ada54b6bd007c6cb58'
    ];

    /**
     * URL метода для авторизации
     */
    const AUTH_LINK = 'https://zamanovaydin.amocrm.ru/private/api/auth.php?type=json';

    /**
     * URL метода для добавления контактов
     */
    const CONTACTS_LINK = 'https://zamanovaydin.amocrm.ru/api/v2/contacts';

    /**
     * URL метода для добавления сделок
     */
    const LEAD_LINK = 'https://zamanovaydin.amocrm.ru/api/v2/leads';

    /**
     * файл куки
     */
    const COOKIE_FILE = APP_DIR . '/cookie.txt';

    /**
     * ID поля email
     */
    const ID_EMAIL_FIELD = 207745;

    /**
     * ID поля телефон
     */
    const ID_PHONE_FIELD = 207743;

    /**
     * ID ответственного лица
     */
    const ID_RESPONSIBLE_USER = 19796272;
    const TEGS = 'важный,доставка';

    /**
     * коды возможных ошибок
     */
    const ERRORS = [
        301=>'Moved permanently',
        400=>'Bad request',
        401=>'Unauthorized',
        403=>'Forbidden',
        404=>'Not found',
        500=>'Internal server error',
        502=>'Bad gateway',
        503=>'Service unavailable'
    ];

    /**
     * Метод для отправления запроса к API
     *
     * URL API метода
     * @param $link
     * Отправляемые данные
     * @param $data
     * Принятые данные в JSON
     * @return mixed
     */
    public static function send($link, $data) {

        $curl=curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        curl_setopt($curl,CURLOPT_URL,$link);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_COOKIEFILE, AmoCrm::COOKIE_FILE);
        curl_setopt($curl,CURLOPT_COOKIEJAR, AmoCrm::COOKIE_FILE);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
        $out=curl_exec($curl);
        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

        $code=(int)$code;
        try
        {
            if($code!=200 && $code!=204) {
                throw new Exception(isset($errors[$code]) ? AmoCrm::ERRORS[$code] : 'Undescribed error',$code);
            }
        }
        catch(Exception $E)
        {
            die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
        }

        $response=json_decode($out,true);

        return $response;

    }


    /**
     * Метод для добавления контакта
     *
     * Объект класса Form
     * @param Form $form
     * ID созданного контакта
     * @return mixed
     */
    public static function addContact(Form $form)
    {

        $contact = [
            'add' => [
                [
                    'name' => $_POST['name'],
                    'responsible_user_id' => AmoCrm::ID_RESPONSIBLE_USER,
                    'created_by' => AmoCrm::ID_RESPONSIBLE_USER,
                    'tags' => AmoCrm::TEGS,
                    'custom_fields' => [
                        [
                            'id' => AmoCrm::ID_EMAIL_FIELD,
                            'values' => [
                                [
                                    'value' => $form->email,
                                    'enum' => 'WORK'
                                ]
                            ]
                        ],
                        [
                            'id' => AmoCrm::ID_PHONE_FIELD,
                            'values' => [
                                [
                                    'value' => $form->phone,
                                    'enum' => 'WORK'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = AmoCrm::send(AmoCrm::CONTACTS_LINK, $contact);

        return $response['_embedded']['items']['0']['id'];

    }

    /**
     * Метод для привязки контакта к сделке
     *
     * Объект класса Form
     * @param Form $form
     * ID контакта
     * @param $contact_id
     * @return mixed
     */
    public static function addLeadWidthContact(Form $form, $contact_id)
    {

        $lead = [
            'add' => [
                [

                    'created_at'=>1298904164,
                    'sale'=> $form->price,
                    'responsible_user_id'=>19796272,
                    'contacts_id'=> $contact_id
                ]

            ]
        ];

        $response = AmoCrm::send(AmoCrm::LEAD_LINK, $lead);

        return $response;

    }

    /**
     * Метод для авторизации в AmoCrm
     *
     * Статус авторизации
     * @return string
     */
    public static function auth()
    {

        $response = AmoCrm::send(AmoCrm::AUTH_LINK, AmoCrm::USER);

        $response = $response['response'];

        if(isset($response['auth']))
            return 'Авторизация прошла успешно';
        return 'Авторизация не удалась';

    }
}