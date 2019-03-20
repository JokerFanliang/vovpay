<?php

namespace App\Common;

class RespCode
{
    const DECRYPT_FAILED     = ['respCode'=>'10001', 'msg'=>'数据解密失败'];
    const CHECK_SIGN_FAILED  = ['respCode'=>'10002', 'msg'=>'数据验签失败'];
    const PARAMETER_ERROR    = ['respCode'=>'10003', 'msg'=>'缺少参数'];
    const MERCHANT_NOT_EXIST = ['respCode'=>'10004', 'msg'=>'商户不存在或未启用'];
    const MCH_BIZ_NOT_OPEN   = ['respCode'=>'10005', 'msg'=>'商户业务未开通'];
    const PARAMETER_ERROR_TYPE = ['respCode'=>'10006','msg'=>'参数类型错误'];
    const PARAMETER_ERROR_PRICE= ['respCode'=>'10007','msg'=>'交易金额错误'];
    const TRADE_BIZ_NOT_OPEN = ['respCode'=>'20001', 'msg'=>'支付方式不存在或未开通'];
    const CHANNEL_NOT_EXIST  = ['respCode'=>'20002', 'msg'=>'交易通道不存在或未开通'];
    const RESOURCE_NOT_FOUND = ['respCode'=>'404',   'msg'=>'资源未找到'];
    const ACCOUNT_NOT_START  = ['respCode'=>'20002', 'msg'=>'账号未配置'];
    const APP_ERROR          = ['respCode'=>'20003', 'msg'=>'APP或账号已限额'];
    const FAILED             = ['respCode'=>'500', 'msg'=>'处理失败'];
    const TRADE_ORDER_NOT_EXIST = ['respCode'=>'20004', 'msg'=>'交易订单不存在'];
    const SYS_ERROR          = ['respCode'=>'20005','msg'=>'系统错误' ];
    const PARAMETER_ERROR_STOP = ['respCode'=>'10008','msg'=>'交易已被终止'];
    const ORDER_REPEAT       = ['respCode'=>'10009','msg'=>'订单已重复'];
    const QRCODE_ERROR       = ['respCode'=>'10010','msg'=>'二维码获取失败'];


    const SUCCESS = ['respCode'=>'200', 'msg'=>'请求成功'];
    const WARN = ['respCode'=>'-1', 'msg'=>'网络异常，请稍后重试'];
    const PARAS_ERR = ['respCode'=>'-1', 'msg'=>'参数错误'];
    const UNAUTHORIZED = ['respCode'=>'401', 'msg'=>'未授权的访问'];
    const MERCHANT_ALREADY_EXIST = ['respCode'=>'10001', 'msg'=>'商户信息已存在'];
    const MCH_WAIT_EXAMINE = ['respCode'=>'10004', 'msg'=>'商户业务审核中'];
    const ORGRATE_NOT_EXIST = ['respCode'=>'10005', 'msg'=>'代理费率信息不存在'];
    const TRADE_ORDER_EXIST = ['respCode'=>'20003', 'msg'=>'交易订单号重复'];
    const TRADE_ORDER_STATUS_EX = ['respCode'=>'20005', 'msg'=>'交易订单状态异常'];
    /** 20006, "该通道的交易时间为${startTime}-${endTime}" */
    const NOT_ALLOW_TRADING_TIEME = ['respCode'=>'20006', 'msg'=>'该通道的交易时间为%s-%s'];
    /** 20007, "该收款方式的交易金额范围为${minAmount}-${maxAmount}元" */
    const AMOUNT_NOT_ALLOWED = ['respCode'=>'20007', 'msg'=>'该通道的交易金额范围为%0f-%0f元'];
    const ACCOUNT_FAILED = ['respCode'=>'20008', 'msg'=>'获取支付账号或云ip异常'];
    const QR_FAILED = ['respCode'=>'20009', 'msg'=>'获取固码异常'];
    const QR2_FAILED = ['respCode'=>'20010', 'msg'=>'获取动态码异常'];
    const VALID_BANK_CAR_FAILED = ['respCode'=>'60001', 'msg'=>'查询银行卡信息失败'];
    const BANK_CARD_VALID_FAILED = ['respCode'=>'60002', 'msg'=>'银行卡信息校验失败'];
    const IDENTITY_VALID_FAILED = ['respCode'=>'60003', 'msg'=>'实名认证失败'];
    const AMOUNT_ERROR = ['respCode'=>'70001', 'msg'=>'金额错误'];
    const SETT_REQ_ERROR = ['respCode'=>'70002', 'msg'=>'发起结算请求失败'];
    const RE_NOTIFY_ERROR = ['respCode'=>'70003', 'msg'=>'补单请求失败'];
    const INSERT_REDIS_ERROR = ['respCode'=>'70004', 'msg'=>'到账通知插入缓存失败'];
    const SEARCH_REDIS_ERROR = ['respCode'=>'70005', 'msg'=>'在缓存中查询到账通知失败'];
    const MQ_SEND_ERROR = ['respCode'=>'70006', 'msg'=>'发送MQ消息失败'];
    const RECIEV__ERROR = ['respCode'=>'70006', 'msg'=>'到账通知处理异常'];
    const INSERTQR_REDIS_ERROR = ['respCode'=>'70008', 'msg'=>'二维码插入redis失败'];
    const GET_QR_ERROR = ['respCode'=>'70009', 'msg'=>'获取二维码失败'];
    const NOTIFY_MCH_ERROR = ['respCode'=>'70010', 'msg'=>'通知下游商户失败'];
    const RECIEV_RECHARGE_ERROR = ['respCode'=>'70011', 'msg'=>'充值到账通知处理异常'];
}