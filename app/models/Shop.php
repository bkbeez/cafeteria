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
     *  Get Name
     *  @return string
     */
    static function getName($id)
    {
        $check = Shop::one("SELECT shop_name FROM shop WHERE id=:id;", array('id'=>$id));
        return ( (isset($check['shop_name'])&&$check['shop_name']) ? $check['shop_name'] : null );
    }

    /**
     *  Get Option
     *  @param  $selected, $addon
     *  @return htmls
     */
    static function getOption($selected=null, $addon=null)
    {
        $lang = App::lang();
        $status_n = Lang::get('NotAvailable');
        $htmls = ( $addon ? $addon : '' );
        $lists = Shop::sql("SELECT id,shop_name FROM shop WHERE status_id>0 ORDER BY sequence;");
        if( isset($lists)&&count($lists)>0 ){
            foreach($lists as $item){
                $htmls .= '<option value="'.$item['id'].'">'.$item['shop_name'].'</option>';
            }
            if( $selected ){
                $htmls = str_replace('value="'.$selected.'"', 'value="'.$selected.'" selected', $htmls);
            }
        }

        return $htmls;
    }

    /**
     *  Get Option All
     *  @param  $selected, $addon
     *  @return htmls
     */
    static function getOptionAll($selected=null, $addon=null)
    {
        $lang = App::lang();
        $status_n = Lang::get('NotAvailable');
        $htmls = ( $addon ? $addon : '' );
        $lists = Shop::sql("SELECT id,shop_name,status_id FROM shop ORDER BY sequence;");
        if( isset($lists)&&count($lists)>0 ){
            foreach($lists as $item){
                $htmls .= '<option value="'.$item['id'].'">'.$item['shop_name'].( ($item['status_id']<1) ? ' ('.$status_n.')' : null ).'</option>';
            }
            if( $selected ){
                $htmls = str_replace('value="'.$selected.'"', 'value="'.$selected.'" selected', $htmls);
            }
        }

        return $htmls;
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