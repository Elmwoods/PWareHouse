update wst_sys_configs set fieldValue='1.4.1_170330' where fieldCode='wstVersion';
update wst_sys_configs set fieldValue='8a490db1dd2763617aee7fa0f851c243' where fieldCode='wstMd5';

INSERT INTO `wst_sys_configs` (fieldName,fieldCode,fieldValue,fieldType) VALUES ('用户提现金额限制', 'drawCashUserLimit',  '10', '0');
INSERT INTO `wst_sys_configs` (fieldName,fieldCode,fieldValue,fieldType) VALUES ('商家提现金额限制', 'drawCashShopLimit',  '10', '0');
update wst_privileges set otherPrivilegeUrl='admin/users/pageQuery,admin/logmoneys/tologmoneys,admin/logmoneys/pageQueryByUser,admin/logmoneys/pageQueryByShop,admin/userscores/touserscores,admin/userscores/pageQuery,admin/userscores/toAdd,admin/userscores/add' where privilegeCode='HYGL_00';

alter table wst_goods add isFreeShipping tinyint default 0;
update wst_privileges set otherPrivilegeUrl='admin/articles/delByBatch' where privilegeCode='WZGL_03';

insert into wst_datas(catId,dataName,dataVal) values(6,'订单评价提醒','ORDER_APPRAISES');


INSERT INTO `wst_template_msgs`(`tplType`,`tplCode`,`tplExternaId`,`tplContent`,`isEnbale`,`tplDesc`,`dataFlag`) 
VALUES ('0', 'ORDER_APPRAISES', '', '您的订单【${ORDER_NO}】商品【${GOODS}】已有新的用户评价。', '1', '1.变量说明：${GOODS}：商品名称 ${ORDER_NO}：订单号。<br/>2.为空则不发送。', '1');

UPDATE `wst_navs` set navUrl='brands.html' where navUrl='home/brands/index.html';
UPDATE `wst_navs` set navUrl='street.html' where navUrl='home/shops/shopstreet.html';
UPDATE `wst_navs` set navUrl='category-47.html' where navUrl='home/goods/lists/cat/47.html';
UPDATE `wst_navs` set navUrl='selfshop.html' where navUrl='home/shops/selfshop';
UPDATE `wst_navs` set navUrl='category-48.html' where navUrl='home/goods/lists/cat/48.html';
UPDATE `wst_navs` set navUrl='category-54.html' where navUrl='home/goods/lists/cat/54.html';

insert into wst_hooks(name,hookRemarks,hookType,updateTime,addons) values('homeBeforeGoShopHome','跳去店铺首页前执行','1','2017-02-13 08:10:25','');
insert into wst_hooks(name,hookRemarks,hookType,updateTime,addons) values('homeBeforeGoSelfShop','跳去自营店铺首页前执行','1','2017-02-13 08:10:25','');
alter table wst_orders add orderCodeTargetId int default 0;

alter table wst_payments add payFor varchar(100) default '';
update wst_payments set payFor='1,2,3,4' where payCode='cod';
update wst_payments set payFor='1,2' where payCode='alipays';
update wst_payments set payFor='1,3' where payCode='weixinpays';
update wst_payments set payFor='1,2,3,4' where payCode='wallets';

insert into wst_hooks(name,hookRemarks,hookType,updateTime,addons) values('wechatDocumentUserIndexTools','用户“我的”工具','1','2017-02-13 08:10:25','');
insert into wst_hooks(name,hookRemarks,hookType,updateTime,addons) values('mobileDocumentUserIndexTools','用户“我的”工具','1','2017-02-13 08:10:25','');
delete from wst_hooks where name='mobileAuctionUserIndex';
delete from wst_hooks where name='wechatAuctionUserIndex';


/*有微信版用户执行下边的语句*/
insert into wst_datas(catId,dataName,dataVal) values(9,'订单评价提醒','WX_ORDER_APPRAISES');
INSERT INTO `wst_template_msgs`(`tplType`,`tplCode`,`tplExternaId`,`tplContent`,`isEnbale`,`tplDesc`,`dataFlag`) 
VALUES ('3', 'WX_ORDER_APPRAISES', '', '{{first.DATA}}\n\n{{Content1.DATA}}\n商品名称：{{Good.DATA}}\n{{remark.DATA}}', '1', '1.变量说明：${GOODS}：商品名称 ${ORDER_NO}：订单号。<br/>2.为空则不发送。', '1');
insert into wst_sys_configs(fieldName,fieldCode,fieldValue,fieldType) values('微信公众号二维码','weixinCode','',1);

/*有开启插件用户看情况执行下边语句*/
UPDATE `wst_navs` set navUrl=replace(navUrl,'index.php/addons/execute/','addon/');
UPDATE `wst_home_menus` set menuUrl=replace(menuUrl,'index.php/addons/execute/','addon/'),menuOtherUrl=replace(menuUrl,'index.php/addons/execute/','addon/');
UPDATE `wst_privileges` set privilegeUrl=replace(privilegeUrl,'index.php/addons/execute/','addon/'),otherPrivilegeUrl=replace(otherPrivilegeUrl,'index.php/addons/execute/','addon/');
UPDATE `wst_mobile_btns` set btnUrl = replace(btnUrl,'index.php/addons/execute/','/addon/');
UPDATE `wst_mobile_btns` set btnUrl = replace(btnUrl,'addons/execute/','/addon/');
UPDATE `wst_crons`  set cronUrl=replace(cronUrl,'index.php/addons/execute/','addon/');
/*修复拍卖，团购数据库错误，有开启这两个功能则执行*/
update `wst_orders`  set orderCodeTargetId=replace(replace(extraJson,'{"grouponId":',''),'}','') where orderCode='groupon';
update `wst_orders`  set orderCodeTargetId=replace(replace(extraJson,'{"auctionId":',''),'}','') where orderCode='auction';

insert into wst_hooks(name,hookRemarks,hookType,updateTime,addons) values('wechatControllerIndexIndex','跳转商城首页前执行','1','2017-02-13 08:10:25','Distribut');


