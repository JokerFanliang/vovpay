DROP PROCEDURE IF EXISTS `account_day_count`;
delimiter ;;
CREATE PROCEDURE `account_day_count`()
  COMMENT '账号交易日统计'
BEGIN


#统计表字段
		#账号
		DECLARE dsys_account VARCHAR(50);
		#账号单日订单成功金额
		DECLARE dsys_amount decimal(12,2) default 0.0;
		#账号单日订单总数量
    DECLARE dsys_order_count	int(11) default 0;
		#账号单日成功订单数量
    DECLARE dsys_order_suc_count	int(11) default 0;
		#账号拥有者id
		DECLARE dsys_account_uid int(11) default 0;

		 -- 遍历数据结束标志
    DECLARE done INT DEFAULT FALSE;

		 -- 账号成功订单表游标
    DECLARE cur_oder CURSOR FOR select count(id),sum(amount),`account`,any_value(`phone_uid`) from pay_orders where `status`=1 and to_days(`updated_at`) = TO_DAYS(NOW()) GROUP BY `account`;
		-- 账号所有订单表游标
		DECLARE cur_count_oder CURSOR FOR select count(id),`account` from pay_orders where to_days(`updated_at`) = TO_DAYS(NOW()) GROUP BY `account`;
		 -- 将结束标志'done'绑定到游标,当遍历结束done会被设置为true
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

		-- 打开游标
    OPEN  cur_oder;
    -- 遍历
    order_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_oder INTO dsys_order_suc_count,dsys_amount,dsys_account,dsys_account_uid;
				 #判断遍历是否结束
         IF done THEN
            LEAVE order_loop;
         END IF;
				 #-------------------------------------开始有账号的订单----------------------------------------

				 #有账号订单统计
				 IF  dsys_account <> '' THEN
					 IF EXISTS (select id  from pay_account_day_counts where pay_account_day_counts.account=dsys_account and TO_DAYS(`updated_at`)=TO_DAYS(NOW()) )  THEN

					 update pay_account_day_counts set
								account=dsys_account,
								user_id=dsys_account_uid,
								account_amount=dsys_amount,
								account_order_suc_count=dsys_order_suc_count
						 where pay_account_day_counts.account=dsys_account and TO_DAYS(updated_at)=TO_DAYS(NOW());
					 ELSE
						 INSERT INTO pay_account_day_counts(account, user_id,account_amount,  account_order_count,account_order_suc_count,created_at,updated_at)
						 VALUES(dsys_account,dsys_account_uid,dsys_amount, 0,dsys_order_suc_count,NOW(),NOW()) ;
					 END IF;
				 END IF;

				 #------------------------------------------结束遍历-----------------------------------
    END LOOP;
    CLOSE cur_oder;

		#SELECT dsys_order_suc_count,dsys_amount,dsys_account;

		 SET done = FALSE;
		  -- 打开游标
    OPEN  cur_count_oder;
-- 遍历
    order_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_count_oder INTO dsys_order_count, dsys_account;
				 #判断遍历是否结束
         IF done THEN
            LEAVE order_loop;
         END IF;
				 #-------------------------------------开始有账号的订单----------------------------------------
					#有账号订单统计
				 IF dsys_account THEN
					 SET dsys_order_count = dsys_order_count;
				 END IF;
				 #------------------------------------------结束遍历-----------------------------------
		#SELECT dsys_order_count;
		IF dsys_account <> '' THEN
			 IF EXISTS (select id  from pay_account_day_counts where pay_account_day_counts.account=dsys_account and TO_DAYS(updated_at)=TO_DAYS(NOW()) )  THEN

						update pay_account_day_counts set
								account_order_count=dsys_order_count
						 where pay_account_day_counts.account=dsys_account and TO_DAYS(updated_at)=TO_DAYS(NOW());
			 ELSE
						 INSERT INTO pay_account_day_counts(account,account_amount,  account_order_count,account_order_suc_count,created_at)
						 VALUES(dsys_account,0,  dsys_order_count,0,NOW()) ;
			 END IF;
		END IF;
		END LOOP;

		CLOSE cur_count_oder;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for account_yesterday_count
-- ----------------------------
DROP PROCEDURE IF EXISTS `account_yesterday_count`;
delimiter ;;
CREATE PROCEDURE `account_yesterday_count`()
  COMMENT '账号昨日交易日统计'
BEGIN

#统计表字段
		#账号
		DECLARE dsys_account VARCHAR(50);
		#账号单日订单成功金额
		DECLARE dsys_amount decimal(12,2) default 0.0;
		#账号单日订单总数量
    DECLARE dsys_order_count	int(11) default 0;
		#账号单日成功订单数量
    DECLARE dsys_order_suc_count	int(11) default 0;
		#账号拥有者id
		DECLARE dsys_account_uid int(11) default 0;

		 -- 遍历数据结束标志
    DECLARE done INT DEFAULT FALSE;

		 -- 账号成功订单表游标
    DECLARE cur_oder CURSOR FOR select count(id),sum(amount),`account`,any_value(`phone_uid`) from pay_orders where `status`=1 and to_days(`updated_at`) = TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY)) GROUP BY `account`;
		-- 账号所有订单表游标
		DECLARE cur_count_oder CURSOR FOR select count(id), `account` from pay_orders where to_days(`updated_at`) = TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY)) GROUP BY `account`;
		 -- 将结束标志'done'绑定到游标,当遍历结束done会被设置为true
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

		-- 打开游标
    OPEN  cur_oder;
    -- 遍历
    order_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_oder INTO dsys_order_suc_count,dsys_amount,dsys_account,dsys_account_uid;
				 #判断遍历是否结束
         IF done THEN
            LEAVE order_loop;
         END IF;
				 #-------------------------------------开始有账号的订单----------------------------------------
					#有账号订单统计

				 IF  dsys_account <> '' THEN
					 IF EXISTS (select id  from pay_account_day_counts where pay_account_day_counts.account=dsys_account and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY)) )  THEN

					 update pay_account_day_counts set
								account=dsys_account,
								user_id=dsys_account_uid,
								account_amount=dsys_amount,
								account_order_suc_count=dsys_order_suc_count
						 where pay_account_day_counts.account=dsys_account and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY));
					 ELSE
						 INSERT INTO pay_account_day_counts(account,user_id, account_amount,  account_order_count,account_order_suc_count,updated_at)
						 VALUES(dsys_account,dsys_account_uid,dsys_amount, 0,dsys_order_suc_count,DATE_SUB(CURDATE(),INTERVAL 1 DAY)) ;
					 END IF;
				 END IF;
				 #------------------------------------------结束遍历-----------------------------------
    END LOOP;
    CLOSE cur_oder;

		#SELECT dsys_order_suc_count,dsys_amount,dsys_account;

		 SET done = FALSE;
		  -- 打开游标
    OPEN  cur_count_oder;
-- 遍历
    order_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_count_oder INTO dsys_order_count,dsys_account;
				 #判断遍历是否结束
         IF done THEN
            LEAVE order_loop;
         END IF;
				 #-------------------------------------开始有账号的订单----------------------------------------
					#有账号订单统计
				 IF dsys_account THEN
					 SET dsys_order_count = dsys_order_count;
				 END IF;
				 #------------------------------------------结束遍历-----------------------------------
		#SELECT dsys_order_count;

		IF dsys_account <> '' THEN
			 IF EXISTS (select id  from pay_account_day_counts where pay_account_day_counts.account=dsys_account and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY)) )  THEN

						update pay_account_day_counts set
								account_order_count=dsys_order_count
						 where pay_account_day_counts.account=dsys_account and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY));
			 ELSE
						 INSERT INTO pay_account_day_counts(account,account_amount,  account_order_count,account_order_suc_count,updated_at)
						 VALUES(dsys_account,0,  dsys_order_count,0,DATE_SUB(CURDATE(),INTERVAL 1 DAY)) ;
			 END IF;
		END IF;

		END LOOP;

		CLOSE cur_count_oder;



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for user_order_day_count
-- ----------------------------
DROP PROCEDURE IF EXISTS `user_order_day_count`;
delimiter ;;
CREATE PROCEDURE `user_order_day_count`()
  COMMENT '用户订单日统计'
BEGIN

#Routine body goes here...
	  #用户表字段
    DECLARE  uid int(10);   -- id
    DECLARE  umerchant  varchar(10); -- 商户号
    DECLARE  uparentId  varchar(10); -- 代理id
    DECLARE  ugroup_type tinyint(3);   -- 类型

		#统计表字段
		#平台单日订单成功金额
		DECLARE dsys_amount decimal(12,2) default 0.0;
	  #平台单日收入金额
	  DECLARE dsys_income	decimal(12,2)  default 0.0;
		#平台单日订单总数量
    DECLARE dsys_order_count	int(11) default 0;
		#平台单日成功订单数量
    DECLARE dsys_order_suc_count	int(11) default 0;

		#商户单日订单成功金额
		DECLARE dmerchant_amount  decimal(12,2)  default 0.0;
		#'商户单日收入金额',
		DECLARE dmerchant_income decimal(12,2)  default 0.0;
		#'商户单日订单量',
		DECLARE dmerchant_order_count int(11)  default 0;
		#'商户单日成功订单量',
		DECLARE dmerchant_order_suc_count int(11) default 0;

		# 单日订单 金额
		DECLARE day_amount  decimal(12,2) ;
		# 单日收入金额
		DECLARE day_income decimal(12,2) ;
		# 单日订单量 ,
		DECLARE day_order_count int(11)  default 0;
    # 单日状态 ,
		DECLARE order_status tinyint(3);

    # 单日统计表id ,
		DECLARE day_id int(10)  default -1;

    -- 遍历数据结束标志
    DECLARE done INT DEFAULT FALSE;
    -- 订单表游标
    DECLARE cur_oder CURSOR FOR select count(id),sum(amount),sum(sysAmount),`status` from pay_orders where TO_DAYS(updated_at)=TO_DAYS(NOW()) GROUP BY `status`;
    -- 用户表游标
    DECLARE cur_account CURSOR FOR select id,merchant,parentId,group_type from pay_users where status=1;
    -- 将结束标志'done'绑定到游标,当遍历结束done会被设置为true
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

		#------------------------------------统计平台情况------------------------------------------------
    -- 打开游标
    OPEN  cur_oder;
    -- 遍历
    order_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_oder INTO day_order_count,day_amount,day_income,order_status;
				 #判断遍历是否结束
         IF done THEN
            LEAVE order_loop;
         END IF;
				 #-------------------------------------开始遍历成功和失败两行数据----------------------------------------
					#失败订单统计
				 IF order_status=0 THEN
					 SET dsys_order_count = dsys_order_count + day_order_count;
				 ELSEIF order_status=1 THEN
					#成功订单统计
					 SET dsys_amount=day_amount;
					 SET dsys_income=day_income;
					 SET dsys_order_suc_count=day_order_count;
					 SET dsys_order_count=dsys_order_count+day_order_count;
				 END IF;
				 #------------------------------------------结束遍历-----------------------------------
    END LOOP;
    CLOSE cur_oder;

		SELECT dsys_order_count,dsys_amount,dsys_income,dsys_order_suc_count;

		-- 开始第二个游标时先将 done 置为 FALSE
	  SET done = FALSE;
		#------------------------------------统计商户和代理情况------------------------------------------
    -- 打开游标
    OPEN  cur_account;
    -- 遍历
    read_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_account INTO uid,umerchant,uparentId,ugroup_type;
				 #判断遍历是否结束
         IF done THEN
            LEAVE read_loop;
         END IF;
				 #输出 结果
				 #select	uid,umerchant,uparentId,ugroup_type;
				 #------------------------------------------开始遍历----------------------------------------
				 #1用户，2代理商
				 IF (ugroup_type=1) THEN
						select count(id),IFNULL(sum(amount),0.0),IFNULL(sum(userAmount),0.0) INTO dmerchant_order_suc_count,dmerchant_amount,dmerchant_income from pay_orders where  pay_orders.user_id=uid and status=1 and TO_DAYS(updated_at)=TO_DAYS(NOW())  ;
						select count(id) INTO dmerchant_order_count  from pay_orders where  pay_orders.user_id=uid  and TO_DAYS(updated_at)=TO_DAYS(NOW()) ;
				 ELSEIF (ugroup_type=2) THEN
						#按订单的agent_id进行查询 代理收入
						select count(id),IFNULL(sum(amount),0.0),IFNULL(sum(agentAmount),0.0) INTO dmerchant_order_suc_count,dmerchant_amount,dmerchant_income from pay_orders where  pay_orders.agent_id=uid and status=1 and TO_DAYS(updated_at)=TO_DAYS(NOW()) ;
						select count(id) INTO dmerchant_order_count  from pay_orders where  pay_orders.agent_id=uid  and TO_DAYS(updated_at)=TO_DAYS(NOW());
				 END IF;

				 #输出统计结果
				 #select uid,uparentId, dmerchant_amount,dmerchant_income,dmerchant_order_count,dmerchant_order_suc_count,  dsys_amount,dsys_income,dsys_order_count,dsys_order_suc_count;
				  #查看本日当前用户是否已经有统计信息,有则更新 无则插入

				 IF EXISTS (select id  from pay_order_day_counts where pay_order_day_counts.user_id=uid and TO_DAYS(updated_at)=TO_DAYS(NOW()) )  THEN

					#`merchant_amount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '商户单日订单成功金额',
					#`merchant_income` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '商户单日收入金额',
					#`merchant_order_count` int(11) NOT NULL DEFAULT '0' COMMENT '商户单日订单量',
					#`merchant_order_suc_count` int(11) NOT NULL DEFAULT '0' COMMENT '商户单日成功订单量',
					#`sys_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '平台单日订单成功金额',
					#`sys_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '平台单日收入金额',
					#`sys_order_count` int(11) NOT NULL DEFAULT '0' COMMENT '平台单日订单总数量',
					#`sys_order_suc_count` int(11) NOT NULL DEFAULT '0' COMMENT '平台单日成功订单数量',


					 update pay_order_day_counts set
							merchant_amount=dmerchant_amount,
							merchant_income=dmerchant_income,
							merchant_order_count=dmerchant_order_count,
							merchant_order_suc_count=dmerchant_order_suc_count,
							sys_amount=dsys_amount,
							sys_income=dsys_income,
							sys_order_count=dsys_order_count,
							sys_order_suc_count=dsys_order_suc_count
					 where pay_order_day_counts.user_id=uid  and TO_DAYS(updated_at)=TO_DAYS(NOW());
				 ELSE
					 INSERT INTO pay_order_day_counts(user_id,agent_id,  merchant_amount,merchant_income,merchant_order_count,merchant_order_suc_count,  sys_amount,sys_income,sys_order_count,sys_order_suc_count)
					 VALUES(uid,uparentId,  dmerchant_amount,dmerchant_income,dmerchant_order_count,dmerchant_order_suc_count,  dsys_amount,dsys_income,dsys_order_count,dsys_order_suc_count) ;
				 END IF;

				 #------------------------------------------结束遍历-----------------------------------
    END LOOP;

		CLOSE cur_account;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for user_yesterday_order_day_count
-- ----------------------------
DROP PROCEDURE IF EXISTS `user_yesterday_order_day_count`;
delimiter ;;
CREATE PROCEDURE `user_yesterday_order_day_count`()
  COMMENT '用户订单昨日统计'
BEGIN

#Routine body goes here...
	  #用户表字段
    DECLARE  uid int(10);   -- id
    DECLARE  umerchant  varchar(10); -- 商户号
    DECLARE  uparentId  varchar(10); -- 代理id
    DECLARE  ugroup_type tinyint(3); -- 类型

		#统计表字段
		#平台单日订单成功金额
		DECLARE dsys_amount decimal(12,2) default 0.0;
	  #平台单日收入金额
	  DECLARE dsys_income	decimal(12,2)  default 0.0;
		#平台单日订单总数量
    DECLARE dsys_order_count	int(11) default 0;
		#平台单日成功订单数量
    DECLARE dsys_order_suc_count	int(11) default 0;

		#商户单日订单成功金额
		DECLARE dmerchant_amount  decimal(12,2)  default 0.0;
		#'商户单日收入金额',
		DECLARE dmerchant_income decimal(12,2)  default 0.0;
		#'商户单日订单量',
		DECLARE dmerchant_order_count int(11)  default 0;
		#'商户单日成功订单量',
		DECLARE dmerchant_order_suc_count int(11) default 0;

		# 单日订单 金额
		DECLARE day_amount  decimal(12,2) ;
		# 单日收入金额
		DECLARE day_income decimal(12,2) ;
		# 单日订单量 ,
		DECLARE day_order_count int(11)  default 0;
    # 单日状态 ,
		DECLARE order_status tinyint(3);

    # 单日统计表id ,
		DECLARE day_id int(10)  default -1;

    -- 遍历数据结束标志
    DECLARE done INT DEFAULT FALSE;
    -- 订单表游标
    DECLARE cur_oder CURSOR FOR select count(id),sum(amount),sum(sysAmount),`status` from pay_orders where TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY)) GROUP BY `status`;
    -- 用户表游标
    DECLARE cur_account CURSOR FOR select id,merchant,parentId,group_type from pay_users where status=1;
    -- 将结束标志'done'绑定到游标,当遍历结束done会被设置为true
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

		#------------------------------------统计平台情况------------------------------------------------
    -- 打开游标
    OPEN  cur_oder;
    -- 遍历
    order_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_oder INTO day_order_count,day_amount,day_income,order_status;
				 #判断遍历是否结束
         IF done THEN
            LEAVE order_loop;
         END IF;
				 #-------------------------------------开始遍历成功和失败两行数据----------------------------------------
					#失败订单统计
				 IF order_status=0 THEN
					 SET dsys_order_count = dsys_order_count + day_order_count;
				 ELSEIF order_status=1 THEN
					#成功订单统计
					 SET dsys_amount=day_amount;
					 SET dsys_income=day_income;
					 SET dsys_order_suc_count=day_order_count;
					 SET dsys_order_count=dsys_order_count+day_order_count;
				 END IF;
				 #------------------------------------------结束遍历-----------------------------------
    END LOOP;
    CLOSE cur_oder;

		#SELECT dsys_order_count,dsys_amount,dsys_income,dsys_order_suc_count;

		-- 开始第二个游标时先将 done 置为 FALSE
	  SET done = FALSE;
		#------------------------------------统计商户和代理情况------------------------------------------
    -- 打开游标
    OPEN  cur_account;
    -- 遍历
    read_loop: LOOP
         -- 游标按行取值 取多个字段
         FETCH  NEXT from cur_account INTO uid,umerchant,uparentId,ugroup_type;
				 #判断遍历是否结束
         IF done THEN
            LEAVE read_loop;
         END IF;
				 #输出 结果
				 #select	uid,umerchant,uparentId,ugroup_type;
				 #------------------------------------------开始遍历----------------------------------------
				 #1用户，2代理商
				 IF (ugroup_type=1) THEN
						select count(id),IFNULL(sum(amount),0.0),IFNULL(sum(userAmount),0.0) INTO dmerchant_order_suc_count,dmerchant_amount,dmerchant_income from pay_orders where  pay_orders.user_id=uid and status=1 and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY))  ;
						select count(id) INTO dmerchant_order_count  from pay_orders where  pay_orders.user_id=uid  and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY))  ;
				 ELSEIF (ugroup_type=2) THEN
						#按订单的agent_id进行查询 代理收入
						select count(id),IFNULL(sum(amount),0.0),IFNULL(sum(agentAmount),0.0) INTO dmerchant_order_suc_count,dmerchant_amount,dmerchant_income from pay_orders where  pay_orders.agent_id=uid and status=1 and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY))  ;
						select count(id) INTO dmerchant_order_count  from pay_orders where  pay_orders.agent_id=uid  and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY))  ;
				 END IF;

				 #输出统计结果
				 #select uid,uparentId, dmerchant_amount,dmerchant_income,dmerchant_order_count,dmerchant_order_suc_count,  dsys_amount,dsys_income,dsys_order_count,dsys_order_suc_count;
				  #查看本日当前用户是否已经有统计信息,有则更新 无则插入

				 IF EXISTS (select id  from pay_order_day_counts where pay_order_day_counts.user_id=uid and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY)) )   THEN

					#`merchant_amount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '商户单日订单成功金额',
					#`merchant_income` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '商户单日收入金额',
					#`merchant_order_count` int(11) NOT NULL DEFAULT '0' COMMENT '商户单日订单量',
					#`merchant_order_suc_count` int(11) NOT NULL DEFAULT '0' COMMENT '商户单日成功订单量',
					#`sys_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '平台单日订单成功金额',
					#`sys_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '平台单日收入金额',
					#`sys_order_count` int(11) NOT NULL DEFAULT '0' COMMENT '平台单日订单总数量',
					#`sys_order_suc_count` int(11) NOT NULL DEFAULT '0' COMMENT '平台单日成功订单数量',


					 update pay_order_day_counts set
							merchant_amount=dmerchant_amount,
							merchant_income=dmerchant_income,
							merchant_order_count=dmerchant_order_count,
							merchant_order_suc_count=dmerchant_order_suc_count,
							sys_amount=dsys_amount,
							sys_income=dsys_income,
							sys_order_count=dsys_order_count,
							sys_order_suc_count=dsys_order_suc_count
					 where pay_order_day_counts.user_id=uid  and TO_DAYS(updated_at)=TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 DAY));
				 ELSE
					 INSERT INTO pay_order_day_counts(user_id,agent_id,  merchant_amount,merchant_income,merchant_order_count,merchant_order_suc_count,  sys_amount,sys_income,sys_order_count,sys_order_suc_count,updated_at)
					 VALUES(uid,uparentId,  dmerchant_amount,dmerchant_income,dmerchant_order_count,dmerchant_order_suc_count,  dsys_amount,dsys_income,dsys_order_count,dsys_order_suc_count,DATE_SUB(CURDATE(),INTERVAL 1 DAY)) ;
				 END IF;

				 #------------------------------------------结束遍历-----------------------------------
    END LOOP;

		CLOSE cur_account;


END
;;
delimiter ;

-- ----------------------------
-- Event structure for account_day_event
-- ----------------------------
DROP EVENT IF EXISTS `account_day_event`;
delimiter ;;
CREATE EVENT `account_day_event`
ON SCHEDULE
EVERY '10' MINUTE STARTS '2019-01-19 13:56:44'
DO CALL account_day_count
;;
delimiter ;

-- ----------------------------
-- Event structure for account_yesterday_event
-- ----------------------------
DROP EVENT IF EXISTS `account_yesterday_event`;
delimiter ;;
CREATE EVENT `account_yesterday_event`
ON SCHEDULE
EVERY '1' DAY STARTS '2019-01-20 00:00:05'
DO CALL account_yesterday_count
;;
delimiter ;

-- ----------------------------
-- Event structure for user_order_day_event
-- ----------------------------
DROP EVENT IF EXISTS `user_order_day_event`;
delimiter ;;
CREATE EVENT `user_order_day_event`
ON SCHEDULE
EVERY '10' MINUTE STARTS '2019-01-14 11:09:41'
ON COMPLETION PRESERVE
DO CALL user_order_day_count
;;
delimiter ;

-- ----------------------------
-- Event structure for user_yesterdyay_order_event
-- ----------------------------
DROP EVENT IF EXISTS `user_yesterdyay_order_event`;
delimiter ;;
CREATE EVENT `user_yesterdyay_order_event`
ON SCHEDULE
EVERY '1' DAY STARTS '2019-01-15 00:00:05'
ON COMPLETION PRESERVE
DO CALL user_yesterday_order_day_count
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;