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
    
    const nom = localStorage.getItem('nom');       
    const prenom = localStorage.getItem('prenom'); 
    
    if (token && id) {
      setUser({ 
        id: parseInt(id), 
        token, 
        role, 
        nom,       
        prenom,    
        balance: parseFloat(balance || 0) 
      });
    }
    setLoading(false);
  }, []);

  const register = async (userData) => {
    try {
      const response = await apiClient.post('/auth/register', userData);
      return { success: true, message: response.data.message };
    } catch (error) {
      return { success: false, error: error.response?.data?.error || "Erreur d'inscription" };
    }
  };

  const login = async (email, password) => {
    try {
      const response = await apiClient.post('/auth/login', { email, password });
      
      const { token, user_id, role, nom, prenom, balance = 500000 } = response.data;
      
      localStorage.setItem('token', token);
      localStorage.setItem('userId', user_id);
      localStorage.setItem('role', role);
      localStorage.setItem('balance', balance);
      
      if (nom) localStorage.setItem('nom', nom);       
      if (prenom) localStorage.setItem('prenom', prenom); 
      localStorage.setItem('email', email);
      
      setUser({ id: parseInt(user_id), token, role, nom, prenom, email, balance });
      return { success: true };
    } catch (error) {
      return { success: false, error: error.response?.data?.error || "Erreur de connexion" };
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
    <AuthContext.Provider value={{ user, login, register, logout, loading, updateBalance }}>
      {!loading && children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);