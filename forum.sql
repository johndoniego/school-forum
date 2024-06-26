SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00"; 

CREATE TABLE `Users` (
  `UserID` INT AUTO_INCREMENT PRIMARY KEY,
  `Username` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(255) UNIQUE NOT NULL,
  `Password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Posts` (
  `PostID` INT AUTO_INCREMENT PRIMARY KEY,
  `UserID` INT NOT NULL,
  `Title` VARCHAR(255) NOT NULL,
  `Content` TEXT NOT NULL,
  `ImagePath` VARCHAR(255) NOT NULL,
  `CreationDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`UserID`) REFERENCES `Users`(`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Comments` (
  `CommentID` INT AUTO_INCREMENT PRIMARY KEY,
  `PostID` INT NOT NULL,
  `UserID` INT NOT NULL,
  `Content` TEXT NOT NULL,
  `CreationDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`PostID`) REFERENCES `Posts`(`PostID`),
  FOREIGN KEY (`UserID`) REFERENCES `Users`(`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `UserProfile` (
  `ProfileID` INT AUTO_INCREMENT PRIMARY KEY,
  `UserID` INT NOT NULL,
  `FirstName` VARCHAR(255) NOT NULL,
  `LastName` VARCHAR(255) NOT NULL,
  `DateOfBirth` DATE,
  `ProfilePicture` VARCHAR(255),
  `Bio` TEXT,
  `ContactEmail` VARCHAR(255),
  `PhoneNumber` VARCHAR(20),
  `Address` VARCHAR(255),
  `City` VARCHAR(100),
  `State` VARCHAR(100),
  `Country` VARCHAR(100),
  `PostalCode` VARCHAR(20),
  `Preferences` TEXT,
  FOREIGN KEY (`UserID`) REFERENCES `Users`(`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Bookmarks (
    BookmarkID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    PostID INT NOT NULL,
    UNIQUE KEY unique_bookmark (UserID, PostID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (PostID) REFERENCES Posts(PostID)
);

-- Step 1: Create Categories Table
CREATE TABLE `Categories` (
  `CategoryID` INT AUTO_INCREMENT PRIMARY KEY,
  `CategoryName` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 2: Add CategoryID Column to Posts Table
ALTER TABLE `Posts`
ADD COLUMN `CategoryID` INT,
ADD CONSTRAINT `FK_Posts_Categories` FOREIGN KEY (`CategoryID`) REFERENCES `Categories`(`CategoryID`);

-- Step 3: Insert Category Data
INSERT INTO `Categories` (`CategoryName`) VALUES
('Homework Help'),
('Club Announcements'),
('Event Updates'),
('General Discussion');

-- Step 4 (Optional): Update Existing Posts with Categories
-- Example: Update a post to "Homework Help" category
-- UPDATE `Posts` SET `CategoryID` = (SELECT `CategoryID` FROM `Categories` WHERE `CategoryName` = 'Homework Help') WHERE `PostID` = [YourPostID];
