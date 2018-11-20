<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 2018/1/29
 * Time: 下午5:18
 */
namespace Phpyk\Utils;

class DateTime
{
    public static function getCurrentWeekSection()
    {
        //当前日期
        $sdefaultDate = date("Y-m-d");
        //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $first = 1;
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($sdefaultDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $weekStart = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        //本周结束日期
        $weekEnd = date('Y-m-d', strtotime("$weekStart +6 days"));
        return [
            'start' => strtotime($weekStart),
            'end' => strtotime($weekEnd)
        ];
    }

    public static function getCurrentMonthSection()
    {
        //当前月份
        $sdefaultDate = date("Y-m");
        $monthStart = strtotime($sdefaultDate);
        $nextMonth = strtotime('+1 month',$monthStart);
        $monthEnd = $nextMonth - 1;
        return [
            'start' => $monthStart,
            'end' => $monthEnd
        ];
    }


    /**
     * 友好的时间显示
     *
     * @param int    $sTime 待显示的时间
     * @return string
     */
    public static  function friendlyDate($sTime)
    {
        if (!$sTime) return '';

        $cTime      =   time();
        $dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
        $dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));

        if($dYear == 0 && $dDay == 0){
            return date('H:i',$sTime);
        }elseif ($dYear == 0 && $dDay < 2) {
            return '昨天'.date('H:i',$sTime);
        }elseif($dYear == 0){
            return date("m月d日 H:i",$sTime);
        }else{
            return date("Y-m-d H:i",$sTime);
        }
    }

}