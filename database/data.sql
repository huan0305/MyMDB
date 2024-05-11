--
-- Test data
-- Completed by Walter Waldron, Jawaa Chen, and Brian Huang
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password`, `username`, `email`, `is_admin`, `displayname`) VALUES
(1, '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'test', 'test@test.com', 1, 'test admin'),
(2, '$2y$10$cyuVwNUnwUtTKXKb9znzPeVaykpo0w5x77fKV2C41fZfu8pVpKqda', 'test2', 'test2@test.com', 0, 'test user'),
(3, 'johnpassword', 'john_doe', 'johndoe@example.com', 0, 'John Doe'),
(4, 'alicepassword', 'Alice_Smith', 'alicesmith@example.com',0, 'Alice Smith'),
(5, 'felixpassword', 'felix_feng', 'felixfeng@example.com', 0, 'Felix Feng');

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `genre`) VALUES
(1, 'Action'),
(2, 'Comedy'),
(3, 'Family'),
(4, 'Horror'),
(5, 'Sci-Fi'),
(6, 'Fantasy'),
(7, 'Animation'),
(8, 'Musical'),
(9, 'Thriller'),
(10, 'Drama');

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `name`, `releaseDate`, `description`, `genre_id`) VALUES
(1, 'Aladdin', '1994-02-01', 'Street kid finds a genie and becomes a prince. ', 7),
(2, 'Jurassic Park', '1993-06-11', 'Dinosaurs come back to life.', 9),
(3, 'Forest Gump', '1994-07-06', 'A man runs across the USA.', 2),
(4, 'Lion King', '1994-06-24', 'Simba comes back to claim his kingdom.', 3),
(5, 'Up', '2008-02-01', 'A guy flies around in his house.', 1),
(7, 'Joker', '2022-08-07', 'The origin of the Joker.', 10),
(9, 'Cinderella', '1970-09-08', 'Cinderella loses her shoe.', 6),
(10, 'The Conjuring', '2013-10-31', 'A family moves into a haunted house.', 4),
(11, 'Interstellar', '2016-02-24', 'Trying to save humanity by traversing space. ', 5),
(12, 'John Wick', '2010-03-29', 'Man seeking revenge for death of his dog..', 8),
(13, 'Frozen', '2008-10-12', 'A frozen wasteland', 3);

--
-- Dumping data for table `images`
-- Images tied to each movie.
--

INSERT INTO `images` (`id`, `movie_id`, `filename`, `text`, `alttext`) VALUES
(3, 1, 'movieImages/54bb31ac5f433381b25323756f8cd44866007cf93da63.png', 'Aladdin', 'Picture of a aladin broadway musical'),
(4, 2, 'movieImages/7b183e6aa34f4ae830e1c665d03a8b1f66007e250e871.png', 'Jurassic Park', 'Picture of T-Rex'),
(5, 3, 'movieImages/eae84a27a4fd10f2ef1ee29738d43e3066007d2d73714.png', 'Forest Gump', 'Picture of Bubba gump shrimp company'),
(6, 4, 'movieImages/984a447632f4d852d623b6dcd0cef68966007d5a93235.png', 'Lion King', 'Picture of a lion head'),
(7, 5, 'movieImages/fa1813718763b662b2b8d33e66bcb8e266007d99c8789.png', 'Up', 'Man with balloons'),
(8, 7, 'movieImages/c4504a35e9e477ed7672894dad1dd6c96603457dd68af.png', 'Joker', 'Picture of the joker from batman'),
(9, 9, 'movieImages/e5ec79e170155816985c50822b695edd6603458eabc61.png', 'Cinderella', 'Picture of cinderella\'s castle'),
(10, 10, 'movieImages/c04818d3d6020e46a07093a724b982b26603459af1850.png', 'The Conjuring', 'Picture of scary ghost lady with knife'),
(11, 11, 'movieImages/2de7ed5ad2405e1747154acd6644ed6d660345b264998.png', 'Interstellar', 'Picture of stars'),
(12, 12, 'movieImages/c21b3259058475c15317a428e8db1f8a660345bc34030.png', 'John Wick', 'Lego man with gun'),
(13, 13, 'movieImages/18e6fd5bb7cdd995a6aa5dbed1b7979c6607097520a30.png', 'Frozen', 'Snowflake');