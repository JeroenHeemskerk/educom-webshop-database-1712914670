-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 19 apr 2024 om 11:32
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
(7, 8, '2024-04-19'),
(8, 8, '2024-04-19'),
(9, 9, '2024-04-19'),
(10, 8, '2024-04-11'),
(11, 8, '2024-04-19');

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
(10, 7, 1, 1),
(11, 8, 4, 3),
(12, 9, 2, 3),
(13, 9, 1, 2),
(14, 9, 5, 1),
(15, 10, 2, 2),
(16, 11, 4, 1),
(17, 11, 3, 7);

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
(6, 'nee@nee.nl', 'nee', '$2y$10$AVgE8NNwlQYiERX.QABU.uaRDJeExurIKoLY.LQcDiJXKRIm8dP96'),
(7, 'ja@ja.nl', 'ja', '$2y$10$OAWsH9KV/rMGuttGl7BN/eS9ROgNsXu.bkXZk/4VwZ8Cd0xGwm6YW'),
(8, 'ok@ok.nl', 'ok', '$2y$10$NxjLPL.2bHOeYUhCvTy6VekNue2nW7/7VFPgGK/1jCJ59t54J7mCi'),
(9, 'no@no.nl', 'no', '$2y$10$ltnKwN5HAQAkC635c0XJuu8n/RIB6K3VlOyvejFW9teR78xQPls2C');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userplacesorders` (`user_id`);

--
-- Indexen voor tabel `ordersproducts`
--
ALTER TABLE `ordersproducts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderhasproducts` (`product_id`),
  ADD KEY `orderhasorders` (`order_id`);

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
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `ordersproducts`
--
ALTER TABLE `ordersproducts`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `userplacesorders` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Beperkingen voor tabel `ordersproducts`
--
ALTER TABLE `ordersproducts`
  ADD CONSTRAINT `orderhasorders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orderhasproducts` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
