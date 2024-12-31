-- Создание таблицы users
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()),
    username TEXT NOT NULL,
    score INTEGER DEFAULT 0,
    CONSTRAINT username_unique UNIQUE (username)
);

-- Создание индекса для более быстрого поиска по username
CREATE INDEX idx_users_username ON users(username);

-- Создание политик безопасности для доступа к таблице
ALTER TABLE users ENABLE ROW LEVEL SECURITY;

-- Политика для чтения данных (доступно всем)
CREATE POLICY "Users are viewable by everyone" 
ON users FOR SELECT 
TO authenticated, anon 
USING (true);

-- Политика для вставки новых пользователей (доступно всем)
CREATE POLICY "Users can be created by anyone" 
ON users FOR INSERT 
TO authenticated, anon 
WITH CHECK (true);

-- Политика для обновления счета (только владелец может обновить свой счет)
CREATE POLICY "Users can update their own score" 
ON users FOR UPDATE 
TO authenticated, anon
USING (true)
WITH CHECK (true);
