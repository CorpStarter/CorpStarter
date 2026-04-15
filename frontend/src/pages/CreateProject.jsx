import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { createProject } from '../api/projectService';
import { ArrowLeft, Send, Loader2, ImagePlus, UploadCloud } from 'lucide-react';
import { motion } from 'framer-motion';
import toast from 'react-hot-toast';

export default function CreateProject() {
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  
  const [formData, setFormData] = useState({ name: '', requested_budget: '', description: '' });
  const [imageFile, setImageFile] = useState(null);
  const [imagePreview, setImagePreview] = useState(null);

  const mutation = useMutation({
    mutationFn: (dataToSubmit) => createProject(dataToSubmit),
    onSuccess: () => {
      queryClient.invalidateQueries(['projects']);
      toast.success('Votre idée a été soumise avec succès !');
      navigate('/dashboard');
    },
    onError: () => {
      toast.error("Erreur lors de la création du projet.");
    }
  });

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImageFile(file);
      setImagePreview(URL.createObjectURL(file)); 
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const submitData = new FormData();
    submitData.append('name', formData.name);
    submitData.append('requested_budget', formData.requested_budget);
    submitData.append('description', formData.description);
    if (imageFile) {
      submitData.append('image', imageFile);
    } else {
      submitData.append('illustration_path', 'default.jpg');
    }
    mutation.mutate(submitData);
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-300 py-12 px-4 font-sans selection:bg-indigo-500/30">
      <div className="max-w-3xl mx-auto">
        <button onClick={() => navigate('/dashboard')} className="flex items-center text-slate-500 hover:text-indigo-400 mb-8 transition-colors font-bold text-sm uppercase tracking-wider">
          <ArrowLeft className="mr-2 h-4 w-4" /> Retour au Lab
        </button>
        
        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          className="bg-slate-900 border border-slate-800 rounded-3xl p-8 sm:p-12 shadow-2xl shadow-black/50 relative overflow-hidden"
        >
          <div className="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none" />

          <h2 className="text-4xl font-black text-white mb-3">Nouvelle Idée</h2>
          <p className="text-slate-400 mb-10 text-lg">Pitch ton projet. Ajoute une belle illustration. Convaincs l'équipe.</p>
          
          <form onSubmit={handleSubmit} className="space-y-8 relative z-10">
            
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Cover du projet</label>
              <div className="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div className="h-32 w-32 rounded-2xl bg-slate-950 border-2 border-dashed border-slate-700 hover:border-indigo-500 flex items-center justify-center overflow-hidden shrink-0 transition-colors relative group cursor-pointer">
                  {imagePreview ? (
                    <>
                      <img src={imagePreview} alt="Aperçu" className="h-full w-full object-cover group-hover:opacity-50 transition-opacity" />
                      <div className="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <UploadCloud className="h-8 w-8 text-white" />
                      </div>
                    </>
                  ) : (
                    <div className="flex flex-col items-center text-slate-600 group-hover:text-indigo-400 transition-colors">
                      <ImagePlus className="h-8 w-8 mb-2" />
                      <span className="text-[10px] uppercase font-bold">Upload</span>
                    </div>
                  )}
                  <input type="file" accept="image/*" onChange={handleImageChange} className="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                </div>
                <div className="flex-1">
                  <h4 className="text-white font-bold text-sm mb-1">Illustration attrayante</h4>
                  <p className="text-sm text-slate-500 mb-3 leading-relaxed">Une image vaut mille mots. Choisissez une image au format paysage (JPG, PNG) qui représente bien votre idée.</p>
                  <div className="relative inline-block">
                    <button type="button" className="bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold py-2 px-4 rounded-xl transition-colors border border-slate-700 hover:border-slate-600">
                      Parcourir les fichiers
                    </button>
                    <input type="file" accept="image/*" onChange={handleImageChange} className="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                  </div>
                </div>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Nom du projet</label>
                <input required type="text" placeholder="Ex: Machine à café premium..." className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner" value={formData.name} onChange={(e) => setFormData({...formData, name: e.target.value})} />
              </div>
              
              <div>
                <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Budget ciblé (€)</label>
                <input required type="number" placeholder="Ex: 850" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner font-mono text-lg" value={formData.requested_budget} onChange={(e) => setFormData({...formData, requested_budget: e.target.value})} />
              </div>
            </div>
            
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Le Pitch (Argumentaire)</label>
              <textarea required rows="6" placeholder="Expliquez à l'équipe pourquoi ce projet va améliorer le quotidien de l'entreprise..." className="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner resize-y leading-relaxed" value={formData.description} onChange={(e) => setFormData({...formData, description: e.target.value})} />
            </div>
            
            <motion.button 
              type="submit" 
              disabled={mutation.isPending} 
              whileHover={{ scale: 1.01, boxShadow: "0 0 25px rgba(99, 102, 241, 0.4)" }}
              whileTap={{ scale: 0.99 }}
              animate={mutation.isPending ? { scale: [1, 1.02, 1], opacity: [1, 0.8, 1], transition: { repeat: Infinity, duration: 1 } } : {}}
              className="w-full py-5 mt-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white rounded-xl font-black text-lg flex justify-center items-center transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-900/30"
            >
              {mutation.isPending ? (
                <>
                  <Loader2 className="animate-spin h-6 w-6 mr-3" /> Création en cours...
                </>
              ) : (
                <>
                  <Send className="mr-3 h-6 w-6" /> Envoyer le dossier au Lab
                </>
              )}
            </motion.button>
          </form>
        </motion.div>
      </div>
    </div>
  );
}