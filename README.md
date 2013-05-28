SanAuthWithDbSaveHandler
========================

ZF2 Module that utilize AuthenticationService with Db Save Handler.

Installation
------------
import following sql to your db.

    -- table to save session data....
    CREATE TABLE IF NOT EXISTS `session` (
      `id` char(32) NOT NULL DEFAULT '',
      `name` char(32) NOT NULL DEFAULT '',
      `modified` int(11) DEFAULT NULL,
      `lifetime` int(11) DEFAULT NULL,
      `data` text,
      PRIMARY KEY (`id`,`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
     
    -- users table
    CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(100) NOT NULL,
      `password` varchar(100) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
     
    -- users data with password = md5('admin')
    INSERT INTO `users` (`id`, `username`, `password`) VALUES
    (1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

Login access : http://zf2app/auth with

    username : admin
    password : admin