<?php

namespace App\Tool;

/**
 * 根据传入的银行编号，提取金额
 * Class RegularGetBankInfo
 * @package App\Tool
 */
class RegularGetBankInfo
{

    /**
     * 提取银行金额
     * @param string $number
     * @param string $content
     * @return string
     */

    public function getAmount(string $number, string $content){
        $content = str_replace(',','',$content);
        $content = str_replace('，','',$content);
        $amount = '0.00';
        switch ($number){
            case "95588"://中国工商银行
                preg_match('/\)(\d+|,\d{3})+(\.\d{0,4})?元/',$content,$matches);
                break;
            case "95533"://中国建设银行
                if(preg_match('/人民币(\d+|,\d{3})+(\.\d{0,4})?元/',$content,$matches)) {
                    break;
                }else
                {
                    preg_match('/客户付款(\d+|,\d{3})+(\.\d{0,4})?元/',$content,$matches);
                    break;
                }

            case "95599"://中国农业银行
                preg_match('/人民币(\d+|,\d{3})+(\.\d{0,4})?/',$content,$matches);
                break;
            case "95555"://招商银行
                preg_match('/人民币(\d+|,\d{3})+(\.\d{0,4})?/',$content,$matches);
                break;
            case "95561"://兴业银行
                preg_match('/收入(\d+|,\d{3})+(\.\d{0,4})?元/',$content,$matches);
                break;
            case "95566":// 中国银行
                preg_match('/人民币(\d+|,\d{3})+(\.\d{0,4})?/',$content,$matches);
                break;
            case "95568":// 民生银行
                preg_match('/存入￥(\d+)+(\.\d{0,4})?元/',$content,$matches);
                break;
            case "95508":// 广发银行
                preg_match('/人民币(\d+)+(\.\d{0,4})?元/',$content,$matches);
                break;
            case "95511":// 平安银行
                preg_match('/人民币(\d+)+(\.\d{0,4})?元/',$content,$matches);
                break;
            case "95558":// 中信银行
                preg_match('/人民币(\d+)+(\.\d{0,4})?元/',$content,$matches);
                break;
            default:
                return $amount;
        }
        if(count($matches) == 2){
            $amount = $matches[1];
        }else if(count($matches) == 3){
            $amount = $matches[1].$matches[2];
        }
        return sprintf("%.2f", $amount);
    }

    /**
     * 提取银行卡尾号
     * @param string $number
     * @param string $content
     * @return string
     */
    public function getCardNo(string $number, string $content){
        $cardNo = '';
        switch ($number){
            case "95588"://中国工商银行
                preg_match('/尾号([\d]{4})卡/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95533"://中国建设银行
                if(preg_match('/尾号([\d]{4})的/',$content,$matches)){
                    $cardNo = $matches[1];
                    break;
                }else{
                    //商户名称：河南先迈电子科技有限公司
                    $content = str_replace(':','',$content);
                    $content = str_replace('：','',$content);
                    $content = str_replace('，','',$content);
                    $content = str_replace(',','',$content);
                    preg_match('/商户名称(.+)付款方式/',$content,$matches);
                    $cardNo = $matches[1];
                    break;
                }

            case "95599"://中国农业银行
                preg_match('/尾号([\d]{4})账户/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95555"://招商银行
                preg_match('/人民币(\d+|,\d{3})+(\.\d{0,4})?/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95561"://兴业银行
                preg_match('/账户\*([\d]{4})\*/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95566"://中国银行
                preg_match('/账户([\d]{4})/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95568"://民生银行
                preg_match('/账户\*([\d]{4})于/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95508"://广发银行
                preg_match('/尾号([\d]{4})卡/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95511"://平安银行
                preg_match('/尾号([\d]{4})的/',$content,$matches);
                $cardNo = $matches[1];
                break;
            case "95558"://中信银行
                preg_match('/尾号([\d]{4})的/',$content,$matches);
                $cardNo = $matches[1];
                break;
            default:
                return $cardNo;
        }
        return $cardNo;
    }
}