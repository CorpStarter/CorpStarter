import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getProjects, allocateBudget, updateProjectStatus } from '../api/projectService';
import { useAuth } from '../context/AuthContext';
import { Loader2, LogOut, ShieldCheck, Check, X, Eye, ArrowBigUp, MessageSquare } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import toast from 'react-hot-toast';

export default function AdminDashboard() {
  const { user, logout, updateBalance } = useAuth();
  const queryClient = useQueryClient();
  const [selectedProject, setSelectedProject] = useState(null);
  const [allocations, setAllocations] = useState({});

  const { data, isLoading } = useQuery({ queryKey: ['projects'], queryFn: getProjects });

  const allocateMutation = useMutation({
    mutationFn: ({ id, amount }) => allocateBudget(id, amount),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries(['projects']);
      if (user?.balance) updateBalance(user.balance - variables.amount);
      toast.success("Budget alloué avec succès !");
      setSelectedProject(null); 
    },
    onError: () => toast.error("La route backend pour allouer le budget n'est pas prête (404).")
  });

  const rejectMutation = useMutation({
    mutationFn: (id) => updateProjectStatus(id, 3), // 3 = Rejected
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success("Projet refusé.");
      setSelectedProject(null);
    }
  });

  const pendingProjects = data?.projects?.filter(p => p.status === 'Pending' || p.status_id === 1) || [];

  return (
    <div className="min-h-screen bg-slate-950 font-sans text-slate-300">
      <nav className="bg-slate-900 border-b border-slate-800 sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
          <div className="flex items-center gap-2">
            <div className="bg-emerald-500/10 p-2 rounded-lg">
              <ShieldCheck className="h-5 w-5 text-emerald-400" />
            </div>
            <h1 className="text-xl font-bold text-white tracking-wide">Direction</h1>
          </div>
          <div className="flex items-center space-x-6">
            <div className="flex flex-col items-end pr-6 border-r border-slate-700">
              <span className="text-[10px] font-bold text-slate-500 uppercase">Trésorerie</span>
              <span className="text-lg font-black text-emerald-400">{user?.balance || 500000} €</span>
            </div>
            <button onClick={logout} className="text-slate-400 hover:text-white transition-colors flex items-center gap-2 text-sm font-medium">
              Quitter <LogOut className="h-4 w-4" />
            </button>
          </div>
        </div>
      </nav>

      <main className="max-w-7xl mx-auto px-4 py-10">
        <div className="mb-10">
          <h2 className="text-3xl font-black text-white">Décisions en attente</h2>
          <p className="text-slate-400 mt-1">Examinez les projets, lisez les retours des équipes et allouez les fonds.</p>
        </div>

        {isLoading ? (
          <div className="flex justify-center h-64 items-center"><Loader2 className="h-10 w-10 text-emerald-500 animate-spin" /></div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            {pendingProjects.map((project) => (
              <motion.div key={project.id} initial={{ opacity: 0, y: 15 }} animate={{ opacity: 1, y: 0 }} className="bg-slate-900 rounded-2xl border border-slate-800 hover:border-slate-700 overflow-hidden flex flex-col transition-colors">
                <div className="p-6 flex-1">
                  <div className="flex justify-between items-start mb-4">
                    <h3 className="text-xl font-bold text-white leading-tight pr-4">{project.name}</h3>
                    
                    {/* Badge de Popularité (Rating) */}
                    <div className="flex items-center gap-1 bg-blue-500/10 text-blue-400 px-2 py-1 rounded-lg border border-blue-500/20 whitespace-nowrap">
                      <ArrowBigUp className="h-4 w-4" />
                      <span className="font-bold text-sm">{project.votes || 0}</span>
                    </div>
                  </div>
                  
                  <div className="bg-slate-950 p-4 rounded-xl border border-slate-800 mb-6 space-y-2">
                    <div className="flex justify-between text-sm"><span className="text-slate-500">Employé</span><span className="text-slate-300 font-medium">{project.requester}</span></div>
                    <div className="flex justify-between text-sm"><span className="text-slate-500">Demandé</span><span className="font-bold text-white">{project.requested_budget} €</span></div>
                  </div>

                  <button 
                    onClick={() => setSelectedProject(project)} 
                    className="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-colors"
                  >
                    <Eye className="h-4 w-4 text-slate-400" /> Analyser le dossier
                  </button>
                </div>
              </motion.div>
            ))}
            
            {pendingProjects.length === 0 && (
              <div className="col-span-full text-center py-20 bg-slate-900/50 rounded-3xl border border-slate-800/50">
                <p className="text-slate-400 text-lg">Aucun projet en attente de validation.</p>
              </div>
            )}
          </div>
        )}
      </main>

      {/* Modale d'Analyse pour l'Admin */}
      <AnimatePresence>
        {selectedProject && (
          <div className="fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
            <motion.div initial={{ opacity: 0, scale: 0.95 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.95 }} className="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full flex flex-col max-h-[90vh] overflow-hidden shadow-2xl relative">
              
              <div className="p-8 border-b border-slate-800 shrink-0">
                <button onClick={() => setSelectedProject(null)} className="absolute top-6 right-6 text-slate-500 hover:text-white bg-slate-800 p-2 rounded-full">✕</button>
                
                <div className="flex items-center gap-3 mb-2">
                  <h3 className="text-2xl font-black text-white">{selectedProject.name}</h3>
                  <div className="flex items-center gap-1 bg-blue-500/10 text-blue-400 px-2 py-1 rounded-lg border border-blue-500/20">
                    <ArrowBigUp className="h-4 w-4" />
                    <span className="font-bold text-sm">{selectedProject.votes || 0} Votes</span>
                  </div>
                </div>
                
                <p className="text-slate-400 font-medium mb-6">Proposé par {selectedProject.requester}</p>
                <div className="bg-slate-950 p-5 rounded-2xl text-slate-300 border border-slate-800 text-sm leading-relaxed">
                  {selectedProject.description || "Aucune description détaillée."}
                </div>
              </div>

              {/* Section Commentaires en lecture seule pour l'Admin */}
              <div className="p-8 overflow-y-auto flex-1 bg-slate-900/50 border-b border-slate-800">
                <h4 className="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                  <MessageSquare className="h-4 w-4" /> Avis des équipes
                </h4>
                
                <div className="space-y-3">
                  <div className="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                    <div className="flex justify-between items-center mb-1">
                      <span className="font-bold text-blue-400 text-sm">Collègue Anonyme</span>
                    </div>
                    <p className="text-sm text-slate-300">Excellente initiative, cela va vraiment améliorer notre quotidien !</p>
                  </div>
                  {/* Plus tard, map ici les vrais commentaires de la BDD */}
                  <div className="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                     <p className="text-sm text-slate-500 italic">Fin des commentaires.</p>
                  </div>
                </div>
              </div>

              {/* Panneau de Décision */}
              <div className="p-8 bg-slate-950 shrink-0">
                <h4 className="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Décision Financière</h4>
                
                <div className="flex gap-4 items-end mb-6">
                  <div className="flex-1">
                    <label className="text-xs text-slate-400 mb-2 block">Montant demandé : {selectedProject.requested_budget} €</label>
                    <input 
                      type="number" 
                      placeholder="Montant à allouer..."
                      value={allocations[selectedProject.id] || ''}
                      onChange={(e) => setAllocations({ ...allocations, [selectedProject.id]: e.target.value })}
                      className="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:border-emerald-500 outline-none transition-colors font-bold"
                    />
                  </div>
                </div>

                <div className="flex gap-4">
                  <button onClick={() => rejectMutation.mutate(selectedProject.id)} className="flex-1 py-3 text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 rounded-xl transition-colors font-bold flex justify-center items-center">
                    <X className="h-5 w-5 mr-2" /> Rejeter le projet
                  </button>
                  <button 
                    onClick={() => allocateMutation.mutate({ id: selectedProject.id, amount: allocations[selectedProject.id] })}
                    disabled={!allocations[selectedProject.id] || allocateMutation.isPending}
                    className="flex-1 py-3 text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center shadow-lg shadow-emerald-900/20"
                  >
                    {allocateMutation.isPending ? <Loader2 className="animate-spin h-5 w-5" /> : <><Check className="h-5 w-5 mr-2" /> Valider & Financer</>}
                  </button>
                </div>
              </div>

            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}