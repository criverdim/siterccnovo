import { useState, ReactNode } from 'react'
import { loadStoredUser } from './auth-utils'
import { User } from '../types'
import { AuthContext } from './auth-context'

// useAuth moved to hooks/useAuth.ts to satisfy react-refresh lint rule

interface AuthProviderProps {
  children: ReactNode
}

export function AuthProvider({ children }: AuthProviderProps) {
  const initialUser = loadStoredUser() as User | null
  const [user, setUser] = useState<User | null>(initialUser)
  const [isLoading, setIsLoading] = useState(false)

  const login = async (email: string): Promise<void> => {
    setIsLoading(true)
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Mock user data - in a real app, this would come from an API
    const mockUser: User = {
      id: '1',
      email,
      name: email.split('@')[0],
      role: email === 'admin@rcc.com' ? 'admin' : 'user',
      createdAt: new Date(),
      updatedAt: new Date()
    }
    
    setUser(mockUser)
    localStorage.setItem('rcc_user', JSON.stringify(mockUser))
    setIsLoading(false)
  }

  const register = async (email: string, name?: string): Promise<void> => {
    setIsLoading(true)
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    const mockUser: User = {
      id: Date.now().toString(),
      email,
      name: name ?? email.split('@')[0],
      role: 'user',
      createdAt: new Date(),
      updatedAt: new Date()
    }
    
    setUser(mockUser)
    localStorage.setItem('rcc_user', JSON.stringify(mockUser))
    setIsLoading(false)
  }

  const logout = () => {
    setUser(null)
    localStorage.removeItem('rcc_user')
  }

  const value = {
    user,
    login,
    register,
    logout,
    isLoading
  }

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  )
}
