/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

UPDATE @@DB_MAIN_PREFIX@@posts SET post_content = REPLACE(post_content,(SELECT `option_value` FROM `@@DB_MAIN_PREFIX@@options` WHERE `option_name`='siteurl'),'@@ROOT_URL@@');
UPDATE @@DB_MAIN_PREFIX@@posts SET guid = REPLACE(guid,(SELECT `option_value` FROM `@@DB_MAIN_PREFIX@@options` WHERE `option_name`='siteurl'),'@@ROOT_URL@@');

UPDATE `@@DB_MAIN_PREFIX@@options` SET `option_value`='@@ROOT_URL@@' WHERE  `option_name`='siteurl';
UPDATE `@@DB_MAIN_PREFIX@@options` SET `option_value`='@@TITLE@@' WHERE  `option_name`='blogname';
UPDATE `@@DB_MAIN_PREFIX@@options` SET `option_value`='@@ADMIN_EMAIL@@' WHERE  `option_name`='admin_email';
UPDATE `@@DB_MAIN_PREFIX@@options` SET `option_value`='@@ROOT_URL@@' WHERE  `option_name`='home';
UPDATE `@@DB_MAIN_PREFIX@@options` SET `option_value`='@@LOCALE@@' WHERE  `option_name`='WPLANG';
UPDATE `@@DB_MAIN_PREFIX@@users` SET `user_login`='@@ADMIN_NAME@@', `user_nicename`='@@ADMIN_NAME@@', `display_name`='@@ADMIN_NAME@@', `user_pass`='@@ADMIN_PASSWORD@@', `user_email`='@@ADMIN_EMAIL@@' WHERE `ID`=1;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

