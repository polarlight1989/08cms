DROP TABLE IF EXISTS {$tblprefix}facetypes;
CREATE TABLE {$tblprefix}facetypes (
  ftid smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  cname varchar(30) NOT NULL,
  facedir varchar(80) NOT NULL,
  vieworder tinyint(3) unsigned NOT NULL DEFAULT '0',
  available tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (ftid)
) TYPE=MyISAM AUTO_INCREMENT=2;

INSERT INTO {$tblprefix}facetypes VALUES ('1','д╛хо','default','0','1');

