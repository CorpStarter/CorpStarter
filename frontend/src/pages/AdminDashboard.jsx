import { useState, useMemo } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getProjects, updateProjectAdmin, getJoinedUsers } from '../api/projectService'; 
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';
import { Loader2, LogOut, ShieldCheck, Check, X, Eye, Users, Trophy, Lightbulb, Image as ImageIcon, Filter } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import toast from 'react-hot-toast';

export default function AdminDashboard() {
  const { user, logout, updateBalance } = useAuth();
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  
  const [activeTab, setActiveTab] = useState('pending'); // 'pending' ou 'ongoing'
  const [budgetFilter, setBudgetFilter] = useState('all');
  const [selectedProject, setSelectedProject] = useState(null);
  const [allocations, setAllocations] = useState({});

  const { data, isLoading } = useQuery({ queryKey: ['projects'], queryFn: getProjects });

  const { data: joinedData } = useQuery({
    queryKey: ['joined-users', selectedProject?.id],
    queryFn: () => getJoinedUsers(selectedProject.id),
    enabled: !!selectedProject, 
  });

  const allocateMutation = useMutation({
    mutationFn: ({ id, amount }) => updateProjectAdmin(id, 'Approved', amount),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries(['projects']);
      if (user?.balance) updateBalance(user.balance - variables.amount);
      toast.success("Budget alloué et projet mis à jour !");
      setSelectedProject(null);
    },
    onError: (err) => toast.error(err.response?.data?.error || "Erreur lors de l'allocation.")
  });

  const rejectMutation = useMutation({
    mutationFn: (id) => updateProjectAdmin(id, 'Rejected', null),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success("Projet refusé définitivement.");
      setSelectedProject(null);
    }
  });

  const displayedProjects = useMemo(() => {
    let projects = data?.projects || [];
    
    projects.sort((a, b) => new Date(b.creation_date) - new Date(a.creation_date));

    if (activeTab === 'pending') {
      projects = projects.filter(p => p.status === 'Pending' || p.status_id === 1);
    } else {
      projects = projects.filter(p => p.status === 'Approved' || p.status === 'In Progress' || p.status_id === 2);
    }

    if (budgetFilter === 'low') projects = projects.filter(p => parseFloat(p.requested_budget) < 500);
    if (budgetFilter === 'mid') projects = projects.filter(p => parseFloat(p.requested_budget) >= 500 && parseFloat(p.requested_budget) <= 2000);
    if (budgetFilter === 'high') projects = projects.filter(p => parseFloat(p.requested_budget) > 2000);

    return projects;
  }, [data, activeTab, budgetFilter]);

  const containerVariants = {
    hidden: { opacity: 0 },
    show: {
      opacity: 1,
      transition: { staggerChildren: 0.1 }
    }
  };

  const cardVariants = {
    hidden: { opacity: 0, y: 20 },
    show: { opacity: 1, y: 0, transition: { type: "spring", stiffness: 300, damping: 24 } }
  };

  return (
    <div className="min-h-screen bg-slate-950 font-sans text-slate-300 selection:bg-emerald-500/30">
      <nav className="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 shadow-sm shadow-black/50 backdrop-blur-md bg-opacity-90">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
          <div className="flex items-center gap-3">
            <div className="bg-gradient-to-br from-emerald-500 to-teal-600 p-2 rounded-xl shadow-lg shadow-emerald-500/20">
              <ShieldCheck className="h-5 w-5 text-white" />
            </div>
            <h1 className="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-wide">
              Direction <span className="text-emerald-500">Hub</span>
            </h1>
          </div>
          
          <div className="flex items-center space-x-6">
            <div className="flex flex-col items-end pr-6 border-r border-slate-700">
              <span className="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Trésorerie</span>
              <span className="text-lg font-black text-emerald-400">{user?.balance || 500000} €</span>
            </div>
            
            <button onClick={() => navigate('/profile')} className="text-slate-400 hover:text-white transition-all flex items-center gap-2 text-sm font-bold bg-slate-800/50 px-4 py-2 rounded-xl border border-transparent hover:border-slate-700">
              <Users className="h-4 w-4" /> Profil
            </button>
            <button onClick={logout} className="text-slate-400 hover:text-rose-500 transition-colors flex items-center gap-2 text-sm font-bold">
               <LogOut className="h-4 w-4" /> Quitter
            </button>
          </div>
        </div>
      </nav>

      <main className="max-w-7xl mx-auto px-4 py-10">
        <div className="mb-10">
          <h2 className="text-4xl font-black text-white tracking-tight mb-2">Espace Décisionnel</h2>
          <p className="text-slate-400 text-lg">Gérez le financement des innovations de l'entreprise.</p>
        </div>

        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 border-b border-slate-800 pb-4">
          <div className="flex gap-6">
            <button 
              onClick={() => setActiveTab('pending')}
              className={`pb-2 font-bold text-sm flex items-center gap-2 transition-colors border-b-2 relative ${activeTab === 'pending' ? 'border-emerald-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300'}`}
            >
              <Lightbulb className={`h-5 w-5 ${activeTab === 'pending' ? 'text-emerald-400' : ''}`} /> 
              Nouveaux dossiers
              {activeTab === 'pending' && <span className="absolute -bottom-[2px] left-0 w-full h-[2px] bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]" />}
            </button>
            <button 
              onClick={() => setActiveTab('ongoing')}
              className={`pb-2 font-bold text-sm flex items-center gap-2 transition-colors border-b-2 relative ${activeTab === 'ongoing' ? 'border-indigo-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300'}`}
            >
              <Trophy className={`h-5 w-5 ${activeTab === 'ongoing' ? 'text-indigo-400' : ''}`} /> 
              Projets financés
              {activeTab === 'ongoing' && <span className="absolute -bottom-[2px] left-0 w-full h-[2px] bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]" />}
            </button>
          </div>

          <div className="flex items-center gap-2 bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 ml-auto shadow-inner">
            <Filter className="h-4 w-4 text-emerald-400" />
            <select value={budgetFilter} onChange={(e) => setBudgetFilter(e.target.value)} className="bg-transparent text-sm text-slate-300 font-bold outline-none cursor-pointer">
              <option value="all">Tous les budgets</option>
              <option value="low">Moins de 500 €</option>
              <option value="mid">500 € - 2000 €</option>
              <option value="high">Plus de 2000 €</option>
            </select>
          </div>
        </div>

        {isLoading ? (
          <div className="flex justify-center h-64 items-center"><Loader2 className="h-10 w-10 text-emerald-500 animate-spin" /></div>
        ) : (
          <motion.div variants={containerVariants} initial="hidden" animate="show" className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {displayedProjects.map((project) => (
              <motion.div key={project.id} variants={cardVariants} className="bg-slate-900 rounded-3xl border border-slate-800 hover:border-slate-700 overflow-hidden flex flex-col transition-all group hover:shadow-2xl hover:shadow-black/50">
                
                <div className="h-36 bg-slate-800 relative overflow-hidden flex items-center justify-center border-b border-slate-800">
                  {project.illustration_path && project.illustration_path !== 'default.jpg' ? (
                     <img src={`http://localhost:8000/uploads/${project.illustration_path}`} alt={project.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onError={(e) => { e.target.onerror = null; e.target.style.display = 'none'; e.target.nextSibling.style.display = 'flex'; }} />
                  ) : null}
                  <div className="absolute inset-0 bg-gradient-to-tr from-slate-950 to-slate-800 flex items-center justify-center" style={{ display: (project.illustration_path && project.illustration_path !== 'default.jpg') ? 'none' : 'flex' }}>
                     <ImageIcon className="h-10 w-10 text-slate-700 group-hover:text-slate-500 transition-colors" />
                  </div>
                </div>

                <div className="p-6 flex-1 flex flex-col">
                  <div className="flex justify-between items-start mb-4">
                    <h3 className="text-xl font-black text-white leading-tight mb-2 truncate group-hover:text-emerald-400 transition-colors" title={project.name}>{project.name}</h3>
                    <div className="flex items-center gap-1 bg-indigo-500/10 text-indigo-400 px-2 py-1 rounded-lg border border-indigo-500/20 whitespace-nowrap">
                      <Users className="h-4 w-4" />
                      <span className="font-bold text-sm">{project.attendees_count || 0}</span>
                    </div>
                  </div>
                  <p className="text-sm text-slate-500 mb-6 font-medium">Par <span className="text-slate-400">{project.requester}</span></p>
                  
                  <div className="mt-auto">
                    <div className="mb-5">
                      <div className="w-full bg-slate-950 rounded-full h-2 overflow-hidden mb-2 border border-slate-800">
                        <div className={`h-full ${activeTab === 'ongoing' ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-blue-500 to-indigo-500'}`} style={{ width: `${Math.min(((project.allocated_budget || 0) / project.requested_budget) * 100, 100)}%` }} />
                      </div>
                      <div className="flex justify-between text-[11px] uppercase font-bold text-slate-500">
                        <span className={project.allocated_budget >= project.requested_budget ? "text-emerald-400" : "text-indigo-400"}>{project.allocated_budget || 0} € alloués</span>
                        <span>Cible: {project.requested_budget} €</span>
                      </div>
                    </div>

                    <button 
                      onClick={() => setSelectedProject(project)} 
                      className="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all border border-transparent hover:border-slate-600"
                    >
                      <Eye className="h-4 w-4 text-slate-400 group-hover:text-white transition-colors" /> Gérer le dossier
                    </button>
                  </div>
                </div>
              </motion.div>
            ))}
            
            {displayedProjects.length === 0 && (
              <div className="col-span-full text-center py-20 bg-slate-900/50 rounded-3xl border border-slate-800/50">
                <p className="text-slate-500 text-lg font-medium italic">Aucun projet ne correspond à vos critères.</p>
              </div>
            )}
          </motion.div>
        )}
      </main>

      <AnimatePresence>
        {selectedProject && (
          <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] flex items-center justify-center p-4">
            <motion.div initial={{ opacity: 0, scale: 0.95, y: 20 }} animate={{ opacity: 1, scale: 1, y: 0 }} exit={{ opacity: 0, scale: 0.95, y: 20 }} className="bg-slate-900 border border-slate-700 rounded-3xl max-w-5xl w-full flex flex-col max-h-[90vh] overflow-hidden shadow-2xl relative">
              
              <button onClick={() => setSelectedProject(null)} className="absolute top-4 right-4 z-10 text-white/70 hover:text-white bg-black/40 p-2.5 rounded-full backdrop-blur-md transition-all">✕</button>

              <div className="h-44 bg-slate-800 relative overflow-hidden shrink-0 border-b border-slate-800">
                {selectedProject.illustration_path && selectedProject.illustration_path !== 'default.jpg' && (
                  <img src={`http://localhost:8000/uploads/${selectedProject.illustration_path}`} alt="" className="w-full h-full object-cover opacity-50" />
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent" />
                <div className="absolute bottom-0 left-0 p-8 w-full">
                  <div className="flex items-end justify-between">
                    <div>
                      <h3 className="text-4xl font-black text-white mb-2 drop-shadow-2xl">{selectedProject.name}</h3>
                      <p className="text-emerald-400 font-black text-xl bg-slate-950/50 px-3 py-1 rounded-lg backdrop-blur-sm inline-block">Cible : {selectedProject.requested_budget} €</p>
                    </div>
                    <div className="text-right hidden sm:block">
                      <p className="text-sm text-slate-400 font-bold uppercase tracking-widest">Auteur</p>
                      <p className="text-xl font-black text-white">{selectedProject.requester}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div className="flex flex-col md:flex-row flex-1 overflow-hidden bg-slate-900">
                {/* Colonne Gauche : Description & Financement */}
                <div className="p-8 flex-1 overflow-y-auto border-r border-slate-800 custom-scrollbar">
                  <h4 className="text-sm font-black text-slate-500 uppercase tracking-widest mb-4">Le Pitch</h4>
                  <div className="bg-slate-950/50 p-6 rounded-2xl text-slate-300 border border-slate-800 text-sm leading-relaxed mb-8 shadow-inner">
                    {selectedProject.description || "Aucune description détaillée."}
                  </div>

                  <h4 className="text-sm font-black text-slate-500 uppercase tracking-widest mb-4">Décision Stratégique</h4>
                  <div className="bg-slate-950/40 p-6 rounded-2xl border border-slate-800">
                    <div className="flex gap-4 items-end mb-6">
                      <div className="flex-1">
                        <label className="text-xs font-black text-emerald-500 uppercase mb-2 block ml-1">Montant à débloquer</label>
                        <input 
                          type="number" 
                          placeholder="Ex: 500"
                          value={allocations[selectedProject.id] || ''}
                          onChange={(e) => setAllocations({ ...allocations, [selectedProject.id]: e.target.value })}
                          className="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-5 py-4 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-all font-black text-lg shadow-inner"
                        />
                      </div>
                    </div>

                    <div className="flex gap-4">
                      {activeTab === 'pending' && (
                        <button onClick={() => rejectMutation.mutate(selectedProject.id)} className="w-1/3 py-4 text-rose-400 bg-rose-500/10 hover:bg-rose-500 hover:text-white rounded-xl transition-all font-black flex justify-center items-center border border-rose-500/20">
                          <X className="h-5 w-5 mr-1" /> Rejeter
                        </button>
                      )}
                      <button 
                        onClick={() => allocateMutation.mutate({ id: selectedProject.id, amount: allocations[selectedProject.id] })}
                        disabled={!allocations[selectedProject.id] || allocateMutation.isPending}
                        className="flex-1 py-4 text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 rounded-xl font-black transition-all disabled:opacity-30 disabled:cursor-not-allowed flex justify-center items-center shadow-lg shadow-emerald-900/30"
                      >
                        {allocateMutation.isPending ? <Loader2 className="animate-spin h-5 w-5" /> : <><Check className="h-5 w-5 mr-2" /> Valider l'investissement</>}
                      </button>
                    </div>
                  </div>
                </div>

                <div className="w-full md:w-80 bg-slate-950/30 p-8 overflow-y-auto flex flex-col">
                  <h4 className="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <div className="h-2 w-2 bg-indigo-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(99,102,241,0.8)]" />
                    Membres mobilisés ({joinedData?.total || 0})
                  </h4>
                  
                  {joinedData?.users?.length > 0 ? (
                    <div className="space-y-3">
                      {joinedData.users.map(u => (
                        <div key={u.id} className="flex items-center gap-4 group bg-slate-900/40 p-3 rounded-2xl border border-slate-800 hover:border-indigo-500/50 transition-all cursor-default shadow-sm">
                          <div className="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500/20 to-blue-500/20 border border-indigo-500/30 flex items-center justify-center text-sm font-black text-indigo-400 group-hover:from-indigo-500 group-hover:to-blue-500 group-hover:text-white transition-all shrink-0">
                            {u.first_name[0]}{u.last_name[0]}
                          </div>
                          <div className="truncate">
                            <p className="text-sm font-black text-slate-200 truncate group-hover:text-white transition-colors">{u.first_name} {u.last_name}</p>
                            <p className="text-xs text-slate-500 truncate font-medium">@{u.username}</p>
                          </div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="text-center mt-10 opacity-50">
                      <Users className="h-10 w-10 text-slate-700 mx-auto mb-4" />
                      <p className="text-sm text-slate-500 font-bold italic">Aucun ralliement détecté.</p>
                    </div>
                  )}
                </div>
              </div>

            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}