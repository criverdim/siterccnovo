import { useState } from 'react'
import { 
  Users, 
  Search, 
  Shield, 
  UserPlus,
  Edit,
  Trash2,
  CheckCircle,
  XCircle
} from 'lucide-react'

interface User {
  id: string
  name: string
  email: string
  role: 'admin' | 'user'
  status: 'active' | 'inactive'
  createdAt: string
  lastLogin: string
  avatar?: string
}

export default function Admin() {
  const [searchTerm, setSearchTerm] = useState('')
  const [roleFilter, setRoleFilter] = useState<'all' | 'admin' | 'user'>('all')
  const [statusFilter, setStatusFilter] = useState<'all' | 'active' | 'inactive'>('all')
  const [showInviteModal, setShowInviteModal] = useState(false)
  const [inviteEmail, setInviteEmail] = useState('')

  // Mock user data
  const users: User[] = [
    {
      id: '1',
      name: 'John Doe',
      email: 'john@example.com',
      role: 'admin',
      status: 'active',
      createdAt: '2024-01-15',
      lastLogin: '2024-01-20'
    },
    {
      id: '2',
      name: 'Jane Smith',
      email: 'jane@example.com',
      role: 'user',
      status: 'active',
      createdAt: '2024-01-10',
      lastLogin: '2024-01-19'
    },
    {
      id: '3',
      name: 'Mike Johnson',
      email: 'mike@example.com',
      role: 'user',
      status: 'inactive',
      createdAt: '2024-01-05',
      lastLogin: '2024-01-05'
    },
    {
      id: '4',
      name: 'Sarah Wilson',
      email: 'sarah@example.com',
      role: 'user',
      status: 'active',
      createdAt: '2024-01-08',
      lastLogin: '2024-01-18'
    }
  ]

  const filteredUsers = users.filter(user => {
    const matchesSearch = user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         user.email.toLowerCase().includes(searchTerm.toLowerCase())
    const matchesRole = roleFilter === 'all' || user.role === roleFilter
    const matchesStatus = statusFilter === 'all' || user.status === statusFilter
    
    return matchesSearch && matchesRole && matchesStatus
  })

  const handleInviteUser = () => {
    if (inviteEmail) {
      // In a real app, this would send an invitation email
      console.log('Inviting user:', inviteEmail)
      setInviteEmail('')
      setShowInviteModal(false)
    }
  }

  const handleRoleChange = (userId: string, newRole: 'admin' | 'user') => {
    console.log(`Changing role for user ${userId} to ${newRole}`)
    // In a real app, this would update the user's role
  }

  const handleStatusChange = (userId: string, newStatus: 'active' | 'inactive') => {
    console.log(`Changing status for user ${userId} to ${newStatus}`)
    // In a real app, this would update the user's status
  }

  const handleDeleteUser = (userId: string) => {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
      console.log('Deleting user:', userId)
      // In a real app, this would delete the user
    }
  }

  return (
    <div className="space-y-6">
      {/* Page Header */}
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-2xl font-bold text-rcc-gray-900">User Management</h1>
          <p className="text-rcc-gray-600">Manage system users and their permissions</p>
        </div>
        <button
          onClick={() => setShowInviteModal(true)}
          className="btn btn-primary inline-flex items-center"
        >
          <UserPlus className="w-4 h-4 mr-2" />
          Invite User
        </button>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0 p-2 rounded-md bg-rcc-cyan bg-opacity-10">
              <Users className="h-6 w-6 text-rcc-cyan" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-rcc-gray-600">Total Users</p>
              <p className="text-2xl font-bold text-rcc-gray-900">{users.length}</p>
            </div>
          </div>
        </div>
        
        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0 p-2 rounded-md bg-rcc-red bg-opacity-10">
              <Shield className="h-6 w-6 text-rcc-red" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-rcc-gray-600">Admins</p>
              <p className="text-2xl font-bold text-rcc-gray-900">
                {users.filter(u => u.role === 'admin').length}
              </p>
            </div>
          </div>
        </div>
        
        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0 p-2 rounded-md bg-green-100">
              <CheckCircle className="h-6 w-6 text-green-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-rcc-gray-600">Active Users</p>
              <p className="text-2xl font-bold text-rcc-gray-900">
                {users.filter(u => u.status === 'active').length}
              </p>
            </div>
          </div>
        </div>
        
        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0 p-2 rounded-md bg-rcc-gray-100">
              <XCircle className="h-6 w-6 text-rcc-gray-500" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-rcc-gray-600">Inactive Users</p>
              <p className="text-2xl font-bold text-rcc-gray-900">
                {users.filter(u => u.status === 'inactive').length}
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Filters and Search */}
      <div className="card">
        <div className="flex flex-col sm:flex-row gap-4">
          <div className="flex-1">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-rcc-gray-400" />
              <input
                type="text"
                placeholder="Search users by name or email..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="input pl-10"
              />
            </div>
          </div>
          
          <div className="flex gap-4">
            <select
              value={roleFilter}
              onChange={(e) => setRoleFilter(e.target.value as 'all' | 'admin' | 'user')}
              className="input"
            >
              <option value="all">All Roles</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
            
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value as 'all' | 'active' | 'inactive')}
              className="input"
            >
              <option value="all">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      {/* Users Table */}
      <div className="card">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-rcc-gray-200">
            <thead className="bg-rcc-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-rcc-gray-500 uppercase tracking-wider">
                  User
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-rcc-gray-500 uppercase tracking-wider">
                  Role
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-rcc-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-rcc-gray-500 uppercase tracking-wider">
                  Created
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-rcc-gray-500 uppercase tracking-wider">
                  Last Login
                </th>
                <th className="relative px-6 py-3">
                  <span className="sr-only">Actions</span>
                </th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-rcc-gray-200">
              {filteredUsers.map((user) => (
                <tr key={user.id} className="hover:bg-rcc-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="flex items-center">
                      <div className="h-10 w-10 rounded-full bg-rcc-cyan flex items-center justify-center">
                        <span className="text-white font-medium">
                          {user.name.split(' ').map(n => n[0]).join('')}
                        </span>
                      </div>
                      <div className="ml-4">
                        <div className="text-sm font-medium text-rcc-gray-900">{user.name}</div>
                        <div className="text-sm text-rcc-gray-500">{user.email}</div>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                      user.role === 'admin' 
                        ? 'bg-rcc-red text-white' 
                        : 'bg-rcc-cyan text-white'
                    }`}>
                      {user.role}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                      user.status === 'active'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-rcc-gray-100 text-rcc-gray-800'
                    }`}>
                      {user.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-rcc-gray-500">
                    {user.createdAt}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-rcc-gray-500">
                    {user.lastLogin}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div className="flex items-center justify-end space-x-2">
                      <button
                        onClick={() => handleRoleChange(user.id, user.role === 'admin' ? 'user' : 'admin')}
                        className="text-rcc-cyan hover:text-rcc-cyan-600 text-sm font-medium"
                      >
                        <Edit className="w-4 h-4" />
                      </button>
                      <button
                        onClick={() => handleStatusChange(user.id, user.status === 'active' ? 'inactive' : 'active')}
                        className="text-rcc-gray-500 hover:text-rcc-gray-700 text-sm font-medium"
                      >
                        {user.status === 'active' ? <XCircle className="w-4 h-4" /> : <CheckCircle className="w-4 h-4" />}
                      </button>
                      <button
                        onClick={() => handleDeleteUser(user.id)}
                        className="text-rcc-red hover:text-red-700 text-sm font-medium"
                      >
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Invite User Modal */}
      {showInviteModal && (
        <div className="fixed inset-0 bg-rcc-gray-500 bg-opacity-75 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Invite New User</h3>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-rcc-gray-700 mb-1">
                  Email Address
                </label>
                <input
                  type="email"
                  value={inviteEmail}
                  onChange={(e) => setInviteEmail(e.target.value)}
                  className="input"
                  placeholder="Enter email address"
                />
              </div>
              <div className="flex justify-end space-x-3">
                <button
                  onClick={() => {
                    setShowInviteModal(false)
                    setInviteEmail('')
                  }}
                  className="btn btn-secondary"
                >
                  Cancel
                </button>
                <button
                  onClick={handleInviteUser}
                  className="btn btn-primary"
                >
                  Send Invitation
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
