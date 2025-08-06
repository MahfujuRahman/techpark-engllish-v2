<template>
    <div class="course-milestone-all">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-flag mr-2"></i>
                    Course Milestones
                </h5>
                <button @click="createNewMilestone" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>
                    Add Milestone
                </button>
            </div>
            <div class="card-body">
                <!-- Loading State -->
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <!-- Milestones List -->
                <div v-else-if="milestones.length > 0" class="milestones-list">
                    <div v-for="(milestone, index) in milestones" :key="milestone.id || index" class="milestone-item">
                        <div class="milestone-header">
                            <div class="milestone-info">
                                <span class="milestone-number">{{ index + 1 }}</span>
                                <div class="milestone-details">
                                    <h6 class="milestone-title">{{ milestone.title }}</h6>
                                    <p class="milestone-description">{{ milestone.description || 'No description' }}</p>
                                </div>
                            </div>
                            <div class="milestone-actions">
                                <span :class="'badge badge-' + (milestone.status === 'active' ? 'success' : 'secondary')">
                                    {{ milestone.status }}
                                </span>
                                <button @click="editMilestone(milestone)" class="btn btn-outline-primary btn-sm ml-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteMilestone(milestone.id, index)" class="btn btn-outline-danger btn-sm ml-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="milestone-meta">
                            <small class="text-muted">
                                Serial: {{ milestone.serial || index + 1 }} | 
                                Modules: {{ milestone.modules ? milestone.modules.length : 0 }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="empty-state text-center py-5">
                    <i class="fas fa-flag fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No milestones found</h5>
                    <p class="text-muted">Create your first milestone to get started.</p>
                    <button @click="createNewMilestone" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Create First Milestone
                    </button>
                </div>
            </div>
        </div>

        <!-- Milestone Form Modal -->
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ isEditing ? 'Edit Milestone' : 'Create New Milestone' }}
                        </h5>
                        <button @click="closeModal" type="button" class="close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveMilestone">
                            <div class="form-group">
                                <label for="title">Milestone Title *</label>
                                <input 
                                    type="text" 
                                    id="title"
                                    v-model="currentMilestone.title"
                                    class="form-control"
                                    required
                                >
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea 
                                    id="description"
                                    v-model="currentMilestone.description"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Describe this milestone..."
                                ></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="serial">Serial Number</label>
                                        <input 
                                            type="number" 
                                            id="serial"
                                            v-model="currentMilestone.serial"
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
                                            v-model="currentMilestone.status"
                                            class="form-control"
                                        >
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal" type="button" class="btn btn-secondary">Cancel</button>
                        <button @click="saveMilestone" type="button" class="btn btn-primary" :disabled="submitting">
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
    name: 'CourseMilestoneAll',
    
    data() {
        return {
            loading: false,
            milestones: [],
            showModal: false,
            isEditing: false,
            submitting: false,
            currentMilestone: {
                title: '',
                description: '',
                serial: '',
                status: 'active'
            }
        };
    },
    
    async created() {
        await this.loadMilestones();
    },
    
    methods: {
        async loadMilestones() {
            this.loading = true;
            try {
                const response = await axios.get(`course-milestones?course_id=${this.$route.params.id}`);
                if (response.data && response.data.status === 'success') {
                    this.milestones = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading milestones:', error);
                window.s_error('Failed to load milestones');
            } finally {
                this.loading = false;
            }
        },
        
        createNewMilestone() {
            this.isEditing = false;
            this.currentMilestone = {
                title: '',
                description: '',
                serial: this.milestones.length + 1,
                status: 'active',
                course_id: this.$route.params.id
            };
            this.showModal = true;
        },
        
        editMilestone(milestone) {
            this.isEditing = true;
            this.currentMilestone = { ...milestone };
            this.showModal = true;
        },
        
        async saveMilestone() {
            if (!this.currentMilestone.title.trim()) {
                window.s_warning('Please enter a milestone title');
                return;
            }
            
            this.submitting = true;
            try {
                const milestoneData = {
                    ...this.currentMilestone,
                    course_id: this.$route.params.id
                };
                
                let response;
                if (this.isEditing) {
                    response = await axios.post(`course-milestones/update/${this.currentMilestone.slug}`, milestoneData);
                } else {
                    response = await axios.post('course-milestones/store', milestoneData);
                }
                
                if (response.data && response.data.status === 'success') {
                    window.s_alert(this.isEditing ? 'Milestone updated successfully!' : 'Milestone created successfully!');
                    this.closeModal();
                    await this.loadMilestones();
                } else {
                    window.s_error('Failed to save milestone');
                }
            } catch (error) {
                console.error('Error saving milestone:', error);
                window.s_error('Failed to save milestone');
            } finally {
                this.submitting = false;
            }
        },
        
        async deleteMilestone(id, index) {
            if (!confirm('Are you sure you want to delete this milestone?')) {
                return;
            }
            
            try {
                if (id) {
                    const milestone = this.milestones[index];
                    await axios.post(`course-milestones/destroy/${milestone.slug}`);
                }
                this.milestones.splice(index, 1);
                window.s_alert('Milestone deleted successfully!');
            } catch (error) {
                console.error('Error deleting milestone:', error);
                window.s_error('Failed to delete milestone');
            }
        },
        
        closeModal() {
            this.showModal = false;
            this.isEditing = false;
            this.currentMilestone = {
                title: '',
                description: '',
                serial: '',
                status: 'active'
            };
        }
    }
};
</script>

<style scoped>
.course-milestone-all {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.milestones-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.milestone-item {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1.5rem;
    transition: box-shadow 0.15s ease-in-out;
}

.milestone-item:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.milestone-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.milestone-info {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    flex: 1;
}

.milestone-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.milestone-details {
    flex: 1;
}

.milestone-title {
    margin: 0 0 0.5rem 0;
    color: #495057;
    font-weight: 600;
}

.milestone-description {
    margin: 0;
    color: #6c757d;
    line-height: 1.5;
}

.milestone-actions {
    display: flex;
    align-items: center;
}

.milestone-meta {
    border-top: 1px solid #f8f9fa;
    padding-top: 1rem;
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
    .milestone-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .milestone-actions {
        width: 100%;
        justify-content: space-between;
    }
}
</style>
