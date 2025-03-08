-- Sample accounts for L1J Remastered Database
-- This file contains sample accounts for testing the login system
-- access_level = 1 means admin account

INSERT INTO `accounts` (`login`, `password`, `access_level`) VALUES
('admin', 'admin123', 1),
('user1', 'password1', 0),
('user2', 'password2', 0);