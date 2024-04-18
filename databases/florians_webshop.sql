-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 18 apr 2024 om 13:50
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `florians_webshop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(200) NOT NULL,
  `user_id` int(200) NOT NULL,
  `order_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`) VALUES
(1, 5, '2024-04-18'),
(2, 5, '2024-04-18'),
(3, 5, '2024-04-18'),
(4, 5, '2024-04-18'),
(5, 5, '2024-04-18'),
(6, 5, '2024-04-18');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ordersproducts`
--

CREATE TABLE `ordersproducts` (
  `id` int(200) NOT NULL,
  `order_id` int(200) NOT NULL,
  `product_id` int(200) NOT NULL,
  `count` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `ordersproducts`
--

INSERT INTO `ordersproducts` (`id`, `order_id`, `product_id`, `count`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 3, 2, 1),
(4, 4, 3, 1),
(5, 5, 1, 2),
(6, 5, 4, 1),
(7, 5, 5, 2),
(8, 6, 4, 1),
(9, 6, 5, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `price` float NOT NULL,
  `fname` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `fname`) VALUES
(1, 'Origami Dwergspaniël', 'Een Dwergspaniël gevouwen van 15x15cm origami papier. Ontwerp door Jun Maekawa. ', 20, 'origamihond.jpg'),
(2, 'Origami Wild Zwijn', 'Een Wild Zwijn gevouwen van 24x24cm origami papier. Ontwerp door Jun Maekawa. ', 15, 'origamizwijn.jpg'),
(3, 'Fidgetspinner', 'Oranje fidgetspinner met kogellagers. ', 2, 'fidgetspinner.jpg'),
(4, 'Stuiterei', 'Stuiterei gemaakt van rubber. ', 2.5, 'stuiterei.jpg'),
(5, 'Mini Kendama', 'Een Japanse kendama ter grootte van een fietsventiel. ', 3.75, 'minikendama.jpg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `pswd` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `pswd`) VALUES
(1, 'johndoe@mail.com', 'johndoe', 'johniscool'),
(2, 'johnfoo@mail.com', 'johnfoo', 'fooiscool'),
(3, 'notsogreat@gmail.com', 'veryungreat', 'secretlygreat'),
(4, 'sofun@mail.com', 'gettingfun', 'sofun'),
(5, 'ja@ja.nl', 'ja', 'ja');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user-orders` (`user_id`);

--
-- Indexen voor tabel `ordersproducts`
--
ALTER TABLE `ordersproducts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders-orders` (`order_id`),
  ADD KEY `products-products` (`product_id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `ordersproducts`
--
ALTER TABLE `ordersproducts`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `user-orders` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Beperkingen voor tabel `ordersproducts`
--
ALTER TABLE `ordersproducts`
  ADD CONSTRAINT `orders-orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `products-products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
