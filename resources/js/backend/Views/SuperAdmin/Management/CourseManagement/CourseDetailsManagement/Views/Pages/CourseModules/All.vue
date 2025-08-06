<template>
    <div class="course-modules-all">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-puzzle-piece mr-2"></i>
                    Course Modules
                </h5>
                <button @click="createNewModule" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>
                    Add Module
                </button>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="filters mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <select v-model="filters.milestone" @change="applyFilters" class="form-control">
                                <option value="">All Milestones</option>
                                <option v-for="milestone in milestones" :key="milestone.id" :value="milestone.id">
                                    {{ milestone.title }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select v-model="filters.status" @change="applyFilters" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input 
                                v-model="filters.search" 
                                @input="applyFilters"
                                type="text" 
                                class="form-control" 
                                placeholder="Search modules..."
                            >
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <!-- Modules Grid -->
                <div v-else-if="filteredModules.length > 0" class="modules-grid">
                    <div v-for="(module, index) in filteredModules" :key="module.id || index" class="module-card">
                        <div class="module-header">
                            <div class="module-icon">
                                <i class="fas fa-puzzle-piece"></i>
                            </div>
                            <div class="module-info">
                                <h6 class="module-title">{{ module.title }}</h6>
                                <p class="module-milestone" v-if="module.milestone">
                                    <small class="text-muted">
                                        Milestone: {{ module.milestone.title }}
                                    </small>
                                </p>
                            </div>
                            <div class="module-actions">
                                <span :class="'badge badge-' + (module.status === 'active' ? 'success' : 'secondary')">
                                    {{ module.status }}
                                </span>
                                <div class="action-buttons ml-2">
                                    <button @click="editModule(module)" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteModule(module.id, index)" class="btn btn-outline-danger btn-sm ml-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="module-content" v-if="module.description">
                            <p class="module-description">{{ module.description }}</p>
                        </div>
                        <div class="module-stats">
                            <div class="stat-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>{{ module.classes_count || 0 }} Classes</span>
                            </div>
                            <div class="stat-item" v-if="module.duration">
                                <i class="fas fa-clock"></i>
                                <span>{{ module.duration }} min</span>
                            </div>
                            <div class="stat-item" v-if="module.sequence">
                                <i class="fas fa-sort-numeric-up"></i>
                                <span>Order: {{ module.sequence }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="empty-state text-center py-5">
                    <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No modules found</h5>
                    <p class="text-muted">Create your first module to get started.</p>
                    <button @click="createNewModule" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Create First Module
                    </button>
                </div>
            </div>
        </div>

        <!-- Module Form Modal -->
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ isEditing ? 'Edit Module' : 'Create New Module' }}
                        </h5>
                        <button @click="closeModal" type="button" class="close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveModule">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="milestone_id">Milestone *</label>
                                        <select 
                                            id="milestone_id"
                                            v-model="currentModule.milestone_id"
                                            class="form-control"
                                            required
                                        >
                                            <option value="">Select Milestone</option>
                                            <option v-for="milestone in milestones" :key="milestone.id" :value="milestone.id">
                                                {{ milestone.title }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sequence">Sequence</label>
                                        <input 
                                            type="number" 
                                            id="sequence"
                                            v-model="currentModule.sequence"
                                            class="form-control"
                                            min="1"
                                        >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="title">Module Title *</label>
                                <input 
                                    type="text" 
                                    id="title"
                                    v-model="currentModule.title"
                                    class="form-control"
                                    required
                                >
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea 
                                    id="description"
                                    v-model="currentModule.description"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Describe what this module covers..."
                                ></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duration">Duration (minutes)</label>
                                        <input 
                                            type="number" 
                                            id="duration"
                                            v-model="currentModule.duration"
                                            class="form-control"
                                            min="1"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select 
                                            id="status"
                                            v-model="currentModule.status"
                                            class="form-control"
                                        >
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="learning_objectives">Learning Objectives</label>
                                <textarea 
                                    id="learning_objectives"
                                    v-model="currentModule.learning_objectives"
                                    class="form-control"
                                    rows="3"
                                    placeholder="What will students learn in this module?"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal" type="button" class="btn btn-secondary">Cancel</button>
                        <button @click="saveModule" type="button" class="btn btn-primary" :disabled="submitting">
                            <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                            {{ submitting ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseModulesAll',
    
    data() {
        return {
            loading: false,
            modules: [],
            milestones: [],
            showModal: false,
            isEditing: false,
            submitting: false,
            filters: {
                milestone: '',
                status: '',
                search: ''
            },
            currentModule: {
                title: '',
                description: '',
                milestone_id: '',
                sequence: '',
                duration: '',
                status: 'active',
                learning_objectives: ''
            }
        };
    },
    
    computed: {
        filteredModules() {
            let filtered = [...this.modules];
            
            if (this.filters.milestone) {
                filtered = filtered.filter(module => module.milestone_id == this.filters.milestone);
            }
            
            if (this.filters.status) {
                filtered = filtered.filter(module => module.status === this.filters.status);
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(module => 
                    module.title.toLowerCase().includes(search) ||
                    (module.description && module.description.toLowerCase().includes(search))
                );
            }
            
            return filtered.sort((a, b) => {
                if (a.milestone_id !== b.milestone_id) {
                    return a.milestone_id - b.milestone_id;
                }
                return (a.sequence || 0) - (b.sequence || 0);
            });
        }
    },
    
    async created() {
        await this.loadData();
    },
    
    methods: {
        async loadData() {
            await Promise.all([
                this.loadModules(),
                this.loadMilestones()
            ]);
        },
        
        async loadModules() {
            this.loading = true;
            try {
                const response = await axios.get(`course-modules?course_id=${this.$route.params.id}`);
                if (response.data && response.data.status === 'success') {
                    this.modules = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading modules:', error);
                window.s_error('Failed to load modules');
            } finally {
                this.loading = false;
            }
        },
        
        async loadMilestones() {
            try {
                const response = await axios.get(`course-milestones?course_id=${this.$route.params.id}`);
                if (response.data && response.data.status === 'success') {
                    this.milestones = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading milestones:', error);
            }
        },
        
        createNewModule() {
            this.isEditing = false;
            this.currentModule = {
                title: '',
                description: '',
                milestone_id: '',
                sequence: '',
                duration: '',
                status: 'active',
                learning_objectives: '',
                course_id: this.$route.params.id
            };
            this.showModal = true;
        },
        
        editModule(module) {
            this.isEditing = true;
            this.currentModule = { ...module };
            this.showModal = true;
        },
        
        async saveModule() {
            if (!this.currentModule.title.trim()) {
                window.s_warning('Please enter a module title');
                return;
            }
            
            if (!this.currentModule.milestone_id) {
                window.s_warning('Please select a milestone');
                return;
            }
            
            this.submitting = true;
            try {
                const moduleData = {
                    ...this.currentModule,
                    course_id: this.$route.params.id
                };
                
                let response;
                if (this.isEditing) {
                    response = await axios.post(`course-modules/update/${this.currentModule.slug}`, moduleData);
                } else {
                    response = await axios.post('course-modules/store', moduleData);
                }
                
                if (response.data && response.data.status === 'success') {
                    window.s_alert(this.isEditing ? 'Module updated successfully!' : 'Module created successfully!');
                    this.closeModal();
                    await this.loadModules();
                } else {
                    window.s_error('Failed to save module');
                }
            } catch (error) {
                console.error('Error saving module:', error);
                window.s_error('Failed to save module');
            } finally {
                this.submitting = false;
            }
        },
        
        async deleteModule(id, index) {
            if (!confirm('Are you sure you want to delete this module? This will also delete all classes in this module.')) {
                return;
            }
            
            try {
                if (id) {
                    const module = this.modules[index];
                    await axios.post(`course-modules/destroy/${module.slug}`);
                }
                this.modules.splice(index, 1);
                window.s_alert('Module deleted successfully!');
            } catch (error) {
                console.error('Error deleting module:', error);
                window.s_error('Failed to delete module');
            }
        },
        
        closeModal() {
            this.showModal = false;
            this.isEditing = false;
            this.currentModule = {
                title: '',
                description: '',
                milestone_id: '',
                sequence: '',
                duration: '',
                status: 'active',
                learning_objectives: ''
            };
        },
        
        applyFilters() {
            // Filters are automatically applied through computed property
        }
    },
    
    mounted() {
        console.log('CourseModulesAll component mounted');
    },
};
</script>

<style scoped>
.course-modules-all {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.filters {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
}

.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.module-card {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1.5rem;
    transition: all 0.15s ease-in-out;
    background-color: white;
}

.module-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.module-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.module-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.module-info {
    flex: 1;
}

.module-title {
    margin: 0 0 0.5rem 0;
    color: #495057;
    font-weight: 600;
}

.module-milestone {
    margin: 0;
}

.module-actions {
    display: flex;
    align-items: center;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.module-content {
    margin-bottom: 1rem;
}

.module-description {
    color: #6c757d;
    margin: 0;
    line-height: 1.5;
}

.module-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f8f9fa;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.stat-item i {
    color: #495057;
}

.empty-state {
    padding: 3rem 1rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.badge-success {
    background-color: #28a745;
}

.badge-secondary {
    background-color: #6c757d;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.modal {
    z-index: 1050;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

@media (max-width: 768px) {
    .modules-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .module-header {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .module-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .module-stats {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
