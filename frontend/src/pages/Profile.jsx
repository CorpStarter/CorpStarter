import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useQuery } from '@tanstack/react-query';
import { getProjects } from '../api/projectService';
import { motion } from 'framer-motion';
import { ArrowLeft, User, Mail, Briefcase, ShieldCheck, Award, LogOut, Lightbulb, Loader2, Trophy, Clock, Edit3 } from 'lucide-react';

export default function Profile() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const profileData = {
    firstName: user?.prenom || "Employé",
    lastName: user?.nom || "CorpStarter",
    username: user?.username || `${user?.prenom || 'user'}_${user?.nom || 'pro'}`,
    email: user?.email || localStorage.getItem('email') || "employe@entreprise.com",
    role: user?.role === 'Admin' ? "Direction & Management" : "Innovateur (Employé)",
    joinDate: "Avril 2026"
  };

  const { data, isLoading } = useQuery({ 
    queryKey: ['projects'], 
    queryFn: getProjects 
  });

  const myProjects = data?.projects?.filter(p => 
    p.requester === profileData.username || 
    (user?.nom && p.requester?.toLowerCase().includes(user.nom.toLowerCase()))
  ) || [];

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
          <div className="h-32 bg-gradient-to-r from-indigo-600 to-blue-600 relative overflow-hidden">
            <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
          </div>

          <div className="px-8 pb-8 relative">
            <div className="flex justify-between items-end -mt-12 mb-6">
              <div className="h-24 w-24 rounded-2xl bg-slate-800 border-4 border-slate-900 flex items-center justify-center shadow-xl">
                <span className="text-3xl font-black text-indigo-400 uppercase">
                  {profileData.firstName[0]}{profileData.lastName[0]}
                </span>
              </div>
              
              {user?.role === 'Admin' && (
                <div className="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-2 rounded-xl flex items-center gap-2 font-bold text-sm shadow-inner mb-2">
                  <ShieldCheck className="h-4 w-4" /> Compte Certifié
                </div>
              )}
            </div>

            <div className="mb-10">
              <h2 className="text-3xl font-black text-white flex items-center gap-3 capitalize">
                {profileData.firstName} {profileData.lastName}
              </h2>
              <p className="text-indigo-400 font-medium flex items-center gap-2 mt-1 lowercase">
                @{profileData.username}
              </p>
            </div>

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

            <div className="mb-10">
              <h3 className="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <Lightbulb className="h-4 w-4 text-indigo-400" /> Mes Initiatives ({myProjects.length})
              </h3>

              {isLoading ? (
                <div className="flex justify-center py-6"><Loader2 className="animate-spin h-6 w-6 text-indigo-500" /></div>
              ) : myProjects.length > 0 ? (
                <div className="space-y-3">
                  {myProjects.map((project) => (
                    <div key={project.id} className="bg-slate-950/50 border border-slate-800/50 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-indigo-500/30 transition-colors">
                      <div>
                        <h4 className="text-white font-bold text-base">{project.name}</h4>
                        <p className="text-xs text-slate-500 mt-1">Budget demandé : {project.requested_budget} €</p>
                      </div>
                      
                      <div className="flex flex-wrap items-center gap-3">
                        {/* Statut du projet */}
                        {project.status === 'Approved' ? (
                          <span className="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-lg text-xs font-bold uppercase flex items-center gap-1">
                            <Trophy className="h-3 w-3" /> Validé
                          </span>
                        ) : project.status === 'Rejected' ? (
                          <span className="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-3 py-1 rounded-lg text-xs font-bold uppercase flex items-center gap-1">
                            Refusé
                          </span>
                        ) : (
                          <span className="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded-lg text-xs font-bold uppercase flex items-center gap-1">
                            <Clock className="h-3 w-3" /> En attente
                          </span>
                        )}

                        {(project.status === 'Pending' || project.status_id === 1) && (
                          <button 
                            onClick={() => navigate(`/edit-project/${project.id}`, { state: { project } })}
                            className="bg-indigo-600/10 hover:bg-indigo-600/20 text-indigo-400 border border-indigo-500/20 px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors"
                          >
                            <Edit3 className="h-3 w-3" /> Modifier
                          </button>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="bg-slate-950/30 border border-dashed border-slate-800 rounded-2xl p-6 text-center">
                  <p className="text-slate-500 text-sm font-medium">Vous n'avez pas encore soumis de projet.</p>
                  <button onClick={() => navigate('/create-project')} className="text-indigo-400 hover:text-indigo-300 text-sm font-bold mt-2 transition-colors">
                    + Lancer votre première idée
                  </button>
                </div>
              )}
            </div>

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