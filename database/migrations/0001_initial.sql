
DROP DATABASE IF EXISTS portfolio_ecole;
CREATE TABLE IF NOT EXISTS portfolio_profile (
  id smallint PRIMARY KEY DEFAULT 1 CHECK (id = 1),
  hero_title text NOT NULL DEFAULT 'Du développement à la cybersécurité',
  hero_intro text NOT NULL DEFAULT '',
  availability text NOT NULL DEFAULT '',
  updated_at timestamptz NOT NULL DEFAULT now()
);
