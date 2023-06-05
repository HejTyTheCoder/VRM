-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 05. čen 2023, 13:47
-- Verze serveru: 10.4.24-MariaDB
-- Verze PHP: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `chatos`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `chatgroups`
--

CREATE TABLE `chatgroups` (
  `idc` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `directchat` tinyint(1) NOT NULL DEFAULT 1,
  `description` varchar(3600) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `chatgroups`
--

INSERT INTO `chatgroups` (`idc`, `name`, `directchat`, `description`) VALUES
(4, 'DirectChatgroup', 1, ''),
(5, 'DirectChatgroup', 1, '');

-- --------------------------------------------------------

--
-- Struktura tabulky `invitations`
--

CREATE TABLE `invitations` (
  `idi` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `idc` int(11) NOT NULL,
  `text` varchar(3600) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `messages`
--

CREATE TABLE `messages` (
  `idm` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `idc` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `messages`
--

INSERT INTO `messages` (`idm`, `idu`, `idc`, `text`, `time`) VALUES
(3, 5, 4, 'Ahoj', '2023-06-05 13:43:34'),
(4, 6, 4, 'Dobry den', '2023-06-05 13:43:48');

-- --------------------------------------------------------

--
-- Struktura tabulky `userchatgroups`
--

CREATE TABLE `userchatgroups` (
  `iduc` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `idc` int(11) NOT NULL,
  `authority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `userchatgroups`
--

INSERT INTO `userchatgroups` (`iduc`, `idu`, `idc`, `authority`) VALUES
(6, 6, 4, 5),
(7, 5, 4, 0),
(8, 6, 5, 5),
(9, 5, 5, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `idu` int(11) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `authority` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `description` varchar(3600) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`idu`, `nickname`, `password`, `authority`, `description`) VALUES
(5, 'vygral', '$2y$10$Gpwx9a0gBvPWbxV4SaaET.5qz.ZyhQUh4/7/cdjB967eGZnFzD9YG', 0, ''),
(6, 'test', '$2y$10$ra7XtopQP352z3hhRzaSQeO7VhLQXiIiG/TT0RuiLh1TBuuRzueOy', 0, '');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `chatgroups`
--
ALTER TABLE `chatgroups`
  ADD PRIMARY KEY (`idc`);

--
-- Indexy pro tabulku `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`idi`),
  ADD KEY `cons_inv_us2` (`idu`),
  ADD KEY `cons_inv_chat` (`idc`),
  ADD KEY `cons_inv_us1` (`sender`);

--
-- Indexy pro tabulku `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`idm`),
  ADD KEY `cons_mes_chat` (`idc`),
  ADD KEY `cons_mes_us` (`idu`);

--
-- Indexy pro tabulku `userchatgroups`
--
ALTER TABLE `userchatgroups`
  ADD PRIMARY KEY (`iduc`),
  ADD KEY `cons_us_chat` (`idc`),
  ADD KEY `cons_us_us` (`idu`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idu`),
  ADD UNIQUE KEY `nickname` (`nickname`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `chatgroups`
--
ALTER TABLE `chatgroups`
  MODIFY `idc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `invitations`
--
ALTER TABLE `invitations`
  MODIFY `idi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `messages`
--
ALTER TABLE `messages`
  MODIFY `idm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `userchatgroups`
--
ALTER TABLE `userchatgroups`
  MODIFY `iduc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `idu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `cons_inv_chat` FOREIGN KEY (`idc`) REFERENCES `chatgroups` (`idc`) ON DELETE CASCADE,
  ADD CONSTRAINT `cons_inv_us1` FOREIGN KEY (`sender`) REFERENCES `users` (`idu`) ON DELETE CASCADE,
  ADD CONSTRAINT `cons_inv_us2` FOREIGN KEY (`idu`) REFERENCES `users` (`idu`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `cons_mes_chat` FOREIGN KEY (`idc`) REFERENCES `chatgroups` (`idc`) ON DELETE CASCADE,
  ADD CONSTRAINT `cons_mes_us` FOREIGN KEY (`idu`) REFERENCES `users` (`idu`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `userchatgroups`
--
ALTER TABLE `userchatgroups`
  ADD CONSTRAINT `cons_us_chat` FOREIGN KEY (`idc`) REFERENCES `chatgroups` (`idc`) ON DELETE CASCADE,
  ADD CONSTRAINT `cons_us_us` FOREIGN KEY (`idu`) REFERENCES `users` (`idu`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
