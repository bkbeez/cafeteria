<?php
/**
 * User Class
 */
class User extends DB {

    /**
     * One
     * @param  sql, parameters
     * @return array
     */
    static function one($sql, $parameters=array(), $init=null){
        $result = DB::query($sql, $parameters);
        return ( isset($result[0]) ? $result[0] : null );
    }

    /**
     * Sql
     * @param  sql, parameters
     * @return array
     */
    static function sql($sql, $parameters=array(), $init=null){
        return DB::query($sql, $parameters);
    }

    /**
     *  Get
     *  @param  key
     *  @return value
     */
    static function get($key='id', $default=null)
    {
        if( isset($_SESSION['login'])&&isset($_SESSION['login']['user']) ){
            if( $key=='picture' ){
                if( isset($_SESSION['login']['user']['picture'])&&$_SESSION['login']['user']['picture'] ){
                    return APP_HOME.'/file/profile/'.$_SESSION['login']['user']['id'].'/'.$_SESSION['login']['user']['picture'].'?'.time();
                }else if( isset($_SESSION['login']['user']['picture_default'])&&$_SESSION['login']['user']['picture_default'] ){
                    return $_SESSION['login']['user']['picture_default'];
                }else{
                    return $default;
                }
            }
        }
        return ( (isset($_SESSION['login'])&&isset($_SESSION['login']['user'])&&isset($_SESSION['login']['user'][$key])&&$_SESSION['login']['user'][$key]) ? $_SESSION['login']['user'][$key] : $default );
    }

}
?>