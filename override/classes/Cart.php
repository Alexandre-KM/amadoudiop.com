<?php
 class Cart extends CartCore
{
    public $signature;
    public $ip_address;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'cart',
        'primary' => 'id_cart',
        'fields' => array(
            'id_shop_group' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_address_delivery' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_address_invoice' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_carrier' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_currency' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_guest' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_lang' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'recyclable' =>            array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'gift' =>                    array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'gift_message' =>            array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'mobile_theme' =>            array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'delivery_option' =>        array('type' => self::TYPE_STRING),
            'secure_key' =>            array('type' => self::TYPE_STRING, 'size' => 32),
            'allow_seperated_package' =>array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add' =>                array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' =>                array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

            'signature' =>            array('type' => self::TYPE_STRING),
            'ip_address' =>            array('type' => self::TYPE_STRING),

        ),
    );

    public static function addSignature($id_cart, $signature, $ip_address)
    {
      $data = array('signature' => pSQL($signature),'ip_address' => pSQL($ip_address));
      $where = 'id_cart='.$id_cart;
      $result = Db::getInstance()->update('cart',$data,$where,$limit = 0,$null_values = false,$use_cache = true,$add_prefix = true);
      return $result;
    }
}
