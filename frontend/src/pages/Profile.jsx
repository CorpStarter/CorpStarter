import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getProjects, deleteProject } from '../api/projectService';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';
import { Loader2, ArrowLeft, User, Clock, CheckCircle, XCircle, Pencil, Trash2 } from 'lucide-react';
import toast from 'react-hot-toast';

export default function Profile() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const { data, isLoading } = useQuery({ queryKey: ['projects'], queryFn: getProjects });

  const deleteMutation = useMutation({
    mutationFn: (id) => deleteProject(id),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success('Projet supprimé définitivement.');
    },
    onError: () => toast.error("Le backend ne permet pas encore la suppression (404).")
  });

  const myProjects = data?.projects?.filter(p => 
    parseInt(p.requester_id) === user?.id || p.requester === 'maryam'
  ) || [];

  const handleDelete = (id) => {
    if (window.confirm("Voulez-vous vraiment supprimer cette idée ? Cette action est irréversible.")) {
      deleteMutation.mutate(id);
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-300 py-10 px-4">
      <div className="max-w-4xl mx-auto">
        <button onClick={() => navigate('/dashboard')} className="flex items-center text-slate-500 hover:text-white mb-10 transition-colors"><ArrowLeft className="mr-2" /> Retour au Lab</button>
        
        <div className="bg-slate-900 border border-slate-800 rounded-3xl p-8 mb-10 flex items-center justify-between shadow-xl shadow-black/30">
          <div className="flex items-center gap-6">
            <div className="bg-slate-800 p-5 rounded-full border border-slate-700"><User className="h-12 w-12 text-blue-400" /></div>
            <div>
              <h2 className="text-3xl font-black text-white tracking-tight">Espace Innovateur</h2>
              <p className="text-slate-500">Compte certifié de Maryam</p>
            </div>
          </div>
        </div>

        <h3 className="text-xl font-bold text-white mb-6">Mes idées soumises ({myProjects.length})</h3>
        
        {isLoading ? <Loader2 className="animate-spin mx-auto h-8 w-8 text-blue-500" /> : (
          <div className="space-y-4">
            {myProjects.map(project => (
              <div key={project.id} className="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-center gap-6 group hover:border-slate-700 transition-all">
                <div className="flex-1">
                  <h4 className="font-bold text-white text-lg mb-1">{project.name}</h4>
                  <div className="flex items-center gap-2">
                    <span className="text-xs font-bold text-slate-500 uppercase tracking-widest">{project.requested_budget} € demandés</span>
                    <span className="h-1 w-1 bg-slate-700 rounded-full"></span>
                    <span className={`text-xs font-bold uppercase tracking-widest ${project.status === 'Approved' ? 'text-emerald-500' : project.status === 'Rejected' ? 'text-rose-500' : 'text-blue-500'}`}>
                      {project.status || 'En attente'}
                    </span>
                  </div>
                </div>

                {/* Actions : Modifier / Supprimer (Uniquement si en attente) */}
                <div className="flex items-center gap-2">
                  {(project.status === 'Pending' || !project.status) && (
                    <>
                      <button 
                        onClick={() => navigate(`/edit-project/${project.id}`)}
                        className="p-3 bg-slate-950 text-slate-400 hover:text-blue-400 border border-slate-800 rounded-xl transition-all"
                        title="Modifier"
                      >
                        <Pencil className="h-5 w-5" />
                      </button>
                      <button 
                        onClick={() => handleDelete(project.id)}
                        className="p-3 bg-slate-950 text-slate-400 hover:text-rose-500 border border-slate-800 rounded-xl transition-all"
                        title="Supprimer"
                      >
                        <Trash2 className="h-5 w-5" />
                      </button>
                    </>
                  )}
                  <div className="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    {project.status === 'Approved' ? <CheckCircle className="text-emerald-500" /> : 
                     project.status === 'Rejected' ? <XCircle className="text-rose-500" /> : 
                     <Clock className="text-blue-500" />}
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}