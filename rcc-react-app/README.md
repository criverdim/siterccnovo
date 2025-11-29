# RCC System - React Application

A modern, responsive web application built with React, TypeScript, and Tailwind CSS, implementing the RCC (Red, Cyan, Cinza) design system.

## Features

- **Authentication System**: Local authentication with role-based access (User/Admin)
- **Responsive Design**: Desktop-first responsive design with breakpoints at 1440px, 1024px, 768px
- **RCC Design System**: Custom color palette with Red (#DC2626), Cyan (#0891B2), and Gray tones
- **Modern UI Components**: Card-based layouts, rounded buttons, 8px grid system, 2px outline icons
- **Smooth Animations**: 200-300ms transitions with natural easing
- **Accessibility**: WCAG 2.1 AA contrast, keyboard navigation, screen reader support

## Pages

- **Homepage**: Hero section with features, stats, and call-to-action
- **Dashboard**: Analytics with stats cards, charts placeholder, and recent activities
- **Profile**: Editable user profile with form validation
- **Settings**: Theme switcher, notification preferences, and privacy settings
- **Admin Panel**: User management with role assignment and status controls

## Tech Stack

- **Frontend**: React 18 with TypeScript
- **Build Tool**: Vite
- **Styling**: Tailwind CSS with custom RCC color palette
- **Icons**: Lucide React
- **Routing**: React Router DOM
- **State Management**: React Context + useReducer
- **Local Storage**: For authentication and settings persistence

## Getting Started

### Prerequisites

- Node.js 20.19.2 or higher
- npm or pnpm

### Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   npm install
   ```

3. Start the development server:
   ```bash
   npm run dev
   ```

4. Open your browser and navigate to `http://localhost:5173`

### Demo Credentials

- **User Account**: `user@rcc.com` (any password)
- **Admin Account**: `admin@rcc.com` (any password)

## Available Scripts

- `npm run dev` - Start development server
- `npm run build` - Build for production
- `npm run lint` - Run ESLint
- `npm run preview` - Preview production build

## Project Structure

```
src/
├── components/     # Reusable UI components
├── contexts/       # React contexts (Auth, Theme)
├── hooks/          # Custom React hooks
├── pages/          # Page components
├── types/          # TypeScript type definitions
├── utils/          # Utility functions
└── App.tsx         # Main application component
```

## Design System

### Colors

- **Primary Red**: `#DC2626` - Actions, buttons, highlights
- **Secondary Cyan**: `#0891B2` - Links, hover states, accents
- **Gray Scale**: Various shades from `#F9FAFB` to `#111827` for backgrounds and text

### Typography

- **Font Family**: Inter (Google Fonts)
- **Font Sizes**: Limited to 4-5 sizes for consistent hierarchy
- **Font Weights**: 300, 400, 500, 600, 700

### Spacing

- **Grid System**: 8px base unit
- **Padding/Margins**: Multiples of 4 for visual rhythm

### Components

- **Buttons**: Rounded corners, consistent padding, hover transitions
- **Cards**: White background, subtle shadows, rounded corners
- **Forms**: Consistent input styling with focus states
- **Navigation**: Responsive navigation with mobile menu

## Authentication

The application uses a local authentication system with:

- **User Registration**: Email-based registration
- **Role-Based Access**: User and Admin roles
- **Local Storage**: Persistent authentication state
- **Protected Routes**: Route guards based on authentication status

## Responsive Design

The application is built with a desktop-first approach:

- **Desktop**: 1440px and above
- **Tablet**: 1024px to 1439px
- **Mobile**: 768px and below

## Accessibility

- **WCAG 2.1 AA**: Color contrast compliance
- **Keyboard Navigation**: Full keyboard accessibility
- **Screen Reader**: ARIA labels and semantic HTML
- **Focus States**: Visible focus indicators

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.