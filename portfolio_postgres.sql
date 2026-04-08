-- PostgreSQL Dump for Portfolio

CREATE TABLE admins (
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL
);

INSERT INTO admins (email, password) VALUES
('test@test', '$2y$12$wcHFWBbufOwgr6NFAqcyvu9g6424.Zw1OgiCmFFLFkCvl8GvFYwie');

CREATE TABLE categories (
  id SERIAL PRIMARY KEY,
  name varchar(100) NOT NULL
);

CREATE TABLE images (
  id SERIAL PRIMARY KEY,
  url varchar(100) NOT NULL,
  alt varchar(100) NOT NULL,
  is_cover boolean NOT NULL DEFAULT FALSE
);

INSERT INTO images (id, url, alt, is_cover) VALUES
(1, 'profile_picture.webp', 'Photo de profile', FALSE);

-- Reset sequence since we inserted explicit IDs
SELECT setval('images_id_seq', (SELECT MAX(id) FROM images));

CREATE TABLE profile (
  full_name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  phone_number integer NOT NULL,
  description text NOT NULL,
  id_image integer NOT NULL DEFAULT 1 REFERENCES images(id),
  skills varchar(255) NOT NULL DEFAULT ''
);

INSERT INTO profile (full_name, email, phone_number, description, id_image, skills) VALUES
('Gabriel Caboche', 'gabriel.caboche@gmail.com', 767929246, '<p>KUFSDIL§JHHT EXKBUHJ?£</p><p>YGP%¨%OV B</p><p>PYIYG%LJ</p><p>IYBIO IKL UFC£JH</p>', 1, 'HTML,SCSS,JavaScript,MySQL,Git,Laravel,CSS,PHP,Docker,Notion,Figma');

CREATE TABLE projects (
  id SERIAL PRIMARY KEY,
  title varchar(100) NOT NULL,
  description text NOT NULL,
  start_date date NOT NULL,
  end_date date NOT NULL,
  labels varchar(255) NOT NULL,
  link varchar(255) NOT NULL,
  id_category integer NOT NULL REFERENCES categories(id)
);

CREATE TABLE "project images" (
  id SERIAL PRIMARY KEY,
  id_project integer NOT NULL REFERENCES projects(id),
  id_image integer NOT NULL REFERENCES images(id)
);

CREATE TABLE skills (
  id SERIAL PRIMARY KEY,
  name varchar(100) NOT NULL
);

CREATE TABLE "project skills" (
  id SERIAL PRIMARY KEY,
  id_project integer NOT NULL REFERENCES projects(id),
  id_skills integer NOT NULL REFERENCES skills(id)
);

INSERT INTO skills (id, name) VALUES
(1, 'PHP'),
(2, 'HTML'),
(3, 'CSS'),
(4, 'SCSS'),
(5, 'JavaScript'),
(6, 'MySQL'),
(7, 'Git'),
(8, 'Laravel'),
(9, 'Symfony'),
(10, 'Vue.js'),
(11, 'React'),
(12, 'Node.js'),
(13, 'Figma'),
(14, 'Notion'),
(15, 'Photoshop'),
(16, 'Illustrator'),
(17, 'Docker'),
(18, 'Composer'),
(19, 'NPM'),
(20, 'Webpack');

-- Reset sequence since we inserted explicit IDs
SELECT setval('skills_id_seq', (SELECT MAX(id) FROM skills));

CREATE TABLE socials (
  id SERIAL PRIMARY KEY,
  name varchar(100) NOT NULL,
  url varchar(255) NOT NULL,
  icon text NOT NULL
);
