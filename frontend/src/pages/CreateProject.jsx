import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { createProject } from '../api/projectService';
import { ArrowLeft, Send, Loader2 } from 'lucide-react';
import toast from 'react-hot-toast';

export default function CreateProject() {
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const [formData, setFormData] = useState({ name: '', requested_budget: '', description: '', illustration_path: 'default.jpg' });

  const mutation = useMutation({
    mutationFn: createProject,
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success('Votre idée a été soumise avec succès !'); 
      navigate('/dashboard');
    },
    onError: () => {
      toast.error("Erreur lors de la création du projet.");
    }
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    mutation.mutate({ ...formData, requested_budget: parseFloat(formData.requested_budget) });
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-300 py-12 px-4 font-sans">
      <div className="max-w-2xl mx-auto">
        <button onClick={() => navigate('/dashboard')} className="flex items-center text-slate-500 hover:text-white mb-8 transition-colors">
          <ArrowLeft className="mr-2 h-5 w-5" /> Retour au Lab
        </button>
        
        <div className="bg-slate-900 border border-slate-800 rounded-3xl p-10 shadow-2xl">
          <h2 className="text-3xl font-black text-white mb-2">Nouvelle Idée</h2>
          <p className="text-slate-400 mb-10">Détaillez votre projet pour convaincre vos collègues et la direction.</p>
          
          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label className="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nom du projet</label>
              <input required type="text" placeholder="Ex: Espace détente" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" value={formData.name} onChange={(e) => setFormData({...formData, name: e.target.value})} />
            </div>
            
            <div>
              <label className="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Budget ciblé (€)</label>
              <input required type="number" placeholder="Ex: 1500" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" value={formData.requested_budget} onChange={(e) => setFormData({...formData, requested_budget: e.target.value})} />
            </div>
            
            <div>
              <label className="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Argumentaire</label>
              <textarea required rows="5" placeholder="Pourquoi ce projet est-il indispensable ?" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all resize-none" value={formData.description} onChange={(e) => setFormData({...formData, description: e.target.value})} />
            </div>
            
            <button type="submit" disabled={mutation.isPending} className="w-full py-4 mt-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-lg flex justify-center items-center shadow-lg shadow-blue-900/20 transition-all disabled:opacity-50">
              {mutation.isPending ? <Loader2 className="animate-spin h-6 w-6" /> : <><Send className="mr-2 h-5 w-5" /> Soumettre l'idée</>}
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}