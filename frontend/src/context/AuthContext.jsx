import { createContext, useContext, useState, useEffect } from 'react';
import apiClient from '../api/axios';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('token');
    const id = localStorage.getItem('userId');
    const role = localStorage.getItem('role');
    const balance = localStorage.getItem('balance');
    
    if (token && id) {
      setUser({ id: parseInt(id), token, role, balance: parseFloat(balance || 0) });
    }
    setLoading(false);
  }, []);

  const login = async (email, password) => {
    try {
      const response = await apiClient.post('/auth/login', { email, password });
      const { token, user_id, balance = 500000 } = response.data;
      
      const role = email.includes('direction') ? 'Admin' : 'User';
      
      localStorage.setItem('token', token);
      localStorage.setItem('userId', user_id);
      localStorage.setItem('role', role);
      localStorage.setItem('balance', balance);
      
      setUser({ id: parseInt(user_id), token, role, balance });
      return { success: true };
    } catch (error) {
      return { success: false, error: error.response?.data?.error || "Erreur" };
    }
  };

  const logout = () => {
    localStorage.clear();
    setUser(null);
  };

  const updateBalance = (newBalance) => {
    setUser(prev => ({ ...prev, balance: newBalance }));
    localStorage.setItem('balance', newBalance);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout, loading, updateBalance }}>
      {!loading && children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);