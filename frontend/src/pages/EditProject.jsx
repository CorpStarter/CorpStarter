import { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { useMutation, useQueryClient, useQuery } from '@tanstack/react-query';
import { getProjects, updateProject } from '../api/projectService';
import { ArrowLeft, Save, Loader2 } from 'lucide-react';
import toast from 'react-hot-toast';

export default function EditProject() {
  const { id } = useParams();
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const [formData, setFormData] = useState({ name: '', requested_budget: '', description: '' });

  const { data } = useQuery({ queryKey: ['projects'], queryFn: getProjects });
  
  useEffect(() => {
    const project = data?.projects?.find(p => p.id === parseInt(id));
    if (project) {
      setFormData({
        name: project.name,
        requested_budget: project.requested_budget,
        description: project.description || ''
      });
    }
  }, [data, id]);

  const mutation = useMutation({
    mutationFn: (updatedData) => updateProject(id, updatedData),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success('Projet mis à jour !');
      navigate('/profile');
    },
    onError: () => toast.error("Erreur lors de la modification.")
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    mutation.mutate({ ...formData, requested_budget: parseFloat(formData.requested_budget) });
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-300 py-12 px-4">
      <div className="max-w-xl mx-auto">
        <button onClick={() => navigate('/profile')} className="flex items-center text-slate-500 hover:text-white mb-8">
          <ArrowLeft className="mr-2 h-5 w-5" /> Annuler
        </button>
        <div className="bg-slate-900 border border-slate-800 rounded-3xl p-10 shadow-2xl">
          <h2 className="text-3xl font-black text-white mb-6">Modifier l'idée</h2>
          <form onSubmit={handleSubmit} className="space-y-6">
            <input required type="text" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 outline-none focus:border-blue-500 text-white" value={formData.name} onChange={(e) => setFormData({...formData, name: e.target.value})} />
            <input required type="number" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 outline-none focus:border-blue-500 text-white" value={formData.requested_budget} onChange={(e) => setFormData({...formData, requested_budget: e.target.value})} />
            <textarea required rows="5" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 outline-none focus:border-blue-500 text-white resize-none" value={formData.description} onChange={(e) => setFormData({...formData, description: e.target.value})} />
            <button type="submit" disabled={mutation.isPending} className="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-lg flex justify-center items-center transition-all shadow-lg shadow-emerald-900/20">
              {mutation.isPending ? <Loader2 className="animate-spin" /> : <><Save className="mr-2 h-5 w-5" /> Enregistrer les modifications</>}
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}