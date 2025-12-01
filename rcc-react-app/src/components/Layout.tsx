import { Outlet, Link, useNavigate, Navigate } from 'react-router-dom'
import { useAuth } from '../hooks/useAuth'
import { 
  Home, 
  BarChart3, 
  User, 
  Settings, 
  Shield, 
  LogOut,
  Menu,
  X
} from 'lucide-react'
import { useState } from 'react'

export default function Layout() {
  const { user, logout } = useAuth()
  const navigate = useNavigate()
  const [isMenuOpen, setIsMenuOpen] = useState(false)

  const handleLogout = () => {
    logout()
    navigate('/login')
  }

  if (!user) {
    return <Navigate to="/login" replace />
  }

  const navItems = [
    { to: '/', icon: Home, label: 'Home' },
    { to: '/dashboard', icon: BarChart3, label: 'Dashboard' },
    { to: '/profile', icon: User, label: 'Profile' },
    { to: '/settings', icon: Settings, label: 'Settings' },
    ...(user.role === 'admin' ? [{ to: '/admin', icon: Shield, label: 'Admin' }] : []),
  ]

  return (
    <div className="min-h-screen bg-rcc-gray-50">
      {/* Navigation */}
      <nav className="bg-white shadow-sm border-b border-rcc-gray-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between h-16">
            <div className="flex">
              <div className="flex-shrink-0 flex items-center">
                <h1 className="text-xl font-bold text-rcc-red">RCC System</h1>
              </div>
              
              {/* Desktop Navigation */}
              <div className="hidden sm:ml-6 sm:flex sm:space-x-8">
                {navItems.map((item) => {
                  const Icon = item.icon
                  return (
                    <Link
                      key={item.to}
                      to={item.to}
                      className={`inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 border-transparent text-rcc-gray-500 hover:text-rcc-gray-700 hover:border-rcc-gray-300`}
                    >
                      <Icon className="w-4 h-4 mr-2" />
                      {item.label}
                    </Link>
                  )
                })}
              </div>
            </div>

            {/* User Menu */}
            <div className="hidden sm:ml-6 sm:flex sm:items-center">
              <div className="flex items-center space-x-4">
                <span className="text-sm text-rcc-gray-700">{user.name}</span>
                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                  user.role === 'admin' 
                    ? 'bg-rcc-red text-white' 
                    : 'bg-rcc-cyan text-white'
                }`}>
                  {user.role}
                </span>
                <button
                  onClick={handleLogout}
                  className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-rcc-gray-500 hover:text-rcc-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rcc-cyan transition-colors duration-200"
                >
                  <LogOut className="w-4 h-4 mr-2" />
                  Logout
                </button>
              </div>
            </div>

            {/* Mobile menu button */}
            <div className="sm:hidden flex items-center">
              <button
                onClick={() => setIsMenuOpen(!isMenuOpen)}
                className="inline-flex items-center justify-center p-2 rounded-md text-rcc-gray-400 hover:text-rcc-gray-500 hover:bg-rcc-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-rcc-cyan"
              >
                {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
              </button>
            </div>
          </div>
        </div>

        {/* Mobile Navigation */}
        {isMenuOpen && (
          <div className="sm:hidden">
            <div className="pt-2 pb-3 space-y-1">
              {navItems.map((item) => {
                const Icon = item.icon
                return (
                  <Link
                    key={item.to}
                    to={item.to}
                    className={`flex items-center px-3 py-2 rounded-md text-base font-medium transition-colors duration-200 text-rcc-gray-600 hover:text-rcc-gray-900 hover:bg-rcc-gray-50`}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    <Icon className="w-5 h-5 mr-3" />
                    {item.label}
                  </Link>
                )
              })}
              <div className="border-t border-rcc-gray-200 pt-4">
                <div className="flex items-center px-3">
                  <span className="text-sm font-medium text-rcc-gray-700">{user.name}</span>
                  <span className={`ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${
                    user.role === 'admin' 
                      ? 'bg-rcc-red text-white' 
                      : 'bg-rcc-cyan text-white'
                  }`}>
                    {user.role}
                  </span>
                </div>
                <button
                  onClick={handleLogout}
                  className="mt-3 flex items-center px-3 py-2 rounded-md text-base font-medium text-rcc-gray-600 hover:text-rcc-gray-900 hover:bg-rcc-gray-50 w-full text-left transition-colors duration-200"
                >
                  <LogOut className="w-5 h-5 mr-3" />
                  Logout
                </button>
              </div>
            </div>
          </div>
        )}
      </nav>

      {/* Main Content */}
      <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <Outlet />
      </main>
    </div>
  )
}
