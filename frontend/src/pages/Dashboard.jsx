import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getProjects, upvoteProject } from '../api/projectService';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';
import { Loader2, LogOut, LayoutGrid, ArrowBigUp, Eye, User, Trophy, Lightbulb, MessageSquare, Send } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import toast from 'react-hot-toast';

export default function Dashboard() {
  const { logout, user } = useAuth();
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  
  const [activeTab, setActiveTab] = useState('lab'); 
  const [selectedProject, setSelectedProject] = useState(null);
  const [commentInput, setCommentInput] = useState('');
  const [localComments, setLocalComments] = useState({}); // Simule la BDD pour les commentaires

  const { data, isLoading } = useQuery({ queryKey: ['projects'], queryFn: getProjects });

  const upvoteMutation = useMutation({
    mutationFn: (id) => upvoteProject(id),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success('Vote enregistré !');
    },
    onError: () => toast.error("Le backend n'a pas encore créé la route /upvote")
  });

  const labProjects = data?.projects?.filter(p => p.status === 'Pending' || p.status_id === 1) || [];
  const hallProjects = data?.projects?.filter(p => p.status === 'Approved' || p.status_id === 2) || [];
  const displayedProjects = activeTab === 'lab' ? labProjects : hallProjects;

  const handleAddComment = (e) => {
    e.preventDefault();
    if (!commentInput.trim()) return;
    
    // simulation  (à remplacer par un appel API)
    const newComment = { id: Date.now(), text: commentInput, author: "Moi", date: "À l'instant" };
    setLocalComments(prev => ({
      ...prev,
      [selectedProject.id]: [...(prev[selectedProject.id] || []), newComment]
    }));
    setCommentInput('');
    toast.success('Commentaire ajouté !');
  };

  return (
    <div className="min-h-screen bg-slate-950 font-sans text-slate-300">
      <nav className="bg-slate-900/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-800">
        <div className="max-w-7xl mx-auto px-4 flex justify-between h-16 items-center">
          <div className="flex items-center gap-3">
            <LayoutGrid className="h-6 w-6 text-blue-500" />
            <h1 className="text-xl font-bold text-white">CorpStarter</h1>
          </div>
          <div className="flex gap-3">
            <button onClick={() => navigate('/profile')} className="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition-colors">
              <User className="h-4 w-4" /> Profil
            </button>
            <button onClick={logout} className="text-slate-400 hover:text-rose-500 px-4 py-2 flex items-center gap-2 text-sm font-bold transition-colors">
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
            whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
            onClick={() => navigate('/create-project')}
            className="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20"
          >
            + Soumettre un projet
          </motion.button>
        </div>

        <div className="flex gap-4 mb-8 border-b border-slate-800 pb-px">
          <button 
            onClick={() => setActiveTab('lab')}
            className={`pb-4 px-2 font-bold text-sm flex items-center gap-2 transition-colors border-b-2 ${activeTab === 'lab' ? 'border-blue-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300'}`}
          >
            <Lightbulb className="h-5 w-5" /> Le Lab (En cours)
          </button>
          <button 
            onClick={() => setActiveTab('hall')}
            className={`pb-4 px-2 font-bold text-sm flex items-center gap-2 transition-colors border-b-2 ${activeTab === 'hall' ? 'border-emerald-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300'}`}
          >
            <Trophy className="h-5 w-5" /> Hall of Fame (Réalisés)
          </button>
        </div>

        {isLoading ? <Loader2 className="animate-spin mx-auto h-10 w-10 text-blue-500 mt-20" /> : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {displayedProjects.length === 0 ? (
              <p className="text-slate-500 col-span-full py-10">Aucun projet dans cette catégorie pour le moment.</p>
            ) : displayedProjects.map((project) => (
              <motion.div key={project.id} initial={{ opacity: 0, y: 15 }} animate={{ opacity: 1, y: 0 }} className="bg-slate-900 border border-slate-800 rounded-2xl flex overflow-hidden group hover:border-slate-700 transition-colors">
                
                {activeTab === 'lab' && (
                  <div className="bg-slate-950/50 w-16 flex flex-col items-center py-4 border-r border-slate-800">
                    <button onClick={() => upvoteMutation.mutate(project.id)} className="text-slate-500 hover:text-blue-400 p-2"><ArrowBigUp className="h-7 w-7" /></button>
                    <span className="font-bold text-slate-300 mt-2">{project.votes || 0}</span>
                  </div>
                )}

                <div className="p-6 flex-1 flex flex-col">
                  <div className="flex justify-between items-start mb-2 gap-4">
                    <h3 className="text-lg font-bold text-white leading-tight">{project.name}</h3>
                    {activeTab === 'hall' && <Trophy className="h-5 w-5 text-emerald-500" />}
                  </div>
                  <p className="text-sm text-slate-500 mb-6">Par {project.requester}</p>

                  <div className="mt-auto">
                    {activeTab === 'lab' && (
                      <div className="mb-4">
                        <div className="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden mb-2">
                          <div className="bg-blue-500 h-full" style={{ width: `${Math.min(((project.allocated_budget || 0) / project.requested_budget) * 100, 100)}%` }} />
                        </div>
                        <div className="flex justify-between text-[10px] uppercase font-bold text-slate-500">
                          <span>{project.allocated_budget || 0} €</span>
                          <span>{project.requested_budget} €</span>
                        </div>
                      </div>
                    )}
                    <button onClick={() => setSelectedProject(project)} className="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                      <Eye className="h-4 w-4" /> Discuter
                    </button>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        )}
      </main>

      <AnimatePresence>
        {selectedProject && (
          <div className="fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
            <motion.div initial={{ opacity: 0, scale: 0.95 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.95 }} className="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full flex flex-col max-h-[90vh] overflow-hidden relative shadow-2xl">
              
              <div className="p-8 border-b border-slate-800 shrink-0">
                <button onClick={() => setSelectedProject(null)} className="absolute top-6 right-6 text-slate-500 hover:text-white bg-slate-800 p-2 rounded-full">✕</button>
                <h3 className="text-2xl font-black text-white mb-2">{selectedProject.name}</h3>
                <p className="text-slate-400 font-medium mb-6">Proposé par {selectedProject.requester}</p>
                <div className="bg-slate-950 p-5 rounded-2xl text-slate-300 border border-slate-800 text-sm leading-relaxed">
                  {selectedProject.description || "Aucune description."}
                </div>
              </div>

              <div className="p-8 overflow-y-auto flex-1 bg-slate-900/50">
                <h4 className="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                  <MessageSquare className="h-4 w-4" /> Discussion
                </h4>
                
                <div className="space-y-4 mb-6">
                  {/* Faux commentaire de base pour l'exemple */}
                  <div className="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                    <div className="flex justify-between items-center mb-2">
                      <span className="font-bold text-white text-sm">Collègue Mystère</span>
                      <span className="text-xs text-slate-500">Hier</span>
                    </div>
                    <p className="text-sm text-slate-300">Très bonne initiative, je soutiens totalement l'idée !</p>
                  </div>
                  
                  {(localComments[selectedProject.id] || []).map(comment => (
                    <div key={comment.id} className="bg-blue-900/20 p-4 rounded-xl border border-blue-500/20">
                      <div className="flex justify-between items-center mb-2">
                        <span className="font-bold text-blue-400 text-sm">{comment.author}</span>
                        <span className="text-xs text-slate-500">{comment.date}</span>
                      </div>
                      <p className="text-sm text-slate-300">{comment.text}</p>
                    </div>
                  ))}
                </div>

                <form onSubmit={handleAddComment} className="flex gap-2">
                  <input 
                    type="text" 
                    value={commentInput}
                    onChange={(e) => setCommentInput(e.target.value)}
                    placeholder="Ajouter un commentaire..." 
                    className="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500"
                  />
                  <button type="submit" disabled={!commentInput.trim()} className="bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 disabled:text-slate-500 text-white p-3 rounded-xl transition-colors">
                    <Send className="h-5 w-5" />
                  </button>
                </form>
              </div>

            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}