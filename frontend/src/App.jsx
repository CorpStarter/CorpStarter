import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { Toaster } from 'react-hot-toast'; 
import { useAuth } from './context/AuthContext';
import Login from './pages/Login';
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

function App() {
  const { user } = useAuth();

  return (
    <Router>
      <Toaster 
        position="top-right"
        toastOptions={{
          style: {
            background: '#0f172a', // slate-950
            color: '#fff',
            border: '1px solid #1e293b', // slate-800
            borderRadius: '12px',
            fontWeight: 'bold',
          },
          success: { 
            iconTheme: { primary: '#10b981', secondary: '#fff' } 
          },
          error: { 
            iconTheme: { primary: '#f43f5e', secondary: '#fff' } 
          },
        }}
      />

      <Routes>
        <Route path="/login" element={
          user ? (user.role === 'Admin' ? <Navigate to="/admin" /> : <Navigate to="/dashboard" />) : <Login />
        } />
        
        {/* --- FRONT EMPLOYÉ --- */}
        <Route path="/dashboard" element={<ProtectedRoute><Dashboard /></ProtectedRoute>} />
        <Route path="/profile" element={<ProtectedRoute><Profile /></ProtectedRoute>} />
        <Route path="/create-project" element={<ProtectedRoute><CreateProject /></ProtectedRoute>} />
        <Route path="/edit-project/:id" element={<ProtectedRoute><EditProject /></ProtectedRoute>} />
        
        {/* --- FRONT DIRECTION (Cloisonné) --- */}
        <Route path="/admin" element={<AdminRoute><AdminDashboard /></AdminRoute>} />
        
        <Route path="*" element={<Navigate to="/login" />} />
      </Routes>
    </Router>
  );
}

export default App;