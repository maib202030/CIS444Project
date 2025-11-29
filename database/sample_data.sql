USE portfolio_db;

-- ========================
-- USER
-- ========================

INSERT INTO `User`
(userId, name, email, password, role, profilePicture, preference, adminKey, permissions, createdAt, updatedAt)
VALUES
(1, 'Admin User', 'admin@csusm.edu', 'admin123', 'admin', 'admin.png', 'dark', 'MASTER-ADMIN-KEY', 'ALL', '2025-03-01 10:00:00', '2025-03-10 12:00:00'),
(2, 'Alex Haskins', 'alex@csusm.edu', 'alex123', 'user', 'alex.png', 'dark', NULL, NULL, '2025-03-05 09:30:00', '2025-03-10 11:45:00'),
(3, 'Mai Bridge', 'mai@csusm.edu', 'mai123', 'user', 'mai.png', 'dark', NULL, NULL, '2025-03-06 14:20:00', '2025-03-11 13:10:00');

-- ========================
-- PORTFOLIO
-- ========================

INSERT INTO Portfolio
(portfolioId, userId, title, description, layout, theme, createdDate, lastModified, isPublished)
VALUES
(1, 2, 'Alex''s CS Portfolio', 'Projects and coursework from CSUSM.', 'default', 'light', '2025-03-05 10:00:00', '2025-03-12 10:30:00', TRUE),
(2, 3, 'Mai''s Design Portfolio', 'Creative and technical projects.', 'default', 'dark', '2025-03-06 15:00:00', '2025-03-12 11:00:00', TRUE);

-- ========================
-- ABOUT ME
-- ========================

INSERT INTO AboutMe
(aboutId, portfolioId, biography, tagline, profileImage)
VALUES
(1, 1, 'Computer Science student with a focus on software engineering.', 'Turning ideas into software.', 'alex_profile.png'),
(2, 2, 'Designer with a passion for UI/UX and interaction.', 'Design meets functionality.', 'mai_profile.png');

-- ========================
-- PROJECTS
-- ========================

INSERT INTO Projects
(projectId, portfolioId, title, description, link, displayOrder)
VALUES
(1, 1, 'Portfolio Creator', 'Web-based dynamic portfolio system.', 'https://example.com/portfolio', 1),
(2, 2, 'UX Redesign', 'Full UI redesign for productivity app.', 'https://example.com/ux', 1);

-- ========================
-- SKILL
-- ========================

INSERT INTO Skill
(skillId, portfolioId, skillName, category, displayOrder)
VALUES
(1, 1, 'Java', 'hard_skill', 1),
(2, 1, 'Communication', 'soft_skill', 2),
(3, 2, 'UI Design', 'key_skill', 1);

-- ========================
-- RESUME
-- ========================

INSERT INTO Resume
(resumeId, portfolioId, fileName, fileType, filePath, fileSize, uploadDate)
VALUES
(1, 1, 'alex_resume.pdf', 'pdf', '/resumes/alex_resume.pdf', 245000, '2025-03-08 12:00:00'),
(2, 2, 'mai_resume.pdf', 'pdf', '/resumes/mai_resume.pdf', 210000, '2025-03-09 12:00:00');

-- ========================
-- SOCIAL LINK
-- ========================

INSERT INTO SocialLink
(linkId, portfolioId, platform, url)
VALUES
(1, 1, 'GitHub', 'https://github.com/alex'),
(2, 2, 'LinkedIn', 'https://linkedin.com/in/mai');

-- ========================
-- VERIFICATION
-- ========================

INSERT INTO Verification
(verificationId, userId, code, expireTime, createdAt)
VALUES
(1, 2, '123456', '2025-03-15 12:00:00', '2025-03-14 12:00:00');