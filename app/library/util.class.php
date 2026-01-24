<?php
/**
 * Util Class
 */
class Util {

    /**
     * Member Role
     * @param string
     * @return string
     */
    static function memberRole()
    {
        return array('ADMIN','STAFF','USER');
    }

    /**
     * Member Role Name
     * @param id
     * @return string
     */
    static function memberRoleName($id)
    {
        return '['.$id.'] '.Lang::get($id);
    }

    /**
     * Member Role Option
     * @param string
     * @return string
     */
    static function memberRoleOption($all=false, $selected=null)
    {
        $htmls = '';
        foreach(Util::memberRole() as $id ){
            if( $all ){
                $htmls .= '<option value="'.$id.'">['.$id.'] '.Lang::get($id).'</option>';
            }else if( $id!='ADMIN' ){
                $htmls .= '<option value="'.$id.'">['.$id.'] '.Lang::get($id).'</option>';
            }   
        }
        if( $selected ){
            return str_replace('value="'.$selected.'"', 'value="'.$selected.'" selected', $htmls);
        }
        return $htmls;
    }

    /**
     *  Province Option
     *  @param  key
     *  @return value
     */
    static function provinceOption($selected=null, $addon=null)
    {
        $lang = App::lang();
        $htmls = ( $addon ? $addon : '' );
        $lists = DB::sql("SELECT IF(code<>'10',CONCAT('จ.',name_th),name_th) AS name_th, name_en FROM tbl_province ORDER BY IF(code='10',0,1), CONVERT(name_".$lang." USING tis620) ASC;");
        if( isset($lists)&&count($lists)>0 ){
            foreach($lists as $item){
                $htmls .= '<option value="'.$item['name_'.$lang].'">'.$item['name_'.$lang].'</option>';
            }
            if( $selected ){
                $htmls = str_replace('value="'.$selected.'"', 'value="'.$selected.'" selected', $htmls);
            }
        }

        return $htmls;
    }

}
?>