import { supabase } from './supabase-config.js'

// Пример добавления пользователя
async function addUser(username, score) {
  const { data, error } = await supabase
    .from('users')
    .insert([
      { username: username, score: score }
    ])
  if (error) console.error('Error:', error)
  else console.log('Success:', data)
}

// Пример получения всех пользователей
async function getUsers() {
  const { data, error } = await supabase
    .from('users')
    .select('*')
  if (error) console.error('Error:', error)
  else console.log('Users:', data)
}

// Пример обновления счета пользователя
async function updateScore(username, newScore) {
  const { data, error } = await supabase
    .from('users')
    .update({ score: newScore })
    .eq('username', username)
  if (error) console.error('Error:', error)
  else console.log('Updated:', data)
}
