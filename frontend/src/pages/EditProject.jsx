import { useState, useEffect } from 'react';
import { useNavigate, useParams, useLocation } from 'react-router-dom';
import { useMutation, useQueryClient, useQuery } from '@tanstack/react-query';
import { getProjects, updateProject } from '../api/projectService';
import { ArrowLeft, Save, Loader2 } from 'lucide-react';
import { motion } from 'framer-motion';
import toast from 'react-hot-toast';

export default function EditProject() {
  const { id } = useParams();
  const navigate = useNavigate();
  const location = useLocation();
  const queryClient = useQueryClient();
  
  const existingProject = location.state?.project;
  
  const [formData, setFormData] = useState({ 
    name: existingProject?.name || '', 
    requested_budget: existingProject?.requested_budget || '', 
    description: existingProject?.description || '' 
  });

  const { data } = useQuery({ queryKey: ['projects'], queryFn: getProjects });
  
  useEffect(() => {
    if (!existingProject && data?.projects) {
      const project = data.projects.find(p => p.id === parseInt(id));
      if (project) {
        setFormData({
          name: project.name,
          requested_budget: project.requested_budget,
          description: project.description || ''
        });
      }
    }
  }, [data, id, existingProject]);

  const mutation = useMutation({
    mutationFn: (updatedData) => updateProject(id, updatedData),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success('Projet mis à jour avec succès !');
      navigate('/profile');
    },
    onError: () => toast.error("Erreur lors de la modification.")
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    mutation.mutate({ ...formData, requested_budget: parseFloat(formData.requested_budget) });
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-300 py-12 px-4 font-sans selection:bg-indigo-500/30">
      <div className="max-w-2xl mx-auto">
        <button onClick={() => navigate('/profile')} className="flex items-center text-slate-500 hover:text-indigo-400 mb-8 transition-colors font-bold text-sm uppercase tracking-wider">
          <ArrowLeft className="mr-2 h-4 w-4" /> Retour à mon Profil
        </button>

        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          className="bg-slate-900 border border-slate-800 rounded-3xl p-8 sm:p-12 shadow-2xl shadow-black/50 relative overflow-hidden"
        >
          <div className="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none" />

          <h2 className="text-4xl font-black text-white mb-3">Modifier l'idée</h2>
          <p className="text-slate-400 mb-10 text-lg">Ajustez les détails de votre projet avant qu'il ne soit examiné par la direction.</p>
          
          <form onSubmit={handleSubmit} className="space-y-8 relative z-10">
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Nom du projet</label>
                <input required type="text" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner" value={formData.name} onChange={(e) => setFormData({...formData, name: e.target.value})} />
              </div>
              
              <div>
                <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Budget ciblé (€)</label>
                <input required type="number" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner font-mono text-lg" value={formData.requested_budget} onChange={(e) => setFormData({...formData, requested_budget: e.target.value})} />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Le Pitch (Argumentaire)</label>
              <textarea required rows="6" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner resize-y leading-relaxed" value={formData.description} onChange={(e) => setFormData({...formData, description: e.target.value})} />
            </div>
            
            <motion.button 
              type="submit" 
              disabled={mutation.isPending} 
              whileHover={{ scale: 1.01, boxShadow: "0 0 25px rgba(99, 102, 241, 0.4)" }}
              whileTap={{ scale: 0.99 }}
              className="w-full py-5 mt-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white rounded-xl font-black text-lg flex justify-center items-center transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-900/30"
            >
              {mutation.isPending ? (
                <Loader2 className="animate-spin h-6 w-6" />
              ) : (
                <><Save className="mr-3 h-6 w-6" /> Enregistrer les modifications</>
              )}
            </motion.button>
          </form>
        </motion.div>
      </div>
    </div>
  );
}