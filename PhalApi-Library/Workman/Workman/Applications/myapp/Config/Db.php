<?php
namespace Config;
/**
 * mysql����
 * @author walkor
 */
class Db
{
    /**
     * ���ݿ��һ��ʵ�����ã���ʹ��ʱ����������ʹ��
     * $user_array = Db::instance('db1')->select('name,age')->from('users')->where('age>12')->query();
     * �ȼ���
     * $user_array = Db::instance('db1')->query('SELECT `name`,`age` FROM `users` WHERE `age`>12');
     * @var array
     */
    public static $db1 = array(
        'host'    => '',
        'port'    => 3306,
        'user'    => '',
        'password' => '',
        'dbname'  => '',
        'charset'    => 'utf8',
    );
}