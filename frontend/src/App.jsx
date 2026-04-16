import { BrowserRouter as Router, Routes, Route, Navigate, useLocation } from 'react-router-dom';
import { Toaster } from 'react-hot-toast'; 
import { useAuth } from './context/AuthContext';
import { AnimatePresence, motion } from 'framer-motion';

import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/Dashboard';
import CreateProject from './pages/CreateProject';
import EditProject from './pages/EditProject'; 
import AdminDashboard from './pages/AdminDashboard'; 
import Profile from './pages/Profile';

const ProtectedRoute = ({ children }) => {
  const { user, loading } = useAuth();
  if (loading) return <div>Chargement...</div>;
  if (!user) return <Navigate to="/login" />;
  return children;
};

const AdminRoute = ({ children }) => {
  const { user, loading } = useAuth();
  if (loading) return <div>Chargement...</div>;
  if (!user || user.role !== 'Admin') return <Navigate to="/dashboard" />;
  return children;
};

const PageWrapper = ({ children }) => (
  <motion.div
    initial={{ opacity: 0, y: 10, filter: "blur(4px)" }}
    animate={{ opacity: 1, y: 0, filter: "blur(0px)" }}
    exit={{ opacity: 0, y: -10, filter: "blur(4px)" }}
    transition={{ duration: 0.3 }}
  >
    {children}
  </motion.div>
);

const AnimatedRoutes = () => {
  const location = useLocation();
  const { user } = useAuth();

  return (
    <AnimatePresence mode="wait">
      <Routes location={location} key={location.pathname}>
        
        <Route path="/login" element={<PageWrapper>{user ? (user.role === 'Admin' ? <Navigate to="/admin" /> : <Navigate to="/dashboard" />) : <Login />}</PageWrapper>} />
        <Route path="/register" element={<PageWrapper>{user ? <Navigate to="/dashboard" /> : <Register />}</PageWrapper>} />
        
        <Route path="/dashboard" element={<ProtectedRoute><PageWrapper><Dashboard /></PageWrapper></ProtectedRoute>} />
        <Route path="/profile" element={<ProtectedRoute><PageWrapper><Profile /></PageWrapper></ProtectedRoute>} />
        <Route path="/create-project" element={<ProtectedRoute><PageWrapper><CreateProject /></PageWrapper></ProtectedRoute>} />
        
        <Route path="/edit-project/:id" element={<ProtectedRoute><PageWrapper><EditProject /></PageWrapper></ProtectedRoute>} />
        
        <Route path="/admin" element={<AdminRoute><PageWrapper><AdminDashboard /></PageWrapper></AdminRoute>} />
        
        <Route path="*" element={<Navigate to="/login" />} />
      </Routes>
    </AnimatePresence>
  );
};

export default function App() {
  return (
    <Router>
      <Toaster 
        position="top-right"
        toastOptions={{
          style: { background: '#0f172a', color: '#fff', border: '1px solid #1e293b', borderRadius: '12px', fontWeight: 'bold' },
          success: { iconTheme: { primary: '#10b981', secondary: '#fff' } },
          error: { iconTheme: { primary: '#f43f5e', secondary: '#fff' } },
        }}
      />
      <AnimatedRoutes />
    </Router>
  );
}