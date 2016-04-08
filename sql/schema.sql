-- phpMyAdmin SQL Dump
-- version 
-- http://www.phpmyadmin.net
--
-- Хост: motodonor.mysql
-- Время создания: Апр 08 2016 г., 10:47
-- Версия сервера: 5.6.25-73.1
-- Версия PHP: 5.6.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `motodonor_mobile`
--

-- --------------------------------------------------------

--
-- Структура таблицы `acc_statuses`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `acc_statuses`;
CREATE TABLE IF NOT EXISTS `acc_statuses` (
  `id` int(10) unsigned NOT NULL,
  `status` varchar(40) NOT NULL,
  `description` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `acc_types`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `acc_types`;
CREATE TABLE IF NOT EXISTS `acc_types` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  `description` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `attributes`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `entity` bigint(20) unsigned NOT NULL,
  `attribute` bigint(20) unsigned NOT NULL,
  `value` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--
-- Создание: Дек 23 2015 г., 21:59
-- Последнее обновление: Дек 23 2015 г., 21:59
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `key` varchar(20) NOT NULL,
  `update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `devices`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id_user` int(11) NOT NULL,
  `imei` varchar(255) NOT NULL,
  `gcm` varchar(255) NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `devices_ios`
--
-- Создание: Апр 01 2016 г., 08:53
--

DROP TABLE IF EXISTS `devices_ios`;
CREATE TABLE IF NOT EXISTS `devices_ios` (
  `id_user` int(11) NOT NULL,
  `key` varchar(128) NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `entities`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `entities`;
CREATE TABLE IF NOT EXISTS `entities` (
  `id` bigint(20) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `starttime` timestamp NULL DEFAULT NULL COMMENT 'начало события',
  `endtime` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `duration` int(10) unsigned NOT NULL DEFAULT '24' COMMENT 'продолжительность в часах',
  `owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `type` varchar(255) NOT NULL,
  `lat` decimal(11,7) DEFAULT '0.0000000' COMMENT 'широта',
  `lon` decimal(11,7) DEFAULT '0.0000000' COMMENT 'долгота',
  `accuracy` int(10) unsigned NOT NULL DEFAULT '0',
  `address` text,
  `attr` mediumtext,
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `is_test` tinyint(1) NOT NULL DEFAULT '0',
  `acc_type` varchar(40) NOT NULL,
  `medicine` varchar(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5543 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `extreq`
--
-- Создание: Дек 23 2015 г., 21:59
-- Последнее обновление: Дек 23 2015 г., 21:59
--

DROP TABLE IF EXISTS `extreq`;
CREATE TABLE IF NOT EXISTS `extreq` (
  `id` mediumint(8) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `function` mediumtext CHARACTER SET utf8,
  `arguments` mediumtext CHARACTER SET utf8,
  `response` mediumtext CHARACTER SET utf8
) ENGINE=MyISAM AUTO_INCREMENT=10470 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `id` bigint(20) unsigned NOT NULL,
  `id_ent` bigint(20) unsigned NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `action` varchar(255) NOT NULL,
  `timest` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `params` varchar(10000) NOT NULL DEFAULT '{}'
) ENGINE=InnoDB AUTO_INCREMENT=133993 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `med_types`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `med_types`;
CREATE TABLE IF NOT EXISTS `med_types` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  `description` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) unsigned NOT NULL,
  `id_ent` bigint(20) unsigned NOT NULL,
  `id_user` bigint(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `text` mediumtext NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB AUTO_INCREMENT=3926 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `onway`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `onway`;
CREATE TABLE IF NOT EXISTS `onway` (
  `id` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `status` varchar(100) NOT NULL,
  `timest` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--
-- Создание: Дек 23 2015 г., 22:00
-- Последнее обновление: Дек 23 2015 г., 22:00
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(10) unsigned NOT NULL,
  `region` varchar(120) NOT NULL,
  `lon` decimal(11,7) NOT NULL,
  `lat` decimal(11,7) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `static`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `static`;
CREATE TABLE IF NOT EXISTS `static` (
  `application` varchar(255) NOT NULL,
  `attribute` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--
-- Создание: Мар 31 2016 г., 10:31
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL,
  `login` varchar(255) NOT NULL,
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` timestamp NULL DEFAULT NULL,
  `lastgetlist` timestamp NULL DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `imei` varchar(255) DEFAULT NULL,
  `attr` varchar(10000) DEFAULT '{}',
  `role` varchar(255) NOT NULL DEFAULT 'standart',
  `gcm` varchar(500) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=885 DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `acc_statuses`
--
ALTER TABLE `acc_statuses`
  ADD PRIMARY KEY (`status`);

--
-- Индексы таблицы `acc_types`
--
ALTER TABLE `acc_types`
  ADD PRIMARY KEY (`type`);

--
-- Индексы таблицы `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`attribute`), ADD KEY `entity` (`entity`);

--
-- Индексы таблицы `devices`
--
ALTER TABLE `devices`
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `devices_ios`
--
ALTER TABLE `devices_ios`
  ADD PRIMARY KEY (`id_user`,`key`);

--
-- Индексы таблицы `entities`
--
ALTER TABLE `entities`
  ADD PRIMARY KEY (`id`), ADD KEY `entities_type_IDX` (`type`), ADD KEY `entities_owner_IDX` (`owner`), ADD KEY `entities_created_IDX` (`created`), ADD KEY `acc_type` (`acc_type`), ADD KEY `medicine` (`medicine`), ADD KEY `status_2` (`status`);

--
-- Индексы таблицы `extreq`
--
ALTER TABLE `extreq`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`), ADD KEY `id_ent` (`id_ent`), ADD KEY `id_user` (`id_user`), ADD KEY `action` (`action`);

--
-- Индексы таблицы `med_types`
--
ALTER TABLE `med_types`
  ADD PRIMARY KEY (`type`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`), ADD KEY `status` (`status`), ADD KEY `id_ent` (`id_ent`), ADD KEY `id_user` (`id_user`), ADD KEY `modified` (`modified`), ADD KEY `id_ent_2` (`id_ent`,`modified`);

--
-- Индексы таблицы `onway`
--
ALTER TABLE `onway`
  ADD PRIMARY KEY (`id`,`id_user`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD UNIQUE KEY `role` (`role`);

--
-- Индексы таблицы `static`
--
ALTER TABLE `static`
  ADD PRIMARY KEY (`attribute`);

--
-- Индексы таблицы `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`type`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `login` (`login`), ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attributes`
--
ALTER TABLE `attributes`
  MODIFY `attribute` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `entities`
--
ALTER TABLE `entities`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5543;
--
-- AUTO_INCREMENT для таблицы `extreq`
--
ALTER TABLE `extreq`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10470;
--
-- AUTO_INCREMENT для таблицы `history`
--
ALTER TABLE `history`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=133993;
--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3926;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=885;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attributes`
--
ALTER TABLE `attributes`
ADD CONSTRAINT `attributes_ibfk_2` FOREIGN KEY (`entity`) REFERENCES `entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `entities`
--
ALTER TABLE `entities`
ADD CONSTRAINT `entities_ibfk_1` FOREIGN KEY (`acc_type`) REFERENCES `acc_types` (`type`),
ADD CONSTRAINT `entities_ibfk_3` FOREIGN KEY (`medicine`) REFERENCES `med_types` (`type`),
ADD CONSTRAINT `entities_ibfk_4` FOREIGN KEY (`status`) REFERENCES `acc_statuses` (`status`),
ADD CONSTRAINT `entities_owner_FK` FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `history`
--
ALTER TABLE `history`
ADD CONSTRAINT `history_ibfk_3` FOREIGN KEY (`id_ent`) REFERENCES `entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `history_ibfk_4` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`role`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
