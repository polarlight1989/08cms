DROP TABLE IF EXISTS {$tblprefix}repugrades;
CREATE TABLE {$tblprefix}repugrades (
  rgid tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  cname varchar(30) NOT NULL,
  rgbase int(10) NOT NULL DEFAULT '0',
  thumb varchar(255) NOT NULL,
  PRIMARY KEY (rgid)
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

