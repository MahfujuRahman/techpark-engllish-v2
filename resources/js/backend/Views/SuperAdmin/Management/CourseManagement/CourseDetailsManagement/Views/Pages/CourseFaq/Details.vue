<template>
    <div class="course-for-whom-details">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                     Course For Why You Learn From Us
                </h5>
                <div class="btn-group" role="group">
                    <button 
                        type="button" 
                        class="btn btn-sm btn-primary"
                        @click="editItem"
                        :disabled="loading"
                    >
                        <i class="fas fa-edit me-1"></i>
                        Edit
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-sm btn-danger"
                        @click="confirmDelete"
                        :disabled="loading"
                    >
                        <i class="fas fa-trash me-1"></i>
                        Delete
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading item details...</p>
                </div>

                <div v-else-if="item" class="details-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Title:</label>
                                <div class="detail-value">{{ item.title || 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Status:</label>
                                <div class="detail-value">
                                     <span :class="['badge', item.status === 'active' ? 'bg-success' : 'bg-danger']">
                                        {{ item.status === 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                
                    <div class="row mt-3" v-if="item.description">
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="detail-label">Description:</label>
                                <div class="detail-value description">
                                    {{ item.description }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Created At:</label>
                                <div class="detail-value">{{ formatDate(item.created_at) }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Updated At:</label>
                                <div class="detail-value">{{ formatDate(item.updated_at) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-4">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        No item details found.
                    </div>
                </div>

                <div class="form-actions mt-4">
                    <button 
                        type="button" 
                        class="btn btn-secondary"
                        @click="goBack"
                    >
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to List
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';
export default {
    name: 'CourseFaqDetails',
    
    data() {
        return {
            item: null,
            loading: true
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse'])
    },

    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),

        async loadItemDetails() {
            try {
                const itemSlug = this.$route.params.slug;
                
                const response = await axios.get(`course-faqs/${itemSlug}`);
                
                if (response.data.status === 'success') {
                    this.item = response.data.data;
                } else {
                    window.s_error('Failed to load item details!');
                    this.goBack();
                }
            } catch (error) {
                console.error('Error loading item details:', error);
                window.s_error('Failed to load item details!');
                this.goBack();
            } finally {
                this.loading = false;
            }
        },

        editItem() {
            this.$router.push({
                name: 'CourseFaqEdit',
                params: { 
                    slug: this.$route.params.slug
                }
            });
        },

        async confirmDelete() {
            const result = await window.s_confirm(
                'Delete Confirmation',
                'Are you sure you want to delete this item? This action cannot be undone.',
                'warning'
            );

            if (result.isConfirmed) {
                await this.deleteItem();
            }
        },

        async deleteItem() {
            try {
                const itemSlug = this.$route.params.slug;
                
                const response = await axios.post(`course-faqs/destroy/${itemSlug}`);
                
                if (response.data.status === 'success') {
                    window.s_alert('Why learn item deleted successfully!');
                    this.goBack();
                } else {
                    window.s_error('Failed to delete item!');
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                window.s_error('Failed to delete item!');
            }
        },

        goBack() {
            this.$router.push({
                name: 'CourseFaq'
            });
        },

        async loadCourseDetails() {
            const courseSlug = this.$route.params.id; // Course slug from parent route
            if (courseSlug) {
                await this.getCourseDetails(courseSlug);
            }
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    },
    
    async mounted() {
        console.log('CourseFaqDetails component mounted');
        await this.loadCourseDetails();
        await this.loadItemDetails();
    },
};
</script>

<style scoped>
.course-for-whom-details {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    padding: 1rem 1.25rem;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}

.btn-group .btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.details-content {
    border-radius: 0.5rem;
    padding: 1.5rem;
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
    display: block;
    font-size: 0.875rem;
}

.detail-value {
    color: #fff;
    font-size: 1rem;
    line-height: 1.5;
}

.detail-value.description {
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

.bg-success {
    background-color: #10b981 !important;
}

.bg-danger {
    background-color: #ef4444 !important;
}

.bg-secondary {
    background-color: #6b7280 !important;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
}

.btn-danger {
    background-color: #ef4444;
    border-color: #ef4444;
}

.btn-danger:hover {
    background-color: #dc2626;
    border-color: #dc2626;
}

.btn-secondary {
    background-color: #6b7280;
    border-color: #6b7280;
}

.btn-secondary:hover {
    background-color: #4b5563;
    border-color: #4b5563;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.alert-warning {
    background-color: #fef3c7;
    border-color: #f59e0b;
    color: #92400e;
}

.form-actions {
    border-top: 1px solid #e5e7eb;
    padding-top: 1rem;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .btn-group {
        margin-top: 0.5rem;
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
    
    .details-content {
        padding: 1rem;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>