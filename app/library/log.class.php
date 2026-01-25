<?php
/**
 * Log Class
 */
class Log {

    /**
     * Login
     * @param  $logs
     * @return void
     */
    static function login($parameters){
        if( User::get('email') ){
            $logs = $parameters;
            if( !isset($logs['id'])||!$logs['id'] ){
                $logs['id'] = (new datetime())->format("YmdHis").Helper::randomNumber(12);
            }
            $logs['email'] = User::get('email');
            $logs['device'] = User::get('device');
            $logs['platform'] = User::get('platform');
            $logs['browser'] = User::get('browser');
            $logs['ip_client'] = User::get('ip_client');
            $logs['ip_server'] = User::get('ip_server');
            DB::create("INSERT INTO `xlg_login` (`id`,`email`,`date_at`,`device`,`platform`,`browser`,`ip_client`,`ip_server`,`action`,`status`,`message`) VALUES (:id,:email,NOW(),:device,:platform,:browser,:ip_client,:ip_server,:action,:status,:message);", $logs);
        }
    }

    /**
     * Member
     * @param  $logs
     * @return void
     */
    static function member($parameters){
        if( User::get('email') ){
            $logs = $parameters;
            if( !isset($logs['id'])||!$logs['id'] ){
                $logs['id'] = (new datetime())->format("YmdHis").Helper::randomNumber(12);
            }
            $logs['email'] = User::get('email');
            DB::create("INSERT INTO `xlg_member` (`id`,`email`,`mode`,`date_at`,`value`,`remark`) VALUES (:id,:email,:mode,NOW(),:value,:remark);", $logs);
        }
    }

    /**
     * Shop
     * @param  $logs
     * @return void
     */
    static function shop($parameters){
        if( User::get('email') ){
            $logs = $parameters;
            if( !isset($logs['id'])||!$logs['id'] ){
                $logs['id'] = (new datetime())->format("YmdHis").Helper::randomNumber(12);
            }
            $logs['email'] = User::get('email');
            DB::create("INSERT INTO `xlg_shop` (`id`,`email`,`mode`,`shop_id`,`date_at`,`title`,`value`,`remark`) VALUES (:id,:email,:mode,:shop_id,NOW(),:title,:value,:remark);", $logs);
        }
    }

}
?>