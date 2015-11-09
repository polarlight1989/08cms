# DatafileID: MTI3NDk0MzM3NywwOENNUywzLjUsaW5zdGFsbHNxbA==
# <?exit();?>
# 08cms InstallPack Data Dump
# Version: 08cms v3.5
# Date: 2010-05-27
# --------------------------------------------------------
# Home: www.08cms.com
# --------------------------------------------------------


DROP TABLE IF EXISTS {$tblprefix}cradminlogs;
CREATE TABLE {$tblprefix}cradminlogs (
  id mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  crid smallint(5) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL,
  frommid mediumint(8) unsigned NOT NULL default '0',
  frommname varchar(15) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  `mode` varchar(6) NOT NULL,
  `value` float NOT NULL default '0',
  dealmode varchar(10) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS {$tblprefix}msession;
CREATE TABLE {$tblprefix}msession (
  msid varchar(6) NOT NULL,
  onlineip varchar(15) NOT NULL,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(30) NOT NULL,
  mslastactive int(10) unsigned NOT NULL default '0',
  lastolupdate int(10) unsigned NOT NULL default '0',
  msclicks smallint(6) unsigned NOT NULL default '0',
  lastsearch int(10) unsigned NOT NULL default '0',
  errtimes tinyint(3) unsigned NOT NULL default '0',
  UNIQUE KEY msid (msid),
  KEY mid (mid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS {$tblprefix}alangs;
CREATE TABLE {$tblprefix}alangs (
  lid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  content text NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid)
) TYPE=MyISAM AUTO_INCREMENT=3563;

INSERT INTO {$tblprefix}alangs VALUES ('69','subsite','子站','0');
INSERT INTO {$tblprefix}alangs VALUES ('70','catalog','栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('71','inalbum_check','辑内审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('72','msite','主站','0');
INSERT INTO {$tblprefix}alangs VALUES ('65','id','ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('66','exit','退出','0');
INSERT INTO {$tblprefix}alangs VALUES ('67','altype','合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('68','author','作者','0');
INSERT INTO {$tblprefix}alangs VALUES ('64','current_album_set','已经归入的合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('63','modify_album_copys','修改合辑副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('60','catas_album_set','类目与合辑设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('61','permission_set','权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('62','modify_album','修改合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('53','freesale','免费','0');
INSERT INTO {$tblprefix}alangs VALUES ('52','arc_price','浏览文档售价','0');
INSERT INTO {$tblprefix}alangs VALUES ('54','annex_price','附件操作售价','0');
INSERT INTO {$tblprefix}alangs VALUES ('55','archive_content_template','文档内容模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('56','archive_plus_page','文档附加页面','0');
INSERT INTO {$tblprefix}alangs VALUES ('57','template','模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('58','add','添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('59','add_album','添加合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('50','content_permissions','内容权限方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('47','day','天','0');
INSERT INTO {$tblprefix}alangs VALUES ('48','max','最大','0');
INSERT INTO {$tblprefix}alangs VALUES ('49','set_valid_day','设置有效期(天)','0');
INSERT INTO {$tblprefix}alangs VALUES ('39','catasalbumset','类目与合辑设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('40','be_catalog','所属栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('41','noset','不设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('42','belong_album','所属合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('43','input_albumid','输入所属合辑ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('44','content_set','内容设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('45','more_set','更多设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('46','mini','最小','0');
INSERT INTO {$tblprefix}alangs VALUES ('73','exit_album','退出合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('74','all_catalog','全部栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('75','all_altype','全部合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('76','filter0_want_in_album','筛选需要归入的合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('77','belong_altype','所属合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('78','search_title','搜索标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('79','search_author','搜索作者','0');
INSERT INTO {$tblprefix}alangs VALUES ('80','agsearchkey','可含通配符 *','0');
INSERT INTO {$tblprefix}alangs VALUES ('81','add_date','添加日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('82','day_before','天前','0');
INSERT INTO {$tblprefix}alangs VALUES ('83','day_in','天内','0');
INSERT INTO {$tblprefix}alangs VALUES ('84','album_list','合辑列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('85','choose_want_setin_album','请选择需要归入的合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('86','title','标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('87','setalbum','归辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('88','exit_album_admin','退出合辑管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('89','archive_exit_album','文档退出合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('90','setalbum_admin','归辑管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('91','archive_manager','文档管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('92','album_manager','合辑管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('93','detail','详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('94','abover','完结','0');
INSERT INTO {$tblprefix}alangs VALUES ('95','load','加载','0');
INSERT INTO {$tblprefix}alangs VALUES ('96','inalbum_content_manager','辑内内容管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('97','inalbum_content_list','辑内内容列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('98','clear','清除','0');
INSERT INTO {$tblprefix}alangs VALUES ('99','type','类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('100','check','审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('101','inalbum_order','辑内排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('102','edit','编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('103','submit','提交','0');
INSERT INTO {$tblprefix}alangs VALUES ('104','admin_album','管理合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('105','all_channel','全部模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('106','filter0_archive_album','筛选文档与合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('107','archive_belong_channel','文档所属模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('108','marchtmldir','会员档案静态文件目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('109','album_belong_type','合辑所属类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('110','content_load_list','请选择需要加载的文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('111','goback','返回','0');
INSERT INTO {$tblprefix}alangs VALUES ('112','channel_album','模型或合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('113','based_msg','基本信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('114','marchtmlurl','会员档案静态url格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('115','archive_title','文档标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('116','member_cname','会员名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('117','add_time','添加时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('118','update_time','更新时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('119','readd_time','重发布时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('120','end1_time','到期时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('121','check_state','审核状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('122','uncheck','解审','0');
INSERT INTO {$tblprefix}alangs VALUES ('123','clicks','点击数','0');
INSERT INTO {$tblprefix}alangs VALUES ('124','comments','评论数','0');
INSERT INTO {$tblprefix}alangs VALUES ('125','othermessage','其它信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('126','nocheck','未审','0');
INSERT INTO {$tblprefix}alangs VALUES ('127','checked','已审','0');
INSERT INTO {$tblprefix}alangs VALUES ('128','nolimit','不限','0');
INSERT INTO {$tblprefix}alangs VALUES ('129','nocheck_album','未审合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('130','checked_album','已审合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('131','noabover_album','未完结合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('132','abover_album','完结合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('133','invalid','无效','0');
INSERT INTO {$tblprefix}alangs VALUES ('134','available','有效','0');
INSERT INTO {$tblprefix}alangs VALUES ('135','filter0_album','筛选合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('136','validperiod_state','有效期状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('137','weather_abover','是否完结','0');
INSERT INTO {$tblprefix}alangs VALUES ('138','order_prior_smaller','排序优先级小于','0');
INSERT INTO {$tblprefix}alangs VALUES ('139','look','查看','0');
INSERT INTO {$tblprefix}alangs VALUES ('140','nocata','草稿箱','0');
INSERT INTO {$tblprefix}alangs VALUES ('141','admin','管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('142','selectallpage','全选所有页内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('143','order','排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('144','message','信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('145','album','合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('146','cancel','取消','0');
INSERT INTO {$tblprefix}alangs VALUES ('147','operate_item','操作项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('148','delete_album','删除合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('149','archive_readd','文档重发布','0');
INSERT INTO {$tblprefix}alangs VALUES ('150','auto_abstract','自动摘要','0');
INSERT INTO {$tblprefix}alangs VALUES ('151','auto_thumb','自动缩略图','0');
INSERT INTO {$tblprefix}alangs VALUES ('152','stat_attachment_size','统计附件大小','0');
INSERT INTO {$tblprefix}alangs VALUES ('153','auto_keyword','自动关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('154','check_album','审核合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('155','album_weather_abover','合辑是否完结','0');
INSERT INTO {$tblprefix}alangs VALUES ('156','setalbum_input_album_id','归辑(请输入合辑ID)','0');
INSERT INTO {$tblprefix}alangs VALUES ('157','reset_validperiod_days','重设有效期(天数)','0');
INSERT INTO {$tblprefix}alangs VALUES ('158','order_prior','排序优先级','0');
INSERT INTO {$tblprefix}alangs VALUES ('159','album_admin','常规合辑管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('160','album_list_operate','合辑列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('176','marchiveslist','会员档案列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('162','update_copys','更新副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('163','no_update_copys','无更新副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('164','yes_update_copys','有更新副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('165','album_channel','合辑模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('166','update_need','更新申请','0');
INSERT INTO {$tblprefix}alangs VALUES ('167','allow_update','允许更新','0');
INSERT INTO {$tblprefix}alangs VALUES ('168','copys','副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('169','original','正本','0');
INSERT INTO {$tblprefix}alangs VALUES ('170','allow','可以','0');
INSERT INTO {$tblprefix}alangs VALUES ('2884','novalidtype','没有任何有效类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('172','disagreeupdate','驳回并删除副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2882','add_inalbum','在 %s 中添加内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('3490','autocnode','添加顶级节点','1273112495');
INSERT INTO {$tblprefix}alangs VALUES ('206','edswomanli','编辑被关联词管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('205','addsword','添加被关联词','0');
INSERT INTO {$tblprefix}alangs VALUES ('203','modify','修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('204','swordillegal','被关联词不合规范','0');
INSERT INTO {$tblprefix}alangs VALUES ('202','del','删?','0');
INSERT INTO {$tblprefix}alangs VALUES ('201','swordmanager','被关联词管理&nbsp;:&nbsp;(启用&nbsp;: %s)','0');
INSERT INTO {$tblprefix}alangs VALUES ('200','imphotkey','从系统导入热门关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('199','marchivesedit','会员档案批量修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('198','choose_item','选择操作项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('197','memberid','会员ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('196','matype','会员档案类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('195','marchive','会员档案','0');
INSERT INTO {$tblprefix}alangs VALUES ('194','search_member','搜索会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('193','filter0','筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('207','impamomaxlim','导入关键词数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('208','add_altype','添加合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('209','fromidmod','指定ID的资料','0');
INSERT INTO {$tblprefix}alangs VALUES ('210','keyvpclar','被引用次数需要大于','0');
INSERT INTO {$tblprefix}alangs VALUES ('211','import','导入','0');
INSERT INTO {$tblprefix}alangs VALUES ('212','altype_name','合辑类型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('213','handaddswo','手动添加被关联词','0');
INSERT INTO {$tblprefix}alangs VALUES ('214','sword','被关联词','0');
INSERT INTO {$tblprefix}alangs VALUES ('215','album_cover_channel','合辑封面模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('216','relateurl','关联链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('217','vpcs','引用次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('218','altype_admin','合辑类型管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('219','achannel','文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('220','enable','启用','0');
INSERT INTO {$tblprefix}alangs VALUES ('221','cover_channel','封面模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('222','addvote','添加投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('223','votetitle','投票标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('224','mchannel','会员模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('225','votecoc','投票分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('226','voteexpl','投票说明','0');
INSERT INTO {$tblprefix}alangs VALUES ('227','endtime','结束时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('228','weathermsel','是否多项选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('229','delete','删除','0');
INSERT INTO {$tblprefix}alangs VALUES ('230','fornouservote','禁止游客投票','1274496819');
INSERT INTO {$tblprefix}alangs VALUES ('231','limitedrevo','限制重复投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('232','idsourcetype','指定ID来源类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('233','editaltypemanagerlist','编辑合辑类型管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('234','repvotetimemin','重复投票时间(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('235','altype_set','合辑类型设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('236','inalbum_add_archive','辑内允许添加以下文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('237','inalbum_add_album','辑内允许添加以下类型合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('238','pointid','指定ID（空为激活ID）','0');
INSERT INTO {$tblprefix}alangs VALUES ('239','setalbum_auto_check','归辑自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('240','albumoneuser','合辑作者的个人作品合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('241','allcoclass','全部分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('242','nocheckvote','未审投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('243','noovervote','未过期投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('244','filvote','筛选投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('245','cotype','类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('246','belongcocl','所属分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('247','alang_manager','后台语言包','0');
INSERT INTO {$tblprefix}alangs VALUES ('248','ischeckvo','是否审核投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('249','coclass','分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('250','usergroup','会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('251','isovevo','是否过期投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('252','mlang_manager','会员中心言语包','0');
INSERT INTO {$tblprefix}alangs VALUES ('253','clang_manager','前台言语包','0');
INSERT INTO {$tblprefix}alangs VALUES ('254','schoise','单选','0');
INSERT INTO {$tblprefix}alangs VALUES ('255','amsg_manager','后台提示','0');
INSERT INTO {$tblprefix}alangs VALUES ('256','mmsg_manager','会员中心提示','0');
INSERT INTO {$tblprefix}alangs VALUES ('257','cmsg_manager','前台提示','0');
INSERT INTO {$tblprefix}alangs VALUES ('258','votmanlis','投票管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('259','alang_filter','后台语言包筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('260','search_keyword','搜索关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('261','alang_admin','后台言语包管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('262','enddate','结束日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('263','add_alang','添加后台言语包','0');
INSERT INTO {$tblprefix}alangs VALUES ('264','editvote','编辑投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('265','voteenddate','投票结束日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('266','edit_alang_list','编辑后台言语包列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('267','ename','标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('268','remark','备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('269','cannotrepevote','不能重复投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('270','reptimintmin','重复投票时间间隔(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('271','voteoption','投票选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('272','optiontitle','选项标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('273','optioorder','选项排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('274','checkvote','已审投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('275','mchoise','多选','0');
INSERT INTO {$tblprefix}alangs VALUES ('276','alang_ename','后台言语包标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('277','votenum','票数','0');
INSERT INTO {$tblprefix}alangs VALUES ('278','addvoteopt','添加投票选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('279','alang_remark','后台言语包备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('280','alang_content','后台言语包内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('281','edit_alang','编辑后台言语包','0');
INSERT INTO {$tblprefix}alangs VALUES ('282','addvotcoc','添加投票分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('283','cocname','分类名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('284','edit_alang_detail','编辑后台言语包详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('285','votcocman','投票分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('286','sn','序号','0');
INSERT INTO {$tblprefix}alangs VALUES ('287','choose_operate_type','选择操作类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('288','del_alert','删除不能恢复，确定删除所选项目?','0');
INSERT INTO {$tblprefix}alangs VALUES ('289','confirmclick','确认请点击：','0');
INSERT INTO {$tblprefix}alangs VALUES ('290','mmsg_filter','会员中心提示信息筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('291','giveupclick','放弃请点击：','0');
INSERT INTO {$tblprefix}alangs VALUES ('292','nocheckalter','未审变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('293','checkedalter','已审变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('294','filaltrec','筛选变更记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('295','viewdetail','显示详细','0');
INSERT INTO {$tblprefix}alangs VALUES ('296','altchesta','变更审核状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('297','altadddate','变更添加日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('298','user0','组外会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('299','altneetim','变更申请时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('300','alterremark','变更备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('301','add_mmsg','添加会员中心提示信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('302','mmsg_ename','会员中心提示信息标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('303','adminreply','管理员回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('304','mmsg_remark','会员中心提示信息备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('305','mmsg_content','会员中心提示信息内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('306','mmsg_jump_url','会员中心提示信息跳转链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('307','mmsg_view_url','会员中心提示信息显示链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('308','moduseralter','修改会员组变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('309','mmsg_admin','会员中心提示信息管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('310','useraltetmodope','会员组变更详情修改操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('311','edit_mmsg_list','编辑会员中心提示信息列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('312','edit_mmsg','编辑会员中心提示信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('313','useraltlist','会员组变更列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('314','edit_mmsg_detail','编辑会员中心提示信息详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('315','sourceuser','来源会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('316','cmsg_filter','前台提示信息筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('317','targetusergroup','目标会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('318','cmsg_admin','前台提示信息管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('319','add_cmsg','添加前台提示信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('320','edit_cmsg_list','编辑前台提示信息列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('321','cmsg_ename','前台提示信息标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('322','cmsg_remark','前台提示信息备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('323','cmsg_content','前台提示信息内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('324','cmsg_jump_url','前台提示信息跳转链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('325','cmsg_view_url','前台提示信息显示链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('326','edit_cmsg','编辑前台提示信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('327','edit_cmsg_detail','编辑前台提示信息详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('328','useraltadm','会员组变更管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('329','setting','设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('330','albumonlyone','内容不能归入多个本类合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('331','isonlyloadalbum','是否加载性合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('332','agonlyload','只能由合辑作者提取已有内容到合辑中，不允许内容主动归入或退出此合辑。','0');
INSERT INTO {$tblprefix}alangs VALUES ('333','enableinalbumcount','启用辑内统计合计','0');
INSERT INTO {$tblprefix}alangs VALUES ('334','inalbummaxlimit','辑内内容最大数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('335','albumcctpl','合辑封面内容模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('336','useraltlisadmoper','会员组变更列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('337','usereeopt','会员组申请选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('338','altergroup','变更会员组体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('339','albumplustpl','合辑封面附加页%s模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('340','album_add_tpl','合辑添加模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('341','useraltmode','会员组变更方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('342','album_prepage_tpl','合辑封面前导页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('343','usuatitle','常用链接标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('344','album_add_p_set','合辑添加权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('346','usualurl','常用链接URL','0');
INSERT INTO {$tblprefix}alangs VALUES ('347','detail_modify_altype','详细修改合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('348','del_altype','删除合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('349','adminbapmanager','后台管理方案管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('350','usuorder','常用链接排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('351','adminbapadd','后台管理方案添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('352','urlimage','链接图片','0');
INSERT INTO {$tblprefix}alangs VALUES ('353','adminbapname','后台管理方案名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('354','belsitforuse','以下站点禁止使用','0');
INSERT INTO {$tblprefix}alangs VALUES ('355','editbaprojectlist','编辑后台管理方案管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('356','tagtemplate','标识内模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('357','projectname','方案名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('358','inhitatt','继承站点属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('359','addadminbap','添加后台管理方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('377','aaddusualurl','添加常用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('361','edusudet','编辑常用链接详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('362','tagjspick','是否启用JS动态内容调用','0');
INSERT INTO {$tblprefix}alangs VALUES ('363','selectall','全选','0');
INSERT INTO {$tblprefix}alangs VALUES ('364','msitebasshieldmenu','管理后台允许显示的菜单项','0');
INSERT INTO {$tblprefix}alangs VALUES ('365','addusualurl','添加%s常用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('366','newwin','新窗口打开链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('367','list_result','列表中显示多少条内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('368','list_cols','分为几列显示','0');
INSERT INTO {$tblprefix}alangs VALUES ('369','chourlty','选择链接类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('370','marchive_list','会员档案列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('371','marchive_mod','单个会员档案','0');
INSERT INTO {$tblprefix}alangs VALUES ('372','ediusuli','编辑常用链接列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('373','caid_attr','允许栏目内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('374','adminbacuser','管理后台用户链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('375','colasslimit','分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('376','memcenuse','会员中心用户链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('378','atid_attr','设定合辑属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('379','subsitebasshieldmenu','子站后台设置 -  选择需要屏蔽的菜单','0');
INSERT INTO {$tblprefix}alangs VALUES ('380','chid_attr','允许以下文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('381','memcenusua','会员中心常用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('382','only_valid_period','只允许有效期内的内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('383','contentadminforbid','内容管理功能禁止','0');
INSERT INTO {$tblprefix}alangs VALUES ('384','forbidentersubsiteba','禁止进入以下子站后台','0');
INSERT INTO {$tblprefix}alangs VALUES ('385','view_ch_field','需要显示非通用字段内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('386','adminbackusual','管理后台常用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('387','view_plus_stat','需要附加统计信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('388','agtagdetail','仅当模型限制为单个模型时有效。较耗资源','0');
INSERT INTO {$tblprefix}alangs VALUES ('389','agtagrec','需要周、月、辑合计等统计信息排序或显示，以及筛选使用了archives_rec表时，请选 是','0');
INSERT INTO {$tblprefix}alangs VALUES ('390','urlusualurlmana','%s 常用链接管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('391','forbidadmincatalog','禁止管理栏目内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('392','forbidadminfcoclass','禁止管理插件分类内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('393','forbidadmincmember','禁止管理以下模型的会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('394','detailmodifyabap','详细修改后台管理方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('395','amsgfilter','后台提示信息筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('396','amsgadmin','后台提示信息管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('397','addamsg','添加后台提示信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('398','editamsglist','编辑后台提示信息列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('399','amsgename','后台提示信息标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('400','amsgremark','后台提示信息备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('401','addscoclass','添加%s分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('402','topiccoclass','顶级分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('403','amsgcontent','后台提示信息内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('404','amsg_jump_url','后台提示信息跳转链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('405','amsg_view_url','后台提示信息显示链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('406','editamsgdetail','编辑后台提示信息详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('407','editamsg','编辑后台提示信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('408','uplevelcoclass','上级分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('409','noadoptanswer','未采用答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('410','coclasspickurl','分类调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('411','adoptedanswer','被采用答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('412','filteranswer','筛选答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('413','isadoptedanswer','是否被采用答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('414','search_arc_title','搜索文档标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('415','answer_list','答案列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('416','question_title','问题标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('417','member','会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('418','coclassorder','分类排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('419','adopt','采用','0');
INSERT INTO {$tblprefix}alangs VALUES ('420','award','奖励','0');
INSERT INTO {$tblprefix}alangs VALUES ('421','accountin','入账','0');
INSERT INTO {$tblprefix}alangs VALUES ('422','ediusecocdet','编辑用户链接分类详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('423','source','来源','0');
INSERT INTO {$tblprefix}alangs VALUES ('424','answer_list_admin','答案列表管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('425','answer_list_a_operate','答案列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('426','editsuserurl','编辑%s用户链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('427','edit_answer','编辑答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('428','question_state','问题状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('429','userurlcname','用户链接名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('430','answer_title','答疑标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('431','userurlorder','用户链接排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('432','answer_content','答案内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('433','question_closed','问题已关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('434','ediuserdetail','编辑用户链接详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('435','question_noclose','问题未关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('436','answer_content_edit','答案内容编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('437','delusercoc','删除用户链接分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('438','edit_answer_content','编辑答案内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('439','deleteuserurl','删除用户链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('440','addusecoc','添加用户链接分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('441','acontent_by_catalog','按栏目管理内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('442','nolimitchannel','不限模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('443','addsuserurl','添加%s用户链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('444','archive_admin','文档管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('445','handpoint','手动指定','0');
INSERT INTO {$tblprefix}alangs VALUES ('446','userurl','用户链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('447','archive','文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('448','nolimitcatas','不限类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('449','inputbealbumid','手动输入所属合辑ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('450','suserurlmanager','%s用户链接管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('451','add_archive','添加文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('452','activecatas','激活类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('453','addurlcoclass','添加链接分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('454','modify_archive','修改文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('455','nearofactive','激活类目的相邻类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('456','modify_arc_copys','修改文档副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('457','current_be_album','当前所属合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('458','editsuserurlcoclass','编辑%s用户链接分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('459','nocheck_archive','未审文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('460','ediuserulist','编辑用户链接列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('461','include_son','含子分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('462','url','链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('463','checked_archive','已审文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('464','filter_archive','筛选文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('465','archive_channel','文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('466','copy','复制','0');
INSERT INTO {$tblprefix}alangs VALUES ('467','check_archive','审核文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('468','addusergroup','添加会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('469','archive_list','文档列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('470','noalbum_archive','非合辑文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('471','channel','模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('472','editusergroup','编辑会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('473','unstatic','解除静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('474','usergroupcname','会员组名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('475','tostatic','产生静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('476','all_archive','所有文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('2881','inalbum_add_coids','添加的文档继承合辑的分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('478','inchallowuse','勾选启用会员模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('479','delete_archive','删除文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('480','uservalid','会员组有效期','0');
INSERT INTO {$tblprefix}alangs VALUES ('481','active_channel','激活模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('482','alloissuearch','允许发表文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('483','allissuecomm','允许发表评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('484','member_related','会员相关设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('485','allpurcgoods','允许购买商品','0');
INSERT INTO {$tblprefix}alangs VALUES ('486','setalbum_input','归辑(请输入合辑ID)','0');
INSERT INTO {$tblprefix}alangs VALUES ('487','alloissans','允许发表答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('488','arc_update_admin','文档更新管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('489','arc_list_aoperate','文档列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('490','allouploattach','允许上传附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('491','individual_list','仅显示激活会员的文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('492','allodownattach','允许下载附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('493','admibackaproje','后台管理方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('494','active_uclass','只显示激活个人分类的文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('3156','closequestion','关闭问题','0');
INSERT INTO {$tblprefix}alangs VALUES ('496','allinsisear','允许站内搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('498','freeupdatecheck','更新已审文章免申请','0');
INSERT INTO {$tblprefix}alangs VALUES ('3146','memlogset','会员登陆设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('500','nolimit_coclass','不限分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('501','nospare','无余额','0');
INSERT INTO {$tblprefix}alangs VALUES ('502','freelooktaxcon','免费查看收费文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('503','spared','有余额','0');
INSERT INTO {$tblprefix}alangs VALUES ('504','freelooktaxattach','免费查看收费附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('505','active_coclass','激活分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('506','pmmountlimi','短信数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('3164','agadditems_m','不选则注册会员时只需要添写帐号、密码与Email。','0');
INSERT INTO {$tblprefix}alangs VALUES ('508','nosetting','不设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('509','uploadlimited','上传限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('510','downloadlimited','下载限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('512','purgoodiscount','购买商品折扣(%)','0');
INSERT INTO {$tblprefix}alangs VALUES ('513','isspared','是否有余额','0');
INSERT INTO {$tblprefix}alangs VALUES ('514','relatcurramou','相关积分数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('516','freesubarch','免费订阅文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('3161','crelates','会员中心可关联的类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('3162','agrelates','不选表示当前类型会员不能关联任何类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('519','freesubatta','免费订阅附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('520','allarcdefamo','限额文档默认数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('521','allcomdefamomon','限额交互默认数量/月','0');
INSERT INTO {$tblprefix}alangs VALUES ('3163','additems_m','注册新会员的可用项','0');
INSERT INTO {$tblprefix}alangs VALUES ('523','detmoduserg','详情修改会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('524','reward_currency','悬赏积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('525','belonggroupty','所属组系','0');
INSERT INTO {$tblprefix}alangs VALUES ('526','spare','余额','0');
INSERT INTO {$tblprefix}alangs VALUES ('527','pointmatype','指定会员档案类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('528','base','基数','0');
INSERT INTO {$tblprefix}alangs VALUES ('3160','arelates','管理后台可关联的类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('530','exchange','兑换','0');
INSERT INTO {$tblprefix}alangs VALUES ('531','answer0','答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('532','related','相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('533','currency','积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('534','answer_reward','答疑悬赏','0');
INSERT INTO {$tblprefix}alangs VALUES ('535','usergroupcopy','会员组复制','0');
INSERT INTO {$tblprefix}alangs VALUES ('536','alter_record','变更记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('537','sousergname','源会员组名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('538','noclose','未关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('539','newusergroupname','新会员组名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('540','closed','已关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('541','copyusergroup','复制会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('542','alltype','全部类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('544','image','图片','0');
INSERT INTO {$tblprefix}alangs VALUES ('545','question_title_state','问题标题(状态)','0');
INSERT INTO {$tblprefix}alangs VALUES ('546','flash','Flash','0');
INSERT INTO {$tblprefix}alangs VALUES ('547','question_det_content','问题详细内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('548','media','视频','0');
INSERT INTO {$tblprefix}alangs VALUES ('549','other','其它','0');
INSERT INTO {$tblprefix}alangs VALUES ('550','reward_spare_appeal','悬赏 / 余额','0');
INSERT INTO {$tblprefix}alangs VALUES ('551','nonethumb','无缩略图','0');
INSERT INTO {$tblprefix}alangs VALUES ('552','question_adddate','问题添加日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('553','answer_enddate','答疑结束日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('554','havethumb','有缩略图','0');
INSERT INTO {$tblprefix}alangs VALUES ('3158','regtpl','注册模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('556','filteattachment','筛选附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('557','attachmenttype','附件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('558','aidstxt','相关文档ID(多个ID用逗号隔开)','0');
INSERT INTO {$tblprefix}alangs VALUES ('560','ishavethumb','是否有缩略图','0');
INSERT INTO {$tblprefix}alangs VALUES ('561','support','支持','0');
INSERT INTO {$tblprefix}alangs VALUES ('562','attalistdeloper','附件列表删除操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('563','question_alter_record','问题变更记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('564','answer_alter_record','答案变更记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('565','preview','预览','0');
INSERT INTO {$tblprefix}alangs VALUES ('566','modify_date','修改日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('567','thumb','缩略图','0');
INSERT INTO {$tblprefix}alangs VALUES ('568','content','内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('569','uploaddate','上传日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('570','uploadattadm','上传附件管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('571','add_badword','添加不良词','0');
INSERT INTO {$tblprefix}alangs VALUES ('572','cname','名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('573','badword','不良词','0');
INSERT INTO {$tblprefix}alangs VALUES ('574','rword','替换词','0');
INSERT INTO {$tblprefix}alangs VALUES ('575','sizek','大小(k)','0');
INSERT INTO {$tblprefix}alangs VALUES ('576','edit_badword_mlist','编辑不良词管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('577','badword_manager','不良词管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('578','indextemplate','首页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('579','cataspagetem','类目页面模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('580','text','单行文本','0');
INSERT INTO {$tblprefix}alangs VALUES ('581','albuconpagtemset','合辑内容页面模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('582','multitext','多行文本','0');
INSERT INTO {$tblprefix}alangs VALUES ('583','freconpagtemset','插件内容页面模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('584','htmltext','Html文本','0');
INSERT INTO {$tblprefix}alangs VALUES ('585','arccomtemset','文档交互模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('586','image_f','单图','0');
INSERT INTO {$tblprefix}alangs VALUES ('587','memcomtemset','会员交互模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('588','images','图集','0');
INSERT INTO {$tblprefix}alangs VALUES ('589','sppagtemset','特定功能页面模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('590','flashs','Flash集','0');
INSERT INTO {$tblprefix}alangs VALUES ('591','basemset','基本模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('592','arcconpagtemset','文档内容页面模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('593','medias','视频集','0');
INSERT INTO {$tblprefix}alangs VALUES ('594','file_f','单点下载','0');
INSERT INTO {$tblprefix}alangs VALUES ('595','files_f','多点下载','0');
INSERT INTO {$tblprefix}alangs VALUES ('596','addconsub','添加内容子站','0');
INSERT INTO {$tblprefix}alangs VALUES ('597','select','单项选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('598','subsitecname','子站名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('599','mselect','多项选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('600','date_f','日期(时间戳)','0');
INSERT INTO {$tblprefix}alangs VALUES ('601','subsstadir','子站静态目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('602','int','整数','0');
INSERT INTO {$tblprefix}alangs VALUES ('603','substempldir','子站模板目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('604','float','小数','0');
INSERT INTO {$tblprefix}alangs VALUES ('605','agtemplatedir','只需要名称，位于template目录下','0');
INSERT INTO {$tblprefix}alangs VALUES ('606','common_message','通用信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('607','archive_related','文档相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('608','addsubsite','添加子站','0');
INSERT INTO {$tblprefix}alangs VALUES ('609','catas_related','类目相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('610','freeinfo_related','插件信息相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('611','subsitemanager','子站管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('612','commu_message','交互信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('613','msitetranstsubsite','主站转为子站','0');
INSERT INTO {$tblprefix}alangs VALUES ('614','transtomsite','转为主站','0');
INSERT INTO {$tblprefix}alangs VALUES ('615','index','首页','0');
INSERT INTO {$tblprefix}alangs VALUES ('616','choose_initag_type','选择原始标识列表类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('617','start','开始','0');
INSERT INTO {$tblprefix}alangs VALUES ('618','comment','评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('619','purchase','购买','0');
INSERT INTO {$tblprefix}alangs VALUES ('620','subsittranstmsite','子站转为主站','0');
INSERT INTO {$tblprefix}alangs VALUES ('621','answer','答疑','0');
INSERT INTO {$tblprefix}alangs VALUES ('622','delsubsite','删除子站之前必须先清空该子站的栏目,节点,合辑类型,文档及合辑!','0');
INSERT INTO {$tblprefix}alangs VALUES ('623','pt','分页','0');
INSERT INTO {$tblprefix}alangs VALUES ('624','close','关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('625','attachment','附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('626','vote','投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('627','sublisadmope','子站列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('628','initag_common','原始标识(通用)','0');
INSERT INTO {$tblprefix}alangs VALUES ('629','newsubset','新子站设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('630','createdate_desc','添加时间↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('631','tagname','标识名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('632','createdate_asc','添加时间↑','0');
INSERT INTO {$tblprefix}alangs VALUES ('633','use_style','使用样式','0');
INSERT INTO {$tblprefix}alangs VALUES ('634','poisubstatramsi','指定的子站开始转为主站!','0');
INSERT INTO {$tblprefix}alangs VALUES ('635','field_type','字段类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('636','related_tag','相关标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('637','currencytype','积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('638','refreshdate_desc','刷新时间↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('639','search_initag','搜索原始标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('640','grouptype','会员组体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('641','tagid_inc_string','标识ID含字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('642','order_str','排序字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('643','commuitem','交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('644','cotypem','类别体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('645','create_str','生成字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('646','coclasssetting','分类设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('647','startno','列表起始位置','0');
INSERT INTO {$tblprefix}alangs VALUES ('648','catascnode','类目节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('649','affixchannel','插件模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('650','agstartno','设置按当前设置的第几条记录开始，默认为0','0');
INSERT INTO {$tblprefix}alangs VALUES ('651','affixcoclass','插件分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('652','isolutepage','独立页面','0');
INSERT INTO {$tblprefix}alangs VALUES ('653','more_filter','更多筛选设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('654','contsubsinst','内容子站安装','0');
INSERT INTO {$tblprefix}alangs VALUES ('655','list_order','列表排序设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('656','delinupdatandrec','删除安装上传资料与记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('657','nextstep','下一步','0');
INSERT INTO {$tblprefix}alangs VALUES ('658','filter_sql_str','筛选查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('659','tagname_inc_string','标识名称含字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('660','relay_param','向内嵌标识传递参数','0');
INSERT INTO {$tblprefix}alangs VALUES ('661','tag_coclass','标识分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('662','rrelay_param','上级传送参数作为原始标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('663','search','搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('664','undosetting','撤消设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('665','agrelays','多个参数间以逗号隔开，如 a,b=b_1 ，在内嵌标识中，使用{$a}调用本标识中{$a}的值，用{$b_1}调用{$b}的值。','0');
INSERT INTO {$tblprefix}alangs VALUES ('666','initag_search_result','原始标识搜索结果列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('667','agrrelays','多个参数间以逗号隔开，如 a,b=b_1 ，在当前模板中，使用{$a}调用上级标识中的{$a}，用{$b_1}调用{$b}。','0');
INSERT INTO {$tblprefix}alangs VALUES ('668','delsubinsupdatandrec','删除子站安装上传资料与安装记录!','0');
INSERT INTO {$tblprefix}alangs VALUES ('669','subsiteid','子站ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('670','currtypetran','积分类型转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('671','soucurid','来源积分ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('672','tagclass','标识类别','0');
INSERT INTO {$tblprefix}alangs VALUES ('673','tranurrentsys','转入当前系统','0');
INSERT INTO {$tblprefix}alangs VALUES ('674','detail_coclass','详细分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('675','memchantransto','会员模型转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('676','soumemchaid','来源会员模型ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('677','soumemchname','来源会员模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('678','subtemtra','子站模板转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('679','templatesdo','模板do','0');
INSERT INTO {$tblprefix}alangs VALUES ('680','templatesundo','模板undo','0');
INSERT INTO {$tblprefix}alangs VALUES ('681','sourceid','来源ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('682','sourcecurrencycname','来源积分名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('683','grouptypetransto','会员组体系转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('684','sogrouptypeid','来源会员组体系ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('685','add_catalog','添加栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('686','sogroupname','来源会员组体系名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('687','base_setting','基本设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('688','usertransto','会员组转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('689','catalog_name','栏目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('690','sourcegrouptype','来源会员组体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('691','parent_catalog','父栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('692','souusergrid','来源会员组ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('693','souusename','来源会员组名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('694','commuitemtrans','交互项目转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('695','isframe_catalog_r','结构栏目(仅含子栏目)','0');
INSERT INTO {$tblprefix}alangs VALUES ('696','soucomitemid','来源交互项目ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('697','soucomitemname','来源交互项目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('698','shipingtransto','送货方式转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('700','sourshipid','来源送货方式ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('701','soushipname','来源送货方式名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('702','allow_channel_archive','允许添加以下模型的文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('703','arcchatrans','文档模型转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('704','souarcchaid','来源文档模型ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('705','allow_type_album','允许添加以下类型的合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('706','purchase_discount','购买商品折扣(%)','0');
INSERT INTO {$tblprefix}alangs VALUES ('707','issue_arc_currency','发表文档奖励积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('708','arc_deduct_currency','浏览文档扣除积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('709','att_deduct_currency','下载或播放附件扣除积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('710','souarcchaname','来源文档模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('711','allow_sale_archive','允许作者出售文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('712','altypetransto','合辑类型转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('713','allow_sale_attachment','允许作者出售附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('714','arc_static_url_format','文档页静态保存格式','1271062643');
INSERT INTO {$tblprefix}alangs VALUES ('715','topic_catalog','顶级栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('716','noaward','不奖励','0');
INSERT INTO {$tblprefix}alangs VALUES ('717','direct_aid','指定文档id(空缺指激活文档)','0');
INSERT INTO {$tblprefix}alangs VALUES ('718','agcustomurl','留空为默认格式，{$topdir}顶级栏目目录，{$cadir}所属栏目目录，{$y}年 {$m}月 {$d}日 {$h}时 {$i}分 {$s}秒 {$chid}模型id  {$aid}文档id {$page}分页页码 {$addno}附加页id，id之间建议用分隔符_或-连接。','1270859327');
INSERT INTO {$tblprefix}alangs VALUES ('719','direct_maid','指定会员ID(空缺指激活会员)','0');
INSERT INTO {$tblprefix}alangs VALUES ('720','iscustom_message','自定信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('721','iscustom_catalog_field','自定栏目字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('722','sourcealtypeid','来源合辑类型ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('723','souraltyname','来源合辑类型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('724','catalogtransto','栏目转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('725','catalog_manager','栏目管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('726','soucatid','来源栏目ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('727','sourcataname','来源栏目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('728','reset_parent_catalog','重设父栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('729','cotypetransto','类系转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('730','coclasstransto','分类转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('731','to_other_subsite','转入其它子站(限顶级栏目)','0');
INSERT INTO {$tblprefix}alangs VALUES ('732','sourcecotype','来源类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('733','soucocid','来源分类ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('734','soucoclcname','来源分类名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('735','edit_catalog_mlist','编辑栏目管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('736','catcnotran','类目节点转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('737','soucatconfigid','来源类目结构ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('738','catalog_base_set','栏目基本设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('739','sourataonfigname','来源类目结构名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('740','freechantransto','插件模型转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('741','del_catalog','删除栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('742','soufrechaid','来源插件模型ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('743','add_catalog_field','添加栏目字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('744','sourfreechanname','来源插件模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('745','freecoctran','插件信息分类转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('746','catalog_msg_field_m','栏目信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('747','soufrecocid','来源插件分类ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('748','soufrecoccna','来源插件分类名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('749','add_field','添加字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('750','isolpagtrans','独立页面转入','0');
INSERT INTO {$tblprefix}alangs VALUES ('751','field_name','字段名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('752','souisopagid','来源独立页面ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('753','field_ename','字段标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('754','souisopagcna','来源独立页面名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('755','catalog_iscustom_msg','栏目自定信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('756','subcoliadmoper','订阅内容列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('757','subconadm','订阅内容管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('758','field_edit','字段编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('759','purchasedate','购买日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('760','filsubrec','筛选订阅记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('761','subscribetype','订阅类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('762','detail_modify_catalog','详细修改栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('763','contpurchdat','内容购买日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('764','continue','继续','0');
INSERT INTO {$tblprefix}alangs VALUES ('765','subsrecolis','订阅记录列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('766','add_catalog_msg_field','添加栏目信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('767','edit_cmsg_field_mlist','编辑栏目信息字段管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('768','detail_mcmsg_field','详细修改栏目信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('769','channel_manager','模型管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('770','channel_name','模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('771','ut_commu','特殊交互','0');
INSERT INTO {$tblprefix}alangs VALUES ('772','add_channel','添加模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('773','ut_commu_item','特殊交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('774','arc_channel_copy','文档模型复制','0');
INSERT INTO {$tblprefix}alangs VALUES ('775','soc_channel_name','源模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('776','soc_ccommu_config','源模型交互配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('777','new_channel_name','新模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('778','new_ccommu_config','新模型交互配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('779','plimits','每页显示多少条内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('780','palimits','总结果数限制(空为不限)','0');
INSERT INTO {$tblprefix}alangs VALUES ('781','nav_simple','是否简易模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('782','nav_length','导航页码长度','0');
INSERT INTO {$tblprefix}alangs VALUES ('783','masearchlist','会员档案搜索列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('784','arcconpage','文档内容页','0');
INSERT INTO {$tblprefix}alangs VALUES ('785','pascresta','被动生成静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('786','actcresta','主动生成静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('787','cleolstfi','清除静态文件','1271872167');
INSERT INTO {$tblprefix}alangs VALUES ('788','repstaurl','修复静态链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('789','crearcpagsta','生成文档页静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('790','stacremo','静态生成模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('791','choarcpaty','选择文档页面类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('792','passtadet','被动静态多少分钟后开始','0');
INSERT INTO {$tblprefix}alangs VALUES ('793','numperpic20_500','分批操作数量(20-500)','0');
INSERT INTO {$tblprefix}alangs VALUES ('794','filarcpagcurarcamo','筛选文档页--当前文档数量:&nbsp; &nbsp;','0');
INSERT INTO {$tblprefix}alangs VALUES ('795','edit_arc_channel_list','编辑文档模型列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('796','clickslarger','点击数大于','0');
INSERT INTO {$tblprefix}alangs VALUES ('797','commentlarger','评论数大于','0');
INSERT INTO {$tblprefix}alangs VALUES ('798','arcstaadm','文档静态管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('799','copy_arc_channel','复制文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('800','channel_set','模型设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('801','sum','合计','0');
INSERT INTO {$tblprefix}alangs VALUES ('802','add_freeinfo','添加插件信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('803','crecatcnodpagsta','生成类目节点页静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('804','admin_self_channel','管理专用模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('805','common_option','通用选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('806','arc_auto_check','自动审核权限设置','1273359121');
INSERT INTO {$tblprefix}alangs VALUES ('807','arc_auto_static','文档自动静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('808','message_coclass','信息分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('809','auto_abstract_src','自动摘要来源字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('810','state_message','状态信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('811','indstaadm','首页静态管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('812','auto_thumb_src','自动缩略图来源字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('813','auto_keyword_src','自动关键词来源字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('814','msg_buy_price','信息购买价格','0');
INSERT INTO {$tblprefix}alangs VALUES ('815','auto_stat_asize_src','自动统计附件大小来源','0');
INSERT INTO {$tblprefix}alangs VALUES ('816','flong','永久信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('817','buy_cell','购买单元','0');
INSERT INTO {$tblprefix}alangs VALUES ('818','mini_buy_cell_amount','最小购买单元数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('819','msg_content_checked','信息内容已审','0');
INSERT INTO {$tblprefix}alangs VALUES ('820','msg_content_nocheck','信息内容未审','0');
INSERT INTO {$tblprefix}alangs VALUES ('821','auto_stat_a_size_mode','自动统计附件大小模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('822','effecting_msg','生效中信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('823','baidu_map_src','Baidu地图内容来源字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('824','catasindex','类目节点','1271831256');
INSERT INTO {$tblprefix}alangs VALUES ('825','fulltxt_search_src','全文搜索来源字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('826','start_date','开始日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('827','cataslistpage','类目列表页','0');
INSERT INTO {$tblprefix}alangs VALUES ('828','catasbklist','类目备用页','0');
INSERT INTO {$tblprefix}alangs VALUES ('829','oneof','其中之一','0');
INSERT INTO {$tblprefix}alangs VALUES ('830','topic','顶级','0');
INSERT INTO {$tblprefix}alangs VALUES ('831','level2','二级','0');
INSERT INTO {$tblprefix}alangs VALUES ('832','noeffect_msg','未生效信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('833','arc_prepage_tpl','文档前导页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('834','level3','三级','0');
INSERT INTO {$tblprefix}alangs VALUES ('835','level4','四级','0');
INSERT INTO {$tblprefix}alangs VALUES ('836','search_tpl','指定模型的搜索模板','1270783807');
INSERT INTO {$tblprefix}alangs VALUES ('837','arc_add_tpl','文档发布页模板','1270783632');
INSERT INTO {$tblprefix}alangs VALUES ('838','choatpaty','选择页面类型','1271765158');
INSERT INTO {$tblprefix}alangs VALUES ('839','commu_item_sett','[%s]交互项目设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('840','msg_current_state','信息当前状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('841','comment_commu_setg','评论交互设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('842','reply_commu_set','回复交互设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('843','detail0_modify_freeinfo','详细修改插件信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('844','offer_commu_set','报价交互设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('845','pickbug_commu_set','举报交互设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('846','nocheck_msg','未审信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('847','is_allowance_arc','是否限额文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('848','not_enable_readd','不启用重发布','0');
INSERT INTO {$tblprefix}alangs VALUES ('849','checked_msg','已审信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('850','allowance_and_vp','限额与有效期','0');
INSERT INTO {$tblprefix}alangs VALUES ('851','ba_allow_readd','后台可以重发布','0');
INSERT INTO {$tblprefix}alangs VALUES ('852','filter0_msg','筛选信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('853','member_allow_readd','会员可以重发布','0');
INSERT INTO {$tblprefix}alangs VALUES ('854','readd_set','重发布设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('855','readd_time_inval_h','重发布时间间隔(小时)','0');
INSERT INTO {$tblprefix}alangs VALUES ('856','ficatcnocuo','筛选类目节点--当前节点数量:&nbsp; &nbsp;','0');
INSERT INTO {$tblprefix}alangs VALUES ('857','check_msg','审核信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('858','not_enable_vp','不启用有效期','0');
INSERT INTO {$tblprefix}alangs VALUES ('859','mainlinemode','节点的首序类系','1272770024');
INSERT INTO {$tblprefix}alangs VALUES ('860','over_reset_vp','过期后重设有效期','0');
INSERT INTO {$tblprefix}alangs VALUES ('861','cnodelevelnum','节点层数','1272522893');
INSERT INTO {$tblprefix}alangs VALUES ('862','msg_list','信息列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('863','anytime_reset_vp','随时重设有效期','0');
INSERT INTO {$tblprefix}alangs VALUES ('864','catcnostaadm','类目节点静态管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('865','arc_vp_set','文档有效期设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('866','cnliadmope','节点列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('867','vp_days','有效期天数','0');
INSERT INTO {$tblprefix}alangs VALUES ('868','create_static','生成静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('869','admin_self','管理专用','0');
INSERT INTO {$tblprefix}alangs VALUES ('870','del_msg','删除信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('871','add_arc_channel','添加文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('872','email','Email','0');
INSERT INTO {$tblprefix}alangs VALUES ('873','default','默认','0');
INSERT INTO {$tblprefix}alangs VALUES ('874','check_msg_content','审核信息内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('875','tpl_set','模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('876','insitepm','站内短信','0');
INSERT INTO {$tblprefix}alangs VALUES ('877','msg_content_page0_static','信息内容页静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('878','nouser','游客','0');
INSERT INTO {$tblprefix}alangs VALUES ('879','nolimittype','不限类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('880','issue_permission_set','发布权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('881','filtersplang','筛选功能语言','0');
INSERT INTO {$tblprefix}alangs VALUES ('882','spltemadmin','功能语言模板管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('883','field_manager','字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('884','freeinfo_admin','插件信息管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('885','detail_marc_channel','详细修改文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('886','freeinfo_list_admin','插件信息列表管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('887','splangcname','功能语言名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('888','add_arc_channel_field','添加文档模型字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('889','splangtype','功能语言类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('890','is_func_field','是否函数字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2796','lic_no','授权号：','0');
INSERT INTO {$tblprefix}alangs VALUES ('892','add_achannel_msg_field','添加文档模型信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('893','detaimodifysplang','详细修改功能语言','0');
INSERT INTO {$tblprefix}alangs VALUES ('894','del_arc_channel','删除文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('895','splangset','功能语言设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('896','edit_channel_field','编辑模型字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('897','msg_coclass_manager','信息分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('898','sitepageadmin','Sitemap页面管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('899','detail_mach_msg_field','详细修改文档模型信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('900','consult','咨询','0');
INSERT INTO {$tblprefix}alangs VALUES ('901','add_common_field','添加通用字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('902','sitemapcname','Sitemap名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('903','common_field_manager','通用字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('904','add_msg_coclass','添加信息分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('905','dynamicurl','动态链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('906','xmlurl','xml链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('907','edit_acm_field_mlist','编辑文档通用信息字段管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('908','create','生成','0');
INSERT INTO {$tblprefix}alangs VALUES ('909','freeinfo_channel','插件模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('910','detail_mac_msg_field','详细修改文档通用信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('911','sitemapsetting','Sitemap设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('912','add_acm_field','添加文档通用信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('913','dynapickurl','动态调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('914','xmlpickurl','xml调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('915','weather_consult_coclass','是否咨询分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('916','isenable','是否启用','0');
INSERT INTO {$tblprefix}alangs VALUES ('917','upperiodhours','更新周期(小时)','0');
INSERT INTO {$tblprefix}alangs VALUES ('918','edit_freeinfo_list','编辑插件分类管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('919','limitdayarchive','限多少天以内添加的文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('920','cataloglimi','栏目限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('921','limited','限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('922','addshiping','添加送货方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('923','shiiteadm','送货方式项目管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('924','add_freeinfo_coclass','添加插件分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('925','msg_coclass_set','信息分类设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('926','msg_auto_check','信息自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('927','dbsrc_data_miss','外部数据源资料不完全','0');
INSERT INTO {$tblprefix}alangs VALUES ('928','author_update_checked_msg','作者可以更新已审信息内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('929','dbsrc_connect_error','外部数据源连接错误','0');
INSERT INTO {$tblprefix}alangs VALUES ('930','freetop','免费额度','0');
INSERT INTO {$tblprefix}alangs VALUES ('931','dbsrc_connect_correct','外部数据源连接正确','0');
INSERT INTO {$tblprefix}alangs VALUES ('932','edishimanlis','编辑送货方式管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('933','shipingset','送货方式设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('934','test_mail','系统测试邮件','0');
INSERT INTO {$tblprefix}alangs VALUES ('935','email_test_succeed','Email测试成功!','0');
INSERT INTO {$tblprefix}alangs VALUES ('936','detaimodiship','详细修改送货方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('937','msg_con_tpl','信息内容模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('938','freetopyuan','免费额度(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('939','consult_set','咨询设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('940','basedfeeyuan','基本费用(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('941','bystep_config_cnode','按步骤配置节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('942','plusfee1','附加费用1','0');
INSERT INTO {$tblprefix}alangs VALUES ('943','pluscontent','不选为固定值(元)<br>\r\n否则附加费=交易值×输入值%','0');
INSERT INTO {$tblprefix}alangs VALUES ('952','issue1_tax_set','发布收费设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('945','allow_reply_usergroup','可以回复会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('946','plusfee2','附加费用2','0');
INSERT INTO {$tblprefix}alangs VALUES ('947','commu_content_length','交互内容长度限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('948','node_step2','步骤2  修改类目结构配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('949','overw1staweightkg','超重1开始重量(Kg)','0');
INSERT INTO {$tblprefix}alangs VALUES ('950','node_step1','添加节点配置','1272474238');
INSERT INTO {$tblprefix}alangs VALUES ('951','overw1weighkg','超重1计费单元(Kg)','0');
INSERT INTO {$tblprefix}alangs VALUES ('953','overw1priyuan','超重1价格(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('954','node_step3','步骤3  依据配置更新类目节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('955','overw2stawkg','超重2开始重量(Kg)','0');
INSERT INTO {$tblprefix}alangs VALUES ('956','save_config','保存配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('957','overw2weigkg','超重2计费单元(Kg)','0');
INSERT INTO {$tblprefix}alangs VALUES ('958','overw2priyuan','超重2价格(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('959','tax_currency_type','收费积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('960','edit_catas_cmlist','编辑类目结构管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('961','tax_cell','收费单元','0');
INSERT INTO {$tblprefix}alangs VALUES ('962','shipdatamiss','送货方式资料不完全','0');
INSERT INTO {$tblprefix}alangs VALUES ('963','catas_cdescription','类目结构描述','0');
INSERT INTO {$tblprefix}alangs VALUES ('964','contseaurl','内容搜索链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('966','agmainline','结构中的首序类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('967','searformurl','搜索表单链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('968','tax_price','收费价格','0');
INSERT INTO {$tblprefix}alangs VALUES ('969','searesurl','搜索结果链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('970','add_catas_configs','添加类目结构','0');
INSERT INTO {$tblprefix}alangs VALUES ('971','more','更多','0');
INSERT INTO {$tblprefix}alangs VALUES ('972','currency_cell','积分/单元','0');
INSERT INTO {$tblprefix}alangs VALUES ('973','keyword','关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('974','cnode_name','节点名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('975','cnode_alias','节点别名','0');
INSERT INTO {$tblprefix}alangs VALUES ('976','seaparset','搜索参数设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('977','taxmini','每次购买不少于(单元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('978','searchmode1','搜索方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('979','cnode_url','指定节点链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('980','index_tpl','首页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('981','indays','多少天以内添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('982','online_msg_amount','在线信息数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('983','list_tpl','列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('984','outdays','多少天以前添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('985','bklist_tpl','备用页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('986','ordertype','排序类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('987','agappurl','站外Url或站内Url均可','0');
INSERT INTO {$tblprefix}alangs VALUES ('988','isasc','是否升序','0');
INSERT INTO {$tblprefix}alangs VALUES ('989','detail_catas_cnode','详细类目节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('990','seasettres','搜索设置结果','0');
INSERT INTO {$tblprefix}alangs VALUES ('991','agcncorder','勾选需要参与生成节点的栏目或类系，并为选中项排序。','1272456390');
INSERT INTO {$tblprefix}alangs VALUES ('992','del_freeinfo_coclass','删除插件分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('993','ctaquestr','复合标识查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('994','cnode_all','一次生成全部节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('995','cnode_cnc','cnode_cnc','0');
INSERT INTO {$tblprefix}alangs VALUES ('996','remproman','远程方案管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('997','cnode_top','cnode_top','0');
INSERT INTO {$tblprefix}alangs VALUES ('998','update','更新','0');
INSERT INTO {$tblprefix}alangs VALUES ('999','projecttype','方案类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1000','filetypeext','文件类型扩展名','0');
INSERT INTO {$tblprefix}alangs VALUES ('1001','update_catas_cnode','更新类目结构节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('1002','edit_freeinfo_channel_list','编辑插件模型管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1003','nolimit_outconfig','不限冗余','0');
INSERT INTO {$tblprefix}alangs VALUES ('1004','iscustom','自定','0');
INSERT INTO {$tblprefix}alangs VALUES ('1005','outconfig_cnode','冗余节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('1006','system','系统','0');
INSERT INTO {$tblprefix}alangs VALUES ('1007','inconfig_cnode','正常节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('1008','message_title','信息标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('1009','addrempro','添加远程方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1010','ediremuplpro','编辑远程上传方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1011','is_outconfig_cnode','是否冗余节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('1012','editprojlist','编辑方案列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1014','add_freeinfo_channel','添加插件模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1015','addremouplpro','添加远程上传方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1016','outconfig','冗余','0');
INSERT INTO {$tblprefix}alangs VALUES ('1017','remdowfityp','远程下载文件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1018','catas_cnode_list','类目节点列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1019','fileext','文件扩展名','0');
INSERT INTO {$tblprefix}alangs VALUES ('1020','field','字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1021','maxlimited','最大限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1022','del_cnode','删除节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('1023','minilimited','最小限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1024','cnode_index_tpl','节点首页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1025','savecoclass','保存分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1026','otherset','其它设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1027','cnode_list_tpl','节点列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1028','cnode_bklist_tpl','节点备用页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1029','down_timeout','下载超时限制(秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1030','add_free_channel_field','添加插件模型字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1031','excludestxt','忽略含以下字串的远程文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1032','agexcludes','可用于设定不下载某些网址的远程文件，每行输入一个字串，全部内容请勿超过255字节。','0');
INSERT INTO {$tblprefix}alangs VALUES ('1033','config_name','配置名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1034','agnolimit','0或空表示不限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1035','catas_configs','类目结构','0');
INSERT INTO {$tblprefix}alangs VALUES ('1036','addfiletype','添加文件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1037','filter_cnode','筛选节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('1038','filesavecoclass','文件保存分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1039','mainline','首序类系','1272770097');
INSERT INTO {$tblprefix}alangs VALUES ('1040','detail0_modify_freeinfo_channel_field','详细修改插件模型字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1041','catalog_attr','栏目属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('1042','list_page0','列表页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1043','bklist','备用页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1044','delete_freeinfo_channel','删除插件模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1045','nosettle','未解决','0');
INSERT INTO {$tblprefix}alangs VALUES ('1046','cnode_admin_operate','节点管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1047','cnode_list_admin','节点列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1048','cnode_detail_set','节点详细设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1049','addremupprofity','添加远程上传方案文件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1050','dealing0','正在处理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1051','coclass_ename','分类标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1052','settled','已解决','0');
INSERT INTO {$tblprefix}alangs VALUES ('1053','detmodremouplpro','详细修改远程上传方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1054','parent_coclass','父分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1059','purcgood','已购商品','0');
INSERT INTO {$tblprefix}alangs VALUES ('1057','nopurgoods','未购商品','0');
INSERT INTO {$tblprefix}alangs VALUES ('1058','filter0_consult_msg','筛选咨询信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1060','isframe_coclass_i','结构分类(仅含子分类)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1061','filpicarc','筛选举报文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('1062','belongchannel','所属模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1063','deal_state','处理状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1064','piclisadmope','举报列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1065','picklistadmin','举报列表管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1066','clearrecord','清除记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('1067','allow_sale_arc','允许作者出售文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('1068','amount','数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1069','allow_sale_att','允许作者出售附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1070','pickrcli','举报文档列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1071','all_state','全部状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1072','time','时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1073','reason','原因','0');
INSERT INTO {$tblprefix}alangs VALUES ('1074','consult_msg_list','咨询信息列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1075','mode1','方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1076','praise_pics','顶次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('1077','usercname','用户名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1078','debase_pics','踩次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('1079','userid','用户ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('1080','state','状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1081','address','地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1082','arc_acondition_set','文档自动归类条件设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1083','operate','操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1084','favorite_pics','收藏次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('1085','tryusercname','尝试用户名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1086','consult_based_msg','咨询基本信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1087','goods_orders_amount','商品订单数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1088','trypassword','尝试密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1089','goods_price','商品价格','0');
INSERT INTO {$tblprefix}alangs VALUES ('1090','operatetime','操作时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1091','answer_amount','答案数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1092','crrecord','积分变更日志','0');
INSERT INTO {$tblprefix}alangs VALUES ('1093','answer_reward_currency','答疑悬赏积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1094','loginerrrecord','登录出错日志','0');
INSERT INTO {$tblprefix}alangs VALUES ('1095','adminoperate','管理操作日志','0');
INSERT INTO {$tblprefix}alangs VALUES ('1096','is_answer_close','答疑是否已关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('1097','consult_coclass_title','咨询分类 / 标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('1098','create_string','生成字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('1099','iscustom_coclass_field','自定分类字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1100','consult_member_add_update','咨询会员 / 添加 / 更新','0');
INSERT INTO {$tblprefix}alangs VALUES ('1101','set_state','设置状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1102','consult_commu_list','咨询交互列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1103','refmssyscac','刷新主站系统缓存','0');
INSERT INTO {$tblprefix}alangs VALUES ('1104','udef_query_string','用户定义查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('1105','refsubsyca','刷新子站系统缓存','0');
INSERT INTO {$tblprefix}alangs VALUES ('1106','consult_reply_content','咨询与回复内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1107','end','结束','0');
INSERT INTO {$tblprefix}alangs VALUES ('1108','add_arc_coclass','添加文档分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1109','reply','回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1110','coclass_manager','分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1111','add_coclass','添加分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1112','son_coclass','子分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1113','reply_content','回复内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1114','edit_acoclass_mlist','编辑文档分类管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1115','detail_marc_coclass','详细修改文档分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1116','del_arc_coclass','删除文档分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1117','html_to_txt','HtmlText字段转为文本储存 - 文档通用字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1118','filter_comment','筛选评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1119','is_check','是否审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('1120','bassyscac','基本系统缓存','0');
INSERT INTO {$tblprefix}alangs VALUES ('1121','del_comment','删除评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1122','table_to_txt','数据表转为文本储存','0');
INSERT INTO {$tblprefix}alangs VALUES ('1123','check_comment','审核评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1124','intagquliac','原始标识查询列表缓存','0');
INSERT INTO {$tblprefix}alangs VALUES ('1125','catcnocac','类目节点缓存','0');
INSERT INTO {$tblprefix}alangs VALUES ('1126','comment_list','评论列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1127','comment_content','评论内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1128','txt_to_table','文本储存转为数据表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1129','goolisdmope','商品列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1130','score','评分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1131','gooliadm','商品列表管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1132','orders','订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('1133','release_check','解除审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('1134','nocheck_orders','未审订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('1135','arc_comment_admin','文档评论管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1136','comment_list_admin','评论列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1137','filtgoods','筛选商品','0');
INSERT INTO {$tblprefix}alangs VALUES ('1138','edit_comment','编辑评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1139','ispurchased','是否已购','0');
INSERT INTO {$tblprefix}alangs VALUES ('1140','comment_title','评论标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('1141','filter0_orders','筛选订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('1142','arc_score','文档评分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1143','arc_comment_edit','文档评论编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('1144','edit_comment_content','编辑评论内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1145','praise_no','顶/踩','0');
INSERT INTO {$tblprefix}alangs VALUES ('1146','pickbug','举报','0');
INSERT INTO {$tblprefix}alangs VALUES ('1147','favorite','收藏','0');
INSERT INTO {$tblprefix}alangs VALUES ('1148','offer','报价','0');
INSERT INTO {$tblprefix}alangs VALUES ('1149','item_name','项目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1150','pick_url_style','调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1151','edit_citem_mlist','编辑交互项目管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1152','commu_item_copy','交互项目复制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1153','soc_citem_name','源交互项目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1154','soc_citem_type','源交互项目类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1155','new_citem_name','新交互项目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1156','copy_commu_item','复制交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1157','orders_check_state','订单审核状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1158','commu_item_set','交互项目设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1159','item_type','项目类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1160','is_allowance_citem','是否限额交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('3018','deleteorders','删除订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('1162','detail_modify_citem','详细修改交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1163','check_orders','审核订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('1164','forbid_reoperate','禁止重复操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1165','orders_admin','订单管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1166','increase','增加','0');
INSERT INTO {$tblprefix}alangs VALUES ('1167','decrease','减少','0');
INSERT INTO {$tblprefix}alangs VALUES ('1168','orders_list_admin','订单列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1169','reoperate_time_m','重复操作时间(分)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1170','forder_msg','订单信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1171','detail0','详细','0');
INSERT INTO {$tblprefix}alangs VALUES ('1172','minscore','最低评分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1173','member_current_currency','会员当前积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1174','maxscore','最高评分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1175','udef_func','用户定义函数','0');
INSERT INTO {$tblprefix}alangs VALUES ('1176','php_func_code','PHP函数代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1177','operate_permi_set','操作权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1178','view','显示','0');
INSERT INTO {$tblprefix}alangs VALUES ('1179','ava_msg_field','有效信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1180','isolute_page_manager','独立页面管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1181','add_report_tpl','添加举报模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1182','comment_autocheck','评论自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('1183','forbid_repeat_vote','禁止重复投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('1184','isolute_page_cname','独立页面名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1185','add_comment_tpl','添加评论模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1186','comment_list_tpl','评论列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1187','page_template','页面模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1188','admin_permi_set','管理权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1189','goodslist','商品列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1190','forbid_revote_support','禁止重复投票支持','0');
INSERT INTO {$tblprefix}alangs VALUES ('1191','page_pick_url','页面调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1192','answer_minlength','答案最小字长','0');
INSERT INTO {$tblprefix}alangs VALUES ('1193','answer_maxlength','答案最大字长','0');
INSERT INTO {$tblprefix}alangs VALUES ('1194','static','静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1195','item_ava_days','项目有效天数','0');
INSERT INTO {$tblprefix}alangs VALUES ('1196','subscribe','订阅','0');
INSERT INTO {$tblprefix}alangs VALUES ('1197','citem_admin','交互项目管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1198','add_page_tpl','发布页面模板','1271988346');
INSERT INTO {$tblprefix}alangs VALUES ('1199','add_isolute_page','添加独立页面','0');
INSERT INTO {$tblprefix}alangs VALUES ('1200','list_page_tpl','列表页面模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1201','isolute_page_template','独立页面模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1202','max_favorite_amount','最大收藏数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1203','purchasemember','购买会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('1204','reward_currency_type','悬赏积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1205','gather_mission_manager','采集任务管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1206','allow_reward_max_cu','悬赏允许最高积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1207','goodscname','商品名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1208','add_mission','添加任务','0');
INSERT INTO {$tblprefix}alangs VALUES ('1209','onlyclearreadpm','仅清除已读短信','0');
INSERT INTO {$tblprefix}alangs VALUES ('1210','credit_val_reward_cu','付出每个悬赏积分得到信用值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1211','pmfromids','发送人ID(逗号分隔多个ID)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1212','pmclearfilter','短信清除筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('3165','relate','关联','0');
INSERT INTO {$tblprefix}alangs VALUES ('1214','mission_cname','任务名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1215','pmcontent','短信内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1216','pmtitle','短信标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('3166','allowance','限额','0');
INSERT INTO {$tblprefix}alangs VALUES ('1218','gather_model','采集模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1219','add_answer_tpl','添加答疑模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1220','pmcontentset','短信内容设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1221','handworkchoose','手动选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('1222','inalbum_mission','辑内任务','0');
INSERT INTO {$tblprefix}alangs VALUES ('1223','nolimitusergroup','不限会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('1224','pmtonames','发送至(逗号分隔多个会员名称)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1225','netsite_gather','网址采集','1264120825');
INSERT INTO {$tblprefix}alangs VALUES ('1226','pmtoids','发送至(逗号分隔多个会员ID)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1227','answer_list_tpl','答疑列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1228','acceptmemberfilter','接收会员筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('1229','detmodmedpla','详细修改视频播放器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1230','discount_nov','折扣无效','0');
INSERT INTO {$tblprefix}alangs VALUES ('1231','maxmode','最大值模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1232','content_gather','内容采集','1264120841');
INSERT INTO {$tblprefix}alangs VALUES ('1233','playertemplate','播放器模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1234','addmode','多重累计模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1235','defplayfileformat','默认播放文件格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1236','cart_mode','购物车模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1237','playertype','播放器类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1238','playercname','播放器名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1239','single_goods_mode','单商品模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1240','playerset','播放器设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1241','goods_purchase_mode','商品购买模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1242','edimedplalis','编辑视频播放器列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1243','discount1','折扣1   交互项目折扣(%)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1244','addmedplay','添加视频播放器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1245','discount2','自动计算会员组折扣','0');
INSERT INTO {$tblprefix}alangs VALUES ('1246','discount3','折扣3   类目折扣','0');
INSERT INTO {$tblprefix}alangs VALUES ('1247','alldcmode','综合折扣模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1248','addplayer','添加播放器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1249','purchase_list_tpl','购买列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1250','offer_msg_autocheck','报价信息自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('1251','offer_msg_ava_days','报价信息有效天数','0');
INSERT INTO {$tblprefix}alangs VALUES ('1252','content_output','内容入库','1264120856');
INSERT INTO {$tblprefix}alangs VALUES ('1253','playermanager','播放器管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1254','add_offer_tpl','添加报价模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1255','flashplayer','Flash播放器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1256','offer_list_tpl','报价列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1257','mediaplayer','视频播放器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1258','detmodpermpro','详细修改权限方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1259','auto_pro_av_price','自动生成产品平均价格','0');
INSERT INTO {$tblprefix}alangs VALUES ('1260','detailset','详情设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1261','detailpermis','详情权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('1262','no','否','0');
INSERT INTO {$tblprefix}alangs VALUES ('1263','lowest','最低','0');
INSERT INTO {$tblprefix}alangs VALUES ('1264','prior','优先级','0');
INSERT INTO {$tblprefix}alangs VALUES ('1265','play','播放','0');
INSERT INTO {$tblprefix}alangs VALUES ('1266','download','下载','0');
INSERT INTO {$tblprefix}alangs VALUES ('1267','issuearchive','发表文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('1268','catasbrowse','类目浏览','0');
INSERT INTO {$tblprefix}alangs VALUES ('1269','archivebrowse','文档浏览','0');
INSERT INTO {$tblprefix}alangs VALUES ('1270','edipermpromanlist','编辑权限方案管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1271','addpermiproj','添加权限方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1272','pleetdet','请设置详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('1273','onekey_all_finish','一键全部完成','0');
INSERT INTO {$tblprefix}alangs VALUES ('1274','defaultproject','默认方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1275','permprojmana','权限方案管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1276','highest','最高','0');
INSERT INTO {$tblprefix}alangs VALUES ('1277','low','低','0');
INSERT INTO {$tblprefix}alangs VALUES ('1278','center','中','0');
INSERT INTO {$tblprefix}alangs VALUES ('1279','high','高','0');
INSERT INTO {$tblprefix}alangs VALUES ('1280','visitingpay','上门支付','0');
INSERT INTO {$tblprefix}alangs VALUES ('1281','postoffremit','邮局汇款','0');
INSERT INTO {$tblprefix}alangs VALUES ('1282','bigimage','大图','0');
INSERT INTO {$tblprefix}alangs VALUES ('1283','paywarrant','支付凭证','0');
INSERT INTO {$tblprefix}alangs VALUES ('1284','contactemail','联系Email','0');
INSERT INTO {$tblprefix}alangs VALUES ('1285','contatelep','联系电话','0');
INSERT INTO {$tblprefix}alangs VALUES ('1286','contaname','联系人姓名','0');
INSERT INTO {$tblprefix}alangs VALUES ('1287','currsavtime','积分充值时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1288','casarrtim','现金到帐时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1289','messsentim','信息发送时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1290','payorderidsn','支付订单号','0');
INSERT INTO {$tblprefix}alangs VALUES ('1291','payinter','支付接口','0');
INSERT INTO {$tblprefix}alangs VALUES ('1292','handrmbi','手续费(人民币)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1293','payamourmbi','支付数量(人民币)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1294','paymode','支付模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1295','paymesslook','支付信息查看','0');
INSERT INTO {$tblprefix}alangs VALUES ('1296','paymessamod','支付信息修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('1297','edit_gather_mission','编辑采集任务管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1298','paysavlisadmoper','支付充值列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1299','gather_mission_add','采集任务添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('1300','cashsavadmin','现金充值管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1301','cashsav','现金充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1302','payarrcansav','支付到帐才能充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1303','formemcasaccsav','为会员现金帐户充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1304','gather_mission_cname','采集任务名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1305','setarrsta','设置到帐状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1306','belong_gather_mission','所属采集任务','0');
INSERT INTO {$tblprefix}alangs VALUES ('1307','is_create_av_price','是否生成平均价格','0');
INSERT INTO {$tblprefix}alangs VALUES ('1308','av_price_field_type','平均价格字段类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1309','add_gather_mission','添加采集任务','0');
INSERT INTO {$tblprefix}alangs VALUES ('1310','av_price_field_ename','平均价格字段标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1311','gather_based_setting','采集基本设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1312','onlynoartrarecdel','仅未到账或已充值的支付记录才能删除','0');
INSERT INTO {$tblprefix}alangs VALUES ('1313','delpayrec','删除支付记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('1314','choose_reward_cutype','请指定正确的悬赏积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1315','arrived','已到账','0');
INSERT INTO {$tblprefix}alangs VALUES ('1316','noarrive','未到账','0');
INSERT INTO {$tblprefix}alangs VALUES ('1317','savdate','充值日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('1318','common_field','通用字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1319','arrivedate','到帐日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('1320','recodate','记录日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('1321','payamount','支付数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1322','paymember','支付会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('1323','channel_field','模型字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1324','test_rule','测试规则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1325','payrecolist','支付记录列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1326','onlpayinter','在线支付接口','0');
INSERT INTO {$tblprefix}alangs VALUES ('1327','currweattra','积分是否已充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1328','casweaarr','现金是否已到账','0');
INSERT INTO {$tblprefix}alangs VALUES ('1329','forbid_repeat_add','禁止重复添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('1330','filpayrec','筛选支付记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('1331','repeat_add_time_m','重复添加时间(分)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1332','transed','已充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1333','notrans','未充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1334','charset','页面编码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1335','reply_autocheck','回复自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2992','cu_citems','启用的分类或字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1337','banktransfer','银行转账','0');
INSERT INTO {$tblprefix}alangs VALUES ('1339','onlinepay','在线支付','0');
INSERT INTO {$tblprefix}alangs VALUES ('2991','cu_aitems','管理方的有效控制项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1341','add_reply_tpl','添加回复模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1342','downsyscondatfi','下载系统配置数据文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1343','instwebscon','安装网站配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1344','sameversion','版本严格匹配','0');
INSERT INTO {$tblprefix}alangs VALUES ('1345','reply_list_tpl','回复列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1346','login_website','登录网站','0');
INSERT INTO {$tblprefix}alangs VALUES ('1347','arc_subscribe_mode','文档订阅模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1348','tempfoldcnam','模板文件夹名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1349','uplfolcnam','上传文件夹名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1350','att_subscribe_mode','附件订阅模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1351','add_comment','添加评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1352','delsyscondafil','删除系统配置数据文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1353','admin_arc_comment','管理指定文档的评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1354','expsyscondat','输出系统配置数据','0');
INSERT INTO {$tblprefix}alangs VALUES ('1355','edit_point_comment','编辑指定的评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('3545','qt_url','盖楼引用','1274083398');
INSERT INTO {$tblprefix}alangs VALUES ('1357','add_reply','添加回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1358','exportime','输出时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1359','size','大小','0');
INSERT INTO {$tblprefix}alangs VALUES ('1360','edit_submit_reply','编辑提交的指定回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1361','version','版本','0');
INSERT INTO {$tblprefix}alangs VALUES ('1362','confilecnam','配置文件名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1363','expdafilli','输出数据文件列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1364','admin_rec_arc_reply','管理收到的指定文档的回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1365','usehex','十六进制方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1366','admin_rec_reply','管理收到的指定的回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1367','sqlcompat','建表语句格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1368','dbfilename','备份文件名(不需扩展名)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1370','ordersmodify','订单修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('1371','m_add_edit_offer','会员添加/编辑报价','0');
INSERT INTO {$tblprefix}alangs VALUES ('1372','netsite_source_rule','网址来源规则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1373','ordemessset','订单信息设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1374','admin_arc_offer','管理指定文档的报价','0');
INSERT INTO {$tblprefix}alangs VALUES ('1375','price','价格','0');
INSERT INTO {$tblprefix}alangs VALUES ('1376','weight','重量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1377','admin_point_offer','管理指定的报价','0');
INSERT INTO {$tblprefix}alangs VALUES ('1378','handwork_source_netsite','手动来源网址<br> (每行一个网址，可输入多行)','1264120544');
INSERT INTO {$tblprefix}alangs VALUES ('1379','ordergoodlist','订单商品列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1380','arc_offer_list','指定文档的报价列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1381','shiping','送货方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1382','answer_pick_url','答疑调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1383','payedcashyuan','已支付现金(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1384','serial_source_netsite','序列来源网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1385','ordfeeallamyua','订单费用总额(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1386','del_answer_url','删除答案链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1387','shipfeeyuan','送货费用(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1388','goodfeeyuan','商品费用(元)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1389','vote_sup_answer_url','投票支持答案链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1391','received','已收货','0');
INSERT INTO {$tblprefix}alangs VALUES ('1392','noreceive','未收货','0');
INSERT INTO {$tblprefix}alangs VALUES ('1393','question_admin_url','问题管理链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1394','ordersstate','订单状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1395','purchase_pick_url','向网站购物的调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1396','orderssncode','订单编号','0');
INSERT INTO {$tblprefix}alangs VALUES ('1397','ordebasedset','订单基本设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1398','goods_pu_record_url','商品购买记录链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1399','setordesta','设置订单状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1400','arc_subscribe_pick_url','文档订阅调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1401','att_subscribe_pick_url','附件订阅调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1402','nosend','未发货','0');
INSERT INTO {$tblprefix}alangs VALUES ('1403','sended','已发货','0');
INSERT INTO {$tblprefix}alangs VALUES ('1404','arc_praise_operate','指定文档的顶操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1405','arc_debase_operate','指定文档的踩操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1406','serial_start_pagecode','序列开始页码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1407','arc_score_operate','指定文档的评分操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1408','ordersdate','订单日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('1409','serial_end_pagecode','序列结束页码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1410','arc_favorite','指定文档的收藏','0');
INSERT INTO {$tblprefix}alangs VALUES ('1411','payed','已支付','0');
INSERT INTO {$tblprefix}alangs VALUES ('1412','auto_purchase','自动购买','0');
INSERT INTO {$tblprefix}alangs VALUES ('1413','ordallamo','订单总额','0');
INSERT INTO {$tblprefix}alangs VALUES ('1414','confirm_purchase','确认购买','0');
INSERT INTO {$tblprefix}alangs VALUES ('1415','more_pick_url','更多调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1416','orderslist','订单列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1417','score_amount','评分数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1418','isreceived','是否已收货','0');
INSERT INTO {$tblprefix}alangs VALUES ('1419','issended','是否已发货','0');
INSERT INTO {$tblprefix}alangs VALUES ('1420','ischecked','是否已审','0');
INSERT INTO {$tblprefix}alangs VALUES ('1421','css_file_admin','CSS文件管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1422','none','无','0');
INSERT INTO {$tblprefix}alangs VALUES ('1423','nolimiship','不限送货方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1424','js_file','JS文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1425','edit_cssfile_mlist','编辑CSS文件管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1426','edit_jsfile_mlist','编辑JS文件管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1427','based_content_page0','基本内容页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1428','add_css_js_file','添加css或js文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1429','content_trace_page0_1','内容追溯页1','0');
INSERT INTO {$tblprefix}alangs VALUES ('1430','file_saveas','文件另存为','0');
INSERT INTO {$tblprefix}alangs VALUES ('1431','file_content','文件内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1432','content_trace_page0_2','内容追溯页2','0');
INSERT INTO {$tblprefix}alangs VALUES ('1433','ufrompage','网址来自合辑的哪个页面','0');
INSERT INTO {$tblprefix}alangs VALUES ('1434','add_file','添加 %s 文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1435','reverseorder_gather','倒序采集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1436','netsite_gather_rule','网址采集规则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1437','css_file','css文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1438','page_initial_rgp','页面采集范围<br /> 采集模印','1264120679');
INSERT INTO {$tblprefix}alangs VALUES ('1439','js_file_admin','js文件管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1440','netsite_list_cell_split_tag','网址列表分隔符','1264120722');
INSERT INTO {$tblprefix}alangs VALUES ('1441','copy_css_js_file','复制css或js文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1442','netsite_gather_pattern','网址采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('1443','edinmlandet','编辑会员中心语言包详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('1444','title_gather_pattern','标题采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('1445','result_netsite_mustinc','结果网址必含','0');
INSERT INTO {$tblprefix}alangs VALUES ('1446','soc_file','源文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1447','mlangcontent','会员中心语言包内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1448','trace_netsite_rule','追溯网址规则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1449','mlangremark','会员中心语言包备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('1450','trace_netsite_1_gp','追溯网址1采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('1451','file_edit','文件编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('1452','mlangename','会员中心语言包标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1453','file_name','文件名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1454','trace_netsite_1_m','追溯网址1必含','0');
INSERT INTO {$tblprefix}alangs VALUES ('1455','editmlang','编辑会员中心语言包','0');
INSERT INTO {$tblprefix}alangs VALUES ('1456','trace_netsite_1_f','追溯网址1禁含','0');
INSERT INTO {$tblprefix}alangs VALUES ('1457','addmlang','添加会员中心语言包','0');
INSERT INTO {$tblprefix}alangs VALUES ('1458','detail_modify_file','详细修改%s文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1459','delete_file','删除%s文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1460','trace_netsite_2_gp','追溯网址2采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('1461','update_js_file','更新js文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1462','trace_netsite_2_m','追溯网址2必含','0');
INSERT INTO {$tblprefix}alangs VALUES ('1463','copy_file','复制%s文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1464','trace_netsite_2_f','追溯网址2禁含','0');
INSERT INTO {$tblprefix}alangs VALUES ('1465','reply_coclass_manager','回复分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1466','detail0_modify_gm','详细修改采集任务','0');
INSERT INTO {$tblprefix}alangs VALUES ('1467','editmlanglist','编辑会员中心语言包列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1468','e_re_class_mlist','编辑回复分类管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1469','mlangadmin','会员中心语言包管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1470','add_reply_class','添加回复分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1471','no_splitpage','无分页字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1472','del_reply_class','删除回复分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1473','mlangfilter','会员中心语言包筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('1474','co_class_manager','交互分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1475','reply_class_manager','回复分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1476','editclangdetail','编辑前台语言包详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('1477','clangcontent','前台语言包内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1478','timeout_s','连接超时(秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1479','clangremark','前台语言包备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('1480','clangename','前台语言包标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1481','editclang','编辑前台语言包','0');
INSERT INTO {$tblprefix}alangs VALUES ('1482','addclang','添加前台语言包','0');
INSERT INTO {$tblprefix}alangs VALUES ('1483','splitpage_gather_rule','分页采集规则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1484','editclanglist','编辑前台语言包列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1485','clangadmin','前台语言包管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1486','splitpage_field','分页字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1487','clangfilter','前台语言包筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('1488','notall_splitpage_navi','分页导航是否完整','1264121369');
INSERT INTO {$tblprefix}alangs VALUES ('1489','splitpage_navi_region_br_gp','分页导航区域<br /> 采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('1490','memaltdetmodope','会员模型变更详情修改操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1491','modmemchanalt','修改会员模型变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('1492','memaltmes','会员变更信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1493','memchaaltmod','会员模型变更方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1494','memtypneeopt','会员类型申请选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('1495','memchaalliadope','会员模型变更列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1496','memchaaltadm','会员模型变更管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1497','targetchannel','目标模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1498','sourcechannel','来源模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1499','memchanaltli','会员模型变更列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1500','alttarcha','变更目标模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1501','altsoucha','变更来源模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1502','splitpage_url_mustinc','分页链接必含','0');
INSERT INTO {$tblprefix}alangs VALUES ('1503','pu_msg_field_manager','购买信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1504','e_pu_msg_field_mlist','编辑购买信息字段管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1505','add_pu_field','添加购买字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1506','splitpage_url_forbidinc','分页链接禁含','0');
INSERT INTO {$tblprefix}alangs VALUES ('1507','add_pu_msg_field','添加购买信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1508','det_modify_pu_msg_field','详细修改购买信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1509','rt','区块替代','0');
INSERT INTO {$tblprefix}alangs VALUES ('1510','cataslist','类目列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1511','add_offer_field','添加报价字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1512','archivecontent','文档内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1513','gather_field_rule','采集字段规则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1514','add_offer_msg_field','添加报价信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1515','freeinfocontent','插件信息内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1516','output_based_setting',']入库基本设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1517','mustfields','以下采集字段为空时不能入库','0');
INSERT INTO {$tblprefix}alangs VALUES ('1518','archiverelated','文档相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('1519','output_default_value',']入库默认值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1520','gather_netsite_rule_test','采集网址规则测试','0');
INSERT INTO {$tblprefix}alangs VALUES ('1521','reply_msg_field_manager','回复信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1522','none_source_netsite','无来源网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1523','all_source_netsite','全部来源网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1524','archivecommu','文档交互','0');
INSERT INTO {$tblprefix}alangs VALUES ('1525','add_pickbug_msg_field','添加举报信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1526','det_modify_pickbug_msg_field','详细修改举报信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1527','membercommu','会员交互','0');
INSERT INTO {$tblprefix}alangs VALUES ('1528','current_test_source_netsite','当前测试来源网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1529','commu_field_manager','交互字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1530','con_weblist','内容网址列表 (测试网址结果数量限制10)','1271490696');
INSERT INTO {$tblprefix}alangs VALUES ('1531','spacepage','个人空间页面','0');
INSERT INTO {$tblprefix}alangs VALUES ('1532','netsite_title','网址标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('1533','pu_field_manager','购买字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1534','content_netsite','内容网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1535','offer_field_manager','报价字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1536','jstemplate','js模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1537','reply_field_manager','回复字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1538','pttag','分页标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1539','cttag','复合标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1540','trace_netsite_1','追溯网址1','0');
INSERT INTO {$tblprefix}alangs VALUES ('1541','utfield','特殊字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1542','initdata1','原始数据','0');
INSERT INTO {$tblprefix}alangs VALUES ('1543','ref','参考','0');
INSERT INTO {$tblprefix}alangs VALUES ('1544','comment_field_manager','评论字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1545','pickbug_field_manager','举报字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1546','tagtype','标识类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1547','tagstyle','标识样式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1548','offer_msg_field_manager','报价信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1549','tagmap','标识地图','0');
INSERT INTO {$tblprefix}alangs VALUES ('1550','trace_netsite_2','追溯网址2','0');
INSERT INTO {$tblprefix}alangs VALUES ('1551','copynormaltemplate','复制常规模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1552','tempfilsav','模板文件另存为','0');
INSERT INTO {$tblprefix}alangs VALUES ('1553','add_reply_field','添加回复字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1554','gather_content_rule_test','采集内容规则测试','0');
INSERT INTO {$tblprefix}alangs VALUES ('1555','soctemfi','源模板文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1556','add_reply_msg_field','添加回复信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1557','templateclass','模板类别','0');
INSERT INTO {$tblprefix}alangs VALUES ('1558','current_want_gather0','当前需要采集的网址数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1559','templatecname','模板名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1560','cmt_msg_field_manager','评论信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1561','copnormapagetemp','复制常规页面模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1562','please_gather_netsite','请先采集内容网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1563','add_comment_field','添加评论字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1564','detamodnormtem','详细修改常规模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1565','add_cmt_msg_field','添加评论信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1566','current_test_netsite_title','当前测试网址标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('1567','edinortemmanli','编辑常规模板管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1568','pb_msg_field_manager','举报信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1569','templatefile','模板文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1570','add_pickbug_field','添加举报字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1571','current_test_netsite','当前测试网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1572','currency_manager','积分管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1573','gather_result','采集结果','0');
INSERT INTO {$tblprefix}alangs VALUES ('1574','currency_name','积分名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1575','no1_gather','未采集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1576','already1_gather','已采集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1577','unit','单位','0');
INSERT INTO {$tblprefix}alangs VALUES ('1578','norpagtempadm','常规页面模板管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1579','reg_initval','注册初始值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1580','addnormtemp','添加常规模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1581','no1_output','未入库','0');
INSERT INTO {$tblprefix}alangs VALUES ('1582','add_currency','添加积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1583','already1_output','已入库','0');
INSERT INTO {$tblprefix}alangs VALUES ('1584','edit_currency_type','编辑积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1585','noabover','未完结','0');
INSERT INTO {$tblprefix}alangs VALUES ('1586','putin','入库','0');
INSERT INTO {$tblprefix}alangs VALUES ('1587','modify_currency_mlist','修改积分管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1588','filter0_gather_record','筛选采集记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('1589','edit_currency','编辑积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1590','currency_unit','积分单位','0');
INSERT INTO {$tblprefix}alangs VALUES ('1591','settitemplty','设置模板类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1592','current_gather_mission','当前采集任务&nbsp;:&nbsp;','0');
INSERT INTO {$tblprefix}alangs VALUES ('1593','currency_allow_inout','积分可以充/扣值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1594','settempcna','设置模板名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1595','crpolicy','积分增减策略','0');
INSERT INTO {$tblprefix}alangs VALUES ('1596','gather_state','采集状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1597','output_state','入库状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('1598','issue_arc','发表文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('1599','issue_freeinfo','发表插件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1600','weather_abover_album','是否完结合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('1601','issue_comment','发表评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1602','nortemaddpu','常规模板添加入库','0');
INSERT INTO {$tblprefix}alangs VALUES ('1603','purchase_goods','购买商品','0');
INSERT INTO {$tblprefix}alangs VALUES ('1604','templatetype','模板类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1605','question_answer','问题答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1606','autosearch','自动搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('1607','favorite_arc','收藏文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('1608','content_gather_manager','内容采集管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1609','other_commu','其它交互','0');
INSERT INTO {$tblprefix}alangs VALUES ('1610','searchpage0','搜索页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1611','website_vote','网站投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('1612','send_pm','发送短信','0');
INSERT INTO {$tblprefix}alangs VALUES ('1613','gather','采集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1614','website_search','网站搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('1615','result','结果','0');
INSERT INTO {$tblprefix}alangs VALUES ('1616','add_currency_type','添加积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1617','add_price_project','添加价格方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1618','already1_abover','已完结','0');
INSERT INTO {$tblprefix}alangs VALUES ('1619','price_name','价格名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1620','setting_album_abover','合辑完结','0');
INSERT INTO {$tblprefix}alangs VALUES ('1621','currency_amount','积分数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1622','add_cu_price_prj','添加积分价格方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1623','edit_cu_price_prj','编辑积分价格方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1624','add_cu_ex_prj','添加积分兑换方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1625','edit_cu_ex_prj','编辑积分兑换方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1626','edit_cu_ex_prj_mlist','编辑积分兑换方案管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1627','member_cu_saving','会员积分充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1628','det_modify_cutype','详细修改积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1629','onekey_auto_gather','一键自动采集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1630','url_auto_gather','链接自动采集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1631','content_auto_gather','内容自动采集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1632','content_auto_output','内容自动入库','0');
INSERT INTO {$tblprefix}alangs VALUES ('1633','rule','规则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1634','detmodspatempro','详细修改空间模板方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1635','spalistemp','空间列表模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1636','spaindtem','空间首页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('1637','enaspacat','启用空间栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1638','price_prj_manager','价格方案管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1639','spacatcna','空间栏目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1640','spatemproset','空间模板方案设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1641','addspaccata','添加空间栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1642','catalogremark','栏目备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('1643','uclmaxaddamomem','个人分类最大添加数量 / 会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('1644','addspatempro','添加空间模板方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1645','temprocna','模板方案名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1646','spatemproadd','空间模板方案添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('1647','edispacatmanlis','编辑空间栏目管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1648','add_ex_prj','添加兑换方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1649','edispatepromanis','编辑空间模板方案管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1650','cocllimi','分类限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1651','source_currency','来源积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1652','src_cu_val','来源积分值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1653','spacatamana','空间栏目管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1654','inchuse','在以下模型中使用','0');
INSERT INTO {$tblprefix}alangs VALUES ('1655','ex_currency','兑换积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1656','ex_cu_val','兑换积分值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1657','spatemproman','空间模板方案管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1658','cash','现金','0');
INSERT INTO {$tblprefix}alangs VALUES ('1659','saving','充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1660','deductvalue','扣值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1661','member_inout','会员充/扣值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1662','choose_cutype','选择积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1663','choose','选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('1664','operate_type','操作类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1665','detamod','详细修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('1666','add_val','添加值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1667','closecode','关闭代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1668','copycode','复制代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1669','reduce_val','减少值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1670','curtagoftemcod','当前标识的模板代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1671','savingmode','充/扣值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1672','tagenidill','标识英文名称不合规范','1264819540');
INSERT INTO {$tblprefix}alangs VALUES ('1673','addreduce','增减','0');
INSERT INTO {$tblprefix}alangs VALUES ('1674','inptagenid','请输入标识英文名称','1264819540');
INSERT INTO {$tblprefix}alangs VALUES ('1675','createcode','生成代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1676','enid','标识英文名称','1264819540');
INSERT INTO {$tblprefix}alangs VALUES ('1677','typeset','%s 设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1678','help','帮助','0');
INSERT INTO {$tblprefix}alangs VALUES ('1679','newtempcna','新模板名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1680','newtagid','新标识ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('1681','filter_record','筛选记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('1682','newtagcnam','新标识名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1683','operate_mode','操作方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1684','soctempcnam','源模板名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1685','soctagid','源标识ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('1686','soctagcname','源标识名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1687','crmode','积分增减','0');
INSERT INTO {$tblprefix}alangs VALUES ('1688','inaarcli','辑内文档列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1689','seararchli','搜索文档列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1690','relarclis','相关文档列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1691','crrecordmname','操作对象(逗号分隔多个名称)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1692','freepickli','自由调用列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1693','crrecordfrommname','经手人(逗号分隔多个名称)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1694','startdate','开始日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('1695','arcconmod','单个文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('1696','arcamosta','文档数量统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('1697','pre','上一页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1698','cu_operate_record','积分操作记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('1699','catasmod','单个类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1700','next','下一页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1701','iscusfunlist','自定函数列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1702','catasplacnav','类目位置导航','0');
INSERT INTO {$tblprefix}alangs VALUES ('1703','tomname','操作对象','0');
INSERT INTO {$tblprefix}alangs VALUES ('1704','frommname','经手人','0');
INSERT INTO {$tblprefix}alangs VALUES ('1705','context','上下篇','0');
INSERT INTO {$tblprefix}alangs VALUES ('1706','inalbumcontext','辑内上下篇','0');
INSERT INTO {$tblprefix}alangs VALUES ('1707','operate_date','操作日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('1708','freelist','插件信息列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1709','messconmod','单个插件信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1710','arc_browse','文档浏览','0');
INSERT INTO {$tblprefix}alangs VALUES ('1711','isolpageurl','独立页链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1712','arc_sale','文档出售','0');
INSERT INTO {$tblprefix}alangs VALUES ('1713','archcommlist','文档交互列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1714','membcommlist','会员交互列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1715','arc_issue','文档发表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1716','votemod','投票模块','0');
INSERT INTO {$tblprefix}alangs VALUES ('1717','votelist','投票列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1718','att_operate','附件操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1719','memberlist','会员列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1720','membermessage','单个会员信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1721','att_sale','附件出售','0');
INSERT INTO {$tblprefix}alangs VALUES ('1722','membamousta','会员数量统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('1723','keywlist','关键词列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1724','edit_cu_prj_mlist','编辑积分价格方案管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1725','archichanlist','文档模型列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1726','membchalist','会员模型列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1727','space0','空间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1728','catasplace','类目位置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1729','ex_prj_manager','兑换方案管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1730','choose_table','选择数据表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1731','subsitelist','子站列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1732','backup_param_set','备份参数设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1733','imagemod','图片模块','0');
INSERT INTO {$tblprefix}alangs VALUES ('1734','dbsizelimit','备份分卷大小(KB)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1735','downloadmod','下载模块','0');
INSERT INTO {$tblprefix}alangs VALUES ('1736','flashmod','Flash模块','0');
INSERT INTO {$tblprefix}alangs VALUES ('1737','mediamod','视频模块','0');
INSERT INTO {$tblprefix}alangs VALUES ('1738','sqlcharset','强制字符集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1739','imageslist','图集列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1740','backup','备份','0');
INSERT INTO {$tblprefix}alangs VALUES ('1741','downlist','下载列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1742','dbbackup','数据库备份','0');
INSERT INTO {$tblprefix}alangs VALUES ('1743','flashlist','Flash列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1744','medialist','视频列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1745','attachmenturl','附件链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1746','timeviewtag','时间显示标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1747','backup_file_list','备份文件列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1748','txtdealtag','文本处理标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1749','backup_file_name','备份文件名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1750','archfeemes','文档费用信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1751','iscusfuntag','自定函数标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('1752','volume','分卷','0');
INSERT INTO {$tblprefix}alangs VALUES ('1753','pttxt','分页文本','0');
INSERT INTO {$tblprefix}alangs VALUES ('1754','backup_time','备份时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('1755','ptimages','分页图集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1756','searmembelist','搜索会员列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1757','del_db_backup_file','删除数据库备份文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1758','import_db_backup','导入数据库备份','0');
INSERT INTO {$tblprefix}alangs VALUES ('1759','optimize','优化','0');
INSERT INTO {$tblprefix}alangs VALUES ('1760','repair','修复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1761','db_tb_optimize','数据库数据表优化','0');
INSERT INTO {$tblprefix}alangs VALUES ('1762','db_tb_repair','数据库数据表修复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1763','addtype','添加 %s','0');
INSERT INTO {$tblprefix}alangs VALUES ('1764','run_sql_code','运行SQL代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1765','im_sql_code_content','输入SQL代码内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1766','dl_db_backup_file','下载数据库备份文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1767','curtagtemcod','当前标识模板代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1768','closewindow','关闭窗口','0');
INSERT INTO {$tblprefix}alangs VALUES ('1769','typeadmin','%s 管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1770','select_table','请选择数据表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1771','code','代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1772','membchaaltpro','会员模型变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1773','input_notnull','输入不能为空','0');
INSERT INTO {$tblprefix}alangs VALUES ('1774','form_guide','表单提示说明','0');
INSERT INTO {$tblprefix}alangs VALUES ('1775','delusealtpro','删除会员组变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1776','delmemchaaltpro','删除会员模型变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1777','modusealtpro','修改会员组变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1778','agguide','换行请使用','0');
INSERT INTO {$tblprefix}alangs VALUES ('1779','useraltautch','会员组变更自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('1780','default_value','默认输入值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1781','ediusergaltpro','编辑会员组变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1782','addusergaltpro','添加会员组变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1783','date_range','输入日期范围限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1784','php_func','输入PHP函数代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1785','addproject','添加方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1786','memaltautche','会员变更自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('1787','tarmemcha','目标会员模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1788','sourmemcha','来源会员模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1789','nosearch','不参与搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('1790','edmemchaaltpro','编辑会员模型变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1791','modmemchaaltpro','修改会员模型变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1792','db_field_list','数据库字段列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1793','onesearch','精确搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('1794','autocheck','自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('1795','content_replace','内容替换','0');
INSERT INTO {$tblprefix}alangs VALUES ('1796','useltpro','会员组变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1797','field_remark','字段备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('1798','multisearch','范围搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('1799','normal','常规','0');
INSERT INTO {$tblprefix}alangs VALUES ('1800','addmemchaaltpro','添加会员模型变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1801','regular','正则','0');
INSERT INTO {$tblprefix}alangs VALUES ('1802','field_cre_operate','字段内容替换操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('1803','issearch','可作搜索条件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1804','current_table','当前数据表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1805','current_field','当前字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1806','search_mode','搜索模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1807','search_txt','搜索文本','0');
INSERT INTO {$tblprefix}alangs VALUES ('1808','delmemcenmeite','删除会员中心菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1809','delmemcenmencoc','删除会员中心菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1810','replace_txt','替换文本','0');
INSERT INTO {$tblprefix}alangs VALUES ('1811','memcenmeitedet','编辑会员中心菜单项目详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('1812','remote_download','远程下载方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1813','where_plus_string','WHERE附加条件字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('1814','beluseval','菜单显示权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1815','meniteord','菜单项目排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('1816','dont_inc_where','不要含WHERE','0');
INSERT INTO {$tblprefix}alangs VALUES ('1817','menuitemurl','菜单项目链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1818','menuitemcname','菜单项目名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1819','replace','替换','0');
INSERT INTO {$tblprefix}alangs VALUES ('1820','annex_limit','附件数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1821','edmecemecode','编辑会员中心菜单分类详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('1822','regular_help','正则帮助','0');
INSERT INTO {$tblprefix}alangs VALUES ('1823','edmecemeli','编辑会员中心菜单列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1824','f_view_player','表单控件显示播放器列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1825','guide0','注释','0');
INSERT INTO {$tblprefix}alangs VALUES ('1826','menu','菜单','0');
INSERT INTO {$tblprefix}alangs VALUES ('1827','start_replace','开始替换','0');
INSERT INTO {$tblprefix}alangs VALUES ('1828','admencoc','添加菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1829','memcenmenman','会员中心菜单','0');
INSERT INTO {$tblprefix}alangs VALUES ('1830','addmemcenmenite','添加会员中心菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1831','value_range','输入值范围限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1832','addmemcenmenco','添加会员中心菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1833','guidecontent','提示说明内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1834','memcenpacna','会员中心页面名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1835','memcenpaggui','会员中心页面提示说明','0');
INSERT INTO {$tblprefix}alangs VALUES ('1836','delmenuitem','删除菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1837','db_src_manager','外部数据源管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1838','format_regular_check_str','输入格式正则检查字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('1839','delmenucoc','删除菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1840','db_server','数据库服务器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1841','edimenitdet','编辑菜单项目详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('1842','db_src_name','外部数据源名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1843','db_user','数据库用户','0');
INSERT INTO {$tblprefix}alangs VALUES ('1844','istxt_field','是否文本储存字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1845','db_name','数据库名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1846','db_charset','数据库字符集','0');
INSERT INTO {$tblprefix}alangs VALUES ('1847','yes','是','0');
INSERT INTO {$tblprefix}alangs VALUES ('1848','edimencocdet','编辑菜单分类详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('1849','db_config','数据库结构','0');
INSERT INTO {$tblprefix}alangs VALUES ('1850','cocdefurl','分类默认链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1851','add_db_src','添加外部数据源','0');
INSERT INTO {$tblprefix}alangs VALUES ('1852','db_pwd','数据库密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1853','controller_mode','表单控件模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1854','edit_db_src','编辑外部数据源','0');
INSERT INTO {$tblprefix}alangs VALUES ('1855','edmenitli','编辑菜单项目列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1856','dbcheck','测试连接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1857','addtopcocl','添加顶级分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1858','subsimenadm','子站菜单管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1859','msimenadm','主站菜单管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1860','det_modify_db_src','详情修改外部数据源','0');
INSERT INTO {$tblprefix}alangs VALUES ('1861','chositetyp','选择站点类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('1862','current_system','当前系统','0');
INSERT INTO {$tblprefix}alangs VALUES ('1863','addbackmenite','添加后台菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1864','choose_db_src','选择外部数据源','0');
INSERT INTO {$tblprefix}alangs VALUES ('1865','create_query_string','生成查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('1866','asc','升序','0');
INSERT INTO {$tblprefix}alangs VALUES ('1867','desc','降序','0');
INSERT INTO {$tblprefix}alangs VALUES ('1868','query_mode','查询模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1869','menuitem_k1','菜单项目_%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('1870','value','值','0');
INSERT INTO {$tblprefix}alangs VALUES ('1871','query_str_result','查询字串生成结果','0');
INSERT INTO {$tblprefix}alangs VALUES ('1872','query_string','查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('1873','normal_editor','常规编辑器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1874','menutype_k0','菜单类型_%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('1875','logoutadmin','退出管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1876','adminindex','管理首页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1877','membercenter1','会员中心','0');
INSERT INTO {$tblprefix}alangs VALUES ('1878','subsiteindex','子站首页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1879','websiteindex','网站首页','0');
INSERT INTO {$tblprefix}alangs VALUES ('1880','backarea','后台','0');
INSERT INTO {$tblprefix}alangs VALUES ('1881','founder','创始人','0');
INSERT INTO {$tblprefix}alangs VALUES ('1882','simple_editor','简易编辑器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1883','memomfiman','会员交互字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1884','value_length','输入值字节长度限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1885','detmocomefi','详细修改交互信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1886','addmecomefi','添加会员交互信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1887','edmecomefimali','编辑会员交互信息字段管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1888','mesfiman','信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1889','table_fieldlength','数据表字段长度','0');
INSERT INTO {$tblprefix}alangs VALUES ('1890','memberreport','会员举报','0');
INSERT INTO {$tblprefix}alangs VALUES ('1891','memberreply','会员回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('1892','agtlength','设定范围1-255','0');
INSERT INTO {$tblprefix}alangs VALUES ('1893','membercomment','会员评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('1894','spaceflink','空间友情链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1895','agmselectsplit','多个默认值以[##] (方括号中加##) 隔开','0');
INSERT INTO {$tblprefix}alangs VALUES ('1896','upanddownset','上传与下载设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1897','mchoise_list','多选列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1898','mail_sign','测试邮件签名','0');
INSERT INTO {$tblprefix}alangs VALUES ('1899','mchoisebox','多选框','0');
INSERT INTO {$tblprefix}alangs VALUES ('1900','mail_to','测试邮件收信地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1901','mail_silent','屏蔽邮件发送的出错信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1902','mail_delimiter','邮件头的分隔符','0');
INSERT INTO {$tblprefix}alangs VALUES ('1903','choose_content_set','选择内容设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1904','mail_pwd','SMTP 身份验证密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1905','mail_user','SMTP 身份验证帐户','0');
INSERT INTO {$tblprefix}alangs VALUES ('1906','mail_from','发信人邮件地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('1907','mail_auth','SMTP 要求身份验证','0');
INSERT INTO {$tblprefix}alangs VALUES ('1908','mail_port','SMTP 端口','0');
INSERT INTO {$tblprefix}alangs VALUES ('1909','mail_smtp','SMTP 服务器','0');
INSERT INTO {$tblprefix}alangs VALUES ('1910','normal_size1','常规尺寸','0');
INSERT INTO {$tblprefix}alangs VALUES ('1911','mailtest','邮件测试','0');
INSERT INTO {$tblprefix}alangs VALUES ('1912','mailset','邮件设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1913','enlarge_size1','加大尺寸','0');
INSERT INTO {$tblprefix}alangs VALUES ('1914','subsiteset','子站设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1915','siteset','站点设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1916','hostname','整站名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1917','hosturl','整站URL','0');
INSERT INTO {$tblprefix}alangs VALUES ('1918','cmsname','当前主站名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1919','cmsurl','当前主站URL','0');
INSERT INTO {$tblprefix}alangs VALUES ('1920','cmslogo','主站Logo','0');
INSERT INTO {$tblprefix}alangs VALUES ('1921','sitetitle','主站标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('1922','sitekeyword','主站关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('1923','sitedescrip','主站描述','0');
INSERT INTO {$tblprefix}alangs VALUES ('1924','siteicpno','网站ICP备案','0');
INSERT INTO {$tblprefix}alangs VALUES ('1925','bazscert','备案证书bazs.cert文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('1926','copyrightmessage','版权信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1928','subsitetitle','子站标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('1929','subkeyword','子站关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('1930','subsitedescrip','子站描述','0');
INSERT INTO {$tblprefix}alangs VALUES ('1931','websiteset','网站设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1932','sitemessaadmi','站点信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('1933','timerelated','时间相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('1934','sitetimez','站点时区','0');
INSERT INTO {$tblprefix}alangs VALUES ('1935','contentrelat','内容相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('1936','msg_orders_list','信息订单列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1937','issueupdateche','启用文档更新申请机制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1938','purchase_days','购买天数','0');
INSERT INTO {$tblprefix}alangs VALUES ('1939','issueupdatecopy','启用文档更新内容审核机制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1940','purchase_currency','购买积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('1941','msg_content','信息内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('1942','arcautcreathu','文档自动生成缩略图','0');
INSERT INTO {$tblprefix}alangs VALUES ('1943','orders_date','订单日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('1944','arcautcreabs','文档自动生成摘要','0');
INSERT INTO {$tblprefix}alangs VALUES ('1945','arcautbstlen','文档自动摘要长度','0');
INSERT INTO {$tblprefix}alangs VALUES ('1946','nohtml','清除内容中的Html代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('1947','stathotkey','统计热门关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('1948','cotypem_manager','类别体系管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1949','enablerss','启用RSS','0');
INSERT INTO {$tblprefix}alangs VALUES ('1950','rss_ttl','RSS刷新周期(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1951','schoise_list','单选列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1952','nousersearch','允许游客搜索','0');
INSERT INTO {$tblprefix}alangs VALUES ('1953','cotype_name','类系名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('1954','schoise_box','单选框','0');
INSERT INTO {$tblprefix}alangs VALUES ('1955','seamaxreamomembcen','搜索最大结果数量(会员中心)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1956','seatiintlimsec','搜索时间间隔限制(秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1957','format_limited','输入格式限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('1958','websitstat','网站统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('1959','enabelstat','启用网站统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('1960','add_cotypem','添加类别体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('1961','clickscachetime','流量统计的缓存更新周期(秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1962','mclickscircle','用户点击的更新频率(次)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1963','context_choose','上下篇选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('1964','is_self_reg','是否自动归类','0');
INSERT INTO {$tblprefix}alangs VALUES ('1965','mactivetime','活动时间更新周期(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1966','onlinehold','用户在线保持时间(秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('3113','inurl_admin','后台内链管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1968','cnodestatcir','类目统计更新周期(小时)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1969','edit_cotype_mlist','编辑类系管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1970','purchamount','购买数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('1971','vmode0','普通选择列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('1972','vmode1','单选按钮','0');
INSERT INTO {$tblprefix}alangs VALUES ('1973','mailmode3','PHP的SMTP功能(仅用于Windows主机,不支持身份验证)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1974','is_admin_self','是否管理专用','0');
INSERT INTO {$tblprefix}alangs VALUES ('1975','mailmode2','SOCKET 连接SMTP服务器(支持身份验证)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1976','is_notblank_catas','是否必选类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('1977','mailmode1','PHP的mail函数功能','0');
INSERT INTO {$tblprefix}alangs VALUES ('1978','filter0_set','基本筛选设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1979','cnode_leaguer_cotype','节点成员类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('1980','emaisenmod','Email发送方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1981','emaiset','Email设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('1983','maildelimiter3','CR (Mac 主机)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1984','maildelimiter2','LF (Unix/Linux 主机)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1985','maildelimiter1','CRLF (Windows 主机)','0');
INSERT INTO {$tblprefix}alangs VALUES ('1986','below_charc_forbid_use','以下模型文档禁止使用','0');
INSERT INTO {$tblprefix}alangs VALUES ('1987','attr','属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('1988','below_type_album_forbid_use','以下类型合辑禁止使用','0');
INSERT INTO {$tblprefix}alangs VALUES ('1989','class_choose_list_mode','分类选择列表模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('1990','det_modify_cotype','详细修改类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('1991','agtagdetail_yes','仅当模型限制为单个模型时有效。较耗资源','0');
INSERT INTO {$tblprefix}alangs VALUES ('1992','ftpcheck','检测FTP','0');
INSERT INTO {$tblprefix}alangs VALUES ('1993','add_cotype_msg_field','添加类系信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('1994','ftp_url','WEB访问URL','0');
INSERT INTO {$tblprefix}alangs VALUES ('1995','class_msg_field_manager','分类信息字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('1996','ftp_dir','远程附件目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('3105','traceurl','追溯网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('3106','ga_result','采集结果','0');
INSERT INTO {$tblprefix}alangs VALUES ('1998','ftp_ssl','是否启用SSL安全连接','0');
INSERT INTO {$tblprefix}alangs VALUES ('1999','det_modify_cotype_msg_field','详细修改类系信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2000','ftp_pasv','是否使用被动模式(pasv)上传','0');
INSERT INTO {$tblprefix}alangs VALUES ('2001','ftp_timeout','FTP 传输超时时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('2002','self_reg','自动归类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2003','inalbum_order_asc','辑内排序升序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2004','cnode_leaguer','节点成员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2005','ftp_password','FTP 密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2007','clicks_desc1','点击数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2008','ftp_user','FTP 帐号','0');
INSERT INTO {$tblprefix}alangs VALUES ('2009','add_cotype','添加类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2010','ftp_port','FTP 服务器端口','0');
INSERT INTO {$tblprefix}alangs VALUES ('2011','ftp_host','FTP 服务器地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('2012','cotypem_detail_edit','类别体系详细编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2013','del_cotype','删除类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2014','enaatftpupl','启用附件FTP上传','0');
INSERT INTO {$tblprefix}alangs VALUES ('2015','add_class_msg_field','添加分类信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2016','rematftpset','远程附件FTP设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2017','edit_cotype_msg_field','编辑类系信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2018','watermarkquality','JPEG图片水印后质量','0');
INSERT INTO {$tblprefix}alangs VALUES ('2019','watermarktrans','图片水印融合度','0');
INSERT INTO {$tblprefix}alangs VALUES ('2020','comments_desc1','评论数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2021','imawattyp','图片水印类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2022','add_gather_model','添加采集模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2023','average_score_desc1','平均评分↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2024','rightbottom','右下','0');
INSERT INTO {$tblprefix}alangs VALUES ('2025','gather_model_name','采集模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2026','centerbottom','中下','0');
INSERT INTO {$tblprefix}alangs VALUES ('2027','favorite_pics_desc1','收藏次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2028','leftbottom','左下','0');
INSERT INTO {$tblprefix}alangs VALUES ('2029','arc_model_choose','请指定采集的文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2030','rightcenter','右中','0');
INSERT INTO {$tblprefix}alangs VALUES ('2031','altype_choose','合辑类型(采集合辑请选择)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2032','praise_pics_desc1','顶次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2033','leftcenter','左中','0');
INSERT INTO {$tblprefix}alangs VALUES ('2034','debase_pics_desc1','踩次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2035','righttop','右上','0');
INSERT INTO {$tblprefix}alangs VALUES ('2036','gather_model_manager','采集模型管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2037','centertop','中上','0');
INSERT INTO {$tblprefix}alangs VALUES ('2038','lefttop','左上','0');
INSERT INTO {$tblprefix}alangs VALUES ('2039','notaddwater','不添加水印','0');
INSERT INTO {$tblprefix}alangs VALUES ('2040','imawateset','图片水印设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2041','edit_gat_model_mlist','编辑采集模型管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2042','gather_field_set','采集字段设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2043','attbroperset','附件浏览器权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2044','allnouupl','游客允许上传','0');
INSERT INTO {$tblprefix}alangs VALUES ('2045','det_modify_gather_model','详细修改采集模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2046','player_height','默认媒体播放高度','0');
INSERT INTO {$tblprefix}alangs VALUES ('2047','player_width','默认媒体播放宽度','0');
INSERT INTO {$tblprefix}alangs VALUES ('2048','path_userfile','附件分类保存','0');
INSERT INTO {$tblprefix}alangs VALUES ('2049','onlylink','纯链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('2050','attacsmal','附件绑定域名','0');
INSERT INTO {$tblprefix}alangs VALUES ('2051','dir_userfile','附件路径(相对系统根目录)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2052','orders_amount_desc1','订单数量↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2053','user_handwork','用户手动','0');
INSERT INTO {$tblprefix}alangs VALUES ('2054','uplattaset','上传附件设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2055','download_pics_desc1','下载次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2056','admin_handwork','管理手动','0');
INSERT INTO {$tblprefix}alangs VALUES ('2057','uplimaaddwate','上传图片添加水印','0');
INSERT INTO {$tblprefix}alangs VALUES ('2058','crbase','积分基数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2059','crex','积分兑换','0');
INSERT INTO {$tblprefix}alangs VALUES ('2060','onlyadmini','仅管理员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2061','play_pics_desc1','播放次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2062','allmember','全部会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2063','edit_grouptype','编辑会员组体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2064','alluser','全部用户','0');
INSERT INTO {$tblprefix}alangs VALUES ('2065','imagewaterm','图片水印','0');
INSERT INTO {$tblprefix}alangs VALUES ('2066','grouptype_name','组系名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2067','answer_reward_desc1','答疑悬赏↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2068','date','日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2069','deal_mode','处理模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2070','related_currency','相关积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2071','month_clicks_desc1','月点击数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2072','month','月','0');
INSERT INTO {$tblprefix}alangs VALUES ('2073','add_grouptype','添加会员组体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2074','related_cutype','相关积分类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2075','edit_grouptype_mlist','编辑会员组体系管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2076','usergroup_alter_reset','会员组变更需要重设限额','0');
INSERT INTO {$tblprefix}alangs VALUES ('2077','inchids_forbid_use','在以下模型中禁止使用','0');
INSERT INTO {$tblprefix}alangs VALUES ('2078','detail_modify_grouptype','详情修改会员组体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2079','websbuspayset','网站商务支付设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2080','week_clicks_desc1','周点击数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2081','msite_backarea','主站后台','0');
INSERT INTO {$tblprefix}alangs VALUES ('2082','month_comments_desc1','月评论数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2083','deducthandfee','扣除手续费(%)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2084','paykey','支付密钥','0');
INSERT INTO {$tblprefix}alangs VALUES ('2085','partnerid','商户编号','0');
INSERT INTO {$tblprefix}alangs VALUES ('2086','week_comments_desc1','周评论数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2087','resultreceiveurl','结果接收URL','0');
INSERT INTO {$tblprefix}alangs VALUES ('2088','datasendurl','资料发送URL','0');
INSERT INTO {$tblprefix}alangs VALUES ('2089','paycname','支付名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2090','m_fav_pics_desc1','月收藏次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2091','w_fav_pics_desc1','周收藏次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2092','onlpayset','%s-在线支付设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2093','class_condition_set','分类条件设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2094','cartgooamolim','购物车商品数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2095','enagoostosta','启用商品库存统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('2096','choose_list_tag_type','选择列表标识类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2097','m_praise_pics_desc1','月顶次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2098','enagoshfest','启用商品送货费用统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('2099','onlpayarrautsav','在线支付到帐自动充值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2100','busrelbasset','商务相关基本设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2101','w_praise_pics_desc1','周顶次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2102','webpptpptset','网站pptput反向通行证设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2103','filter_string','筛选字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2104','m_debase_pics_desc1','月踩次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2105','order_string','排序字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2106','ct_listtag_querystring','生成列表标识查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2107','uc_key','UCenter 通信密钥','0');
INSERT INTO {$tblprefix}alangs VALUES ('2108','list','列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2109','table','数据表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2110','uc_appid','UCenter 分配的应用ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('2111','uc_dbpre','UCenter 数据库表前缀','0');
INSERT INTO {$tblprefix}alangs VALUES ('2112','edit_local_upload_prj','编辑本地上传方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2113','modify_filetype','修改文件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2114','local_upload_prj','本地上传方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2115','uc_dbpwd','UCenter 数据库密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2116','all_att_type','全部附件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2117','allow_upload_type','允许本地上传的类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2118','uc_dbuser','UCenter 数据库用户名','0');
INSERT INTO {$tblprefix}alangs VALUES ('2119','uc_dbname','UCenter 数据库名','0');
INSERT INTO {$tblprefix}alangs VALUES ('2120','local_upload_filetype','本地上传文件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2121','uc_dbhost','UCenter 数据库主机名','0');
INSERT INTO {$tblprefix}alangs VALUES ('2122','add_file_type','添加文件类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2123','uc_ip','UCenter 主机IP，通常留空。<br>\r\n因域名解析而通信失败时设置该值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2124','file_type_input','文件类型(输入扩展名,逗号分隔多个内容)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2125','uc_api','UCenter API 地址，末尾不需斜杆','0');
INSERT INTO {$tblprefix}alangs VALUES ('2126','file_ext','文件扩展名','0');
INSERT INTO {$tblprefix}alangs VALUES ('2127','enableucent','启用UCenter','0');
INSERT INTO {$tblprefix}alangs VALUES ('2128','w_debase_pics_desc1','周踩次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2129','allow_local_upload','允许本地上传','0');
INSERT INTO {$tblprefix}alangs VALUES ('2130','uc_clientconfig','UCenter 客户端配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2131','max_up_limit_k','最大上传限制(K)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2132','pptin_logout','接口程序退出地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('2133','min_up_limit_k','最小上传限制(K)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2134','pptin_login','接口程序登录地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('2135','pptin_register','接口程序注册地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('2136','m_orders_amount_desc1','月订单数量↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2137','pptin_url','接口程序URL地址','0');
INSERT INTO {$tblprefix}alangs VALUES ('2138','altypeid','类型ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('2139','pptin_expire','验证字串有效期(秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2140','w_orders_amount_desc1','周订单数量↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2144','m_download_pics_desc1','月下载次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2146','typename','类型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2147','pptout_charset','接口程序字符集','0');
INSERT INTO {$tblprefix}alangs VALUES ('2148','pptout_file','接口程序名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2151','add_marc_type','添加会员档案类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2152','pagandtemset','页面与模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2153','matype_name','会员档案类型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2154','w_download_pics_desc1','周下载次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2155','edit_matype_list','编辑会员档案类型列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2156','add_arc_autocheck','添加的档案自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2157','m_play_pics_desc1','月播放次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2158','subindtem','子站模板目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2159','add_arc_autostatic','添加的档案自动静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2160','enmemcenmefiuse','是否根据会员组权限过滤菜单','0');
INSERT INTO {$tblprefix}alangs VALUES ('2161','w_play_pics_desc1','周播放次数↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2162','floathei','浮动窗口高度（px）','0');
INSERT INTO {$tblprefix}alangs VALUES ('2163','allow_update_checked_arc','会员可以更新已审档案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2164','floatwinwidth','浮动窗口宽度（px）','0');
INSERT INTO {$tblprefix}alangs VALUES ('2165','enablefloatwin','启用浮动窗口','0');
INSERT INTO {$tblprefix}alangs VALUES ('2166','memcenterlogo','会员中心LOGO','0');
INSERT INTO {$tblprefix}alangs VALUES ('2167','content_open_tpl','内容公开页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2168','first_order','第一排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2169','content_limit_tpl','内容限制页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2170','uclbytlenlim','个人分类字节长度限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2171','second0_order','第二排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2172','add_tpl','添加模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2173','uclmaxamolim','个人分类最大数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2174','read_permi_set','阅读权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2175','det_set_matype','详细设置会员档案类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2176','add_matype_field','添加会员档案字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2177','mrowpp','会员中心列表每页显示数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2178','clicks_gt','点击数>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2179','del_matype','删除会员档案类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2180','memcenmsgfor','会员中心提示信息停留(毫秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2181','comments_gt','评论数>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2182','memcentrelaset','会员中心参数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2183','matype_set','会员档案类型设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2184','favorite_pics_gt','收藏次数>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2185','catahidden','类目选择列表隐藏不可选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2186','praise_pics_gt','顶次数>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2187','edit_matype_field','编辑会员档案字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2188','catacholismod','栏目选择时列表方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2189','cnprow','节点配置界面每行显示数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2190','debase_pics_gt','踩次数>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2191','goods_orders_amount_gt','商品订单数量>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2192','admbackamsgforw','管理后台提示信息停留(毫秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2193','atpp','管理后台列表每页显示数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2194','admbacrelset','管理后台参数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2195','goods_price_le','商品价格<=','0');
INSERT INTO {$tblprefix}alangs VALUES ('2196','goods_price_gt','商品价格>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2197','member_channel_name','会员模型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2198','answer0_amount_gt','答案数量>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2199','htmltext_channel','HtmlText字段转为文本储存 - %s模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2200','transto_txt_field','数据表字段开始转为文本储存字段!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2201','transto_table_field','文本储存字段开始转为数据表字段!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2202','add_member_channel','添加会员模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2203','agmclogo','最佳尺寸 260 X 50','0');
INSERT INTO {$tblprefix}alangs VALUES ('2204','adopt_answer0_amount_gt','采用答案数量>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2205','clearoldcache','清理过期页面缓存周期(小时)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2206','answer_reward_currency_le','答疑悬赏积分<=','0');
INSERT INTO {$tblprefix}alangs VALUES ('2207','cachejscircle','js数据缓存更新周期(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2208','mslistcachenum','个人空间列表页缓存页数限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2209','cachemscircle','个人空间缓存更新周期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2210','answer_reward_currency_gt','答疑悬赏积分>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2211','listcachenum','列表页缓存页数限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2212','cache1circle','动态页缓存更新周期(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2213','edit_member_channel_list','编辑会员模型列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2214','cacherelaset','缓存相关设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2215','rewritephp','.php?的Rewrite对应字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2216','dynamipage','动态页面','0');
INSERT INTO {$tblprefix}alangs VALUES ('2217','liststaticnum','多页码静态时只生成前几页静态','1271819868');
INSERT INTO {$tblprefix}alangs VALUES ('2218','virtuastatic','虚拟静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2219','contpagestaticci','内容页静态更新周期(分钟)','1271820053');
INSERT INTO {$tblprefix}alangs VALUES ('2220','calispagestati','类目列表页被动静态周期(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2221','catindestaticc','类目首页被动静态周期(分钟)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2222','infohtmldir','插件信息及独立页静态目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2223','archtmlmode','文本字段内容保存目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2224','cnhtmldir','主站节点及文档静态总目录','1271821679');
INSERT INTO {$tblprefix}alangs VALUES ('2225','only_stat_validperiod_archive','仅统计有效期内文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('3307','homedefault','站点首页静态文件名','1271819529');
INSERT INTO {$tblprefix}alangs VALUES ('2227','space_related_setting','个人空间相关设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2228','weaenasta','是否启用静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2229','starelset','静态相关设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2230','agjsrefsource','输入允许的来路域名，留空则开放所有来路。每个域名一行，不支持通配符，请勿包含 http:// 或其它非域名内容，','1274283541');
INSERT INTO {$tblprefix}alangs VALUES ('2231','jsrefsource','js动态调用只允许以下来路','1274283237');
INSERT INTO {$tblprefix}alangs VALUES ('2232','tepawedest','模板解析设为调试状态','1264130825');
INSERT INTO {$tblprefix}alangs VALUES ('2233','temjsdir','模板js目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2234','temcssdir','模板css目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2235','templatedir','模板目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2236','commsgforwordtime','前台提示信息停留(毫秒)','1270769548');
INSERT INTO {$tblprefix}alangs VALUES ('2237','timeformat','默认时间格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2238','dateformat','默认日期格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2239','gzipenable','页面Gzip压缩','0');
INSERT INTO {$tblprefix}alangs VALUES ('2240','agrewritephp','如设置为-htm-，则虚拟静态url 如archive.php?aid-5.htm，将封装为archive-htm-aid-5.htm。设置在url虚拟静态开启时有效，请保持与站点rewrite规则相对应。','0');
INSERT INTO {$tblprefix}alangs VALUES ('2241','agcss_dir','只需要添写目录名','0');
INSERT INTO {$tblprefix}alangs VALUES ('2242','eg','例：','0');
INSERT INTO {$tblprefix}alangs VALUES ('2243','weather_space_archive_list','是否个人空间文档列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2244','yearmonthday','年月日','0');
INSERT INTO {$tblprefix}alangs VALUES ('2245','yearmonth','年月','0');
INSERT INTO {$tblprefix}alangs VALUES ('2246','pagebasedset','页面设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2247','visiandregset','访问与注册设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2248','enableregco','启用验证码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2249','cashpay','现金支付','0');
INSERT INTO {$tblprefix}alangs VALUES ('2250','addpickbug','添加举报','0');
INSERT INTO {$tblprefix}alangs VALUES ('2251','member_channel_copy','会员模型复制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2252','addoffer','添加报价','0');
INSERT INTO {$tblprefix}alangs VALUES ('2253','backalogin','后台登录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2254','memblogin','会员登录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2255','memberregis','会员注册','0');
INSERT INTO {$tblprefix}alangs VALUES ('2256','regcode_height','验证码图片高度(像素)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2257','more_filter0_order_set','更多筛选与排序设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2258','regcode_width','验证码图片宽度(像素)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2259','regcodeset','验证码设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2260','censoruser','用户名称保留字','0');
INSERT INTO {$tblprefix}alangs VALUES ('2261','regclosereason','注册关闭原因','0');
INSERT INTO {$tblprefix}alangs VALUES ('2262','siteclosereg','站点关闭注册','0');
INSERT INTO {$tblprefix}alangs VALUES ('2263','reg_member_check_mode','注册会员审核方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2264','memregset','会员注册设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2265','urlmode','设置首序类系','1272769861');
INSERT INTO {$tblprefix}alangs VALUES ('2266','member_comment_commu_set','会员评论交互设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2267','sid_self','子站后台只能管理子站项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2268','spaceclose','个人空间关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('2269','list_item_setting','列表项目设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2270','adminipaccess','管理后台允许IP列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2271','siteclosereason','站点关闭原因','0');
INSERT INTO {$tblprefix}alangs VALUES ('2272','siteclose','站点关闭','0');
INSERT INTO {$tblprefix}alangs VALUES ('2273','current_subsite','当前子站','0');
INSERT INTO {$tblprefix}alangs VALUES ('2274','webvisiset','注册访问','0');
INSERT INTO {$tblprefix}alangs VALUES ('2275','member_reply_commu_set','会员回复交互设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2276','websibaseset','网站基本设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2277','search_member_tpl','搜索会员模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2278','subsite_attr','子站属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('2279','handwork_check','手动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2280','inausttiminterh','辑内合计统计时间间隔(小时)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2281','wanweestatitem','需要周统计的项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2282','wanmonstatitem','需要月统计的项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2283','auto_check','自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2284','all_topic_catas','全部顶级类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2285','replpic','回复次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2286','mail_active','Email激活','0');
INSERT INTO {$tblprefix}alangs VALUES ('2287','sonofactive','激活类目的下级类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2288','offerpics','报价次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2289','not_relate','不关联','0');
INSERT INTO {$tblprefix}alangs VALUES ('2290','sameofactive','激活类目的平级类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2291','user_choose_and_modify','用户选择与修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('2292','list_item','作为列表项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2293','playpics','播放次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2294','user_choose_admin_modify','用户选择 管理员修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('2295','downlopic','下载次数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2296','catas_attr','类目属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('2297','admin_choose_modify','管理员选择与修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('2298','addanswer','添加答案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2299','member_relate_catas_attr','会员关联类目属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('2300','nolist_item_available','非列表项目有效','0');
INSERT INTO {$tblprefix}alangs VALUES ('2301','member_relate_catalog','关联栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2302','norelated','非关联项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2303','freeinfo','插件信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2304','active_catalog','激活栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2305','member_channel_set','会员模型设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2306','agcensor','用户名不能使用列表中的关键词，每行填写一个关键词，允许使用通配符 *','0');
INSERT INTO {$tblprefix}alangs VALUES ('2307','agipaccess','每行输入一个 IP，可为完整地址，也可是 IP 开头某几个字符，空表示不限制登录者IP','0');
INSERT INTO {$tblprefix}alangs VALUES ('2308','member_relate_cotype','关联类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2309','purcallam','购买总额','0');
INSERT INTO {$tblprefix}alangs VALUES ('2310','list_channel','列表模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2311','point_cotypem','指定类别体系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2312','directid2','指定类目id(空缺指激活类目)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2313','agcmsurl','结尾需含 /','0');
INSERT INTO {$tblprefix}alangs VALUES ('2314','det_modify_mchannel','详细修改会员模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2315','not_trace','不追溯','0');
INSERT INTO {$tblprefix}alangs VALUES ('2316','aghosturl','应含http，结尾勿含 /','0');
INSERT INTO {$tblprefix}alangs VALUES ('2317','add_member_msg_field','添加会员信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2318','del_mchannel','删除会员模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2319','level1','一级','0');
INSERT INTO {$tblprefix}alangs VALUES ('2320','addflink','添加友情链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('2321','addfriend','添加好友','0');
INSERT INTO {$tblprefix}alangs VALUES ('2322','poimemoffavo','指定会员的收藏','0');
INSERT INTO {$tblprefix}alangs VALUES ('2323','catas_upcata','追溯指定类目的上级类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2324','det_modify_mch_msg_field','详细修改会员模型信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2325','add_member_cfield','添加会员通用字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2326','poimembscooper','指定会员的评分操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('2327','list_upcata','追溯指定列表项目的上级类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2328','add_member_cmsg_field','添加会员通用信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2329','recrepallrep','收到的回复可以答复','0');
INSERT INTO {$tblprefix}alangs VALUES ('2330','member_cfield_manager','会员通用字段管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2331','active_archive','激活文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('2332','recrepallche','收到的回复可以审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2333','active_member','激活会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2334','recerepalldel','收到的回复可以删除','0');
INSERT INTO {$tblprefix}alangs VALUES ('2335','emcmsg_field_mlist','编辑会员通用信息字段管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2336','reccomallrep','收到的评论可以答复','0');
INSERT INTO {$tblprefix}alangs VALUES ('2337','reccoalche','收到的评论可以审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2338','point_commu_item','指定交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2339','relate_id_source','关联ID来源','0');
INSERT INTO {$tblprefix}alangs VALUES ('2340','reccomalldel','收到的评论可以删除','0');
INSERT INTO {$tblprefix}alangs VALUES ('2341','det_modify_mcmsg_field','详细修改会员通用信息字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2342','aguclass','当关联ID来源为会员时有效','0');
INSERT INTO {$tblprefix}alangs VALUES ('2343','friautche','好友自动审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2344','frimaxamo','好友最大数量','0');
INSERT INTO {$tblprefix}alangs VALUES ('2345','demomecomit','详细修改会员交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2346','memcomitset','会员交互项目设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2347','add_time_asc','添加时间升序排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2348','copymcomitem','复制会员中心交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2349','add_subsite_menu_class','添加子站菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2350','add_msite_menu_class','添加主站菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2351','memcomitco','会员交互项目复制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2352','limitin_current_channel','限于当前模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2353','edmemcomitmanli','编辑会员交互项目管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2354','limitin_current_catalog','限于当前栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2355','add_subsite_menu_item','添加子站菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2356','memcomitad','会员交互项目管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2357','add_msite_menu_item','添加主站菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2358','limitin_current_coclass','限于当前分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2359','limitin_active_member','限于激活会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2360','flink','友情链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('2361','friend','好友','0');
INSERT INTO {$tblprefix}alangs VALUES ('2362','subsite_ba_menu_manager','子站后台菜单管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2363','point_msg_id','指定信息ID(0-激活信息)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2364','msite_ba_menu_manager','主站后台菜单管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2365','msg_order','信息排序升序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2366','edit_subsite_menu_class','编辑子站菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2367','referror','参考错误','0');
INSERT INTO {$tblprefix}alangs VALUES ('2368','edit_msite_menu_class','编辑主站菜单分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2369','msg_order_desc','信息排序降序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2370','tagmodify','标识修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('2371','add_time_desc','添加时间降序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2372','edit_subsite_menu_item','编辑子站菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2373','edit_msite_menu_item','编辑主站菜单项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2374','detmosptemp','详细修改特定功能模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2375','templatecontent','模板内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2376','pagecname','页面名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2377','ctag','复合标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2378','sptemset','特定功能模板设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2379','utag','特殊字段标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2380','rtag','区块替代标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2381','edsptemmanli','编辑特定功能模板管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2382','ptag','分页标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2383','sppagemana','特定功能页面管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2384','ctag_admin','复合标识管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2385','utag_admin','特殊字段标识管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2386','add_time_asc1','添加时间升序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2387','rtag_admin','区块替代标识管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2388','ptag_admin','分页标识管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2389','tag_style','标识样式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2390','listby','排列顺序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2391','edit_ctag_mlist','编辑复合标识管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2392','edit_utag_mlist','编辑特殊字段标识管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2393','edit_rtag_mlist','编辑区块替代标识管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2394','point_isolute_page0_id','指定独立页ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('2395','edit_ptag_mlist','编辑分页标识管理列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2396','copy_ctag','复制复合标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2397','copy_utag','复制特殊字段标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2398','copy_rtag','复制区块替代标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2399','functionscode','列表内容来自以下PHP函数返回值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2400','copy_ptag','复制分页标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2401','agfunctionscode','返回值为列表内容数组，以原始标识形式调用指定内容项，当前页码为{$nowpage}','0');
INSERT INTO {$tblprefix}alangs VALUES ('2402','currencyinout','积分充/扣值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2403','otherreason','其它原因','0');
INSERT INTO {$tblprefix}alangs VALUES ('2404','setting_list_item','设置列表项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2405','lookinittag','查看原始标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2406','all_space0_catalog','全部空间栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2407','lookttype','查看 %s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2408','lookselecttag','查看选中标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2409','plepoimemid','请指定会员ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('2410','notremote','不下载远程文件','0');
INSERT INTO {$tblprefix}alangs VALUES ('2411','netsilistpage','网址列表页','0');
INSERT INTO {$tblprefix}alangs VALUES ('2412','contensourcpage','内容来源页面','0');
INSERT INTO {$tblprefix}alangs VALUES ('2413','resultdealfunc','结果处理函数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2414','fiecontgathpatt','字段内容<br>采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('2415','space0catalog','空间栏目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2416','catalog_all_coclass','栏目内全部分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2417','replmesssouront','替换信息<br> 来源内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2418','repmessagresulcont','替换信息<br>\r\n=>结果内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2419','lisregigathpatt','列表区域<br>采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('2420','liscellsplitag','列表单元\r\n分隔标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2421','cellurlgathpatte','单元链接<br>采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('2422','celltitlgathepatt','单元标题<br>采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('2423','downjumfilsty','下载跳转文件样式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2424','titleunknown','标题不详','0');
INSERT INTO {$tblprefix}alangs VALUES ('2425','confchoosarchi','请指定正确的文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('2426','poinarchnoch','指定的文档未审','0');
INSERT INTO {$tblprefix}alangs VALUES ('2427','noarchivbrowpermis','无文档浏览权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('2428','subattachwanpaycur','订阅此附件需要支付积分 : &nbsp;:&nbsp;','0');
INSERT INTO {$tblprefix}alangs VALUES ('2429','younosuatwaencur','<br><br>您没有订阅此附件所需要的足够积分!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2430','subsattach','订阅附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('2431','saleattach','出售附件','0');
INSERT INTO {$tblprefix}alangs VALUES ('2432','nolimitformat','不限格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2433','number','数字','0');
INSERT INTO {$tblprefix}alangs VALUES ('2434','letter','字母','0');
INSERT INTO {$tblprefix}alangs VALUES ('2435','numberletter','字母与数字','0');
INSERT INTO {$tblprefix}alangs VALUES ('2436','advancedmes','进阶信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2437','attachmentedit','附件编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2438','uclass','个人分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2439','lengsmalmilim','长度小于最小限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2440','lenglargmaxlimi','长度大于最大限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2441','smallminilimi','小于最小限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2442','largmaxlimi','大于最大限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2443','attatamosmaminili','附件数量小于最小限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2444','notnull','不能为空','0');
INSERT INTO {$tblprefix}alangs VALUES ('2445','liminpda','限输入日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2446','liminpint','限输入整数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2447','receive_member','接收会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2448','liminpnum','限输入数字','0');
INSERT INTO {$tblprefix}alangs VALUES ('2449','limiinputlett','限输入字母','0');
INSERT INTO {$tblprefix}alangs VALUES ('2450','submit_member','提交会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2451','limitinputnumberl','限输入字母与数字','0');
INSERT INTO {$tblprefix}alangs VALUES ('2452','limitinputtagtype','限输入字母开头的_字母数字','0');
INSERT INTO {$tblprefix}alangs VALUES ('2453','limitinputemail','限输入Email','0');
INSERT INTO {$tblprefix}alangs VALUES ('2454','regcode','验证码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2455','member_channel_limited','会员模型限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2456','agregcode','请输入图片框中的字符','0');
INSERT INTO {$tblprefix}alangs VALUES ('2457','issutaxfree','发表收费插件信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2458','re_regcode','点击图片换一个验证码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2459','catas_relate_setting','类目关联设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2460','rewarcurrenval','悬赏积分值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2461','relate_catalog_limited','关联栏目限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2462','question','问题','0');
INSERT INTO {$tblprefix}alangs VALUES ('2463','guide','提示说明','0');
INSERT INTO {$tblprefix}alangs VALUES ('2464','clickhere','如果浏览器没有跳转请点这里','0');
INSERT INTO {$tblprefix}alangs VALUES ('2465','stock','库存','0');
INSERT INTO {$tblprefix}alangs VALUES ('2466','default_order','默认排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2467','register_time_desc1','注册时间↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2468','register_time_asc1','注册时间↑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2469','questcontnotn','问题内容不能为空','0');
INSERT INTO {$tblprefix}alangs VALUES ('2470','prompt_msg','提示信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2471','online_time_desc1','在线时间↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2472','msclicks_desc1','空间点击量↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2473','rewcurtychdomoarc','悬赏积分类型改变,不要修改文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('2474','dontredrewcur','不要减少悬赏积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2475','recusmmiva','悬赏积分小于最小值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2476','issue_archive_amount_desc1','发表文档数量↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2477','rightnowjump','立即跳转','0');
INSERT INTO {$tblprefix}alangs VALUES ('2478','purchase_amount_desc1','购买数量↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2479','rightnowgoback','立即返回','0');
INSERT INTO {$tblprefix}alangs VALUES ('2480','defaultclosedreason','网站正在维护，请稍后再连接。','0');
INSERT INTO {$tblprefix}alangs VALUES ('2481','answer_amount_desc1','答疑数量↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2482','arc_browse_fee','文档浏览费用','0');
INSERT INTO {$tblprefix}alangs VALUES ('2483','credit_desc1','信用↓','0');
INSERT INTO {$tblprefix}alangs VALUES ('2484','att_deal_fee','附件处理费用','0');
INSERT INTO {$tblprefix}alangs VALUES ('2485','fee_msg_type','费用信息类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('3157','membertpl','会员模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2487','list_amount_limit','列表数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2489','online_time','在线时间>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2490','awardcurrency','奖励积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2491','input_tag_tpl','请输入标识模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2492','msclicks1','空间点击量>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2493','cheforcon','请检查表单内容!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2494','issue_archive_amount','发表文档数量>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2495','usource','指定内容来源','1264056469');
INSERT INTO {$tblprefix}alangs VALUES ('2496','date_view_format','日期显示格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2497','purchase_goods_amount','购买商品数量>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2498','time_view_format','时间显示格式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2499','answer_credit','答疑信用>','0');
INSERT INTO {$tblprefix}alangs VALUES ('2500','memcnameerror','会员名称错误','0');
INSERT INTO {$tblprefix}alangs VALUES ('2501','not_view_date','不显示日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2502','passerror','密码错误','0');
INSERT INTO {$tblprefix}alangs VALUES ('2503','not_view_time','不显示时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('2504','outregmemwanact','站外注册会员,需要激活!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2505','pick_setting','调用设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3221','tagdisabled','[本标识关闭中]','1264060037');
INSERT INTO {$tblprefix}alangs VALUES ('2507','oldpasserr','原密码错误','0');
INSERT INTO {$tblprefix}alangs VALUES ('2508','dbsource','外部数据源','0');
INSERT INTO {$tblprefix}alangs VALUES ('2509','mempassmodfai','会员密码修改失败','0');
INSERT INTO {$tblprefix}alangs VALUES ('2510','files_t','多点下载','0');
INSERT INTO {$tblprefix}alangs VALUES ('2511','look_configs','查看结构','0');
INSERT INTO {$tblprefix}alangs VALUES ('2512','file_t','独立点下载','0');
INSERT INTO {$tblprefix}alangs VALUES ('2513','usourcemode','来源内容模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('3220','sourceillegal','内容来源设置不合规范','1264056727');
INSERT INTO {$tblprefix}alangs VALUES ('2515','define_content_query_string','定义内容查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2516','clicks_desc','点击数降序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2517','ctrl_permission','参与设置内容权限方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2518','comments_desc','评论数降序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2519','filter0set','筛选设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2520','clearhtml','清除Html标签','0');
INSERT INTO {$tblprefix}alangs VALUES ('2521','ctrl_awardcp','参与设置文档发表奖励','0');
INSERT INTO {$tblprefix}alangs VALUES ('2522','nodeal','不作处理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2523','ctrl_taxcp','参与设置浏览文档扣除积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2524','disablehtml','仅显示Html标签','0');
INSERT INTO {$tblprefix}alangs VALUES ('2525','ctrl_ftaxcp','参与设置附件操作扣除积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2526','channel_limited','模型限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2527','safehtml','保护性过滤Html','0');
INSERT INTO {$tblprefix}alangs VALUES ('2528','ctrl_sale','参与控制作者出售文档权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('2529','ctrl_fsale','参与控制作者出售附件权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('2530','deal_html_code','处理Html代码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2531','searchtxt','查询字串(请通过 文档-文档搜索相关-设置 生成字串)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2532','txt_length_trim','文本长度剪裁','0');
INSERT INTO {$tblprefix}alangs VALUES ('2533','filter_badword','过滤不良词','0');
INSERT INTO {$tblprefix}alangs VALUES ('2534','ctrl_discount','参与设置商品折扣','0');
INSERT INTO {$tblprefix}alangs VALUES ('2535','deal_wordlink','处理关联链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('2536','all_site','全部站点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2537','temfilecna','模板文件名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2538','multitext_newline','多行文本换行','0');
INSERT INTO {$tblprefix}alangs VALUES ('2539','all_subsite','全部子站','0');
INSERT INTO {$tblprefix}alangs VALUES ('2540','add_randstr','添加混淆字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2541','browse_user','浏览用户','0');
INSERT INTO {$tblprefix}alangs VALUES ('2542','amount_limit','数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2543','active_user','激活用户','0');
INSERT INTO {$tblprefix}alangs VALUES ('2544','user_source','用户来源','0');
INSERT INTO {$tblprefix}alangs VALUES ('2545','pleinptatem','请输入标识模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2546','flashs_t','多集Flash','0');
INSERT INTO {$tblprefix}alangs VALUES ('2547','flash_t','独立Flash','0');
INSERT INTO {$tblprefix}alangs VALUES ('2548','point_voteid','指定投票ID(0-激活投票)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2549','ptnaviset','分页导航设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2550','vote_option_list','投票选项列表结果限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2551','nolimitsubsite','不限子站','0');
INSERT INTO {$tblprefix}alangs VALUES ('2552','vote_option_cols','投票选项分列数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2553','confirmcomitem','请指定正确的交互项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2554','vote_coclass_limited','投票分类限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2555','player_width_r','播放器宽度','0');
INSERT INTO {$tblprefix}alangs VALUES ('2556','vote_id_limited','投票ID限制(逗号分隔多个内容)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2557','choosemescoc','请指定正确的信息分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2558','player_height_r','播放器高度','0');
INSERT INTO {$tblprefix}alangs VALUES ('2559','functionsmpcode','列表项目总数来自以下PHP函数返回值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2560','agfunctionsmpcode','返回值为列表项目总数，用于生成分页导航','0');
INSERT INTO {$tblprefix}alangs VALUES ('2561','tagdatamiss','标识资料不完全','0');
INSERT INTO {$tblprefix}alangs VALUES ('2562','psource','分页内容来源','1264046716');
INSERT INTO {$tblprefix}alangs VALUES ('2563','imawidlim','图片宽度限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2564','att_page_type','附件页面类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2565','imaheilim','图片高度限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2566','createthumb','按设定尺寸生成缩略图','1264047951');
INSERT INTO {$tblprefix}alangs VALUES ('2567','emptyurl','补缺图片url','1264047870');
INSERT INTO {$tblprefix}alangs VALUES ('2568','emptytitle','补缺图片说明','1264047870');
INSERT INTO {$tblprefix}alangs VALUES ('2570','file_download','文件下载','0');
INSERT INTO {$tblprefix}alangs VALUES ('2571','media_play','视频播放','0');
INSERT INTO {$tblprefix}alangs VALUES ('2572','flash_play','Flash播放','0');
INSERT INTO {$tblprefix}alangs VALUES ('2573','mcontent','多集(点)内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2574','scontent','独立内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2575','inpquerstr','请输入查询字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2576','image_width_limit','图片宽度限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2577','image_height_limit','图片高度限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2578','medias_t','多集视频','0');
INSERT INTO {$tblprefix}alangs VALUES ('2579','media_t','独立视频','0');
INSERT INTO {$tblprefix}alangs VALUES ('2580','byte_len_trim','输入字节长度,如为空或0值表示不剪裁','0');
INSERT INTO {$tblprefix}alangs VALUES ('2581','point_altype','请指定合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2582','not_direct_album','不指向合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2583','direct_belong_album','指向所属合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2584','view_channel_option_msg','显示模型选项信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2585','page0id','请指定独立页ID','0');
INSERT INTO {$tblprefix}alangs VALUES ('2586','add_flink_tpl','添加友情链接模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2587','inalbum_sum_item','辑内合计统计的项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2588','channel_add_member','按模型添加会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2589','base_option','基本选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2590','password','密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2591','member_relate_class','关联分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2592','space_tpl_prj','个人空间模板方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2593','usergroup_msg','会员组信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2594','add_member','添加会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2595','modify_pwd','修改密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('2596','release_usergroup','解除会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('2597','noend','无限期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2598','notbelong_usergroup','不属于会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('2599','issue_allowance_manager','发表限额管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2600','aw_arc_issue_limit','限额文档发表数量限制','0');
INSERT INTO {$tblprefix}alangs VALUES ('2601','aw_commu_issue_limit','限额交互发表数量限制/月','0');
INSERT INTO {$tblprefix}alangs VALUES ('2602','detail_edit_member','详细编辑会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2603','nocheck_member','未审会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2604','checked_member','已审会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2605','filter_normal_member','筛选普通会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2606','filter_admin_member','筛选管理会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2607','register_date','注册日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2608','reg_date','注册日期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2609','recentvisit','最近访问','0');
INSERT INTO {$tblprefix}alangs VALUES ('2610','del_member','删除会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2611','member_admin','会员管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2612','member_list_admin','会员列表管理操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('2613','addinpointalbum','在指定合辑内添加内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('3447','df_tpl','默认模板','1272185617');
INSERT INTO {$tblprefix}alangs VALUES ('3448','mdirname','会员静态目录','1272196329');
INSERT INTO {$tblprefix}alangs VALUES ('2617','html_dirname','静态文件保存目录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2618','scnodemanager','基本节点管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2619','mcnodemanager','类目节点管理','1272515404');
INSERT INTO {$tblprefix}alangs VALUES ('2620','acrossleve2','两重交叉','0');
INSERT INTO {$tblprefix}alangs VALUES ('2621','acrossleve3','三重交叉','0');
INSERT INTO {$tblprefix}alangs VALUES ('2622','acrossleve4','四重交叉','0');
INSERT INTO {$tblprefix}alangs VALUES ('2623','scnode_update','您选择了批量更新基本节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2624','amconfigbelongsid','管理方案所属站点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2625','belongsubsite','所属站点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2626','subbackareatitle','子站管理后台','0');
INSERT INTO {$tblprefix}alangs VALUES ('2627','mbackareatitle','主站管理后台','0');
INSERT INTO {$tblprefix}alangs VALUES ('2628','awelcome','您好：%s ，欢迎使用网站管理系统！','0');
INSERT INTO {$tblprefix}alangs VALUES ('2629','msite_index_deal','主站首页处理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2630','subsite_index_deal','子站首页处理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2631','check_member','审核会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2632','dbopre','优化与修复','0');
INSERT INTO {$tblprefix}alangs VALUES ('2633','dbsql','执行SQL','0');
INSERT INTO {$tblprefix}alangs VALUES ('2634','dboperate','数据库操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('2635','content_static','内容页静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2636','cnodes_static','类目页静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2637','index_static','首页静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2638','static_config','静态参数设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2639','staticadmin','静态管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2640','updatescnode','更新基本节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2641','cnconfigsadd','添加节点配置','1272473843');
INSERT INTO {$tblprefix}alangs VALUES ('2642','cnconfigadmin','节点配置管理','1272473914');
INSERT INTO {$tblprefix}alangs VALUES ('2643','cnodesupdate','更新交叉节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2644','cnodeadmin','节点管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2645','normal_member_list','普通会员列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2646','admin_member_list','管理会员列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2647','member_channel_manager','会员模型管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2648','set','设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2649','alladmin','综合管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2650','update_admin','更新管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2651','checkadmin','审核操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('2652','all','全部','0');
INSERT INTO {$tblprefix}alangs VALUES ('2653','detailfilter','详细筛选','0');
INSERT INTO {$tblprefix}alangs VALUES ('2654','pointcaid','请指定栏目!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2655','imged','[图]','0');
INSERT INTO {$tblprefix}alangs VALUES ('2656','vmode2','新窗口选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('2657','coclassvmode','分类选择列表模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2658','cataapermission','允许管理的文档或合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2659','otherapermission','其它管理权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('2660','allowafcoclass','允许管理的插件内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2661','allowamember','允许管理的会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2662','allowafuncs','允许的管理功能','0');
INSERT INTO {$tblprefix}alangs VALUES ('2663','tpladmin','模板管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2708','purchase_record','购物记录','0');
INSERT INTO {$tblprefix}alangs VALUES ('2707','accessorytool','附属工具','0');
INSERT INTO {$tblprefix}alangs VALUES ('2695','contentmanage','内容管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2696','conventcontent','常规内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2697','docintercontent','文档交互内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2698','staticpage','页面静态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2699','goodsorder','商品订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('2700','plugincontent','插件内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2701','votingmanagement','投票管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2702','attachmentmanage','附件管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2703','popularwords','热门关键词','0');
INSERT INTO {$tblprefix}alangs VALUES ('2704','managemmembermanage','管理会员管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2705','memberintercontent','会员交互内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2706','membertypechange','会员类型变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('2681','allowacommu','允许管理的文档交互内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2682','allowamcommu','允许管理的会员交互内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('2683','allowamatype','允许管理的会员档案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2684','commu','交互','0');
INSERT INTO {$tblprefix}alangs VALUES ('2685','report','举报','0');
INSERT INTO {$tblprefix}alangs VALUES ('2686','ba_logout_finish','管理后台退出完成','0');
INSERT INTO {$tblprefix}alangs VALUES ('2687','mtrans','会员模型变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('2688','utrans','会员组变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('2689','memtrans','会员变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('2690','crproject','积分互兑方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2691','crprice','积分价格方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2692','crconfig','积分配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2709','collectmanagement','采集管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2710','aboutdatabase','数据库相关','0');
INSERT INTO {$tblprefix}alangs VALUES ('2711','sitemap','SiteMap地图','0');
INSERT INTO {$tblprefix}alangs VALUES ('2712','sitelogs','站点日志','0');
INSERT INTO {$tblprefix}alangs VALUES ('2713','systemstructure','架构与模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2714','integralset','积分设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2715','membergroupset','会员组系设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2716','backgroundmanageplan','后台管理方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2717','memberinterconfig','会员交互配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2718','memberchange','会员变更方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2719','classmanage','类系管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2720','collectset','合辑设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2721','docinterconfig','文档交互配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2722','pluginframework','插件架构','0');
INSERT INTO {$tblprefix}alangs VALUES ('2723','templatestyles','模版风格','0');
INSERT INTO {$tblprefix}alangs VALUES ('2724','regulartemplate','常规模版','0');
INSERT INTO {$tblprefix}alangs VALUES ('2725','functiontpl','功能模版','0');
INSERT INTO {$tblprefix}alangs VALUES ('2726','spatialtpl_solutions','空间模版/方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2727','cssandjsmanage','CSS与JS管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2728','originallogo','原始标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2729','systemset','系统设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2730','basicparameter','基本参数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2731','passsettings','通行证设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2732','ecommerce','电子商务','0');
INSERT INTO {$tblprefix}alangs VALUES ('2733','electronicmail','电子邮件','0');
INSERT INTO {$tblprefix}alangs VALUES ('2734','annexset','附件设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2735','menumanage','菜单管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2736','lanpackmanage','语言包管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2737','voteadmin','投票管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2738','pmadmin','短信管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2739','pmclear','清理短信','0');
INSERT INTO {$tblprefix}alangs VALUES ('2740','fieldtotxt','字段转文本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2741','dbdict','数据库词典','0');
INSERT INTO {$tblprefix}alangs VALUES ('2742','m_cfield','会员通用字段','0');
INSERT INTO {$tblprefix}alangs VALUES ('2743','channeladmin','文档模型管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2744','tplallconfig','模板管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2745','subindtpl','子站首页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('2746','field_cname','字段名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2747','exchange_currency','兑换积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2748','base_currency','基数积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2749','atmdown','下载','1273347091');
INSERT INTO {$tblprefix}alangs VALUES ('2750','farcissue','插件发布','0');
INSERT INTO {$tblprefix}alangs VALUES ('2751','cuissue','交互发布','0');
INSERT INTO {$tblprefix}alangs VALUES ('2752','allusergroup','全部会员组','0');
INSERT INTO {$tblprefix}alangs VALUES ('2753','pugidsbelow','以下会员组拥有权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('2754','allopen','完全开放','0');
INSERT INTO {$tblprefix}alangs VALUES ('2755','read_pmid','内容浏览权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2756','add_pmid','内容发布权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2757','down_pmid','附件下载权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2758','cnbrowse','类目浏览','0');
INSERT INTO {$tblprefix}alangs VALUES ('2759','cread_pmid','类目页浏览权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2760','mcmenu','菜单','1273347111');
INSERT INTO {$tblprefix}alangs VALUES ('2761','abackarea','管理后台','0');
INSERT INTO {$tblprefix}alangs VALUES ('2762','u_permission_set','链接显示权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2763','ablock','文档内容区','0');
INSERT INTO {$tblprefix}alangs VALUES ('2764','amconfig','后台管理方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('2765','sysdefsetting','系统默认设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2766','issysdef','是否使用自定义设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2767','anodeset','内容管理节点设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2768','anode','内容管理节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2769','aurl','管理后台外链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2770','defsetting','默认设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2771','aurl_add','添加管理外链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2772','aurl_name','管理外链名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2773','aurl_remark','管理外链备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('2774','aurl_type','管理链接类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2775','aurl_admin','后台外链管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2776','aurl_item_set','管理后台外链设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2777','arcandalb','文档与合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2778','arange','管理范围：','0');
INSERT INTO {$tblprefix}alangs VALUES ('2779','nococlass','未设置分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2780','agnoselect','不选表示不限范围','0');
INSERT INTO {$tblprefix}alangs VALUES ('2781','view_filters','管理页面需要显示以下筛选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2782','view_info','显示详细信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2783','view_lists','管理页面的列表显示的信息项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2784','reset_validperiod','重设有效期','0');
INSERT INTO {$tblprefix}alangs VALUES ('2785','view_operates','管理页面需要显示的操作项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2786','arangeset','管理范围设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2787','pageresult','管理页面显示设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2788','customapage','自定义管理脚本','1264210971');
INSERT INTO {$tblprefix}alangs VALUES ('2789','agcustomapage','不定义请留空。定义后其它页面显示设置无效。输入格式：a/b.php，请确认系统存在该文件。','1264300736');
INSERT INTO {$tblprefix}alangs VALUES ('2790','node','节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2792','fromcata','继承类目权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('2793','agnoselect1','不选表示全部显示','0');
INSERT INTO {$tblprefix}alangs VALUES ('2794','customphp','自定程序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2795','readd','重发布','0');
INSERT INTO {$tblprefix}alangs VALUES ('2797','lic_ck','核实授权','0');
INSERT INTO {$tblprefix}alangs VALUES ('2798','lic_uk','未授权版本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2799','lic_by','点击购买','0');
INSERT INTO {$tblprefix}alangs VALUES ('2800','unknow','未知','0');
INSERT INTO {$tblprefix}alangs VALUES ('2801','welcome_platform','欢迎光临网站管理平台','0');
INSERT INTO {$tblprefix}alangs VALUES ('2802','08cms_dynamic','官方最新动态 官方新版本的发布与重要补丁的升级等动态，都会在这里显示','0');
INSERT INTO {$tblprefix}alangs VALUES ('2803','08cms_service','技术支持服务 如果你在使用中遇到问题，可以访问以下链接寻求帮助','0');
INSERT INTO {$tblprefix}alangs VALUES ('2804','08cms_bbs','官方交流论坛','1272112716');
INSERT INTO {$tblprefix}alangs VALUES ('2805','08cms_biz_service','商业支持服务','0');
INSERT INTO {$tblprefix}alangs VALUES ('2806','08cms_stat','站点数据统计 通过站点统计，您可以整体把握站点的发展状况。','0');
INSERT INTO {$tblprefix}alangs VALUES ('2807','server_stat','系统基本信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2808','dingyue_com','鼎越科技','0');
INSERT INTO {$tblprefix}alangs VALUES ('2809','master_level','你的管理级别：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2810','daterange','日期范围','0');
INSERT INTO {$tblprefix}alangs VALUES ('2811','server_info','服务器信息：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2812','cancel_album_abover','取消完结','0');
INSERT INTO {$tblprefix}alangs VALUES ('2813','php_safemode','PHP安全模式：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2814','mysql_version','MySQL版本：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2815','php_max_upload','最大上传限制：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2816','allow_url_fopen','允许打开远程文件：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2817','db_use_size','当前数据库大小：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2818','php_gd_pic','图像GD库支持：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2819','attach_size','当前附件总量：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2820','php_mail_mode','邮件支持模式：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2821','server_ip','服务器IP：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2822','server_time','服务器当前时间：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2823','server_domain','当前域名：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2824','user_ip','当前访问IP：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2825','08cms_version','软件版本信息：%s','0');
INSERT INTO {$tblprefix}alangs VALUES ('2826','arctype','模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2827','content_list','内容列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2828','all_cuitem','全部交互项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2829','reply_list','回复列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2830','replys','回复数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2831','order_num','订购数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2832','offers','报价数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2833','ordersum','订购总额','0');
INSERT INTO {$tblprefix}alangs VALUES ('2834','favorites','收藏数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2835','praises','顶数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2836','debases','踩数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2837','answers','答案数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2838','adopts','采用数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2839','downs','下载数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2840','reward','悬赏','0');
INSERT INTO {$tblprefix}alangs VALUES ('2841','view_coids','发表页面需要显示以下类系选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2842','cata_choose','请选择栏目或分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2843','p_choose','请选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('2844','allow_type','选择文档类型添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('2845','inurl','管理后台内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2846','inurl_add','添加管理内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2847','addinalbum','辑内添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('2848','abconfig','辑内结构','0');
INSERT INTO {$tblprefix}alangs VALUES ('2849','abcontent','辑内文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('2850','selected','已选择：','0');
INSERT INTO {$tblprefix}alangs VALUES ('2851','oneuser','个人','0');
INSERT INTO {$tblprefix}alangs VALUES ('2852','multiuser','公用','0');
INSERT INTO {$tblprefix}alangs VALUES ('2853','oneuser_state','是否公用合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2854','webparam','网站参数','0');
INSERT INTO {$tblprefix}alangs VALUES ('2855','view_inurls','管理后台列表的自定义<br />管理内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2856','inadmin','单点管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2857','inurl_','[%s]管理后台内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2858','pchoosecontent','请选择要操作的内容!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2860','lookrelatedsource','查看相关来源','0');
INSERT INTO {$tblprefix}alangs VALUES ('2861','submitmessage','提交信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2862','backmessage','返回信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2864','offer_list','报价列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2865','oprice','报价价格','0');
INSERT INTO {$tblprefix}alangs VALUES ('2866','inpofferprice','请输入报价价格!','0');
INSERT INTO {$tblprefix}alangs VALUES ('2867','adminmessage','管理信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2868','adopt_state','采用状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2869','adopted','被采用','0');
INSERT INTO {$tblprefix}alangs VALUES ('2870','noadopt','未采用','0');
INSERT INTO {$tblprefix}alangs VALUES ('2871','look_question','查看问题','0');
INSERT INTO {$tblprefix}alangs VALUES ('3159','allow_mchannel','允许以下类型的会员关联','0');
INSERT INTO {$tblprefix}alangs VALUES ('2873','allow_reward_mini_cu','悬赏允许最低积分','0');
INSERT INTO {$tblprefix}alangs VALUES ('2874','report_list','举报列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2883','look_album','查看指定合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2877','uncheckneed','拒绝更新申请','0');
INSERT INTO {$tblprefix}alangs VALUES ('2878','checkcopy','启用更新副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2879','uncheckcopy','驳回并删除更新副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2880','checkneed','批准更新申请','0');
INSERT INTO {$tblprefix}alangs VALUES ('2885','catas_pointed','已指定的类目属性','0');
INSERT INTO {$tblprefix}alangs VALUES ('2886','saddinalbum','定类型添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('2887','maddinalbum','辑内添加','0');
INSERT INTO {$tblprefix}alangs VALUES ('2888','look_archive','查看文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('2889','cinadmin','自定单点管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2946','view_inmurls','会员中心列表的自定管理内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2890','click','点击','0');
INSERT INTO {$tblprefix}alangs VALUES ('2891','ba_map','后台地图','0');
INSERT INTO {$tblprefix}alangs VALUES ('2892','cn_pointed','已指定类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2893','albumchoose','合辑选择','0');
INSERT INTO {$tblprefix}alangs VALUES ('2894','selectedalbum','已选合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2895','confirm','确认','0');
INSERT INTO {$tblprefix}alangs VALUES ('2896','addinpriv','加入个人合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2897','addinopen','加入公用合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2899','allowcopys','发表时允许添加复本的类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2900','addcpinca','同时在以下栏目发表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2901','addcpincc','同时在以下 %s 中发表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2902','cpkeep','增加其它类系复本时保持与正本同步','0');
INSERT INTO {$tblprefix}alangs VALUES ('2903','cpupdate','同步更新当前文档的复本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2904','noupdate','不更新','0');
INSERT INTO {$tblprefix}alangs VALUES ('2905','cpupdate1','完全同步更新','0');
INSERT INTO {$tblprefix}alangs VALUES ('2906','cpupdate2','部分更新(不更新标题、关健词、缩略图、摘要)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2907','addcp','在 %s 增加复本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2908','cpallow','允许在不同分类增加复本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2909','nocp','隐藏列表中重复的复本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2910','agnocp','当列表中出现多个重复的复本时，只显示第一条','0');
INSERT INTO {$tblprefix}alangs VALUES ('2911','incheck','辑内审核','0');
INSERT INTO {$tblprefix}alangs VALUES ('2912','inuncheck','辑内解审','0');
INSERT INTO {$tblprefix}alangs VALUES ('2913','inclear','退出合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2914','inorder','辑内排序','0');
INSERT INTO {$tblprefix}alangs VALUES ('2915','murl','会员中心外链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2916','inmurl','会员中心内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('2917','delcp','删除副本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2918','needupdate','申请更新','0');
INSERT INTO {$tblprefix}alangs VALUES ('2919','unneedupdate','取消更新申请','0');
INSERT INTO {$tblprefix}alangs VALUES ('2920','onclick','链接加入onclick字串','0');
INSERT INTO {$tblprefix}alangs VALUES ('2921','fnodeset','插件管理节点设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2922','fnode','插件管理节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('2923','fblock','插件内容区','0');
INSERT INTO {$tblprefix}alangs VALUES ('2924','reply_permission_set','回复权限设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2925','consult_pics','咨询字节长度限制！','0');
INSERT INTO {$tblprefix}alangs VALUES ('2926','agdate','不限日期请留空','0');
INSERT INTO {$tblprefix}alangs VALUES ('2927','utran','组变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('2928','mtran','类型变更','0');
INSERT INTO {$tblprefix}alangs VALUES ('2929','mblock','会员内容区','0');
INSERT INTO {$tblprefix}alangs VALUES ('2930','qstate','咨询状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('2931','qadmin','咨询管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2932','updatebtag','更新原始标识列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2933','btag_update','你选择了更新原始标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('2934','btaglist','原始标识列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('2935','isconsult','仅限咨询类信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2936','nousergroup','未设置组的会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('2937','memtype','会员类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2938','regip','注册IP','0');
INSERT INTO {$tblprefix}alangs VALUES ('2939','arcallows','文档限额','0');
INSERT INTO {$tblprefix}alangs VALUES ('2940','cuallows','交互限额','0');
INSERT INTO {$tblprefix}alangs VALUES ('2942','aga_title','留空则使用系统默认值','0');
INSERT INTO {$tblprefix}alangs VALUES ('2941','arange_field','需要管理的信息项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2943','adm_title','管理界面标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('2944','adm_guide','管理界面提示说明','0');
INSERT INTO {$tblprefix}alangs VALUES ('2945','arcdetail','文档详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('3032','asetalbum','文档归辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2947','fieldpm','字段','1273347130');
INSERT INTO {$tblprefix}alangs VALUES ('2948','useredit','审核后在前台允许修改','0');
INSERT INTO {$tblprefix}alangs VALUES ('2949','field_pmid','信息内容的编辑权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('2950','frommsg','继承所在信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2951','commu_sett','交互项目设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2952','a_url','管理链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('2953','cpkeeps','生成复本时需要保持的分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2954','aitems','管理后台禁用的操作项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2955','agitems','系统默认所有项目有效，选中后关闭。','0');
INSERT INTO {$tblprefix}alangs VALUES ('2956','mcopy','复本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2957','addcopy','添加复本','0');
INSERT INTO {$tblprefix}alangs VALUES ('2958','citems','会员中心禁用的操作项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2959','useredits','会员中心的自由修改项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2960','aguseredits','已审内容的信息项系统默认为不可修改，选中后会员可自由修改该项信息。','0');
INSERT INTO {$tblprefix}alangs VALUES ('2961','acoids','管理后台禁用的类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2962','agcoids','系统默认所有的类系有效，选中后关闭。','0');
INSERT INTO {$tblprefix}alangs VALUES ('2963','ccoids','会员中心禁用的类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2964','altype_copy','复制合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2965','soc_altype_name','源合辑类型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2966','new_altype_name','新合辑类型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2967','altypenamemiss','请输入合辑类型名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('2970','additems_a','发表文档时禁用的操作项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2969','arcedit','文档编辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('2971','agadditems','系统默认为全部项目可用，选中项在添加信息时不显示。','0');
INSERT INTO {$tblprefix}alangs VALUES ('2972','abfunc','合辑功能','0');
INSERT INTO {$tblprefix}alangs VALUES ('2973','enablealbum','开启合辑功能','0');
INSERT INTO {$tblprefix}alangs VALUES ('2974','paynext','货到付款','0');
INSERT INTO {$tblprefix}alangs VALUES ('2975','paycurrency','站内帐户支付','0');
INSERT INTO {$tblprefix}alangs VALUES ('2976','payalipay','支付宝支付','0');
INSERT INTO {$tblprefix}alangs VALUES ('2977','paytenpay','财付通支付','0');
INSERT INTO {$tblprefix}alangs VALUES ('2978','shipingfee1','平邮','0');
INSERT INTO {$tblprefix}alangs VALUES ('2979','shipingfee2','特快专递EMS','0');
INSERT INTO {$tblprefix}alangs VALUES ('2980','shipingfee3','其它快递公司','0');
INSERT INTO {$tblprefix}alangs VALUES ('2981','mypaymode','商品支付方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('2982','agisalbum','开启合辑功能后，本类型文档将成为合辑，允许在合辑内添加其它文档或加载已有的文档到合辑中。','0');
INSERT INTO {$tblprefix}alangs VALUES ('3253','advsetting','高级扩展选项','1264302613');
INSERT INTO {$tblprefix}alangs VALUES ('3254','customsetting','自定义设置参数','1264302885');
INSERT INTO {$tblprefix}alangs VALUES ('2984','ucotype','交互类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2985','ucotype_manager','交互类系管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('2986','add_ucotype','添加交互类系','0');
INSERT INTO {$tblprefix}alangs VALUES ('2987','commu_type','交互类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('2988','ucotypem_detail_edit','交互类系详细设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('2989','ucoclass','交互分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('2990','ucoclass_admin','交互分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3024','modify_payed','修改已付款','0');
INSERT INTO {$tblprefix}alangs VALUES ('2993','allow_repeat','允许重复发送信息','0');
INSERT INTO {$tblprefix}alangs VALUES ('2994','nouservote','允许游客参与投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('2995','repeatvote','允许重复投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('2996','repeat_time_m','重复发送时间间隔(分)','0');
INSERT INTO {$tblprefix}alangs VALUES ('2997','cu_cviews','发送方允许查看的项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('2998','cu_useredits','发送方对已审信息的自由编辑项','0');
INSERT INTO {$tblprefix}alangs VALUES ('2999','cu_aviews','管理方可查看的项目','0');
INSERT INTO {$tblprefix}alangs VALUES ('3000','wait_cpcheck','等待商家确认','0');
INSERT INTO {$tblprefix}alangs VALUES ('3001','wait_pay','等待付款','0');
INSERT INTO {$tblprefix}alangs VALUES ('3002','wait_send','等待发货','0');
INSERT INTO {$tblprefix}alangs VALUES ('3003','goods_send','已发货','0');
INSERT INTO {$tblprefix}alangs VALUES ('3004','order_ok','完成','0');
INSERT INTO {$tblprefix}alangs VALUES ('3005','order_cancel','取消','0');
INSERT INTO {$tblprefix}alangs VALUES ('3006','orderstate','订单状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('3007','noshiping','无需送货','0');
INSERT INTO {$tblprefix}alangs VALUES ('3008','cancelorders','取消订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('3009','mtplstore','常规模板库','0');
INSERT INTO {$tblprefix}alangs VALUES ('3010','sptplstore','功能模板库','0');
INSERT INTO {$tblprefix}alangs VALUES ('3011','spacetpl','空间模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3017','confirmorders','确认订单','0');
INSERT INTO {$tblprefix}alangs VALUES ('3013','modify_confirm','修改并确认','0');
INSERT INTO {$tblprefix}alangs VALUES ('3014','tplrelated','模板相关管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3015','confirm_cancel','确定取消','0');
INSERT INTO {$tblprefix}alangs VALUES ('3021','umode0','发送方与管理方通用','0');
INSERT INTO {$tblprefix}alangs VALUES ('3020','coclassumode','分类使用范围','0');
INSERT INTO {$tblprefix}alangs VALUES ('3022','umode1','发送方专用','0');
INSERT INTO {$tblprefix}alangs VALUES ('3023','umode2','管理方专用','0');
INSERT INTO {$tblprefix}alangs VALUES ('3025','purchase_type_set','向商家购物方式','0');
INSERT INTO {$tblprefix}alangs VALUES ('3026','nopurchse','不支持向商家购物','0');
INSERT INTO {$tblprefix}alangs VALUES ('3027','no_confirm','无需确认','0');
INSERT INTO {$tblprefix}alangs VALUES ('3028','be_confirm','必需确认','0');
INSERT INTO {$tblprefix}alangs VALUES ('3029','ordmode','订单模式','0');
INSERT INTO {$tblprefix}alangs VALUES ('3030','cartkey','购物车校验密钥','0');
INSERT INTO {$tblprefix}alangs VALUES ('3031','offerdetail','报价详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('3033','loadold','加载文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('3034','commentadmin','评论管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3035','offeradmin','报价管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3036','replyadmin','回复管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3037','purchaseadmin','购物管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3038','answeradmin','答案管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3039','pickbugadmin','举报管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3041','memberdetail','会员设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3042','inurl0','管理内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('3043','no_admin_backarea','没有管理后台权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('3044','backarea_ip_forbid','管理后台IP禁止','0');
INSERT INTO {$tblprefix}alangs VALUES ('3045','admin_login_finish','管理登陆成功','0');
INSERT INTO {$tblprefix}alangs VALUES ('3046','cur_member','当前会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('3047','logout_member','退出会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('3048','login_member','登陆会员','0');
INSERT INTO {$tblprefix}alangs VALUES ('3049','goback_index','返回首页','0');
INSERT INTO {$tblprefix}alangs VALUES ('3050','admin_account','管理帐号','0');
INSERT INTO {$tblprefix}alangs VALUES ('3051','login_pwd','登陆密码','0');
INSERT INTO {$tblprefix}alangs VALUES ('3053','areplyadmin','回复管理(收)','0');
INSERT INTO {$tblprefix}alangs VALUES ('3054','allsitecac','清空整站缓存并重建','0');
INSERT INTO {$tblprefix}alangs VALUES ('3055','read_state','已读状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('3056','reply_state','反馈状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('3057','mnode','会员模型节点','0');
INSERT INTO {$tblprefix}alangs VALUES ('3058','mnodeset','会员模型节点设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3059','nobackareapm','您没有 %s 管理后台权限!','0');
INSERT INTO {$tblprefix}alangs VALUES ('3060','rechoosebackarea','请重新选择进入有权限的后台：','0');
INSERT INTO {$tblprefix}alangs VALUES ('3061','mainmenu','主站菜单','0');
INSERT INTO {$tblprefix}alangs VALUES ('3062','submenu','子站菜单','0');
INSERT INTO {$tblprefix}alangs VALUES ('3063','usualurl0','常用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('3064','backareaconfig','管理后台配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3065','mcenterconfig','会员中心配置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3066','choosecpurl','请指定要复制的链接！','0');
INSERT INTO {$tblprefix}alangs VALUES ('3067','url_copy','复制链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('3068','soc_url_name','来源链接名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('3069','new_url_name','新链接名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('3070','urlcopyfinish','链接复制完成！','0');
INSERT INTO {$tblprefix}alangs VALUES ('3071','fugidsbelow','以下会员组没有权限','0');
INSERT INTO {$tblprefix}alangs VALUES ('3072','remodedown','远程下载','0');
INSERT INTO {$tblprefix}alangs VALUES ('3073','localproject','上传方案','0');
INSERT INTO {$tblprefix}alangs VALUES ('3074','player','播放器','0');
INSERT INTO {$tblprefix}alangs VALUES ('3075','passport','通行证','0');
INSERT INTO {$tblprefix}alangs VALUES ('3076','ms_cnt_tpl','空间内容页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3077','ms_plus_page','空间文档附加页','0');
INSERT INTO {$tblprefix}alangs VALUES ('3078','ms_cu_tpl','空间内容模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3079','ms_plus_tpl','空间内容附加模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3080','arctpl','文档模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3081','mnamestxt','发件人名称(逗号分隔多个)','0');
INSERT INTO {$tblprefix}alangs VALUES ('3082','gotopage','进入','0');
INSERT INTO {$tblprefix}alangs VALUES ('3083','cnt_tpl','内容模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3084','product_tpl','空间产品页模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3085','parent_altype','指定所属合辑类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('3086','othertpl','其它模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3087','checksubject','检查重名','0');
INSERT INTO {$tblprefix}alangs VALUES ('3088','result_netsite_forbidinc','结果网址禁含','0');
INSERT INTO {$tblprefix}alangs VALUES ('3089','test','测试','0');
INSERT INTO {$tblprefix}alangs VALUES ('3090','plitpage_navi_region','分页导航区域采集模印','0');
INSERT INTO {$tblprefix}alangs VALUES ('3092','choose_urlsauto','您选择了批量采集当前任务(含辑内任务)的内容网址！<br>提示：一键全部完成包含这步操作','0');
INSERT INTO {$tblprefix}alangs VALUES ('3093','choose_gatherauto','您选择了采集当前任务(含辑内任务)的文档内容！<br>\r\n提示：一键全部完成包含了本步的操作。','0');
INSERT INTO {$tblprefix}alangs VALUES ('3095','choose_outputauto','您选择了将当前任务(含辑内任务)中的内容批量入库！<br>\r\n提示：一键全部完成包含了本步的操作。','0');
INSERT INTO {$tblprefix}alangs VALUES ('3096','choose_allauto','您选择了一键完成以下操作：<br>\r\n网址采集、内容采集、内容入库！<br>\r\n执行之前确保所有规则已经设置完成。','0');
INSERT INTO {$tblprefix}alangs VALUES ('3104','cnturl','内容网址','0');
INSERT INTO {$tblprefix}alangs VALUES ('3103','utitle','网址标题','0');
INSERT INTO {$tblprefix}alangs VALUES ('3100','choose_urlstest','您选择了测试规则。<br>\r\n在测试之前请确认已经设置相关规则。<br>\r\n本测试从网址规则一步一步测试，请注意选择测试页里的相关链接进行下一步测试。','1264120912');
INSERT INTO {$tblprefix}alangs VALUES ('3101','choose_contentstest','您选择了内容规则测试。<br>\r\n在执行这前请确认已设置内容规则，<br>\r\n以及库中需要有未采集内容的网址。','0');
INSERT INTO {$tblprefix}alangs VALUES ('3107','all_1_catas','全部二级类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('3108','all_2_catas','全部三级类目','0');
INSERT INTO {$tblprefix}alangs VALUES ('3109','orderitem','自定类目排序因素','0');
INSERT INTO {$tblprefix}alangs VALUES ('3110','agorderitem','限添一个因素，如：点击数输入clicks，文档数输入archives','0');
INSERT INTO {$tblprefix}alangs VALUES ('3130','adv_options','高级选项','0');
INSERT INTO {$tblprefix}alangs VALUES ('3112','cotypestats','以下类系统计流量及文档','0');
INSERT INTO {$tblprefix}alangs VALUES ('3114','inurl_name','后台内链名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('3115','inurl_remark','后台内链备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('3116','inurl_type','后台内链类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('3117','murl_admin','会员中心外链管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3118','murl_name','会员中心外链名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('3119','murl_remark','会员中心外链备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('3120','murl_type','会员中心外链类型','0');
INSERT INTO {$tblprefix}alangs VALUES ('3121','murl_add','添加会员中心外链','0');
INSERT INTO {$tblprefix}alangs VALUES ('3122','inmurl_admin','会员中心内链管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3123','inmurl_add','添加会员中心内链','0');
INSERT INTO {$tblprefix}alangs VALUES ('3124','inmurl_remark','会员中心内链备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('3125','inmurl_type','会员中心内链备注','0');
INSERT INTO {$tblprefix}alangs VALUES ('3126','inmurl_name','会员中心内链名称','0');
INSERT INTO {$tblprefix}alangs VALUES ('3127','inmurl_item_set','会员中心内链设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3128','murl_item_set','会员中心外链设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3129','inurl_item_set','后台内链设置','0');
INSERT INTO {$tblprefix}alangs VALUES ('3131','replydetail','回复详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('3132','commentdetail','评论详情','0');
INSERT INTO {$tblprefix}alangs VALUES ('3133','noread','未读','0');
INSERT INTO {$tblprefix}alangs VALUES ('3134','mcomment','会员评论','0');
INSERT INTO {$tblprefix}alangs VALUES ('3135','mreply','会员回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('3136','mem_stat','会员信息统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('3137','arc_stat','站内信息统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('3138','stat','统计','0');
INSERT INTO {$tblprefix}alangs VALUES ('3139','nowmonth','本月','0');
INSERT INTO {$tblprefix}alangs VALUES ('3140','nowweek','本周','0');
INSERT INTO {$tblprefix}alangs VALUES ('3141','day_3','三天','0');
INSERT INTO {$tblprefix}alangs VALUES ('3142','day_1','今天','0');
INSERT INTO {$tblprefix}alangs VALUES ('3143','amember','管理员','0');
INSERT INTO {$tblprefix}alangs VALUES ('3144','openreg','会员开放注册：','0');
INSERT INTO {$tblprefix}alangs VALUES ('3145','openspace','会员空间开放：','0');
INSERT INTO {$tblprefix}alangs VALUES ('3147','minerrtime','同一IP登陆失败锁定时间(秒)','0');
INSERT INTO {$tblprefix}alangs VALUES ('3148','maxerrtimes','同一IP最大尝试次数(次)','0');
INSERT INTO {$tblprefix}alangs VALUES ('3149','purchase_pick_url1','向商家购物的调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('3150','emaltpl','邮件模板','0');
INSERT INTO {$tblprefix}alangs VALUES ('3151','splangcontent','功能语言内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('3152','vote_option','投票选项，从1到5','0');
INSERT INTO {$tblprefix}alangs VALUES ('3153','vote_url','对单个记录投票','0');
INSERT INTO {$tblprefix}alangs VALUES ('3154','cart_pick_url','购物车调用链接','0');
INSERT INTO {$tblprefix}alangs VALUES ('3155','vcatalog','投票分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('3167','overupdate','更新完成','0');
INSERT INTO {$tblprefix}alangs VALUES ('3168','checkupdate','应用更新内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('3169','uncheckupdate','拒绝更新内容','0');
INSERT INTO {$tblprefix}alangs VALUES ('3170','memberareply','会员回复(收)','0');
INSERT INTO {$tblprefix}alangs VALUES ('3171','status','状态','0');
INSERT INTO {$tblprefix}alangs VALUES ('3172','no_chid_attr','排除以下文档模型','0');
INSERT INTO {$tblprefix}alangs VALUES ('3173','reportor','举报者','0');
INSERT INTO {$tblprefix}alangs VALUES ('3174','reporttime','举报时间','0');
INSERT INTO {$tblprefix}alangs VALUES ('3176','read','已读','0');
INSERT INTO {$tblprefix}alangs VALUES ('3177','areply','回复','0');
INSERT INTO {$tblprefix}alangs VALUES ('3179','openalbum','公用合辑','0');
INSERT INTO {$tblprefix}alangs VALUES ('3180','indestaticc','站点首页静态更新周期(分钟)','1271819642');
INSERT INTO {$tblprefix}alangs VALUES ('3181','reset_parent_coclass','重设父分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('3183','setusualtag','设为常用标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('3184','usual','常用','0');
INSERT INTO {$tblprefix}alangs VALUES ('3185','usualtagremark','常用标识说明','0');
INSERT INTO {$tblprefix}alangs VALUES ('3186','usualtagclass','常用标识分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('3187','usualtags','常用标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('3188','usualtagsadmin','常用标识管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3189','cancelclass','解除分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('3190','noclass','未分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('3191','addclass','添加分类','0');
INSERT INTO {$tblprefix}alangs VALUES ('3192','tagclassesadmin','常用标识分类管理','0');
INSERT INTO {$tblprefix}alangs VALUES ('3193','edit_tagclasses_mlist','编辑常用标识分类列表','0');
INSERT INTO {$tblprefix}alangs VALUES ('3194','edit_usualtags_mlist','编辑常用标识','0');
INSERT INTO {$tblprefix}alangs VALUES ('3195','space','会员空间','0');
INSERT INTO {$tblprefix}alangs VALUES ('3198','please_set_payed','请设置已付款数额！','1262612859');
INSERT INTO {$tblprefix}alangs VALUES ('3199','set_payed','以防误操作，请填入已付款数额后再次提交！','1262612873');
INSERT INTO {$tblprefix}alangs VALUES ('3196','alipay_partner','支付宝合作商户ID','1262163309');
INSERT INTO {$tblprefix}alangs VALUES ('3200','orderspayed','已付款确认','1262612978');
INSERT INTO {$tblprefix}alangs VALUES ('3197','confirm_set_payed','确定对方已支付 %s 元的货款，并结单吗？','1262612821');
INSERT INTO {$tblprefix}alangs VALUES ('3201','needtime','申请时间','1263192862');
INSERT INTO {$tblprefix}alangs VALUES ('3202','checkdate','审核时间','1263195965');
INSERT INTO {$tblprefix}alangs VALUES ('3203','extract_count','提现数量','1263196557');
INSERT INTO {$tblprefix}alangs VALUES ('3204','extract_getcount','提现获得','1263196573');
INSERT INTO {$tblprefix}alangs VALUES ('3205','extract_discount','提现率(%)','1263346450');
INSERT INTO {$tblprefix}alangs VALUES ('3206','extract_record_check','提现记录审核','1263201697');
INSERT INTO {$tblprefix}alangs VALUES ('3207','yuan','元','1263201791');
INSERT INTO {$tblprefix}alangs VALUES ('3208','extract_list','提现记录列表','1263257271');
INSERT INTO {$tblprefix}alangs VALUES ('3209','delstate','删除状态','1263266047');
INSERT INTO {$tblprefix}alangs VALUES ('3210','extract','提现','1263289817');
INSERT INTO {$tblprefix}alangs VALUES ('3211','extract_mincount','最低提现额(元)','1263348247');
INSERT INTO {$tblprefix}alangs VALUES ('3212','inalbumlist','所属合辑列表','1263983745');
INSERT INTO {$tblprefix}alangs VALUES ('3213','inscount','辑内文档数量','1263983803');
INSERT INTO {$tblprefix}alangs VALUES ('3214','arr_pre','标识返回的数组名称','1263986807');
INSERT INTO {$tblprefix}alangs VALUES ('3215','alltag','全部标识','1263988184');
INSERT INTO {$tblprefix}alangs VALUES ('3216','abnew','辑内最新文档','1264007618');
INSERT INTO {$tblprefix}alangs VALUES ('3217','album_newstat','辑内最新文档统计的时间间隔(小时)','1264012996');
INSERT INTO {$tblprefix}alangs VALUES ('3218','agusource1','可输入字段名aa或变量$a[b]。如标识用于下载、播放页如flash.php等(页面指定来源)，请留空。','1264046431');
INSERT INTO {$tblprefix}alangs VALUES ('3219','agusource','输入格式：字段名aa、变量$a[b]等。','1264047147');
INSERT INTO {$tblprefix}alangs VALUES ('3222','rebtplcache','重建模板缓存','1264069340');
INSERT INTO {$tblprefix}alangs VALUES ('3223','rebuld_tplcache','您选择了重建本子站模板页面缓存。<br> 在非调试模式下，页面模板的修改，<br>需要重建缓存才能生效。','1272916148');
INSERT INTO {$tblprefix}alangs VALUES ('3224','rebuild','重建','1264069730');
INSERT INTO {$tblprefix}alangs VALUES ('3225','tplcachefin','模板缓存重建成功！','1264069961');
INSERT INTO {$tblprefix}alangs VALUES ('3226','tagsearch','标识搜索','1264074068');
INSERT INTO {$tblprefix}alangs VALUES ('3227','customsql','自定查询字串','1264093785');
INSERT INTO {$tblprefix}alangs VALUES ('3228','gather_test_title','[测试采集]','1264120325');
INSERT INTO {$tblprefix}alangs VALUES ('3229','reset_gather','重置状态','1264120343');
INSERT INTO {$tblprefix}alangs VALUES ('3230','gmission_copy','任务复制','1264120360');
INSERT INTO {$tblprefix}alangs VALUES ('3231','gather_mission_copy','采集任务复制','1264120380');
INSERT INTO {$tblprefix}alangs VALUES ('3232','copy_gather_mission','复制采集任务','1264120398');
INSERT INTO {$tblprefix}alangs VALUES ('3233','son_mission','子任务','1264120542');
INSERT INTO {$tblprefix}alangs VALUES ('3234','son_gather_mission_cname','子任务名称','1264120556');
INSERT INTO {$tblprefix}alangs VALUES ('3235','son_gather_model','子任务模型','1264120697');
INSERT INTO {$tblprefix}alangs VALUES ('3236','autoall','一键','1264120713');
INSERT INTO {$tblprefix}alangs VALUES ('3237','netsite','网址','1264120749');
INSERT INTO {$tblprefix}alangs VALUES ('3238','warehousing','入库','1264120774');
INSERT INTO {$tblprefix}alangs VALUES ('3239','rulemanagement','规则管理','1264120795');
INSERT INTO {$tblprefix}alangs VALUES ('3240','breakfinish','中止操作完成','1264120811');
INSERT INTO {$tblprefix}alangs VALUES ('3242','vol_admin','分卷管理','1264145515');
INSERT INTO {$tblprefix}alangs VALUES ('3243','volno','分卷号','1264145542');
INSERT INTO {$tblprefix}alangs VALUES ('3244','volname','分卷名','1264145567');
INSERT INTO {$tblprefix}alangs VALUES ('3245','add_vol','添加分卷','1264145590');
INSERT INTO {$tblprefix}alangs VALUES ('3246','vol','分卷','1264150524');
INSERT INTO {$tblprefix}alangs VALUES ('3247','set_volid','设置分卷','1264151162');
INSERT INTO {$tblprefix}alangs VALUES ('3248','del_vol','删除分卷后，分卷内的内容将作为不分卷处理。 <br>此操作执行后不可恢复。','1264152218');
INSERT INTO {$tblprefix}alangs VALUES ('3255','agmucustom','不定义请留空，输入格式：a/b.php，请确认系统中存在该文件。','1264303145');
INSERT INTO {$tblprefix}alangs VALUES ('3252','no_arange','排除范围：','1264251919');
INSERT INTO {$tblprefix}alangs VALUES ('3251','yes_arange','允许范围：','1264251919');
INSERT INTO {$tblprefix}alangs VALUES ('3250','onlyview','脚本只处理界面','1264216291');
INSERT INTO {$tblprefix}alangs VALUES ('3256','custom_ucadd','自定前台添加处理脚本','1264304436');
INSERT INTO {$tblprefix}alangs VALUES ('3257','custom_uadetail','自定义管理后台编辑脚本','1264304348');
INSERT INTO {$tblprefix}alangs VALUES ('3258','custom_umdetail','自定义会员中心编辑脚本','1264304377');
INSERT INTO {$tblprefix}alangs VALUES ('3259','agcustomsetting','每行限添一个设置参数，设置格式为：参数名=设置值，如aaa=abc或aa=a,b,c等。<br>参数名需要符合php参数规范','1264322421');
INSERT INTO {$tblprefix}alangs VALUES ('3260','custom_ucvote','自定前台投票处理脚本','1264345190');
INSERT INTO {$tblprefix}alangs VALUES ('3261','bytenum','字节数','1264383660');
INSERT INTO {$tblprefix}alangs VALUES ('3262','agarr_pre','复合标识与分页标识默认为v，特殊标识默认为u。当本标识内嵌于同类型标识时，需要修改该值。<br> 在当前标识内可用{aaa}或{$v[aaa]}调用信息，跨标识调用信息只能使用{$v[aaa]}。','1264818861');
INSERT INTO {$tblprefix}alangs VALUES ('3263','stat_text_size_src','自动统计文本大小来源','1264402621');
INSERT INTO {$tblprefix}alangs VALUES ('3264','disable_htmldir','不启用此目录','1264408485');
INSERT INTO {$tblprefix}alangs VALUES ('3265','agdirname','请谨慎操作，修改静态目录后，需要针对相关页面修复静态链接或重新生成静态。','1264427322');
INSERT INTO {$tblprefix}alangs VALUES ('3266','fromcode','来自代码返回数组','1264600443');
INSERT INTO {$tblprefix}alangs VALUES ('3267','aginnertext','每行填写一个选项，格式1：选项值（同时为显示标题），格式2：选项值=选项显示标题。<br> 如选择了 来自代码返回数组，请填写PHP代码，使用return array(数组内容);得到选择内容。','1264600443');
INSERT INTO {$tblprefix}alangs VALUES ('3268','scorestr','评分选项(逗号分隔)','1264678394');
INSERT INTO {$tblprefix}alangs VALUES ('3269','agscorestr','选项限为1-99间的整数，选项间以逗号分隔','1264678498');
INSERT INTO {$tblprefix}alangs VALUES ('3270','scorepics','统计各选项的评分次数','1264679184');
INSERT INTO {$tblprefix}alangs VALUES ('3271','agscorepics','如果统计次数，需要定义格式为score_2(2为选项)的文档通用字段(整数类型)。','1264679329');
INSERT INTO {$tblprefix}alangs VALUES ('3272','repugradeadmin','信用等级管理','1264700919');
INSERT INTO {$tblprefix}alangs VALUES ('3273','rgbase','信用值底线','1264701022');
INSERT INTO {$tblprefix}alangs VALUES ('3274','repugrade','信用等级','1264701057');
INSERT INTO {$tblprefix}alangs VALUES ('3275','ico','图标','1264701169');
INSERT INTO {$tblprefix}alangs VALUES ('3276','nodurat','不设置时间限制','1264747618');
INSERT INTO {$tblprefix}alangs VALUES ('3278','custom_uaadd','自定管理后台添加脚本','1264835376');
INSERT INTO {$tblprefix}alangs VALUES ('3279','repu','信用','1264904214');
INSERT INTO {$tblprefix}alangs VALUES ('3280','repualterlist','信用变更列表','1264904263');
INSERT INTO {$tblprefix}alangs VALUES ('3281','repualter','信用变更','1264904298');
INSERT INTO {$tblprefix}alangs VALUES ('3282','hand_repu','手动操作信用值','1264906552');
INSERT INTO {$tblprefix}alangs VALUES ('3283','agmultiuser','多个会员以逗号分隔','1264907302');
INSERT INTO {$tblprefix}alangs VALUES ('3284','repurecord','信用记录','1264909424');
INSERT INTO {$tblprefix}alangs VALUES ('3285','repurelate','会员信用相关','1264909735');
INSERT INTO {$tblprefix}alangs VALUES ('3286','foundernobest','<b>[提示]内容管理请使用其它管理帐号</b>','1273198948');
INSERT INTO {$tblprefix}alangs VALUES ('3287','face_update','更新表情将加载image/face/下的所有表情资料。','1268734663');
INSERT INTO {$tblprefix}alangs VALUES ('3288','facerelate','表情相关','1268744172');
INSERT INTO {$tblprefix}alangs VALUES ('3289','faceadmin','表情管理','1268744207');
INSERT INTO {$tblprefix}alangs VALUES ('3290','updateface','加载新表情','1268793193');
INSERT INTO {$tblprefix}alangs VALUES ('3291','facetype','表情组','1268744896');
INSERT INTO {$tblprefix}alangs VALUES ('3292','facedir','表情路径','1268745083');
INSERT INTO {$tblprefix}alangs VALUES ('3293','facecode','表情代码','1268785397');
INSERT INTO {$tblprefix}alangs VALUES ('3294','deal_face','处理表情符号','1268790419');
INSERT INTO {$tblprefix}alangs VALUES ('3296','vmode3','多级联动','1268877711');
INSERT INTO {$tblprefix}alangs VALUES ('3297','vmode4','多级联动(ajax)','1268905696');
INSERT INTO {$tblprefix}alangs VALUES ('3298','agrelatecaid','请谨慎操作！！多选会影响某些查询效率，单选与多选间切换将更新数据库的大量数据。<br>多选转为单选时，将只保留第一个原有选择，且不可恢复。','1269088006');
INSERT INTO {$tblprefix}alangs VALUES ('3299','mrelatecaid','会员关联栏目的选择模式','1269050803');
INSERT INTO {$tblprefix}alangs VALUES ('3300','asmode','分类的选择模式','1272786767');
INSERT INTO {$tblprefix}alangs VALUES ('3301','msmode','关联会员的选择模式','1269072734');
INSERT INTO {$tblprefix}alangs VALUES ('3302','smax2','最多选2个','1269086409');
INSERT INTO {$tblprefix}alangs VALUES ('3303','smax3','最多选3个','1269086428');
INSERT INTO {$tblprefix}alangs VALUES ('3304','smax4','最多选4个','1269086446');
INSERT INTO {$tblprefix}alangs VALUES ('3305','smax5','最多选5个','1269086464');
INSERT INTO {$tblprefix}alangs VALUES ('3306','max_addno','文档附加内容页最大数量','1271821500');
INSERT INTO {$tblprefix}alangs VALUES ('3308','cnlistdefault','节点列表页静态文件名','1270730179');
INSERT INTO {$tblprefix}alangs VALUES ('3309','bklistdefault','节点备用页静态文件名','1270730217');
INSERT INTO {$tblprefix}alangs VALUES ('3310','hiddensinurl','以下文件名在url中隐藏','1270778003');
INSERT INTO {$tblprefix}alangs VALUES ('3311','aghiddensinurl','多个文件名之间用逗号分隔，除非全站只使用动态，否则请不要隐藏index.php','1273140708');
INSERT INTO {$tblprefix}alangs VALUES ('3312','abandcu','合辑与交互','1270802141');
INSERT INTO {$tblprefix}alangs VALUES ('3313','addnoset','静态时addno值','1272008368');
INSERT INTO {$tblprefix}alangs VALUES ('3314','agaddnos','留空则静态链接中addno采用系统默认值：内容页为空，附加页则为相应附加页序号。','1270806398');
INSERT INTO {$tblprefix}alangs VALUES ('3504','msgfollow','即将转入下面的操作！','1273105706');
INSERT INTO {$tblprefix}alangs VALUES ('3505','bkfollow','执行后续操作','1273107875');
INSERT INTO {$tblprefix}alangs VALUES ('3506','custom_1','自定参数1','1273195616');
INSERT INTO {$tblprefix}alangs VALUES ('3436','astaticset','文档页静态参数设置','1272008339');
INSERT INTO {$tblprefix}alangs VALUES ('3317','addnonum','附加页数量','1270826091');
INSERT INTO {$tblprefix}alangs VALUES ('3318','domain_admin','域名管理','1270993494');
INSERT INTO {$tblprefix}alangs VALUES ('3319','add_domain','添加域名','1270993517');
INSERT INTO {$tblprefix}alangs VALUES ('3320','domain','指向域名','1270995373');
INSERT INTO {$tblprefix}alangs VALUES ('3321','folder','系统路径','1270995350');
INSERT INTO {$tblprefix}alangs VALUES ('3322','isregular','是否正则','1270993747');
INSERT INTO {$tblprefix}alangs VALUES ('3323','edit_domain_list','编辑域名列表','1270994548');
INSERT INTO {$tblprefix}alangs VALUES ('3324','staticfomart','静态保存格式','1271065119');
INSERT INTO {$tblprefix}alangs VALUES ('3325','agcnstaticfomart','留空为默认格式，{$cndir}系统默认保存路径，{$page}分页页码，数字之间建议加上分隔符_或-连接。','1271065285');
INSERT INTO {$tblprefix}alangs VALUES ('3337','soninsearch','同时搜索子类','1271467549');
INSERT INTO {$tblprefix}alangs VALUES ('3327','agfrelates','不选则本类信息不关联任何栏目或分类','1271164660');
INSERT INTO {$tblprefix}alangs VALUES ('3328','frelates','信息关联栏目或类系','1271164754');
INSERT INTO {$tblprefix}alangs VALUES ('3330','frelatecaid','分类的选择方式','1271248840');
INSERT INTO {$tblprefix}alangs VALUES ('3331','cacc','类目选择','1271236447');
INSERT INTO {$tblprefix}alangs VALUES ('3332','fieldtitle','字段标题值','1271386778');
INSERT INTO {$tblprefix}alangs VALUES ('3333','resultnum','多个标题只列出前几个','1271407395');
INSERT INTO {$tblprefix}alangs VALUES ('3334','agresultnum','留空表示多个标题全部列出。','1271407501');
INSERT INTO {$tblprefix}alangs VALUES ('3335','sfield_name','内容来源字段英文标识','1271413048');
INSERT INTO {$tblprefix}alangs VALUES ('3336','agsfname','输入字段的英文标识，不能带$或[]。','1271413324');
INSERT INTO {$tblprefix}alangs VALUES ('3338','gather_timeout_err','采集超时或出错','1271490628');
INSERT INTO {$tblprefix}alangs VALUES ('3339','no_content_gather','没有采集到内容','1271490643');
INSERT INTO {$tblprefix}alangs VALUES ('3340','mcnode','会员节点','1271552689');
INSERT INTO {$tblprefix}alangs VALUES ('3341','mcnodeadmin','会员频道节点','1272897487');
INSERT INTO {$tblprefix}alangs VALUES ('3342','customnode','自定义节点','1271559940');
INSERT INTO {$tblprefix}alangs VALUES ('3343','mcn_max_addno','会员节点附加页最大数量','1271562654');
INSERT INTO {$tblprefix}alangs VALUES ('3344','nodetype','节点类型','1271572414');
INSERT INTO {$tblprefix}alangs VALUES ('3345','addmcnode','添加会员节点','1271573596');
INSERT INTO {$tblprefix}alangs VALUES ('3346','choosenode','选择成为节点','1271576480');
INSERT INTO {$tblprefix}alangs VALUES ('3347','mcnode_list','会员节点列表','1271580546');
INSERT INTO {$tblprefix}alangs VALUES ('3351','addp','附加页','1271589296');
INSERT INTO {$tblprefix}alangs VALUES ('3353','add_p','附','1271605647');
INSERT INTO {$tblprefix}alangs VALUES ('3352','agmcnstaticfomart','系统默认格式：{$cndir}/index页码_{$page}.html，页码为空,1,2，{$page}分页页码，{$cndir}位于member下的节点默认目录。','1272014453');
INSERT INTO {$tblprefix}alangs VALUES ('3354','staticperiod','静态更新周期(分钟)','1271609239');
INSERT INTO {$tblprefix}alangs VALUES ('3355','keepdnc','保持动态','1271609465');
INSERT INTO {$tblprefix}alangs VALUES ('3356','needset','我要设置','1271609623');
INSERT INTO {$tblprefix}alangs VALUES ('3357','ifstatic','是否生成静态','1271612230');
INSERT INTO {$tblprefix}alangs VALUES ('3358','staticsys','系统默认','1271918689');
INSERT INTO {$tblprefix}alangs VALUES ('3359','mcataslist','会员类目列表','1271646467');
INSERT INTO {$tblprefix}alangs VALUES ('3360','m_index_tpl','会员频道首页模板','1271671958');
INSERT INTO {$tblprefix}alangs VALUES ('3361','grouplist','会员组列表','1271701576');
INSERT INTO {$tblprefix}alangs VALUES ('3362','matypelist','档案类型列表','1271701626');
INSERT INTO {$tblprefix}alangs VALUES ('3363','mscnode','单个会员节点','1271701737');
INSERT INTO {$tblprefix}alangs VALUES ('3364','point_mcntype','指定会员节点类型','1271731160');
INSERT INTO {$tblprefix}alangs VALUES ('3365','directidmcn','指定节点属性id','1271731257');
INSERT INTO {$tblprefix}alangs VALUES ('3366','mcn_static','会员频道静态','1271746713');
INSERT INTO {$tblprefix}alangs VALUES ('3371','siteidx','站点首页','1271747724');
INSERT INTO {$tblprefix}alangs VALUES ('3370','choidxtp','选择首页类型','1271747704');
INSERT INTO {$tblprefix}alangs VALUES ('3372','mcnidx','会员频道首页','1271747740');
INSERT INTO {$tblprefix}alangs VALUES ('3373','cn_max_addno','类目节点附加页最大数量','1271812738');
INSERT INTO {$tblprefix}alangs VALUES ('3374','memcert_add','认证类型添加','1270888000');
INSERT INTO {$tblprefix}alangs VALUES ('3375','memcert_title','认证类型名称','1270888029');
INSERT INTO {$tblprefix}alangs VALUES ('3376','memcert_modify','认证编辑','1271121428');
INSERT INTO {$tblprefix}alangs VALUES ('3377','memcert_level','认证权重','1271122207');
INSERT INTO {$tblprefix}alangs VALUES ('3378','memcert_level_tip','权重大的认证后不能再做小于它权重的认证','1271320563');
INSERT INTO {$tblprefix}alangs VALUES ('3379','memcert_and_checked','认证后审核会员','1271145334');
INSERT INTO {$tblprefix}alangs VALUES ('3380','memcert_admin','认证类型管理','1271123571');
INSERT INTO {$tblprefix}alangs VALUES ('3381','memcert_delete','删除选中认证类型','1271124479');
INSERT INTO {$tblprefix}alangs VALUES ('3382','memcert_remark','认证内容说明','1271320034');
INSERT INTO {$tblprefix}alangs VALUES ('3383','memcert_icon','认证表示图标','1271319960');
INSERT INTO {$tblprefix}alangs VALUES ('3384','general_cert','其他认证','1271140652');
INSERT INTO {$tblprefix}alangs VALUES ('3385','email_cert','邮箱认证','1271140680');
INSERT INTO {$tblprefix}alangs VALUES ('3386','mobile_cert','手机认证','1271140691');
INSERT INTO {$tblprefix}alangs VALUES ('3387','memcert_special','特殊字段','1271140834');
INSERT INTO {$tblprefix}alangs VALUES ('3388','memcert_fields','其他认证字段','1271320349');
INSERT INTO {$tblprefix}alangs VALUES ('3389','memcert_special_tip','特殊处理字段，如果没有或不需要请留空。请保证所选会员模型有这个字段','1271320467');
INSERT INTO {$tblprefix}alangs VALUES ('3390','memcert_fields_tip','多个用逗号分割。请保证所选会员模型有这些字段','1271320563');
INSERT INTO {$tblprefix}alangs VALUES ('3391','memcert_list','认证申请管理','1271145261');
INSERT INTO {$tblprefix}alangs VALUES ('3392','memcert_check','会员认证审核','1271145293');
INSERT INTO {$tblprefix}alangs VALUES ('3393','memcert_manage','认证管理','1271206805');
INSERT INTO {$tblprefix}alangs VALUES ('3394','memcert_info','认证内容','1271215676');
INSERT INTO {$tblprefix}alangs VALUES ('3395','memcert_mchid','允许的会员模型','1271228911');
INSERT INTO {$tblprefix}alangs VALUES ('3396','memcert_email_field','邮箱字段','1271320232');
INSERT INTO {$tblprefix}alangs VALUES ('3397','memcert_mobile_field','手机字段','1271320059');
INSERT INTO {$tblprefix}alangs VALUES ('3398','mobileset','手机设置','1271573019');
INSERT INTO {$tblprefix}alangs VALUES ('3399','mob_mail','手机和邮箱','1271573006');
INSERT INTO {$tblprefix}alangs VALUES ('3400','msgcodemode','认证模式','1271573043');
INSERT INTO {$tblprefix}alangs VALUES ('3401','msgcode2','自动模式','1271573062');
INSERT INTO {$tblprefix}alangs VALUES ('3402','msgcode1','手动模式','1271573074');
INSERT INTO {$tblprefix}alangs VALUES ('3403','msgcode0','关闭认证','1271573090');
INSERT INTO {$tblprefix}alangs VALUES ('3404','msggate','网关选择','1271573129');
INSERT INTO {$tblprefix}alangs VALUES ('3405','msggate1','E商发 [<a href=\"http://sms.eshang8.cn\" target=\"_blank\">官网</a>]','1271573569');
INSERT INTO {$tblprefix}alangs VALUES ('3406','msgcode_sp1','用户名','1271573289');
INSERT INTO {$tblprefix}alangs VALUES ('3407','msgcode_pw1','密钥','1271573500');
INSERT INTO {$tblprefix}alangs VALUES ('3408','msggate2','移动商务应用中心 [<a href=\"http://www.winic.org\" target=\"_blank\">官网</a>]','1271573569');
INSERT INTO {$tblprefix}alangs VALUES ('3409','msgcode_sp2','用户名','1271573600');
INSERT INTO {$tblprefix}alangs VALUES ('3410','msgcode_pw2','密码','1271573636');
INSERT INTO {$tblprefix}alangs VALUES ('3411','msgcode_sms','短信内容','1271573655');
INSERT INTO {$tblprefix}alangs VALUES ('3412','msgcode_sms_tip','注意内容长度限制，使用%s代替确认码。例：<br/>\r\n您的确认码为%s。本信息自动发送，请勿回复。','1271573842');
INSERT INTO {$tblprefix}alangs VALUES ('3413','msgcode_msg','提示内容','1271573910');
INSERT INTO {$tblprefix}alangs VALUES ('3414','msgcode_msg_tip','使用%s代替确认码。例：<br/>\r\n请编辑内容%s发送到188****8888，稍候管理员确认。','1271574069');
INSERT INTO {$tblprefix}alangs VALUES ('3415','mobiletest','短信测试','1271574162');
INSERT INTO {$tblprefix}alangs VALUES ('3416','memcert_modify_cert','更改并认证','1271672704');
INSERT INTO {$tblprefix}alangs VALUES ('3417','msg_code','短信确认码','1271726598');
INSERT INTO {$tblprefix}alangs VALUES ('3418','memcert_ok','已认证','1271726789');
INSERT INTO {$tblprefix}alangs VALUES ('3419','mcnindexcircle','会员频道节点静态更新周期(分钟)','1271820226');
INSERT INTO {$tblprefix}alangs VALUES ('3420','wap_set','WAP设置','1270190589');
INSERT INTO {$tblprefix}alangs VALUES ('3421','wap_open','开启','1270190710');
INSERT INTO {$tblprefix}alangs VALUES ('3422','wap_status','WAP状态','1270190741');
INSERT INTO {$tblprefix}alangs VALUES ('3423','wap_close','关闭','1270190810');
INSERT INTO {$tblprefix}alangs VALUES ('3424','wap_charset','汉字编码','1270190866');
INSERT INTO {$tblprefix}alangs VALUES ('3425','wap_domain','绑定域名','1270190926');
INSERT INTO {$tblprefix}alangs VALUES ('3426','wap_param','WAP参数','1270197204');
INSERT INTO {$tblprefix}alangs VALUES ('3427','wap_lang','WAP语言包','1270198386');
INSERT INTO {$tblprefix}alangs VALUES ('3428','add_wlang','添加WAP语言包','1270198725');
INSERT INTO {$tblprefix}alangs VALUES ('3429','lang_ename','语言包标识','1270198789');
INSERT INTO {$tblprefix}alangs VALUES ('3430','lang_content','语言包内容','1270198819');
INSERT INTO {$tblprefix}alangs VALUES ('3431','wlang_admin','WAP语言包管理','1270199003');
INSERT INTO {$tblprefix}alangs VALUES ('3432','edit_wlang','编辑WAP语言包','1270280593');
INSERT INTO {$tblprefix}alangs VALUES ('3433','waptpl','WAP模板','1271907828');
INSERT INTO {$tblprefix}alangs VALUES ('3434','w_index_tpl','WAP首页模板','1271909267');
INSERT INTO {$tblprefix}alangs VALUES ('3435','wtemplate','WAP模板','1271918823');
INSERT INTO {$tblprefix}alangs VALUES ('3437','spread','推广','1268641605');
INSERT INTO {$tblprefix}alangs VALUES ('3438','spread_url','普通推广','1272462089');
INSERT INTO {$tblprefix}alangs VALUES ('3439','spread_switch','启用推广','1268642477');
INSERT INTO {$tblprefix}alangs VALUES ('3440','spread_maxlimit','每日最多积分','1268643227');
INSERT INTO {$tblprefix}alangs VALUES ('3441','spread_record','记录推广日志','1268643270');
INSERT INTO {$tblprefix}alangs VALUES ('3442','use_record_limit','0表示不限制。确保能被“奖励积分”整除，否则结果可能不正常','1269068987');
INSERT INTO {$tblprefix}alangs VALUES ('3443','spread_idx','首页推广','1269046181');
INSERT INTO {$tblprefix}alangs VALUES ('3444','spread_reg','注册推广','1269046199');
INSERT INTO {$tblprefix}alangs VALUES ('3445','tplpermi_set','浏览权限设置','1272183177');
INSERT INTO {$tblprefix}alangs VALUES ('3446','agtplpermi_set','在标识模板中以[#pm#]分隔，前部分为有权限显示模板，后部分为无权限显示模板。','1272190293');
INSERT INTO {$tblprefix}alangs VALUES ('3449','fromfunc','字串由函数生成','1272218231');
INSERT INTO {$tblprefix}alangs VALUES ('3456','novu','关闭虚拟静态','1272281198');
INSERT INTO {$tblprefix}alangs VALUES ('3451','mspacedir','个人空间目录','1272249979');
INSERT INTO {$tblprefix}alangs VALUES ('3452','memberdir','会员频道目录','1272250005');
INSERT INTO {$tblprefix}alangs VALUES ('3471','spread_js_tip','比如首页推广：在首页模板里添加这段JS，然后用{$cms_abs}uid={mname}访问首页就会自动统计推广','1274240242');
INSERT INTO {$tblprefix}alangs VALUES ('3458','mlclass','首序分类描述','1272456928');
INSERT INTO {$tblprefix}alangs VALUES ('3454','agmspacedir','个人空间目录，不要带/。{$mspacedir}调用目录，{$mspaceurl}调用url。','1272250539');
INSERT INTO {$tblprefix}alangs VALUES ('3455','agmemberdir','会员频道目录，不要带/。{$memberdir}调用目录，{$memberurl}调用url。','1272250590');
INSERT INTO {$tblprefix}alangs VALUES ('3459','disable','禁用','1272342166');
INSERT INTO {$tblprefix}alangs VALUES ('3460','pptset','通行证设置','1272342340');
INSERT INTO {$tblprefix}alangs VALUES ('3461','pptmode','将本系统作为','1272342416');
INSERT INTO {$tblprefix}alangs VALUES ('3462','server','服务端','1272342465');
INSERT INTO {$tblprefix}alangs VALUES ('3463','client','客户端','1272342498');
INSERT INTO {$tblprefix}alangs VALUES ('3464','pptkey','通行证密钥','1272342631');
INSERT INTO {$tblprefix}alangs VALUES ('3465','cncfg','节点配置','1272437259');
INSERT INTO {$tblprefix}alangs VALUES ('3467','incbelow','包含以下：','1272442340');
INSERT INTO {$tblprefix}alangs VALUES ('3468','all_3_catas','全部四级栏目','1272445547');
INSERT INTO {$tblprefix}alangs VALUES ('3469','nobelow','排除以下：','1272462142');
INSERT INTO {$tblprefix}alangs VALUES ('3470','spread_js_mode','JS调用','1272462197');
INSERT INTO {$tblprefix}alangs VALUES ('3472','cnopmode0','修改配置中设置','1272463358');
INSERT INTO {$tblprefix}alangs VALUES ('3473','cnopmode1','在原配置中添加','1272463358');
INSERT INTO {$tblprefix}alangs VALUES ('3474','cnopmode2','从原配置中移除','1272463386');
INSERT INTO {$tblprefix}alangs VALUES ('3475','partop','局部修改配置：','1272463496');
INSERT INTO {$tblprefix}alangs VALUES ('3476','agpartop','仅当前类系存在于选中配置中，且为手动选择分类时有效。修改配置不自动更新节点。','1272584668');
INSERT INTO {$tblprefix}alangs VALUES ('3477','copy0','拷贝','1272464970');
INSERT INTO {$tblprefix}alangs VALUES ('3478','pointmid','指定会员id(手动模式有效)','1272505159');
INSERT INTO {$tblprefix}alangs VALUES ('3479','acrossleve1','单层节点','1272515275');
INSERT INTO {$tblprefix}alangs VALUES ('3480','wapcode','WAP代码','1272549903');
INSERT INTO {$tblprefix}alangs VALUES ('3481','updatecnode','更新配置中节点','1272595145');
INSERT INTO {$tblprefix}alangs VALUES ('3484','emode','分类的期限设置模式','1272788084');
INSERT INTO {$tblprefix}alangs VALUES ('3485','emode0','不设置期限','1272787593');
INSERT INTO {$tblprefix}alangs VALUES ('3486','emode1','设定期限(选添)','1272787676');
INSERT INTO {$tblprefix}alangs VALUES ('3487','emode2','设定期限(必添)','1272787703');
INSERT INTO {$tblprefix}alangs VALUES ('3488','agemode','请谨慎操作！从支持期限到不支持会丢失原有分类的期限数据。','1272787883');
INSERT INTO {$tblprefix}alangs VALUES ('3489','enddate1','截止日期','1272846161');
INSERT INTO {$tblprefix}alangs VALUES ('3491','autoinit','注册自动成为本组会员','1272974244');
INSERT INTO {$tblprefix}alangs VALUES ('3492','agautoinit','当会员可以自动进入多个组中，选择优先级高的一个会员组。','1272974354');
INSERT INTO {$tblprefix}alangs VALUES ('3493','batch_catalogs','批量添加栏目','1272957275');
INSERT INTO {$tblprefix}alangs VALUES ('3494','batch_catalog_numbers','批量添加栏目数量','1272957300');
INSERT INTO {$tblprefix}alangs VALUES ('3495','catalog_diffitems','需要分别设置的项','1273219728');
INSERT INTO {$tblprefix}alangs VALUES ('3496','same_setting','相同设置','1272962232');
INSERT INTO {$tblprefix}alangs VALUES ('3497','batch_catalog_item','批量添加 - 栏目','1272962267');
INSERT INTO {$tblprefix}alangs VALUES ('3498','auto_pinyin','自动拼音','1272976838');
INSERT INTO {$tblprefix}alangs VALUES ('3499','batch','批量','1272984930');
INSERT INTO {$tblprefix}alangs VALUES ('3500','batch_coclasses','批量分类添加','1273020181');
INSERT INTO {$tblprefix}alangs VALUES ('3501','batch_coclass_numbers','批量添加分类数量','1273020273');
INSERT INTO {$tblprefix}alangs VALUES ('3502','coclass_diffitems','需要分别设置的项','1273219728');
INSERT INTO {$tblprefix}alangs VALUES ('3503','batch_coclass_item','批量添加 - 分类','1273020347');
INSERT INTO {$tblprefix}alangs VALUES ('3507','custom_2','自定参数2','1273195631');
INSERT INTO {$tblprefix}alangs VALUES ('3508','agcustom_1','参数可为0-255长度的文本，用于系统自定义扩展。','1273196299');
INSERT INTO {$tblprefix}alangs VALUES ('3509','jumpurl','跳转URL','1273199632');
INSERT INTO {$tblprefix}alangs VALUES ('3510','agjumpurl','请输入以http://开头的完整url。指定跳转后，所有该文档的url均为该地址。','1273203413');
INSERT INTO {$tblprefix}alangs VALUES ('3511','foundercontent','开放创始人内容管理区块','1273206079');
INSERT INTO {$tblprefix}alangs VALUES ('3512','keydowm_setscreen','F11/Ctrl+F11 更便捷','1273217545');
INSERT INTO {$tblprefix}alangs VALUES ('3513','agfoundercontent','强烈建议不用创始人帐号管理常规内容及插件信息，请另行创建管理员进行日常管理。','1273251988');
INSERT INTO {$tblprefix}alangs VALUES ('3514','last_patch','系统最近更新：%s','1273344629');
INSERT INTO {$tblprefix}alangs VALUES ('3515','chpmid','内容审核权限设置','1273354450');
INSERT INTO {$tblprefix}alangs VALUES ('3516','noatchk','不自动审核','1273348188');
INSERT INTO {$tblprefix}alangs VALUES ('3517','myid','我的..','1273353065');
INSERT INTO {$tblprefix}alangs VALUES ('3518','nomyid','不限我的..','1273353828');
INSERT INTO {$tblprefix}alangs VALUES ('3519','myadd','我发布的','1273353865');
INSERT INTO {$tblprefix}alangs VALUES ('3520','mycheck','我审核的','1273353882');
INSERT INTO {$tblprefix}alangs VALUES ('3521','cncfgtpl','配置中节点默认参数','1273647380');
INSERT INTO {$tblprefix}alangs VALUES ('3522','tplsmode','以上默认值的执行模式','1273626245');
INSERT INTO {$tblprefix}alangs VALUES ('3523','tplsmode0','与节点无关','1273633462');
INSERT INTO {$tblprefix}alangs VALUES ('3524','tplsmode1','仅新增节点设为默认值','1273626408');
INSERT INTO {$tblprefix}alangs VALUES ('3525','tplsmode2','所有节点设为默认值','1273626617');
INSERT INTO {$tblprefix}alangs VALUES ('3527','checkpm','内容审核权限','1273744347');
INSERT INTO {$tblprefix}alangs VALUES ('3528','check_1','一审','1273849190');
INSERT INTO {$tblprefix}alangs VALUES ('3529','check_2','二审','1273849173');
INSERT INTO {$tblprefix}alangs VALUES ('3530','check_3','三审','1273849173');
INSERT INTO {$tblprefix}alangs VALUES ('3531','check_4','直审','1273849109');
INSERT INTO {$tblprefix}alangs VALUES ('3532','chklevel','需要经过几级审核','1273745574');
INSERT INTO {$tblprefix}alangs VALUES ('3533','uncheck_3','解三审','1273887063');
INSERT INTO {$tblprefix}alangs VALUES ('3534','uncheck_2','解二审','1273887063');
INSERT INTO {$tblprefix}alangs VALUES ('3535','uncheck_1','解一审','1273887063');
INSERT INTO {$tblprefix}alangs VALUES ('3536','max_chklv','内容审核的最大级数','1273809273');
INSERT INTO {$tblprefix}alangs VALUES ('3537','agmax_chklv','修改此值以后，请设置管理后台权限方案中的审核设置。如果数值变大，需要修改文档模型中的设置才能将审核级数升级。','1273845740');
INSERT INTO {$tblprefix}alangs VALUES ('3547','maptype','地图类型','1274360228');
INSERT INTO {$tblprefix}alangs VALUES ('3541','myneedchk','我要审的','1273883107');
INSERT INTO {$tblprefix}alangs VALUES ('3546','map','地图','1274347684');
INSERT INTO {$tblprefix}alangs VALUES ('3548','mapsearch1','距离模式(千米)','1274365166');
INSERT INTO {$tblprefix}alangs VALUES ('3549','mapsearch2','经纬度模式(度)','1274365134');
INSERT INTO {$tblprefix}alangs VALUES ('3550','agmapvdefault','输入初始定位位置：纬度,经度','1274365366');
INSERT INTO {$tblprefix}alangs VALUES ('3551','map_view','地图初始显示比例','1274435593');
INSERT INTO {$tblprefix}alangs VALUES ('3552','agmap_view','请输入0-19的整数，数字越大越精确。','1274435665');
INSERT INTO {$tblprefix}alangs VALUES ('3553','maxvote','最多允许添加几个投票','1274496665');
INSERT INTO {$tblprefix}alangs VALUES ('3554','maxoption','每个投票最多几个选项','1274496706');
INSERT INTO {$tblprefix}alangs VALUES ('3555','moveup','上移','1274668406');
INSERT INTO {$tblprefix}alangs VALUES ('3556','movedown','下移','1274668425');
INSERT INTO {$tblprefix}alangs VALUES ('3557','freevote','独立投票','1274769376');
INSERT INTO {$tblprefix}alangs VALUES ('3558','vote_type','投票类型','1274769486');
INSERT INTO {$tblprefix}alangs VALUES ('3559','votesource','投票来源数组名称','1274805278');
INSERT INTO {$tblprefix}alangs VALUES ('3560','agvotesource','字段中单个投票来源于投票列表(字段类型)中输出数组，如所在投票列表标识定义的数组名称为v，则此处输入v即可。','1274805515');
INSERT INTO {$tblprefix}alangs VALUES ('3561','soucerid','内容来源记录id','1274862033');
INSERT INTO {$tblprefix}alangs VALUES ('3562','soucefname','来源投票字段名称','1274862231');

DROP TABLE IF EXISTS {$tblprefix}albums;
CREATE TABLE {$tblprefix}albums (
  abid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  chid smallint(6) unsigned NOT NULL default '0',
  pid mediumint(8) unsigned NOT NULL default '0',
  pchid smallint(6) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  volid smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (abid),
  KEY aid (aid),
  KEY pid (pid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}amconfigs;
CREATE TABLE {$tblprefix}amconfigs (
  amcid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  sid smallint(6) unsigned NOT NULL default '0',
  caids text NOT NULL,
  fcaids varchar(255) NOT NULL,
  mchids varchar(255) NOT NULL,
  menus text NOT NULL,
  funcs text NOT NULL,
  cuids varchar(255) NOT NULL,
  mcuids varchar(255) NOT NULL,
  matids varchar(255) NOT NULL,
  abcustom tinyint(1) unsigned NOT NULL default '0',
  fbcustom tinyint(1) unsigned NOT NULL default '0',
  mbcustom tinyint(1) unsigned NOT NULL default '0',
  anodes text NOT NULL,
  fnodes text NOT NULL,
  mnodes text NOT NULL,
  checks varchar(10) NOT NULL default '-1',
  PRIMARY KEY  (amcid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}amsgs;
CREATE TABLE {$tblprefix}amsgs (
  lid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  content text NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid)
) TYPE=MyISAM AUTO_INCREMENT=652;

INSERT INTO {$tblprefix}amsgs VALUES ('5','swordillegal','被关联词不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('6','pleinpreaurl','请输入关联链接','0');
INSERT INTO {$tblprefix}amsgs VALUES ('7','swoaddfinish','被关联词添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('8','hotkeystfunclo','热门关键词统计功能已关闭','0');
INSERT INTO {$tblprefix}amsgs VALUES ('3','no_apermission','您没有此项目的管理权限！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('4','swmodfin','被关联词修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('9','keyimpfinish','关键词输入完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('10','msiteadmitem','主站管理项目!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('11','pleaddvotcoc','请添加投票分类','0');
INSERT INTO {$tblprefix}amsgs VALUES ('12','datamissing','资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('13','voteaddfinish','投票添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('14','votmodfin','投票修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('15','poivotenoe','指定的投票不存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('16','userisforbid','所在的屏蔽组禁止了此功能','0');
INSERT INTO {$tblprefix}amsgs VALUES ('17','voteedifin','投票编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('18','pleinpopttit','请输入选项标题','0');
INSERT INTO {$tblprefix}amsgs VALUES ('19','optaddfin','选项添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('20','cocledifin','分类编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('21','votcocaddfin','投票分类添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('22','choosealtype','请指定正确的合辑类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('23','coclwitvotcandel','分类没有相关联的投票才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('24','cocdelefini','分类删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('25','selectaltrec','请选择变更记录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('26','useraltopefin','会员组变更操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('27','choosealtrec','请指定正确的变更记录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('28','useraltrecmodfin','会员组变更记录修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('29','filetypeaddfinish','文件类型添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('30','pleinpusutitandurl','请输入常用链接标题与链接!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('31','usuaddfin','常用链接添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('32','usuedifin','常用链接编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('33','chooseusu','请指定正确的常用链接','0');
INSERT INTO {$tblprefix}amsgs VALUES ('34','usuamodifin','常用链接修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('35','filetypeeditfinish','文件类型编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('36','inpusecoctit','请输入用户链接分类标题!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('37','usecocmodfin','用户链接分类修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('38','chooseuserurl','请指定正确的用户链接','0');
INSERT INTO {$tblprefix}amsgs VALUES ('39','inpusetiau','请输入用户链接标题与链接!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('40','marcmodifysuccess','会员档案修改成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('41','poiusebelcoc','请指定用户链接所属分类!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('42','matypeaddsuccess','会员档案类型添加成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('43','usermodfin','用户链接修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('44','usercocwitsoncoccandel','用户链接分类没有相关联的子分类才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('45','matypesetsuccess','会员档案类型设置成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('46','mafieldaddsuccess','会员档案字段添加成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('47','usercocwitusecandel','用户链接分类没有相关联的用户链接才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('48','matypedelsuccess','会员档案类型删除成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('49','usecocdelfin','用户链接分类删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('50','mafieldmodifysuccess','会员档案字段编辑成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('51','userurldelfin','用户链接删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('52','deluserasmatype','请先删除与本类型相关的所有会员档案','0');
INSERT INTO {$tblprefix}amsgs VALUES ('53','inputmatypename','请输入会员档案类型名称','0');
INSERT INTO {$tblprefix}amsgs VALUES ('54','mchanneleditfinish','会员模型编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('55','mchanneladdfinish','会员模型添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('56','mchannelcopyfinish','会员模型复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('57','usercocaddfin','用户链接分类添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('58','useraddfin','用户链接添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('59','channelmodifyfinish','模型修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('60','useedifin','用户链接编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('61','chooseusecoc','请指定正确的用户链接分类','0');
INSERT INTO {$tblprefix}amsgs VALUES ('62','fieldaddfinish','字段添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('63','chooseuserpara','请指定正确的用户链接参数!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('64','mchanneldeletefinish','会员模型删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('65','nopermission','没有指定项目的操作权限!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('66','fieldeditfinish','字段编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('67','fieldmodifyfinish','字段修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('68','schannelcannotdelete','系统模型不能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('69','delchannelnomember','模型没有相关联的会员才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('70','choosememberchannel','请指定正确的会员模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('71','choosefield','请指定正确的字段','0');
INSERT INTO {$tblprefix}amsgs VALUES ('72','choosegroup','请指定正确的会员组体系','0');
INSERT INTO {$tblprefix}amsgs VALUES ('73','userdatamiss','会员组资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('74','itemmodifyfinish','项目修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('75','usergroupaddfin','会员组添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('76','usergroupmodfin','会员组修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('77','usercopyfin','会员组复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('78','usereditfin','会员组编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('79','mcommucopyfinish','会员交互项目复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('80','selectarchive','请选择文档','0');
INSERT INTO {$tblprefix}amsgs VALUES ('81','chooseitem','请指定正确的项目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('82','operating','文件操作正在进行中...<br>共 %s 页，正在处理第 %s 页<br><br>%s>>中止当前操作%s','0');
INSERT INTO {$tblprefix}amsgs VALUES ('83','attopefin','附件操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('84','subdatamiss','子站资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('85','substadirill','子站静态目录不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('86','nowcresubstadir','无法生成子站静态目录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('87','msitrasubfin','主站转为子站完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('88','msitrasubfai','主站转为子站失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('89','delmsiarcoralb','请删除主站的文档或合辑','0');
INSERT INTO {$tblprefix}amsgs VALUES ('90','delmsicat','请删除主站的栏目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('91','inputcommuname','请输入交互项目名称！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('92','hosturlillegal','主机URL不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('93','subtemdirill','子站模板目录不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('94','websitesetfinish','网站设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('95','nowcresubtemdir','无法生成子站模板目录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('96','tpldirillegal','模板目录不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('97','subaddfin','子站添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('98','paysetfinish','商务支付设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('99','subaddfai','子站添加失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('100','mailsetfinish','邮件设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('101','subopefin','子站操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('102','pointfieldtype','请指定字段类型!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('103','delmsicno','请删除主站的节点','0');
INSERT INTO {$tblprefix}amsgs VALUES ('104','delmsicnocon','请删除主站的节点配置','0');
INSERT INTO {$tblprefix}amsgs VALUES ('105','delmsialt','请删除主站的合辑类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('106','delmsiisopag','请删除主站的独立页面','0');
INSERT INTO {$tblprefix}amsgs VALUES ('107','delmsigatrec','请删除主站的采集记录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('108','delmsigatmiss','请删除主站的采集任务','0');
INSERT INTO {$tblprefix}amsgs VALUES ('109','delmsgatcha','请删除主站的采集模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('110','subtramsifin','子站转为主站完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('111','subwitarccandel','子站没有相关联的文档才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('112','subwitcatcandel','子站没有相关联的栏目才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('113','subwitcatcnocandel','子站没有相关联的类目节点才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('114','subswitcnoconcandel','子站没有相关联的节点配置才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('115','membernameillegal','会员名称不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('116','subwitltdel','子站没有相关联的合辑类型才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('117','subitisopagdel','子站没有相关联的独立页面才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('118','membernamerepeat','会员名称重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('119','subwitgatmisdel','子站没有相关联的采集任务才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('120','memberemailillegal','会员Email不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('121','subwitgathadel','子站没有相关联的采集模型才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('122','erroroperate','错误操作','0');
INSERT INTO {$tblprefix}amsgs VALUES ('123','subitgatrecdel','子站没有相关联的采集记录才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('124','subdelfin','子站删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('125','memberaddfinish','会员添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('126','membermodifyfinish','会员修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('127','donrepoper','请不要重复操作!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('128','subsetupcancel','安装过程正在取消','0');
INSERT INTO {$tblprefix}amsgs VALUES ('129','mnamelengthillegal','会员名称长度不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('130','operatesuc','操作成功!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('131','memberpwdillegal','会员密码不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('132','undosuc','撤消成功!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('133','upinssubinidataupl','请上传需要安装的子站原始资料!<br /><br />资料上传路径:%s','0');
INSERT INTO {$tblprefix}amsgs VALUES ('134','memberaddfailed','会员添加失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('135','choosemember','请指定正确的会员','0');
INSERT INTO {$tblprefix}amsgs VALUES ('136','invoperate','无效操作!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('137','cannotmodifyfounder','不能修改创始人资料','0');
INSERT INTO {$tblprefix}amsgs VALUES ('138','telcopyerror','模板复制错误!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('139','memberdelfinish','会员删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('140','memberoperatefinish','会员操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('141','recdelfin','记录删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('142','selectsubcon','请选择订阅内容','0');
INSERT INTO {$tblprefix}amsgs VALUES ('143','selectoperateitem','请选择操作项目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('144','selectmember','请选择会员','0');
INSERT INTO {$tblprefix}amsgs VALUES ('145','noissuepermission','没有 %s 的发表权限','0');
INSERT INTO {$tblprefix}amsgs VALUES ('146','calbumcoverchannel','请指定正确的合辑封面模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('147','choosecatalog','请指定正确的栏目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('148','nocurcatalogpermi','当前用户没有指定栏目的发表权限','0');
INSERT INTO {$tblprefix}amsgs VALUES ('149','subdelsuc','订阅删除成功!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('150','catalogcantadd','%s 栏目不能添加 %s','0');
INSERT INTO {$tblprefix}amsgs VALUES ('151','fbd_caids','指定栏目的后台管理权限被限制 !','0');
INSERT INTO {$tblprefix}amsgs VALUES ('152','addalbumfailed','添加合辑失败!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('153','choarcpaty','请选择文档页面类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('154','albumaddfinish','合辑添加完成!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('155','albumeditfinish','合辑编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('156','staopefin','静态操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('157','albumcopyseditfinish','合辑副本编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('158','chocatpagty','请选择类目页面类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('159','selectalbum','请选择合辑','0');
INSERT INTO {$tblprefix}amsgs VALUES ('160','chocatcno','请选择类目节点','0');
INSERT INTO {$tblprefix}amsgs VALUES ('161','catcnoopefin','类目节点操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('162','inddeafin','首页处理完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('163','setalbumfinish','归辑操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('164','pagemodfin','页面修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('165','exitalbumfinish','退出合辑操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('166','sptplnoexist','没有定义模板或模板不存在!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('167','temconnot','模板内容不能为空','0');
INSERT INTO {$tblprefix}amsgs VALUES ('168','tplerrsave','模板保存时发生错误','0');
INSERT INTO {$tblprefix}amsgs VALUES ('169','choosealbum','请指定正确的合辑','0');
INSERT INTO {$tblprefix}amsgs VALUES ('170','tplmodfin','模板修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('171','albumadminfinish','合辑管理完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('172','splmodfin','功能语言修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('173','sitmodfin','Sitemap修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('174','choosesite','请指定正确的Sitemap','0');
INSERT INTO {$tblprefix}amsgs VALUES ('175','aboveralbum','完结合辑','0');
INSERT INTO {$tblprefix}amsgs VALUES ('176','nobaidunews','未发现已设置Sitemap新闻内容字段的文档模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('177','selectcatg','请选择栏目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('178','noarcoralbumload','没有可以加载的文档或合辑','0');
INSERT INTO {$tblprefix}amsgs VALUES ('179','selectcha','请选择模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('180','confirmselect','请选择','0');
INSERT INTO {$tblprefix}amsgs VALUES ('181','sitsetfin','Sitemap设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('182','sitemapclo','Sitemap已关闭','0');
INSERT INTO {$tblprefix}amsgs VALUES ('183','sitcrefin','Sitemap生成完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('184','shiaddfin','送货方式添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('185','shimodfin','送货方式修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('186','promodfin','方案修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('187','proaddfin','方案添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('188','fileextill','文件扩展名不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('189','fileextrep','文件扩展名重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('190','remproedifin','远程方案编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('191','prodatamis','方案资料missiong','0');
INSERT INTO {$tblprefix}amsgs VALUES ('192','piclisopefin','举报列表操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('193','noexrecord','不存在记录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('194','syscacreffin','系统缓存刷新完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('195','albumfinish','合辑操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('196','selectgoods','请选择商品','0');
INSERT INTO {$tblprefix}amsgs VALUES ('197','goolisopefin','商品列表操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('198','updateclosed','系统关闭了更新管制功能及更新副本功能！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('199','definechannelp','请定义模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('200','altypeaddfinish','合辑类型添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('201','altypesetfinish','合辑类型设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('202','altypenoarcoralbumdel','合辑类型没有相关联的文档或合辑才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('203','altypedelfinish','合辑类型删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('204','bapdatamiss','后台方案资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('205','bapaddfinish','后台方案添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('206','bapmodifyfinish','后台方案修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('207','chooseadminbap','请指定正确的管理后台方案','0');
INSERT INTO {$tblprefix}amsgs VALUES ('208','adminbapsetfinish','管理后台方案设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('209','pmmiss','短信资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('210','pmsendfin','短信发送完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('211','pmclearfin','短信清除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('212','amsgeditfinish','后台提示编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('213','inpplanam','请输入播放器名称','0');
INSERT INTO {$tblprefix}amsgs VALUES ('214','amsgaddfinish','后台提示添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('215','playaddfin','播放器添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('216','amsgmodifyfinish','后台提示修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('217','playedifin','播放器编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('218','enameillegal','标识不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('219','choosepla','请指定正确的播放器','0');
INSERT INTO {$tblprefix}amsgs VALUES ('220','enamerepeat','标识重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('221','inpplatem','请输入播放器模板','0');
INSERT INTO {$tblprefix}amsgs VALUES ('222','playmodfin','播放器修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('223','chooseamsg','请指定正确的后台提示文字','0');
INSERT INTO {$tblprefix}amsgs VALUES ('224','selectpayrec','请选择支付记录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('225','selectanswer','请选择答案','0');
INSERT INTO {$tblprefix}amsgs VALUES ('226','casvadmopefin','现金充值管理操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('227','answerlistfinish','答案列表操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('228','choosepay','请指定正确的支付','0');
INSERT INTO {$tblprefix}amsgs VALUES ('229','answernoexist','指定的答案不存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('230','choosepayrec','请指定正确的支付记录','0');
INSERT INTO {$tblprefix}amsgs VALUES ('231','inppayamo','请输入支付数量','0');
INSERT INTO {$tblprefix}amsgs VALUES ('232','questionclosed','问题已关闭','0');
INSERT INTO {$tblprefix}amsgs VALUES ('233','inputanswercontent','请输入回答内容','0');
INSERT INTO {$tblprefix}amsgs VALUES ('234','paymesmodfin','支付信息修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('235','answerovermin','答案内容太少','0');
INSERT INTO {$tblprefix}amsgs VALUES ('236','selectorder','请选择订单','0');
INSERT INTO {$tblprefix}amsgs VALUES ('237','answermodifyfinish','答案修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('238','choosechannel','请指定正确的模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('239','ordopefin','订单操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('240','ordmodfin','订单修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('241','chooseord','请指定正确的订单','0');
INSERT INTO {$tblprefix}amsgs VALUES ('242','addarcfailed','添加文档失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('243','cheordcanmod','不能对此状态订单进行本操作','0');
INSERT INTO {$tblprefix}amsgs VALUES ('244','arcaddfinish','文档添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('245','arceditfinish','文档编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('246','arccopyseditfinish','文档副本编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('247','mlangedifin','会员中心编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('248','mlanaddfin','会员中心语言包添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('249','choosemlan','请指定正确的会员中心语言包','0');
INSERT INTO {$tblprefix}amsgs VALUES ('250','mlangmodfin','会员中心语言包修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('251','clangedifin','前台语言包编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('252','clangaddfin','前台语言包添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('253','choosenclang','请指定正确的前台语言包','0');
INSERT INTO {$tblprefix}amsgs VALUES ('254','clangmodfin','前台语言包修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('255','memchaaltopefin','会员模型变更操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('256','memchaaltrecmodfin','会员模型变更记录修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('257','arcfinish','文档操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('258','mtplsearchnone','没有搜索到需要入库的模板文件','0');
INSERT INTO {$tblprefix}amsgs VALUES ('259','temcnaill','模板名称不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('260','pagtemrepdef','页面模板重复定义','0');
INSERT INTO {$tblprefix}amsgs VALUES ('261','temaddfin','模板添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('262','undefanswerchannel','未定义答疑模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('263','temputfin','模板入库完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('264','temdatamiss','模板资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('265','temcopfin','模板复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('266','poitagsou','请指定标识来源!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('267','temfiladdfai','模板文件添加失败!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('269','poisotemfino','指定的源模板文件不存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('270','poitemficnarep','指定的模板文件名称重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('584','replysetsucceed','回复管理完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('272','temcopfai','模板复制失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('585','setusualfin','指定标识成功加入常用标识库！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('274','spatempromodfin','空间模板方案修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('586','setusualed','指定标识已经在您的常用标识库中！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('276','questionadminsucceed','问题管理成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('277','spacatmodfin','空间栏目修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('278','badwordsamerword','不良词与替换词相同','0');
INSERT INTO {$tblprefix}amsgs VALUES ('279','badwordaddfinish','不良词添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('280','badwordmodifyfinish','不良词修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('281','temprodatmis','模板方案资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('282','inputsearchstring','请输入搜索字串','0');
INSERT INTO {$tblprefix}amsgs VALUES ('283','temproaddfin','模板方案添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('284','addchannel','请先添加有效的模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('285','inpspacatcnam','请输入空间栏目名称!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('286','catalogdatamiss','栏目资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('287','spacataddfin','空间栏目添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('288','catalogenameillegal','栏目标识不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('289','choosespatempro','请指定正确的空间模板方案','0');
INSERT INTO {$tblprefix}amsgs VALUES ('290','catalogenamerepeat','栏目标识重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('291','selectopecat','请选择操作类目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('292','catalogaddfinish','栏目添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('293','catalogeditfinish','栏目编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('294','tempprosetfin','模板方案设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('295','paramerror','参数错误','0');
INSERT INTO {$tblprefix}amsgs VALUES ('296','catas_forbidmove','类目不能转到原类目及其子类目下','0');
INSERT INTO {$tblprefix}amsgs VALUES ('297','catalogsetfinish','栏目设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('298','tagdatamiss','标识资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('299','tagenidill','标识英文ID不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('304','choosetag','请指定正确的标识','0');
INSERT INTO {$tblprefix}amsgs VALUES ('301','tagenidrep','标识英文ID重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('302','catalognosoncandel','栏目没有相关联的子栏目才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('303','tagaddfin','标识添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('305','catalognoarccandel','栏目没有相关联的文档才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('306','tagmodfin','标识修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('307','catalogdelfinish','栏目删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('308','errorparament','错误的文件参数','0');
INSERT INTO {$tblprefix}amsgs VALUES ('309','archanneleditfinish','文档模型编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('310','channelnamemiss','模型名称不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('311','arcchanneladdfinish','文档模型添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('312','arcchannelcopyfinish','文档模型复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('313','channelnoaltypecandel','模型没有相关联的合辑类型才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('314','channelnoarccandel','模型没有相关联的文档才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('315','arcchanneldelfinish','文档模型删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('316','temfilcnaill','模板文件名称不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('317','cmsgeditfinish','前台提示编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('318','cmsgaddfinish','前台提示添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('319','tagcopfin','标识复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('320','confirmchoosecmsg','请指定正确的前台提示','0');
INSERT INTO {$tblprefix}amsgs VALUES ('321','cmsgmodifyfinish','前台提示修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('322','conmemcha','请先添加有效的会员模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('323','choosememchaaltpro','请指定正确的会员模型变更方案','0');
INSERT INTO {$tblprefix}amsgs VALUES ('324','inpprocna','请输入方案名称!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('325','souchatarchasam','来源模型与目标模型相同!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('326','memchaalpromodfin','会员模型变更方案修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('327','confirmadduser','请先添加有效的会员组','0');
INSERT INTO {$tblprefix}amsgs VALUES ('328','usealtprodelfin','会员组变更方案删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('329','memchanaltprodelfin','会员模型变更方案删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('330','usealtpromodfin','会员组变更方案修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('331','prorepdef','方案重复定义!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('332','useraltproaddfin','会员组变更方案添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('333','souuserandtar','来源会员组与目标会员组相同!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('334','memchaalproaddfin','会员模型变更方案添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('335','mmsgmodfin','会员中心提示信息修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('336','choosemmsg','请指定正确的会员中心提示信息','0');
INSERT INTO {$tblprefix}amsgs VALUES ('337','mmsgaddfin','会员中心提示信息添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('338','ccconfigsavefinish','节点结构配置保存完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('339','mmsgeditfin','会员中心提示信息编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('340','cconfigsaddfinish','节点结构添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('341','menitedelfin','菜单项目删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('342','pleinpmmecoctit','请输入会员中心菜单分类标题!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('343','memcenmecocaddfin','会员中心菜单分类添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('344','pleinpmetitandurl','请输入菜单标题与链接!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('345','poimmebelcoc','请指定会员中心菜单所属分类!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('346','memcenmeniteadd','会员中心菜单项目添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('347','ccnodeupdatefinish','类目节点更新完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('348','mecemeedifin','会员中心菜单编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('349','choosemecoc','请指定正确的菜单分类','0');
INSERT INTO {$tblprefix}amsgs VALUES ('350','mecocmodfin','菜单分类修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('351','selectcnode','请选择节点','0');
INSERT INTO {$tblprefix}amsgs VALUES ('352','oosemmit','请指定正确的会员中心菜单项目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('353','cnodeoperatefinish','节点操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('354','inmmtiturl','请输入会员中心菜单标题与链接!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('355','menitemodfin','菜单项目修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('356','cnodesetfinish','节点设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('357','mecocoutmetedel','菜单分类没有相关联的菜单项目才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('358','mecocdefi','菜单分类删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('359','choosecotypem','请指定正确的类别体系','0');
INSERT INTO {$tblprefix}amsgs VALUES ('360','coclassdatamiss','分类资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('361','mecenpagusetfin','会员中心页面提示说明设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('362','coclassenameillegal','分类标识不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('363','inpmecoctit','请输入菜单分类标题!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('364','coclassenamerepeat','分类标识重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('365','inpcocdefurl','请输入菜单分类默认链接!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('366','setself_regcondition','请设置自动归类条件','0');
INSERT INTO {$tblprefix}amsgs VALUES ('367','mocwitocdel','菜单分类没有相关联的子分类才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('368','coclassaddfinish','分类添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('369','pombecoc','请指定菜单所属分类!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('370','coclasssetfinish','分类设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('371','choosemeit','请指定正确的菜单项目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('372','coclassnosoncandel','分类没有相关联的子分类才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('373','menitedfin','菜单项目编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('374','selectcomment','请选择评论','0');
INSERT INTO {$tblprefix}amsgs VALUES ('375','commentadminfinish','评论管理完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('377','commentnoexist','指定的评论不存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('378','choosematype','请指定正确的会员档案类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('379','commentovermin','评论超出最小字长','0');
INSERT INTO {$tblprefix}amsgs VALUES ('380','commentmodifyfinish','评论修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('381','cotypeeditfinish','类系编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('382','cotypenamemiss','类系名称不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('383','cotypeaddfinish','类系添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('384','cotypeaddfailed','类系添加失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('385','choosecotype','请指定正确的类系','0');
INSERT INTO {$tblprefix}amsgs VALUES ('386','cotypemsetfinish','类别体系设置完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('387','cotypedelfinish','类别体系删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('388','grouedifin','会员组体系编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('389','pointfilename','请指定文件名称!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('390','groupdatamis','会员组体系资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('391','grouaddfin','会员组体系添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('392','sfileaddfinish','%s 文件添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('393','grouperrsave','会员组体系保存时发生错误','0');
INSERT INTO {$tblprefix}amsgs VALUES ('394','fileaddfailed','文件添加失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('395','gathmodedifin','采集模型编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('396','filenamerepeat','指定的文件名称重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('397','modrelarcmodnoe','采集模型关联的文档模型不存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('398','jsfilemodifyfinish','JS文件修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('399','cssfilemodifyfinish','CSS文件修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('400','socfilenoexist','指定的源文件不存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('401','targetfilename','请指定目标文件名称!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('402','filecopyfailed','文件复制失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('403','definejsfiletemplate','请定义JS文件模板!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('404','jsfileupdatefinish','JS文件更新完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('405','gatmodmodfin','采集模型修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('406','filecopyfinish','文件复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('407','filemodifyfinish','文件修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('408','filedelfinish','文件删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('409','replycoclasseditfinish','回复分类编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('410','replycoclassaddfinish','回复分类添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('411','inpgatmodnam','请输入采集模型名称!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('412','replycoclassdelfinish','回复分类删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('413','chorcchanalt','请选择文档模型或合辑类型!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('414','gamodaddfin','采集模型添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('415','choosegatmod','请指定正确的采集模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('416','currencyaddfinish','积分添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('417','currencyfinish','积分操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('418','currencyeditfinish','积分编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('419','definecurrencytype','请定义积分类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('420','pricenamerepeat','价格名称重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('421','currencypriceaddfinish','积分价格添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('422','currencypriceeditfinish','积分价格编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('423','addgatcha','请添加采集模型!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('424','definemorecurrencytype','请定义更多积分类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('425','notexchangesame','相同积分之间不能兑换','0');
INSERT INTO {$tblprefix}amsgs VALUES ('426','gatmismodfin','采集任务修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('427','gatmisaddfin','采集任务添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('428','exchangeexist','兑换方案已存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('429','gatmisedifin','采集任务编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('430','exchangeaddfinish','兑换方案添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('431','exchangemodifyfinish','兑换方案修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('432','defineinoutcutype','请定义可以充/扣值的积分类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('433','currencyinoutfinish','积分充/扣值完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('434','commuitemcopyfinish','交互项目复制完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('435','filenameillegal','文件名称不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('436','selecttable','请选择数据表','0');
INSERT INTO {$tblprefix}amsgs VALUES ('437','tableexportfailed','数据表输出失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('438','backuping','全部 %s 个数据表,正在备份第 %s 个数据表<br>分卷 %s 备份完毕。','0');
INSERT INTO {$tblprefix}amsgs VALUES ('439','dbbackupfinish','数据库备份完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('440','choosegatmis','请指定正确的采集任务','0');
INSERT INTO {$tblprefix}amsgs VALUES ('441','selectnet','请选择网址','0');
INSERT INTO {$tblprefix}amsgs VALUES ('442','congatfinconoutwa','内容自动采集完成<br>开始内容入库,请等待','0');
INSERT INTO {$tblprefix}amsgs VALUES ('443','nooutitem','无入库项目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('444','setoutrul','请设置入库规则!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('445','conautoutfin','内容自动入库完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('446','backupfiledelfinish','备份文件删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('447','connetgatfin','内容网址采集完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('448','nongatitem','无采集项目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('449','setgatrul','请设置采集规则!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('450','conaugatfin','内容自动采集完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('451','gatmisdatmis','采集任务资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('452','pageaddfin','页面添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('453','selectbackupfile','请选择备份文件','0');
INSERT INTO {$tblprefix}amsgs VALUES ('454','chooseisopa','请指定正确的独立页面','0');
INSERT INTO {$tblprefix}amsgs VALUES ('455','pagestafin','页面静态完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('456','pastacanfin','页面静态取消完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('457','selectford','请选择订单','0');
INSERT INTO {$tblprefix}amsgs VALUES ('458','choosemes','请指定正确的信息','0');
INSERT INTO {$tblprefix}amsgs VALUES ('459','addrepsuc','添加回复成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('460','thconiteclo','此咨询项目已关闭','0');
INSERT INTO {$tblprefix}amsgs VALUES ('461','staetsuc','状态设置成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('462','nomatype','请定义会员档案类型!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('463','poconcoc','请指定咨询分类','0');
INSERT INTO {$tblprefix}amsgs VALUES ('464','nocheckmess','未审信息','0');
INSERT INTO {$tblprefix}amsgs VALUES ('465','importdbsucceed','输入数据库成功!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('466','freeopefin','附属信息操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('467','mselectmes','请选择信息','0');
INSERT INTO {$tblprefix}amsgs VALUES ('468','maddconmescoc','请先添加有效的咨询信息分类','0');
INSERT INTO {$tblprefix}amsgs VALUES ('469','frechadelfin','附属信息模型删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('470','chaoutrelocdel','模型没有相关联的相关分类才能删除','0');
INSERT INTO {$tblprefix}amsgs VALUES ('471','frechaaddfin','附属信息模型添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('472','frechaedifin','附属信息模型编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('473','cocwitarccandel','分类没有相关联的文档和子分类时才能删除','1264318910');
INSERT INTO {$tblprefix}amsgs VALUES ('474','mescocaddfin','信息分类添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('475','deffrecha','请定义附属信息模型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('476','volumeexporting','分卷%s备份完毕，正在处理分卷%s。<br>%s中止当前操作%s','1269390880');
INSERT INTO {$tblprefix}amsgs VALUES ('477','choosemesid','请指定正确的信息ID','0');
INSERT INTO {$tblprefix}amsgs VALUES ('478','freaddfin','附属信息添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('479','mesaddfai','信息添加失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('480','nothicocaddper','您没有此分类的添加权限','0');
INSERT INTO {$tblprefix}amsgs VALUES ('481','choosemescoc','请指定正确的信息分类','0');
INSERT INTO {$tblprefix}amsgs VALUES ('482','dbconerr','外部数据源连接错误','0');
INSERT INTO {$tblprefix}amsgs VALUES ('483','dbdatamis','外部数据源资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('484','choosedbs','请指定正确的外部数据源','0');
INSERT INTO {$tblprefix}amsgs VALUES ('485','dbmodfin','外部数据源修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('486','dbaddfin','外部数据源添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('487','succrepl','成功替换%s条记录。','0');
INSERT INTO {$tblprefix}amsgs VALUES ('488','tableoperatefinish','数据表操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('489','inputsqlcode','请输入SQL代码','0');
INSERT INTO {$tblprefix}amsgs VALUES ('490','ondeal','请不要批量处理主键字段。','0');
INSERT INTO {$tblprefix}amsgs VALUES ('491','sqlresult','SQL代码执行成功!共涉及 %s 条记录。','0');
INSERT INTO {$tblprefix}amsgs VALUES ('492','find','查找%s','0');
INSERT INTO {$tblprefix}amsgs VALUES ('493','notablerecord','没有找到符合条件的记录。','0');
INSERT INTO {$tblprefix}amsgs VALUES ('494','notablekey','否数据表密钥','0');
INSERT INTO {$tblprefix}amsgs VALUES ('495','modseareptxtnot','搜索模式,搜索文本或替换文本不能为空','0');
INSERT INTO {$tblprefix}amsgs VALUES ('496','dataexportfailed','数据输出失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('497','dataexportfinish','数据输出成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('498','choosetable','请指定正确的数据表','0');
INSERT INTO {$tblprefix}amsgs VALUES ('499','dataiodfin','数据库字段备注修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('500','chooseconfigfile','请选择配置文件','0');
INSERT INTO {$tblprefix}amsgs VALUES ('501','configfiledelfinish','配置文件删除完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('502','onlyfounderconfig','仅创始人可以安装系统配置','0');
INSERT INTO {$tblprefix}amsgs VALUES ('503','inputuportplfolder','请输入上传文件夹或模板文件夹名称','0');
INSERT INTO {$tblprefix}amsgs VALUES ('504','uportplfolderillegal','上传文件夹或模板文件夹名称不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('505','uploadfilemiss','上传文件不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('506','tplfolderused','模板文件夹被占用','0');
INSERT INTO {$tblprefix}amsgs VALUES ('507','psqlfileillegal','package.sql文件不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('508','psqlfileerror','package.sql文件错误','0');
INSERT INTO {$tblprefix}amsgs VALUES ('509','cfgfilevererror','配置文件版本错误','0');
INSERT INTO {$tblprefix}amsgs VALUES ('510','funcdircopyfailed','功能目录复制失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('511','marcfinish','会员档案修改成功!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('512','alangaddfin','后台语言包添加完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('513','alangedifin','后台语言包编辑完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('514','alangmodfin','后台语言包修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('515','find_replace','查找%s条记录。成功替换%s条记录。','0');
INSERT INTO {$tblprefix}amsgs VALUES ('516','choose_msg_id','请指定正确的信息ID','0');
INSERT INTO {$tblprefix}amsgs VALUES ('534','point_altype','请指定合辑类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('533','input_tag_tpl','请输入标识模板','0');
INSERT INTO {$tblprefix}amsgs VALUES ('530','field_ename_repeat','字段标识重复','0');
INSERT INTO {$tblprefix}amsgs VALUES ('531','field_ename_illegal','字段标识不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('532','field_ename_notuse','字段标识禁止使用','0');
INSERT INTO {$tblprefix}amsgs VALUES ('529','field_data_miss','字段资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('547','nosite','指定的站点不存在!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('525','data_export_failed','数据输出失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('526','data_export_finish','数据输出完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('535','usource_illegal','作为对象的原始信息标识名称不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('536','input_query_string','请输入查询字串','0');
INSERT INTO {$tblprefix}amsgs VALUES ('537','choose_commu_item','请指定正确的交互项目','0');
INSERT INTO {$tblprefix}amsgs VALUES ('538','psource_illegal','分页内容来源标识名不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('539','choose_msg_class','请指定正确的信息分类','0');
INSERT INTO {$tblprefix}amsgs VALUES ('540','tag_data_miss','标识资料不完全','0');
INSERT INTO {$tblprefix}amsgs VALUES ('541','tpl_file_name_illegal','模板文件名称不合规范','0');
INSERT INTO {$tblprefix}amsgs VALUES ('542','tpl_save_failed','模板保存失败','0');
INSERT INTO {$tblprefix}amsgs VALUES ('543','point_isolute_page_id','请指定独立页ID','0');
INSERT INTO {$tblprefix}amsgs VALUES ('544','input_usource','确认输入正确作为对象的原始信息标识名称','0');
INSERT INTO {$tblprefix}amsgs VALUES ('546','addinpointalbum','在指定合辑内添加','0');
INSERT INTO {$tblprefix}amsgs VALUES ('549','defineachannel','请定义文档模型！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('550','aurlmissname','请输入管理链接名称','0');
INSERT INTO {$tblprefix}amsgs VALUES ('551','aurladdfinish','管理链接添加成功!\r\n请对添加的链接进行详细设置。','0');
INSERT INTO {$tblprefix}amsgs VALUES ('552','chooseaurl','您选择的管理链接不存在或被删除!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('553','selectanode','请选择需要操作的管理节点!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('554','definealtype','请定义合辑类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('555','define_add_fcoclass','请定义附属信息类型','0');
INSERT INTO {$tblprefix}amsgs VALUES ('556','no_commu_tplset','没有需要设置模板的文档交互','0');
INSERT INTO {$tblprefix}amsgs VALUES ('557','no_mcommu_tplset','没有需要设置模板的会员交互','0');
INSERT INTO {$tblprefix}amsgs VALUES ('558','contentsetsucceed','内容批量操作成功!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('559','pchoosecontent','请选择需要操作的内容项!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('560','updatesucceed','%s更新成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('561','choosereply','请指定正确的回复内容!','0');
INSERT INTO {$tblprefix}amsgs VALUES ('562','albumisover','您所指定的合辑已设置为完结，不能添加或加载新的内容！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('563','albumisoneuser','你所指定的合辑为个人合辑，只有合辑作者才可以添加新的内容！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('564','albumisload','您所指定的合辑为加载性合辑，不能直接在合辑中添加新的内容！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('565','albumovermax','您所指定的合辑的内容数量已达到最大数限制，请清除部分辑内内容后才可以添加或加载新的内容！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('566','confchoosarchi','请指定正确的文档！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('567','choosearctype','请选择文档类型！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('568','btagupdatefin','原始标识更新成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('569','altypecopyfinish','合辑类型复制成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('570','orddelfin','订单删除成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('571','select_both_cc','确认订单 和 取消订单 不能并存执行','0');
INSERT INTO {$tblprefix}amsgs VALUES ('572','ordmodpay','付款金额修改成功','0');
INSERT INTO {$tblprefix}amsgs VALUES ('573','scnodeupdatefinish','基本节点更新成功！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('575','goucpmadmin','请进入UCenter管理 [%s]','0');
INSERT INTO {$tblprefix}amsgs VALUES ('576','outrulmodfin','入库规则修改完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('577','nogatheritem','没有需要采集的内容网址！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('578','toautogather','内容网址采集完毕！<br> 系统将自动转入内容采集！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('579','toautoouput','内容采集完成！<br> 系统即将自动将内容入库！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('580','onekeyfinish','一键采集全部过程完成！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('581','p_setrule','请检查采集规格的完整性！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('582','ga_op_finish','采集批量操作完成！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('583','p_choosegurl','请指定正确的采集记录！','0');
INSERT INTO {$tblprefix}amsgs VALUES ('589','utcls_fin','常用标识分类操作完成','0');
INSERT INTO {$tblprefix}amsgs VALUES ('590','utcls_exist','常用标识分类已存在','0');
INSERT INTO {$tblprefix}amsgs VALUES ('591','setcommuitem','请设置交互项目!','1261884698');
INSERT INTO {$tblprefix}amsgs VALUES ('592','chooseproduct','请指定产品!','1261884750');
INSERT INTO {$tblprefix}amsgs VALUES ('593','choosecommentobject','请指定评论的对象!','1261884789');
INSERT INTO {$tblprefix}amsgs VALUES ('594','choosereplyobject','请指定正确的回复对象!','1261886023');
INSERT INTO {$tblprefix}amsgs VALUES ('595','notnull','%s 不能为空','1261897183');
INSERT INTO {$tblprefix}amsgs VALUES ('596','extract_check_finish','提现申请审核完成','1263203390');
INSERT INTO {$tblprefix}amsgs VALUES ('597','extract_operate_finish','批量操作完成','1263264774');
INSERT INTO {$tblprefix}amsgs VALUES ('598','select_extract','请选择提现记录','1263264973');
INSERT INTO {$tblprefix}amsgs VALUES ('599','uurls_or_uregular','手动来源网址与序列来源网址至少需要填写一个','1264129641');
INSERT INTO {$tblprefix}amsgs VALUES ('600','uspilit_and_uurltag','网址列表分隔符和网址采集模印不能为空','1264130243');
INSERT INTO {$tblprefix}amsgs VALUES ('601','onlyabvol','只有合辑才可以分卷!','1264142459');
INSERT INTO {$tblprefix}amsgs VALUES ('602','pputvtitle','请输入分卷名!','1264144824');
INSERT INTO {$tblprefix}amsgs VALUES ('603','volidrepeat','分卷号重复，请重新输入！','1264145013');
INSERT INTO {$tblprefix}amsgs VALUES ('604','voladdfin','分卷添加成功！','1264145170');
INSERT INTO {$tblprefix}amsgs VALUES ('605','voldelfin','指定分卷删除成功！','1264146371');
INSERT INTO {$tblprefix}amsgs VALUES ('606','voleditfin','分卷修改完成！','1264146535');
INSERT INTO {$tblprefix}amsgs VALUES ('607','repugrademodfin','信用等级编辑成功！','1264704241');
INSERT INTO {$tblprefix}amsgs VALUES ('608','confirmselectcomment','请选择至少一条评论记录！','1264818563');
INSERT INTO {$tblprefix}amsgs VALUES ('609','delrepusrecord','删除会员信用值记录','1264906194');
INSERT INTO {$tblprefix}amsgs VALUES ('610','handrepufin','手动操作会员信用值完成!','1264907484');
INSERT INTO {$tblprefix}amsgs VALUES ('611','arcallowance','您的发布数量已超出限额！','1265535495');
INSERT INTO {$tblprefix}amsgs VALUES ('612','faceupdatefin','表情加载完成！','1268793245');
INSERT INTO {$tblprefix}amsgs VALUES ('613','facetypeseditfinish','表情组设置完成！','1268793272');
INSERT INTO {$tblprefix}amsgs VALUES ('614','faceseditfinish','表情设置完成！','1268793297');
INSERT INTO {$tblprefix}amsgs VALUES ('615','pointfacetype','请选择正确的表情组！','1268793875');
INSERT INTO {$tblprefix}amsgs VALUES ('616','msitesyscacreffin','主站缓存更新完成！','1270080347');
INSERT INTO {$tblprefix}amsgs VALUES ('617','subsitesyscacreffin','子站缓存更新完成！','1270080387');
INSERT INTO {$tblprefix}amsgs VALUES ('618','domaineditfin','域名编辑完成！','1270994610');
INSERT INTO {$tblprefix}amsgs VALUES ('619','domainaddfin','域名添加成功！','1270994637');
INSERT INTO {$tblprefix}amsgs VALUES ('620','addmcnodefin','会员节点添加成功！','1271574676');
INSERT INTO {$tblprefix}amsgs VALUES ('622','pointcnode','请指定正确的节点！','1271587950');
INSERT INTO {$tblprefix}amsgs VALUES ('623','editmcnodefin','会员节点编辑完成！','1271590863');
INSERT INTO {$tblprefix}amsgs VALUES ('624','pchoidxtp','请选择需要生成静态的首页类型！','1271748097');
INSERT INTO {$tblprefix}amsgs VALUES ('625','memcert_exists','认证添加重复','1271120959');
INSERT INTO {$tblprefix}amsgs VALUES ('626','memcert_add_modify','认证添加完成，进行相关设置','1271121013');
INSERT INTO {$tblprefix}amsgs VALUES ('627','memcert_add_error','认证添加出错','1271121033');
INSERT INTO {$tblprefix}amsgs VALUES ('628','memcert_modify_fail','无效的认证修改请求','1271121292');
INSERT INTO {$tblprefix}amsgs VALUES ('629','memcertfinish','认证编辑完成','1271129171');
INSERT INTO {$tblprefix}amsgs VALUES ('630','memcert_mode_fail','在特殊认证类型里特殊字段填写不合法','1271145824');
INSERT INTO {$tblprefix}amsgs VALUES ('631','memcert_fields_fail','认证关联字段不合法，不能全为空，且注意格字段的要求','1271320655');
INSERT INTO {$tblprefix}amsgs VALUES ('632','memcert_check_finish','认证审核完成','1271236106');
INSERT INTO {$tblprefix}amsgs VALUES ('633','memcert_check_fail','认证审核错误，无效的认证请求！','1271236499');
INSERT INTO {$tblprefix}amsgs VALUES ('634','mcrecord_finish','认证申请处理完成','1271239145');
INSERT INTO {$tblprefix}amsgs VALUES ('635','mobilesetfinish','手机设置完成','1271579223');
INSERT INTO {$tblprefix}amsgs VALUES ('636','choosecnconfig','请选择交叉节点结构！','1271840200');
INSERT INTO {$tblprefix}amsgs VALUES ('637','wapset_finish','WAP设置完成','1270194764');
INSERT INTO {$tblprefix}amsgs VALUES ('638','wapset_domain_fail','WAP域名填写不合法','1270195398');
INSERT INTO {$tblprefix}amsgs VALUES ('639','wlangedifin','WAP语言包编辑完成','1270199098');
INSERT INTO {$tblprefix}amsgs VALUES ('640','wlangaddfin','WAP语言包添加完成','1270199369');
INSERT INTO {$tblprefix}amsgs VALUES ('641','wlangmodfin','WAP语言包编辑完成','1270280728');
INSERT INTO {$tblprefix}amsgs VALUES ('642','cncfgeditfin','节点结构配置编辑完成！','1272449135');
INSERT INTO {$tblprefix}amsgs VALUES ('644','setcoclass','请设置 %s 分类','1272851190');
INSERT INTO {$tblprefix}amsgs VALUES ('645','empty_batch_count','请填写批量添加的栏目数量','1272957496');
INSERT INTO {$tblprefix}amsgs VALUES ('646','batch_catalog_some','成功批量添加了 %s 个栏目','1272967037');
INSERT INTO {$tblprefix}amsgs VALUES ('647','batch_catalog_bad','批量添加失败','1272967053');
INSERT INTO {$tblprefix}amsgs VALUES ('648','batch_coclass_some','成功批量添加了 %s 个分类','1273021041');
INSERT INTO {$tblprefix}amsgs VALUES ('649','batch_coclass_bad','批量添加失败','1273021025');
INSERT INTO {$tblprefix}amsgs VALUES ('650','no_matype_tplset','请指定会员档案类型','1273044003');
INSERT INTO {$tblprefix}amsgs VALUES ('651','murladdfinish','会员中心外链添加完成','1273151547');

DROP TABLE IF EXISTS {$tblprefix}answers;
CREATE TABLE {$tblprefix}answers (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  answer mediumtext NOT NULL,
  aid mediumint(8) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  votes1 int(10) unsigned NOT NULL default '0',
  votes2 int(10) unsigned NOT NULL default '0',
  votes3 int(10) unsigned NOT NULL default '0',
  votes4 int(10) unsigned NOT NULL default '0',
  votes5 int(10) unsigned NOT NULL default '0',
  crid smallint(6) unsigned NOT NULL default '0',
  currency int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}archives;
CREATE TABLE {$tblprefix}archives (
  aid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  `subject` varchar(255) NOT NULL,
  jumpurl varchar(120) NOT NULL default '',
  sid smallint(5) unsigned NOT NULL default '0',
  caid smallint(5) unsigned NOT NULL default '0',
  chid tinyint(3) unsigned NOT NULL default '0',
  cpid mediumint(8) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  ucid mediumint(8) unsigned NOT NULL default '0',
  abover tinyint(1) unsigned NOT NULL default '0',
  abnew mediumint(8) unsigned NOT NULL default '0',
  author char(30) NOT NULL,
  `source` varchar(50) NOT NULL default '',
  abstract text NOT NULL,
  keywords char(100) NOT NULL,
  thumb varchar(255) NOT NULL,
  vieworder smallint(6) unsigned NOT NULL default '500',
  createdate int(10) unsigned NOT NULL default '0',
  updatedate int(10) unsigned NOT NULL default '0',
  refreshdate int(10) NOT NULL default '0',
  enddate int(10) NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  chkstate tinyint(1) unsigned NOT NULL default '0',
  customurl varchar(100) NOT NULL default '',
  clicks int(10) unsigned NOT NULL default '0',
  comments int(10) unsigned NOT NULL default '0',
  scores int(10) unsigned NOT NULL default '0',
  avgscore float unsigned NOT NULL default '0',
  orders int(10) unsigned NOT NULL default '0',
  ordersum int(10) unsigned NOT NULL default '0',
  favorites int(10) unsigned NOT NULL default '0',
  praises int(10) unsigned NOT NULL default '0',
  debases int(10) unsigned NOT NULL default '0',
  answers int(10) unsigned NOT NULL default '0',
  atmsize int(10) unsigned NOT NULL default '0',
  bytenum int(10) unsigned NOT NULL default '0',
  downs int(10) unsigned NOT NULL default '0',
  rpmid smallint(6) NOT NULL default '-1',
  dpmid smallint(6) NOT NULL default '-1',
  salecp varchar(15) NOT NULL,
  fsalecp varchar(15) NOT NULL,
  price float unsigned NOT NULL default '0',
  crid smallint(6) unsigned NOT NULL default '0',
  currency int(10) unsigned NOT NULL default '0',
  adopts int(10) unsigned NOT NULL default '0',
  closed tinyint(1) unsigned NOT NULL default '0',
  finishdate int(10) unsigned NOT NULL default '0',
  replys int(10) unsigned NOT NULL default '0',
  offers int(10) unsigned NOT NULL default '0',
  plays int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (aid),
  KEY cpid (cpid),
  KEY sid (sid,caid),
  KEY mid (mid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}archives_rec;
CREATE TABLE {$tblprefix}archives_rec (
  aid mediumint(8) unsigned NOT NULL default '0',
  mclicks int(10) unsigned NOT NULL default '0',
  wclicks int(10) unsigned NOT NULL default '0',
  mcomments int(10) unsigned NOT NULL default '0',
  wcomments int(10) unsigned NOT NULL default '0',
  morders int(10) unsigned NOT NULL default '0',
  worders int(10) unsigned NOT NULL default '0',
  mordersum int(10) unsigned NOT NULL default '0',
  wordersum int(10) unsigned NOT NULL default '0',
  mfavorites int(10) unsigned NOT NULL default '0',
  wfavorites int(10) unsigned NOT NULL default '0',
  mpraises int(10) unsigned NOT NULL default '0',
  wpraises int(10) unsigned NOT NULL default '0',
  mdebases int(10) unsigned NOT NULL default '0',
  wdebases int(10) unsigned NOT NULL default '0',
  mdowns int(10) unsigned NOT NULL default '0',
  wdowns int(10) unsigned NOT NULL default '0',
  mplays int(10) unsigned NOT NULL default '0',
  wplays int(10) unsigned NOT NULL default '0',
  mreplys int(10) unsigned NOT NULL default '0',
  wreplys int(10) unsigned NOT NULL default '0',
  moffers int(10) unsigned NOT NULL default '0',
  woffers int(10) unsigned NOT NULL default '0',
  aclicks int(10) unsigned NOT NULL default '0',
  acomments int(10) unsigned NOT NULL default '0',
  afavorites int(10) unsigned NOT NULL default '0',
  aorders int(10) unsigned NOT NULL default '0',
  aanswers int(10) unsigned NOT NULL default '0',
  aadopts int(10) unsigned NOT NULL default '0',
  aordersum int(10) unsigned NOT NULL default '0',
  apraises int(10) unsigned NOT NULL default '0',
  adebases int(10) unsigned NOT NULL default '0',
  adowns int(10) unsigned NOT NULL default '0',
  aplays int(10) unsigned NOT NULL default '0',
  areplys int(10) unsigned NOT NULL default '0',
  aoffers int(10) unsigned NOT NULL default '0',
  abytenum int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (aid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}archives_sub;
CREATE TABLE {$tblprefix}archives_sub (
  aid mediumint(8) unsigned NOT NULL default '0',
  editorid mediumint(8) unsigned NOT NULL default '0',
  editor char(30) NOT NULL,
  editorid1 mediumint(8) unsigned NOT NULL default '0',
  editor1 char(30) NOT NULL default '',
  editorid2 mediumint(8) unsigned NOT NULL default '0',
  editor2 char(30) NOT NULL default '',
  editorid3 mediumint(8) unsigned NOT NULL default '0',
  editor3 char(30) NOT NULL default '',
  needupdate int(10) unsigned NOT NULL default '0',
  overupdate int(10) unsigned NOT NULL default '0',
  updatecopyid mediumint(8) unsigned NOT NULL default '0',
  weight float unsigned NOT NULL default '0',
  `storage` int(10) unsigned NOT NULL default '0',
  spare int(10) unsigned NOT NULL default '0',
  reports int(10) unsigned NOT NULL default '0',
  needstatics varchar(50) NOT NULL default '',
  arctpls varchar(120) NOT NULL default '',
  PRIMARY KEY  (aid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}arecents;
CREATE TABLE {$tblprefix}arecents (
  aid mediumint(8) unsigned NOT NULL default '0',
  vardate int(10) unsigned NOT NULL default '0',
  clicks int(10) unsigned NOT NULL default '0',
  comments int(10) unsigned NOT NULL default '0',
  orders int(10) unsigned NOT NULL default '0',
  ordersum int(10) unsigned NOT NULL default '0',
  favorites int(10) unsigned NOT NULL default '0',
  praises int(10) unsigned NOT NULL default '0',
  debases int(10) unsigned NOT NULL default '0',
  downs int(10) unsigned NOT NULL default '0',
  plays int(10) unsigned NOT NULL default '0',
  replys int(10) unsigned NOT NULL default '0',
  offers int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (aid,vardate),
  KEY vardate (vardate)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}asession;
CREATE TABLE {$tblprefix}asession (
  mid mediumint(8) unsigned NOT NULL default '0',
  ip char(15) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  errorcount tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (mid)
) TYPE=MyISAM;

INSERT INTO {$tblprefix}asession VALUES ('1','192.168.1.60','1274943377','-1');

DROP TABLE IF EXISTS {$tblprefix}aurls;
CREATE TABLE {$tblprefix}aurls (
  auid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  remark varchar(80) NOT NULL,
  uclass varchar(15) NOT NULL,
  issys tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL,
  setting text NOT NULL,
  tplname varchar(50) NOT NULL,
  onlyview tinyint(1) unsigned NOT NULL default '0',
  mtitle varchar(80) NOT NULL,
  guide text NOT NULL,
  isbk tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (auid)
) TYPE=MyISAM AUTO_INCREMENT=101;

INSERT INTO {$tblprefix}aurls VALUES ('1','内容发布','文档与合辑的发布(系统内置)','arcadd','1','1','3','?entry=addpre&nauid=1','a:3:{s:5:\"coids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"nochids\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('2','','','','1','1','1','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('3','文档管理','文档(系统内置)','archives','1','1','2','?entry=archives&action=archivesedit&nauid=3','a:7:{s:7:\"checked\";s:2:\"-1\";s:5:\"valid\";s:2:\"-1\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";s:5:\"iuids\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('4','评论管理','评论管理(系统内置)','comments','1','1','5','?entry=comments&action=commentsedit&nauid=4','a:6:{s:7:\"checked\";s:2:\"-1\";s:5:\"cuids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('5','报价管理','报价管理(系统内置)','offers','1','1','7','?entry=offers&action=offersedit&nauid=5','a:7:{s:7:\"checked\";s:2:\"-1\";s:5:\"valid\";s:2:\"-1\";s:5:\"cuids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('6','回复管理','回复管理(系统内置)','replys','1','1','6','?entry=replys&action=replysedit&nauid=6','a:6:{s:7:\"checked\";s:2:\"-1\";s:5:\"cuids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('7','答案管理','答案管理(系统内置)','answers','1','1','8','?entry=answers&action=answersedit&nauid=7','a:5:{s:7:\"checked\";s:2:\"-1\";s:5:\"cuids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('9','举报管理','举报管理(系统内置)','reports','1','1','10','?entry=reports&action=reportsedit&nauid=9','a:4:{s:5:\"cuids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('10','更新管理','更新管理(系统内置)','arcupdate','1','1','4','?entry=archives&action=archivesupdate&nauid=10','a:1:{s:5:\"coids\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('11','信息列表','信息列表','farchives','1','1','11','?entry=farchives&action=farchivesedit&nauid=11','a:6:{s:7:\"checked\";s:2:\"-1\";s:5:\"valid\";s:2:\"-1\";s:7:\"consult\";s:1:\"0\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('12','发布信息','发布信息','farcadd','1','1','12','?entry=farchive&action=farchiveadd&nauid=12','','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('13','会员管理','会员管理','members','1','1','13','?entry=members&action=membersedit&nauid=13','a:6:{s:7:\"checked\";s:2:\"-1\";s:7:\"filters\";s:5:\"ugid2\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";s:6:\"ugids1\";s:0:\"\";s:6:\"ugids2\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('14','添加会员','添加会员','memadd','1','1','14','?entry=memberadd&nauid=14','','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('15','会员评论','会员评论','mcomments','1','1','18','?entry=mcomments&action=mcommentsedit&nauid=15','a:6:{s:7:\"checked\";s:2:\"-1\";s:5:\"cuids\";s:0:\"\";s:6:\"mchids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('16','会员回复','会员回复','mreplys','1','1','19','?entry=mreplys&action=mreplysedit&nauid=16','a:6:{s:7:\"checked\";s:2:\"-1\";s:5:\"cuids\";s:0:\"\";s:6:\"mchids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('17','会员举报','会员举报','mreports','1','1','20','?entry=mreports&action=mreportsedit&nauid=17','','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('18','类型变更','会员类型变更','mtrans','1','1','16','?entry=mtrans&action=mtransedit&nauid=18','','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('19','组变更','会员组变更','utrans','1','1','17','?entry=utrans&action=utransedit&nauid=19','','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('20','会员档案','会员档案','marchives','1','1','15','?entry=marchives&action=marchivesedit&nauid=20','','','0','','','0');
INSERT INTO {$tblprefix}aurls VALUES ('21','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('22','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('23','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('24','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('25','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('26','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('27','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('28','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('29','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('30','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('31','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('32','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('33','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('34','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('35','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('36','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('37','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('38','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('39','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('40','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('41','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('42','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('43','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('44','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('45','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('46','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('47','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('48','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('49','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('50','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('51','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('52','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('53','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('54','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('55','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('56','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('57','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('58','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('59','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('60','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('61','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('62','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('63','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('64','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('65','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('66','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('67','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('68','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('69','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('70','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('71','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('72','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('73','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('74','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('75','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('76','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('77','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('78','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('79','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('80','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('81','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('82','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('83','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('84','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('85','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('86','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('87','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('88','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('89','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('90','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('91','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('92','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('93','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('94','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('95','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('96','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('97','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('98','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('99','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}aurls VALUES ('100','','','','1','1','0','','','','0','','','1');

DROP TABLE IF EXISTS {$tblprefix}badwords;
CREATE TABLE {$tblprefix}badwords (
  bwid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  wsearch varchar(255) NOT NULL,
  wreplace varchar(255) NOT NULL,
  PRIMARY KEY  (bwid)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}badwords VALUES ('1','妈的','*v*');

DROP TABLE IF EXISTS {$tblprefix}btagnames;
CREATE TABLE {$tblprefix}btagnames (
  bnid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  cname varchar(50) NOT NULL,
  bclass varchar(10) NOT NULL,
  sclass varchar(15) NOT NULL,
  vieworder smallint(3) NOT NULL default '0',
  datatype varchar(30) NOT NULL,
  PRIMARY KEY  (bnid)
) TYPE=MyISAM AUTO_INCREMENT=302;

INSERT INTO {$tblprefix}btagnames VALUES ('1','hostname','主站名称','common','','1','text');
INSERT INTO {$tblprefix}btagnames VALUES ('2','hosturl','主站域名','common','','2','text');
INSERT INTO {$tblprefix}btagnames VALUES ('3','cmsname','站点名称','common','','3','text');
INSERT INTO {$tblprefix}btagnames VALUES ('4','cmsurl','站点相对URL','common','','7','text');
INSERT INTO {$tblprefix}btagnames VALUES ('7','cmsindex','站点首页','common','','8','text');
INSERT INTO {$tblprefix}btagnames VALUES ('8','cmstitle','站点标题','common','','9','text');
INSERT INTO {$tblprefix}btagnames VALUES ('9','cmskeyword','站点关键词','common','','10','text');
INSERT INTO {$tblprefix}btagnames VALUES ('10','cmsdescription','站点描述','common','','11','text');
INSERT INTO {$tblprefix}btagnames VALUES ('11','tplurl','模板位置URL','common','','12','text');
INSERT INTO {$tblprefix}btagnames VALUES ('13','title','类目标题','cnode','','1','text');
INSERT INTO {$tblprefix}btagnames VALUES ('18','indexurl','类目首页URL','cnode','','2','text');
INSERT INTO {$tblprefix}btagnames VALUES ('47','clicks','点击数','archive','','12','int');
INSERT INTO {$tblprefix}btagnames VALUES ('19','listurl','类目列表页URL','cnode','','3','text');
INSERT INTO {$tblprefix}btagnames VALUES ('20','arcurl','文档URL','archive','','6','text');
INSERT INTO {$tblprefix}btagnames VALUES ('31','archives','文档数量','member','','12','int');
INSERT INTO {$tblprefix}btagnames VALUES ('26','aid','文档ID','archive','','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('27','catalog','栏目名称','archive','','3','text');
INSERT INTO {$tblprefix}btagnames VALUES ('46','createdate','文档添加时间','archive','','11','date');
INSERT INTO {$tblprefix}btagnames VALUES ('28','score','评分','commu','comment','5','int');
INSERT INTO {$tblprefix}btagnames VALUES ('29','content','评论内容','commu','comment','6','multitext');
INSERT INTO {$tblprefix}btagnames VALUES ('118','url','附件URL','other','attachment','1','text');
INSERT INTO {$tblprefix}btagnames VALUES ('30','mid','会员ID','member','','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('32','cmslogo','站点LOGO','common','','15','text');
INSERT INTO {$tblprefix}btagnames VALUES ('33','copyright','站点版权信息','common','','16','text');
INSERT INTO {$tblprefix}btagnames VALUES ('34','cms_icpno','站点备案信息','common','','17','text');
INSERT INTO {$tblprefix}btagnames VALUES ('35','bazscert','备案证书','common','','18','text');
INSERT INTO {$tblprefix}btagnames VALUES ('36','mcharset','站点页面编码','common','','13','text');
INSERT INTO {$tblprefix}btagnames VALUES ('37','cms_version','cms版本编号','common','','14','text');
INSERT INTO {$tblprefix}btagnames VALUES ('38','cms_counter','页面统计器','common','','19','text');
INSERT INTO {$tblprefix}btagnames VALUES ('39','caid','栏目ID','archive','','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('40','chid','模型ID','archive','','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('41','mid','会员ID','archive','','7','int');
INSERT INTO {$tblprefix}btagnames VALUES ('42','mname','会员名称','archive','','8','text');
INSERT INTO {$tblprefix}btagnames VALUES ('43','ucid','个人分类ID','archive','','9','int');
INSERT INTO {$tblprefix}btagnames VALUES ('44','atid','合辑体系ID','archive','','10','int');
INSERT INTO {$tblprefix}btagnames VALUES ('48','rclicks','近期点击数','archive','','13','int');
INSERT INTO {$tblprefix}btagnames VALUES ('49','comments','评论数','archive','','14','int');
INSERT INTO {$tblprefix}btagnames VALUES ('50','scores','平均评分','archive','','15','float');
INSERT INTO {$tblprefix}btagnames VALUES ('51','orders','商品出售量','archive','','16','int');
INSERT INTO {$tblprefix}btagnames VALUES ('52','ordersum','商品出售金额合计','archive','','17','int');
INSERT INTO {$tblprefix}btagnames VALUES ('53','favorites','收藏次数','archive','','18','int');
INSERT INTO {$tblprefix}btagnames VALUES ('54','praises','被赞次数','archive','','19','int');
INSERT INTO {$tblprefix}btagnames VALUES ('55','debases','被贬次数','archive','','20','int');
INSERT INTO {$tblprefix}btagnames VALUES ('56','answers','答案数量','archive','','21','int');
INSERT INTO {$tblprefix}btagnames VALUES ('57','price','商品价格','archive','','22','int');
INSERT INTO {$tblprefix}btagnames VALUES ('58','currency','问题悬赏积分','archive','','24','int');
INSERT INTO {$tblprefix}btagnames VALUES ('59','crid','问题悬赏积分ID','archive','','23','int');
INSERT INTO {$tblprefix}btagnames VALUES ('60','adopts','采用答案数量','archive','','25','int');
INSERT INTO {$tblprefix}btagnames VALUES ('61','closed','问题是否关闭','archive','','26','int');
INSERT INTO {$tblprefix}btagnames VALUES ('62','finishdate','问题结束时间','archive','','27','date');
INSERT INTO {$tblprefix}btagnames VALUES ('63','channel','模型名称','archive','','5','text');
INSERT INTO {$tblprefix}btagnames VALUES ('65','createdate','添加时间','freeinfo','','5','date');
INSERT INTO {$tblprefix}btagnames VALUES ('64','alias','类目别名','cnode','','4','text');
INSERT INTO {$tblprefix}btagnames VALUES ('66','editor','责任编辑','freeinfo','','6','text');
INSERT INTO {$tblprefix}btagnames VALUES ('67','mid','会员ID','freeinfo','','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('68','mname','会员名称','freeinfo','','3','text');
INSERT INTO {$tblprefix}btagnames VALUES ('69','arcurl','信息内容页URL','freeinfo','','4','text');
INSERT INTO {$tblprefix}btagnames VALUES ('70','aid','附属信息ID','freeinfo','','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('71','regip','注册IP','member','','2','text');
INSERT INTO {$tblprefix}btagnames VALUES ('72','lastip','上次登录IP','member','','6','text');
INSERT INTO {$tblprefix}btagnames VALUES ('73','onlinetime','在线时间','member','','10','int');
INSERT INTO {$tblprefix}btagnames VALUES ('74','clicks','点击数','member','','11','int');
INSERT INTO {$tblprefix}btagnames VALUES ('75','regdate','注册时间','member','','4','date');
INSERT INTO {$tblprefix}btagnames VALUES ('76','lastvisit','上次登录时间','member','','5','date');
INSERT INTO {$tblprefix}btagnames VALUES ('77','lastactive','上次激活时间','member','','7','date');
INSERT INTO {$tblprefix}btagnames VALUES ('78','uptotal','已上传量','member','','8','int');
INSERT INTO {$tblprefix}btagnames VALUES ('79','downtotal','已下载量','member','','9','int');
INSERT INTO {$tblprefix}btagnames VALUES ('80','checks','已审文档数量','member','','13','int');
INSERT INTO {$tblprefix}btagnames VALUES ('81','comments','评论数','member','','14','int');
INSERT INTO {$tblprefix}btagnames VALUES ('82','favorites','收藏数','member','','15','int');
INSERT INTO {$tblprefix}btagnames VALUES ('83','purchases','采购商品数','member','','16','int');
INSERT INTO {$tblprefix}btagnames VALUES ('84','answers','答案数','member','','17','int');
INSERT INTO {$tblprefix}btagnames VALUES ('85','freeinfos','附属信息数','member','','18','int');
INSERT INTO {$tblprefix}btagnames VALUES ('86','credits','信用度','member','','19','int');
INSERT INTO {$tblprefix}btagnames VALUES ('87','taxs','付费文档数','member','','20','int');
INSERT INTO {$tblprefix}btagnames VALUES ('88','sales','购买文档数','member','','21','int');
INSERT INTO {$tblprefix}btagnames VALUES ('89','ftaxs','付费附件数','member','','22','int');
INSERT INTO {$tblprefix}btagnames VALUES ('90','fsales','购买附件数','member','','23','int');
INSERT INTO {$tblprefix}btagnames VALUES ('91','cid','评论ID','commu','comment','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('92','aid','文档ID','commu','comment','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('93','mid','会员ID','commu','comment','3','int');
INSERT INTO {$tblprefix}btagnames VALUES ('94','mname','会员名称','commu','comment','4','text');
INSERT INTO {$tblprefix}btagnames VALUES ('95','createdate','添加时间','commu','comment','7','date');
INSERT INTO {$tblprefix}btagnames VALUES ('96','ip','评论IP','commu','comment','8','text');
INSERT INTO {$tblprefix}btagnames VALUES ('97','cid','购买ID','commu','purchase','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('98','aid','文档ID','commu','purchase','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('99','mid','会员ID','commu','purchase','3','int');
INSERT INTO {$tblprefix}btagnames VALUES ('100','mname','会员名称','commu','purchase','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('101','subject','文档标题','commu','purchase','5','text');
INSERT INTO {$tblprefix}btagnames VALUES ('102','price','商品价格','commu','purchase','6','int');
INSERT INTO {$tblprefix}btagnames VALUES ('103','weight','商品重量','commu','purchase','7','float');
INSERT INTO {$tblprefix}btagnames VALUES ('104','nums','购买数量','commu','purchase','8','int');
INSERT INTO {$tblprefix}btagnames VALUES ('105','oid','订单ID','commu','purchase','9','int');
INSERT INTO {$tblprefix}btagnames VALUES ('106','createdate','购买时间','commu','purchase','10','date');
INSERT INTO {$tblprefix}btagnames VALUES ('107','cid','答案ID','commu','answer','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('108','aid','文档ID','commu','answer','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('109','mid','会员ID','commu','answer','3','int');
INSERT INTO {$tblprefix}btagnames VALUES ('110','mname','会员名称','commu','answer','4','text');
INSERT INTO {$tblprefix}btagnames VALUES ('111','subject','文档标题','commu','answer','5','text');
INSERT INTO {$tblprefix}btagnames VALUES ('112','answer','答案内容','commu','answer','6','text');
INSERT INTO {$tblprefix}btagnames VALUES ('113','createdate','答疑时间','commu','answer','7','date');
INSERT INTO {$tblprefix}btagnames VALUES ('114','currency','奖励积分','commu','answer','8','int');
INSERT INTO {$tblprefix}btagnames VALUES ('116','votes','支持票数','commu','answer','10','int');
INSERT INTO {$tblprefix}btagnames VALUES ('119','title','附件说明','other','attachment','2','text');
INSERT INTO {$tblprefix}btagnames VALUES ('120','url_s','图片缩略图URL','other','attachment','3','text');
INSERT INTO {$tblprefix}btagnames VALUES ('121','width','图片宽度','other','attachment','4','text');
INSERT INTO {$tblprefix}btagnames VALUES ('122','height','图片高度','other','attachment','5','text');
INSERT INTO {$tblprefix}btagnames VALUES ('123','vid','投票项目id','other','vote','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('124','caid','投票分类id','other','vote','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('125','subject','投票标题','other','vote','3','text');
INSERT INTO {$tblprefix}btagnames VALUES ('126','content','投票说明','other','vote','4','multitext');
INSERT INTO {$tblprefix}btagnames VALUES ('127','totalnum','总票数','other','vote','5','int');
INSERT INTO {$tblprefix}btagnames VALUES ('128','mid','发起人会员ID','other','vote','6','int');
INSERT INTO {$tblprefix}btagnames VALUES ('129','mname','发起人会员名称','other','vote','7','text');
INSERT INTO {$tblprefix}btagnames VALUES ('130','createdate','投票添加时间','other','vote','8','date');
INSERT INTO {$tblprefix}btagnames VALUES ('131','vopid','投票选项ID','other','vote','9','int');
INSERT INTO {$tblprefix}btagnames VALUES ('132','votenum','投票选项票数','other','vote','11','int');
INSERT INTO {$tblprefix}btagnames VALUES ('133','title','投票选项标题','other','vote','10','text');
INSERT INTO {$tblprefix}btagnames VALUES ('134','input','投票选项控件','other','vote','12','text');
INSERT INTO {$tblprefix}btagnames VALUES ('135','percent','投票选项百分比','other','vote','13','text');
INSERT INTO {$tblprefix}btagnames VALUES ('136','mpnav','分页导航','other','mp','1','text');
INSERT INTO {$tblprefix}btagnames VALUES ('137','mptitle','(文本)分页标题','other','mp','2','text');
INSERT INTO {$tblprefix}btagnames VALUES ('138','rss','类目rss链接','cnode','','5','text');
INSERT INTO {$tblprefix}btagnames VALUES ('140','mppage','分页当前页','other','mp','3','int');
INSERT INTO {$tblprefix}btagnames VALUES ('146','mpacount','分页总记录数','other','mp','9','int');
INSERT INTO {$tblprefix}btagnames VALUES ('141','mpcount','分页总页数','other','mp','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('142','mpstart','分页首页URL','other','mp','5','text');
INSERT INTO {$tblprefix}btagnames VALUES ('143','mpend','分页尾页URL','other','mp','6','text');
INSERT INTO {$tblprefix}btagnames VALUES ('144','mppre','分页上页URL','other','mp','7','text');
INSERT INTO {$tblprefix}btagnames VALUES ('145','mpnext','分页下页URL','other','mp','8','text');
INSERT INTO {$tblprefix}btagnames VALUES ('147','siteurl','当前子站URL','common','','4','text');
INSERT INTO {$tblprefix}btagnames VALUES ('148','cms_abs','主站绝对URL','common','','6','text');
INSERT INTO {$tblprefix}btagnames VALUES ('149','timestamp','当前系统时间戳','common','','20','text');
INSERT INTO {$tblprefix}btagnames VALUES ('150','atmsize','附件大小(K)','archive','','21','text');
INSERT INTO {$tblprefix}btagnames VALUES ('151','downs','下载次数','archive','','21','int');
INSERT INTO {$tblprefix}btagnames VALUES ('152','plays','播放次数','archive','','21','int');
INSERT INTO {$tblprefix}btagnames VALUES ('153','sid','文档所在子站id','archive','','8','int');
INSERT INTO {$tblprefix}btagnames VALUES ('154','sitename','文档所在子站名称','archive','','8','text');
INSERT INTO {$tblprefix}btagnames VALUES ('155','siteurl','文档所在子站URL','archive','','8','text');
INSERT INTO {$tblprefix}btagnames VALUES ('156','bkurl','类目备用页URL','cnode','','3','text');
INSERT INTO {$tblprefix}btagnames VALUES ('157','sid','类目所在子站id','cnode','','6','int');
INSERT INTO {$tblprefix}btagnames VALUES ('158','sitename','类目所在子站名称','cnode','','7','text');
INSERT INTO {$tblprefix}btagnames VALUES ('159','siteurl','类目所在子站URL','cnode','','8','text');
INSERT INTO {$tblprefix}btagnames VALUES ('197','mid','会员ID','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('161','cuid','项目ID','commu','reply','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('163','votes1','投票1','commu','reply','5','int');
INSERT INTO {$tblprefix}btagnames VALUES ('164','votes2','投票2','commu','reply','6','int');
INSERT INTO {$tblprefix}btagnames VALUES ('165','votes3','投票3','commu','reply','7','int');
INSERT INTO {$tblprefix}btagnames VALUES ('166','votes4','投票4','commu','reply','8','int');
INSERT INTO {$tblprefix}btagnames VALUES ('167','votes5','投票5','commu','reply','9','int');
INSERT INTO {$tblprefix}btagnames VALUES ('168','updatedate','更新时间','commu','reply','10','date');
INSERT INTO {$tblprefix}btagnames VALUES ('169','cid','回复ID','commu','reply','3','int');
INSERT INTO {$tblprefix}btagnames VALUES ('170','aid','文档ID','commu','reply','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('172','mid','会员ID','commu','reply','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('173','mname','会员名称','commu','reply','11','text');
INSERT INTO {$tblprefix}btagnames VALUES ('174','createdate','创建时间','commu','reply','12','date');
INSERT INTO {$tblprefix}btagnames VALUES ('175','arcaid','阅读文档ID','commu','reply','13','int');
INSERT INTO {$tblprefix}btagnames VALUES ('176','urcaid','阅读回复ID','commu','reply','14','int');
INSERT INTO {$tblprefix}btagnames VALUES ('177','areply','是否回复','commu','reply','15','int');
INSERT INTO {$tblprefix}btagnames VALUES ('178','aread','是否阅读','commu','reply','16','int');
INSERT INTO {$tblprefix}btagnames VALUES ('179','uread','是否阅读','commu','reply','17','int');
INSERT INTO {$tblprefix}btagnames VALUES ('180','cid','报价ID','commu','offer','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('181','aid','文档ID','commu','offer','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('182','cuid','项目ID','commu','offer','1','int');
INSERT INTO {$tblprefix}btagnames VALUES ('183','oprice','平均价格','commu','offer','1','float');
INSERT INTO {$tblprefix}btagnames VALUES ('184','mid','会员ID','commu','offer','2','int');
INSERT INTO {$tblprefix}btagnames VALUES ('185','mname','会员名称','commu','offer','2','text');
INSERT INTO {$tblprefix}btagnames VALUES ('186','ucid','归类ID','commu','offer','3','int');
INSERT INTO {$tblprefix}btagnames VALUES ('187','votes1','投票1','commu','offer','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('188','votes2','投票2','commu','offer','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('189','votes3','投票3','commu','offer','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('190','votes4','投票4','commu','offer','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('191','votes5','投票5','commu','offer','4','int');
INSERT INTO {$tblprefix}btagnames VALUES ('192','createdate','创建时间','commu','offer','5','date');
INSERT INTO {$tblprefix}btagnames VALUES ('193','updatedate','更新时间','commu','offer','5','date');
INSERT INTO {$tblprefix}btagnames VALUES ('194','enddate','结束时间','commu','offer','5','date');
INSERT INTO {$tblprefix}btagnames VALUES ('198','cid','评论ID','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('199','mname','会员名称','mcommu','comment','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('200','fromid','来源会员ID','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('201','fromname','来源会员名称','mcommu','comment','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('202','ucid','分类ID','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('203','uread','空','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('204','aread','是否阅读','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('205','areply','是否回复','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('206','createdate','创建时间','mcommu','comment','0','date');
INSERT INTO {$tblprefix}btagnames VALUES ('207','cuid','项目ID','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('208','maid','会员档案ID','marchive','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('209','matid','会员档案类型ID','marchive','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('210','cid','回复ID','mcommu','reply','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('211','mid','会员ID','mcommu','reply','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('212','mname','会员名称','mcommu','reply','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('213','fromid','来源会员ID','mcommu','reply','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('214','fromname','来源会员名称','mcommu','reply','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('215','areply','是否回复','mcommu','reply','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('216','aread','是否阅读','mcommu','reply','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('217','createdate','创建时间','mcommu','reply','0','date');
INSERT INTO {$tblprefix}btagnames VALUES ('218','cuid','分类ID','mcommu','reply','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('219','cid','友情链接ID','mcommu','comment','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('220','mid','会员ID','mcommu','flink','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('221','mname','会员名称','mcommu','flink','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('225','createdate','创建时间','mcommu','flink','0','date');
INSERT INTO {$tblprefix}btagnames VALUES ('227','abover','合辑是否完结','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('229','vieworder','所有订单','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('230','updatedate','更新日期','archive','','29','date');
INSERT INTO {$tblprefix}btagnames VALUES ('231','refreshdate','重发布日期','archive','','29','date');
INSERT INTO {$tblprefix}btagnames VALUES ('232','enddate','结束时间','archive','','29','date');
INSERT INTO {$tblprefix}btagnames VALUES ('237','replys','回复次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('238','offers','报价次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('239','mclicks','月点击数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('240','wclicks','周点击数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('241','mcomments','月评论数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('242','wcomments','周评论数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('243','morders','月出售数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('244','worders','周出售数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('245','mordersum','月出售金额总数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('246','wordersum','周出售金额总数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('247','mfavorites','月收藏次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('248','wfavorites','周收藏次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('249','mpraises','月被赞次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('250','wpraises','周被赞次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('251','mdebases','月被贬次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('252','wdebases','周被贬次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('253','mdowns','月下载数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('254','wdowns','周下载数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('255','mplays','月播放次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('256','wplays','周播放次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('257','mreplys','月回复数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('258','wreplys','周回复数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('259','moffers','月报价数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('260','woffers','周报价次数','archive','','29','int');
INSERT INTO {$tblprefix}btagnames VALUES ('261','aclicks','辑内文档点击总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('262','acomments','辑内文档评论总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('263','afavorites','辑内文档收藏次数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('264','aorders','辑内文档出售总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('265','aanswers','辑内文档答案总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('266','aadopts','辑内文档采用答案总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('267','aordersum','辑内文档出售金额总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('268','apraises','辑内文档被赞总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('269','adebases','辑内文档被贬总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('270','adowns','辑内文档下载总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('271','aplays','辑内文档播放总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('272','areplys','辑内文档回复总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('273','aoffers','辑内文档报价总数','archive','','28','int');
INSERT INTO {$tblprefix}btagnames VALUES ('275','startdate','开始时间','freeinfo','','5','int');
INSERT INTO {$tblprefix}btagnames VALUES ('276','enddate','结束时间','freeinfo','','5','date');
INSERT INTO {$tblprefix}btagnames VALUES ('277','vieworder','显示排序','freeinfo','','7','int');
INSERT INTO {$tblprefix}btagnames VALUES ('279','updatedate','更新时间','freeinfo','','5','date');
INSERT INTO {$tblprefix}btagnames VALUES ('280','mchid','会员模型ID','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('281','isfounder','空','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('283','arcallowance','可发限制文档总数','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('284','cuallowance','可发限制回复总数','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('285','cuaddmonth','本月剩余回复数量','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('287','subscribes','空','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('288','fsubscribes','空','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('289','replys','回复数','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('290','offers','报价次数','member','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('291','confirmstr','空','member','','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('294','mid','会员ID','marchive','','0','int');
INSERT INTO {$tblprefix}btagnames VALUES ('295','mname','会员名称','marchive','','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('296','arcurl','档案链接','marchive','','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('297','createdate','创建时间','marchive','','0','date');
INSERT INTO {$tblprefix}btagnames VALUES ('298','updatedate','更新时间','marchive','','0','date');
INSERT INTO {$tblprefix}btagnames VALUES ('299','refreshdate','重刷新时间','marchive','','0','date');
INSERT INTO {$tblprefix}btagnames VALUES ('300','editor','编辑者','marchive','','0','text');
INSERT INTO {$tblprefix}btagnames VALUES ('301','sitename','当前子站名称','common','','5','text');

DROP TABLE IF EXISTS {$tblprefix}catalogs;
CREATE TABLE {$tblprefix}catalogs (
  caid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  sid smallint(5) unsigned NOT NULL default '0',
  `level` tinyint(3) unsigned NOT NULL default '0',
  pid smallint(6) unsigned NOT NULL default '0',
  title char(50) NOT NULL,
  vieworder tinyint(3) unsigned NOT NULL default '0',
  trueorder smallint(6) unsigned NOT NULL default '0',
  chids varchar(255) NOT NULL,
  isframe tinyint(1) unsigned NOT NULL default '0',
  dirname varchar(30) NOT NULL,
  apmid smallint(6) unsigned NOT NULL default '0',
  rpmid smallint(6) unsigned NOT NULL default '0',
  crpmid smallint(6) unsigned NOT NULL default '0',
  dpmid smallint(6) unsigned NOT NULL default '0',
  allowsale tinyint(1) unsigned NOT NULL default '0',
  allowfsale tinyint(1) unsigned NOT NULL default '0',
  taxcp varchar(15) NOT NULL,
  awardcp varchar(15) NOT NULL,
  ftaxcp varchar(15) NOT NULL,
  customurl varchar(50) NOT NULL default '',
  clicks int(10) unsigned NOT NULL default '0',
  archives int(10) unsigned NOT NULL default '0',
  tpls varchar(255) NOT NULL default '',
  PRIMARY KEY  (caid),
  KEY parentid (pid,vieworder)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}channels;
CREATE TABLE {$tblprefix}channels (
  chid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname char(30) NOT NULL,
  remark varchar(80) NOT NULL,
  vieworder smallint(6) unsigned NOT NULL,
  available tinyint(1) NOT NULL default '1',
  userforbidadd tinyint(1) unsigned NOT NULL default '0',
  autocheck smallint(6) NOT NULL default '1',
  autostatic tinyint(1) unsigned NOT NULL default '0',
  apmid smallint(6) unsigned NOT NULL default '0',
  chpmid smallint(6) unsigned NOT NULL default '0',
  chklv tinyint(1) unsigned NOT NULL default '1',
  isalbum tinyint(1) unsigned NOT NULL default '0',
  inchids varchar(255) NOT NULL,
  incoids varchar(255) NOT NULL default '',
  inautocheck tinyint(1) unsigned NOT NULL default '0',
  onlyload tinyint(1) unsigned NOT NULL default '0',
  oneuser tinyint(1) unsigned NOT NULL default '0',
  onlyone tinyint(1) unsigned NOT NULL default '0',
  statsum tinyint(1) unsigned NOT NULL default '0',
  maxnums smallint(6) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  `comment` smallint(6) unsigned NOT NULL default '0',
  reply smallint(6) unsigned NOT NULL default '0',
  offer smallint(6) unsigned NOT NULL default '0',
  report smallint(6) unsigned NOT NULL default '0',
  arctpls varchar(120) NOT NULL default '',
  warctpls varchar(120) NOT NULL default '',
  pretpl varchar(50) NOT NULL,
  srhtpl varchar(50) NOT NULL,
  addtpl varchar(50) NOT NULL default '',
  autoabstract varchar(30) NOT NULL default '0',
  autokeyword varchar(30) NOT NULL default '0',
  autothumb varchar(30) NOT NULL default '0',
  autosize varchar(30) NOT NULL default '0',
  autosizemode tinyint(1) unsigned NOT NULL default '0',
  autobyte varchar(30) NOT NULL,
  baidu varchar(30) NOT NULL,
  fulltxt varchar(30) NOT NULL,
  allowance tinyint(1) unsigned NOT NULL default '0',
  readd tinyint(1) unsigned NOT NULL default '0',
  validperiod tinyint(1) unsigned NOT NULL default '0',
  mindays int(10) NOT NULL default '0',
  maxdays int(10) NOT NULL default '0',
  reinterval int(10) NOT NULL default '0',
  iuids varchar(255) NOT NULL default '',
  coidscp varchar(255) NOT NULL default '',
  imuids varchar(255) NOT NULL default '',
  cpkeeps varchar(255) NOT NULL,
  aitems varchar(255) NOT NULL default '',
  citems varchar(255) NOT NULL default '',
  additems text NOT NULL,
  useredits text NOT NULL,
  acoids varchar(255) NOT NULL default '',
  ccoids varchar(255) NOT NULL default '',
  ucadd varchar(80) NOT NULL,
  uaadd varchar(80) NOT NULL,
  uadetail varchar(80) NOT NULL,
  umdetail varchar(80) NOT NULL,
  usetting text NOT NULL,
  statics varchar(8) NOT NULL default '',
  periods varchar(255) NOT NULL default '',
  addnos varchar(40) NOT NULL default ',,,',
  addnum tinyint(3) NOT NULL default '2',
  novus varchar(8) NOT NULL default '',
  PRIMARY KEY  (chid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}clangs;
CREATE TABLE {$tblprefix}clangs (
  lid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  content text NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid)
) TYPE=MyISAM AUTO_INCREMENT=172;

INSERT INTO {$tblprefix}clangs VALUES ('1','answer_reward','答疑悬赏','0');
INSERT INTO {$tblprefix}clangs VALUES ('2','awardcurrency','奖励积分','0');
INSERT INTO {$tblprefix}clangs VALUES ('3','msite','主站','0');
INSERT INTO {$tblprefix}clangs VALUES ('4','goback','返回','0');
INSERT INTO {$tblprefix}clangs VALUES ('5','closewindow','关闭窗口','0');
INSERT INTO {$tblprefix}clangs VALUES ('6','rightnowjump','立即跳转','0');
INSERT INTO {$tblprefix}clangs VALUES ('7','rightnowgoback','立即返回','0');
INSERT INTO {$tblprefix}clangs VALUES ('8','defaultclosedreason','网站正在维护，请稍后再连接。','0');
INSERT INTO {$tblprefix}clangs VALUES ('9','choose_reward_cutype','请指定正确的悬赏积分类型','0');
INSERT INTO {$tblprefix}clangs VALUES ('10','price','价格','0');
INSERT INTO {$tblprefix}clangs VALUES ('11','weight','重量','0');
INSERT INTO {$tblprefix}clangs VALUES ('12','rewarcurrenval','悬赏积分值','0');
INSERT INTO {$tblprefix}clangs VALUES ('13','question','问题','0');
INSERT INTO {$tblprefix}clangs VALUES ('14','stock','库存','0');
INSERT INTO {$tblprefix}clangs VALUES ('15','questcontnotn','问题内容不能为空','0');
INSERT INTO {$tblprefix}clangs VALUES ('16','rewcurtychdomoarc','悬赏积分类型改变,不要修改文档','0');
INSERT INTO {$tblprefix}clangs VALUES ('17','dontredrewcur','不要减少悬赏积分','0');
INSERT INTO {$tblprefix}clangs VALUES ('18','recusmmiva','悬赏积分小于最小值','0');
INSERT INTO {$tblprefix}clangs VALUES ('19','issutaxfree','发表收费附属信息','0');
INSERT INTO {$tblprefix}clangs VALUES ('20','nolimit','不限','0');
INSERT INTO {$tblprefix}clangs VALUES ('21','lengsmalmilim','长度小于最小限制','0');
INSERT INTO {$tblprefix}clangs VALUES ('22','lenglargmaxlimi','长度大于最大限制','0');
INSERT INTO {$tblprefix}clangs VALUES ('23','smallminilimi','小于最小限制','0');
INSERT INTO {$tblprefix}clangs VALUES ('24','largmaxlimi','大于最大限制','0');
INSERT INTO {$tblprefix}clangs VALUES ('25','attatamosmaminili','附件数量小于最小限制','0');
INSERT INTO {$tblprefix}clangs VALUES ('26','notnull','不能为空','0');
INSERT INTO {$tblprefix}clangs VALUES ('27','liminpda','限输入日期','0');
INSERT INTO {$tblprefix}clangs VALUES ('28','liminpint','限输入整数','0');
INSERT INTO {$tblprefix}clangs VALUES ('29','liminpnum','限输入数字','0');
INSERT INTO {$tblprefix}clangs VALUES ('30','limiinputlett','限输入字母','0');
INSERT INTO {$tblprefix}clangs VALUES ('31','limitinputnumberl','限输入字母与数字','0');
INSERT INTO {$tblprefix}clangs VALUES ('32','limitinputtagtype','限输入字母开头的_字母数字','0');
INSERT INTO {$tblprefix}clangs VALUES ('33','limitinputemail','限输入Email','0');
INSERT INTO {$tblprefix}clangs VALUES ('34','clear','清除','0');
INSERT INTO {$tblprefix}clangs VALUES ('35','selectall','全选','0');
INSERT INTO {$tblprefix}clangs VALUES ('36','based_content_page0','基本内容页','0');
INSERT INTO {$tblprefix}clangs VALUES ('37','content_trace_page0_1','内容追溯页1','0');
INSERT INTO {$tblprefix}clangs VALUES ('38','content_trace_page0_2','内容追溯页2','0');
INSERT INTO {$tblprefix}clangs VALUES ('39','remote_download','远程下载方案','0');
INSERT INTO {$tblprefix}clangs VALUES ('40','notremote','不下载远程文件','0');
INSERT INTO {$tblprefix}clangs VALUES ('41','netsilistpage','网址列表页','0');
INSERT INTO {$tblprefix}clangs VALUES ('42','contensourcpage','内容来源页面','0');
INSERT INTO {$tblprefix}clangs VALUES ('43','resultdealfunc','结果处理函数','0');
INSERT INTO {$tblprefix}clangs VALUES ('44','fiecontgathpatt','字段内容\r\n采集模印','0');
INSERT INTO {$tblprefix}clangs VALUES ('45','replmesssouront','替换信息\r\n来源内容','0');
INSERT INTO {$tblprefix}clangs VALUES ('46','repmessagresulcont','替换信息\r\n=>结果内容','0');
INSERT INTO {$tblprefix}clangs VALUES ('47','lisregigathpatt','列表区域\r\n采集模印','0');
INSERT INTO {$tblprefix}clangs VALUES ('48','liscellsplitag','列表单元\r\n分隔标识','0');
INSERT INTO {$tblprefix}clangs VALUES ('49','cellurlgathpatte','单元链接采集模印','0');
INSERT INTO {$tblprefix}clangs VALUES ('50','celltitlgathepatt','单元标题采集模印','0');
INSERT INTO {$tblprefix}clangs VALUES ('51','downjumfilsty','下载跳转文件样式','0');
INSERT INTO {$tblprefix}clangs VALUES ('52','detail','详情','0');
INSERT INTO {$tblprefix}clangs VALUES ('53','based_msg','基本信息','0');
INSERT INTO {$tblprefix}clangs VALUES ('54','order','排序','0');
INSERT INTO {$tblprefix}clangs VALUES ('55','flash','Flash','0');
INSERT INTO {$tblprefix}clangs VALUES ('56','media','视频','0');
INSERT INTO {$tblprefix}clangs VALUES ('57','text','单行文本','0');
INSERT INTO {$tblprefix}clangs VALUES ('58','multitext','多行文本','0');
INSERT INTO {$tblprefix}clangs VALUES ('59','htmltext','Html文本','0');
INSERT INTO {$tblprefix}clangs VALUES ('60','image_f','单图','0');
INSERT INTO {$tblprefix}clangs VALUES ('61','images','图集','0');
INSERT INTO {$tblprefix}clangs VALUES ('62','flashs','Flash集','0');
INSERT INTO {$tblprefix}clangs VALUES ('63','medias','视频集','0');
INSERT INTO {$tblprefix}clangs VALUES ('64','file_f','单点下载','0');
INSERT INTO {$tblprefix}clangs VALUES ('65','files_f','多点下载','0');
INSERT INTO {$tblprefix}clangs VALUES ('66','select','单项选择','0');
INSERT INTO {$tblprefix}clangs VALUES ('67','mselect','多项选择','0');
INSERT INTO {$tblprefix}clangs VALUES ('68','date_f','日期(时间戳)','0');
INSERT INTO {$tblprefix}clangs VALUES ('69','int','整数','0');
INSERT INTO {$tblprefix}clangs VALUES ('70','float','小数','0');
INSERT INTO {$tblprefix}clangs VALUES ('71','email','Email','0');
INSERT INTO {$tblprefix}clangs VALUES ('72','system','系统','0');
INSERT INTO {$tblprefix}clangs VALUES ('73','tagtype','标识类型','0');
INSERT INTO {$tblprefix}clangs VALUES ('74','date','日期','0');
INSERT INTO {$tblprefix}clangs VALUES ('75','nolimitformat','不限格式','0');
INSERT INTO {$tblprefix}clangs VALUES ('76','number','数字','0');
INSERT INTO {$tblprefix}clangs VALUES ('77','letter','字母','0');
INSERT INTO {$tblprefix}clangs VALUES ('78','numberletter','字母与数字','0');
INSERT INTO {$tblprefix}clangs VALUES ('79','advancedmes','进阶信息','0');
INSERT INTO {$tblprefix}clangs VALUES ('80','attachmentedit','附件编辑','0');
INSERT INTO {$tblprefix}clangs VALUES ('81','coclass','分类','0');
INSERT INTO {$tblprefix}clangs VALUES ('82','usergroup','会员组','0');
INSERT INTO {$tblprefix}clangs VALUES ('83','cocname','分类名称','0');
INSERT INTO {$tblprefix}clangs VALUES ('84','amount','数量','0');
INSERT INTO {$tblprefix}clangs VALUES ('87','plepoimemid','请指定会员ID','0');
INSERT INTO {$tblprefix}clangs VALUES ('88','crpolicy','积分增减策略','0');
INSERT INTO {$tblprefix}clangs VALUES ('89','cash','现金','0');
INSERT INTO {$tblprefix}clangs VALUES ('90','currencyinout','积分充/扣值','0');
INSERT INTO {$tblprefix}clangs VALUES ('91','otherreason','其它原因','0');
INSERT INTO {$tblprefix}clangs VALUES ('92','subscribe','订阅','0');
INSERT INTO {$tblprefix}clangs VALUES ('93','confchoosarchi','请指定正确的文档','0');
INSERT INTO {$tblprefix}clangs VALUES ('94','poinarchnoch','指定的文档未审','0');
INSERT INTO {$tblprefix}clangs VALUES ('95','noarchivbrowpermis','无文档浏览权限','0');
INSERT INTO {$tblprefix}clangs VALUES ('96','subattachwanpaycur','订阅此附件需要支付积分 : &nbsp;&nbsp;','1261388506');
INSERT INTO {$tblprefix}clangs VALUES ('97','younosuatwaencur','<br><br>您没有订阅此附件所需要的足够积分!','0');
INSERT INTO {$tblprefix}clangs VALUES ('98','subsattach','订阅附件','0');
INSERT INTO {$tblprefix}clangs VALUES ('99','saleattach','出售附件','0');
INSERT INTO {$tblprefix}clangs VALUES ('100','lookinittag','查看原始标识','0');
INSERT INTO {$tblprefix}clangs VALUES ('101','lookttype','查看 %s','0');
INSERT INTO {$tblprefix}clangs VALUES ('102','lookselecttag','查看选中标识','0');
INSERT INTO {$tblprefix}clangs VALUES ('103','titleunknown','标题不详','0');
INSERT INTO {$tblprefix}clangs VALUES ('104','noadminbackareapermis','无管理后台权限','0');
INSERT INTO {$tblprefix}clangs VALUES ('105','submit','提交','0');
INSERT INTO {$tblprefix}clangs VALUES ('106','regcode','验证码','0');
INSERT INTO {$tblprefix}clangs VALUES ('107','loginpassword','登录密码','0');
INSERT INTO {$tblprefix}clangs VALUES ('108','adminaccount','管理账号','0');
INSERT INTO {$tblprefix}clangs VALUES ('109','gobackindex','返回首页','0');
INSERT INTO {$tblprefix}clangs VALUES ('110','loginmember','登录会员','0');
INSERT INTO {$tblprefix}clangs VALUES ('111','logoutmember','退出会员','0');
INSERT INTO {$tblprefix}clangs VALUES ('112','currentmember','当前会员','0');
INSERT INTO {$tblprefix}clangs VALUES ('113','clickhere','如果浏览器没有跳转请点这里','0');
INSERT INTO {$tblprefix}clangs VALUES ('114','adminbackarealogoutfin','管理后台退出完成','0');
INSERT INTO {$tblprefix}clangs VALUES ('115','nosubbackareaenterpermis','没有子站后台进入权限','0');
INSERT INTO {$tblprefix}clangs VALUES ('116','adminloginfinish','管理登录完成','0');
INSERT INTO {$tblprefix}clangs VALUES ('117','adminbackareaipforbid','管理后台IP禁止','0');
INSERT INTO {$tblprefix}clangs VALUES ('118','siteoff','网站关闭','0');
INSERT INTO {$tblprefix}clangs VALUES ('119','no_apermission','没有当前项目的管理权限!','0');
INSERT INTO {$tblprefix}clangs VALUES ('120','overquick','操作过于频繁','0');
INSERT INTO {$tblprefix}clangs VALUES ('121','inputbytelennullnotrim','输入字节长度,如为空或0值表示不剪裁','0');
INSERT INTO {$tblprefix}clangs VALUES ('122','operatesucceed','操作成功','0');
INSERT INTO {$tblprefix}clangs VALUES ('123','salestritem','出售%s','0');
INSERT INTO {$tblprefix}clangs VALUES ('124','purchasestritem','购买%s','0');
INSERT INTO {$tblprefix}clangs VALUES ('125','younopurcstriwanenocurr','您没有购买此%s所需要的足够积分!','0');
INSERT INTO {$tblprefix}clangs VALUES ('126','youalrpurchasestritem','您已经购买此%s','0');
INSERT INTO {$tblprefix}clangs VALUES ('127','nousernopurchpermi','游客没有购买权限','0');
INSERT INTO {$tblprefix}clangs VALUES ('128','scoresucceed','评分成功','0');
INSERT INTO {$tblprefix}clangs VALUES ('129','dontrepeatscore','请不要重复评分','0');
INSERT INTO {$tblprefix}clangs VALUES ('130','younoscorepermis','您没有评分权限','0');
INSERT INTO {$tblprefix}clangs VALUES ('131','scorefunclosed','评分功能已关闭','0');
INSERT INTO {$tblprefix}clangs VALUES ('132','nocatasbrowsepermis','无类目浏览权限','0');
INSERT INTO {$tblprefix}clangs VALUES ('133','reportfunclos','举报功能已关闭','0');
INSERT INTO {$tblprefix}clangs VALUES ('134','userchecking','用户等待审核','0');
INSERT INTO {$tblprefix}clangs VALUES ('135','regcodeerror','验证码错误','0');
INSERT INTO {$tblprefix}clangs VALUES ('136','usercnamerepeat','用户名称重复','0');
INSERT INTO {$tblprefix}clangs VALUES ('137','usercnameillegal','用户名称不合规范','0');
INSERT INTO {$tblprefix}clangs VALUES ('138','inputquerystr','请输入查询字串','0');
INSERT INTO {$tblprefix}clangs VALUES ('139','pageparammiss','页面参数不完全','0');
INSERT INTO {$tblprefix}clangs VALUES ('140','salearchive','出售文档','0');
INSERT INTO {$tblprefix}clangs VALUES ('141','subscribearchive','订阅文档','0');
INSERT INTO {$tblprefix}clangs VALUES ('142','younosubsarchivewantenoughcur','<br><br>您没有订阅此文档所需要的足够积分!','0');
INSERT INTO {$tblprefix}clangs VALUES ('143','reloginback','请重新登陆管理后台!','0');
INSERT INTO {$tblprefix}clangs VALUES ('144','catachoose','类目选择','0');
INSERT INTO {$tblprefix}clangs VALUES ('145','albumchoose','合辑选择','0');
INSERT INTO {$tblprefix}clangs VALUES ('146','addpre','前导页','0');
INSERT INTO {$tblprefix}clangs VALUES ('147','cata_choose','请选择栏目或分类','0');
INSERT INTO {$tblprefix}clangs VALUES ('148','be_catalog','所属栏目','0');
INSERT INTO {$tblprefix}clangs VALUES ('149','prompt_msg','提示信息','0');
INSERT INTO {$tblprefix}clangs VALUES ('150','allow_type','选择文档类型添加','0');
INSERT INTO {$tblprefix}clangs VALUES ('151','p_choose','请选择','0');
INSERT INTO {$tblprefix}clangs VALUES ('156','memcnameerror','会员名称错误','0');
INSERT INTO {$tblprefix}clangs VALUES ('153','logout','退出','1260243876');
INSERT INTO {$tblprefix}clangs VALUES ('155','outregmemwanact','站外注册会员,需要激活!','1260243876');
INSERT INTO {$tblprefix}clangs VALUES ('157','passerror','密码错误','1260243876');
INSERT INTO {$tblprefix}clangs VALUES ('159','subarcwantpaycur','订阅此文档需要支付积分 :','1261360139');
INSERT INTO {$tblprefix}clangs VALUES ('160','payfinish','在线支付完成，请查看详情！','1262944014');
INSERT INTO {$tblprefix}clangs VALUES ('161','look','查看','1262943874');
INSERT INTO {$tblprefix}clangs VALUES ('162','wap_login','登陆','1270453604');
INSERT INTO {$tblprefix}clangs VALUES ('163','wap_username','用户名','1270453627');
INSERT INTO {$tblprefix}clangs VALUES ('164','wap_password','密码','1270453642');
INSERT INTO {$tblprefix}clangs VALUES ('165','wap_justlogin','立即登陆','1270453706');
INSERT INTO {$tblprefix}clangs VALUES ('166','wap_register','注册','1270454106');
INSERT INTO {$tblprefix}clangs VALUES ('167','wap_index','首页','1270454127');
INSERT INTO {$tblprefix}clangs VALUES ('168','wap_rightnowgoback','立即返回','1270454251');
INSERT INTO {$tblprefix}clangs VALUES ('169','wap_infotip','提示','1270455068');
INSERT INTO {$tblprefix}clangs VALUES ('170','wap_logout','注销','1270456150');
INSERT INTO {$tblprefix}clangs VALUES ('171','img','图片','1272528505');

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

DROP TABLE IF EXISTS {$tblprefix}cnconfigs;
CREATE TABLE {$tblprefix}cnconfigs (
  cncid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  sid smallint(5) unsigned NOT NULL default '0',
  cname varchar(50) NOT NULL,
  configs text NOT NULL,
  mainline smallint(5) unsigned NOT NULL default '0',
  `level` smallint(5) unsigned NOT NULL default '1',
  vieworder smallint(5) unsigned NOT NULL default '0',
  tpls varchar(255) NOT NULL default '',
  tplsmode tinyint(1) unsigned NOT NULL default '0',
  wtpls varchar(255) NOT NULL default '',
  wtplsmode tinyint(1) unsigned NOT NULL default '0',
  urls varchar(255) NOT NULL default '',
  urlsmode tinyint(1) unsigned NOT NULL default '0',
  statics varchar(255) NOT NULL default '',
  staticsmode tinyint(1) unsigned NOT NULL default '0',
  periods varchar(255) NOT NULL default '',
  periodsmode tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (cncid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}cnfields;
CREATE TABLE {$tblprefix}cnfields (
  fid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  ename char(30) NOT NULL default '',
  cname char(30) NOT NULL,
  iscc tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  isfunc tinyint(1) NOT NULL default '0',
  func text NOT NULL,
  innertext mediumtext NOT NULL,
  fromcode tinyint(1) unsigned NOT NULL default '0',
  issearch tinyint(1) unsigned NOT NULL default '0',
  length smallint(5) unsigned NOT NULL default '0',
  datatype char(10) NOT NULL,
  notnull tinyint(1) unsigned NOT NULL default '0',
  nohtml tinyint(1) unsigned NOT NULL default '0',
  mlimit char(15) NOT NULL,
  regular varchar(80) NOT NULL,
  min varchar(15) NOT NULL,
  max varchar(15) NOT NULL,
  rpid smallint(5) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  guide varchar(80) NOT NULL,
  vdefault varchar(255) NOT NULL default '',
  pmid smallint(6) unsigned NOT NULL default '0',
  useredit tinyint(1) unsigned NOT NULL default '0',
  custom_1 varchar(255) NOT NULL default '',
  custom_2 varchar(255) NOT NULL default '',
  PRIMARY KEY  (fid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}cnodes;
CREATE TABLE {$tblprefix}cnodes (
  cnid int(10) unsigned NOT NULL auto_increment auto_increment,
  sid smallint(5) unsigned NOT NULL default '0',
  alias varchar(50) NOT NULL,
  appurl varchar(80) NOT NULL,
  ename varchar(50) NOT NULL,
  inconfig tinyint(1) unsigned NOT NULL default '1',
  cncids varchar(255) NOT NULL default '',
  mainline smallint(6) unsigned NOT NULL default '0',
  caid smallint(5) unsigned NOT NULL default '0',
  cnlevel tinyint(1) unsigned NOT NULL default '0',
  addnum tinyint(1) NOT NULL default '2',
  tpls varchar(255) NOT NULL default '',
  wtpls varchar(255) NOT NULL default '',
  urls varchar(255) NOT NULL default '',
  needstatics varchar(40) NOT NULL default '',
  statics varchar(6) NOT NULL default '',
  periods varchar(255) NOT NULL default '',
  PRIMARY KEY  (cnid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}coclass;
CREATE TABLE {$tblprefix}coclass (
  ccid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  pid smallint(6) unsigned NOT NULL default '0',
  `level` tinyint(3) unsigned NOT NULL default '0',
  title char(30) NOT NULL,
  isframe tinyint(1) unsigned NOT NULL default '0',
  chids varchar(255) NOT NULL,
  dirname varchar(30) NOT NULL,
  coid smallint(6) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  trueorder smallint(6) unsigned NOT NULL default '0',
  apmid smallint(6) unsigned NOT NULL default '0',
  rpmid smallint(6) unsigned NOT NULL default '0',
  crpmid smallint(6) unsigned NOT NULL default '0',
  dpmid smallint(6) unsigned NOT NULL default '0',
  allowsale tinyint(1) unsigned NOT NULL default '0',
  allowfsale tinyint(1) unsigned NOT NULL default '0',
  taxcp varchar(15) NOT NULL,
  awardcp varchar(15) NOT NULL,
  ftaxcp varchar(15) NOT NULL,
  clicks int(10) unsigned NOT NULL default '0',
  archives int(10) unsigned NOT NULL default '0',
  conditions text NOT NULL,
  PRIMARY KEY  (ccid),
  KEY `level` (`level`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}comments;
CREATE TABLE {$tblprefix}comments (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(30) NOT NULL,
  title varchar(80) NOT NULL,
  content text NOT NULL,
  score int(10) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  updatedate int(10) unsigned NOT NULL default '0',
  ip varchar(15) NOT NULL,
  checked tinyint(1) unsigned NOT NULL default '0',
  floorid smallint(5) unsigned NOT NULL default '0',
  quoteids varchar(255) NOT NULL default '',
  votes1 int(10) unsigned NOT NULL default '0',
  votes2 int(10) unsigned NOT NULL default '0',
  votes3 int(10) unsigned NOT NULL default '0',
  votes4 int(10) unsigned NOT NULL default '0',
  votes5 int(10) unsigned NOT NULL default '0',
  cmtest varchar(10) NOT NULL default '',
  cmtint int(11) NOT NULL default '0',
  uccid9 smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}commus;
CREATE TABLE {$tblprefix}commus (
  cuid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  issystem tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  cclass varchar(30) NOT NULL,
  setting text NOT NULL,
  func text NOT NULL,
  cutpl varchar(50) NOT NULL,
  addtpl varchar(50) NOT NULL default '',
  sortable tinyint(1) unsigned NOT NULL default '0',
  addable tinyint(1) unsigned NOT NULL default '0',
  ch tinyint(1) unsigned NOT NULL default '0',
  isbk tinyint(1) unsigned NOT NULL default '0',
  allowance tinyint(1) NOT NULL default '0',
  ucadd varchar(80) NOT NULL,
  ucvote varchar(80) NOT NULL,
  uadetail varchar(80) NOT NULL,
  umdetail varchar(80) NOT NULL,
  usetting text NOT NULL,
  uconfig varchar(80) NOT NULL,
  PRIMARY KEY  (cuid)
) TYPE=MyISAM AUTO_INCREMENT=30;

INSERT INTO {$tblprefix}commus VALUES ('1','顶/踩','1','1','praise','a:3:{s:5:\"apmid\";s:1:\"0\";s:6:\"repeat\";s:1:\"0\";s:10:\"repeattime\";i:1;}','','','','0','0','0','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('2','评分','1','1','score','a:5:{s:5:\"apmid\";s:1:\"0\";s:6:\"repeat\";s:1:\"0\";s:10:\"repeattime\";i:0;s:8:\"minscore\";i:1;s:8:\"maxscore\";i:10;}','','','','0','0','0','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('3','举报','1','1','report','a:4:{s:5:\"apmid\";s:1:\"0\";s:6:\"repeat\";s:1:\"0\";s:10:\"repeattime\";i:0;s:6:\"citems\";s:0:\"\";}','','','','0','1','1','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('4','收藏','1','1','favorite','a:2:{s:5:\"apmid\";s:1:\"0\";s:3:\"max\";i:300;}','','','','0','0','0','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('5','评论','1','1','comment','a:7:{s:5:\"apmid\";s:1:\"0\";s:9:\"autocheck\";s:1:\"1\";s:6:\"repeat\";s:1:\"0\";s:10:\"repeattime\";i:1;s:10:\"nouservote\";s:1:\"0\";s:10:\"repeatvote\";s:1:\"0\";s:6:\"citems\";s:0:\"\";}','','','','1','1','1','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('6','答疑','1','1','answer','a:10:{s:5:\"apmid\";s:1:\"1\";s:10:\"nouservote\";s:1:\"0\";s:10:\"repeatvote\";s:1:\"0\";s:9:\"minlength\";i:10;s:9:\"maxlength\";i:1000;s:5:\"vdays\";i:10;s:4:\"crid\";s:1:\"2\";s:4:\"mini\";i:1;s:3:\"max\";i:100;s:6:\"credit\";i:2;}','','answers.htm','','1','1','1','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('7','购物','1','1','purchase','a:3:{s:5:\"apmid\";s:1:\"0\";s:6:\"gtmode\";s:1:\"0\";s:6:\"citems\";s:0:\"\";}','','','','1','0','1','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('8','订阅','1','1','subscribe','a:3:{s:5:\"apmid\";s:1:\"0\";s:7:\"autoarc\";s:1:\"1\";s:7:\"autoatm\";s:1:\"1\";}','','','','0','0','0','0','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('20','报价','1','1','offer','a:11:{s:5:\"apmid\";s:1:\"0\";s:9:\"autocheck\";s:1:\"1\";s:5:\"vdays\";i:10;s:8:\"purchase\";s:1:\"0\";s:10:\"nouservote\";s:1:\"1\";s:10:\"repeatvote\";s:1:\"1\";s:7:\"average\";s:1:\"1\";s:6:\"ptable\";s:4:\"main\";s:6:\"pename\";s:6:\"oprice\";s:6:\"citems\";s:0:\"\";s:9:\"useredits\";s:0:\"\";}','','answers.htm','','1','1','1','0','1','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('21','回复','1','1','reply','a:8:{s:9:\"autocheck\";s:1:\"1\";s:5:\"apmid\";s:1:\"0\";s:6:\"repeat\";s:1:\"0\";s:10:\"repeattime\";i:5;s:10:\"nouservote\";s:1:\"0\";s:10:\"repeatvote\";s:1:\"0\";s:6:\"citems\";s:0:\"\";s:9:\"useredits\";s:0:\"\";}','','answers.htm','','1','1','1','0','1','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('22','','1','1','','','','','','0','0','0','1','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('23','','1','1','','','','','','0','0','0','1','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('24','','1','1','','','','','','0','0','0','1','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('25','','1','1','','','','','','0','0','0','1','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('26','','1','1','','','','','','0','0','0','1','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('27','','1','1','','','','','','0','0','0','1','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('28','','1','1','','','','','','0','0','0','1','0','','','','','','');
INSERT INTO {$tblprefix}commus VALUES ('29','','1','1','','','','','','0','0','0','1','0','','','','','','');

DROP TABLE IF EXISTS {$tblprefix}consults;
CREATE TABLE {$tblprefix}consults (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL,
  content mediumtext NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  reply tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}cotypes;
CREATE TABLE {$tblprefix}cotypes (
  coid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname char(30) NOT NULL,
  vieworder smallint(6) unsigned NOT NULL default '0',
  notblank tinyint(1) unsigned NOT NULL default '0',
  sortable tinyint(1) unsigned NOT NULL default '0',
  self_reg tinyint(1) unsigned NOT NULL default '0',
  vmode tinyint(1) unsigned NOT NULL default '0',
  asmode tinyint(1) NOT NULL default '0',
  emode tinyint(1) unsigned NOT NULL default '0',
  permission tinyint(1) unsigned NOT NULL default '0',
  awardcp tinyint(1) unsigned NOT NULL default '0',
  taxcp tinyint(1) unsigned NOT NULL default '0',
  ftaxcp tinyint(1) unsigned NOT NULL default '0',
  sale tinyint(1) unsigned NOT NULL default '0',
  fsale tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (coid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}crprices;
CREATE TABLE {$tblprefix}crprices (
  cpid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(15) NOT NULL,
  cname varchar(30) NOT NULL,
  crid smallint(5) unsigned NOT NULL default '0',
  crvalue float NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '0',
  tax tinyint(1) unsigned NOT NULL default '0',
  award tinyint(1) unsigned NOT NULL default '0',
  sale tinyint(1) unsigned NOT NULL default '0',
  ftax tinyint(1) unsigned NOT NULL default '0',
  fsale tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (cpid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}crprojects;
CREATE TABLE {$tblprefix}crprojects (
  crpid smallint(3) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(10) NOT NULL,
  scrid smallint(3) unsigned NOT NULL default '0',
  scurrency mediumint(8) unsigned NOT NULL default '0',
  ecrid smallint(3) unsigned NOT NULL default '0',
  ecurrency mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (crpid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}cufields;
CREATE TABLE {$tblprefix}cufields (
  fid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  ename char(30) NOT NULL default '',
  cname char(30) NOT NULL,
  cu tinyint(3) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  isfunc tinyint(1) unsigned NOT NULL default '0',
  func text NOT NULL,
  isadmin tinyint(1) unsigned NOT NULL default '0',
  innertext mediumtext NOT NULL,
  fromcode tinyint(1) unsigned NOT NULL default '0',
  length smallint(5) unsigned NOT NULL default '0',
  datatype char(10) NOT NULL,
  notnull tinyint(1) unsigned NOT NULL default '0',
  nohtml tinyint(1) unsigned NOT NULL default '0',
  mlimit char(15) NOT NULL,
  regular varchar(80) NOT NULL,
  min varchar(15) NOT NULL,
  max varchar(15) NOT NULL,
  rpid smallint(5) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  guide varchar(80) NOT NULL,
  vdefault varchar(255) NOT NULL default '',
  pmid smallint(6) unsigned NOT NULL default '0',
  useredit tinyint(1) unsigned NOT NULL default '0',
  custom_1 varchar(255) NOT NULL default '',
  custom_2 varchar(255) NOT NULL default '',
  PRIMARY KEY  (fid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}currencys;
CREATE TABLE {$tblprefix}currencys (
  crid smallint(3) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  unit varchar(30) NOT NULL default '个',
  available tinyint(1) unsigned NOT NULL default '1',
  initial int(8) unsigned NOT NULL default '0',
  saving tinyint(1) unsigned NOT NULL default '0',
  archive float unsigned NOT NULL default '0',
  `comment` float unsigned NOT NULL default '0',
  purchase float unsigned NOT NULL default '0',
  answer float unsigned NOT NULL default '0',
  favorite float unsigned NOT NULL default '0',
  commu float unsigned NOT NULL default '0',
  vote float unsigned NOT NULL default '0',
  freeinfo float unsigned NOT NULL default '0',
  pm float unsigned NOT NULL default '0',
  search float unsigned NOT NULL default '0',
  PRIMARY KEY  (crid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}dbfields;
CREATE TABLE {$tblprefix}dbfields (
  dfid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  ddtable varchar(64) NOT NULL default '',
  ddfield varchar(64) NOT NULL default '',
  ddcomment varchar(255) NOT NULL default '',
  PRIMARY KEY  (dfid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}dbsources;
CREATE TABLE {$tblprefix}dbsources (
  dsid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  dbhost varchar(30) NOT NULL,
  dbuser varchar(50) NOT NULL,
  dbpw varchar(100) NOT NULL,
  dbname varchar(50) NOT NULL,
  dbcharset varchar(10) NOT NULL,
  PRIMARY KEY  (dsid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}domains;
CREATE TABLE {$tblprefix}domains (
  id smallint(6) unsigned NOT NULL auto_increment auto_increment,
  domain varchar(100) NOT NULL default '',
  folder varchar(100) NOT NULL default '',
  isreg tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  remark varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}extracts;
CREATE TABLE {$tblprefix}extracts (
  eid int(10) unsigned NOT NULL auto_increment auto_increment,
  mid int(10) unsigned NOT NULL,
  mname char(15) NOT NULL,
  integral float unsigned NOT NULL,
  total float NOT NULL,
  rate float NOT NULL,
  remark text NOT NULL,
  checkdate int(11) NOT NULL,
  createdate int(11) NOT NULL,
  PRIMARY KEY  (eid),
  KEY mid (mid),
  KEY mid_date (mid,createdate,checkdate)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}faces;
CREATE TABLE {$tblprefix}faces (
  id smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ftid smallint(6) unsigned NOT NULL default '0',
  ename varchar(30) NOT NULL,
  url varchar(30) NOT NULL,
  vieworder tinyint(3) unsigned NOT NULL,
  available tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=25;

INSERT INTO {$tblprefix}faces VALUES ('1','1','[:1_biggrin:]','biggrin.gif','1','1');
INSERT INTO {$tblprefix}faces VALUES ('2','1','[:1_call:]','call.gif','2','1');
INSERT INTO {$tblprefix}faces VALUES ('3','1','[:1_cry:]','cry.gif','3','1');
INSERT INTO {$tblprefix}faces VALUES ('4','1','[:1_curse:]','curse.gif','4','1');
INSERT INTO {$tblprefix}faces VALUES ('5','1','[:1_dizzy:]','dizzy.gif','5','1');
INSERT INTO {$tblprefix}faces VALUES ('6','1','[:1_funk:]','funk.gif','6','1');
INSERT INTO {$tblprefix}faces VALUES ('7','1','[:1_handshake:]','handshake.gif','7','1');
INSERT INTO {$tblprefix}faces VALUES ('8','1','[:1_huffy:]','huffy.gif','8','1');
INSERT INTO {$tblprefix}faces VALUES ('9','1','[:1_hug:]','hug.gif','9','1');
INSERT INTO {$tblprefix}faces VALUES ('10','1','[:1_kiss:]','kiss.gif','10','1');
INSERT INTO {$tblprefix}faces VALUES ('11','1','[:1_lol:]','lol.gif','11','1');
INSERT INTO {$tblprefix}faces VALUES ('12','1','[:1_loveliness:]','loveliness.gif','12','1');
INSERT INTO {$tblprefix}faces VALUES ('13','1','[:1_mad:]','mad.gif','13','1');
INSERT INTO {$tblprefix}faces VALUES ('14','1','[:1_sad:]','sad.gif','14','1');
INSERT INTO {$tblprefix}faces VALUES ('15','1','[:1_shocked:]','shocked.gif','15','1');
INSERT INTO {$tblprefix}faces VALUES ('16','1','[:1_shutup:]','shutup.gif','16','1');
INSERT INTO {$tblprefix}faces VALUES ('17','1','[:1_shy:]','shy.gif','17','1');
INSERT INTO {$tblprefix}faces VALUES ('18','1','[:1_sleepy:]','sleepy.gif','18','1');
INSERT INTO {$tblprefix}faces VALUES ('19','1','[:1_smile:]','smile.gif','19','1');
INSERT INTO {$tblprefix}faces VALUES ('20','1','[:1_sweat:]','sweat.gif','20','1');
INSERT INTO {$tblprefix}faces VALUES ('21','1','[:1_time:]','time.gif','21','1');
INSERT INTO {$tblprefix}faces VALUES ('22','1','[:1_titter:]','titter.gif','22','1');
INSERT INTO {$tblprefix}faces VALUES ('23','1','[:1_tongue:]','tongue.gif','23','1');
INSERT INTO {$tblprefix}faces VALUES ('24','1','[:1_victory:]','victory.gif','24','1');

DROP TABLE IF EXISTS {$tblprefix}facetypes;
CREATE TABLE {$tblprefix}facetypes (
  ftid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  facedir varchar(80) NOT NULL,
  vieworder tinyint(3) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (ftid)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}facetypes VALUES ('1','默认','default','0','1');

DROP TABLE IF EXISTS {$tblprefix}farchives;
CREATE TABLE {$tblprefix}farchives (
  aid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  `subject` varchar(80) NOT NULL,
  chid smallint(5) unsigned NOT NULL default '0',
  fcaid smallint(6) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  editor char(30) NOT NULL,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  startdate int(10) unsigned NOT NULL default '0',
  enddate int(10) unsigned NOT NULL default '0',
  arcurl varchar(100) NOT NULL,
  vieworder mediumint(8) unsigned NOT NULL default '999',
  qnew tinyint(1) unsigned NOT NULL default '1',
  qstate enum('new','dealing','end','close') NOT NULL default 'new',
  updatedate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (aid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}favorites;
CREATE TABLE {$tblprefix}favorites (
  fid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  aid mediumint(8) unsigned NOT NULL default '0',
  bkid mediumint(8) unsigned NOT NULL default '0',
  ucid mediumint(8) unsigned NOT NULL default '0',
  abnew mediumint(8) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (fid),
  KEY mid (mid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}fcatalogs;
CREATE TABLE {$tblprefix}fcatalogs (
  fcaid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title char(50) NOT NULL,
  pid smallint(6) unsigned NOT NULL default '0',
  vieworder tinyint(3) unsigned NOT NULL default '0',
  chid smallint(6) unsigned NOT NULL default '0',
  cumode tinyint(1) unsigned NOT NULL default '0',
  culength smallint(6) unsigned NOT NULL default '0',
  autocheck tinyint(1) unsigned NOT NULL default '0',
  allowupdate tinyint(1) unsigned NOT NULL default '0',
  arctpl varchar(50) NOT NULL,
  apmid smallint(6) unsigned NOT NULL default '0',
  rpmid smallint(6) unsigned NOT NULL default '0',
  nodurat tinyint(1) unsigned NOT NULL default '0',
  ucadd varchar(80) NOT NULL,
  uaadd varchar(80) NOT NULL,
  uadetail varchar(80) NOT NULL,
  umdetail varchar(80) NOT NULL,
  usetting text NOT NULL,
  PRIMARY KEY  (fcaid),
  KEY parentid (vieworder)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}fchannels;
CREATE TABLE {$tblprefix}fchannels (
  chid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname char(30) NOT NULL,
  PRIMARY KEY  (chid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}ffields;
CREATE TABLE {$tblprefix}ffields (
  fid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  ename char(30) NOT NULL default '',
  cname char(30) NOT NULL,
  chid smallint(5) unsigned NOT NULL default '0',
  issystem tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  isfunc tinyint(1) unsigned NOT NULL default '0',
  func text NOT NULL,
  innertext mediumtext NOT NULL,
  fromcode tinyint(1) unsigned NOT NULL default '0',
  length smallint(5) unsigned NOT NULL default '0',
  datatype char(10) NOT NULL,
  notnull tinyint(1) unsigned NOT NULL default '0',
  nohtml tinyint(1) unsigned NOT NULL default '0',
  mlimit char(15) NOT NULL,
  regular varchar(80) NOT NULL,
  min varchar(15) NOT NULL,
  max varchar(15) NOT NULL,
  rpid smallint(5) unsigned NOT NULL default '0',
  isadmin tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  guide varchar(80) NOT NULL,
  vdefault varchar(255) NOT NULL default '',
  pmid smallint(6) unsigned NOT NULL default '0',
  useredit tinyint(1) unsigned NOT NULL default '0',
  custom_1 varchar(255) NOT NULL default '',
  custom_2 varchar(255) NOT NULL default '',
  PRIMARY KEY  (fid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}fields;
CREATE TABLE {$tblprefix}fields (
  fid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  ename char(30) NOT NULL default '',
  cname char(30) NOT NULL,
  chid smallint(6) unsigned NOT NULL default '0',
  issystem tinyint(1) unsigned NOT NULL default '0',
  iscustom tinyint(1) unsigned NOT NULL default '0',
  mcommon tinyint(1) unsigned NOT NULL default '0',
  isfunc tinyint(1) unsigned NOT NULL default '0',
  func text NOT NULL,
  available tinyint(1) unsigned NOT NULL default '0',
  tbl varchar(10) NOT NULL default 'main',
  innertext text NOT NULL,
  fromcode tinyint(1) unsigned NOT NULL default '0',
  issearch tinyint(1) unsigned NOT NULL default '0',
  length smallint(5) unsigned NOT NULL default '0',
  datatype char(10) NOT NULL,
  notnull tinyint(1) unsigned NOT NULL default '0',
  nohtml tinyint(1) unsigned NOT NULL default '0',
  mlimit char(15) NOT NULL,
  regular varchar(80) NOT NULL,
  min varchar(15) NOT NULL,
  max varchar(15) NOT NULL,
  rpid smallint(5) unsigned NOT NULL default '0',
  isadmin tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  guide varchar(80) NOT NULL,
  vdefault varchar(255) NOT NULL default '',
  istxt tinyint(1) unsigned NOT NULL default '0',
  pmid smallint(6) unsigned NOT NULL default '0',
  useredit tinyint(1) unsigned NOT NULL default '0',
  custom_1 varchar(255) NOT NULL default '',
  custom_2 varchar(255) NOT NULL default '',
  PRIMARY KEY  (fid)
) TYPE=MyISAM AUTO_INCREMENT=7;

INSERT INTO {$tblprefix}fields VALUES ('1','subject','标题','0','1','0','1','0','','1','main','','0','0','50','text','1','1','','','','100','0','0','0','0','','','0','0','0','','');
INSERT INTO {$tblprefix}fields VALUES ('2','author','作者','0','0','0','1','0','','1','main','','0','0','50','text','0','1','','','','','0','0','0','0','','','0','0','0','','');
INSERT INTO {$tblprefix}fields VALUES ('3','source','来源','0','0','0','1','0','','1','main','','0','0','50','text','0','1','','','','','0','0','0','0','','','0','0','0','','');
INSERT INTO {$tblprefix}fields VALUES ('4','keywords','关键词','0','0','0','1','0','','1','main','','0','0','50','text','0','1','','','','','0','0','0','0','','','0','0','0','','');
INSERT INTO {$tblprefix}fields VALUES ('5','abstract','摘要','0','0','0','1','0','','1','main','','0','0','1000','multitext','0','0','0','','','','0','0','0','0','','','0','0','0','','');
INSERT INTO {$tblprefix}fields VALUES ('6','thumb','缩略图','0','0','0','1','0','','1','main','','0','0','0','image','0','0','0','','','','1','0','0','0','','','0','0','0','','');

DROP TABLE IF EXISTS {$tblprefix}freeinfos;
CREATE TABLE {$tblprefix}freeinfos (
  fid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  sid smallint(6) unsigned NOT NULL default '0',
  cname varchar(50) NOT NULL,
  tplname varchar(50) NOT NULL,
  arcurl varchar(80) NOT NULL,
  PRIMARY KEY  (fid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}gmissions;
CREATE TABLE {$tblprefix}gmissions (
  gsid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  gmid smallint(3) unsigned NOT NULL default '0',
  sonid smallint(6) unsigned NOT NULL default '0',
  pid smallint(6) unsigned NOT NULL default '0',
  mcharset varchar(10) NOT NULL,
  timeout int(10) unsigned NOT NULL default '5',
  mcookies varchar(255) NOT NULL,
  umode tinyint(1) unsigned NOT NULL default '0',
  uurls mediumtext NOT NULL,
  uregular varchar(255) NOT NULL,
  ufromnum int(10) unsigned NOT NULL default '1',
  utonum int(10) unsigned NOT NULL default '2',
  ufrompage tinyint(1) unsigned NOT NULL default '0',
  udesc tinyint(1) unsigned NOT NULL default '0',
  uinclude varchar(255) NOT NULL,
  uforbid varchar(255) NOT NULL,
  uregion mediumtext NOT NULL,
  uspilit varchar(255) NOT NULL,
  uurltag varchar(255) NOT NULL,
  utitletag varchar(255) NOT NULL,
  uurltag1 varchar(255) NOT NULL,
  uinclude1 varchar(255) NOT NULL,
  uforbid1 varchar(255) NOT NULL,
  uurltag2 varchar(255) NOT NULL,
  uinclude2 varchar(255) NOT NULL,
  uforbid2 varchar(255) NOT NULL,
  mpfield varchar(50) NOT NULL,
  mpmode tinyint(1) unsigned NOT NULL default '0',
  mptag varchar(255) NOT NULL,
  mpinclude varchar(255) NOT NULL,
  mpforbid varchar(255) NOT NULL,
  fsettings mediumtext NOT NULL,
  dvalues mediumtext NOT NULL,
  sid smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (gsid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}gmodels;
CREATE TABLE {$tblprefix}gmodels (
  gmid smallint(3) unsigned NOT NULL auto_increment auto_increment,
  sid smallint(6) unsigned NOT NULL default '0',
  cname varchar(50) NOT NULL,
  chid smallint(6) unsigned NOT NULL default '0',
  atid smallint(6) unsigned NOT NULL default '0',
  gfields text NOT NULL,
  PRIMARY KEY  (gmid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}grouptypes;
CREATE TABLE {$tblprefix}grouptypes (
  gtid int(3) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  issystem tinyint(1) unsigned NOT NULL default '0',
  forbidden tinyint(1) unsigned NOT NULL default '0',
  afunction tinyint(1) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  crid smallint(3) unsigned NOT NULL default '0',
  mchids varchar(255) NOT NULL,
  allowance tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (gtid)
) TYPE=MyISAM AUTO_INCREMENT=3;

INSERT INTO {$tblprefix}grouptypes VALUES ('1','屏蔽组系','1','1','0','1','0','','0');
INSERT INTO {$tblprefix}grouptypes VALUES ('2','管理组系','1','0','1','1','0','5','0');

DROP TABLE IF EXISTS {$tblprefix}gurls;
CREATE TABLE {$tblprefix}gurls (
  guid mediumint(12) NOT NULL auto_increment auto_increment,
  sid smallint(6) unsigned NOT NULL default '0',
  pid mediumint(8) unsigned NOT NULL default '0',
  aid mediumint(8) unsigned NOT NULL default '0',
  abover tinyint(1) unsigned NOT NULL default '0',
  gurl varchar(255) NOT NULL,
  utitle varchar(255) NOT NULL,
  gurl1 varchar(255) NOT NULL,
  gurl2 varchar(255) NOT NULL,
  gsid smallint(3) unsigned NOT NULL default '0',
  adddate int(10) unsigned NOT NULL default '0',
  gatherdate int(10) unsigned NOT NULL default '0',
  outputdate int(10) unsigned NOT NULL default '0',
  contents mediumtext NOT NULL,
  ufids varchar(255) NOT NULL,
  PRIMARY KEY  (guid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}inmurls;
CREATE TABLE {$tblprefix}inmurls (
  imuid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  remark varchar(80) NOT NULL,
  uclass varchar(15) NOT NULL,
  issys tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL,
  setting text NOT NULL,
  tplname varchar(50) NOT NULL,
  onlyview tinyint(1) unsigned NOT NULL default '0',
  mtitle varchar(80) NOT NULL,
  guide text NOT NULL,
  isbk tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (imuid)
) TYPE=MyISAM AUTO_INCREMENT=101;

INSERT INTO {$tblprefix}inmurls VALUES ('1','编辑','编辑编辑编辑','adetail','1','1','0','?action=archive&nimuid=1&aid=','a:1:{s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inmurls VALUES ('2','归辑','归辑归辑归辑','setalbum','1','1','0','?action=setalbum&nimuid=2&aid=','a:4:{s:5:\"chids\";s:0:\"\";s:4:\"sids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inmurls VALUES ('3','内容','合辑内所有内容的管理','content','1','1','0','?action=inarchives&nimuid=3&aid=','a:5:{s:4:\"sids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:47:\"catalog,channel,check,incheck,adddate,view,edit\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inmurls VALUES ('4','加载','加载其它文档到当前合辑','load','1','1','0','?action=loadold&nimuid=4&aid=','a:4:{s:5:\"chids\";s:0:\"\";s:4:\"sids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inmurls VALUES ('5','添加','合辑内添加新文档','inadd','1','1','0','tools/addpre.php?nimuid=5&aid=','','','0','','','0');
INSERT INTO {$tblprefix}inmurls VALUES ('6','回复','当前文档收到所有回复','replys','1','1','0','?action=inreplys&nimuid=6&aid=','a:4:{s:7:\"checked\";s:2:\"-1\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inmurls VALUES ('7','答案','当前提问的所有答案','answers','1','1','0','?action=inanswers&nimuid=7&aid=','a:3:{s:7:\"checked\";s:2:\"-1\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inmurls VALUES ('8','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('9','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('10','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('11','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('12','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('13','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('14','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('15','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('16','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('17','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('18','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('19','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('20','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('21','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('22','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('23','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('24','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('25','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('26','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('27','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('28','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('29','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('30','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('31','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('32','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('33','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('34','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('35','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('36','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('37','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('38','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('39','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('40','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('41','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('42','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('43','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('44','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('45','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('46','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('47','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('48','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('49','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('50','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('51','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('52','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('53','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('54','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('55','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('56','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('57','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('58','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('59','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('60','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('61','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('62','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('63','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('64','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('65','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('66','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('67','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('68','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('69','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('70','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('71','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('72','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('73','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('74','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('75','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('76','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('77','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('78','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('79','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('80','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('81','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('82','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('83','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('84','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('85','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('86','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('87','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('88','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('89','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('90','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('91','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('92','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('93','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('94','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('95','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('96','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('97','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('98','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('99','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inmurls VALUES ('100','','','','1','1','0','','','','0','','','1');

DROP TABLE IF EXISTS {$tblprefix}inurls;
CREATE TABLE {$tblprefix}inurls (
  iuid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  remark varchar(80) NOT NULL,
  uclass varchar(15) NOT NULL,
  issys tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL,
  setting text NOT NULL,
  tplname varchar(50) NOT NULL,
  onlyview tinyint(1) unsigned NOT NULL default '0',
  mtitle varchar(50) NOT NULL,
  guide text NOT NULL,
  isbk tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (iuid)
) TYPE=MyISAM AUTO_INCREMENT=101;

INSERT INTO {$tblprefix}inurls VALUES ('1','归辑','归辑归辑归辑归辑','setalbum','1','1','2','?entry=inarchive&action=setalbum&niuid=1&aid=','a:4:{s:5:\"chids\";s:0:\"\";s:4:\"sids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('2','','','','1','1','3','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('3','加载','辑内加载文档或合辑','load','1','1','5','?entry=inarchive&action=loadold&niuid=3&aid=','a:4:{s:5:\"chids\";s:0:\"\";s:4:\"sids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('4','','','','1','1','100','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('5','评论','档内评论','comments','1','1','6','?entry=inarchive&action=comments&niuid=5&aid=','a:4:{s:7:\"checked\";s:2:\"-1\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('6','购买','购买购买购买购买','purchases','1','1','9','?entry=inarchive&action=purchases&niuid=6&aid=','','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('7','答案','档内答案','answers','1','1','10','?entry=inarchive&action=answers&niuid=7&aid=','a:3:{s:7:\"checked\";s:2:\"-1\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('8','添加','辑内不指定类型的添加','inadd','1','1','1','?entry=addpre&niuid=8&aid=','','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('9','内容','辑内文档或合辑的管理','content','1','1','4','?entry=inarchive&action=archives&niuid=9&aid=','a:5:{s:4:\"sids\";s:0:\"\";s:5:\"chids\";s:0:\"\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('10','报价','指定文档的报价信息管理','offers','1','1','8','?entry=inarchive&action=offers&niuid=10&aid=','a:5:{s:7:\"checked\";s:2:\"-1\";s:5:\"valid\";s:2:\"-1\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('11','回复','指定文档的回复信息管理','replys','1','1','7','?entry=inarchive&action=replys&niuid=11&aid=','a:4:{s:7:\"checked\";s:2:\"-1\";s:7:\"filters\";s:0:\"\";s:5:\"lists\";s:0:\"\";s:8:\"operates\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('12','举报','指定文档的举报信息管理','reports','1','1','11','?entry=inarchive&action=reports&niuid=12&aid=','a:1:{s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('13','编辑','文档的详情编辑','adetail','1','1','0','?entry=archive&action=archivedetail&niuid=13&aid=','a:1:{s:5:\"lists\";s:0:\"\";}','','0','','','0');
INSERT INTO {$tblprefix}inurls VALUES ('14','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('15','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('16','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('17','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('18','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('19','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('20','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('21','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('22','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('23','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('24','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('25','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('26','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('27','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('28','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('29','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('30','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('31','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('32','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('33','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('34','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('35','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('36','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('37','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('38','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('39','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('40','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('41','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('42','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('43','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('44','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('45','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('46','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('47','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('48','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('49','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('50','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('51','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('52','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('53','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('54','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('55','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('56','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('57','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('58','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('59','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('60','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('61','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('62','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('63','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('64','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('65','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('66','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('67','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('68','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('69','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('70','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('71','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('72','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('73','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('74','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('75','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('76','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('77','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('78','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('79','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('80','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('81','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('82','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('83','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('84','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('85','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('86','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('87','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('88','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('89','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('90','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('91','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('92','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('93','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('94','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('95','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('96','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('97','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('98','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('99','','','','1','1','0','','','','0','','','1');
INSERT INTO {$tblprefix}inurls VALUES ('100','','','','1','1','0','','','','0','','','1');

DROP TABLE IF EXISTS {$tblprefix}keywords;
CREATE TABLE {$tblprefix}keywords (
  keyword varchar(20) NOT NULL,
  pcs int(10) unsigned NOT NULL default '1',
  KEY keyword (keyword),
  KEY aid (pcs)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}localfiles;
CREATE TABLE {$tblprefix}localfiles (
  lfid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  extname char(10) NOT NULL,
  ftype char(10) NOT NULL,
  islocal tinyint(1) unsigned NOT NULL default '0',
  maxsize int(10) unsigned NOT NULL default '0',
  minisize int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lfid)
) TYPE=MyISAM AUTO_INCREMENT=26;

INSERT INTO {$tblprefix}localfiles VALUES ('3','gif','image','1','500','1');
INSERT INTO {$tblprefix}localfiles VALUES ('2','jpg','image','1','500','1');
INSERT INTO {$tblprefix}localfiles VALUES ('4','swf','flash','1','10000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('5','fla','flash','1','10000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('6','png','image','1','500','1');
INSERT INTO {$tblprefix}localfiles VALUES ('7','jpeg','image','0','500','1');
INSERT INTO {$tblprefix}localfiles VALUES ('8','bmp','image','0','500','1');
INSERT INTO {$tblprefix}localfiles VALUES ('10','wmv','media','1','60000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('11','asf','media','1','60000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('12','wma','media','1','60000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('13','mpeg','media','1','60000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('14','rmvb','media','1','60000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('15','zip','file','1','50000','0');
INSERT INTO {$tblprefix}localfiles VALUES ('16','rar','file','1','20000','0');
INSERT INTO {$tblprefix}localfiles VALUES ('19','txt','file','1','2000','0');
INSERT INTO {$tblprefix}localfiles VALUES ('20','doc','file','0','100','0');
INSERT INTO {$tblprefix}localfiles VALUES ('21','mpg','media','1','300000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('22','exe','file','0','100','0');
INSERT INTO {$tblprefix}localfiles VALUES ('23','avi','media','1','300000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('24','flv','media','1','360000','1');
INSERT INTO {$tblprefix}localfiles VALUES ('25','wav','media','1','10000','1');

DROP TABLE IF EXISTS {$tblprefix}logerrortimes;
CREATE TABLE {$tblprefix}logerrortimes (
  mname char(15) NOT NULL,
  logip int(10) NOT NULL,
  errortime int(10) unsigned NOT NULL,
  times tinyint(1) unsigned NOT NULL,
  KEY mname (mname)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mafields;
CREATE TABLE {$tblprefix}mafields (
  fid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  ename char(30) NOT NULL default '',
  matid smallint(6) unsigned NOT NULL default '0',
  issystem tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  iscustom tinyint(1) unsigned NOT NULL default '1',
  mcommon tinyint(1) unsigned NOT NULL default '0',
  isfunc tinyint(1) unsigned NOT NULL default '0',
  func text NOT NULL,
  tbl varchar(10) NOT NULL default 'main',
  innertext text NOT NULL,
  fromcode tinyint(1) unsigned NOT NULL default '0',
  issearch tinyint(1) unsigned NOT NULL default '0',
  length smallint(6) unsigned NOT NULL default '0',
  datatype varchar(15) NOT NULL,
  notnull tinyint(1) unsigned NOT NULL default '0',
  nohtml tinyint(1) unsigned NOT NULL default '0',
  mlimit varchar(15) NOT NULL default '0',
  regular varchar(80) NOT NULL,
  min varchar(15) NOT NULL,
  max varchar(15) NOT NULL,
  rpid smallint(5) unsigned NOT NULL default '0',
  isadmin tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  guide varchar(80) NOT NULL,
  vdefault varchar(255) NOT NULL default '',
  pmid smallint(6) unsigned NOT NULL default '0',
  useredit tinyint(1) unsigned NOT NULL default '0',
  custom_1 varchar(255) NOT NULL default '',
  custom_2 varchar(255) NOT NULL default '',
  PRIMARY KEY  (fid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}matypes;
CREATE TABLE {$tblprefix}matypes (
  matid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  autocheck tinyint(1) unsigned NOT NULL default '0',
  autostatic tinyint(1) unsigned NOT NULL default '0',
  allowupdate tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  apmid smallint(6) unsigned NOT NULL default '0',
  rpmid smallint(6) unsigned NOT NULL default '0',
  arctpl varchar(50) NOT NULL,
  parctpl varchar(50) NOT NULL,
  srhtpl varchar(50) NOT NULL,
  addtpl varchar(50) NOT NULL,
  PRIMARY KEY  (matid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mcatalogs;
CREATE TABLE {$tblprefix}mcatalogs (
  mcaid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  maxucid smallint(6) unsigned NOT NULL default '0',
  vieworder smallint(5) unsigned NOT NULL default '0',
  remark varchar(255) NOT NULL default '',
  PRIMARY KEY  (mcaid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mchannels;
CREATE TABLE {$tblprefix}mchannels (
  mchid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  cname char(30) NOT NULL,
  available tinyint(1) unsigned NOT NULL default '1',
  issystem tinyint(1) unsigned NOT NULL default '0',
  userforbidadd tinyint(1) unsigned NOT NULL default '0',
  autocheck tinyint(1) unsigned NOT NULL default '0',
  reply smallint(6) unsigned NOT NULL default '0',
  `comment` smallint(6) unsigned NOT NULL default '0',
  srhtpl varchar(50) NOT NULL default '',
  addtpl varchar(50) NOT NULL,
  additems text NOT NULL,
  useredits text NOT NULL,
  PRIMARY KEY  (mchid)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}mchannels VALUES ('1','基本会员','0','1','0','1','0','0','msearch_1.htm','','','');

DROP TABLE IF EXISTS {$tblprefix}mcnodes;
CREATE TABLE {$tblprefix}mcnodes (
  cnid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename char(12) NOT NULL default '',
  alias varchar(30) NOT NULL default '',
  appurl varchar(80) NOT NULL default '',
  mcnvar char(10) NOT NULL default 'caid',
  mcnid smallint(6) unsigned NOT NULL default '0',
  addnum tinyint(1) unsigned NOT NULL default '0',
  tpls varchar(30) NOT NULL default '',
  urls varchar(255) NOT NULL default '',
  needstatics varchar(20) NOT NULL default '',
  statics varchar(2) NOT NULL default '',
  periods varchar(80) NOT NULL default '',
  PRIMARY KEY  (cnid),
  KEY ename (ename)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mcomments;
CREATE TABLE {$tblprefix}mcomments (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  fromid mediumint(8) unsigned NOT NULL default '0',
  fromname varchar(15) NOT NULL default '',
  ucid mediumint(8) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  floorid smallint(5) unsigned NOT NULL default '0',
  quoteids varchar(255) NOT NULL default '',
  uread tinyint(1) unsigned NOT NULL default '0',
  aread tinyint(1) unsigned NOT NULL default '0',
  areply tinyint(1) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mcommus;
CREATE TABLE {$tblprefix}mcommus (
  cuid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL default '',
  issystem tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  cclass varchar(30) NOT NULL default '',
  setting text NOT NULL,
  func text NOT NULL,
  cutpl varchar(50) NOT NULL default '',
  addtpl varchar(50) NOT NULL default '',
  sortable tinyint(1) unsigned NOT NULL default '0',
  addable tinyint(1) unsigned NOT NULL default '0',
  ch tinyint(1) unsigned NOT NULL default '0',
  isbk tinyint(1) unsigned NOT NULL default '0',
  ucadd varchar(80) NOT NULL,
  ucvote varchar(80) NOT NULL,
  uadetail varchar(80) NOT NULL,
  umdetail varchar(80) NOT NULL,
  usetting text NOT NULL,
  uconfig varchar(80) NOT NULL,
  PRIMARY KEY  (cuid)
) TYPE=MyISAM AUTO_INCREMENT=11;

INSERT INTO {$tblprefix}mcommus VALUES ('1','评分','1','1','score','a:3:{s:5:\"apmid\";s:1:\"0\";s:8:\"norepeat\";s:1:\"0\";s:10:\"repeattime\";i:1;}','','','','0','0','0','0','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('2','好友','1','1','friend','a:4:{s:5:\"apmid\";s:1:\"0\";s:9:\"autocheck\";s:1:\"1\";s:3:\"max\";s:2:\"30\";s:6:\"fields\";s:0:\"\";}','','','','1','0','0','0','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('3','空间友情链接','1','1','flink','a:2:{s:5:\"apmid\";s:1:\"0\";s:6:\"fields\";s:0:\"\";}','','','','1','1','0','0','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('4','评论','1','1','comment','a:5:{s:5:\"apmid\";s:1:\"0\";s:9:\"autocheck\";i:1;s:8:\"norepeat\";s:1:\"1\";s:10:\"repeattime\";i:0;s:6:\"fields\";s:0:\"\";}','','','','1','1','1','0','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('5','回复','1','1','reply','a:5:{s:5:\"apmid\";s:1:\"0\";s:9:\"autocheck\";s:1:\"1\";s:8:\"norepeat\";s:1:\"1\";s:10:\"repeattime\";i:0;s:6:\"fields\";s:0:\"\";}','','','','1','1','1','0','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('6','举报','1','1','report','a:4:{s:5:\"apmid\";s:1:\"0\";s:8:\"norepeat\";s:1:\"0\";s:10:\"repeattime\";i:0;s:6:\"fields\";s:0:\"\";}','','','','0','1','0','0','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('7','收藏','1','1','favorite','a:2:{s:3:\"max\";i:30;s:5:\"apmid\";s:1:\"0\";}','','','','1','0','0','0','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('8','','1','1','','','','','','0','0','0','1','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('9','','1','1','','','','','','0','0','0','1','','','','','','');
INSERT INTO {$tblprefix}mcommus VALUES ('10','','1','1','','','','','','0','0','0','1','','','','','','');

DROP TABLE IF EXISTS {$tblprefix}mconfigs;
CREATE TABLE {$tblprefix}mconfigs (
  varname varchar(30) NOT NULL,
  `value` text NOT NULL,
  cftype char(10) NOT NULL,
  PRIMARY KEY  (varname)
) TYPE=MyISAM;

INSERT INTO {$tblprefix}mconfigs VALUES ('cms_icpno','<a href=\"http://www.miibeian.gov.cn\" target=\"_blank\">粤ICP备09026950号</a>','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmslogo','images/common/logo.gif','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('bazscert','备案证书bazs.cert文件','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('copyright','Copyright &#169; 2008-2015 上海钒斯网络科技有限公司 All rights reserved.','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('monthstats','','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('hostname','网站主站名称','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('hosturl','http://192.168.1.60','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmsname','主站','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmsurl','/v35i/','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('rewritephp','','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmstitle','PHP网站管理系统','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmskeyword','网站管理系统','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmsdescription','具有独特的设计理念,完全由用户自定义文档模型,多重X多层的自由类目系统,用户自定义多重系统合辑,自定义模型的网站附属信息发布系统,完全界面化的模板设计系统,类目内允许多种文档模型混排','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmsclosed','0','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('cmsclosedreason','正在进行网站维护，请稍后再访问，谢谢光临！','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('registerclosed','0','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('regclosedreason','本站暂停注册新会员，敬请留意网站公告的开放注册通知。','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('censoruser','','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('regcode_width','60','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('regcode_height','25','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('arcautoabstract','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('arcautothumb','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('hotkeywords','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('virtualurl','1','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('dir_userfile','userfiles','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('path_userfile','day','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('backupdir','3a5a95','');
INSERT INTO {$tblprefix}mconfigs VALUES ('watermarktype','1','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('upload_nouser','0','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('marchtmlurl','{$matid}/{$y}{$m}/{$maid}-{$page}.html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('marchtmldir','marchives','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('watermarkstatus','0','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('authkey','b818bauH59gpz0ye','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('watermarktrans','65','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('watermarkquality','80','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('gzipenable','1','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('dateformat','Y-n-j','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('timeformat','H:i','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('msgforwordtime','1500','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('atpp','20','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mrowpp','15','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mmaxpage','100','center');
INSERT INTO {$tblprefix}mconfigs VALUES ('templatedir','default','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('htmldir','html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cache1circle','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cache2circle','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('liststaticnum','5','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('listcachenum','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('archtmlmode','month','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cachemscircle','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mslistcachenum','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('adminipaccess','','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('rss_enabled','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('enableupdatecheck','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('enableupdatecopy','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('autoabstractlength','200','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('debugtag','1','tpl');
INSERT INTO {$tblprefix}mconfigs VALUES ('commentmaxlength','0','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('commentminlength','0','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('enabelstat','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('clickscachetime','10','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('mclickscircle','20','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('onlinehold','900','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('onlinetimecircle','10','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('hometpl','','tpl');
INSERT INTO {$tblprefix}mconfigs VALUES ('cnodestat','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('cnodestatcircle','12','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('onlineautosaving','1','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('player_width','500','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('player_height','400','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('enable_pptout','0','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptout_file','phpwind','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptout_charset','gbk','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptout_url','http://192.168.1.60/pw632/','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptout_key','4XSaL4BaJWnCKX26','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('enable_pptin','0','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptin_url','http://192.168.1.60/ihbcms/','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptin_register','register.php','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptin_login','login.php','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptin_logout','login.php?action=logout','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptin_key','P9ZXKrHGpFCG9vGx','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('pptin_expire','60','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('rss_ttl','30','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('search_max','200','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('search_repeat','5','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('atm_smallsite','','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('cms_regcode','','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('mcenterlogo','images/adminm/logo_member.png','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('nousersearch','0','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('amsgforwordtime','1250','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mmsgforwordtime','1500','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mcforbids','','center');
INSERT INTO {$tblprefix}mconfigs VALUES ('uclasslength','8','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_api','http://192.168.1.76/uc','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('enable_uc','0','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('clearoldcache','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('enablestatic','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('enableship','1','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('enablestock','1','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('cartmaxlimited','10','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('cachejscircle','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('jsrefsource','','tpl');
INSERT INTO {$tblprefix}mconfigs VALUES ('cnprow','10','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('sid_self','0','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('outextcredits','a:2:{i:0;a:8:{s:9:\"appiddesc\";s:1:\"6\";s:10:\"creditdesc\";s:1:\"2\";s:9:\"creditsrc\";s:1:\"2\";s:5:\"title\";s:12:\"Discuz! 金钱\";s:4:\"unit\";s:2:\"个\";s:8:\"ratiosrc\";s:2:\"10\";s:9:\"ratiodesc\";s:2:\"10\";s:5:\"ratio\";s:1:\"1\";}i:1;a:8:{s:9:\"appiddesc\";s:1:\"5\";s:10:\"creditdesc\";s:1:\"1\";s:9:\"creditsrc\";s:1:\"1\";s:5:\"title\";s:13:\"个人家园 积分\";s:4:\"unit\";s:2:\"个\";s:8:\"ratiosrc\";s:1:\"1\";s:9:\"ratiodesc\";s:1:\"1\";s:5:\"ratio\";s:1:\"1\";}}','uc');
INSERT INTO {$tblprefix}mconfigs VALUES ('autorelates','','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('atmbrowser','1','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('catahidden','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mspacedisabled','0','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_smtp','','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_ip','192.168.1.76','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_dbhost','192.168.1.76','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_dbname','uc','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_dbuser','08cms','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_dbpwd','','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_dbpre','uc_','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_appid','4','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('uc_key','73P9dd4cy8abgb72k67417785168Oda191EbK4Qe8aM1Zea3pc45weYcY0Senei1','ppt');
INSERT INTO {$tblprefix}mconfigs VALUES ('cms_upurl','#','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_enabled','0','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_host','192.168.1.55','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_port','21','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_user','08cms','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_password','','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_timeout','0','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_pasv','0','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_ssl','0','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_dir','./userfiles','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('ftp_url','http://ftp.z/userfiles','upload');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_mode','2','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_port','25','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_auth','1','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_from','','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_user','','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_pwd','','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_delimiter','1','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('mail_silent','0','mail');
INSERT INTO {$tblprefix}mconfigs VALUES ('homedefault','index{$page}.html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cnhtmldir','html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('infohtmldir','info','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('ineedstatic','1248185858','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('indexcircle','10','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cnindexcircle','60','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cnlistcircle','180','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('archivecircle','300','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('albumstatcircle','1','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('weekstats','','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('albumstats','','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('timezone','-8','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('arcplusnum','2','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('arccustomurl','{$topdir}/{$y}{$m}/{$aid}/{$addno}-{$page}.html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('ca_vmode','2','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('aallowfloatwin','1','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('afloatwinwidth','800','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('afloatwinheight','600','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mallowfloatwin','1','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mfloatwinwidth','800','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mfloatwinheight','600','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('css_dir','css','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('js_dir','js','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('maxuclassnum','30','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cu_nowmonth','5','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('mmenufilter','1','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('last_patch','20100520','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('cfg_paymode','7','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('shipingfee1','0','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('shipingfee2','0','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('shipingfee3','0','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('cartkey','U20Ew75Fw7dEEueXOKoa5Qvk78Iu82oi','pay');
INSERT INTO {$tblprefix}mconfigs VALUES ('cotypestats','','basic');
INSERT INTO {$tblprefix}mconfigs VALUES ('maxerrtimes','3','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('minerrtime','14400','visit');
INSERT INTO {$tblprefix}mconfigs VALUES ('max_addno','2','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cnlistdefault','{$page}.html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('bklistdefault','bk_{$page}.html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('hiddensinurl','index.html,index.htm','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('hiddenfirstpage','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mcnneedstatic','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cn_max_addno','2','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cn_urls','{$cndir}/index{$page}.html,{$cndir}/{$page}.html,{$cndir}/bk_{$page}.html','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('cn_periods','60,180,180','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mcnindexcircle','60','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('toolsdir','tools','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('mspacedir','mspace','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('memberdir','member','site');
INSERT INTO {$tblprefix}mconfigs VALUES ('foundercontent','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('max_chklv','1','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('mcn_max_addno','0','view');
INSERT INTO {$tblprefix}mconfigs VALUES ('disable_htmldir','0','view');

DROP TABLE IF EXISTS {$tblprefix}mcrecords;
CREATE TABLE {$tblprefix}mcrecords (
  crid int(11) NOT NULL auto_increment auto_increment,
  mid int(11) NOT NULL,
  mname char(15) NOT NULL,
  mcid int(11) NOT NULL,
  needtime int(11) NOT NULL,
  checktime int(11) NOT NULL,
  certdata text NOT NULL,
  PRIMARY KEY  (crid),
  KEY `check` (mid,checktime)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mcufields;
CREATE TABLE {$tblprefix}mcufields (
  fid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(30) NOT NULL default '',
  cname varchar(30) NOT NULL default '',
  cu tinyint(3) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  isfunc tinyint(1) unsigned NOT NULL default '0',
  func text NOT NULL,
  isadmin tinyint(1) unsigned NOT NULL default '0',
  innertext text NOT NULL,
  fromcode tinyint(1) unsigned NOT NULL default '0',
  length smallint(5) unsigned NOT NULL default '0',
  datatype char(10) NOT NULL default '',
  notnull tinyint(1) unsigned NOT NULL default '0',
  nohtml tinyint(1) unsigned NOT NULL default '0',
  mlimit char(15) NOT NULL default '',
  regular varchar(80) NOT NULL default '',
  min varchar(15) NOT NULL default '',
  max varchar(15) NOT NULL default '',
  rpid smallint(5) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  guide varchar(80) NOT NULL default '',
  vdefault varchar(255) NOT NULL default '',
  pmid smallint(6) unsigned NOT NULL default '0',
  useredit tinyint(1) unsigned NOT NULL default '0',
  custom_1 varchar(255) NOT NULL default '',
  custom_2 varchar(255) NOT NULL default '',
  PRIMARY KEY  (fid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}members;
CREATE TABLE {$tblprefix}members (
  mid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mchid smallint(6) unsigned NOT NULL default '1',
  mname char(15) NOT NULL,
  isfounder tinyint(1) unsigned NOT NULL default '0',
  `password` char(32) NOT NULL,
  email char(50) NOT NULL,
  mtcid smallint(6) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  dirname char(10) NOT NULL default '',
  regip char(15) NOT NULL,
  lastip char(15) NOT NULL,
  onlinetime mediumint(8) unsigned NOT NULL default '0',
  clicks mediumint(8) unsigned NOT NULL default '0',
  regdate int(10) NOT NULL default '0',
  lastvisit int(10) NOT NULL default '0',
  lastactive int(10) unsigned NOT NULL default '0',
  currency0 float NOT NULL default '0',
  repus int(10) NOT NULL default '0',
  rgid tinyint(3) unsigned NOT NULL default '1',
  uptotal int(10) unsigned NOT NULL default '0',
  downtotal int(10) unsigned NOT NULL default '0',
  arcallowance int(10) NOT NULL default '0',
  cuallowance int(10) NOT NULL default '0',
  cuaddmonth int(10) NOT NULL default '0',
  grouptype1 smallint(5) NOT NULL default '0',
  grouptype1date int(10) NOT NULL default '0',
  grouptype2 smallint(5) NOT NULL default '0',
  grouptype2date int(10) NOT NULL default '0',
  PRIMARY KEY  (mid),
  UNIQUE KEY mname (mname)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}members VALUES ('1','1','admin','1','c3284d0f94606de1fd2af172aba15bf3','admin@domain.com','0','1','','','192.168.1.60','0','0','1273830103','1273830132','1274942789','0','0','1','0','0','0','0','0','0','0','0','0');

DROP TABLE IF EXISTS {$tblprefix}members_1;
CREATE TABLE {$tblprefix}members_1 (
  mid mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (mid)
) TYPE=MyISAM;

INSERT INTO {$tblprefix}members_1 VALUES ('1');

DROP TABLE IF EXISTS {$tblprefix}members_sub;
CREATE TABLE {$tblprefix}members_sub (
  mid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  msclicks int(10) unsigned NOT NULL default '0',
  archives int(10) unsigned NOT NULL default '0',
  checks int(10) unsigned NOT NULL default '0',
  comments int(10) unsigned NOT NULL default '0',
  scores int(10) unsigned NOT NULL default '0',
  favorites int(10) unsigned NOT NULL default '0',
  purchases int(10) unsigned NOT NULL default '0',
  answers int(10) unsigned NOT NULL default '0',
  freeinfos int(10) unsigned NOT NULL default '0',
  credits int(10) unsigned NOT NULL default '0',
  subscribes int(10) unsigned NOT NULL default '0',
  fsubscribes int(10) unsigned NOT NULL default '0',
  replys int(10) unsigned NOT NULL default '0',
  offers int(10) unsigned NOT NULL default '0',
  mscores1 int(10) unsigned NOT NULL default '0',
  mscores2 int(10) unsigned NOT NULL default '0',
  mscores3 int(10) unsigned NOT NULL default '0',
  mscores4 int(10) unsigned NOT NULL default '0',
  mscores5 int(10) unsigned NOT NULL default '0',
  mavgscore float unsigned NOT NULL default '0',
  confirmstr char(20) NOT NULL,
  ordermode tinyint(1) NOT NULL,
  shipingfee1 int(11) NOT NULL default '-1',
  shipingfee2 int(11) NOT NULL default '-1',
  shipingfee3 int(11) NOT NULL default '-1',
  paymode int(11) NOT NULL default '0',
  alipay varchar(50) NOT NULL default '',
  alipid char(16) NOT NULL default '',
  alikeyt varchar(50) NOT NULL default '',
  tenpay varchar(50) NOT NULL default '',
  tenkeyt varchar(50) NOT NULL default '',
  caid smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (mid)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}members_sub VALUES ('1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','','0','-1','-1','-1','0','','','','','','0');

DROP TABLE IF EXISTS {$tblprefix}memcerts;
CREATE TABLE {$tblprefix}memcerts (
  mcid int(11) NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  `level` smallint(6) NOT NULL,
  icon varchar(50) NOT NULL,
  remark varchar(255) NOT NULL,
  mchids varchar(255) NOT NULL,
  mobile varchar(25) NOT NULL,
  email varchar(25) NOT NULL,
  `fields` varchar(255) NOT NULL,
  PRIMARY KEY  (mcid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}menus;
CREATE TABLE {$tblprefix}menus (
  mnid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  url varchar(255) NOT NULL,
  mtid smallint(6) unsigned NOT NULL default '0',
  issub tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `fixed` tinyint(1) unsigned NOT NULL default '0',
  isbk tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (mnid)
) TYPE=MyISAM AUTO_INCREMENT=116;

INSERT INTO {$tblprefix}menus VALUES ('1','文档合辑交互','javascript://content','1','0','1','0','1','0');
INSERT INTO {$tblprefix}menus VALUES ('2','内容管理','javascript://content','2','1','1','1','1','0');
INSERT INTO {$tblprefix}menus VALUES ('3','插件管理','javascript://fcontent','3','0','1','0','1','0');
INSERT INTO {$tblprefix}menus VALUES ('4','会员管理','javascript://mcontent','4','0','1','0','1','0');
INSERT INTO {$tblprefix}menus VALUES ('5','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('6','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('7','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('8','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('9','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('10','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('11','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('12','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('13','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('14','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('15','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('16','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('17','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('18','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('19','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('20','','','0','0','1','0','0','1');
INSERT INTO {$tblprefix}menus VALUES ('21','积分充/扣值','?entry=currencys&action=currencysaving','17','0','1','8','0','0');
INSERT INTO {$tblprefix}menus VALUES ('22','数据库相关','?entry=database&action=dbexport','17','0','1','13','0','0');
INSERT INTO {$tblprefix}menus VALUES ('26','运行记录','?entry=records&action=badlogin','17','0','1','15','0','0');
INSERT INTO {$tblprefix}menus VALUES ('27','采集管理','?entry=gmissions&action=gmissionsedit','17','0','1','12','0','0');
INSERT INTO {$tblprefix}menus VALUES ('28','积分设置','?entry=currencys&action=currencysedit','15','0','1','5','0','0');
INSERT INTO {$tblprefix}menus VALUES ('29','会员模型','?entry=mchannels&action=mchannelsedit','15','0','1','6','0','0');
INSERT INTO {$tblprefix}menus VALUES ('30','商品订单','?entry=orders&action=ordersedit','17','0','1','2','0','0');
INSERT INTO {$tblprefix}menus VALUES ('31','会员组系设置','?entry=grouptypes&action=grouptypesedit','15','0','1','7','0','0');
INSERT INTO {$tblprefix}menus VALUES ('32','内容权限方案','?entry=permissions&action=permissionsedit','18','0','1','4','0','0');
INSERT INTO {$tblprefix}menus VALUES ('33','会员交互配置','?entry=mcommus&action=mcommusedit','15','0','1','8','0','0');
INSERT INTO {$tblprefix}menus VALUES ('34','会员变更方案','?entry=mprojects&action=mprojectsedit','15','0','1','10','0','0');
INSERT INTO {$tblprefix}menus VALUES ('35','文档模型','?entry=channels&action=channeledit','15','0','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('36','栏目管理','?entry=catalogs&action=catalogedit','15','0','1','1','0','0');
INSERT INTO {$tblprefix}menus VALUES ('37','SiteMap地图','?entry=sitemaps&action=sitemapsedit','17','0','1','14','0','0');
INSERT INTO {$tblprefix}menus VALUES ('38','类系管理','?entry=cotypes&action=cotypesedit','15','0','1','2','0','0');
INSERT INTO {$tblprefix}menus VALUES ('39','管理后台设置','?entry=backparams&action=bkparams','18','0','1','2','0','0');
INSERT INTO {$tblprefix}menus VALUES ('40','节点管理','?entry=cnodes&action=cnodescommon','15','0','1','3','0','0');
INSERT INTO {$tblprefix}menus VALUES ('42','文档交互配置','?entry=commus&action=commusedit','15','0','1','4','0','0');
INSERT INTO {$tblprefix}menus VALUES ('43','网站参数设置','?entry=mconfigs&action=cfsite','18','0','1','1','0','0');
INSERT INTO {$tblprefix}menus VALUES ('44','页面静态','?entry=static&action=index','17','0','1','1','0','0');
INSERT INTO {$tblprefix}menus VALUES ('106','重建系统缓存','?entry=rebuilds','18','0','1','10','0','0');
INSERT INTO {$tblprefix}menus VALUES ('48','模板相关','?entry=csstpls','16','0','1','4','0','0');
INSERT INTO {$tblprefix}menus VALUES ('51','原始标识','?entry=btags','16','0','1','6','0','0');
INSERT INTO {$tblprefix}menus VALUES ('107','空间模板','?entry=mtconfigs&action=mtconfigsedit','16','0','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('53','特殊标识','?entry=mtags&action=mtagsedit&ttype=utag','16','0','1','7','0','0');
INSERT INTO {$tblprefix}menus VALUES ('54','复合标识','?entry=mtags&action=mtagsedit&ttype=ctag','16','0','1','8','0','0');
INSERT INTO {$tblprefix}menus VALUES ('55','分页标识','?entry=mtags&action=mtagsedit&ttype=ptag','16','0','1','9','0','0');
INSERT INTO {$tblprefix}menus VALUES ('56','插件架构','?entry=fchannels&action=fchannelsedit','15','0','1','14','0','0');
INSERT INTO {$tblprefix}menus VALUES ('57','区块标识','?entry=mtags&action=mtagsedit&ttype=rtag','16','0','1','10','0','0');
INSERT INTO {$tblprefix}menus VALUES ('59','投票管理','?entry=votes&action=votesedit','17','0','1','4','0','0');
INSERT INTO {$tblprefix}menus VALUES ('60','站内短信','?entry=pms&action=batchpms','17','0','1','5','0','0');
INSERT INTO {$tblprefix}menus VALUES ('61','会员中心设置','?entry=backparams&action=mcparams','18','0','1','3','0','0');
INSERT INTO {$tblprefix}menus VALUES ('62','子站参数','?entry=mconfigs&action=cfsite','20','1','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('63','子站路径','?entry=mconfigs&action=cfview','20','1','1','1','0','0');
INSERT INTO {$tblprefix}menus VALUES ('109','常用标识','?entry=usualtags','16','0','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('66','文档模型','?entry=channels&action=channeledit','20','1','1','4','0','0');
INSERT INTO {$tblprefix}menus VALUES ('67','栏目管理','?entry=catalogs&action=catalogedit','20','1','1','2','0','0');
INSERT INTO {$tblprefix}menus VALUES ('68','节点管理','?entry=cnodes&action=cnodescommon','20','1','1','3','0','0');
INSERT INTO {$tblprefix}menus VALUES ('70','文档交互设置','?entry=commus&action=commusedit','20','1','1','5','0','0');
INSERT INTO {$tblprefix}menus VALUES ('71','采集管理','?entry=gmodels&action=gmodeledit','23','1','1','1','0','0');
INSERT INTO {$tblprefix}menus VALUES ('72','模板相关','?entry=csstpls','21','1','1','3','0','0');
INSERT INTO {$tblprefix}menus VALUES ('73','原始标识','?entry=btags','21','1','1','4','0','0');
INSERT INTO {$tblprefix}menus VALUES ('74','特殊标识','?entry=mtags&action=mtagsedit&ttype=utag','21','1','1','5','0','0');
INSERT INTO {$tblprefix}menus VALUES ('75','复合标识','?entry=mtags&action=mtagsedit&ttype=ctag','21','1','1','6','0','0');
INSERT INTO {$tblprefix}menus VALUES ('76','分页标识','?entry=mtags&action=mtagsedit&ttype=ptag','21','1','1','7','0','0');
INSERT INTO {$tblprefix}menus VALUES ('77','区块标识','?entry=mtags&action=mtagsedit&ttype=rtag','21','1','1','8','0','0');
INSERT INTO {$tblprefix}menus VALUES ('78','不良词管理','?entry=badwords','17','0','1','8','0','0');
INSERT INTO {$tblprefix}menus VALUES ('79','热门关键词','?entry=wordlinks','17','0','1','9','0','0');
INSERT INTO {$tblprefix}menus VALUES ('80','子站管理','?entry=subsites&action=subsitesedit','15','0','1','15','0','0');
INSERT INTO {$tblprefix}menus VALUES ('82','现金充值管理','?entry=pays&action=paysedit','17','0','1','9','0','0');
INSERT INTO {$tblprefix}menus VALUES ('86','语言包管理','?entry=alangs&action=alangsedit','18','0','1','5','0','0');
INSERT INTO {$tblprefix}menus VALUES ('88','附件管理','?entry=userfiles&action=userfilesedit','17','0','1','6','0','0');
INSERT INTO {$tblprefix}menus VALUES ('90','页面静态','?entry=static&action=index','23','1','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('91','问吧相关','?entry=answers&action=answersedit','23','1','1','2','0','0');
INSERT INTO {$tblprefix}menus VALUES ('92','商品记录','?entry=purchases&action=purchasesedit','23','1','1','3','0','0');
INSERT INTO {$tblprefix}menus VALUES ('95','会员档案类型','?entry=matypes&action=matypesedit','15','0','1','9','0','0');
INSERT INTO {$tblprefix}menus VALUES ('96','订阅管理','?entry=subscribes','17','0','1','7','0','0');
INSERT INTO {$tblprefix}menus VALUES ('97','模板管理','?entry=tplconfig&action=tplbase','16','0','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('98','模板设置','?entry=tplconfig&action=tplbase','21','1','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('110','常用标识','?entry=usualtags','21','1','1','0','0','0');
INSERT INTO {$tblprefix}menus VALUES ('111','会员信用管理','?entry=repugrades','17','0','1','10','0','0');
INSERT INTO {$tblprefix}menus VALUES ('112','认证管理','?entry=memcerts','17','0','1','11','0','0');
INSERT INTO {$tblprefix}menus VALUES ('113','域名管理','?entry=domains','18','0','1','6','0','0');
INSERT INTO {$tblprefix}menus VALUES ('114','重建子站缓存','?entry=rebuilds','20','1','1','10','0','0');
INSERT INTO {$tblprefix}menus VALUES ('115','提现管理','?entry=extracts','17','0','1','9','0','0');

DROP TABLE IF EXISTS {$tblprefix}mfavorites;
CREATE TABLE {$tblprefix}mfavorites (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  fromid mediumint(8) unsigned NOT NULL default '0',
  fromname varchar(15) NOT NULL default '',
  ucid mediumint(8) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mfields;
CREATE TABLE {$tblprefix}mfields (
  mfid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  ename char(30) NOT NULL default '',
  mchid smallint(6) unsigned NOT NULL default '0',
  issystem tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  iscustom tinyint(1) unsigned NOT NULL default '1',
  mcommon tinyint(1) unsigned NOT NULL default '0',
  isfunc tinyint(1) unsigned NOT NULL default '0',
  func text NOT NULL,
  tbl varchar(10) NOT NULL default 'main',
  innertext text NOT NULL,
  fromcode tinyint(1) unsigned NOT NULL default '0',
  issearch tinyint(1) unsigned NOT NULL default '0',
  length smallint(6) unsigned NOT NULL default '0',
  datatype varchar(15) NOT NULL,
  notnull tinyint(1) unsigned NOT NULL default '0',
  nohtml tinyint(1) unsigned NOT NULL default '0',
  mlimit varchar(15) NOT NULL default '0',
  regular varchar(80) NOT NULL,
  min varchar(15) NOT NULL,
  max varchar(15) NOT NULL,
  rpid smallint(5) unsigned NOT NULL default '0',
  isadmin tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  `mode` tinyint(1) unsigned NOT NULL default '0',
  guide varchar(80) NOT NULL,
  vdefault varchar(255) NOT NULL default '',
  pmid smallint(6) unsigned NOT NULL default '0',
  useredit tinyint(1) unsigned NOT NULL default '0',
  custom_1 varchar(255) NOT NULL default '',
  custom_2 varchar(255) NOT NULL default '',
  PRIMARY KEY  (mfid)
) TYPE=MyISAM AUTO_INCREMENT=15;

INSERT INTO {$tblprefix}mfields VALUES ('1','用户名','mname','0','1','1','0','1','0','','main','','0','0','15','text','0','0','notnull','','','','0','0','0','0','','','0','0','','');
INSERT INTO {$tblprefix}mfields VALUES ('2','密码','password','0','1','1','0','1','0','','main','','0','0','15','text','0','0','notnull','','','','0','0','0','0','','','0','0','','');
INSERT INTO {$tblprefix}mfields VALUES ('3','Email','email','0','1','1','0','1','0','','main','','0','0','50','text','0','0','notnull','','','','0','0','0','0','','','0','0','','');
INSERT INTO {$tblprefix}mfields VALUES ('10','用户名1','mname','1','1','1','0','1','0','','main','','0','0','15','text','0','0','notnull','','','','0','0','1','0','','','0','0','','');
INSERT INTO {$tblprefix}mfields VALUES ('11','密码1','password','1','1','1','0','1','0','','main','','0','0','15','text','0','0','notnull','','','','0','0','2','0','','','0','0','','');
INSERT INTO {$tblprefix}mfields VALUES ('12','Email1','email','1','1','1','0','1','0','','main','','0','0','50','text','0','0','notnull','','','','0','0','3','0','','','0','0','','');
INSERT INTO {$tblprefix}mfields VALUES ('13','关联栏目','caid','0','0','1','1','1','0','','sub','a:2:{s:6:\"source\";s:1:\"0\";s:3:\"ids\";s:0:\"\";}','0','1','0','cacc','1','0','0','','','','0','0','0','0','','','0','0','','');
INSERT INTO {$tblprefix}mfields VALUES ('14','关联栏目','caid','1','0','1','1','1','0','','sub','a:2:{s:6:\"source\";s:1:\"0\";s:3:\"ids\";s:0:\"\";}','0','1','0','cacc','1','0','0','','','','0','0','0','0','','','0','0','','');

DROP TABLE IF EXISTS {$tblprefix}mflinks;
CREATE TABLE {$tblprefix}mflinks (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  fromid mediumint(8) unsigned NOT NULL default '0',
  fromname varchar(15) NOT NULL default '',
  ucid mediumint(8) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mfriends;
CREATE TABLE {$tblprefix}mfriends (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  fromid mediumint(8) unsigned NOT NULL default '0',
  fromname varchar(15) NOT NULL default '',
  ucid mediumint(8) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mlangs;
CREATE TABLE {$tblprefix}mlangs (
  lid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  content text NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid)
) TYPE=MyISAM AUTO_INCREMENT=965;

INSERT INTO {$tblprefix}mlangs VALUES ('1','madmincenter','会员管理中心','0');
INSERT INTO {$tblprefix}mlangs VALUES ('2','mindex','会员首页','0');
INSERT INTO {$tblprefix}mlangs VALUES ('3','mcenter','会员中心','0');
INSERT INTO {$tblprefix}mlangs VALUES ('4','space','我的空间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('5','msetting','我的资料','0');
INSERT INTO {$tblprefix}mlangs VALUES ('6','logout','退出','0');
INSERT INTO {$tblprefix}mlangs VALUES ('7','login','登陆','0');
INSERT INTO {$tblprefix}mlangs VALUES ('8','register','注册','0');
INSERT INTO {$tblprefix}mlangs VALUES ('9','getpwd','找回密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('10','siteoff','网站正在维护，请稍后再连接。','0');
INSERT INTO {$tblprefix}mlangs VALUES ('11','guide','提示说明','0');
INSERT INTO {$tblprefix}mlangs VALUES ('12','msite','主站','0');
INSERT INTO {$tblprefix}mlangs VALUES ('13','selectsite','选择内容站点','0');
INSERT INTO {$tblprefix}mlangs VALUES ('14','submit','提交','0');
INSERT INTO {$tblprefix}mlangs VALUES ('15','safecode','验证码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('16','safetips','点击图片换一个验证码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('17','safemark','请输入图片框中的字符','0');
INSERT INTO {$tblprefix}mlangs VALUES ('18','yes','是','0');
INSERT INTO {$tblprefix}mlangs VALUES ('19','no','否','0');
INSERT INTO {$tblprefix}mlangs VALUES ('20','clickhere','如果浏览器没有跳转请点这里','0');
INSERT INTO {$tblprefix}mlangs VALUES ('21','rightnowjump','立即跳转','0');
INSERT INTO {$tblprefix}mlangs VALUES ('22','closewindow','关闭窗口','0');
INSERT INTO {$tblprefix}mlangs VALUES ('23','promptmessage','提示信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('610','readdoffer','重发布报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('25','waitcheck','请等待管理员审核！','0');
INSERT INTO {$tblprefix}mlangs VALUES ('26','usergroupalterfinish','会员组设置成功','0');
INSERT INTO {$tblprefix}mlangs VALUES ('27','adminreply','管理员回复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('28','remark','备注','0');
INSERT INTO {$tblprefix}mlangs VALUES ('29','belongcatalog','所属栏目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('30','catasalbumsetting','类目和合辑设定','0');
INSERT INTO {$tblprefix}mlangs VALUES ('31','addalter','添加变更','0');
INSERT INTO {$tblprefix}mlangs VALUES ('32','alterstate','目前处于变更流程的环节','0');
INSERT INTO {$tblprefix}mlangs VALUES ('33','nosetting','不设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('34','applytime','申请时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('35','user0','组外会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('36','belongalbum','所属合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('37','usergroupaltermodel','会员级变更方式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('39','inputalbumid','手动输入所属合辑ID','0');
INSERT INTO {$tblprefix}mlangs VALUES ('40','usergroupneedoption','会员组申请方式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('41','need','申请','0');
INSERT INTO {$tblprefix}mlangs VALUES ('42','mycoclass','我的分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('43','nococlass','不设置分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('44','contentsetting','内容设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('45','moresetting','更多设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('46','altertargetusergroup','变更目标会员组','0');
INSERT INTO {$tblprefix}mlangs VALUES ('47','groupcurrentuser','您所在组','0');
INSERT INTO {$tblprefix}mlangs VALUES ('48','needusergroupalter','[%s]变更申请','0');
INSERT INTO {$tblprefix}mlangs VALUES ('49','miniday','最小%s天','0');
INSERT INTO {$tblprefix}mlangs VALUES ('50','maxday','最大%s天','0');
INSERT INTO {$tblprefix}mlangs VALUES ('51','contentproject','内容权限方案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('52','archivesaleprice','浏览文档售价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('53','adjunctsaleprice','附件操作售价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('54','settingvalidperiod','设置有效期（天）','0');
INSERT INTO {$tblprefix}mlangs VALUES ('55','freesale','免费','0');
INSERT INTO {$tblprefix}mlangs VALUES ('615','choosemessage','请指定正确的信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('57','allcatalog','所有栏目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('58','allaltype','所有合辑类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('59','nolimit','不限','0');
INSERT INTO {$tblprefix}mlangs VALUES ('60','nocheckalbum','未审合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('61','nopermission','没有指定项目的操作权限!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('62','checkedalbum','已审合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('63','invalid','无效','0');
INSERT INTO {$tblprefix}mlangs VALUES ('64','available','有效','0');
INSERT INTO {$tblprefix}mlangs VALUES ('65','filteralbum','筛选合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('66','currentalbum','当前合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('67','checkstate','审核状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('68','validperiodstate','有效期状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('69','belongaltype','所属合辑类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('70','searchtitle','搜索标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('71','agsearchkey','可含通配符 *','0');
INSERT INTO {$tblprefix}mlangs VALUES ('72','adddate','添加日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('73','daybefore','天之前','0');
INSERT INTO {$tblprefix}mlangs VALUES ('74','pause','中止当前操作','0');
INSERT INTO {$tblprefix}mlangs VALUES ('75','page0','页','0');
INSERT INTO {$tblprefix}mlangs VALUES ('76','dayin','天内','0');
INSERT INTO {$tblprefix}mlangs VALUES ('77','nocata','草稿箱','0');
INSERT INTO {$tblprefix}mlangs VALUES ('78','look','查看','0');
INSERT INTO {$tblprefix}mlangs VALUES ('79','uploaddate','上传日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('80','thumb','缩略图','0');
INSERT INTO {$tblprefix}mlangs VALUES ('81','setalbum','归辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('82','admin','管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('83','preview','预览','0');
INSERT INTO {$tblprefix}mlangs VALUES ('84','modify','修改','0');
INSERT INTO {$tblprefix}mlangs VALUES ('85','size_k','大小(K)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('86','type','类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('87','cname','名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('88','needing','申请中','0');
INSERT INTO {$tblprefix}mlangs VALUES ('89','pleaseneed','请申请','0');
INSERT INTO {$tblprefix}mlangs VALUES ('90','albumlist','合辑列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('91','selectallpage','全选所有页内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('92','title','标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('93','del','删?','0');
INSERT INTO {$tblprefix}mlangs VALUES ('94','altype','合辑类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('95','catalog','栏目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('96','check','审核','0');
INSERT INTO {$tblprefix}mlangs VALUES ('97','message','信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('212','filter0','筛选','0');
INSERT INTO {$tblprefix}mlangs VALUES ('99','album','合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('100','edit','编辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('101','cancel','取消','0');
INSERT INTO {$tblprefix}mlangs VALUES ('102','abover','完结','0');
INSERT INTO {$tblprefix}mlangs VALUES ('103','operateitem','操作项目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('104','delarchive','删除文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('105','delalbumupcopy','删除合辑更新副本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('106','rearchive','文档重发布','0');
INSERT INTO {$tblprefix}mlangs VALUES ('107','albumupdateneed','合辑更新申请','0');
INSERT INTO {$tblprefix}mlangs VALUES ('108','albumisabover','合辑是否完结','0');
INSERT INTO {$tblprefix}mlangs VALUES ('109','cancelcoclass','取消分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('110','setalbumtips','请输入回辑ID','0');
INSERT INTO {$tblprefix}mlangs VALUES ('111','resetvalidperiod','重设有效期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('112','albumupdatemode','合辑更新模式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('113','ishavethumb','是否有缩略图','0');
INSERT INTO {$tblprefix}mlangs VALUES ('114','aidstxt','相关文档ID(多个ID用逗号隔开)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('115','attachmenttype','附件类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('116','viewdetail','显示详细','0');
INSERT INTO {$tblprefix}mlangs VALUES ('117','filterattachment','筛选附件','0');
INSERT INTO {$tblprefix}mlangs VALUES ('118','curcontentsrc','当前内容来源','0');
INSERT INTO {$tblprefix}mlangs VALUES ('119','updatecontentsaveas','更新内容另存为','0');
INSERT INTO {$tblprefix}mlangs VALUES ('120','albumupdatecopy','合辑更新副本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('121','albumself','合辑正本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('122','wantcheck','需要管理审核','0');
INSERT INTO {$tblprefix}mlangs VALUES ('123','permissionsetting','权限设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('124','havethumb','有缩略图','0');
INSERT INTO {$tblprefix}mlangs VALUES ('125','nonethumb','没有缩略图','0');
INSERT INTO {$tblprefix}mlangs VALUES ('126','other','其它','0');
INSERT INTO {$tblprefix}mlangs VALUES ('127','media','视频','0');
INSERT INTO {$tblprefix}mlangs VALUES ('128','flash','Flash','0');
INSERT INTO {$tblprefix}mlangs VALUES ('129','curalbumsetting','当前所属合辑设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('130','image','图片','0');
INSERT INTO {$tblprefix}mlangs VALUES ('131','alltype','全部类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('132','id','ID','0');
INSERT INTO {$tblprefix}mlangs VALUES ('133','exit','退出','0');
INSERT INTO {$tblprefix}mlangs VALUES ('134','author','作者','0');
INSERT INTO {$tblprefix}mlangs VALUES ('135','subsite','子站','0');
INSERT INTO {$tblprefix}mlangs VALUES ('136','editcoclassfinish','编辑分类完成','0');
INSERT INTO {$tblprefix}mlangs VALUES ('137','inalbumcheck','辑内审核','0');
INSERT INTO {$tblprefix}mlangs VALUES ('138','exitalbum','退出合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('139','belongspacecatalog','所属空间栏目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('140','uclasstype','个人分类类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('141','filterhasinalbum','筛选需要归入的合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('142','uclasscname','个人分类名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('143','searchauthor','搜索作者','0');
INSERT INTO {$tblprefix}mlangs VALUES ('144','choosewantalbum','请选择需要归入的合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('145','detail','详情','0');
INSERT INTO {$tblprefix}mlangs VALUES ('146','archivemanager','文档管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('147','albummanager','合辑管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('148','add','添加','0');
INSERT INTO {$tblprefix}mlangs VALUES ('149','load','加载','0');
INSERT INTO {$tblprefix}mlangs VALUES ('150','inalbummanager','辑内内容管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('151','inalbumlist','辑内内容列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('152','clear','清除','0');
INSERT INTO {$tblprefix}mlangs VALUES ('153','inalbumorder','辑内排序','0');
INSERT INTO {$tblprefix}mlangs VALUES ('154','allchannel','所有模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('155','edituclass','编辑个人分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('156','archive','文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('157','filterarcalbum','筛选文档和合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('158','arcbelongchannel','文档所属模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('159','chooseyouruclass','请选择你的个人分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('160','albumbelongtype','合辑所属类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('161','addcoclassfinish','添加分类完成','0');
INSERT INTO {$tblprefix}mlangs VALUES ('162','coclasscname','分类名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('163','addusercoclass','添加用户分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('164','order','排序','0');
INSERT INTO {$tblprefix}mlangs VALUES ('165','uclassmanager','个人分类管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('166','goback','返回','0');
INSERT INTO {$tblprefix}mlangs VALUES ('167','channelalbum','模型或合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('168','basemessage','基本信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('169','purchasedate','购买日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('170','albumtitle','合辑标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('171','currency','积分','0');
INSERT INTO {$tblprefix}mlangs VALUES ('172','membercname','会员名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('173','attachment','附件','0');
INSERT INTO {$tblprefix}mlangs VALUES ('174','subscribelist','订阅列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('175','addtime','添加时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('176','updatetime','更新时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('177','retime','重新发布时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('178','archivetitle','文档标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('179','endtime','到期时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('180','archivechannel','文档模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('181','subscribetype','订阅类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('182','clickcomment','点击数 / 评论数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('183','uncheck','解审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('184','othermessage','其它信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('185','filtersubscribe','过滤订阅','0');
INSERT INTO {$tblprefix}mlangs VALUES ('186','checked','已审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('187','nocheck','未审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('188','member','会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('189','sn','序号','0');
INSERT INTO {$tblprefix}mlangs VALUES ('190','searchresultlist','搜索结果列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('191','search','搜索','0');
INSERT INTO {$tblprefix}mlangs VALUES ('192','comment','评论','0');
INSERT INTO {$tblprefix}mlangs VALUES ('193','outdays','多少天以前添加','0');
INSERT INTO {$tblprefix}mlangs VALUES ('194','indays','多少天以内添加','0');
INSERT INTO {$tblprefix}mlangs VALUES ('195','ordertype','其它类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('196','asc','升序','0');
INSERT INTO {$tblprefix}mlangs VALUES ('197','nolimittype','不限类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('198','searchkeyword','搜索关键词','0');
INSERT INTO {$tblprefix}mlangs VALUES ('199','nocheckcomment','未审评论','0');
INSERT INTO {$tblprefix}mlangs VALUES ('200','searchmode','搜索方式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('201','checkedcomment','已审评论','0');
INSERT INTO {$tblprefix}mlangs VALUES ('202','filtersetting','筛选设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('203','searchsetting','搜索设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('205','allarchive','所有文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('206','uclass','个人分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('207','comments','评论数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('208','clicks','点击数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('209','fulltxt','全文','0');
INSERT INTO {$tblprefix}mlangs VALUES ('210','allcoclass','所有分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('211','srcmemberid','来源会员ID','0');
INSERT INTO {$tblprefix}mlangs VALUES ('213','keyword','关键词','0');
INSERT INTO {$tblprefix}mlangs VALUES ('214','srcmembercname','来源会员名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('215','commentdate','评论日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('216','list','列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('217','usernosearchpermi','所属会员组没有搜索权限','0');
INSERT INTO {$tblprefix}mlangs VALUES ('218','srcmember','来源会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('219','reply0','答复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('220','read','已读','0');
INSERT INTO {$tblprefix}mlangs VALUES ('221','delete','删除','0');
INSERT INTO {$tblprefix}mlangs VALUES ('222','memberid','会员ID','0');
INSERT INTO {$tblprefix}mlangs VALUES ('223','operate','操作','0');
INSERT INTO {$tblprefix}mlangs VALUES ('224','nocheckfriend','未审好友','0');
INSERT INTO {$tblprefix}mlangs VALUES ('225','checkedfriend','已审好友','0');
INSERT INTO {$tblprefix}mlangs VALUES ('226','my','我的','0');
INSERT INTO {$tblprefix}mlangs VALUES ('227','agree','同意','0');
INSERT INTO {$tblprefix}mlangs VALUES ('228','needlist','申请列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('229','reportsucceed','举报成功','0');
INSERT INTO {$tblprefix}mlangs VALUES ('230','needtime','申请时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('231','deleteneed','删除申请','0');
INSERT INTO {$tblprefix}mlangs VALUES ('232','reply','回复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('233','confirmselectreport','请选择举报','0');
INSERT INTO {$tblprefix}mlangs VALUES ('234','nocheckreply','未审回复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('235','checkedreply','已审回复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('236','replydate','回复时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('237','noadopt','未采用','0');
INSERT INTO {$tblprefix}mlangs VALUES ('238','adopted','已采用','0');
INSERT INTO {$tblprefix}mlangs VALUES ('239','filteranswer','筛选答案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('240','isadopt','是否采用','0');
INSERT INTO {$tblprefix}mlangs VALUES ('241','questiontitle','问题标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('893','closequestion','关闭问题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('243','myanswerlist','我的答案列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('244','adopt','采用','0');
INSERT INTO {$tblprefix}mlangs VALUES ('245','accountin','入帐','0');
INSERT INTO {$tblprefix}mlangs VALUES ('246','votenum','票数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('247','answerdate','回答时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('248','source','来源','0');
INSERT INTO {$tblprefix}mlangs VALUES ('249','editanswer','编辑答案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('250','answercontent','答复内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('251','updatedate','更新日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('252','reportobject','举报对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('253','nosettingcoclass','不设置分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('254','reportlist','举报列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('255','noadminpermi','你没有管理权限','0');
INSERT INTO {$tblprefix}mlangs VALUES ('256','reportdelsucceed','举报删除成功','0');
INSERT INTO {$tblprefix}mlangs VALUES ('257','nocheckarchive','未审文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('258','checkedarchive','已审文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('259','selectreport','请选择举报','0');
INSERT INTO {$tblprefix}mlangs VALUES ('260','channel','模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('261','reportupdatedate','举报更新日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('262','filterarchive','筛选文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('263','updatesucceed','更新成功!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('264','readonly','(只读)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('265','archivelist','文档列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('266','adminmessage','管理信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('267','submitmessage','提交信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('268','reportmember','举报会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('269','delarchiveupcopy','删除文档更新副本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('270','lastupdatetime','上次更新时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('271','lookreportobject','查看举报对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('272','archiveupdateneed','文档更新申请','0');
INSERT INTO {$tblprefix}mlangs VALUES ('273','basedmessage','基本信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('274','chooseyourreport','请选择你自己的举报','0');
INSERT INTO {$tblprefix}mlangs VALUES ('275','archiveupdatemode','文档更新模式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('276','archiveupdatecopy','文档更新副本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('277','archiveself','文档正本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('278','archivenocheck','选定的文档没审核','0');
INSERT INTO {$tblprefix}mlangs VALUES ('279','replysetsucceed','回复设置成功','0');
INSERT INTO {$tblprefix}mlangs VALUES ('280','albumsaleprice','浏览合辑售价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('281','confirmselectreply','请选择回复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('282','selectoperateitem','请选择操作项目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('283','settingcoclass','设置分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('284','coclass','分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('285','belongcoclass','所属分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('286','email','Email','0');
INSERT INTO {$tblprefix}mlangs VALUES ('287','pm','短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('288','mycartstep','我的购物车 商家：%s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('289','goodscname','商品名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('902','alipay_partner','支付宝合作商户ID','1262162565');
INSERT INTO {$tblprefix}mlangs VALUES ('838','dcprice','折扣价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('292','puamount','购买数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('293','weight_kg','重量(kg)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('294','stock','库存','0');
INSERT INTO {$tblprefix}mlangs VALUES ('295','oldpricesum','原价总和&#160;%s&#160;元，','0');
INSERT INTO {$tblprefix}mlangs VALUES ('296','dcpricesum','折扣总和&#160;%s&#160;元','0');
INSERT INTO {$tblprefix}mlangs VALUES ('297','weightsum','重量总和&nbsp;&nbsp;%s&nbsp;kg','0');
INSERT INTO {$tblprefix}mlangs VALUES ('298','nogoods','尚未添加商品','0');
INSERT INTO {$tblprefix}mlangs VALUES ('299','settlementcenter','结算中心','0');
INSERT INTO {$tblprefix}mlangs VALUES ('758','addinpriv','加入到个人合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('300','backmessage','返回信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('301','settlementcenterstep','购物中心&#160;&#160;商家：%s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('302','goodsoldpricesum','商品原价总和','0');
INSERT INTO {$tblprefix}mlangs VALUES ('303','goodsdcpricesum','商品折扣价总和','0');
INSERT INTO {$tblprefix}mlangs VALUES ('304','choosecoclass','选择分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('305','relatedmember','相关会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('306','yuan','元','0');
INSERT INTO {$tblprefix}mlangs VALUES ('307','goodsweightsum','商品重量总和','0');
INSERT INTO {$tblprefix}mlangs VALUES ('308','shiping','送货方式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('309','continue','继续','0');
INSERT INTO {$tblprefix}mlangs VALUES ('310','lookrelatedsource','查看相关来源','0');
INSERT INTO {$tblprefix}mlangs VALUES ('311','orderssncode','订单编号','0');
INSERT INTO {$tblprefix}mlangs VALUES ('312','chooseyourreply','请选择你自已的回复!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('313','ordersgoodsfee','订单商品费用','0');
INSERT INTO {$tblprefix}mlangs VALUES ('314','lookrelatedcontent','查看相关内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('315','ordersgoodsweight','订单商品重量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('316','ordersshipfee','订单送货费用','0');
INSERT INTO {$tblprefix}mlangs VALUES ('317','ordersfeesum','订单费用总额','0');
INSERT INTO {$tblprefix}mlangs VALUES ('318','mycashaccount','我的现金帐户','0');
INSERT INTO {$tblprefix}mlangs VALUES ('319','payfrommyaccount','从我的帐户支付(元)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('320','ordersothermessage','订单其它信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('321','onlinesaving','在线充值','0');
INSERT INTO {$tblprefix}mlangs VALUES ('322','purchase','购买','0');
INSERT INTO {$tblprefix}mlangs VALUES ('323','purchasegoods','购买商品','0');
INSERT INTO {$tblprefix}mlangs VALUES ('324','goodspurchasestep1','商品购买步骤&nbsp; 1&nbsp; / &nbsp;2','0');
INSERT INTO {$tblprefix}mlangs VALUES ('325','goodspurchasestep2','商品购买步骤&nbsp; 2&nbsp; / &nbsp;2','0');
INSERT INTO {$tblprefix}mlangs VALUES ('326','goodsoldprice','商品原价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('327','goodsdcprice','商品折扣价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('328','goodsstock','商品库存','0');
INSERT INTO {$tblprefix}mlangs VALUES ('329','goodsweight','商品重量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('331','lookcommentobject','查看评论对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('332','commentmember','评论会员','1263183038');
INSERT INTO {$tblprefix}mlangs VALUES ('333','commentupdatedate','评论更新时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('334','commentlist','评论列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('335','commentobject','评论对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('336','delcomment','删除评论','0');
INSERT INTO {$tblprefix}mlangs VALUES ('337','checkcomment','审核评论','0');
INSERT INTO {$tblprefix}mlangs VALUES ('338','addreplyoverquick','添加回复操作过于频繁','0');
INSERT INTO {$tblprefix}mlangs VALUES ('339','norepeataddreply','请不要重复添加回复!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('340','choosereplyobject','请指定正确的回复对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('341','memberrelatecoclass','关联分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('342','spacetemplateproject','个人空间模板方案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('343','addcontentbycatalog','按栏目添加内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('344','memberchannel','会员模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('345','password','密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('346','regcode','验证码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('347','addinpublicalbum','在公共合辑中添加','0');
INSERT INTO {$tblprefix}mlangs VALUES ('348','choosememberchannel','选择会员模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('349','archiveadmin','文档管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('350','albumadmin','合辑管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('351','norepeatregister','请不要重复注册','0');
INSERT INTO {$tblprefix}mlangs VALUES ('352','defaultregclosedreason','对不起，网站暂停注册新会员！','0');
INSERT INTO {$tblprefix}mlangs VALUES ('353','replys','回复数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('354','addincatalog','栏目内添加','0');
INSERT INTO {$tblprefix}mlangs VALUES ('355','content','内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('356','contentlist','内容列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('357','filterpublicalbum','筛选公共合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('358','weatherchecked','是否已审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('359','checkedquestion','已审问题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('360','nocheckquestion','未审问题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('361','related','相关','0');
INSERT INTO {$tblprefix}mlangs VALUES ('362','publicalbumlist','公共合辑列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('363','addcontent','添加内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('364','withoutarchiveoralbum','没有相关联的文档或合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('365','exchangeto','&nbsp;&nbsp;兑换为&nbsp;&nbsp;','0');
INSERT INTO {$tblprefix}mlangs VALUES ('366','membercurrent','您拥有的 %s 数量为','1272980692');
INSERT INTO {$tblprefix}mlangs VALUES ('367','exchangescale','兑换比例','0');
INSERT INTO {$tblprefix}mlangs VALUES ('369','exchangeamount','兑换数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('370','exchange','兑换','0');
INSERT INTO {$tblprefix}mlangs VALUES ('371','answerreward','答疑悬赏','0');
INSERT INTO {$tblprefix}mlangs VALUES ('372','checkout','结案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('373','answer0','答案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('374','currencyexcurrency','积分兑换积分','0');
INSERT INTO {$tblprefix}mlangs VALUES ('375','overdate','过期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('376','close','关闭','0');
INSERT INTO {$tblprefix}mlangs VALUES ('377','spare','余额','0');
INSERT INTO {$tblprefix}mlangs VALUES ('378','reward','悬赏','0');
INSERT INTO {$tblprefix}mlangs VALUES ('379','questionlist','问题列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('380','filterquestion','筛选问题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('381','currencytype','积分类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('382','mode1','方式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('383','amount','数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('384','noanswerchannel','未定义答疑模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('385','reason','原因','0');
INSERT INTO {$tblprefix}mlangs VALUES ('386','time','时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('387','crrecord','积分变更日志','0');
INSERT INTO {$tblprefix}mlangs VALUES ('388','orders','订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('390','addfreeinfo','添加插件信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('391','commonoption','通用选项','0');
INSERT INTO {$tblprefix}mlangs VALUES ('392','purchasedgoodslist','已购商品列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('393','messagecoclass','信息分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('394','nocheckmessage','未审信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('395','checkedmessage','已审信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('396','filtergoods','过滤商品','0');
INSERT INTO {$tblprefix}mlangs VALUES ('397','filtermessage','筛选信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('398','ordering','下单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('399','messagelist','信息列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('400','messagetitle','信息标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('401','startdate','开始日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('402','enddate','结束日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('403','all','所有','0');
INSERT INTO {$tblprefix}mlangs VALUES ('614','messagefinish','信息操作成功','0');
INSERT INTO {$tblprefix}mlangs VALUES ('405','messagechecked','信息内容已审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('406','messagenocheck','信息内容未审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('407','effectmessage','生效中信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('408','flong','永久信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('409','pmsendfinish','短信发送成功','0');
INSERT INTO {$tblprefix}mlangs VALUES ('410','noeffectmessage','未生效信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('411','regcodeerror','验证码错误','0');
INSERT INTO {$tblprefix}mlangs VALUES ('412','fordermessage','订单信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('413','messagepuprice','信息购买价格','0');
INSERT INTO {$tblprefix}mlangs VALUES ('414','day','天','0');
INSERT INTO {$tblprefix}mlangs VALUES ('415','purchasecell','购买单元','0');
INSERT INTO {$tblprefix}mlangs VALUES ('416','pmcontent','内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('417','minipucellamount','最小购买单元数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('418','pmtonames','<font color=\"gray\">(用逗号分隔多个会员名称)</font> 发送至','0');
INSERT INTO {$tblprefix}mlangs VALUES ('419','messagecurstate','信息当前状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('420','membercurcurrency','会员当前积分','0');
INSERT INTO {$tblprefix}mlangs VALUES ('421','pmtitle','标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('422','fordermanager','订单管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('423','sendpm','发送短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('424','messagecontent','信息内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('425','purchasedays','购买天数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('426','sendtime','发送时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('427','purchasecurrency','购买积分','0');
INSERT INTO {$tblprefix}mlangs VALUES ('428','senduser','发信人','0');
INSERT INTO {$tblprefix}mlangs VALUES ('429','ordersdate','订单日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('430','pmcontentsetting','短信内容设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('431','pointpm','请指定短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('432','pmdelfinish','短信删除操作完成','0');
INSERT INTO {$tblprefix}mlangs VALUES ('433','addforder','添加订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('434','choosedeltem','请选择删除项目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('435','purchasecellamount','购买单元数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('436','senddate','发送日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('437','consultbasemessage','咨询基本信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('438','noread','未读','0');
INSERT INTO {$tblprefix}mlangs VALUES ('439','consulttitle','咨询标题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('440','consultcommulist','咨询交互列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('441','consultreplycontent','咨询和回复内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('442','pmlist','短信列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('443','consult','咨询','0');
INSERT INTO {$tblprefix}mlangs VALUES ('444','continueconsult','继续咨询','0');
INSERT INTO {$tblprefix}mlangs VALUES ('445','consultcontent','咨询内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('446','overconsult','当前咨询主题已被关闭，继续咨询请新添主题。','0');
INSERT INTO {$tblprefix}mlangs VALUES ('447','paymodifyfinish','支付信息修改完成','0');
INSERT INTO {$tblprefix}mlangs VALUES ('448','favoritedate','收藏日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('449','favoritearchivelist','收藏文档列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('450','paynomodify','已充值支付信息不能修改','0');
INSERT INTO {$tblprefix}mlangs VALUES ('451','nosettle','未解决','0');
INSERT INTO {$tblprefix}mlangs VALUES ('452','dealing','正在处理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('453','settled','已解决','0');
INSERT INTO {$tblprefix}mlangs VALUES ('454','closed','已关闭','0');
INSERT INTO {$tblprefix}mlangs VALUES ('455','bigimage','大图','0');
INSERT INTO {$tblprefix}mlangs VALUES ('456','paywarrant','支付凭证','0');
INSERT INTO {$tblprefix}mlangs VALUES ('457','contactemail','联系Email','0');
INSERT INTO {$tblprefix}mlangs VALUES ('458','contacttel','联系电话','0');
INSERT INTO {$tblprefix}mlangs VALUES ('459','contactorname','联系人名字','0');
INSERT INTO {$tblprefix}mlangs VALUES ('460','currencysavtime','积分充值时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('461','cashartime','现金到帐时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('462','filterconsultmessage','筛选咨询信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('463','dealstate','处理状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('464','allstate','所有状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('465','consultmessagelist','咨询信息列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('466','consultaddtime','咨询添加时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('467','memberpwdsetting','会员密码设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('468','messagesendtime','信息发送时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('469','payordersidsn','支付订单号','0');
INSERT INTO {$tblprefix}mlangs VALUES ('470','payinterface','支付接口','0');
INSERT INTO {$tblprefix}mlangs VALUES ('471','handfeermb','手续费(人民币)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('472','payamountrmbi','支付数量(人民币)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('473','inputnewpwd','输入新密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('474','paymode','支付方式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('475','renewpwd','重新输入新密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('476','lookpaymessage','查看支付信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('477','modifypaymessage','修改支付信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('478','choosepayrecord','请指定正确的支付记录','0');
INSERT INTO {$tblprefix}mlangs VALUES ('479','operating','文件操作正在进行中...','0');
INSERT INTO {$tblprefix}mlangs VALUES ('480','currentusergroup','您所属的会员组是','1272980684');
INSERT INTO {$tblprefix}mlangs VALUES ('481','curusergroupenddate','当前会员组结束日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('482','exchangeusergroup','请选择要购买的组','1272981441');
INSERT INTO {$tblprefix}mlangs VALUES ('483','selectpayrecord','请选择支付记录','0');
INSERT INTO {$tblprefix}mlangs VALUES ('484','currencyexusergroup','积分兑换会员组','0');
INSERT INTO {$tblprefix}mlangs VALUES ('485','savingdate','充值日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('486','cashaccount','现金帐户','0');
INSERT INTO {$tblprefix}mlangs VALUES ('487','arrivedate','到帐日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('488','recorddate','记录日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('489','lastloginip','上次登陆IP','0');
INSERT INTO {$tblprefix}mlangs VALUES ('490','lastlogintime','上次登陆时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('491','payamount','支付数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('492','mb_state','您目前的身份是：%s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('493','payrecordlist','支付记录列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('494','messagestat','信息统计','0');
INSERT INTO {$tblprefix}mlangs VALUES ('495','addarcamount','会员添加文档数量 %s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('496','onlinepayinterface','在线支付接口','0');
INSERT INTO {$tblprefix}mlangs VALUES ('497','issuearcamount','会员已审文档数量 %s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('498','membercomments','会员评论数 %s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('499','arcsubscribeamount','会员文档订阅数量 %s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('500','adjsubscribeamount','会员附件订阅数量 %s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('501','currencyistransed','积分是否已充值','0');
INSERT INTO {$tblprefix}mlangs VALUES ('502','uploadedadjunct','会员已上传附件 %s (K)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('503','downloadedadjunct','会员已下载附件 %s (K)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('504','friendlist','好友列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('505','cashisarrived','现金是否已到账','0');
INSERT INTO {$tblprefix}mlangs VALUES ('506','filterpayrecord','筛选支付记录','0');
INSERT INTO {$tblprefix}mlangs VALUES ('507','transed','已充值','0');
INSERT INTO {$tblprefix}mlangs VALUES ('508','memberlogin','会员登陆','0');
INSERT INTO {$tblprefix}mlangs VALUES ('509','notrans','未充值','0');
INSERT INTO {$tblprefix}mlangs VALUES ('510','loginpwd','登陆密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('511','arrived','已到账','0');
INSERT INTO {$tblprefix}mlangs VALUES ('512','noarrive','未到账','0');
INSERT INTO {$tblprefix}mlangs VALUES ('513','postofficeremit','邮局汇款','0');
INSERT INTO {$tblprefix}mlangs VALUES ('514','banktransfer','银行转账','0');
INSERT INTO {$tblprefix}mlangs VALUES ('515','membergetpwd','会员找回密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('516','memberemail','会员Email','0');
INSERT INTO {$tblprefix}mlangs VALUES ('517','onlinepay','在线支付','0');
INSERT INTO {$tblprefix}mlangs VALUES ('518','visitingpay','上门支付','0');
INSERT INTO {$tblprefix}mlangs VALUES ('519','addmember','添加会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('520','lookcommentsource','查看评论来源','0');
INSERT INTO {$tblprefix}mlangs VALUES ('521','cashpayedmessageadmini','现金充值已支付信息通知管理员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('522','activeoutsitemember','站外注册会员激活','0');
INSERT INTO {$tblprefix}mlangs VALUES ('523','memberpwd','会员密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('524','baseoption','基本选项','0');
INSERT INTO {$tblprefix}mlangs VALUES ('830','product','产品','0');
INSERT INTO {$tblprefix}mlangs VALUES ('526','checkordernomodify','已审订单不能修改','0');
INSERT INTO {$tblprefix}mlangs VALUES ('527','ordersmessageset','订单信息设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('528','usergroup','会员组','0');
INSERT INTO {$tblprefix}mlangs VALUES ('529','weight','重量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('530','memberrelatecatalog','关联栏目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('531','ordersgoodslist','订单商品列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('532','payedcashyuan','已支付现金(元)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('533','oldpwd','原始密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('534','newpwd','新密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('535','repwd','重复密码','0');
INSERT INTO {$tblprefix}mlangs VALUES ('536','orderfeeamountyuan','订单费用总额(元)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('537','shipfeeyuan','送货费用(元)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('538','goodsfeeyuan','商品费用(元)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('539','basestate','基本状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('540','membercheckstate','会员审核状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('541','memberregtime','会员注册时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('542','memberregip','会员注册IP','0');
INSERT INTO {$tblprefix}mlangs VALUES ('543','lastactivetime','会员上次激活时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('544','memberclicks','会员点击数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('545','memberonlinetime','会员在线时间(时)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('546','addarcamount1','会员添加文档数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('547','issuearcamount1','会员已审文档数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('548','membercomments1','会员评论数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('549','arcsubscribeamount1','会员文档订阅数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('550','adjsubscribeamount1','会员附件订阅数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('551','uploadedadjunct1','会员已上传附件(K)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('552','downloadedadjunct1','会员已下载附件(K)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('553','otherstate','其它状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('554','membercurrency','会员积分','0');
INSERT INTO {$tblprefix}mlangs VALUES ('555','memberstate','会员状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('556','nocheckmember','未审会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('557','checkedmember','已审会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('558','lookaddobject','查看添加对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('559','needmessage','申请信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('560','looklinkobject','查看友情链接对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('561','nochecklink','未审友情链接','0');
INSERT INTO {$tblprefix}mlangs VALUES ('562','checkedlink','已审友情链接','0');
INSERT INTO {$tblprefix}mlangs VALUES ('563','lookreplyobject','查看回复对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('564','lookreplysource','查看回复来源','0');
INSERT INTO {$tblprefix}mlangs VALUES ('565','gobackmessage','返回信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('566','received','已收货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('567','noreceive','未收货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('568','sended','已发货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('569','nosend','未发货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('570','ordersstate','订单状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('571','ordersbasedset','定单基本设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('572','cancelorders','取消订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('573','selectorders','请选择订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('574','payed','已支付','0');
INSERT INTO {$tblprefix}mlangs VALUES ('575','reportdate','举报日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('576','ordersallamount','订单总额','0');
INSERT INTO {$tblprefix}mlangs VALUES ('577','commentamount','评论数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('578','archiveamount','文档数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('579','registertime','注册时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('580','orderslist','订单列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('581','isreceived','是否已收货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('582','issended','是否已发货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('583','ischecked','是否已审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('584','filterorders','筛选订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('585','needmembertypealter','申请会员类型变更','0');
INSERT INTO {$tblprefix}mlangs VALUES ('586','membercurrenttype','会员当前类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('587','altertargettype','变更目标类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('588','membertypeneedoption','会员类型申请选项','0');
INSERT INTO {$tblprefix}mlangs VALUES ('589','alter_state','变更状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('590','wait_check','等待审核','0');
INSERT INTO {$tblprefix}mlangs VALUES ('591','add_alter','添加变更','0');
INSERT INTO {$tblprefix}mlangs VALUES ('592','checkoffer','审核报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('593','masterreply','管理员回复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('594','inputmembermessage','输入会员信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('595','updateoffer','更新报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('596','deleteoffer','删除报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('597','productoffer','产品报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('598','lookofferobject','查看报价对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('599','offeravailabledays','报价有效天数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('600','myoffer','我的报价(元)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('601','otheroffermessage','其它报价信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('602','offerobject','报价对象','0');
INSERT INTO {$tblprefix}mlangs VALUES ('603','lookprorelatedmsg','查看产品相关信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('604','offercheckstate','报价审核状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('605','offerovertime','报价到期时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('606','offerlist','报价列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('607','updatemyoffer','更新我的报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('608','noend','无限期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('609','offeruclass','报价类别','0');
INSERT INTO {$tblprefix}mlangs VALUES ('611','isend','是否到期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('612','end1','到期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('613','no1end1','未到期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('618','marcsaveerr','保存会员档案时出现错误!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('619','marcaddfinish','会员档案添加成功!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('620','marchiveslist','我的会员档案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('621','marctype','档案类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('622','answer_reward','答疑悬赏','0');
INSERT INTO {$tblprefix}mlangs VALUES ('623','awardcurrency','奖励积分','0');
INSERT INTO {$tblprefix}mlangs VALUES ('624','msite','主站','0');
INSERT INTO {$tblprefix}mlangs VALUES ('625','goback','返回','0');
INSERT INTO {$tblprefix}mlangs VALUES ('626','closewindow','关闭窗口','0');
INSERT INTO {$tblprefix}mlangs VALUES ('627','rightnowjump','立即跳转','0');
INSERT INTO {$tblprefix}mlangs VALUES ('628','rightnowgoback','立即返回','0');
INSERT INTO {$tblprefix}mlangs VALUES ('629','defaultclosedreason','网站正在维护，请稍后再连接。','0');
INSERT INTO {$tblprefix}mlangs VALUES ('630','choose_reward_cutype','请指定正确的悬赏积分类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('837','price','价格','0');
INSERT INTO {$tblprefix}mlangs VALUES ('632','weight','重量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('633','rewarcurrenval','悬赏积分值','0');
INSERT INTO {$tblprefix}mlangs VALUES ('634','question','问题','0');
INSERT INTO {$tblprefix}mlangs VALUES ('635','stock','库存','0');
INSERT INTO {$tblprefix}mlangs VALUES ('636','questcontnotn','问题内容不能为空','0');
INSERT INTO {$tblprefix}mlangs VALUES ('637','rewcurtychdomoarc','悬赏积分类型改变,不要修改文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('638','dontredrewcur','不要减少悬赏积分','0');
INSERT INTO {$tblprefix}mlangs VALUES ('639','recusmmiva','悬赏积分小于最小值','0');
INSERT INTO {$tblprefix}mlangs VALUES ('640','issutaxfree','发表收费附属信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('641','nolimit','不限','0');
INSERT INTO {$tblprefix}mlangs VALUES ('642','lengsmalmilim','长度小于最小限制','0');
INSERT INTO {$tblprefix}mlangs VALUES ('643','lenglargmaxlimi','长度大于最大限制','0');
INSERT INTO {$tblprefix}mlangs VALUES ('644','smallminilimi','小于最小限制','0');
INSERT INTO {$tblprefix}mlangs VALUES ('645','largmaxlimi','大于最大限制','0');
INSERT INTO {$tblprefix}mlangs VALUES ('646','attatamosmaminili','附件数量小于最小限制','0');
INSERT INTO {$tblprefix}mlangs VALUES ('647','notnull','不能为空','0');
INSERT INTO {$tblprefix}mlangs VALUES ('648','liminpda','限输入日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('649','liminpint','限输入整数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('650','liminpnum','限输入数字','0');
INSERT INTO {$tblprefix}mlangs VALUES ('651','limiinputlett','限输入字母','0');
INSERT INTO {$tblprefix}mlangs VALUES ('652','limitinputnumberl','限输入字母与数字','0');
INSERT INTO {$tblprefix}mlangs VALUES ('653','limitinputtagtype','限输入字母开头的_字母数字','0');
INSERT INTO {$tblprefix}mlangs VALUES ('654','limitinputemail','限输入Email','0');
INSERT INTO {$tblprefix}mlangs VALUES ('655','clear','清除','0');
INSERT INTO {$tblprefix}mlangs VALUES ('656','selectall','全选','0');
INSERT INTO {$tblprefix}mlangs VALUES ('657','based_content_page0','基本内容页','0');
INSERT INTO {$tblprefix}mlangs VALUES ('658','content_trace_page0_1','内容追溯页1','0');
INSERT INTO {$tblprefix}mlangs VALUES ('659','content_trace_page0_2','内容追溯页2','0');
INSERT INTO {$tblprefix}mlangs VALUES ('660','remote_download','远程下载方案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('661','notremote','不下载远程文件','0');
INSERT INTO {$tblprefix}mlangs VALUES ('662','netsilistpage','网址列表页','0');
INSERT INTO {$tblprefix}mlangs VALUES ('663','contensourcpage','内容来源页面','0');
INSERT INTO {$tblprefix}mlangs VALUES ('664','resultdealfunc','结果处理函数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('665','fiecontgathpatt','字段内容\r\n采集模印','0');
INSERT INTO {$tblprefix}mlangs VALUES ('666','replmesssouront','替换信息\r\n来源内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('667','repmessagresulcont','替换信息\r\n=>结果内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('668','lisregigathpatt','列表区域\r\n采集模印','0');
INSERT INTO {$tblprefix}mlangs VALUES ('669','liscellsplitag','列表单元\r\n分隔标识','0');
INSERT INTO {$tblprefix}mlangs VALUES ('670','cellurlgathpatte','单元链接<br>\r\n采集模印','0');
INSERT INTO {$tblprefix}mlangs VALUES ('671','celltitlgathepatt','单元标题<br>\r\n采集模印','0');
INSERT INTO {$tblprefix}mlangs VALUES ('672','downjumfilsty','下载跳转文件样式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('673','detail','详情','0');
INSERT INTO {$tblprefix}mlangs VALUES ('674','based_msg','基本信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('675','order','排序','0');
INSERT INTO {$tblprefix}mlangs VALUES ('676','flash','Flash','0');
INSERT INTO {$tblprefix}mlangs VALUES ('677','media','视频','0');
INSERT INTO {$tblprefix}mlangs VALUES ('678','text','单行文本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('679','multitext','多行文本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('680','htmltext','Html文本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('681','image_f','单图','0');
INSERT INTO {$tblprefix}mlangs VALUES ('682','images','图集','0');
INSERT INTO {$tblprefix}mlangs VALUES ('683','flashs','Flash集','0');
INSERT INTO {$tblprefix}mlangs VALUES ('684','medias','视频集','0');
INSERT INTO {$tblprefix}mlangs VALUES ('685','file_f','单点下载','0');
INSERT INTO {$tblprefix}mlangs VALUES ('686','files_f','多点下载','0');
INSERT INTO {$tblprefix}mlangs VALUES ('687','select','单项选择','0');
INSERT INTO {$tblprefix}mlangs VALUES ('688','mselect','多项选择','0');
INSERT INTO {$tblprefix}mlangs VALUES ('689','date_f','日期(时间戳)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('690','int','整数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('691','float','小数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('692','email','Email','0');
INSERT INTO {$tblprefix}mlangs VALUES ('693','system','系统','0');
INSERT INTO {$tblprefix}mlangs VALUES ('694','tagtype','标识类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('695','date','日期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('696','nolimitformat','不限格式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('697','number','数字','0');
INSERT INTO {$tblprefix}mlangs VALUES ('698','letter','字母','0');
INSERT INTO {$tblprefix}mlangs VALUES ('699','numberletter','字母与数字','0');
INSERT INTO {$tblprefix}mlangs VALUES ('700','advancedmes','进阶信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('701','attachmentedit','附件编辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('702','coclass','分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('703','usergroup','会员组','0');
INSERT INTO {$tblprefix}mlangs VALUES ('704','cocname','分类名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('705','amount','数量','0');
INSERT INTO {$tblprefix}mlangs VALUES ('891','award','奖励','0');
INSERT INTO {$tblprefix}mlangs VALUES ('892','awarded','已奖励','0');
INSERT INTO {$tblprefix}mlangs VALUES ('708','plepoimemid','请指定会员ID','0');
INSERT INTO {$tblprefix}mlangs VALUES ('709','crpolicy','积分增减策略','0');
INSERT INTO {$tblprefix}mlangs VALUES ('710','cash','现金','0');
INSERT INTO {$tblprefix}mlangs VALUES ('711','currencyinout','积分充/扣值','0');
INSERT INTO {$tblprefix}mlangs VALUES ('712','otherreason','其它原因','0');
INSERT INTO {$tblprefix}mlangs VALUES ('713','subscribe','订阅','0');
INSERT INTO {$tblprefix}mlangs VALUES ('714','confchoosarchi','请指定正确的文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('715','poinarchnoch','指定的文档未审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('716','noarchivbrowpermis','无文档浏览权限','0');
INSERT INTO {$tblprefix}mlangs VALUES ('717','subattachwanpaycur','订阅此附件需要支付积分 : &nbsp;:&nbsp;','0');
INSERT INTO {$tblprefix}mlangs VALUES ('718','younosuatwaencur','<br><br>您没有订阅此附件所需要的足够积分!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('719','subsattach','订阅附件','0');
INSERT INTO {$tblprefix}mlangs VALUES ('720','saleattach','出售附件','0');
INSERT INTO {$tblprefix}mlangs VALUES ('721','lookinittag','查看原始标识','0');
INSERT INTO {$tblprefix}mlangs VALUES ('722','lookttype','查看 %s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('723','lookselecttag','查看选中标识','0');
INSERT INTO {$tblprefix}mlangs VALUES ('724','titleunknown','标题不详','0');
INSERT INTO {$tblprefix}mlangs VALUES ('726','addinpointalbum','在指定合辑内添加内容','0');
INSERT INTO {$tblprefix}mlangs VALUES ('727','lookpointalbum','您已经指定了以下合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('728','allowadd_arctype','添加文档请选择','0');
INSERT INTO {$tblprefix}mlangs VALUES ('729','allowadd_altype','添加合辑请选择','0');
INSERT INTO {$tblprefix}mlangs VALUES ('730','cheforcon','请检查表单内容!','0');
INSERT INTO {$tblprefix}mlangs VALUES ('731','websiteindex','网站首页','0');
INSERT INTO {$tblprefix}mlangs VALUES ('733','arctype','类型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('734','inadmin','档内管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('735','alladmin','综合管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('736','imged','[图]','0');
INSERT INTO {$tblprefix}mlangs VALUES ('737','choose_item','请选择操作项目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('738','setting_album_abover','设置合辑完结','0');
INSERT INTO {$tblprefix}mlangs VALUES ('739','cancel_album_abover','取消合辑完结','0');
INSERT INTO {$tblprefix}mlangs VALUES ('740','unneedupdate','取消更新申请','0');
INSERT INTO {$tblprefix}mlangs VALUES ('741','set','设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('742','click','点击','0');
INSERT INTO {$tblprefix}mlangs VALUES ('743','offer','报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('744','ordersum','订单额','0');
INSERT INTO {$tblprefix}mlangs VALUES ('745','favorite','收藏','0');
INSERT INTO {$tblprefix}mlangs VALUES ('746','praise','顶','0');
INSERT INTO {$tblprefix}mlangs VALUES ('747','debase','踩','0');
INSERT INTO {$tblprefix}mlangs VALUES ('748','download','下载','0');
INSERT INTO {$tblprefix}mlangs VALUES ('749','p_choose','请选择','0');
INSERT INTO {$tblprefix}mlangs VALUES ('750','cpupdate','同步更新当前文档的复本','0');
INSERT INTO {$tblprefix}mlangs VALUES ('751','noupdate','不更新','0');
INSERT INTO {$tblprefix}mlangs VALUES ('752','cpupdate1','完全同步更新','0');
INSERT INTO {$tblprefix}mlangs VALUES ('753','cpupdate2','部分更新(不更新标题、关健词、缩略图、摘要)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('754','cata_choose','请选择栏目或分类','0');
INSERT INTO {$tblprefix}mlangs VALUES ('755','be_catalog','所属栏目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('756','prompt_msg','提示信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('757','allow_type','选择文档类型添加','0');
INSERT INTO {$tblprefix}mlangs VALUES ('759','addinopen','加入到公用合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('760','albumchoose','请选择','0');
INSERT INTO {$tblprefix}mlangs VALUES ('761','addcpinca','同时在以下栏目发表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('762','addcpincc','同时在以下 %s 中发表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('763','more_set','更多设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('764','add_archive','添加文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('765','content_set','内容设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('766','arc_price','浏览文档售价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('767','annex_price','附件操作售价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('768','set_valid_day','设置有效期(天)','0');
INSERT INTO {$tblprefix}mlangs VALUES ('769','belong_album','所属合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('770','mini','最小','0');
INSERT INTO {$tblprefix}mlangs VALUES ('771','max','最大','0');
INSERT INTO {$tblprefix}mlangs VALUES ('772','mypaymode','我的支付方式:','0');
INSERT INTO {$tblprefix}mlangs VALUES ('773','paynext','货到付款','0');
INSERT INTO {$tblprefix}mlangs VALUES ('774','payalipay','支付宝支付','0');
INSERT INTO {$tblprefix}mlangs VALUES ('775','paytenpay','财付通支付','0');
INSERT INTO {$tblprefix}mlangs VALUES ('776','shipingfee1','平邮','0');
INSERT INTO {$tblprefix}mlangs VALUES ('777','shipingfee2','特快专递EMS','0');
INSERT INTO {$tblprefix}mlangs VALUES ('778','shipingfee3','其它快递公司','0');
INSERT INTO {$tblprefix}mlangs VALUES ('779','alipay_account','支付宝帐号','0');
INSERT INTO {$tblprefix}mlangs VALUES ('780','tenpay_account','财付通帐号','0');
INSERT INTO {$tblprefix}mlangs VALUES ('782','noshiping','无需送货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('783','shipingfee','送货方式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('784','add_inalbum','在指定合辑 %s 中添加文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('785','look_album','查看合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('786','paycurrency','站内帐户支付','0');
INSERT INTO {$tblprefix}mlangs VALUES ('787','websiteseller','网站出售','0');
INSERT INTO {$tblprefix}mlangs VALUES ('788','catas_pointed','已指定的类目属性','0');
INSERT INTO {$tblprefix}mlangs VALUES ('789','operate_item','操作项目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('790','content_list','内容列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('791','all_channel','全部模型','0');
INSERT INTO {$tblprefix}mlangs VALUES ('792','all_catalog','全部栏目','0');
INSERT INTO {$tblprefix}mlangs VALUES ('793','inclear','退出合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('794','incheck','辑内审核','0');
INSERT INTO {$tblprefix}mlangs VALUES ('795','inuncheck','辑内解审','0');
INSERT INTO {$tblprefix}mlangs VALUES ('796','readd','重发布','0');
INSERT INTO {$tblprefix}mlangs VALUES ('797','inorder','辑内排序','0');
INSERT INTO {$tblprefix}mlangs VALUES ('798','offers','报价数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('799','order_num','订单数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('800','favorites','收藏数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('801','praises','顶数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('802','debases','踩数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('803','answers','答案数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('804','adopts','采用数','0');
INSERT INTO {$tblprefix}mlangs VALUES ('805','add_time','添加时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('806','update_time','更新时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('807','readd_time','重发时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('808','end1_time','结束时间','0');
INSERT INTO {$tblprefix}mlangs VALUES ('809','current_album_set','已经归入的合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('810','choose_want_setin_album','请选择需要归入的合辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('811','exit_album','退辑','0');
INSERT INTO {$tblprefix}mlangs VALUES ('812','content_load_list','请选择需要加载的文档','0');
INSERT INTO {$tblprefix}mlangs VALUES ('813','orderstate','订单状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('814','wait_cpcheck','等待商家确认','0');
INSERT INTO {$tblprefix}mlangs VALUES ('815','wait_pay','等待付款','0');
INSERT INTO {$tblprefix}mlangs VALUES ('816','wait_send','等待发货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('817','goods_send','已发货','0');
INSERT INTO {$tblprefix}mlangs VALUES ('818','order_ok','完成','0');
INSERT INTO {$tblprefix}mlangs VALUES ('819','order_cancel','取消','0');
INSERT INTO {$tblprefix}mlangs VALUES ('820','searchmember','搜索会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('821','confirmorders','确认订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('822','deleteorders','删除订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('823','modify_confirm','修改并确认','0');
INSERT INTO {$tblprefix}mlangs VALUES ('824','confirm_cancel','确定取消','0');
INSERT INTO {$tblprefix}mlangs VALUES ('825','confirm_pay','确认并付款','0');
INSERT INTO {$tblprefix}mlangs VALUES ('826','alipay_keyt','支付宝密钥','0');
INSERT INTO {$tblprefix}mlangs VALUES ('827','tenpay_keyt','财富通密钥','0');
INSERT INTO {$tblprefix}mlangs VALUES ('828','addtooffer','加入报价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('829','seller','商家','0');
INSERT INTO {$tblprefix}mlangs VALUES ('831','modify_payed','修改已付款','0');
INSERT INTO {$tblprefix}mlangs VALUES ('832','log_order_pay','订单付款 订单号：%s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('833','log_order_rev','订单收款 订单号：%s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('834','ordmode','订单模式','0');
INSERT INTO {$tblprefix}mlangs VALUES ('835','be_confirm','必需确认','0');
INSERT INTO {$tblprefix}mlangs VALUES ('836','no_confirm','无需确认','0');
INSERT INTO {$tblprefix}mlangs VALUES ('840','productname','产品名称','0');
INSERT INTO {$tblprefix}mlangs VALUES ('841','productlist','产品列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('842','pro_price','建议价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('843','avg_price','平均价','0');
INSERT INTO {$tblprefix}mlangs VALUES ('844','read_state','已读状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('845','areplyed','有反馈','0');
INSERT INTO {$tblprefix}mlangs VALUES ('846','noareply','无反馈','0');
INSERT INTO {$tblprefix}mlangs VALUES ('847','replylist','回复列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('848','areply','反馈','0');
INSERT INTO {$tblprefix}mlangs VALUES ('849','attachmentlist','附件列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('850','friend','好友','0');
INSERT INTO {$tblprefix}mlangs VALUES ('851','qstate','咨询状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('852','saveforever','永久保存','0');
INSERT INTO {$tblprefix}mlangs VALUES ('853','hours','小时','0');
INSERT INTO {$tblprefix}mlangs VALUES ('854','days','天','0');
INSERT INTO {$tblprefix}mlangs VALUES ('855','weeks','星期','0');
INSERT INTO {$tblprefix}mlangs VALUES ('856','month','月','0');
INSERT INTO {$tblprefix}mlangs VALUES ('857','inbrowser','浏览器进程','0');
INSERT INTO {$tblprefix}mlangs VALUES ('858','ucpm','UC短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('859','syspm','系统短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('860','memberpm','会员短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('861','noreadpm','未读短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('862','state','状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('863','nonenewpm','没有新短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('865','onyousay','您在 %s 说：','0');
INSERT INTO {$tblprefix}mlangs VALUES ('866','historypm','历史短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('867','nonepm','没有短信','0');
INSERT INTO {$tblprefix}mlangs VALUES ('868','pmdatamiss','短信内容不完整','0');
INSERT INTO {$tblprefix}mlangs VALUES ('869','pmsenderr','短信发送错误','0');
INSERT INTO {$tblprefix}mlangs VALUES ('870','fupmrecord','与 %s 的短消息记录：','0');
INSERT INTO {$tblprefix}mlangs VALUES ('871','today','今天','0');
INSERT INTO {$tblprefix}mlangs VALUES ('872','near3days','最近三天','0');
INSERT INTO {$tblprefix}mlangs VALUES ('873','thisweek','本周','0');
INSERT INTO {$tblprefix}mlangs VALUES ('874','onformsay','%s 在 %s 说：','0');
INSERT INTO {$tblprefix}mlangs VALUES ('875','checksubject','检查重名','0');
INSERT INTO {$tblprefix}mlangs VALUES ('876','defaultplayer','默认播放器','0');
INSERT INTO {$tblprefix}mlangs VALUES ('877','file_upload','附件上传','0');
INSERT INTO {$tblprefix}mlangs VALUES ('878','att_admin','附件管理','0');
INSERT INTO {$tblprefix}mlangs VALUES ('879','normal_upload','普通上传','0');
INSERT INTO {$tblprefix}mlangs VALUES ('880','bat_upload','批量上传','0');
INSERT INTO {$tblprefix}mlangs VALUES ('881','zip_upload','打包上传','0');
INSERT INTO {$tblprefix}mlangs VALUES ('882','remote_att','远程附件','0');
INSERT INTO {$tblprefix}mlangs VALUES ('883','default_set','默认设置','0');
INSERT INTO {$tblprefix}mlangs VALUES ('884','mwelcome','欢迎 <b>%s</b> 进入','0');
INSERT INTO {$tblprefix}mlangs VALUES ('885','mb_type','您的会员类型是：%s','0');
INSERT INTO {$tblprefix}mlangs VALUES ('886','receives','收到的','0');
INSERT INTO {$tblprefix}mlangs VALUES ('887','mymsg','消息中心','0');
INSERT INTO {$tblprefix}mlangs VALUES ('888','sorders','的订单','0');
INSERT INTO {$tblprefix}mlangs VALUES ('890','answerlist','答案列表','0');
INSERT INTO {$tblprefix}mlangs VALUES ('894','newreg','注册新会员','0');
INSERT INTO {$tblprefix}mlangs VALUES ('895','adminnopm','管理员请不要变更会员类型！','0');
INSERT INTO {$tblprefix}mlangs VALUES ('896','notranpro','没有您可用的变更方案！','0');
INSERT INTO {$tblprefix}mlangs VALUES ('897','areply_state','反馈状态','0');
INSERT INTO {$tblprefix}mlangs VALUES ('898','freeinfo','插件信息','0');
INSERT INTO {$tblprefix}mlangs VALUES ('899','marchive','会员档案','0');
INSERT INTO {$tblprefix}mlangs VALUES ('900','mcomment','会员评论','0');
INSERT INTO {$tblprefix}mlangs VALUES ('901','mreply','会员回复','0');
INSERT INTO {$tblprefix}mlangs VALUES ('903','errorpaymode','支付模式错误或信息不完整','1262220454');
INSERT INTO {$tblprefix}mlangs VALUES ('904','alipay','支付宝','1262221859');
INSERT INTO {$tblprefix}mlangs VALUES ('905','tenpay','财付通','1262221877');
INSERT INTO {$tblprefix}mlangs VALUES ('906','confirm_pay_info','确认付款信息','1262223633');
INSERT INTO {$tblprefix}mlangs VALUES ('907','systemerror','系统错误','1262224835');
INSERT INTO {$tblprefix}mlangs VALUES ('908','account_plaza','%s-帐户充值','1262225548');
INSERT INTO {$tblprefix}mlangs VALUES ('909','and_more','%s 等商品','1262226432');
INSERT INTO {$tblprefix}mlangs VALUES ('910','orderspayed','已付款确认','1262598113');
INSERT INTO {$tblprefix}mlangs VALUES ('911','confirm_set_payed','确定对方已支付 %s 元的货款，并结单吗？','1262610798');
INSERT INTO {$tblprefix}mlangs VALUES ('912','please_set_payed','请设置已付款数额！','1262611388');
INSERT INTO {$tblprefix}mlangs VALUES ('913','set_payed','以防误操作，请填入已付款数额后再次提交！','1262611853');
INSERT INTO {$tblprefix}mlangs VALUES ('914','crproject_tip','您现有<b>%n</b> <span style=\"color:red\">%v</span> %u，您的对换率是 <span style=\"color:red\">%d</span>。请输入您要对换的数量：','1263091556');
INSERT INTO {$tblprefix}mlangs VALUES ('915','currency_list_tip','积分列表（您还可以通过以下积分兑换而来）','1262941048');
INSERT INTO {$tblprefix}mlangs VALUES ('916','currency0_extract','<b>%n</b> 您现有 <span style=\"color:red\">%v</span> %u，您的提现率是 <span style=\"color:red\">%d</span>，最低提现额度是 <span style=\"color:red\">%c</span> %u。','1263345621');
INSERT INTO {$tblprefix}mlangs VALUES ('917','input_extract_count','请输入您要提现的金额：','1262958884');
INSERT INTO {$tblprefix}mlangs VALUES ('918','submit_extract','申请提现','1262959298');
INSERT INTO {$tblprefix}mlangs VALUES ('919','less_than_mincount','不满足最低提现额度，无法提现！您可以通过积分对换后再试','1263029083');
INSERT INTO {$tblprefix}mlangs VALUES ('920','currency_trade_tip','可以兑换成 <span style=\"color:red\">%v</span> 元现金。','1263085091');
INSERT INTO {$tblprefix}mlangs VALUES ('921','extract_total_tip','折扣后您可以获得 <span style=\"color:red\">%v</span> 元现金。','1263085240');
INSERT INTO {$tblprefix}mlangs VALUES ('922','extract_total','输入的积分兑换后，您将有 <span style=\"color:red\">%v</span> 元现金。','1263085435');
INSERT INTO {$tblprefix}mlangs VALUES ('923','extract_confirm','确定要提现 %i 元现金吗，折扣后您将获得 %v 元现金？','1263086260');
INSERT INTO {$tblprefix}mlangs VALUES ('924','extract_mincount_tip','系统设定至少要提取 %v 元现金，请重新输入！','1263086677');
INSERT INTO {$tblprefix}mlangs VALUES ('935','yourrepugrade','您的信用等级是','1264918870');
INSERT INTO {$tblprefix}mlangs VALUES ('925','extract_record_modify','提现申请记录修改','1263109601');
INSERT INTO {$tblprefix}mlangs VALUES ('926','extract_count','提现数量','1263110437');
INSERT INTO {$tblprefix}mlangs VALUES ('927','extract_discount','提现率','1263345606');
INSERT INTO {$tblprefix}mlangs VALUES ('928','checkdate','审核时间','1263191843');
INSERT INTO {$tblprefix}mlangs VALUES ('929','extract_getcount','提现获得','1263192013');
INSERT INTO {$tblprefix}mlangs VALUES ('930','no_modify_action','没有修改动作，无需提交！','1263201267');
INSERT INTO {$tblprefix}mlangs VALUES ('931','extract_record_info','提现记录详情','1263201721');
INSERT INTO {$tblprefix}mlangs VALUES ('932','extract_list','提现记录列表','1263257259');
INSERT INTO {$tblprefix}mlangs VALUES ('933','delstate','删除状态','1263266032');
INSERT INTO {$tblprefix}mlangs VALUES ('934','extract_remark','请留下您的提款方式和相关信息，谢谢！','1263344300');
INSERT INTO {$tblprefix}mlangs VALUES ('936','volno','卷号','1265874551');
INSERT INTO {$tblprefix}mlangs VALUES ('937','vol_admin','分卷管理','1265874608');
INSERT INTO {$tblprefix}mlangs VALUES ('938','vol','分卷','1265874937');
INSERT INTO {$tblprefix}mlangs VALUES ('939','set_volid','设置分卷','1265874976');
INSERT INTO {$tblprefix}mlangs VALUES ('940','volname','分卷标题','1265875996');
INSERT INTO {$tblprefix}mlangs VALUES ('941','add_vol','添加分卷','1265876044');
INSERT INTO {$tblprefix}mlangs VALUES ('942','memcert_need','会员认证申请','1271296134');
INSERT INTO {$tblprefix}mlangs VALUES ('943','memcert','会员认证','1271296148');
INSERT INTO {$tblprefix}mlangs VALUES ('945','memcert_click','点击申请','1271296220');
INSERT INTO {$tblprefix}mlangs VALUES ('946','memcert_need_tip','在所选之前未认证的内容会要求一并填写','1271296360');
INSERT INTO {$tblprefix}mlangs VALUES ('947','msg_code','确认码','1271384680');
INSERT INTO {$tblprefix}mlangs VALUES ('948','click_get_mcode','【点击获得确认码】','1271384744');
INSERT INTO {$tblprefix}mlangs VALUES ('949','memcert_checkimg','申请中认证','1272022320');
INSERT INTO {$tblprefix}mlangs VALUES ('950','memcert_current','当前认证级别','1272021144');
INSERT INTO {$tblprefix}mlangs VALUES ('951','memcert_checked','通过的认证','1272022228');
INSERT INTO {$tblprefix}mlangs VALUES ('952','memcert_can_check','可申请认证','1272021261');
INSERT INTO {$tblprefix}mlangs VALUES ('953','mdirname','会员静态目录','1272196353');
INSERT INTO {$tblprefix}mlangs VALUES ('954','enddate1','截止日期','1272846241');
INSERT INTO {$tblprefix}mlangs VALUES ('955','gtex','使用 %s 购买 %s 中的会员组','1272981486');
INSERT INTO {$tblprefix}mlangs VALUES ('956','jumpurl','跳转URL','1273200087');
INSERT INTO {$tblprefix}mlangs VALUES ('957','agjumpurl','请输入以http://开头的完整url。指定跳转后，所有该文档的url均为该地址。','1273204035');
INSERT INTO {$tblprefix}mlangs VALUES ('958','spread_firend','邀请好友,获得积分','1274237464');
INSERT INTO {$tblprefix}mlangs VALUES ('959','spread_closed','功能未开启','1274237594');
INSERT INTO {$tblprefix}mlangs VALUES ('960','spread_firend_show','将本站推荐给朋友：','1274237836');
INSERT INTO {$tblprefix}mlangs VALUES ('961','spread_firend_club','邀请朋友来本站注册：','1274237874');
INSERT INTO {$tblprefix}mlangs VALUES ('962','click_and_copy','点击右键复制链接','1274237901');
INSERT INTO {$tblprefix}mlangs VALUES ('963','spread_award','奖励说明：成功推荐一个%s,您就可以增加 %s 个%s。赶快行动吧！','1274238128');
INSERT INTO {$tblprefix}mlangs VALUES ('964','visitor','访问者','1274238156');

DROP TABLE IF EXISTS {$tblprefix}mmenus;
CREATE TABLE {$tblprefix}mmenus (
  mnid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  url varchar(255) NOT NULL,
  mtid smallint(6) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  issys tinyint(1) unsigned NOT NULL default '0',
  isbk tinyint(1) unsigned NOT NULL default '0',
  pmid smallint(6) unsigned NOT NULL default '0',
  onclick varchar(255) NOT NULL default '',
  newwin tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (mnid)
) TYPE=MyISAM AUTO_INCREMENT=251;

INSERT INTO {$tblprefix}mmenus VALUES ('1','资料设置','?action=memberinfo','1','1','1','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('2','修改密码','?action=memberpwd','1','1','3','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('3','我的状态','?action=memberstate','1','1','2','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('4','兑换积分','?action=crexchange','8','1','9','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('5','兑换会员组','?action=gtexchange','1','1','4','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('6','在线支付','?action=payonline','8','1','8','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('7','其它支付','?action=payother','8','1','7','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('8','现金支付记录','?action=pays','8','1','6','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('9','积分变更记录','?action=crrecords','8','1','10','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('10','发表新文档','tools/addpre.php?nmuid=3','2','1','4','1','0','0','return floatwin(\'open_acrhiveedit\',this)','0');
INSERT INTO {$tblprefix}mmenus VALUES ('250','文档管理','?action=archives','2','1','1','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('13','添加插件信息','?action=farchiveadd','2','1','5','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('14','插件信息管理','?action=farchives','2','1','6','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('15','个人分类','?action=uclasses','2','1','7','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('16','我的附件','?action=userfiles','2','1','8','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('18','我的收藏','?action=favorites','3','1','1','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('20','我的评论','?action=comments','3','1','2','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('21','我的答案','?action=answers','3','1','4','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('23','我的购物车','tools/cart.php','8','1','3','1','0','0','return floatwin(\'open_mycart\',this)','0');
INSERT INTO {$tblprefix}mmenus VALUES ('24','我的订单','?action=orders','8','1','1','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('25','我的购物记录','?action=purchases','8','1','2','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('26','发短信','?action=pmsend','4','1','0','1','0','2','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('27','收件箱','?action=pmbox','4','1','0','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('30','会员类型变更','?action=mtrans','1','1','6','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('31','文档购买记录','?action=subscribes','3','0','10','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('32','会员组变更','?action=utrans','1','1','5','1','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('105','提交的回复','?action=mreplys','9','0','0','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('106','收到的回复','?action=amreplys','9','0','0','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('107','提交的评论','?action=mcomments','9','0','0','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('114','我的举报','?action=mreports','3','1','6','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('115','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('116','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('117','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('118','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('119','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('120','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('121','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('122','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('123','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('124','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('125','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('126','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('127','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('128','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('129','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('130','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('131','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('132','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('133','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('134','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('135','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('136','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('137','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('138','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('139','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('140','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('141','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('142','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('143','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('144','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('145','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('146','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('147','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('148','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('149','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('150','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('151','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('152','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('153','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('154','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('155','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('156','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('157','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('158','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('159','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('160','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('161','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('162','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('163','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('164','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('165','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('166','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('167','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('168','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('169','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('170','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('171','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('172','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('173','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('174','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('175','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('176','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('177','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('178','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('179','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('180','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('181','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('182','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('183','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('184','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('185','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('186','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('187','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('188','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('189','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('190','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('191','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('192','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('193','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('194','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('195','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('196','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('197','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('198','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('199','','','0','1','0','0','1','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('238','我的好友','?action=mfriends','9','0','0','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('239','好友申请管理','?action=amfriends','9','0','0','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('218','友情链接','?action=mflinks','9','0','0','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('219','友情链接申请','?action=amflinks','9','0','0','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('232','报价管理','?action=offers&nmuid=5','3','0','7','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('237','文档回复','?action=rarchives','3','0','8','0','0','0','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('247','商品支付方式','?action=paymanager','8','1','5','0','0','4','','0');
INSERT INTO {$tblprefix}mmenus VALUES ('248','销售订单','?action=aorders','8','0','4','0','0','4','','0');

DROP TABLE IF EXISTS {$tblprefix}mmsgs;
CREATE TABLE {$tblprefix}mmsgs (
  lid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  content text NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid)
) TYPE=MyISAM AUTO_INCREMENT=380;

INSERT INTO {$tblprefix}mmsgs VALUES ('1','loginsucceed','登录成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('2','userisforbid','所在的屏蔽组禁止了此功能','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('3','confirmaltype','请指定正确的合辑类型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('4','noaltypepermission','没有%s的发表权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('5','cacoverchannel','请指定正确的合辑封面模型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('6','usergroupalterfinish','会员组设置成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('7','waitcheck','请等待管理员审核！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('8','ybomcnntu','您所属的会员模型不能申请此会员组!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('9','allowamountlimit','限额文档数量超出限制！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('89','choosereply','请指定正确的回复','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('11','cusernocatalogissue','当前用户没有指定栏目的发表权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('12','catalognoaltype','%s 栏目不能添加 %s 合辑类型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('13','safecodeerr','验证码错误','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('14','albumaddfailed','合辑添加失败！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('15','albumaddfinish','合辑添加成功！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('16','selectoperateitem','请选择操作项目','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('17','selectalbum','请选择合辑','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('18','operating','文件操作正在进行中...<br>共 %s 页，正在处理第 %s 页<br><br>%s>>中止当前操作%s','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('19','archiveoperatefinish','文档操作完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('20','confirmalbum','请指定合辑','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('21','selectownalbum','请选择您自己的合辑','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('22','updatearcneed','请提交文档更新申请','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('23','arceditfailed','文档编辑失败','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('24','albumeditfinish','合辑编辑成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('25','exitalbumfinish','退出合辑成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('26','setalbumfinish','归辑操作成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('27','albumadminfinish','合辑管理成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('28','aboveralbum','已完结合辑','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('29','ybomccntu','您所属的会员模型不能申请此会员组!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('30','nalarcalbum','没有可以加载的文档或合辑','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('31','ycnpu','您不能申请指定的会员组!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('32','choosegrouptype','请指定正确的会员组体系!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('33','nopermission','没有指定项目的操作权限!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('34','chooseuserurlparam','请指定正确的用户链接参数!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('35','selectcomment','请选择评论','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('36','commentadminfinish','评论管理完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('37','selectarchive','请选择文档','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('38','editcoclassfinish','编辑分类完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('39','selectlink','请选择友情链接','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('40','linkadminfinish','友情链接管理完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('41','inputuclasscname','请输入个人分类名称','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('42','dellinkconfirm','删除友情链接确认<br /><br />%s[确认]%s&nbsp;&nbsp;%s[取消]%s','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('43','chooseyouruclass','请选择你的个人分类','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('44','addcoclassfinish','添加分类完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('45','pcuaol','指定的栏目个人分类数量超出限制','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('46','succeeddellink','成功删除 %s 个友情链接','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('47','pccau','指定的栏目不能添加个人分类','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('48','uclassoverlimit','个人分类数量封面限制','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('49','chooseoperatemember','请选择操作会员','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('50','friendneedadminok','好友申请管理完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('51','delfriendconfirm','删除好友确认<br /><br />%s[确定]%s&nbsp;&nbsp;%s[取消]%s','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('52','succeeddelfriend','成功删除 %s 个好友','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('53','sagreefriendadd','成功同意 %s 个好友添加','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('54','subscribedelsucceed','订阅删除成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('55','selectreply','请选择回复','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('56','subscribecontent','请选择订阅内容','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('57','replyadminfinish','回复管理完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('58','searchoverquick','搜索操作过于频繁','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('59','chooseanswer','请选择一个答案','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('60','usernosearchpermi','所属会员组没有搜索权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('61','questionclose','问题关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('62','reportsucceed','举报成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('63','inputanswer','请输入回答内容','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('64','confirmselectreport','请选择举报','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('65','noadminpermi','你没有管理权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('66','reportdelsucceed','举报删除成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('67','updatesucceed','%s更新成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('68','chooseyourreport','请选择你自己的举报','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('69','choosereport','请指定正确的举报','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('70','archivenocheck','选定的文档没审核','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('71','younoreportpermi','你没有举报权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('72','reportfunctionclose','举报功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('73','pleasesetcommuitem','请设置交互项目!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('74','choosereportobject','请指定正确的举报对象','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('75','answereditfinish','答案编辑成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('76','operateoverdate','操作过期','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('78','commuitemclose','此交互操作项目已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('79','choosearchive','选择文档','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('344','noopenalbum','没有有效的公用合辑！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('82','choosechannel','请指定相关模型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('83','channelnoadd','指定模型无添加权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('84','confirmselectreply','请选择回复','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('85','adminxchannel','管理专用模型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('86','replysetsucceed','回复设置成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('87','chooseyourreply','请选择你自已的回复!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('88','choosecatalog','请指定正确栏目','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('90','addreplyoverquick','添加回复操作过于频繁','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('91','norepeataddreply','请不要重复添加回复!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('92','yntrap','你没有此回复管理权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('93','younoreplypermi','你没有回复权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('94','replyinvalid','回复无效','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('95','replyfunclosed','回复功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('96','choosecomitem','请指定正确的交互项目','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('97','choosereplyobject','请指定正确的回复对象','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('98','arcsaveerr','文档保存时发生错误','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('99','arcaddfinish','文档添加完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('281','plogin','游客没有操作权限！\r\n请先登陆或注册新会员！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('102','withoutarchiveoralbum','没有相关联的文档或合辑','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('105','noanswerchannel','未定义答疑模型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('106','pmsendfinish','短信发送成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('107','pmdatamissing','短信资料不完全','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('108','regcodeerror','验证码输入错误！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('109','chooseyourarchive','请选择你自己的文档','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('110','pointpm','请指定短信','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('111','pmdelfinish','短信删除操作完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('112','choosedeltem','请选择删除项目','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('113','paymodifyfinish','支付信息修改完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('114','paynomodify','已充值支付信息不能修改','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('115','choosepayrecord','请指定正确的支付记录','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('116','confirmchoosepays','请指定正确的支付','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('117','csmds','现金充值信息删除成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('118','selectpayrecord','请选择支付记录','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('119','chooseobject','请选择对象','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('120','objectnotreply','指定的对象不支持回复！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('121','csnsspwad','现金充值通知发送成功,请等待管理员处理','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('122','pinputpayamount','请输入支付数量','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('123','cartmodifyfinish','购物车修改完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('124','pugoodssucceed','商品订单生成成功，请到会员中心管理订单。<br /><br />\r\n立即管理订单：%s','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('125','pugoodsfailed','购买商品失败','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('126','nvopi','没有有效的在线支付接口','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('127','ordersmodifyfinish','订单修改完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('128','cocm','已审订单不能修改','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('129','invaliditem','无效项目操作','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('130','itemnopermission','你没有这个项目权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('131','choosecommentobject','选择评论对象','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('132','setcommuitem','设置交互项目','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('133','commentclose','评论功能关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('134','nocommentpermission','你没有评论权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('135','nobrowsepermission','没有文档浏览权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('136','overquick','操作过于频繁','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('137','dontreoperate','请不要重复操作','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('269','cheordcanmod','不能对此状态订单进行本操作','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('139','choosecomment','请指定正确的评论','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('140','chooseorders','请指定正确的订单','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('141','orderopfinish','订单操作完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('142','selectorders','请选择订单','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('143','offerupdatesucce','报价更新成功!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('144','nmcmessage','请输入报价价格','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('145','pcyo','请选择你自已的报价!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('146','chooseoffer','请指定正确的报价','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('147','norepeataddoffer','请不要重复添加报价!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('148','chooseyourcomment','请选择你自已的评论！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('149','noofferpermi','你没有报价权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('150','comfunclos','评论功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('151','chooseproduct','请指定正确的产品','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('152','loginmemcenter','请登录会员中心 [%s]','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('283','poinarcnoche','您无法对未审文档执行指定的操作！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('153','commentdelsucceed','评论删除成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('154','noadminpermission','您没有管理权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('155','membertypealter','会员类型变更完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('156','commentsucceed','评论操作成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('157','nousepublicaltype','没有可用的公共合辑类型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('158','curexproject','请指定当前兑换方案','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('159','inputexamount','请输入兑换数量','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('160','ycnpmt','你不能申请指定的会员类型!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('161','examountsmall','兑换数量少于兑换基数','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('162','sucdelete','成功删除','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('163','examountlarge','兑换数量大于拥有总数','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('164','currencyexfinish','积分兑换完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('165','delreportcon','删除举报确认','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('166','reportadminfin','举报管理完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('167','uccurrencyexitem','请指定UCenter积分兑换项目','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('169','nameadminfin','%s管理完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('170','noucuser','UCenter中没有当前会员资料！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('171','pdrar','请不要重复添加举报!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('172','mrfc','会员举报功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('173','psmci','请设置会员交互项目!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('174','choosemember','请指定正确的会员!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('175','invalidoperate','无效操作','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('176','choosemessagecoclass','请指定正确的信息分类','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('177','nococlassaddpermi','您没有这个分类的添加权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('178','msgsaveerr','信息保存错误','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('179','pcyr','请选择你的评论回复','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('180','freeinfoaddfinish','附属信息添加完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('181','selectmessage','请选择信息','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('182','yntroap','你没有此回复的管理权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('183','memberrfc','会员回复功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('184','chooseyourmessage','请选择您自己的信息','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('185','msgforbidupdate','指定的信息审核后不能修改，需要修改请联系管理员。','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('186','freeinfoeditfinish','附属信息编辑完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('187','selectfriend','请选择好友','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('188','choosemessage','请指定正确的信息','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('189','setpurchaseamount','请指定购买单元数量','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('190','dontresendorders','不要重复发送订单','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('191','fordersendfinish','订单发送完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('192','fordermodifyfinish','订单修改完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('193','consultchannel','请指定咨询类信息！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('194','maxamountlimit','超过最大数量限制','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('195','datamissing','数据不完整','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('196','confirmchooseflink','请指定正确的友情链接','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('197','addconsultsucceed','添加咨询成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('198','pdraf','请不要重复添加友情链接!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('199','mffc','会员友情链接功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('200','selectfavoritearc','请选择收藏文档','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('201','favoritedelsucceed','收藏删除成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('202','choflinkobject','请指定正确的友情链接对象','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('203','addconsultcoclass','请先添加有效的咨询信息分类','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('204','messagefinish','信息操作成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('205','nocheckmessage','未审信息','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('206','consultcoclass','请指定正确的咨询分类','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('207','consultclosed','此咨询项目已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('208','choosematype','请指定正确的会员档案类型!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('209','matypenoadd','您所在的会员组没有指定类型会员档案的添加权限!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('210','offopesucce','报价操作成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('211','conoffer','请选择报价','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('212','ponso','指定的对象不支持报价!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('213','chooseoffbje','请指定正确的报价对象','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('214','notsamepwd','两次输入密码不一致','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('215','memberpwdillegal','会员密码不合规范','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('216','refindpwdsucceed','会员找回密码成功！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('217','selectopeitem','请选择操作列表','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('218','addcrexusergroup','请先添加有效的积分兑换会员组','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('219','getgrouptype','请指定会员组体系','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('220','getcytype','请指定保健类型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('221','getusergroup','请指定会员组','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('222','noenoughcurrency','没有足够积分','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('223','favadminfinish','收藏管理完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('224','cyexusergroupfinish','积分兑换会员组完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('225','selectmember','请选择会员','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('226','dontrepeatlogin','请不要重复登陆&nbsp;[%s退出%s]','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('227','membercnameillegal','会员名称不合规范','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('228','mempassmodsuc','会员密码修改成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('229','pwdillegal','密码不规范','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('230','oldpasserror','原密码错误','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('231','outmemberactive','站外注册会员,需要激活!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('232','nocheckmember','未审会员','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('233','loginfailed','会员登陆失败','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('234','memmesmodfin','会员信息修改完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('235','mememill','会员Email不合规范','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('236','memactfai','会员激活失败','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('237','impdra','站内会员,请不要重复激活!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('238','choosememchal','请指定正确的会员模型','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('239','userchecking','用户等待审核','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('240','logoutsucceed','会员退出成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('241','emailactiving','Email激活中','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('242','memactivesucceed','会员激活成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('243','membernamelenillegal','会员名称长度不规范','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('244','emailillegal','会员Email不规范','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('245','ucenterdisabled','Ucenter禁用','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('246','nomemberemail','指定会员不存在或Email错误','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('247','mastercannotuse','管理员不能使用此功能！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('248','lostpwd_send','取回密码的方法成功发送到您的电子邮箱!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('249','plenorepaddcomm','请不要重复添加评论','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('250','ynothcomadmpermi','请没有此评论的管理权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('251','younocompermi','您没有评论权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('252','memcomfunclo','会员评论功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('253','selectmarchive','请选择会员档案!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('254','marcnotdel','您无法删除此会员档案，请与管理员联系!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('255','selectyoumarc','请选择属于您自已的会员档案进行相关操作!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('256','marcdelfin','指定的会员档案删除成功!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('257','marcaddfinish','会员档案添加成功!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('258','choosemarchive','请选择正确的会员档案!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('259','chooseinalbum','请指定需要添加内容的合辑!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('260','noinalbumaddpermission','对不起，您没有在指定合辑内添加新内容的权限!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('261','arceditfinish','文档编辑完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('262','nogoods','尚未添加商品','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('263','paymodefinish','支付方式设置完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('264','nopaymode','商家没有设置付款方式','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('265','confchoosarchi','请指定正确的文档！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('266','crc_error','数据校验失败','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('271','pay_no_money','现金帐户余额不足，请先充值。','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('267','nocurrency','现金帐户余额不足','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('268','goods_nums_err','商品不存在或库存量不足','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('272','ordopefin','订单操作完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('273','select_both_cc','确认订单 和 取消订单 不能并存执行','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('274','orddelfin','订单删除成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('275','paymodeerr','此商家不支持当前支付方式','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('276','paymodecerr','请选择付款方式','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('277','ordmodpay','付款金额修改成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('282','commentsetsucceed','评论设置成功！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('278','no_product','产品库中没有可供报价的产品!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('279','productadded','所选产品已加入到您的报价库！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('284','younoitempermis','您没有此项目的操作权限！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('285','dontrepeatadd','请不要重复执行此操作！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('286','addoverquick','操作过于频繁！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('287','notnull','%s 不能为空','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('288','submitsucceed','您发送的内容提交成功！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('289','noissuepermission','没有 %s 的发表权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('290','choosealbum','请指定正确的合辑','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('291','albumisover','您所指定的合辑已设置为完结，不能添加或加载新的内容！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('292','albumisoneuser','你所指定的合辑为个人合辑，只有合辑作者才可以添加新的内容！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('293','albumisload','您所指定的合辑为加载性合辑，不能直接在合辑中添加新的内容！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('294','albumovermax','您所指定的合辑的内容数量已达到最大数限制，请清除部分辑内内容后才可以添加或加载新的内容！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('295','nousernooperatepermis','游客无操作权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('296','setcomitem','请设置交互项目!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('297','questionclosed','问题已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('298','inputanswercontent','请输入答疑内容','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('299','answeroverminlength','答案超出最小字长','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('300','answeraddfinish','答案添加完成','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('301','choosevoteobject','请指定正确的投票对象','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('302','loginmember','登录会员','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('303','dontnrepeatvote','请不要重复投票','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('304','votesucceed','投票成功!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('305','chooseyouanswer','请选择你自已的答案','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('348','norepeatoper','请不要重复操作！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('307','noavailableitemoper','无效项目操作','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('310','choosecoclass','请选择类系','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('311','nousnopurchasepermi','游客没有购买权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('312','nousernofavoritepermis','游客没有收藏权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('313','choosecommuitem','您所指定的操作项目不存在或被关闭！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('314','favoriteamooverlimit','您的收藏夹的空间已满！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('315','archivealreadyfavorite','您已经收藏了当前文档，请查看收藏夹！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('316','favoritesucceed','收藏成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('317','dorepeataddcomment','请不要重复发表评论','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('318','addcommentoverquick','发表评论操作太频繁','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('319','cannotfavoritemember','不能收藏此会员!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('320','favoriatefunclos','收藏功能关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('321','younofavoriatpermis','您没有收藏权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('322','memalrefavorite','会员已经收藏','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('324','setmemcommitem','请设置会员交互项目!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('328','nousernoaddfripermis','游客没有添加好友权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('327','dorepeataddflink','请不要重复添加友情链接!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('329','cannotaddyourself','不能添加你自已的!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('330','friamountoverlim','好友数量超过限制','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('331','memberalreadyadd','此会员已经添加!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('332','friendaddsucce','好友添加成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('334','dorepeataddreply','请不要重复添加回复!','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('335','dorepeataddreport','不要重复提交举报','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('336','scorefunclosed','评分功能已关闭','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('337','younoscorepermis','您没有评分权限','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('338','dontrepeatscore','请不要重复评分','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('339','scoresucceed','评分成功','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('340','nousernoofferpermis','游客没有报价权限，请先登录或注册！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('341','offerexist','本产品已经在您的报价库中！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('342','offersubmitsucceed','报价添加成功！<br><br>请对报价作详细设置！','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('343','commentmembernotexist','评论的会员不存在','0');
INSERT INTO {$tblprefix}mmsgs VALUES ('349','orderpayfinish','订单付款状态设置成功','1261299745');
INSERT INTO {$tblprefix}mmsgs VALUES ('351','no_extract_permission','您没有提现的权限！','1262944602');
INSERT INTO {$tblprefix}mmsgs VALUES ('352','currency_muns_lack','您输入的 %s 数量不合法！','1263108680');
INSERT INTO {$tblprefix}mmsgs VALUES ('353','less_than_mincount','低于最小提取额度 %s 元，无法提取！','1263093012');
INSERT INTO {$tblprefix}mmsgs VALUES ('354','extract_muns_lack','提取数量超过了您所拥有的数量','1263095986');
INSERT INTO {$tblprefix}mmsgs VALUES ('355','extract_operate_finish','提现申请操作完成！','1263105079');
INSERT INTO {$tblprefix}mmsgs VALUES ('356','extract_error','提现相关操作未完成！','1263348788');
INSERT INTO {$tblprefix}mmsgs VALUES ('357','noedit_extract_record','没有可编辑的提现记录！','1263108117');
INSERT INTO {$tblprefix}mmsgs VALUES ('358','extract_modify_finish','提现申请记录修改完成','1263115425');
INSERT INTO {$tblprefix}mmsgs VALUES ('359','invalid_extract_record','无效的提现记录','1263257199');
INSERT INTO {$tblprefix}mmsgs VALUES ('360','select_extract','请选择提现记录','1263264956');
INSERT INTO {$tblprefix}mmsgs VALUES ('361','del_vol','删除分卷确认<br /><br />%s[确认]%s&nbsp;&nbsp;%s[取消]%s','1265873939');
INSERT INTO {$tblprefix}mmsgs VALUES ('362','voleditfin','分卷编辑完成。','1265874749');
INSERT INTO {$tblprefix}mmsgs VALUES ('363','voladdfin','分卷添加完成。','1265874778');
INSERT INTO {$tblprefix}mmsgs VALUES ('364','voldelfin','分卷删除成功。','1265874810');
INSERT INTO {$tblprefix}mmsgs VALUES ('365','memcert_exists','已申请会员认证正在审核中，请耐心等待。。。','1271294968');
INSERT INTO {$tblprefix}mmsgs VALUES ('366','memcert_need_fail','非法的认证申请请求！','1271297452');
INSERT INTO {$tblprefix}mmsgs VALUES ('367','memcert_no_field','会员认证需要的字段不存在，请联系管理员','1271300453');
INSERT INTO {$tblprefix}mmsgs VALUES ('368','memcert_empty_field','认证信息填写不完整！','1271580894');
INSERT INTO {$tblprefix}mmsgs VALUES ('369','memcert_msgcode_err','手机确认码有误','1271581915');
INSERT INTO {$tblprefix}mmsgs VALUES ('370','memcert_upload_ok','认证提交成功','1271662621');
INSERT INTO {$tblprefix}mmsgs VALUES ('371','memcert_upload_bad','认证提交失败','1271662635');
INSERT INTO {$tblprefix}mmsgs VALUES ('372','memcert_email_sent','认证提交成功，系统已经向您的邮箱发送了一封确认邮件。','1271662725');
INSERT INTO {$tblprefix}mmsgs VALUES ('373','memcert_link_ok','邮箱认证成功','1271662758');
INSERT INTO {$tblprefix}mmsgs VALUES ('374','memcert_link_bad','无效的请求','1271662778');
INSERT INTO {$tblprefix}mmsgs VALUES ('375','memcert_link_more','错误次数太多','1271662795');
INSERT INTO {$tblprefix}mmsgs VALUES ('376','memcert_delete_ok','认证申请删除成功','1272027847');
INSERT INTO {$tblprefix}mmsgs VALUES ('377','memcert_delete_bad','认证申请删除失败','1272027864');
INSERT INTO {$tblprefix}mmsgs VALUES ('378','setcoclass','请设置 %s 分类','1272851206');
INSERT INTO {$tblprefix}mmsgs VALUES ('379','foundernomc','创始人请不要使用会员中心！%s','1273175537');

DROP TABLE IF EXISTS {$tblprefix}mmtypes;
CREATE TABLE {$tblprefix}mmtypes (
  mtid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  url varchar(255) NOT NULL,
  `fixed` tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (mtid)
) TYPE=MyISAM AUTO_INCREMENT=13;

INSERT INTO {$tblprefix}mmtypes VALUES ('1','个人资料','','0','99');
INSERT INTO {$tblprefix}mmtypes VALUES ('2','内容管理','','0','2');
INSERT INTO {$tblprefix}mmtypes VALUES ('3','文档交互','','0','3');
INSERT INTO {$tblprefix}mmtypes VALUES ('4','短消息','','0','1');
INSERT INTO {$tblprefix}mmtypes VALUES ('8','财务管理','','0','5');
INSERT INTO {$tblprefix}mmtypes VALUES ('9','空间交互','','0','4');

DROP TABLE IF EXISTS {$tblprefix}mprojects;
CREATE TABLE {$tblprefix}mprojects (
  mpid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(10) NOT NULL,
  cname varchar(50) NOT NULL,
  smchid smallint(6) unsigned NOT NULL default '0',
  tmchid smallint(6) unsigned NOT NULL default '0',
  autocheck tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (mpid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mreplys;
CREATE TABLE {$tblprefix}mreplys (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  fromid mediumint(8) unsigned NOT NULL default '0',
  fromname varchar(15) NOT NULL default '',
  ucid mediumint(8) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  floorid smallint(5) unsigned NOT NULL default '0',
  quoteids varchar(255) NOT NULL default '',
  areply tinyint(1) unsigned NOT NULL default '0',
  aread tinyint(1) unsigned NOT NULL default '0',
  uread tinyint(1) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mreports;
CREATE TABLE {$tblprefix}mreports (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  fromid mediumint(8) unsigned NOT NULL default '0',
  fromname varchar(15) NOT NULL default '',
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mtconfigs;
CREATE TABLE {$tblprefix}mtconfigs (
  mtcid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  issystem tinyint(1) unsigned NOT NULL default '0',
  mchids varchar(255) NOT NULL,
  setting text NOT NULL,
  arctpls text NOT NULL,
  PRIMARY KEY  (mtcid)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}mtconfigs VALUES ('1','默认1','1','','','');

DROP TABLE IF EXISTS {$tblprefix}mtrans;
CREATE TABLE {$tblprefix}mtrans (
  trid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  fromid smallint(6) unsigned NOT NULL default '0',
  toid smallint(6) unsigned NOT NULL default '0',
  remark varchar(255) NOT NULL,
  contentarr text NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  reply varchar(255) NOT NULL,
  PRIMARY KEY  (trid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}mtypes;
CREATE TABLE {$tblprefix}mtypes (
  mtid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  url varchar(255) NOT NULL,
  `fixed` tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  issub tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (mtid)
) TYPE=MyISAM AUTO_INCREMENT=24;

INSERT INTO {$tblprefix}mtypes VALUES ('1','常规内容','?entry=archives&action=index','1','1','0');
INSERT INTO {$tblprefix}mtypes VALUES ('2','常规内容','?entry=archives&action=index','1','1','1');
INSERT INTO {$tblprefix}mtypes VALUES ('15','网站架构','#','0','5','0');
INSERT INTO {$tblprefix}mtypes VALUES ('20','网站架构','','0','3','1');
INSERT INTO {$tblprefix}mtypes VALUES ('16','模版风格','#','0','6','0');
INSERT INTO {$tblprefix}mtypes VALUES ('17','其他管理','#','0','2','0');
INSERT INTO {$tblprefix}mtypes VALUES ('21','模板风格','','0','4','1');
INSERT INTO {$tblprefix}mtypes VALUES ('23','其他管理','','0','2','1');
INSERT INTO {$tblprefix}mtypes VALUES ('18','系统设置','#','0','7','0');
INSERT INTO {$tblprefix}mtypes VALUES ('3','插件管理','','1','1','0');
INSERT INTO {$tblprefix}mtypes VALUES ('4','会员管理','','1','1','0');

DROP TABLE IF EXISTS {$tblprefix}murls;
CREATE TABLE {$tblprefix}murls (
  muid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  remark varchar(80) NOT NULL,
  uclass varchar(15) NOT NULL,
  issys tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL,
  setting text NOT NULL,
  tplname varchar(50) NOT NULL,
  onlyview tinyint(1) unsigned NOT NULL default '0',
  isbk tinyint(1) unsigned NOT NULL default '0',
  mtitle varchar(80) NOT NULL,
  otitle varchar(80) NOT NULL,
  guide text NOT NULL,
  PRIMARY KEY  (muid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}offers;
CREATE TABLE {$tblprefix}offers (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  oprice float unsigned NOT NULL default '0',
  `storage` int(11) NOT NULL default '-1',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  ucid mediumint(8) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  votes1 int(10) unsigned NOT NULL default '0',
  votes2 int(10) unsigned NOT NULL default '0',
  votes3 int(10) unsigned NOT NULL default '0',
  votes4 int(10) unsigned NOT NULL default '0',
  votes5 int(10) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  updatedate int(10) unsigned NOT NULL default '0',
  refreshdate int(10) unsigned NOT NULL default '0',
  enddate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}orders;
CREATE TABLE {$tblprefix}orders (
  oid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  ordersn varchar(30) NOT NULL,
  orderfee float unsigned NOT NULL default '0',
  shipingmode tinyint(4) NOT NULL,
  shipingfee float unsigned NOT NULL default '0',
  paymode tinyint(4) NOT NULL,
  totalfee float unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL,
  tomid int(11) NOT NULL,
  tomname varchar(15) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  payed float unsigned NOT NULL default '0',
  state int(10) NOT NULL default '0',
  updatedate int(10) unsigned NOT NULL default '0',
  remark varchar(255) NOT NULL,
  delstate int(11) NOT NULL default '-1',
  PRIMARY KEY  (oid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}pays;
CREATE TABLE {$tblprefix}pays (
  pid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL,
  ordersn varchar(64) NOT NULL,
  pmode tinyint(1) unsigned NOT NULL default '0',
  poid varchar(15) NOT NULL,
  amount float unsigned NOT NULL default '0',
  handfee float unsigned NOT NULL default '0',
  bank varchar(50) NOT NULL,
  senddate int(10) unsigned NOT NULL default '0',
  receivedate int(10) unsigned NOT NULL default '0',
  transdate int(10) unsigned NOT NULL default '0',
  ip char(15) NOT NULL,
  truename varchar(80) NOT NULL,
  telephone varchar(30) NOT NULL,
  email varchar(100) NOT NULL,
  remark varchar(255) NOT NULL,
  warrant varchar(255) NOT NULL,
  PRIMARY KEY  (pid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}permissions;
CREATE TABLE {$tblprefix}permissions (
  pmid smallint(3) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  aread tinyint(1) unsigned NOT NULL default '0',
  cread tinyint(1) unsigned NOT NULL default '0',
  aadd tinyint(1) unsigned NOT NULL default '0',
  down tinyint(1) NOT NULL default '0',
  fadd tinyint(1) unsigned NOT NULL default '0',
  cuadd tinyint(1) unsigned NOT NULL default '0',
  menu tinyint(1) unsigned NOT NULL default '0',
  field tinyint(1) unsigned NOT NULL default '0',
  tpl tinyint(1) unsigned NOT NULL default '0',
  chk tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  ugids varchar(255) NOT NULL,
  fugids varchar(255) NOT NULL,
  PRIMARY KEY  (pmid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}players;
CREATE TABLE {$tblprefix}players (
  plid tinyint(3) unsigned NOT NULL auto_increment auto_increment,
  cname char(30) NOT NULL,
  ptype char(10) NOT NULL default 'media',
  issystem tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '0',
  vieworder tinyint(3) unsigned NOT NULL default '0',
  exts varchar(50) NOT NULL,
  template mediumtext NOT NULL,
  PRIMARY KEY  (plid)
) TYPE=MyISAM AUTO_INCREMENT=6;

INSERT INTO {$tblprefix}players VALUES ('1','RealPlayer','media','1','1','1','rm,rmvb','<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td height=\"{$height}\" width=\"{$width}\">\r\n<object classid=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" height=\"100%\" id=RP1 name=RP1 width=\"100%\">\r\n  <param name=\"AUTOSTART\" value=\"-1\">\r\n  <param name=\"SHUFFLE\" value=\"0\">\r\n  <param name=\"PREFETCH\" value=\"0\">\r\n  <param name=\"NOLABELS\" value=\"0\">\r\n  <param name=\"CONTROLS\" value=\"Imagewindow\">\r\n  <param name=\"CONSOLE\" value=\"clip1\">\r\n  <param name=\"LOOP\" value=\"0\">\r\n  <param name=\"NUMLOOP\" value=\"0\">\r\n  <param name=\"CENTER\" value=\"0\">\r\n  <param name=\"MAINTAINASPECT\" value=\"1\">\r\n  <param name=\"BACKGROUNDCOLOR\" value=\"#000000\">\r\n</object>\r\n<OBJECT classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA  height=30 id=RP2 name=RP2 width=\"100%\">\r\n<PARAM NAME=\"_ExtentX\" VALUE=\"4657\">\r\n<PARAM NAME=\"_ExtentY\" VALUE=\"794\">\r\n<PARAM NAME=\"AUTOSTART\" VALUE=\"-1\">\r\n<PARAM NAME=\"SRC\" VALUE=\"{$url}\">\r\n<PARAM NAME=\"SHUFFLE\" VALUE=\"0\">\r\n<PARAM NAME=\"PREFETCH\" VALUE=\"0\">\r\n<PARAM NAME=\"NOLABELS\" VALUE=\"-1\">\r\n<PARAM NAME=\"CONTROLS\" VALUE=\"ControlPanel\">\r\n<PARAM NAME=\"CONSOLE\" VALUE=\"clip1\">\r\n<PARAM NAME=\"LOOP\" VALUE=\"0\">\r\n<PARAM NAME=\"NUMLOOP\" VALUE=\"0\">\r\n<PARAM NAME=\"CENTER\" VALUE=\"0\">\r\n<PARAM NAME=\"MAINTAINASPECT\" VALUE=\"1\">\r\n<PARAM NAME=\"BACKGROUNDCOLOR\" VALUE=\"#000000\">\r\n</OBJECT>\r\n<object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height=30 id=RP3 name=RP3 width=\"100%\">\r\n  <param name=\"_ExtentX\" value=\"4657\">\r\n  <param name=\"_ExtentY\" value=\"794\">\r\n  <param name=\"AUTOSTART\" value=\"-1\">\r\n  <param name=\"SHUFFLE\" value=\"0\">\r\n  <param name=\"PREFETCH\" value=\"0\">\r\n  <param name=\"NOLABELS\" value=\"-1\">\r\n  <param name=\"CONTROLS\" value=\"StatusBar\">\r\n  <param name=\"CONSOLE\" value=\"clip1\">\r\n  <param name=\"LOOP\" value=\"0\">\r\n  <param name=\"NUMLOOP\" value=\"0\">\r\n  <param name=\"CENTER\" value=\"0\">\r\n  <param name=\"MAINTAINASPECT\" value=\"1\">\r\n  <param name=\"BACKGROUNDCOLOR\" value=\"#000000\">\r\n</object>\r\n    \r\n    </td>\r\n  </tr>\r\n</table>\r\n');
INSERT INTO {$tblprefix}players VALUES ('2','微软wmPlayer','media','1','1','2','mepg,avi','<object classid=\"clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" id=\"MediaPlayer1\" width=\"{$width}\" height=\"{$height}\">\r\n<param name=\"AudioStream\" value=\"-1\">\r\n<param name=\"AutoSize\" value=\"-1\">\r\n<param name=\"AutoStart\" value=\"-1\">\r\n<param name=\"AnimationAtStart\" value=\"-1\">\r\n<param name=\"AllowScan\" value=\"-1\">\r\n<param name=\"AllowChangeDisplaySize\" value=\"-1\">\r\n<param name=\"AutoRewind\" value=\"0\">\r\n<param name=\"Balance\" value=\"0\">\r\n<param name=\"BaseURL\" value>\r\n<param name=\"BufferingTime\" value=\"15\">\r\n<param name=\"CaptioningID\" value>\r\n<param name=\"ClickToPlay\" value=\"-1\">\r\n<param name=\"CursorType\" value=\"0\">\r\n<param name=\"CurrentPosition\" value=\"0\">\r\n<param name=\"CurrentMarker\" value=\"0\">\r\n<param name=\"DefaultFrame\" value>\r\n<param name=\"DisplayBackColor\" value=\"0\">\r\n<param name=\"DisplayForeColor\" value=\"16777215\">\r\n<param name=\"DisplayMode\" value=\"0\">\r\n<param name=\"DisplaySize\" value=\"0\">\r\n<param name=\"Enabled\" value=\"-1\">\r\n<param name=\"EnableContextMenu\" value=\"-1\">\r\n<param name=\"EnablePositionControls\" value=\"-1\">\r\n<param name=\"EnableFullScreenControls\" value=\"-1\">\r\n<param name=\"EnableTracker\" value=\"-1\">\r\n<param name=\"Filename\" value=\"{$url}\" valuetype=\"ref\">\r\n<param name=\"InvokeURLs\" value=\"-1\">\r\n<param name=\"Language\" value=\"-1\">\r\n<param name=\"Mute\" value=\"0\">\r\n<param name=\"PlayCount\" value=\"1\">\r\n<param name=\"PreviewMode\" value=\"-1\">\r\n<param name=\"Rate\" value=\"1\">\r\n<param name=\"SAMIStyle\" value=\"1\">\r\n<param name=\"SAMILang\" value>\r\n<param name=\"SAMIFilename\" value>\r\n<param name=\"SelectionStart\" value=\"-1\">\r\n<param name=\"SelectionEnd\" value=\"-1\">\r\n<param name=\"SendOpenStateChangeEvents\" value=\"-1\">\r\n<param name=\"SendWarningEvents\" value=\"-1\">\r\n<param name=\"SendErrorEvents\" value=\"-1\">\r\n<param name=\"SendKeyboardEvents\" value=\"0\">\r\n<param name=\"SendMouseClickEvents\" value=\"0\">\r\n<param name=\"SendMousemovieeEvents\" value=\"0\">\r\n<param name=\"SendPlayStateChangeEvents\" value=\"-1\">\r\n<param name=\"ShowCaptioning\" value=\"0\">\r\n<param name=\"ShowControls\" value=\"-1\">\r\n<param name=\"ShowAudioControls\" value=\"-1\">\r\n<param name=\"ShowDisplay\" value=\"0\">\r\n<param name=\"ShowGotoBar\" value=\"0\">\r\n<param name=\"ShowPositionControls\" value=\"-1\">\r\n<param name=\"ShowStatusBar\" value=\"-1\">\r\n<param name=\"ShowTracker\" value=\"-1\">\r\n<param name=\"TransparentAtStart\" value=\"-1\">\r\n<param name=\"VideoBorderWidth\" value=\"0\">\r\n<param name=\"VideoBorderColor\" value=\"0\">\r\n<param name=\"VideoBorder3D\" value=\"0\">\r\n<param name=\"Volume\" value=\"0\">\r\n<param name=\"WindowlessVideo\" value=\"0\">\r\n</object>');
INSERT INTO {$tblprefix}players VALUES ('4','Flash播放器','flash','1','1','3','swf','<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"{$width}\" height=\"{$height}\">\r\n<param name=\"movie\" value=\"{$url}\">\r\n<param name=\"quality\" value=\"high\">\r\n<param name=\"wmode\" value=\"transparent\">\r\n<embed wmode=\"transparent\" src=\"{$url}\" quality=\"high\" type=\"application/x-shockwave-flash\" \r\n   pluginspage=\"http://www.macromedia.com/go/getflashplayer\" width=\"{$width}\" height=\"{$height}\"></embed>\r\n</object>');
INSERT INTO {$tblprefix}players VALUES ('5','Flv播放器','flash','1','1','4','flv','<object type=\"application/x-shockwave-flash\" data=\"{$tplurl}/images/flvplayer.swf\" width=\"{$width}\" height=\"{height}\">\r\n  <param name=\"movie\" value=\"{$tplurl}/images/flvplayer.swf?autostart=true&file={$url}\">\r\n</object>\r\n');

DROP TABLE IF EXISTS {$tblprefix}pms;
CREATE TABLE {$tblprefix}pms (
  pmid int(10) unsigned NOT NULL auto_increment auto_increment,
  fromuser varchar(30) NOT NULL,
  fromid mediumint(8) unsigned NOT NULL default '0',
  toid mediumint(8) unsigned NOT NULL default '0',
  viewed tinyint(1) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL,
  content mediumtext NOT NULL,
  pmdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (pmid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}purchases;
CREATE TABLE {$tblprefix}purchases (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  tocid int(11) NOT NULL,
  price float unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  tomid int(11) NOT NULL,
  tomname varchar(15) NOT NULL,
  nums int(10) unsigned NOT NULL default '0',
  oid mediumint(8) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}replys;
CREATE TABLE {$tblprefix}replys (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  ucid smallint(6) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  floorid smallint(5) unsigned NOT NULL default '0',
  quoteids varchar(255) NOT NULL default '',
  votes1 int(10) unsigned NOT NULL default '0',
  votes2 int(10) unsigned NOT NULL default '0',
  votes3 int(10) unsigned NOT NULL default '0',
  votes4 int(10) unsigned NOT NULL default '0',
  votes5 int(10) unsigned NOT NULL default '0',
  createdate int(10) unsigned NOT NULL default '0',
  updatedate int(10) unsigned NOT NULL default '0',
  refreshdate int(10) unsigned NOT NULL default '0',
  areply tinyint(1) unsigned NOT NULL default '0',
  aread tinyint(1) unsigned NOT NULL default '0',
  uread tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}reports;
CREATE TABLE {$tblprefix}reports (
  cid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  cuid smallint(6) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(15) NOT NULL default '',
  createdate int(10) unsigned NOT NULL default '0',
  updatedate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}repugrades;
CREATE TABLE {$tblprefix}repugrades (
  rgid tinyint(3) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  rgbase int(10) NOT NULL default '0',
  thumb varchar(255) NOT NULL,
  PRIMARY KEY  (rgid)
) TYPE=MyISAM AUTO_INCREMENT=21;

INSERT INTO {$tblprefix}repugrades VALUES ('1','一心','0','images/repu/ico_1.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('2','两心','100','images/repu/ico_2.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('3','三心','200','images/repu/ico_3.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('4','四心','300','images/repu/ico_4.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('5','五心','400','images/repu/ico_5.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('6','一钻','500','images/repu/ico_6.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('7','两钻','600','images/repu/ico_7.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('8','三钻','700','images/repu/ico_8.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('9','四钻','800','images/repu/ico_9.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('10','五钻','900','images/repu/ico_10.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('11','一冠','1000','images/repu/ico_11.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('12','两冠','0','images/repu/ico_12.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('13','三冠','0','images/repu/ico_13.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('14','四冠','0','images/repu/ico_14.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('15','五冠','0','images/repu/ico_15.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('16','皇冠','0','images/repu/ico_16.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('17','两皇冠','0','images/repu/ico_17.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('18','三皇冠','0','images/repu/ico_18.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('19','四皇冠','0','images/repu/ico_19.gif');
INSERT INTO {$tblprefix}repugrades VALUES ('20','五皇冠','0','images/repu/ico_20.gif');

DROP TABLE IF EXISTS {$tblprefix}repus;
CREATE TABLE {$tblprefix}repus (
  rid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  repus int(10) NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  reason varchar(255) NOT NULL,
  PRIMARY KEY  (rid),
  KEY mid (mid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}rprojects;
CREATE TABLE {$tblprefix}rprojects (
  rpid smallint(3) NOT NULL auto_increment auto_increment,
  cname varchar(50) NOT NULL,
  rmfiles mediumtext NOT NULL,
  timeout int(10) unsigned NOT NULL default '0',
  excludes varchar(255) NOT NULL,
  issystem tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (rpid)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}rprojects VALUES ('1','远程图片下载(系统)','a:4:{s:3:\"jpg\";a:5:{s:7:\"maxsize\";i:300;s:8:\"minisize\";i:1;s:4:\"mime\";s:10:\"image/jpeg\";s:5:\"ftype\";s:5:\"image\";s:7:\"extname\";s:3:\"jpg\";}s:3:\"gif\";a:5:{s:7:\"maxsize\";i:300;s:8:\"minisize\";i:1;s:4:\"mime\";s:9:\"image/gif\";s:5:\"ftype\";s:5:\"image\";s:7:\"extname\";s:3:\"gif\";}s:4:\"jpeg\";a:5:{s:7:\"maxsize\";i:300;s:8:\"minisize\";i:1;s:4:\"mime\";s:10:\"image/jpeg\";s:5:\"ftype\";s:5:\"image\";s:7:\"extname\";s:4:\"jpeg\";}s:3:\"png\";a:5:{s:7:\"maxsize\";i:300;s:8:\"minisize\";i:1;s:4:\"mime\";s:9:\"image/png\";s:5:\"ftype\";s:5:\"image\";s:7:\"extname\";s:3:\"png\";}}','10','','1');

DROP TABLE IF EXISTS {$tblprefix}sitemaps;
CREATE TABLE {$tblprefix}sitemaps (
  ename varchar(20) NOT NULL,
  cname varchar(50) NOT NULL,
  d_url varchar(50) NOT NULL,
  xml_url varchar(50) NOT NULL,
  available tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(3) unsigned NOT NULL default '999',
  setting mediumtext NOT NULL,
  UNIQUE KEY ename (ename)
) TYPE=MyISAM;

INSERT INTO {$tblprefix}sitemaps VALUES ('baidu','Baidu新闻协议','baidu.php','baidu.xml','1','2','');
INSERT INTO {$tblprefix}sitemaps VALUES ('google','Google Sitemap','google.php','google.xml','1','1','');

DROP TABLE IF EXISTS {$tblprefix}splangs;
CREATE TABLE {$tblprefix}splangs (
  slid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  cname varchar(100) NOT NULL,
  content text NOT NULL,
  vieworder smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (slid)
) TYPE=MyISAM AUTO_INCREMENT=5;

INSERT INTO {$tblprefix}splangs VALUES ('1','member_active_subject','email','会员激活邮件标题','会员激活电子邮件','0');
INSERT INTO {$tblprefix}splangs VALUES ('2','member_active_content','email','会员激活邮件内容','{$mname}，您好！\r\n这封信是由 {$cmsname} 发送的。\r\n\r\n您收到这封邮件，是因为在我们网站的新用户注册，或用户修改 Email 使用\r\n了您的地址。如果您并没有访问过我们的网站，或没有进行上述操作，请忽\r\n略这封邮件。您不需要退订或进行其他进一步的操作。\r\n\r\n----------------------------------------------------------------------\r\n帐号激活说明\r\n----------------------------------------------------------------------\r\n\r\n您是我们网站的新用户，或在修改您的注册 Email 时使用了本地址，我们需\r\n要对您的地址有效性进行验证以避免垃圾邮件或地址被滥用。\r\n\r\n您只需点击下面的链接即可激活您的帐号：\r\n\r\n{$url}\r\n\r\n(如果上面不是链接形式，请将地址手工粘贴到浏览器地址栏再访问)\r\n\r\n感谢您的访问，祝您使用愉快！\r\n\r\n\r\n\r\n此致\r\n\r\n{$cmsname}\r\n{$cms_abs}','0');
INSERT INTO {$tblprefix}splangs VALUES ('3','member_getpwd_subject','email','会员找密码邮件标题','会员找密码电子邮件','0');
INSERT INTO {$tblprefix}splangs VALUES ('4','member_getpwd_content','email','会员找密码邮件内容','{$mname}，您好！\r\n这封信是由 {$cmsname} 发送的。\r\n\r\n您收到这封邮件，是因为在我们的网站上这个邮箱地址被登记为用户邮箱，\r\n且该用户请求使用 Email 密码重置功能所致。\r\n\r\n----------------------------------------------------------------------\r\n重要！\r\n----------------------------------------------------------------------\r\n\r\n如果您没有提交密码重置的请求或不是我们网站的注册用户，请立即忽略\r\n并删除这封邮件。只在您确认需要重置密码的情况下，才继续阅读下面的\r\n内容。\r\n\r\n----------------------------------------------------------------------\r\n密码重置说明\r\n----------------------------------------------------------------------\r\n\r\n您只需在提交请求后的三天之内，通过点击下面的链接重置您的密码：\r\n\r\n{$url}\r\n\r\n(如果上面不是链接形式，请将地址手工粘贴到浏览器地址栏再访问)\r\n\r\n上面的页面打开后，输入新的密码后提交，之后您即可使用新的密码登录\r\n论坛了。您可以在用户控制面板中随时修改您的密码。\r\n\r\n本请求提交者的 IP 为 {$onlineip}\r\n\r\n\r\n此致\r\n\r\n{$cmsname}\r\n{$cms_abs}','0');

DROP TABLE IF EXISTS {$tblprefix}sptpls;
CREATE TABLE {$tblprefix}sptpls (
  ename varchar(30) NOT NULL,
  cname varchar(80) NOT NULL,
  link varchar(80) NOT NULL,
  vieworder smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (ename)
) TYPE=MyISAM;

INSERT INTO {$tblprefix}sptpls VALUES ('login','会员登录页面','{$cms_abs}login.php','2');
INSERT INTO {$tblprefix}sptpls VALUES ('message','系统提示信息模板','提示信息(系统调用)','3');
INSERT INTO {$tblprefix}sptpls VALUES ('jslogin','会员(未)登录js调用模板','{$cms_abs}login.php?mode=js','4');
INSERT INTO {$tblprefix}sptpls VALUES ('jsloginok','会员(已)登录js调用模板','{$cms_abs}login.php?mode=js','5');
INSERT INTO {$tblprefix}sptpls VALUES ('down','附件下载附加页','通过模板标识定义','6');
INSERT INTO {$tblprefix}sptpls VALUES ('flash','FLASH播放附加页','通过模板标识定义','7');
INSERT INTO {$tblprefix}sptpls VALUES ('media','视频播放附加页','通过模板标识定义','8');
INSERT INTO {$tblprefix}sptpls VALUES ('vote','投票查看页面','{$cms_abs}vote.php?action=view&vid={$vid}','9');
INSERT INTO {$tblprefix}sptpls VALUES ('search','搜索页面','{$cms_abs}search.php','10');
INSERT INTO {$tblprefix}sptpls VALUES ('msearch','搜索会员页面','{$cms_abs}msearch.php','11');

DROP TABLE IF EXISTS {$tblprefix}subscribes;
CREATE TABLE {$tblprefix}subscribes (
  id mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  aid mediumint(8) unsigned NOT NULL default '0',
  isatm tinyint(1) unsigned NOT NULL default '0',
  cridstr varchar(255) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY mid (mid,aid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}subsites;
CREATE TABLE {$tblprefix}subsites (
  sid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  sitename varchar(80) NOT NULL,
  dirname varchar(30) NOT NULL,
  templatedir varchar(30) NOT NULL,
  closed tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  channels text NOT NULL,
  commus text NOT NULL,
  cmslogo varchar(100) NOT NULL,
  cmstitle varchar(100) NOT NULL,
  cmskeyword varchar(100) NOT NULL,
  cmsdescription text NOT NULL,
  hometpl varchar(30) NOT NULL,
  w_index_tpl varchar(30) NOT NULL default '',
  css_dir varchar(30) NOT NULL,
  js_dir varchar(30) NOT NULL,
  ineedstatic int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (sid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}uclasses;
CREATE TABLE {$tblprefix}uclasses (
  ucid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mcaid smallint(5) unsigned NOT NULL default '0',
  cuid smallint(6) NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  mid mediumint(8) unsigned NOT NULL default '0',
  vieworder smallint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (ucid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}ucoclass;
CREATE TABLE {$tblprefix}ucoclass (
  uccid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title char(30) NOT NULL,
  ucoid smallint(6) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (uccid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}ucotypes;
CREATE TABLE {$tblprefix}ucotypes (
  ucoid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname char(30) NOT NULL,
  cclass varchar(15) NOT NULL,
  umode tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  notblank tinyint(1) unsigned NOT NULL default '0',
  vmode tinyint(1) unsigned NOT NULL default '0',
  emode tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (ucoid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}uprojects;
CREATE TABLE {$tblprefix}uprojects (
  upid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename varchar(10) NOT NULL,
  cname varchar(50) NOT NULL,
  gtid smallint(6) unsigned NOT NULL default '0',
  sugid smallint(6) unsigned NOT NULL default '0',
  tugid smallint(6) unsigned NOT NULL default '0',
  autocheck tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (upid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}userfiles;
CREATE TABLE {$tblprefix}userfiles (
  ufid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  filename varchar(50) NOT NULL,
  url varchar(80) NOT NULL,
  `type` varchar(10) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  size int(10) unsigned NOT NULL default '0',
  thumbed tinyint(1) unsigned NOT NULL default '0',
  aid mediumint(8) unsigned NOT NULL default '0',
  tid tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (ufid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}usergroups;
CREATE TABLE {$tblprefix}usergroups (
  ugid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  cname varchar(30) NOT NULL,
  gtid smallint(3) unsigned NOT NULL default '0',
  mchids varchar(255) NOT NULL,
  currency int(10) unsigned NOT NULL default '0',
  limitday int(6) unsigned NOT NULL default '0',
  prior int(3) unsigned NOT NULL default '999',
  amcids text NOT NULL,
  discount float unsigned NOT NULL default '0',
  issuepermit tinyint(1) unsigned NOT NULL default '1',
  commentpermit tinyint(1) unsigned NOT NULL default '1',
  purchasepermit tinyint(1) NOT NULL default '0',
  answerpermit tinyint(1) NOT NULL default '0',
  uploadpermit tinyint(1) unsigned NOT NULL default '1',
  searchpermit tinyint(3) unsigned NOT NULL default '1',
  downloadpermit tinyint(1) unsigned NOT NULL default '1',
  freeupdatecheck tinyint(1) unsigned NOT NULL default '0',
  freeupdatecopy tinyint(1) unsigned NOT NULL default '0',
  denyarc tinyint(1) unsigned NOT NULL default '0',
  denyatm tinyint(1) unsigned NOT NULL default '0',
  maxuptotal int(10) unsigned NOT NULL default '10000000',
  maxdowntotal int(10) unsigned NOT NULL default '10000000',
  maxpms smallint(6) unsigned NOT NULL default '100',
  arcallows int(10) unsigned NOT NULL default '0',
  cuallows int(10) unsigned NOT NULL default '0',
  ex_discount tinyint(4) NOT NULL default '0',
  autoinit tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (ugid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}userurls;
CREATE TABLE {$tblprefix}userurls (
  uid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  url varchar(80) NOT NULL,
  utid smallint(6) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  pmid smallint(6) unsigned NOT NULL default '0',
  sids varchar(255) NOT NULL,
  newwin tinyint(1) unsigned NOT NULL default '0',
  actsid tinyint(1) unsigned NOT NULL default '0',
  onclick varchar(255) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}usualurls;
CREATE TABLE {$tblprefix}usualurls (
  uid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL default '',
  url varchar(80) NOT NULL default '',
  logo varchar(255) NOT NULL default '',
  ismc tinyint(1) unsigned NOT NULL default '0',
  available tinyint(1) unsigned NOT NULL default '1',
  vieworder smallint(6) unsigned NOT NULL default '0',
  pmid smallint(6) unsigned NOT NULL default '0',
  sids varchar(255) NOT NULL default '',
  actsid tinyint(1) unsigned NOT NULL default '0',
  newwin tinyint(1) unsigned NOT NULL default '0',
  onclick varchar(255) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}utrans;
CREATE TABLE {$tblprefix}utrans (
  trid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  mid mediumint(8) unsigned NOT NULL default '0',
  mname char(15) NOT NULL,
  gtid smallint(6) unsigned NOT NULL default '0',
  fromid smallint(6) unsigned NOT NULL default '0',
  toid smallint(6) unsigned NOT NULL default '0',
  remark varchar(255) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  reply varchar(255) NOT NULL,
  PRIMARY KEY  (trid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}utypes;
CREATE TABLE {$tblprefix}utypes (
  utid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  pid smallint(6) unsigned NOT NULL default '0',
  ismc tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  pmid smallint(6) unsigned NOT NULL default '0',
  sids varchar(255) NOT NULL,
  PRIMARY KEY  (utid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}vcatalogs;
CREATE TABLE {$tblprefix}vcatalogs (
  caid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  title char(50) NOT NULL,
  vieworder tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (caid),
  KEY parentid (vieworder)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}vols;
CREATE TABLE {$tblprefix}vols (
  vid mediumint(8) unsigned NOT NULL auto_increment auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  volid smallint(6) unsigned NOT NULL default '1',
  vtitle varchar(80) NOT NULL,
  PRIMARY KEY  (vid),
  KEY aid (aid),
  KEY volid (volid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}voptions;
CREATE TABLE {$tblprefix}voptions (
  vopid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  vid smallint(5) unsigned NOT NULL default '0',
  title varchar(80) NOT NULL,
  votenum int(10) unsigned NOT NULL default '0',
  vieworder tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (vopid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}votes;
CREATE TABLE {$tblprefix}votes (
  vid smallint(5) unsigned NOT NULL auto_increment auto_increment,
  caid smallint(6) unsigned NOT NULL default '0',
  `subject` varchar(80) NOT NULL,
  content mediumtext NOT NULL,
  totalnum mediumint(8) unsigned NOT NULL default '0',
  ismulti tinyint(1) unsigned NOT NULL default '0',
  timelimit smallint(5) unsigned NOT NULL default '0',
  norepeat tinyint(1) unsigned NOT NULL default '0',
  enddate int(10) unsigned NOT NULL default '0',
  onlyuser tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(5) unsigned NOT NULL default '0',
  checked tinyint(1) unsigned NOT NULL default '0',
  mid mediumint(8) unsigned NOT NULL default '0',
  mname varchar(30) NOT NULL,
  createdate int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (vid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS {$tblprefix}wordlinks;
CREATE TABLE {$tblprefix}wordlinks (
  wlid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  sword varchar(30) NOT NULL,
  url varchar(120) NOT NULL,
  available tinyint(1) unsigned NOT NULL default '1',
  pcs int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (wlid)
) TYPE=MyISAM;


