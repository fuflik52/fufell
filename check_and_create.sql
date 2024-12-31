-- Удаляем существующую таблицу и начинаем с чистого листа
DROP TABLE IF EXISTS users;

-- Создаем таблицу заново
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()),
    username TEXT NOT NULL,
    score INTEGER DEFAULT 0
);

-- Включаем RLS
ALTER TABLE users ENABLE ROW LEVEL SECURITY;

-- Удаляем существующие политики
DROP POLICY IF EXISTS "Users are viewable by everyone" ON users;
DROP POLICY IF EXISTS "Users can be created by anyone" ON users;
DROP POLICY IF EXISTS "Users can update their own score" ON users;

-- Создаем политики заново
CREATE POLICY "Users are viewable by everyone" 
ON users FOR SELECT 
TO authenticated, anon 
USING (true);

CREATE POLICY "Users can be created by anyone" 
ON users FOR INSERT 
TO authenticated, anon 
WITH CHECK (true);

CREATE POLICY "Users can update their own score" 
ON users FOR UPDATE 
TO authenticated, anon
USING (true)
WITH CHECK (true);

-- Создаем индекс
DROP INDEX IF EXISTS idx_users_username;
CREATE INDEX idx_users_username ON users(username);

-- Добавляем ограничение уникальности
ALTER TABLE users DROP CONSTRAINT IF EXISTS username_unique;
ALTER TABLE users ADD CONSTRAINT username_unique UNIQUE (username);

-- Вставляем тестовую запись
INSERT INTO users (username, score) 
VALUES ('test_user', 100)
ON CONFLICT (username) 
DO UPDATE SET score = EXCLUDED.score;
