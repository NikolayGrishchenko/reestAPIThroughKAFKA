-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: mysql:3306
-- Время создания: Ноя 12 2025 г., 11:11
-- Версия сервера: 8.1.0
-- Версия PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `books`
--

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `authors` varchar(80) NOT NULL,
  `description` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `name`, `authors`, `description`) VALUES
(2, 'Сто ле тодиночества', 'Габриэль Гарсиа Маркс', 'Рассказывается про несколько поколений одной семьи и то с чем им пришлось столкнуться и о разных судьбах человеческих'),
(3, 'Think and grow rich', 'Напалеон Хилл', 'Книга рассказывает как твои мысли влияют на твою жизнь'),
(4, 'Исскуство войны', 'Сунь Дзы', 'Книга расказывает о военной стратегии великого китайского полководца Сунь Дзы'),
(5, 'Богатый папа, бедный папа', 'Роберт Киосаки', 'Рассказывается о разном мышлении двух отцов у одного ребенка'),
(15, 'КОД', 'Чарльз Петцольд', 'бла бла бла 8');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12355;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
