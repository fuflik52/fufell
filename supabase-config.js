import { createClient } from '@supabase/supabase-js'

const supabaseUrl = 'https://vwezjuwhqjreerkqwzhv.supabase.co'
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZ3ZXpqdXdocWpyZWVya3F3emh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzU2NTgxMjIsImV4cCI6MjA1MTIzNDEyMn0.fTu5W-A20kpwK94M_Mm6I-MxtGD_IOzdep7hkf3Y2pE'

export const supabase = createClient(supabaseUrl, supabaseAnonKey)
