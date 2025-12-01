import { useState } from 'react'
import { 
  Mail, 
  Smartphone, 
  Shield, 
  Globe,
  Moon,
  Sun,
  Save,
  RefreshCw,
  User as UserIcon,
  Activity as ActivityIcon
} from 'lucide-react'

export default function Settings() {
  const [settings, setSettings] = useState({
    theme: 'light' as 'light' | 'dark' | 'system',
    language: 'en',
    notifications: {
      email: true,
      push: false,
      sms: false
    },
    privacy: {
      profileVisible: true,
      activityVisible: true,
      showEmail: false
    }
  })

  const [hasChanges, setHasChanges] = useState(false)
  const [isSaving, setIsSaving] = useState(false)

  const handleThemeChange = (theme: 'light' | 'dark' | 'system') => {
    setSettings(prev => ({ ...prev, theme }))
    setHasChanges(true)
  }

  const handleNotificationChange = (type: 'email' | 'push' | 'sms') => {
    setSettings(prev => ({
      ...prev,
      notifications: {
        ...prev.notifications,
        [type]: !prev.notifications[type]
      }
    }))
    setHasChanges(true)
  }

  const handlePrivacyChange = (type: 'profileVisible' | 'activityVisible' | 'showEmail') => {
    setSettings(prev => ({
      ...prev,
      privacy: {
        ...prev.privacy,
        [type]: !prev.privacy[type]
      }
    }))
    setHasChanges(true)
  }

  const handleSave = async () => {
    setIsSaving(true)
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // In a real app, this would save to backend
    localStorage.setItem('rcc_settings', JSON.stringify(settings))
    setIsSaving(false)
    setHasChanges(false)
  }

  const handleReset = () => {
    setSettings({
      theme: 'light',
      language: 'en',
      notifications: {
        email: true,
        push: false,
        sms: false
      },
      privacy: {
        profileVisible: true,
        activityVisible: true,
        showEmail: false
      }
    })
    setHasChanges(true)
  }

  const themes = [
    { value: 'light', label: 'Light', icon: Sun },
    { value: 'dark', label: 'Dark', icon: Moon },
    { value: 'system', label: 'System', icon: Globe }
  ]

  const languages = [
    { value: 'en', label: 'English' },
    { value: 'pt', label: 'Português' },
    { value: 'es', label: 'Español' },
    { value: 'fr', label: 'Français' }
  ]

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Page Header */}
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-2xl font-bold text-rcc-gray-900">Settings</h1>
          <p className="text-rcc-gray-600">Manage your preferences and account settings</p>
        </div>
        {hasChanges && (
          <div className="flex space-x-3">
            <button
              onClick={handleReset}
              className="btn btn-secondary inline-flex items-center"
            >
              <RefreshCw className="w-4 h-4 mr-2" />
              Reset
            </button>
            <button
              onClick={handleSave}
              disabled={isSaving}
              className="btn btn-primary inline-flex items-center disabled:opacity-50"
            >
              {isSaving ? (
                <div className="flex items-center">
                  <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                  Saving...
                </div>
              ) : (
                <>
                  <Save className="w-4 h-4 mr-2" />
                  Save Changes
                </>
              )}
            </button>
          </div>
        )}
      </div>

      {/* Appearance Settings */}
      <div className="card">
        <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Appearance</h3>
        
        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-rcc-gray-700 mb-3">
              Theme
            </label>
            <div className="grid grid-cols-3 gap-4">
              {themes.map((theme) => {
                const Icon = theme.icon
                return (
                  <button
                    key={theme.value}
                    onClick={() => handleThemeChange(theme.value as 'light' | 'dark' | 'system')}
                    className={`p-4 border rounded-lg text-center transition-colors duration-200 ${
                      settings.theme === theme.value
                        ? 'border-rcc-cyan bg-rcc-cyan bg-opacity-5'
                        : 'border-rcc-gray-300 hover:border-rcc-gray-400'
                    }`}
                  >
                    <Icon className="h-6 w-6 mx-auto mb-2 text-rcc-gray-600" />
                    <span className="text-sm font-medium text-rcc-gray-900">{theme.label}</span>
                  </button>
                )
              })}
            </div>
          </div>

          <div>
            <label className="block text-sm font-medium text-rcc-gray-700 mb-3">
              Language
            </label>
            <select
              value={settings.language}
              onChange={(e) => {
                setSettings(prev => ({ ...prev, language: e.target.value }))
                setHasChanges(true)
              }}
              className="input max-w-xs"
            >
              {languages.map((lang) => (
                <option key={lang.value} value={lang.value}>
                  {lang.label}
                </option>
              ))}
            </select>
          </div>
        </div>
      </div>

      {/* Notifications */}
      <div className="card">
        <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Notifications</h3>
        
        <div className="space-y-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <Mail className="w-5 h-5 text-rcc-gray-400 mr-3" />
              <div>
                <p className="text-sm font-medium text-rcc-gray-900">Email Notifications</p>
                <p className="text-sm text-rcc-gray-500">Receive notifications via email</p>
              </div>
            </div>
            <button
              onClick={() => handleNotificationChange('email')}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ${
                settings.notifications.email ? 'bg-rcc-cyan' : 'bg-rcc-gray-200'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ${
                  settings.notifications.email ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>

          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <Smartphone className="w-5 h-5 text-rcc-gray-400 mr-3" />
              <div>
                <p className="text-sm font-medium text-rcc-gray-900">Push Notifications</p>
                <p className="text-sm text-rcc-gray-500">Receive push notifications on your device</p>
              </div>
            </div>
            <button
              onClick={() => handleNotificationChange('push')}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ${
                settings.notifications.push ? 'bg-rcc-cyan' : 'bg-rcc-gray-200'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ${
                  settings.notifications.push ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>

          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <Shield className="w-5 h-5 text-rcc-gray-400 mr-3" />
              <div>
                <p className="text-sm font-medium text-rcc-gray-900">SMS Notifications</p>
                <p className="text-sm text-rcc-gray-500">Receive notifications via text message</p>
              </div>
            </div>
            <button
              onClick={() => handleNotificationChange('sms')}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ${
                settings.notifications.sms ? 'bg-rcc-cyan' : 'bg-rcc-gray-200'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ${
                  settings.notifications.sms ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>
        </div>
      </div>

      {/* Privacy Settings */}
      <div className="card">
        <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Privacy</h3>
        
        <div className="space-y-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <UserIcon className="w-5 h-5 text-rcc-gray-400 mr-3" />
              <div>
                <p className="text-sm font-medium text-rcc-gray-900">Profile Visibility</p>
                <p className="text-sm text-rcc-gray-500">Make your profile visible to other users</p>
              </div>
            </div>
            <button
              onClick={() => handlePrivacyChange('profileVisible')}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ${
                settings.privacy.profileVisible ? 'bg-rcc-cyan' : 'bg-rcc-gray-200'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ${
                  settings.privacy.profileVisible ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>

          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <ActivityIcon className="w-5 h-5 text-rcc-gray-400 mr-3" />
              <div>
                <p className="text-sm font-medium text-rcc-gray-900">Activity Visibility</p>
                <p className="text-sm text-rcc-gray-500">Show your recent activity to other users</p>
              </div>
            </div>
            <button
              onClick={() => handlePrivacyChange('activityVisible')}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ${
                settings.privacy.activityVisible ? 'bg-rcc-cyan' : 'bg-rcc-gray-200'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ${
                  settings.privacy.activityVisible ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>

          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <Mail className="w-5 h-5 text-rcc-gray-400 mr-3" />
              <div>
                <p className="text-sm font-medium text-rcc-gray-900">Show Email Address</p>
                <p className="text-sm text-rcc-gray-500">Display your email on your profile</p>
              </div>
            </div>
            <button
              onClick={() => handlePrivacyChange('showEmail')}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ${
                settings.privacy.showEmail ? 'bg-rcc-cyan' : 'bg-rcc-gray-200'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ${
                  settings.privacy.showEmail ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>
        </div>
      </div>

      {/* Account Settings */}
      <div className="card">
        <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Account Settings</h3>
        
        <div className="space-y-4">
          <div className="flex justify-between items-center p-4 border border-rcc-gray-200 rounded-lg">
            <div>
              <p className="text-sm font-medium text-rcc-gray-900">Export Data</p>
              <p className="text-sm text-rcc-gray-500">Download all your account data</p>
            </div>
            <button className="btn btn-outline">
              Export
            </button>
          </div>

          <div className="flex justify-between items-center p-4 border border-rcc-gray-200 rounded-lg">
            <div>
              <p className="text-sm font-medium text-rcc-gray-900">Delete Account</p>
              <p className="text-sm text-rcc-gray-500">Permanently delete your account and all data</p>
            </div>
            <button className="btn bg-red-600 text-white hover:bg-red-700">
              Delete Account
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}
