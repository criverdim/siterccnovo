import { useState } from 'react'
import { 
  Users, 
  Activity, 
  TrendingUp, 
  Calendar,
  DollarSign,
  ShoppingCart,
  Eye,
  EyeOff,
  BarChart3,
  Settings
} from 'lucide-react'

export default function Dashboard() {
  const [showValues, setShowValues] = useState(true)

  const stats = [
    {
      title: 'Total Users',
      value: showValues ? '1,234' : '••••',
      change: '+12%',
      icon: Users,
      color: 'text-rcc-cyan',
      bgColor: 'bg-rcc-cyan bg-opacity-10'
    },
    {
      title: 'Revenue',
      value: showValues ? '$45,678' : '••••••',
      change: '+8%',
      icon: DollarSign,
      color: 'text-rcc-red',
      bgColor: 'bg-rcc-red bg-opacity-10'
    },
    {
      title: 'Orders',
      value: showValues ? '892' : '•••',
      change: '+15%',
      icon: ShoppingCart,
      color: 'text-green-600',
      bgColor: 'bg-green-100'
    },
    {
      title: 'Page Views',
      value: showValues ? '23.4K' : '••••',
      change: '+5%',
      icon: Activity,
      color: 'text-purple-600',
      bgColor: 'bg-purple-100'
    }
  ]

  const recentActivities = [
    {
      id: 1,
      user: 'John Doe',
      action: 'Created new account',
      time: '2 hours ago',
      type: 'user'
    },
    {
      id: 2,
      user: 'Jane Smith',
      action: 'Updated profile information',
      time: '4 hours ago',
      type: 'profile'
    },
    {
      id: 3,
      user: 'Mike Johnson',
      action: 'Made a purchase',
      time: '6 hours ago',
      type: 'purchase'
    },
    {
      id: 4,
      user: 'Sarah Wilson',
      action: 'Changed settings',
      time: '8 hours ago',
      type: 'settings'
    }
  ]

  const getActivityIcon = (type: string) => {
    switch (type) {
      case 'user':
        return '👤'
      case 'profile':
        return '✏️'
      case 'purchase':
        return '🛒'
      case 'settings':
        return '⚙️'
      default:
        return '📋'
    }
  }

  return (
    <div className="space-y-6">
      {/* Page Header */}
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-2xl font-bold text-rcc-gray-900">Dashboard</h1>
          <p className="text-rcc-gray-600">Welcome back! Here's what's happening.</p>
        </div>
        <div className="flex items-center space-x-4">
          <button
            onClick={() => setShowValues(!showValues)}
            className="btn btn-outline inline-flex items-center"
          >
            {showValues ? <EyeOff className="w-4 h-4 mr-2" /> : <Eye className="w-4 h-4 mr-2" />}
            {showValues ? 'Hide' : 'Show'} Values
          </button>
          <div className="text-sm text-rcc-gray-500">
            <Calendar className="w-4 h-4 inline mr-1" />
            {new Date().toLocaleDateString('en-US', { 
              weekday: 'long', 
              year: 'numeric', 
              month: 'long', 
              day: 'numeric' 
            })}
          </div>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {stats.map((stat) => {
          const Icon = stat.icon
          return (
            <div key={stat.title} className="card hover:shadow-md transition-shadow duration-200">
              <div className="flex items-center">
                <div className={`flex-shrink-0 p-2 rounded-md ${stat.bgColor}`}>
                  <Icon className={`h-6 w-6 ${stat.color}`} />
                </div>
                <div className="ml-4 flex-1">
                  <p className="text-sm font-medium text-rcc-gray-600">{stat.title}</p>
                  <p className="text-2xl font-bold text-rcc-gray-900">{stat.value}</p>
                </div>
              </div>
              <div className="mt-4 flex items-center text-sm">
                <TrendingUp className="h-4 w-4 text-green-500 mr-1" />
                <span className="text-green-600 font-medium">{stat.change}</span>
                <span className="text-rcc-gray-500 ml-1">from last month</span>
              </div>
            </div>
          )
        })}
      </div>

      {/* Charts and Activities */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Chart Placeholder */}
        <div className="lg:col-span-2 card">
          <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Revenue Overview</h3>
          <div className="h-64 bg-rcc-gray-50 rounded-lg flex items-center justify-center">
            <div className="text-center">
              <BarChart3 className="h-12 w-12 text-rcc-gray-400 mx-auto mb-2" />
              <p className="text-rcc-gray-500">Chart visualization would go here</p>
              <p className="text-sm text-rcc-gray-400">Integration with Chart.js or D3.js</p>
            </div>
          </div>
        </div>

        {/* Recent Activities */}
        <div className="card">
          <div className="flex justify-between items-center mb-4">
            <h3 className="text-lg font-medium text-rcc-gray-900">Recent Activities</h3>
            <button className="text-rcc-cyan hover:text-rcc-cyan-600 text-sm font-medium">
              View all
            </button>
          </div>
          <div className="space-y-4">
            {recentActivities.map((activity) => (
              <div key={activity.id} className="flex items-start space-x-3">
                <div className="flex-shrink-0">
                  <span className="text-lg">{getActivityIcon(activity.type)}</span>
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-rcc-gray-900">{activity.user}</p>
                  <p className="text-sm text-rcc-gray-500">{activity.action}</p>
                  <p className="text-xs text-rcc-gray-400 mt-1">{activity.time}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Additional Widgets */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="card">
          <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">Quick Actions</h3>
          <div className="space-y-3">
            <button className="w-full btn btn-secondary text-left">
              <Users className="w-4 h-4 mr-2 inline" />
              Manage Users
            </button>
            <button className="w-full btn btn-secondary text-left">
              <Settings className="w-4 h-4 mr-2 inline" />
              System Settings
            </button>
            <button className="w-full btn btn-secondary text-left">
              <BarChart3 className="w-4 h-4 mr-2 inline" />
              View Reports
            </button>
          </div>
        </div>

        <div className="card">
          <h3 className="text-lg font-medium text-rcc-gray-900 mb-4">System Status</h3>
          <div className="space-y-3">
            <div className="flex justify-between items-center">
              <span className="text-sm text-rcc-gray-600">API Status</span>
              <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Operational
              </span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-rcc-gray-600">Database</span>
              <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Connected
              </span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-rcc-gray-600">Cache</span>
              <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Active
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}