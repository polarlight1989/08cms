DROP TABLE IF EXISTS {$tblprefix}faces;
CREATE TABLE {$tblprefix}faces (
  id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  ftid smallint(6) unsigned NOT NULL DEFAULT '0',
  ename varchar(30) NOT NULL,
  url varchar(30) NOT NULL,
  vieworder tinyint(3) unsigned NOT NULL,
  available tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
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

