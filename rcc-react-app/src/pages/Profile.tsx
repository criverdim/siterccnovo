import { useState } from 'react'
import { useAuth } from '../contexts/AuthContext'
import { 
  User, 
  Mail, 
  Phone, 
  MapPin, 
  Globe, 
  Twitter, 
  Linkedin, 
  Github,
  Edit3,
  Save,
  X
} from 'lucide-react'

export default function Profile() {
  const { user } = useAuth()
  const [isEditing, setIsEditing] = useState(false)
  const [formData, setFormData] = useState({
    name: user?.name || '',
    email: user?.email || '',
    phone: '+1 (555) 123-4567',
    location: 'New York, NY',
    website: 'https://example.com',
    bio: 'Software developer passionate about creating amazing user experiences.',
    twitter: '@johndoe',
    linkedin: 'john-doe-123',
    github: 'johndoe'
  })

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target
    setFormData(prev => ({ ...prev, [name]: value }))
  }

  const handleSave = () => {
    setIsEditing(false)
    // In a real app, this would save to backend
    console.log('Saving profile:', formData)
  }

  const handleCancel = () => {
    setIsEditing(false)
    // Reset form data to original values
    setFormData({
      name: user?.name || '',
      email: user?.email || '',
      phone: '+1 (555) 123-4567',
      location: 'New York, NY',
      website: 'https://example.com',
      bio: 'Software developer passionate about creating amazing user experiences.',
      twitter: '@johndoe',
      linkedin: 'john-doe-123',
      github: 'johndoe'
    })
  }

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Page Header */}
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-2xl font-bold text-rcc-gray-900">Profile</h1>
          <p className="text-rcc-gray-600">Manage your personal information and preferences</p>
        </div>
        {!isEditing ? (
          <button
            onClick={() => setIsEditing(true)}
            className="btn btn-primary inline-flex items-center"
          >
            <Edit3 className="w-4 h-4 mr-2" />
            Edit Profile
          </button>
        ) : (
          <div className="flex space-x-3">
            <button
              onClick={handleSave}
              className="btn btn-primary inline-flex items-center"
            >
              <Save className="w-4 h-4 mr-2" />
              Save Changes
            </button>
            <button
              onClick={handleCancel}
              className="btn btn-secondary inline-flex items-center"
            >
              <X className="w-4 h-4 mr-2" />
              Cancel
            </button>
          </div>
        )}
      </div>

      {/* Profile Information */}
      <div className="card">
        <div className="flex items-center space-x-6 mb-6">
          <div className="h-20 w-20 rounded-full bg-rcc-cyan flex items-center justify-center">
            <User className="h-10 w-10 text-white" />
          </div>
          <div>
            <h2 className="text-xl font-semibold text-rcc-gray-900">{user?.name}</h2>
            <p className="text-rcc-gray-600">{user?.email}</p>
            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2 ${
              user?.role === 'admin' 
                ? 'bg-rcc-red text-white' 
                : 'bg-rcc-cyan text-white'
            }`}>
              {user?.role === 'admin' ? 'Administrator' : 'User'}
            </span>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {/* Basic Information */}
          <div className="space-y-4">
            <h3 className="text-lg font-medium text-rcc-gray-900">Basic Information</h3>
            
            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <User className="w-4 h-4 inline mr-1" />
                Full Name
              </label>
              {isEditing ? (
                <input
                  type="text"
                  name="name"
                  value={formData.name}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="Enter your full name"
                />
              ) : (
                <p className="text-rcc-gray-900">{formData.name}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <Mail className="w-4 h-4 inline mr-1" />
                Email Address
              </label>
              {isEditing ? (
                <input
                  type="email"
                  name="email"
                  value={formData.email}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="Enter your email"
                />
              ) : (
                <p className="text-rcc-gray-900">{formData.email}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <Phone className="w-4 h-4 inline mr-1" />
                Phone Number
              </label>
              {isEditing ? (
                <input
                  type="tel"
                  name="phone"
                  value={formData.phone}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="Enter your phone number"
                />
              ) : (
                <p className="text-rcc-gray-900">{formData.phone}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <MapPin className="w-4 h-4 inline mr-1" />
                Location
              </label>
              {isEditing ? (
                <input
                  type="text"
                  name="location"
                  value={formData.location}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="Enter your location"
                />
              ) : (
                <p className="text-rcc-gray-900">{formData.location}</p>
              )}
            </div>
          </div>

          {/* Additional Information */}
          <div className="space-y-4">
            <h3 className="text-lg font-medium text-rcc-gray-900">Additional Information</h3>

            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <Globe className="w-4 h-4 inline mr-1" />
                Website
              </label>
              {isEditing ? (
                <input
                  type="url"
                  name="website"
                  value={formData.website}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="https://yourwebsite.com"
                />
              ) : (
                <a 
                  href={formData.website} 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="text-rcc-cyan hover:text-rcc-cyan-600"
                >
                  {formData.website}
                </a>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                Bio
              </label>
              {isEditing ? (
                <textarea
                  name="bio"
                  value={formData.bio}
                  onChange={handleInputChange}
                  rows={3}
                  className="input"
                  placeholder="Tell us about yourself"
                />
              ) : (
                <p className="text-rcc-gray-900">{formData.bio}</p>
              )}
            </div>

            {/* Social Links */}
            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <Twitter className="w-4 h-4 inline mr-1" />
                Twitter
              </label>
              {isEditing ? (
                <input
                  type="text"
                  name="twitter"
                  value={formData.twitter}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="@username"
                />
              ) : (
                <a 
                  href={`https://twitter.com/${formData.twitter.replace('@', '')}`}
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="text-rcc-cyan hover:text-rcc-cyan-600"
                >
                  {formData.twitter}
                </a>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <Linkedin className="w-4 h-4 inline mr-1" />
                LinkedIn
              </label>
              {isEditing ? (
                <input
                  type="text"
                  name="linkedin"
                  value={formData.linkedin}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="username"
                />
              ) : (
                <a 
                  href={`https://linkedin.com/in/${formData.linkedin}`}
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="text-rcc-cyan hover:text-rcc-cyan-600"
                >
                  {formData.linkedin}
                </a>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                <Github className="w-4 h-4 inline mr-1" />
                GitHub
              </label>
              {isEditing ? (
                <input
                  type="text"
                  name="github"
                  value={formData.github}
                  onChange={handleInputChange}
                  className="input"
                  placeholder="username"
                />
              ) : (
                <a 
                  href={`https://github.com/${formData.github}`}
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="text-rcc-cyan hover:text-rcc-cyan-600"
                >
                  {formData.github}
                </a>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Account Information */}
      <div className="card">
        <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Account Information</h3>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
              Account Created
            </label>
            <p className="text-rcc-gray-900">{new Date().toLocaleDateString()}</p>
          </div>
          <div>
            <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
              Last Login
            </label>
            <p className="text-rcc-gray-900">{new Date().toLocaleDateString()}</p>
          </div>
          <div>
            <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
              Account Status
            </label>
            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
              Active
            </span>
          </div>
        </div>
      </div>
    </div>
  )
}