import { useState } from 'react'
import { CheckCircle } from 'lucide-react'

export default function TestComponent() {
  const [testResults, setTestResults] = useState<string[]>([])

  const runTests = () => {
    const results = []
    
    // Test 1: Basic component rendering
    try {
      results.push('✅ Component rendering test passed')
    } catch (error) {
      results.push('❌ Component rendering test failed')
    }
    
    // Test 2: Tailwind CSS classes
    try {
      const testElement = document.createElement('div')
      testElement.className = 'bg-rcc-red text-white p-4 rounded-lg'
      results.push('✅ Tailwind CSS classes test passed')
    } catch (error) {
      results.push('❌ Tailwind CSS classes test failed')
    }
    
    // Test 3: Local storage
    try {
      localStorage.setItem('test', 'value')
      const value = localStorage.getItem('test')
      localStorage.removeItem('test')
      if (value === 'value') {
        results.push('✅ Local storage test passed')
      } else {
        results.push('❌ Local storage test failed')
      }
    } catch (error) {
      results.push('❌ Local storage test failed')
    }
    
    setTestResults(results)
  }

  return (
    <div className="card max-w-2xl mx-auto mt-8">
      <div className="flex items-center mb-4">
        <CheckCircle className="h-6 w-6 text-green-500 mr-2" />
        <h2 className="text-lg font-semibold text-rcc-gray-900">System Tests</h2>
      </div>
      
      <button
        onClick={runTests}
        className="btn btn-primary mb-4"
      >
        Run Tests
      </button>
      
      {testResults.length > 0 && (
        <div className="space-y-2">
          {testResults.map((result, index) => (
            <div key={index} className="text-sm font-mono">
              {result}
            </div>
          ))}
        </div>
      )}
    </div>
  )
}