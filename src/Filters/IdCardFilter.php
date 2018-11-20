<?php
/**
 * 身份证验证
 */
namespace Phpyk\Utils\Filters;

class IdCardFilter
{

    private $birthYear;
    private $birthMonth;
    private $birthDate;
    private $gender = 1;

    /**
     * 获取出生年
     * @return int
     */
    public function getBirthYear()
    {
        return intval($this->birthYear);
    }

    /**
     * 获取出生月
     * @retur string
     */
    public function getBirthMonth()
    {
        return $this->birthMonth;
    }

    /**
     * 获取出生日期
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * 获取性别 1=男, 2=女
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    public function filter($id_card)
    {
        $this->birthYear = '';
        $cardNo = trim($id_card);
        if($this->isChinaCard($cardNo))
        {
            return $cardNo;
        }elseif($this->isAgentNumber($cardNo))
        {
            return $cardNo;
        }elseif($this->isHongKongCard($cardNo))
        {
            return $cardNo;
        }elseif($this->isTaiWanCard($cardNo))
        {
            return $cardNo;
        }elseif($this->isMacaoCard($cardNo))
        {
            return $cardNo;
        }elseif($this->isPassport($cardNo))
        {
            return $cardNo;
        }elseif($this->isTaiBaoCard($cardNo)){
            return substr($cardNo, 3);
        }else
        {
            return false;
        }
    }

    // 计算身份证校验码，根据国家标准GB 11643-1999
    function VerifyIdCardNumber($number)
    {
        if(strlen($number) != 17)
        {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verifyNumberList = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($number); $i++)
        {
            $checksum += substr($number, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verifyNumber = $verifyNumberList[$mod];
        return $verifyNumber;
    }

    // 将15位身份证升级到18位
    function reformatLen15to18($idcard){
        if (strlen($idcard) != 15){
            return false;
        }else{
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
                $idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
            }else{
                $idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
            }
        }
        $idcard = $idcard . $this->VerifyIdCardNumber($idcard);
        return $idcard;
    }

    // 18位身份证校验码有效性检查
    function valideCheckLen18($idcard){
        if (strlen($idcard) != 18){ return false; }
        $idcard_base = substr($idcard, 0, 17);
        if ($this->VerifyIdCardNumber($idcard_base) != strtoupper(substr($idcard, 17, 1))){
            return false;
        }else{
            return $idcard;
        }
    }

    /**
     * 是否中国大陆身份证号
     * @param string $card_no 身份证号
     * @return bool
     */
    public function isChinaCard($card_no)
    {
        $card_no = strtoupper($card_no);
        if(strlen($card_no) == 15)
        {
            $r = substr($card_no, 14, 1);
            $card_no = $this->reformatLen15to18($card_no);
        }else
        {
            $r = substr($card_no, 16, 1);
        }
        $this->gender = ($r%2==0) ? 2 : 1;
        //$card_no = strlen($card_no) == 15 ? $this->idcard_15to18($card_no) : $card_no;
        $this->birthYear = substr($card_no, 6, 4);
        $this->birthMonth = substr($card_no, 10, 2);
        $this->birthDate = substr($card_no, 12, 2);
        return $this->valideCheckLen18($card_no);
    }

    /**
     * 是否开头是万色店主编号的号码
     * @param string $card_no 编号
     * @return bool
     */
    public function isAgentNumber($card_no)
    {
        $letter = strtoupper(substr($card_no, 0 ,2));
        return $letter == 'FS' ? true : false;
    }

    /**
     * 是否中国香港身份证号
     * @param string $card_no 身份证号
     * @return bool
     */
    public function isHongKongCard($card_no)
    {
        $card_no = str_replace(array('（', '）'), array('(', ')'), $card_no);
        $pattern = '/^[a-z]\d{2,7}\([\da]\)$/i';
        $match = preg_match($pattern, $card_no);
        if($match)
        {
            $alpha = strtolower($card_no[0]);
            $first = ord($alpha) - 96;
            $sum = $first*8 + $card_no[1]*7 + $card_no[2]*6 + $card_no[3]*5 + $card_no[4]*4 + $card_no[5]*3 + $card_no[6]*2;
            if(intval($card_no[8]) == 11 - $sum % 11)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 是否中国台湾身份证号
     * @param string $card_no 身份证号
     * @return bool
     */
    public function isTaiWanCard($card_no)
    {
        $pattern = '/^[a-z]\d{9}$/i';
        $match = preg_match($pattern, $card_no);
        if($match)
        {
            $sum = $card_no[1]*8 + $card_no[2]*7 + $card_no[3]*6 + $card_no[4]*5 + $card_no[5]*4 + $card_no[6]*3 + $card_no[7]*2 + $card_no[8]*1 + $card_no[9];
            if(intval($card_no[9]) == $sum % 10)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 输入以TBZ开头,的8-10位数字判定为台胞证
     * @param string $card_no
     * @return bool
     */
    public function isTaiBaoCard($card_no)
    {
        $pattern = '/^TBZ\d{8,10}$/i';
        $match = preg_match($pattern, $card_no);
        return $match ? true : false;
    }

    /**
     * 是否中国澳门身份证号
     * @param string $card_no 身份证号
     * @return bool
     */
    public function isMacaoCard($card_no)
    {
        $card_no = str_replace(array('（', '）'), array('(', ')'), $card_no);
        $pattern = '/^(?:1|5|7)\d{7}\(\d\)$/i';
        $match = preg_match($pattern, $card_no);
        return $match ? true : false;
    }

    /**
     * 是否护照号
     * @param string $card_no 身份证号
     * @return bool
     */
    public function isPassport($card_no)
    {
        if(strlen($card_no) == 9 && substr($card_no, 0, 1) != '0')
        {
            $letter = strtoupper(substr($card_no, 0 ,4));
            if(!in_array($letter, array('1000','0200','0600'))) return true;

        }
        return false;
    }

    //检查身份证合法性
    public function checkIdCard($idcard){
        if(empty($idcard)){return false;}
        $City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
        $iSum = 0;
        $idCardLength = strlen($idcard);
        //长度验证
        if(!preg_match('/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/',$idcard)){
            return false;
        }
            //地区验证
        if(!array_key_exists(intval(substr($idcard,0,2)),$City)){
            return false;
        }
            // 15位身份证验证生日，转换为18位
        if ($idCardLength == 15){
            return false;
            // $sBirthday = '19'.substr($idcard,6,2).'-'.substr($idcard,8,2).'-'.substr($idcard,10,2);
            // $dd = date('Y-m-d', strtotime($sBirthday));
            // if($sBirthday != $dd){
            //     return false;
            // }
            // $idcard = substr($idcard,0,6)."19".substr($idcard,6,9);//15to18
            // $Bit18 = $this->getVerifyBit($idcard);//算出第18位校验码
            // $idcard = $idcard.$Bit18;
        }
            // 判断是否大于2078年，小于1900年
        $year = substr($idcard,6,4);
        if ($year<1900 || $year>2078 ){
            return false;
        }

        //18位身份证处理
        $sBirthday = substr($idcard,6,4).'-'.substr($idcard,10,2).'-'.substr($idcard,12,2);
        $dd = date('Y-m-d', strtotime($sBirthday));
        if($sBirthday != $dd){
           return false;
        }
            //身份证编码规范验证
        $idcardBase = substr($idcard,0,17);
        if(strtoupper(substr($idcard,17,1)) != $this->getVerifyBit($idcardBase)){
            return false;
        }
        return true;
    }

    // 计算身份证校验码，根据国家标准GB 11643-1999
    protected  function getVerifyBit($idcard_base){
        if(strlen($idcard_base) != 17){
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++){
                $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }
}