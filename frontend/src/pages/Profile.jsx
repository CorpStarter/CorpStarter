import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getProjects, deleteProject } from '../api/projectService';
import { motion } from 'framer-motion';
import { ArrowLeft, User, Mail, Briefcase, ShieldCheck, Award, LogOut, Lightbulb, Loader2, Trophy, Clock, Edit3, Trash2, Bug } from 'lucide-react';
import toast from 'react-hot-toast';

export default function Profile() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const [debugMode, setDebugMode] = useState(false);

  const localUsername = localStorage.getItem('username') || '';
  const localPrenom = localStorage.getItem('prenom') || '';
  const localNom = localStorage.getItem('nom') || '';

  const profileData = {
    firstName: user?.prenom || localPrenom || "Employé",
    lastName: user?.nom || localNom || "CorpStarter",
    username: user?.username || localUsername || "utilisateur",
    email: user?.email || localStorage.getItem('email') || "employe@entreprise.com",
    role: user?.role === 'Admin' ? "Direction & Management" : "Innovateur (Employé)",
    joinDate: "Avril 2026"
  };

  const { data, isLoading } = useQuery({ 
    queryKey: ['projects'], 
    queryFn: getProjects 
  });

  const deleteMutation = useMutation({
    mutationFn: (id) => deleteProject(id),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success("Initiative supprimée.");
    }
  });

  const myProjects = data?.projects?.filter(p => {
    if (p.requester_id && user?.id) {
      return p.requester_id === user.id;
    }

    if (!p.requester) return false;
    const req = p.requester.toLowerCase();
    const currentUsername = profileData.username.toLowerCase();
    
    if (currentUsername && currentUsername !== "utilisateur" && req === currentUsername) {
      return true;
    }

    const fName = profileData.firstName.toLowerCase();
    const lName = profileData.lastName.toLowerCase();
    if (fName !== "employé" && req.includes(fName)) return true;
    if (lName !== "corpstarter" && req.includes(lName)) return true;
    
    return false;
  }) || [];

  // Si on est en mode debug, on affiche TOUT. Sinon, on affiche tes projets.
  const displayedProjects = debugMode ? (data?.projects || []) : myProjects;

  return (
    <div className="min-h-screen bg-slate-950 font-sans text-slate-300 py-12 px-4">
      <div className="max-w-3xl mx-auto">
        <button onClick={() => navigate(user?.role === 'Admin' ? '/admin' : '/dashboard')} className="flex items-center text-slate-500 hover:text-indigo-400 mb-8 transition-colors font-bold text-sm uppercase">
          <ArrowLeft className="mr-2 h-4 w-4" /> Retour au tableau de bord
        </button>

        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl relative">
          <div className="h-32 bg-gradient-to-r from-indigo-600 to-blue-600 relative">
             <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
          </div>

          <div className="px-8 pb-8 relative">
            <div className="flex justify-between items-end -mt-12 mb-6">
              <div className="h-24 w-24 rounded-2xl bg-slate-800 border-4 border-slate-900 flex items-center justify-center shadow-xl font-black text-3xl text-indigo-400 uppercase">
                {profileData.firstName[0]}{profileData.lastName[0]}
              </div>
              
              <div className="flex items-center gap-3">
                {user?.role === 'Admin' && (
                  <div className="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-2 rounded-xl flex items-center gap-2 font-bold text-sm shadow-inner mb-2">
                    <ShieldCheck className="h-4 w-4" /> Compte Certifié
                  </div>
                )}
                {/* <button 
                  onClick={() => setDebugMode(!debugMode)} 
                  className={`p-3 rounded-xl mb-2 transition-all font-bold text-xs flex items-center gap-2 ${debugMode ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700'}`} 
                >
                  <Bug className="h-4 w-4" /> {debugMode ? "Désactiver le Débug" : "Activer le Débug"}
                </button> */}
              </div>
            </div>

            <div className="mb-8">
              <h2 className="text-3xl font-black text-white capitalize">{profileData.firstName} {profileData.lastName}</h2>
              <p className="text-indigo-400 font-medium lowercase">@{profileData.username}</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400"><Mail className="h-5 w-5" /></div>
                <div><p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Email pro</p><p className="text-sm text-slate-200 font-medium">{profileData.email}</p></div>
              </div>
              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400"><Briefcase className="h-5 w-5" /></div>
                <div><p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Poste & Rôle</p><p className="text-sm text-slate-200 font-medium">{profileData.role}</p></div>
              </div>
              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400"><User className="h-5 w-5" /></div>
                <div><p className="text-xs font-bold text-slate-500 uppercase tracking-widest">ID Interne</p><p className="text-sm text-slate-200 font-medium">CS-{user?.id || "0000"}</p></div>
              </div>
              <div className="bg-slate-950/50 p-5 rounded-2xl border border-slate-800/50 flex items-center gap-4">
                <div className="bg-indigo-500/10 p-3 rounded-xl text-indigo-400"><Award className="h-5 w-5" /></div>
                <div><p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Ancienneté</p><p className="text-sm text-slate-200 font-medium">Membre depuis {profileData.joinDate}</p></div>
              </div>
            </div>

            <div className="mb-10">
              <h3 className="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <Lightbulb className="h-4 w-4 text-indigo-400" /> {debugMode ? "MODE DÉBUG ACTIVÉ (TOUS LES PROJETS)" : `Mes Initiatives (${myProjects.length})`}
              </h3>

              {isLoading ? <Loader2 className="animate-spin mx-auto text-indigo-500 my-6" /> : (
                <div className="space-y-3">
                  {displayedProjects.map((project) => (
                    <div key={project.id} className={`bg-slate-950/50 border rounded-2xl p-4 flex flex-col gap-4 transition-colors ${debugMode ? 'border-rose-500/30' : 'border-slate-800 hover:border-indigo-500/30'}`}>
                      
                      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                          <h4 className="text-white font-bold text-base">{project.name}</h4>
                          <p className="text-xs text-slate-500 mt-1">Budget : {project.requested_budget} €</p>
                        </div>
                        
                        <div className="flex flex-wrap items-center gap-3">
                          {project.status === 'Approved' ? (
                            <span className="bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-lg text-xs font-bold uppercase flex items-center gap-1"><Trophy className="h-3 w-3" /> Validé</span>
                          ) : project.status === 'Rejected' ? (
                            <span className="bg-rose-500/10 text-rose-400 px-3 py-1 rounded-lg text-xs font-bold uppercase flex items-center gap-1">Refusé</span>
                          ) : (
                            <span className="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-lg text-xs font-bold uppercase flex items-center gap-1"><Clock className="h-3 w-3" /> En attente</span>
                          )}

                          {(project.status === 'Pending' || project.status_id === 1 || debugMode) && (
                            <>
                              <button onClick={() => navigate(`/edit-project/${project.id}`, { state: { project } })} className="p-2 bg-indigo-500/10 text-indigo-400 rounded-lg hover:bg-indigo-500 hover:text-white transition-all"><Edit3 className="h-4 w-4" /></button>
                              <button onClick={() => { if(window.confirm('Supprimer définitivement ce projet ?')) deleteMutation.mutate(project.id); }} disabled={deleteMutation.isPending} className="p-2 bg-rose-500/10 text-rose-400 rounded-lg hover:bg-rose-500 hover:text-white transition-all disabled:opacity-50"><Trash2 className="h-4 w-4" /></button>
                            </>
                          )}
                        </div>
                      </div>

                      {/* LE PANNEAU DE DIAGNOSTIC ROUGE */}
                      {debugMode && (
                        <div className="bg-rose-500/10 border border-rose-500/20 p-4 rounded-xl mt-2 font-mono text-[11px] text-rose-300">
                          <p className="font-bold text-rose-500 mb-1 border-b border-rose-500/20 pb-1">🔎 POURQUOI CE PROJET NE S'AFFICHE PAS ? Compare ces lignes :</p>
                          <p>1. Le Backend dit que l'auteur est : <strong className="text-white">"{project.requester}"</strong></p>
                          <p>2. Ton pseudo sur cette page est : <strong className="text-white">"{profileData.username}"</strong></p>
                          <br/>
                          <p>3. Le Backend dit que l'ID de l'auteur est : <strong className="text-white">{project.requester_id ? project.requester_id : "NON FOURNI (null)"}</strong></p>
                          <p>4. Ton ID sur cette page est : <strong className="text-white">{user?.id}</strong></p>
                        </div>
                      )}

                    </div>
                  ))}
                  {displayedProjects.length === 0 && (
                    <div className="bg-slate-950/30 border border-dashed border-slate-800 rounded-2xl p-6 text-center">
                      <p className="text-slate-500 text-sm font-medium mb-2">Aucun projet trouvé.</p>
                      <button onClick={() => navigate('/create-project')} className="text-indigo-400 font-bold text-sm hover:underline">+ Lancer une idée</button>
                    </div>
                  )}
                </div>
              )}
            </div>
            
            <div className="border-t border-slate-800 pt-6 flex justify-end">
               <button onClick={logout} className="text-rose-500 font-bold flex items-center gap-2 hover:bg-rose-500/10 px-4 py-2 rounded-xl transition-all"><LogOut className="h-4 w-4" /> Déconnexion</button>
            </div>
          </div>
        </motion.div>
      </div>
    </div>
  );
}