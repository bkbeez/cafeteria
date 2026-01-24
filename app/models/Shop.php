<?php
/**
 * Shop Class
 */
class Shop extends DB {

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
     *  Generate Id
     *  @return id
     */
    static function generateId()
    {
        $year = date('Y');
        $check = Shop::one("SELECT id FROM shop WHERE SUBSTR(id FROM 1 FOR 4)=:check ORDER BY id DESC;", array('check'=>$year));
        $code = ( (isset($check['id'])&&$check['id']) ? sprintf("%1$02d", intval(str_replace($year,'',$check['id']))+1): '01' );

        return $year.$code;
    }

    /**
     *  Type Option
     *  @param  $selected, $addon
     *  @return htmls
     */
    static function typeOption($selected=null, $addon=null)
    {
        $lang = App::lang();
        $htmls = ( $addon ? $addon : '' );
        $lists = Shop::sql("SELECT id,name_th,name_en FROM shop_type ORDER BY sequence;");
        if( isset($lists)&&count($lists)>0 ){
            foreach($lists as $item){
                $htmls .= '<option value="'.$item['id'].'">'.$item['name_'.$lang].'</option>';
            }
            if( $selected ){
                $htmls = str_replace('value="'.$selected.'"', 'value="'.$selected.'" selected', $htmls);
            }
        }

        return $htmls;
    }

}
?>