import { Link } from 'react-router-dom'
import { ArrowRight, Shield, BarChart3, Users, Zap, CheckCircle } from 'lucide-react'

export default function Home() {
  const features = [
    {
      icon: Shield,
      title: 'Secure & Reliable',
      description: 'Enterprise-grade security with local data storage and encrypted connections.'
    },
    {
      icon: BarChart3,
      title: 'Real-time Analytics',
      description: 'Get insights into your data with powerful analytics and visualization tools.'
    },
    {
      icon: Users,
      title: 'User Management',
      description: 'Complete user management system with role-based access control.'
    },
    {
      icon: Zap,
      title: 'Lightning Fast',
      description: 'Built with modern technologies for optimal performance and speed.'
    }
  ]

  const stats = [
    { label: 'Active Users', value: '1,234' },
    { label: 'Data Processed', value: '98.7K' },
    { label: 'Uptime', value: '99.9%' },
    { label: 'Support', value: '24/7' }
  ]

  return (
    <div className="space-y-16">
      {/* Hero Section */}
      <section className="relative overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
          <div className="text-center">
            <h1 className="text-4xl font-extrabold text-rcc-gray-900 sm:text-5xl md:text-6xl">
              <span className="block">Welcome to RCC</span>
              <span className="block text-rcc-red">System Management</span>
            </h1>
            <p className="mt-3 max-w-md mx-auto text-base text-rcc-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
              A modern, responsive web application built with React, TypeScript, and Tailwind CSS. 
              Experience the perfect blend of Red, Cyan, and Gray design system.
            </p>
            <div className="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
              <div className="rounded-md shadow">
                <Link
                  to="/dashboard"
                  className="btn btn-primary inline-flex items-center px-8 py-3 text-base"
                >
                  Get Started
                  <ArrowRight className="ml-2 h-5 w-5" />
                </Link>
              </div>
              <div className="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                <Link
                  to="/register"
                  className="btn btn-outline inline-flex items-center px-8 py-3 text-base"
                >
                  Create Account
                </Link>
              </div>
            </div>
          </div>
        </div>
        
        {/* Decorative wave */}
        <div className="absolute bottom-0 left-0 right-0">
          <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="#DC2626" fillOpacity="0.1"/>
          </svg>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <h2 className="text-3xl font-extrabold text-rcc-gray-900 sm:text-4xl">
              Powerful Features
            </h2>
            <p className="mt-4 max-w-2xl mx-auto text-lg text-rcc-gray-500">
              Everything you need to manage your system efficiently with our RCC design system.
            </p>
          </div>

          <div className="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            {features.map((feature) => {
              const Icon = feature.icon
              return (
                <div key={feature.title} className="card text-center hover:shadow-md transition-shadow duration-200">
                  <div className="flex justify-center">
                    <div className="flex items-center justify-center h-12 w-12 rounded-md bg-rcc-cyan text-white">
                      <Icon className="h-6 w-6" />
                    </div>
                  </div>
                  <h3 className="mt-4 text-lg font-medium text-rcc-gray-900">{feature.title}</h3>
                  <p className="mt-2 text-sm text-rcc-gray-500">{feature.description}</p>
                </div>
              )
            })}
          </div>
        </div>
      </section>

      {/* Stats Section */}
      <section className="py-16 bg-rcc-gray-900">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 gap-8 md:grid-cols-4">
            {stats.map((stat) => (
              <div key={stat.label} className="text-center">
                <div className="text-3xl font-extrabold text-white sm:text-4xl">
                  {stat.value}
                </div>
                <div className="mt-2 text-sm font-medium text-rcc-gray-300 uppercase tracking-wide">
                  {stat.label}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 bg-rcc-red">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-extrabold text-white sm:text-4xl">
            Ready to get started?
          </h2>
          <p className="mt-4 text-lg text-red-100">
            Join thousands of users who trust RCC System for their management needs.
          </p>
          <div className="mt-8 flex justify-center">
            <Link
              to="/dashboard"
              className="btn btn-outline bg-white text-rcc-red hover:bg-rcc-gray-50 inline-flex items-center px-8 py-3 text-lg font-medium"
            >
              <CheckCircle className="mr-2 h-5 w-5" />
              Start Now
            </Link>
          </div>
        </div>
      </section>
    </div>
  )
}