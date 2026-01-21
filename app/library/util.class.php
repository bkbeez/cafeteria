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
     * Get Organization
     * @param string
     * @return string
     */
    static function getOrganization()
    {
        return array('คณะมนุษยศาสตร์'
                    ,'คณะศึกษาศาสตร์'
                    ,'คณะวิจิตรศิลป์'
                    ,'คณะสังคมศาสตร์'
                    ,'คณะวิทยาศาสตร์'
                    ,'คณะวิศวกรรมศาสตร์'
                    ,'คณะแพทยศาสตร์'
                    ,'คณะเกษตรศาสตร์'
                    ,'คณะทันตแพทยศาสตร์'
                    ,'คณะเภสัชศาสตร์'
                    ,'คณะเทคนิคการแพทย์'
                    ,'คณะพยาบาลศาสตร์'
                    ,'คณะอุตสาหกรรมเกษตร'
                    ,'คณะสัตวแพทยศาสตร์'
                    ,'คณะบริหารธุรกิจ'
                    ,'คณะเศรษฐศาสตร์'
                    ,'คณะสถาปัตยกรรมศาสตร์'
                    ,'คณะการสื่อสารมวลชน'
                    ,'คณะรัฐศาสตร์และรัฐประศาสนศาสตร์'
                    ,'คณะนิติศาสตร์'
                    ,'วิทยาลัยศิลปะ สื่อ และเทคโนโลยี'
                    ,'คณะสาธารณสุขศาสตร์'
                    ,'วิทยาลัยการศึกษาและการจัดการทางทะเล'
                    ,'วิทยาลัยนานาชาตินวัตกรรมดิจิทัล'
                    ,'สถาบันนโยบายสาธารณะ'
                    ,'สถาบันวิศวกรรมชีวการแพทย์'
                    ,'สถาบันวิจัยวิทยาศาสตร์สุขภาพ'
                    ,'วิทยาลัยพหุวิทยาการและสหวิทยาการ'
                    ,'วิทยาลัยการศึกษาตลอดชีวิต'
                    ,'วิทยาลัยนานาชาติ'
                    ,'วิทยาลัยบัณฑิตวิทยาลัย'
        );
    }

    /**
     * Organization Html
     * @param string
     * @return string
     */
    static function organizationOption($selected=null)
    {
        $htmls = '';
        foreach( Util::getOrganization() as $name ){
            $htmls .= '<option value="'.$name.'">'.$name.'</option>';
        }
        if( $selected ){
            return str_replace('value="'.$selected.'"', 'value="'.$selected.'" selected', $htmls);
        }
        return $htmls;
    }

    /**
     * Get Department
     * @param string
     * @return string
     */
    static function getDepartment()
    {
        return array('ภาควิชาพื้นฐานและการพัฒนาการศึกษา'
                    ,'ภาควิชาหลักสูตร การสอนและการเรียนรู้'
                    ,'ภาควิชาอาชีวศึกษาและการส่งเสริมสุขภาวะ'
                    ,'สำนักงานคณะศึกษาศาสตร์'
                    ,'โรงเรียนสาธิตมหาวิทยาลัยเชียงใหม่ (ระดับมัธยมศึกษา)'
                    ,'โรงเรียนสาธิตมหาวิทยาลัยเชียงใหม่ (ระดับอนุบาลและประถมศึกษา)'
        );
    }

    /**
     * Department Html
     * @param string
     * @return string
     */
    static function departmentOption($selected=null)
    {
        $htmls = '';
        foreach( Util::getDepartment() as $name ){
            $htmls .= '<option value="'.$name.'">'.$name.'</option>';
        }
        if( $selected ){
            return str_replace('value="'.$selected.'"', 'value="'.$selected.'" selected', $htmls);
        }
        return $htmls;
    }

}
?>