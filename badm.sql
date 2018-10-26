-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 26 2018 г., 13:36
-- Версия сервера: 10.1.33-MariaDB
-- Версия PHP: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `badm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `task`
--

INSERT INTO `task` (`id`, `title`, `description`, `status`, `created_at`, `author_id`) VALUES
(1, 'Первая задача', 'Большое описание первой задачи', 1, '2018-10-25 18:54:40', '1'),
(2, 'Вторая задача', 'Большое описание второй задачи', 1, '2018-10-25 18:54:48', '1'),
(3, 'Третья задача', 'Большое описание Третьей задачи', 0, '2018-10-25 18:54:53', '5'),
(4, 'task 2', 'bla bla bla', 0, '2018-10-25 20:53:45', '2'),
(5, 'Отправить код', 'Взять и запулить', 0, '2018-10-26 13:39:24', '2');

-- --------------------------------------------------------

--
-- Структура таблицы `task_coments`
--

CREATE TABLE `task_coments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `task_coments`
--

INSERT INTO `task_coments` (`id`, `task_id`, `description`, `created_at`, `status`, `author_id`) VALUES
(1, 1, 'one comment', '2018-10-25 20:22:19', 1, 1),
(2, 1, 'two comment', '2018-10-25 20:22:19', 1, 1),
(3, 1, 'three comment', '2018-10-25 20:22:19', 1, 1),
(4, 4, 'bla bla bla', '2018-10-25 22:31:12', 2, 2),
(5, 4, 'close task', '2018-10-25 23:07:33', 0, 2),
(6, 3, 'close task', '2018-10-25 23:10:22', 0, 2),
(7, 5, 'кофе допью и сделаю', '2018-10-26 13:42:25', 1, 2),
(8, 5, 'готово', '2018-10-26 13:43:08', 0, 2),
(9, 1, 'готово', '2018-10-26 14:25:42', 0, 2),
(11, 1, 'решили возобновить задачу', '2018-10-26 14:27:17', 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `expire` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `token`
--

INSERT INTO `token` (`id`, `token`, `user_id`, `expire`) VALUES
(1, 'h123', 2, '2018-10-23 18:48:06'),
(2, '9835e897621dc48f33ee042dbd297166', 2, '2018-10-24 01:43:18'),
(3, '7939e8e8843da6ad6c47fa4c97b314e7', 2, '2018-10-24 21:25:09'),
(4, '681cd4d902f7bc940a1dd322a00ebaa2', 2, '2018-10-26 07:03:47'),
(5, '9f6a7873fe0a288a738153988bff5a72', 2, '2018-10-26 20:36:25');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `phone`, `roles`, `access`) VALUES
(1, 'stas', 'stas@gmail.com', '$2y$10$AnOYYXRIreSV7x5KiFIKIeItqAVOMoL9MctKTstG1Zi8B6TSQF0vW', '2018-10-22 10:26:04', '0984603109', 'administrator', 1),
(2, 'stas', 'stas1231@gmail.com', '$2y$10$dxopUmjbQ4gOK0VTLJ.5beAzDEPrpMpv8cFxnz7bE5u8DkBbFs1Ym', '2018-10-26 14:34:14', '0631303534', 'administrator', 1),
(5, 'alloon123', '12345@gmail.com', '$2y$10$M0wHkhZOF6u8Yfuf3RW3ruAoxmd42Yevl3a5owytpBje40gu53mIC', '2018-10-25 11:17:19', '123123123', 'user', 1),
(8, 'test user', 'test1@gmail.com', '$2y$10$Zifl3IWR5064wuL/0g7lruja9IAap7Hd69NMpY7E2AAum/AEzgCNm', '2018-10-26 13:21:18', '098124123', 'administrator', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_coments`
--
ALTER TABLE `task_coments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `task_coments`
--
ALTER TABLE `task_coments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
