DROP TABLE IF EXISTS {$tblprefix}cmsgs;
CREATE TABLE {$tblprefix}cmsgs (
  lid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  content text NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid)
) TYPE=MyISAM AUTO_INCREMENT=220;

INSERT INTO {$tblprefix}cmsgs VALUES ('1','choosematype','请指定正确的会员档案类型!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('4','choosearchive','请指定正确的文档！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('5','poinarcnoche','未审的文档不能进行当前操作！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('6','noarchbrowspermis','您没有此文档的浏览权限！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('7','defineansaddtem','请定义答疑添加模板','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('8','addtemcon','请添加模板内容!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('9','choosecomobj','请指定正确的评论对象!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('10','noavailableitemoper','无效项目操作','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('11','defineanslisttem','请定义答疑列表模板','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('12','answerlisttemconno','答疑列表模板内容不能为空!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('13','purarcwantpaycur','购买此文档需要支付积分 : &nbsp;:&nbsp;','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('197','scoreoptionerr','评分选项错误!','1264679704');
INSERT INTO {$tblprefix}cmsgs VALUES ('15','definereltem','请定义相关模板!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('16','developing','此功能正在开发中，稍后推出。','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('17','younoarcbrowsepermis','您没有文档浏览权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('18','commentfunclo','评论功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('19','setcomitem','请设置交互项目!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('20','owancecommuamooverlim','当前操作有次数限制，您本月的操作次数已经到达限额！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('21','safecodeerr','验证码错误','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('22','userisforbid','所在的屏蔽组禁止了此功能','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('23','younoitempermis','您没有此项目的操作权限！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('24','overquick','您操作过于频繁，请稍候再操作！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('25','norepeatoper','请不要重复操作！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('26','commentsubmitsuc','评论提交成功!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('27','choosecomeditobj','请指定正确的评论编辑对象!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('28','chooseadminobj','请指定正确的管理对象!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('29','definecomlisttem','请定义评论列表模板','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('30','listtemcontentnotnu','评论列表模板内容不能为空!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('31','choosecommuitem','您所指定的操作项目不存在或被关闭！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('32','commuitemclo','此交互项目已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('33','parammissing','参数不完全!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('34','noattach','没有附件','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('35','attachdownerr','附件下载错误','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('36','poiattaconsoufie','请指定附件内容来源字段','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('37','choosemesid','请指定正确的信息ID','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('38','pointmessagenocheck','指定的信息未审','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('39','chooseavaimes','请指定正确的有效信息','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('40','pointconpagetemp','请指定内容页面模板','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('41','definerelaisopage','请定义相关独立页面','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('42','nocatabrowseperm','无类目浏览权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('43','choosecatacnod','请指定正确的类目节点','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('44','membercnameillegal','会员名称不合规范','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('45','passwordillegal','密码不合规范','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('46','loginsucceed','会员登录成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('47','outmemberactive','站外注册会员,需要激活!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('48','nocheckmember','未审会员!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('49','loginfailed','会员登录失败，还可以尝试 %s 次！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('50','memlogoutsucce','会员退出成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('51','choosemarchive','请指定正确的会员文档','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('52','pointmarchinoch','指定的会员模型未审','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('53','beusenoseapermis','所属会员组没有搜索权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('54','searchoverquick','搜索操作过于频繁','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('55','defaultclosedreason','网站正在维护，请稍后再连接。','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('56','choosecommentmem','请指定正确的评论的会员!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('57','setmemcommitem','请设置会员交互项目!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('58','memcommentfunclose','会员评论功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('59','nocommentpermis','您没有评论权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('60','onrepeataddcom','请不要重复添加评论!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('61','addcommenover','添加评论操作过于频繁!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('62','chooseflinkmem','请指定正确的友情链接的会员!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('63','memflinkfunclos','会员友情链接功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('64','noflinkpermis','您没有友情链接权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('65','dorepeataddflink','请不要重复添加友情链接!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('66','flinksubmitsucce','友情链接提交成功!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('67','choosereplyofmember','请指定正确的回复的会员!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('68','memreplyfunclos','会员回复功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('69','younoreplypermis','您没有回复权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('70','dorepeataddreply','请不要重复添加回复!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('71','addreplyoverquick','添加回复操作过于频繁!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('72','replysubmitsucceed','回复提交成功!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('73','choosereportmember','请指定正确的举报的会员!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('74','memreportfunclos','会员举报功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('75','noreportpermiss','您没有举报权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('76','dorepeataddrep','请不要重复添加举报!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('77','reportsubmitsucce','举报提交成功!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('78','nousernoofferpermis','游客没有报价权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('79','chooseofferobje','请选择报价对象','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('80','chooseofferobj','请指定正确的报价对象','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('81','younoarchivebrowsepermiss','您没有此文档浏览权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('82','votesucceed','投票成功!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('83','nousernooperatepermis','游客无操作权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('84','invalidvoteitem','无效投票项目!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('85','choosevoteoption','请选择投票选项!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('86','choosevoteitem','请指定正确的投票项目!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('87','choosereporteditobject','请指定正确的举报编辑对象!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('88','choosereportobject','请指定正确的举报对象','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('89','choosereplyeditobject','请指定正确的回复编辑对象!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('90','choosereplyobject','请指定正确的回复对象','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('91','userchecking','用户等待审核','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('92','emailactiving','Email激活中，请进入邮箱激活','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('93','memberregistersucce','会员注册成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('94','memregisterfail','会员注册失败','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('95','choosememchann','请指定正确的会员模型','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('96','erroroperate','错误操作','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('97','mememailillegal','会员Email不合规范','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('98','memcnamerepeat','会员名称重复','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('99','mempasswordillegal','会员密码不合规范','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('100','notsamepwd','两次输入密码不一致','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('101','memnamelengthillegal','会员名称长度不合规范','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('102','defaultregclosedreason','默认注册已关闭原因','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('103','dorepeatregist','请不要重复注册 [%s]','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('104','defpurchaserecordtemp','请定义购买记录模板','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('105','goodsaddfinish','商品添加完成','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('106','carovermaxgoodamo','购物车超出最大商品数量','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('107','goodalreadyexist','此商品已经存在','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('108','choosegoods','请指定正确的商品！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('109','nousnopurchasepermi','游客没有购买权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('110','offisttemcontentnot','报价列表模板内容不能为空!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('111','defineofferlisttem','请定义报价列表模板','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('112','offerfunclos','报价功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('113','offersubmitsucceed','报价添加成功！<br><br>\r\n请对报价作详细设置！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('114','inputofferprice','请输入报价价格','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('115','noadminbackareapermis','无管理后台权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('116','submit','提交','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('117','regcode','验证码','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('118','loginpassword','登录密码','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('119','adminaccount','管理账号','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('120','gobackindex','返回首页','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('121','loginmember','登录会员','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('122','logoutmember','退出会员','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('123','currentmember','当前会员','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('124','clickhere','如果浏览器没有跳转请点这里','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('125','adminbackarealogoutfin','管理后台退出完成','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('126','nosubbackareaenterpermis','没有子站后台进入权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('127','adminloginfinish','管理登录完成','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('128','adminbackareaipforbid','管理后台IP禁止','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('129','reportfunclos','举报功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('130','operatesucceed','操作成功！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('131','younopurcstriwanenocurr','您没有订阅此%s所需要的足够积分!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('132','youalrpurchasestritem','您已经订阅了此%s','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('133','nousernopurchpermi','游客没有购买权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('134','confchoosarchi','请指定正确的文档','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('135','succeed','成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('136','scoresucceed','评分成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('137','dontrepeatscore','请不要重复评分','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('138','younoscorepermis','您没有评分权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('139','scorefunclosed','评分功能已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('140','noavailablecommuitem','无效交互项目','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('141','choosemember','请指定正确的会员!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('142','friendaddsucce','好友添加成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('143','memberalreadyadd','此会员已经添加!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('144','friamountoverlim','好友数量超过限制','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('145','younoaddfripermis','您没有添加好友权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('146','favoriatefunclos','收藏功能关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('147','cannotaddyourself','不能添加你自已的!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('192','choosereportofmember','请选择举报的会员','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('149','favoritesucceed','收藏成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('150','memalrefavorite','会员已经收藏','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('151','favoriteamooverlimit','您的收藏夹的空间已满！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('152','younofavoriatpermis','您没有收藏权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('153','cannotfavoritemember','不能收藏此会员!','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('154','nousernofavoritepermis','游客没有收藏权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('155','archivealreadyfavorite','您已经收藏了当前文档，请查看收藏夹！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('156','dontnrepeatvote','请不要重复投票','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('157','choosevoteobject','请指定正确的投票对象','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('158','answerdelsucceed','答案删除成功','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('159','chooseanswer','请指定正确的答案','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('160','nooperatepermission','没有操作权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('195','updatesucceed','升级程序执行完毕，请手动更新全站的节点缓存。','1272080155');
INSERT INTO {$tblprefix}cmsgs VALUES ('164','operateoverdate','操作过期','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('165','chooseyouanswer','请选择你自已的答案','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('166','answeraddfinish','答案添加完成','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('167','answeroverminlength','答案超出最小字长','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('168','inputanswercontent','请输入答疑内容','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('169','questionclosed','问题已关闭','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('171','chooseproduct','请选择一个产品！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('172','offerexist','本产品已经在您的报价库中！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('173','noofferpms','您没有报价权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('174','nooffercommu','报价被关闭或不支持报价！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('175','mspacedisabled','个人中心未启用','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('176','membercnameillegal','会员名称无效','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('177','dontrepeatlogin','请不要重复登陆&nbsp;[%s]','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('178','mloginerrtimes','您尝试次数过多，请稍候再试...','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('179','nousernosubper','游客没有订阅权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('180','notnull','%s 不能为空！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('181','younocommentpermis','您没有评论权限！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('183','choosearctype','请选择文档类型！','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('184','noissuepermission','没有发表权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('185','choosecommentobject','选择评论对象','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('186','nomarcreadpermission','没有会员档案访问权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('187','choosecommentofmember','请选择要评论的会员','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('188','dorepeataddcomment','请不要重复发表评论','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('189','addcommentoverquick','发表评论操作太频繁','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('190','chooseflinkofmember','请选择友情连接的会员','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('191','younoflinkpermis','您没有友情连接权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('193','younoreportpermis','您没有举报权限','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('194','dorepeataddreport','不要重复提交举报','0');
INSERT INTO {$tblprefix}cmsgs VALUES ('198','arcallowance','您的发布数量已超过限额！','1265535551');
INSERT INTO {$tblprefix}cmsgs VALUES ('199','wap_no_this_feature','暂无该功能','1270454369');
INSERT INTO {$tblprefix}cmsgs VALUES ('200','wap_nocatabrowseperm','无类目浏览权限','1270454576');
INSERT INTO {$tblprefix}cmsgs VALUES ('201','wap_definereltem','请定义相关模板!','1270454624');
INSERT INTO {$tblprefix}cmsgs VALUES ('202','wap_empty_input','没有填写完整项目','1270455412');
INSERT INTO {$tblprefix}cmsgs VALUES ('203','wap_login_failed','帐号或密码错误\r\n(还有%s次重试机会)','1270455498');
INSERT INTO {$tblprefix}cmsgs VALUES ('204','wap_login_error_times','登陆错误次数太多！','1270455543');
INSERT INTO {$tblprefix}cmsgs VALUES ('205','wap_login_re_ok','已登陆，请勿重复！','1270455607');
INSERT INTO {$tblprefix}cmsgs VALUES ('206','wap_member_name_fail','会员名称不合法','1270455739');
INSERT INTO {$tblprefix}cmsgs VALUES ('207','wap_password_fail','密码不合法','1270455762');
INSERT INTO {$tblprefix}cmsgs VALUES ('208','wap_login_ok','登陆成功','1270455869');
INSERT INTO {$tblprefix}cmsgs VALUES ('209','wap_out_member_active','需要重新激活帐号\r\n(WAP暂不支持激活功能)','1270455904');
INSERT INTO {$tblprefix}cmsgs VALUES ('210','wap_nocheck_member','未审核帐号','1270455925');
INSERT INTO {$tblprefix}cmsgs VALUES ('211','wap_logout_ok','注销成功','1270455963');
INSERT INTO {$tblprefix}cmsgs VALUES ('212','wap_close','Wap 未开启','1270456300');
INSERT INTO {$tblprefix}cmsgs VALUES ('213','wap_choosecatacnod','请指定正确的类目节点','1270513492');
INSERT INTO {$tblprefix}cmsgs VALUES ('214','wap_choosearchive','请指定正确的文档！','1270513563');
INSERT INTO {$tblprefix}cmsgs VALUES ('215','wap_poinarcnoche','未审的文档不能进行当前操作！','1270513612');
INSERT INTO {$tblprefix}cmsgs VALUES ('216','wap_noarchbrowsperm','您没有此文档的浏览权限！','1270513704');
INSERT INTO {$tblprefix}cmsgs VALUES ('217','wap_cantdeductions','WAP暂不支持对收费页面的浏览','1270516537');
INSERT INTO {$tblprefix}cmsgs VALUES ('218','regcodeerror','验证码输入错误！','1272591089');
INSERT INTO {$tblprefix}cmsgs VALUES ('219','adminxchannel','管理专用模型！','1274190648');

