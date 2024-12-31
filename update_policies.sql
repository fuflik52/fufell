-- Включаем RLS если еще не включен
ALTER TABLE users ENABLE ROW LEVEL SECURITY;

-- Создаем политики только если они не существуют
DO $$ 
BEGIN
    -- Проверяем существование политики для чтения
    IF NOT EXISTS (
        SELECT 1 FROM pg_policies 
        WHERE tablename = 'users' 
        AND policyname = 'Users are viewable by everyone'
    ) THEN
        CREATE POLICY "Users are viewable by everyone" 
        ON users FOR SELECT 
        TO authenticated, anon 
        USING (true);
    END IF;

    -- Проверяем существование политики для создания
    IF NOT EXISTS (
        SELECT 1 FROM pg_policies 
        WHERE tablename = 'users' 
        AND policyname = 'Users can be created by anyone'
    ) THEN
        CREATE POLICY "Users can be created by anyone" 
        ON users FOR INSERT 
        TO authenticated, anon 
        WITH CHECK (true);
    END IF;

    -- Проверяем существование политики для обновления
    IF NOT EXISTS (
        SELECT 1 FROM pg_policies 
        WHERE tablename = 'users' 
        AND policyname = 'Users can update their own score'
    ) THEN
        CREATE POLICY "Users can update their own score" 
        ON users FOR UPDATE 
        TO authenticated, anon
        USING (true)
        WITH CHECK (true);
    END IF;
END $$;

-- Создание индекса если не существует
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);

-- Добавление ограничения уникальности если не существует
DO $$ 
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_constraint 
        WHERE conname = 'username_unique'
    ) THEN
        ALTER TABLE users ADD CONSTRAINT username_unique UNIQUE (username);
    END IF;
END $$;
