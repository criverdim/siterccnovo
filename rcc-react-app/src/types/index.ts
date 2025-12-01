export interface User {
  id: string
  email: string
  name: string
  role: 'user' | 'admin'
  avatar?: string
  createdAt: Date
  updatedAt: Date
}

export interface Profile {
  id: string
  userId: string
  bio?: string
  phone?: string
  location?: string
  website?: string
  socialLinks?: {
    twitter?: string
    linkedin?: string
    github?: string
  }
}

export interface Settings {
  id: string
  userId: string
  theme: 'light' | 'dark' | 'system'
  language: string
  notifications: {
    email: boolean
    push: boolean
    sms: boolean
  }
  privacy: {
    profileVisible: boolean
    activityVisible: boolean
  }
}

export interface Activity {
  id: string
  userId: string
  type: 'login' | 'profile_update' | 'settings_change' | 'admin_action'
  description: string
  metadata?: Record<string, unknown>
  createdAt: Date
}

export interface DashboardStats {
  totalUsers: number
  activeUsers: number
  newUsersThisMonth: number
  totalActivities: number
}

export interface AuthContextType {
  user: User | null
  login: (email: string, password: string) => Promise<void>
  register: (email: string, password: string, name: string) => Promise<void>
  logout: () => void
  isLoading: boolean
}
