CREATE TABLE User (
    userId INT PRIMARY KEY AUTO_INCREMENT, /* Since we don't add ID's we want system to automatically add it*/
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user', /* defined set of named constants values aka only can be user or admin */
    profilePicture VARCHAR(500),
    preference ENUM('light', 'dark') DEFAULT 'light',
    adminKey VARCHAR(100),
    permissions VARCHAR(255),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Portfolio (
    portfolioId INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    layout VARCHAR(50) DEFAULT 'default',
    theme VARCHAR(50) DEFAULT 'light',
    createdDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lastModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, /* Upon update, change to current time*/
    isPublished BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE /* userId is a foreign key to the User table */
);

CREATE TABLE AboutMe (
    aboutId INT PRIMARY KEY AUTO_INCREMENT,
    portfolioId INT UNIQUE NOT NULL,
    biography TEXT,
    tagline VARCHAR(255),
    profileImage VARCHAR(500),
    FOREIGN KEY (portfolioId) REFERENCES Portfolio(portfolioId) ON DELETE CASCADE
);

CREATE TABLE Projects (
    projectId INT PRIMARY KEY AUTO_INCREMENT,
    portfolioId INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    link VARCHAR(500),
    displayOrder INT DEFAULT 0,
    FOREIGN KEY (portfolioId) REFERENCES Portfolio(portfolioId) ON DELETE CASCADE
);

CREATE TABLE Skill (
    skillId INT PRIMARY KEY AUTO_INCREMENT,
    portfolioId INT NOT NULL,
    skillName VARCHAR(100) NOT NULL,
    category ENUM('hard_skill', 'soft_skill', 'key_skill'), 
    displayOrder INT DEFAULT 0, 
    FOREIGN KEY (portfolioId) REFERENCES Portfolio(portfolioId) ON DELETE CASCADE
);

CREATE TABLE Resume (
    resumeId INT PRIMARY KEY AUTO_INCREMENT,
    portfolioId INT NOT NULL,
    fileName VARCHAR(255) NOT NULL,
    fileType ENUM('pdf','doc','docx') NOT NULL,
    filePath VARCHAR(500) NOT NULL,
    fileSize INT,
    uploadDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (portfolioId) REFERENCES Portfolio(portfolioId) ON DELETE CASCADE
);

CREATE TABLE SocialLink (
    linkId INT PRIMARY KEY AUTO_INCREMENT,
    portfolioId INT NOT NULL,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(500) NOT NULL,
    FOREIGN KEY (portfolioId) REFERENCES Portfolio(portfolioId) ON DELETE CASCADE
);

CREATE TABLE Verification (
    verificationId INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    code VARCHAR(6) NOT NULL,
    expireTime TIMESTAMP NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
);
