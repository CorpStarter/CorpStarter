import { useState, useMemo } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getProjects, joinProject, getJoinedUsers } from '../api/projectService'; 
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';
import { Loader2, LogOut, LayoutGrid, ArrowBigUp, Eye, User, Trophy, Lightbulb, MessageSquare, Send, Users, Image as ImageIcon, Filter } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import toast from 'react-hot-toast';

export default function Dashboard() {
  const { logout } = useAuth();
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  
  const [activeTab, setActiveTab] = useState('lab'); 
  const [budgetFilter, setBudgetFilter] = useState('all'); // 'all', 'low' (<500), 'mid' (500-2000), 'high' (>2000)
  const [selectedProject, setSelectedProject] = useState(null);
  const [commentInput, setCommentInput] = useState('');
  const [localComments, setLocalComments] = useState({}); 

  const { data, isLoading } = useQuery({ queryKey: ['projects'], queryFn: getProjects });

  const { data: joinedData } = useQuery({
    queryKey: ['joined-users', selectedProject?.id],
    queryFn: () => getJoinedUsers(selectedProject.id),
    enabled: !!selectedProject, 
  });

  const joinMutation = useMutation({
    mutationFn: (id) => joinProject(id),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      queryClient.invalidateQueries(['joined-users']); 
      toast.success('Vous avez rejoint/soutenu ce projet !');
    },
    onError: (err) => toast.error(err.response?.data?.error || "Impossible de rejoindre ce projet.")
  });

  // --- FILTRES ET TRI ---
  const displayedProjects = useMemo(() => {
    let projects = data?.projects || [];

    // 1. Tri par date (du plus récent au plus ancien)
    projects.sort((a, b) => new Date(b.creation_date) - new Date(a.creation_date));

    // 2. Filtre par Onglet
    if (activeTab === 'hall') {
      projects = projects.filter(p => p.status === 'Approved' || p.status === 'In Progress' || p.status_id === 2);
    } 
    // Pour 'lab', on garde tous les projets comme demandé !

    // 3. Filtre par Budget
    if (budgetFilter === 'low') projects = projects.filter(p => parseFloat(p.requested_budget) < 500);
    if (budgetFilter === 'mid') projects = projects.filter(p => parseFloat(p.requested_budget) >= 500 && parseFloat(p.requested_budget) <= 2000);
    if (budgetFilter === 'high') projects = projects.filter(p => parseFloat(p.requested_budget) > 2000);

    return projects;
  }, [data, activeTab, budgetFilter]);

  const handleAddComment = (e) => {
    e.preventDefault();
    if (!commentInput.trim()) return;
    const newComment = { id: Date.now(), text: commentInput, author: "Moi", date: "À l'instant" };
    setLocalComments(prev => ({ ...prev, [selectedProject.id]: [...(prev[selectedProject.id] || []), newComment] }));
    setCommentInput('');
    toast.success('Commentaire ajouté !');
  };

  // --- ANIMATIONS FRAMER MOTION ---
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
    <div className="min-h-screen bg-slate-950 font-sans text-slate-300 selection:bg-indigo-500/30">
      <nav className="bg-slate-900/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-800 shadow-sm shadow-black/50">
        <div className="max-w-7xl mx-auto px-4 flex justify-between h-16 items-center">
          <div className="flex items-center gap-3">
            <div className="bg-gradient-to-br from-indigo-500 to-blue-600 p-2 rounded-xl shadow-lg shadow-indigo-500/20">
              <LayoutGrid className="h-5 w-5 text-white" />
            </div>
            <h1 className="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">CorpStarter</h1>
          </div>
          <div className="flex gap-3">
            <button onClick={() => navigate('/profile')} className="bg-slate-800 hover:bg-slate-700 hover:text-indigo-400 px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-bold transition-all border border-transparent hover:border-slate-600">
              <User className="h-4 w-4" /> Profil
            </button>
            <button onClick={logout} className="text-slate-400 hover:text-rose-400 px-4 py-2 flex items-center gap-2 text-sm font-bold transition-colors">
              Quitter <LogOut className="h-4 w-4" />
            </button>
          </div>
        </div>
      </nav>

      <main className="max-w-7xl mx-auto px-4 py-12">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10">
          <div>
            <h2 className="text-4xl font-black text-white tracking-tight mb-2">Espace Innovation</h2>
            <p className="text-slate-400 text-lg">Façonnez l'avenir de votre entreprise.</p>
          </div>
          <motion.button 
            whileHover={{ scale: 1.02, boxShadow: "0 0 20px rgba(99, 102, 241, 0.4)" }} 
            whileTap={{ scale: 0.98 }}
            onClick={() => navigate('/create-project')}
            className="bg-gradient-to-r from-indigo-600 to-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-900/20 flex items-center gap-2 transition-all"
          >
            <Lightbulb className="h-5 w-5" /> Soumettre un projet
          </motion.button>
        </div>

        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 border-b border-slate-800 pb-4">
          <div className="flex gap-6">
            <button 
              onClick={() => setActiveTab('lab')} 
              className={`pb-2 font-bold text-sm flex items-center gap-2 transition-colors border-b-2 relative ${activeTab === 'lab' ? 'border-indigo-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300'}`}
            >
              <Lightbulb className={`h-5 w-5 ${activeTab === 'lab' ? 'text-indigo-400' : ''}`} /> 
              Le Lab
              {activeTab === 'lab' && <span className="absolute -bottom-[2px] left-0 w-full h-[2px] bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]" />}
            </button>
            <button 
              onClick={() => setActiveTab('hall')} 
              className={`pb-2 font-bold text-sm flex items-center gap-2 transition-colors border-b-2 relative ${activeTab === 'hall' ? 'border-emerald-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300'}`}
            >
              <Trophy className={`h-5 w-5 ${activeTab === 'hall' ? 'text-emerald-400' : ''}`} /> 
              Hall of Fame
              {activeTab === 'hall' && <span className="absolute -bottom-[2px] left-0 w-full h-[2px] bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]" />}
            </button>
          </div>

          <div className="flex items-center gap-2 bg-slate-900/50 hover:bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl px-3 py-2 transition-colors">
            <Filter className="h-4 w-4 text-indigo-400" />
            <select value={budgetFilter} onChange={(e) => setBudgetFilter(e.target.value)} className="bg-transparent text-sm text-slate-300 font-medium outline-none cursor-pointer">
              <option value="all">Tous les budgets</option>
              <option value="low">Moins de 500 €</option>
              <option value="mid">Entre 500 € et 2000 €</option>
              <option value="high">Plus de 2000 €</option>
            </select>
          </div>
        </div>

        {isLoading ? <div className="flex justify-center py-20"><Loader2 className="animate-spin h-10 w-10 text-indigo-500" /></div> : (
          <motion.div variants={containerVariants} initial="hidden" animate="show" className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {displayedProjects.length === 0 ? (
              <p className="text-slate-500 col-span-full py-10 text-center text-lg">Aucun projet ne correspond à vos filtres.</p>
            ) : displayedProjects.map((project) => (
              <motion.div key={project.id} variants={cardVariants} className="bg-slate-900 border border-slate-800 rounded-2xl flex flex-col overflow-hidden group hover:border-slate-700 hover:shadow-xl hover:shadow-black/50 transition-all">
                
                <div className="h-36 bg-slate-800 relative overflow-hidden flex items-center justify-center border-b border-slate-800">
                  {project.illustration_path && project.illustration_path !== 'default.jpg' ? (
                     <img src={`http://localhost:8000/uploads/${project.illustration_path}`} alt="" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onError={(e) => { e.target.onerror = null; e.target.style.display = 'none'; e.target.nextSibling.style.display = 'flex'; }} />
                  ) : null}
                  <div className="absolute inset-0 bg-gradient-to-tr from-slate-950 to-slate-800 flex items-center justify-center" style={{ display: (project.illustration_path && project.illustration_path !== 'default.jpg') ? 'none' : 'flex' }}>
                     <ImageIcon className="h-10 w-10 text-slate-700 group-hover:text-slate-600 transition-colors" />
                  </div>
                </div>

                <div className="flex flex-1">
                  <div className="bg-slate-950/50 w-16 flex flex-col items-center py-4 border-r border-slate-800 shrink-0">
                    <button onClick={() => joinMutation.mutate(project.id)} className="text-slate-500 hover:text-indigo-400 p-2 transition-colors transform hover:-translate-y-1">
                      <ArrowBigUp className="h-7 w-7" />
                    </button>
                    <span className="font-bold text-slate-300 mt-1">{project.attendees_count || project.votes || 0}</span>
                  </div>

                  <div className="p-6 flex-1 flex flex-col">
                    <div className="flex justify-between items-start mb-2 gap-4">
                      <h3 className="text-lg font-black text-white leading-tight truncate group-hover:text-indigo-400 transition-colors" title={project.name}>{project.name}</h3>
                      {project.status === 'Approved' && <Trophy className="h-5 w-5 text-emerald-500 shrink-0 drop-shadow-[0_0_8px_rgba(16,185,129,0.5)]" />}
                    </div>
                    <p className="text-sm text-slate-500 mb-6 font-medium">Par <span className="text-slate-400">{project.requester}</span></p>

                    <div className="mt-auto">
                      <div className="mb-5">
                        <div className="w-full bg-slate-950 rounded-full h-2 border border-slate-800 overflow-hidden mb-2">
                          <div 
                            className={`h-full ${project.status === 'Approved' ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-indigo-500 to-blue-500'}`} 
                            style={{ width: `${Math.min(((project.allocated_budget || 0) / project.requested_budget) * 100, 100)}%` }} 
                          />
                        </div>
                        <div className="flex justify-between text-[11px] uppercase font-bold text-slate-500">
                          <span className={project.allocated_budget >= project.requested_budget ? 'text-emerald-400' : 'text-indigo-400'}>{project.allocated_budget || 0} € alloués</span>
                          <span>Cible: {project.requested_budget} €</span>
                        </div>
                      </div>

                      <button onClick={() => setSelectedProject(project)} className="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2 group-hover:border-slate-600 border border-transparent">
                        <Eye className="h-4 w-4 text-slate-400 group-hover:text-white transition-colors" /> Examiner
                      </button>
                    </div>
                  </div>
                </div>
              </motion.div>
            ))}
          </motion.div>
        )}
      </main>

      <AnimatePresence>
        {selectedProject && (
          <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] flex items-center justify-center p-4 sm:p-6">
            <motion.div initial={{ opacity: 0, scale: 0.95, y: 20 }} animate={{ opacity: 1, scale: 1, y: 0 }} exit={{ opacity: 0, scale: 0.95, y: 20 }} className="bg-slate-900 border border-slate-700 rounded-3xl max-w-5xl w-full flex flex-col max-h-[90vh] overflow-hidden relative shadow-2xl shadow-black">
              <button onClick={() => setSelectedProject(null)} className="absolute top-4 right-4 z-10 text-white/70 hover:text-white bg-black/40 hover:bg-black/60 p-2.5 rounded-full backdrop-blur-md transition-all">✕</button>

              <div className="h-48 bg-slate-800 relative overflow-hidden shrink-0 border-b border-slate-800">
                {selectedProject.illustration_path && selectedProject.illustration_path !== 'default.jpg' && (
                  <img src={`http://localhost:8000/uploads/${selectedProject.illustration_path}`} alt="" className="w-full h-full object-cover opacity-50" />
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent" />
                <div className="absolute bottom-0 left-0 p-8 w-full">
                  <div className="flex items-end justify-between">
                    <div>
                      <div className="flex items-center gap-3 mb-2">
                         {selectedProject.status === 'Approved' && <span className="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Validé</span>}
                         <p className="text-indigo-400 font-black text-lg drop-shadow-md bg-slate-950/50 px-3 py-1 rounded-lg backdrop-blur-sm">Cible : {selectedProject.requested_budget} €</p>
                      </div>
                      <h3 className="text-4xl font-black text-white shadow-black drop-shadow-lg">{selectedProject.name}</h3>
                    </div>
                    <div className="text-right hidden sm:block">
                      <p className="text-sm text-slate-300 font-medium drop-shadow-md">Initiative portée par</p>
                      <p className="text-xl font-bold text-white drop-shadow-md">{selectedProject.requester}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div className="flex flex-col md:flex-row flex-1 overflow-hidden bg-slate-900">
                <div className="p-8 flex-1 overflow-y-auto border-r border-slate-800 custom-scrollbar">
                  <h4 className="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <Lightbulb className="h-4 w-4 text-indigo-400" /> Le Pitch
                  </h4>
                  <div className="bg-slate-950/50 p-6 rounded-2xl text-slate-300 border border-slate-800 text-sm leading-relaxed mb-10 shadow-inner">
                    {selectedProject.description || "Aucune description détaillée fournie."}
                  </div>

                  <h4 className="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <MessageSquare className="h-4 w-4 text-indigo-400" /> Espace de Discussion
                  </h4>
                  
                  <div className="space-y-4 mb-6">
                    {(localComments[selectedProject.id] || []).map(comment => (
                      <div key={comment.id} className="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                        <div className="flex justify-between items-center mb-2">
                          <span className="font-bold text-indigo-400 text-sm">{comment.author}</span>
                          <span className="text-xs text-slate-500">{comment.date}</span>
                        </div>
                        <p className="text-sm text-slate-300">{comment.text}</p>
                      </div>
                    ))}
                    {(!localComments[selectedProject.id] || localComments[selectedProject.id].length === 0) && (
                      <div className="text-center py-6 border border-dashed border-slate-700 rounded-2xl">
                         <p className="text-sm text-slate-500 italic">L'espace commentaire est vide. Lancez le débat !</p>
                      </div>
                    )}
                  </div>

                  <form onSubmit={handleAddComment} className="flex gap-3">
                    <input 
                      type="text" 
                      value={commentInput}
                      onChange={(e) => setCommentInput(e.target.value)}
                      placeholder="Partagez votre avis..." 
                      className="flex-1 bg-slate-950 border border-slate-700 rounded-xl px-5 py-3 text-sm text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner"
                    />
                    <button type="submit" disabled={!commentInput.trim()} className="bg-indigo-600 hover:bg-indigo-500 disabled:bg-slate-800 disabled:text-slate-500 text-white px-5 py-3 rounded-xl transition-colors shadow-lg shadow-indigo-900/20 font-bold flex items-center gap-2">
                      <Send className="h-4 w-4" /> <span className="hidden sm:inline">Envoyer</span>
                    </button>
                  </form>
                </div>

                <div className="w-full md:w-80 bg-slate-950/30 p-8 overflow-y-auto flex flex-col">
                  <h4 className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <div className="h-2 w-2 bg-indigo-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(99,102,241,0.8)]" />
                    Équipe mobilisée ({joinedData?.total || 0})
                  </h4>
                  
                  {joinedData?.users?.length > 0 ? (
                    <div className="space-y-3">
                      {joinedData.users.map(u => (
                        <div key={u.id} className="flex items-center gap-4 group bg-slate-900/50 p-3 rounded-xl border border-slate-800 hover:border-indigo-500/50 transition-colors cursor-default">
                          <div className="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500/20 to-blue-500/20 border border-indigo-500/30 flex items-center justify-center text-sm font-black text-indigo-400 group-hover:from-indigo-500 group-hover:to-blue-500 group-hover:text-white transition-all shrink-0">
                            {u.first_name[0]}{u.last_name[0]}
                          </div>
                          <div className="truncate">
                            <p className="text-sm font-bold text-slate-200 truncate group-hover:text-white transition-colors">{u.first_name} {u.last_name}</p>
                            <p className="text-xs text-slate-500 truncate">@{u.username}</p>
                          </div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="text-center mt-10 bg-slate-900/50 p-6 rounded-2xl border border-slate-800">
                      <Users className="h-8 w-8 text-slate-600 mx-auto mb-3" />
                      <p className="text-sm text-slate-400 font-medium">Soutenez ce projet pour apparaître ici !</p>
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