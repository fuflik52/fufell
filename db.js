import { supabase } from './supabase-config.js';

// Функция для сохранения прогресса игрока
export async function saveProgress(username, score) {
    const { data, error } = await supabase
        .from('users')
        .upsert({ 
            username: username,
            score: score
        }, {
            onConflict: 'username',
            returning: true
        });

    if (error) {
        console.error('Error saving progress:', error);
        return false;
    }
    return true;
}

// Функция для получения прогресса игрока
export async function getProgress(username) {
    const { data, error } = await supabase
        .from('users')
        .select('*')
        .eq('username', username)
        .single();

    if (error) {
        console.error('Error getting progress:', error);
        return null;
    }
    return data;
}

// Функция для получения таблицы лидеров
export async function getLeaderboard(limit = 10) {
    const { data, error } = await supabase
        .from('users')
        .select('username, score')
        .order('score', { ascending: false })
        .limit(limit);

    if (error) {
        console.error('Error getting leaderboard:', error);
        return [];
    }
    return data;
}
