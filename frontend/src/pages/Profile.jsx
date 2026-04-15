import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { motion } from 'framer-motion';
import { ArrowLeft, User, Mail, Briefcase, ShieldCheck, Award, LogOut } from 'lucide-react';

export default function Profile() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  // Ce sont des données par défaut en attendant que le backend renvoie le vrai nom lors du login
  const profileData = {
    firstName: user?.first_name || "Employé",
    lastName: user?.last_name || "CorpStarter",
    username: user?.username || "utilisateur_pro",
    email: user?.email || "employe@entreprise.com",
    role: user?.role === 'Admin' ? "Direction & Management" : "Innovateur (Employé)",
    joinDate: "Avril 2026"
  };

  return (
    <div className="min-h-screen bg-slate-950 font-sans text-slate-300 selection:bg-indigo-500/30 py-12 px-4 sm:px-6">
      <div className="max-w-3xl mx-auto">
        
        <button onClick={() => navigate(user?.role === 'Admin' ? '/admin' : '/dashboard')} className="flex items-center text-slate-500 hover:text-indigo-400 mb-8 transition-colors font-bold text-sm uppercase tracking-wider">
          <ArrowLeft className="mr-2 h-4 w-4" /> Retour au tableau de bord
        </button>

        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          className="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl shadow-black relative"
        >
          {/* Header de profil / Bannière */}
          <div className="h-32 bg-gradient-to-r from-indigo-600 to-blue-600 relative overflow-hidden">
            <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
          </div>

          <div className="px-8 pb-8 relative">
            {/* Avatar qui chevauche la bannière */}
            <div className="flex justify-between items-end -mt-12 mb-6">
              <div className="h-24 w-24 rounded-2xl bg-slate-800 border-4 border-slate-900 flex items-center justify-center shadow-xl">
                <span className="text-3xl font-black text-indigo-400">
                  {profileData.firstName[0]}{profileData.lastName[0]}
                </span>
              </div>
              
              {user?.role === 'Admin' && (
                <div className="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-2 rounded-xl flex items-center gap-2 font-bold text-sm shadow-inner mb-2">
                  <ShieldCheck className="h-4 w-4" /> Compte Certifié
                </div>
              )}
            </div>

            {/* Infos principales */}
            <div className="mb-10">
              <h2 className="text-3xl font-black text-white flex items-center gap-3">
                {profileData.firstName} {profileData.lastName}
              </h2>
              <p className="text-indigo-400 font-medium flex items-center gap-2 mt-1">
                @{profileData.username}
              </p>
            </div>

            {/* Grille de détails */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400">
                  <Mail className="h-5 w-5" />
                </div>
                <div>
                  <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Email pro</p>
                  <p className="text-sm text-slate-200 font-medium">{profileData.email}</p>
                </div>
              </div>

              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400">
                  <Briefcase className="h-5 w-5" />
                </div>
                <div>
                  <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Poste & Rôle</p>
                  <p className="text-sm text-slate-200 font-medium">{profileData.role}</p>
                </div>
              </div>

              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400">
                  <User className="h-5 w-5" />
                </div>
                <div>
                  <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">ID Interne</p>
                  <p className="text-sm text-slate-200 font-medium">CS-{user?.id || "0000"}</p>
                </div>
              </div>

              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400">
                  <Award className="h-5 w-5" />
                </div>
                <div>
                  <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Ancienneté</p>
                  <p className="text-sm text-slate-200 font-medium">Membre depuis {profileData.joinDate}</p>
                </div>
              </div>
            </div>

            {/* Actions */}
            <div className="border-t border-slate-800 pt-8 flex justify-end">
              <button 
                onClick={logout}
                className="bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 border border-rose-500/20 hover:border-rose-500/50 px-6 py-3 rounded-xl font-bold flex items-center gap-2 transition-all"
              >
                <LogOut className="h-5 w-5" /> Déconnexion sécurisée
              </button>
            </div>

          </div>
        </motion.div>
      </div>
    </div>
  );
}
