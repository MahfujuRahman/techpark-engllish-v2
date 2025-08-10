<template>
    <div class="course-for-whom-all">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list mr-2"></i>
                        Course For Whom - All Items
                    </h5>
                    <div class="header-actions">
                        <router-link 
                            :to="{ name: 'CourseForWhomCreate' }"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            Add New Item
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading for whom items...</p>
                </div>

                <div v-else>
                    <!-- For Whom Items List -->
                    <div v-if="forWhomItems.length > 0" class="for-whom-items">
                        <div v-for="(item, index) in forWhomItems" :key="item.id" class="for-whom-item">
                            <div class="item-content">
                                <div class="item-number">
                                    {{ index + 1 }}
                                </div>
                                <div class="item-icon">
                                    <i class="fas fa-users text-success"></i>
                                </div>
                                <div class="item-details">
                                    <h6 class="item-title">{{ item.title }}</h6>
                                    <p class="item-description">{{ item.description }}</p>
                                    <div class="item-meta">
                                        <span :class="getStatusClass(item.status)">{{ getStatusLabel(item.status) }}</span>
                                        <small class="ml-2">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ formatDate(item.created_at) }}
                                        </small>
                                    </div>
                                </div>
                                <div class="item-actions">
                                    <router-link 
                                        :to="{ name: 'CourseForWhomDetails', params: { slug: item.slug } }"
                                        class="btn btn-sm btn-outline-info"
                                        title="View Details"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                    <router-link 
                                        :to="{ name: 'CourseForWhomEdit', params: { slug: item.slug } }"
                                        class="btn btn-sm btn-outline-primary ml-1"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                    <button 
                                        @click="deleteItem(item)"
                                        class="btn btn-sm btn-outline-danger ml-1"
                                        title="Delete"
                                        :disabled="deleting"
                                    >
                                        <i v-if="deleting && deletingId === item.id" class="fas fa-spinner fa-spin"></i>
                                        <i v-else class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="empty-state text-center py-5">
                        <div class="empty-icon mb-3">
                            <i class="fas fa-users fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted">No Target Audience Found</h5>
                        <p class="text-muted mb-4">
                            Start by adding who this course is designed for.
                        </p>
                        <router-link 
                            :to="{ name: 'CourseForWhomCreate' }"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            Add First Target Audience
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
    name: 'CourseForWhomAll',
    
    data() {
        return {
            loading: false,
            deleting: false,
            deletingId: null,
            forWhomItems: [],
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        
        async loadForWhomItems() {
            const courseSlug = this.$route.params.id; // Course slug comes from parent route
            if (!courseSlug) return;

            this.loading = true;
            try {
                // First ensure we have the current course loaded
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                console.log('Fetching for whom items for course ID:', courseId);
                const response = await axios.get(`course-for-whoms?course_id=${courseId}`);

                console.log('API Response:', response.data);

                // Check if response has the expected structure
                if (response.data && response.data.status === 'success') {
                    this.forWhomItems = response.data.data?.data || [];
                    console.log('Loaded for whom items:', this.forWhomItems);
                } else {
                    console.error('Unexpected response structure:', response.data);
                    this.forWhomItems = [];
                }
            } catch (error) {
                console.error('Error fetching for whom items:', error);
                window.s_error('Failed to load for whom items!');
                this.forWhomItems = [];
            } finally {
                this.loading = false;
            }
        },
        
        async deleteItem(item) {
            const confirmed = await window.s_confirm(
                'Are you sure you want to delete this for whom item?',
                'Yes, delete it!'
            );
            
            if (confirmed) {
                this.deleting = true;
                this.deletingId = item.id;
                
                try {
                    const itemSlug = item.slug;
                    console.log('Deleting for whom item with slug:', itemSlug);
                    
                    await axios.post(`course-for-whoms/destroy/${itemSlug}`);
                    
                    window.s_alert('Target audience item deleted successfully!');
                    await this.loadForWhomItems(); // Reload the list
                } catch (error) {
                    console.error('Error deleting for whom item:', error);
                    window.s_error('Failed to delete target audience item!');
                } finally {
                    this.deleting = false;
                    this.deletingId = null;
                }
            }
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
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },
    },
    
    async mounted() {
        await this.loadForWhomItems();
    },
};
</script>

<style scoped>
.course-for-whom-all {
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
}

.for-whom-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.for-whom-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.for-whom-item:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.1);
}

.item-content {
    display: flex;
    align-items: center;
    padding: 20px;
    gap: 15px;
}

.item-number {
    background: #28a745;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.item-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.item-details {
    flex: 1;
}

.item-title {
    color: #fff;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 16px;
}

.item-description {
    color: #fff;
    margin-bottom: 8px;
    line-height: 1.4;
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge {
    font-size: 11px;
    font-weight: 500;
    padding: 4px 8px;
}

.item-actions {
    display: flex;
    gap: 5px;
    flex-shrink: 0;
}

.item-actions .btn {
    border-radius: 6px;
    padding: 6px 10px;
}

.empty-state {
    border-radius: 8px;
    margin: 20px 0;
}

.empty-icon {
    opacity: 0.5;
}

.text-muted {
    color: #6c757d !important;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}

@media (max-width: 768px) {
    .item-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .item-actions {
        width: 100%;
        justify-content: center;
    }
    
    .header-actions {
        margin-top: 10px;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>