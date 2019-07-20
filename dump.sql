CREATE TABLE `light_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(30) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `light_config` (`id`, `title`, `value`) VALUES
(1, 'name', 'Light Framework'),
(2, 'description', 'Light Framework has been developed by Paul Sinnah. He is intended for small projects !'),
(3, 'keywords', 'light,light framework,framework php');