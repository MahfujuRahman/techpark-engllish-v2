<template>
    <div class="course-how-is-structured-details">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sitemap mr-2"></i>
                        Structure Item Details
                    </h5>
                    <div class="header-actions">
                        <router-link 
                            :to="{ name: 'CourseHowIsStructuredEdit', params: { id: $route.params.id, slug: $route.params.slug } }"
                            class="btn btn-sm btn-primary mr-2"
                        >
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </router-link>
                        <router-link 
                            :to="{ name: 'CourseHowIsStructuredAll', params: { id: $route.params.id } }"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back to List
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading structure item details...</p>
                </div>

                <div v-else-if="structureItem" class="structure-details">
                    <!-- Main Details Card -->
                    <div class="details-card">
                        <div class="details-header">
                            <div class="item-number">
                                {{ structureItem.serial || 'N/A' }}
                            </div>
                            <div class="item-title-section">
                                <h4 class="item-title">{{ structureItem.title }}</h4>
                                <div class="item-meta">
                                    <span :class="getStatusClass(structureItem.status)">
                                        {{ getStatusLabel(structureItem.status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Information -->
                    <div class="course-info-card">
                        <h6 class="section-title">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Course Information
                        </h6>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Course Title:</label>
                                <span>{{ currentCourse?.title || 'Loading...' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Course ID:</label>
                                <span>{{ structureItem.course_id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Details -->
                    <div class="technical-details-card">
                        <h6 class="section-title">
                            <i class="fas fa-cog mr-2"></i>
                            Technical Details
                        </h6>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Slug:</label>
                                <code>{{ structureItem.slug }}</code>
                            </div>
                            <div class="info-item">
                                <label>Created At:</label>
                                <span>{{ formatDate(structureItem.created_at) }}</span>
                            </div>
                            <div class="info-item">
                                <label>Last Updated:</label>
                                <span>{{ formatDate(structureItem.updated_at) }}</span>
                            </div>
                            <div class="info-item" v-if="structureItem.creator">
                                <label>Created By:</label>
                                <span>ID: {{ structureItem.creator }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="actions-section">
                        <div class="d-flex justify-content-between">
                            <router-link 
                                :to="{ name: 'CourseHowIsStructuredEdit', params: { id: $route.params.id, slug: $route.params.slug } }"
                                class="btn btn-primary"
                            >
                                <i class="fas fa-edit mr-1"></i>
                                Edit Structure Item
                            </router-link>
                            
                            <button 
                                @click="deleteItem"
                                class="btn btn-danger"
                                :disabled="deleting"
                            >
                                <i v-if="deleting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else class="fas fa-trash mr-1"></i>
                                {{ deleting ? 'Deleting...' : 'Delete Item' }}
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="text-muted">Structure Item Not Found</h5>
                        <p class="text-muted">The requested structure item could not be found.</p>
                        <router-link 
                            :to="{ name: 'CourseHowIsStructuredAll', params: { id: $route.params.id } }"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back to List
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

export default {
    name: 'CourseHowIsStructuredDetails',
    
    data() {
        return {
            loading: false,
            deleting: false,
            structureItem: null,
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        
        async loadItemDetails() {
            this.loading = true;
            try {
                const slug = this.$route.params.slug;
                const courseSlug = this.$route.params.id;
                
                // Load course details first
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }
                
                console.log('Loading structure item details for slug:', slug);
                const response = await axios.get(`course-how-is-structureds/${slug}`);
                console.log('Item details response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    this.structureItem = response.data.data;
                } else {
                    window.s_error('Failed to load structure item details');
                    this.goBack();
                }
            } catch (error) {
                console.error('Error loading structure item details:', error);
                window.s_error('Failed to load structure item details');
                this.goBack();
            } finally {
                this.loading = false;
            }
        },
        
        async deleteItem() {
            const confirmed = await window.s_confirm(
                'Are you sure you want to delete this structure item? This action cannot be undone.',
                'Yes, delete it!'
            );
            
            if (confirmed) {
                this.deleting = true;
                
                try {
                    const slug = this.$route.params.slug;
                    console.log('Deleting structure item with slug:', slug);
                    
                    await axios.post(`course-how-is-structureds/destroy/${slug}`);
                    
                    window.s_alert('Structure item deleted successfully!');
                    this.goBack();
                } catch (error) {
                    console.error('Error deleting structure item:', error);
                    window.s_error('Failed to delete structure item!');
                } finally {
                    this.deleting = false;
                }
            }
        },
        
        goBack() {
            this.$router.push({ 
                name: 'CourseHowIsStructuredAll', 
                params: { id: this.$route.params.id } 
            });
        },
        
        getStatusClass(status) {
            return {
                'badge badge-success': status === 'active',
                'badge badge-secondary': status === 'inactive',
            };
        },
        
        getStatusLabel(status) {
            return status === 'active' ? 'Active' : 'Inactive';
        },
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
    },
    
    async mounted() {
        await this.loadItemDetails();
    },
};
</script>

<style scoped>
.course-how-is-structured-details {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.card-header {
    border-bottom: 1px solid #e9ecef;
    padding: 20px;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.header-actions .btn {
    border-radius: 6px;
    font-weight: 500;
    color: #fff;
}

.structure-details {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.details-card,
.course-info-card,
.technical-details-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 25px;
}

.details-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

.item-number {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.item-title-section {
    flex: 1;
}

.item-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 24px;
    line-height: 1.3;
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 15px;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label {
    font-weight: 600;
    color: #6c757d;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span,
.info-item code {
    color: #fff;
    font-size: 16px;
}

.info-item code {
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
}

.badge {
    font-size: 12px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 20px;
}

.badge-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.actions-section {
    border-radius: 8px;
    padding: 25px;
    border: 1px solid #e9ecef;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 12px 24px;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
    transform: translateY(-1px);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.empty-state {
    padding: 40px 20px;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}

.text-muted {
    color: #6c757d !important;
}

@media (max-width: 768px) {
    .details-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-section .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .actions-section .btn {
        width: 100%;
    }
    
    .header-actions {
        margin-top: 15px;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
