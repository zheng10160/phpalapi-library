<?php
/**
 * ������������κ�����Ҫ��Ӧ������
 */

return array(
    /**
     * Ӧ�ýӿڲ��ͳһ����
     */
    'workman'=>array(
        'app_name'      =>"my_app",                  // ��ĿӦ������
        'socket_host'   =>"tcp://0.0.0.0:1212",      // socket���Ӷ˿ڵ�ַ
        'service_port'  =>"1238",                    // ����ע��˿�
        'lan_ip'        =>"127.0.0.1",               // ����ip���ֲ�ʽ����ʱʹ��ͨ��ip
        'process_count' =>4,                         // ������
        'start_port'    =>"2900",                    // �ڲ�ͨѶ��ʼ�˿�
        'heartbeat'     =>10,                        // �������ʱ�䣬��λ��
        'heartbeat_data'=> '{"type":"ping"}',        // ��������
        'default_server'=> "Index",                  // �ͻ�������ʱ����Ϣ��û��server����ʱ��Ĭ�ϵ���Ϣ������
        'default_action'=> "index",                  // �ͻ�������ʱ����Ϣ��û��action����ʱ��Ĭ�ϵ���Ϣ������
    )
);
